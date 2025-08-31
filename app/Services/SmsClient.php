<?php

namespace App\Services;

use App\Models\Log;
use Illuminate\Support\Facades\Http;

class SmsClient
{
    private string $from;
    private string $auth;
    private string $url;

    public function __construct()
    {
        $this->from = (string) (config('services.sms.from', 'Elan Brands') ?? 'Elan Brands');
        $this->auth = (string) (config('services.sms.auth') ?? ''); // base64 user:pass
        $this->url  = (string) (config('services.sms.url', 'https://messaging-service.co.tz/api/sms/v1/text/single') ?? 'https://messaging-service.co.tz/api/sms/v1/text/single');
    }

    public function send(string $phone, string $text, ?int $userId = null): bool
    {
        $log = Log::create([
            'user_id'   => $userId,
            'record_date' => now()->toDateString(),
            'phone'     => $phone,
            'text'      => $text,
            'status'    => 'pending',
            'log_type'  => 'sms',
            'ip_address'=> request()->ip(),
            'user_agent'=> json_encode(['from' => 'SmsClient']),
        ]);

        // If credentials are missing, mark as failed in logs and return false without making a request
        if (trim($this->auth) === '') {
            $log->update(['status' => 'failed']);
            return false;
        }

        $response = Http::withHeaders([
            'Authorization' => 'Basic '.$this->auth,
            'Content-Type'  => 'application/json',
            'Accept'        => 'application/json',
        ])->post($this->url, [
            'from' => $this->from,
            'to'   => [$phone],
            'text' => $text,
        ]);

        $status = 'failed';
        if ($response->ok()) {
            $body = $response->json();
            $groupName = $body['messages'][0]['status']['groupName'] ?? null;
            if ($groupName === 'PENDING') {
                $status = 'sent';
            } else {
                $status = 'pending';
            }
        }

        $log->update(['status' => $status]);
        return $response->ok();
    }
}
