<?php

use Illuminate\Support\Facades\DB;
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

        // Light phone normalization (safety): keep digits and format to 255XXXXXXXXX
        $digits = preg_replace('/\D+/', '', $phone);
        if (str_starts_with($digits, '0') && strlen($digits) === 10) {
            $digits = '255' . substr($digits, 1);
        } elseif (strlen($digits) === 9) {
            $digits = '255' . $digits;
        } elseif (str_starts_with($digits, '255') && strlen($digits) >= 12) {
            $digits = substr($digits, 0, 12);
        }
        $to = $digits;

        // Prepare base log row
        $log_id = DB::table('logs')->insertGetId([
            'user_id'    => $user_id,
            'record_date'=> now()->toDateString(),
            'phone'      => $to ?: $phone,
            'text'       => $text,
            'status'     => 'pending',
            'log_type'   => 'sms',
            'ip_address' => request()?->ip(),
            'user_agent' => request()?->userAgent(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ENV
        $username = env('SMS_USERNAME', 'elanbrands');
        $password = env('SMS_PASSWORD', 'Eliyaamos1@');
        $from     = env('SMS_FROM', 'Elan Brands');
        $baseUrl  = env('SMS_LINK_BASE', 'https://messaging-service.co.tz/link/sms/v1/text/single');
        $sslVerify = filter_var(env('SMS_SSL_VERIFY', true), FILTER_VALIDATE_BOOLEAN);
        $caFile    = env('SMS_CAFILE'); // optional absolute path to CA bundle

        // Single attempt: API JSON POST with Basic Auth (as per provided function)
        $apiUrl = 'https://messaging-service.co.tz/api/sms/v1/text/single';
        $payloadArr = [
            'from' => $from,
            'to'   => [$to],
            'text' => $text,
        ];
        $payload = json_encode($payloadArr);
        $auth    = 'Authorization: Basic ' . base64_encode($username . ':' . $password);

        $attempts = [];
        $finalStatus = 'pending';

        $ch = curl_init();
        $curlOpts = [
            CURLOPT_URL => $apiUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => [
                $auth,
                'Content-Type: application/json',
                'Accept: application/json',
            ],
        ];
        if ($sslVerify) {
            $curlOpts[CURLOPT_SSL_VERIFYPEER] = true;
            $curlOpts[CURLOPT_SSL_VERIFYHOST] = 2;
            if ($caFile) { $curlOpts[CURLOPT_CAINFO] = $caFile; }
        } else {
            $curlOpts[CURLOPT_SSL_VERIFYPEER] = false;
            $curlOpts[CURLOPT_SSL_VERIFYHOST] = 0;
        }
        curl_setopt_array($ch, $curlOpts);
        $response = curl_exec($ch);
        $err      = curl_error($ch);
        $info     = curl_getinfo($ch);
        curl_close($ch);
        $httpCode = $info['http_code'] ?? null;

        // Parse response like the provided function
        $groupName = null;
        if (!$err && $response) {
            $decoded = json_decode($response, true);
            $groupName = $decoded['messages'][0]['status']['groupName'] ?? null;
            if ($groupName === 'PENDING') {
                $finalStatus = 'sent';
            } elseif ($groupName !== null) {
                $finalStatus = 'pending';
            }
        }

        $attempts[] = [
            'channel'   => 'api_post',
            'request'   => ['url' => $apiUrl, 'payload' => $payloadArr],
            'http_code' => $httpCode,
            'error'     => $err ?: null,
            'response'  => $response,
            'parsed'    => ['groupName' => $groupName],
        ];

        DB::table('logs')->where('id', $log_id)->update([
            'status'     => $finalStatus,
            'user_agent' => json_encode([
                'attempts' => $attempts,
                'final'    => $finalStatus,
                'ssl'      => [
                    'verify' => $sslVerify,
                    'caFile' => $caFile,
                ],
            ]),
            'updated_at' => now(),
        ]);

        return $finalStatus === 'sent';
    }
}
