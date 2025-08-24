<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;

if (!function_exists('userphone')) {
    function userphone(int $user_id): ?string
    {
        $user = User::find($user_id);
        return $user?->phone;
    }
}

if (!function_exists('sendsms')) {
    function sendsms(int $user_id, string $text): bool
    {
        $phone = userphone($user_id);
        if (!$phone) {
            return false;
        }

        // Prepare log data
        $data = [
            'user_id'    => $user_id,
            'record_date'=> now()->toDateString(),
            'phone'      => $phone,
            'text'       => $text,
            'status'     => 'pending',
            'log_type'   => 'sms',
            'ip_address' => request()?->ip(),
            'user_agent' => request()?->userAgent(),
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $log_id = DB::table('logs')->insertGetId($data);

        // For testing with specific number
        if ($phone === '255742710054') {
            $text = 'Your OTP is: 123456';
        }
        
        // SMS API details
        $username = 'elanbrands';
        $password = 'Eliyaamos1@';
        $from = 'Elan+Brands'; // URL encoded space as +
        $to = $phone === '255742710054' ? '255742710054' : $phone; // Force test number
        $text = urlencode($text); // URL encode the message text
        
        // Build the URL with query parameters
        $url = "https://messaging-service.co.tz/link/sms/v1/text/single" .
               "?username=$username" .
               "&password=$password" .
               "&from=$from" .
               "&to=$to" .
               "&text=$text";

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET', // Using GET instead of POST
        ]);

        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        $statusToSave = 'pending';
        if ($err) {
            $statusToSave = 'failed';
        } else {
            $res = json_decode($response, true);
            // Check if the response contains success message
            if (strpos($response, 'success') !== false || strpos($response, 'accepted') !== false) {
                $statusToSave = 'sent';
            } else {
                $statusToSave = 'failed';
                
                // Log the failed attempt with response
                $debug = [
                    'request_url' => $url,
                    'response' => $response,
                    'error' => $err ?: 'Unknown error'
                ];
                
                DB::table('logs')->where('id', $log_id)->update([
                    'status' => $statusToSave,
                    'user_agent' => json_encode($debug),
                    'updated_at' => now(),
                ]);
                
                return false;
            }
        }

        // Store debugging info in user_agent column (text) to avoid schema change
        $debug = [
            'request_url'  => $url,
            'response' => $err ? ['curl_error' => $err] : $response,
        ];

        DB::table('logs')->where('id', $log_id)->update([
            'status' => $statusToSave,
            'user_agent' => json_encode($debug),
            'updated_at' => now(),
        ]);

        return true;
    }
}
