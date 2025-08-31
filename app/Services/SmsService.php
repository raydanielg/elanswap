<?php

namespace App\Services;

use App\Models\Log as LogModel;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Http;

class SmsService
{
    /**
     * Send an SMS message
     *
     * @param int $userId
     * @param string $message
     * @return bool
     */
    public function sendSms($userId, $message)
    {
        try {
            $user = User::findOrFail($userId);
            $phone = $user->phone;
            
            // Log the SMS
            $log = LogModel::create([
                'user_id' => $userId,
                'record_date' => now(),
                'phone' => $phone,
                'text' => $message,
                'status' => 'pending',
                'log_type' => 'sms',
                'ip_address' => Request::ip(),
                'user_agent' => Request::userAgent(),
            ]);

            // Send the SMS (config-driven)
            $base = rtrim((string) config('services.sms.base_url', 'https://messaging-service.co.tz'), '/');
            $endpoint = $base . '/api/sms/v1/text/single';
            $from = (string) config('services.sms.from', 'Elan Brands');
            $auth = (string) config('services.sms.auth', '');

            $res = Http::withHeaders([
                    'Authorization' => $auth,
                    'Accept' => 'application/json',
                ])
                ->asJson()
                ->timeout((int) config('services.sms.timeout', 15))
                ->post($endpoint, [
                    'from' => $from,
                    'to'   => [$phone],
                    'text' => $message,
                ]);

            $httpCode = $res->status();
            $responseData = $res->json();
            // Default to 'pending' like your reference implementation
            $status = 'pending';

            if ($httpCode === 200 && isset($responseData['messages'][0]['status']['groupName'])) {
                $groupName = $responseData['messages'][0]['status']['groupName'];
                // Mark as 'sent' only when groupName is exactly 'PENDING', otherwise keep 'pending'
                $status = ($groupName === 'PENDING') ? 'sent' : 'pending';
            }

            // Update the log status
            $log->update(['status' => $status]);

            // Match the provided sendsms() behavior: always return true
            return true;
        } catch (\Exception $e) {
            Log::error('SMS sending failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send OTP verification SMS
     *
     * @param int $userId
     * @param string $otp
     * @return bool
     */
    public function sendOtpVerification($userId, $otp)
    {
        $user = User::findOrFail($userId);
        $message = "Your ElanSwap verification code is: {$otp}. This code will expire in 15 minutes.";
        
        return $this->sendSms($userId, $message);
    }

    /**
     * Send welcome message after successful registration
     *
     * @param int $userId
     * @param string $password
     * @return bool
     */
    public function sendWelcomeMessage($userId, $password)
    {
        $user = User::findOrFail($userId);
        $message = "Welcome to ElanSwap! Your account has been created successfully. " .
                  "Username: {$user->phone}, Password: {$password}. " .
                  "Please keep your login details secure.";
        
        return $this->sendSms($userId, $message);
    }
}
