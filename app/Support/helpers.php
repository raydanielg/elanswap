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

        // SMS API details
        $from = 'Elan Brands';
        $to   = $phone; // must be in international format e.g., 2557XXXXXXXX
        $auth = 'Basic ZWxhbmJyYW5kczpFbGl5YWFtb3MxQA==';
        $url  = 'https://messaging-service.co.tz/api/sms/v1/text/single';

        $payloadArray = [
            'from' => $from,
            'to'   => [$to],
            'text' => $text,
        ];
        $payload = json_encode($payloadArray);

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => [
                'Authorization: ' . $auth,
                'Content-Type: application/json',
                'Accept: application/json',
            ],
        ]);

        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        $statusToSave = 'pending';
        if ($err) {
            $statusToSave = 'failed';
        } else {
            $res = json_decode($response, true);
            $groupName = $res['messages'][0]['status']['groupName'] ?? null;
            if ($groupName === 'PENDING') {
                $statusToSave = 'sent';
            } elseif ($groupName) {
                $statusToSave = 'pending';
            } else {
                $statusToSave = 'failed';
            }
        }

        // Store debugging info in user_agent column (text) to avoid schema change
        $debug = [
            'request'  => $payloadArray,
            'response' => $err ? ['curl_error' => $err] : json_decode($response, true),
        ];

        DB::table('logs')->where('id', $log_id)->update([
            'status' => $statusToSave,
            'user_agent' => json_encode($debug),
            'updated_at' => now(),
        ]);

        return true;
    }
}
