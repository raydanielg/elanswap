<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SelcomClient
{
    private string $base;
    private string $appId;

    public function __construct()
    {
        $this->base = rtrim(config('services.selcom.base', 'https://elan.co.tz/api/payments/selcom'), '/');
        $this->appId = (string) config('services.selcom.app_id', '104');
    }

    private function url(string $path): string
    {
        return $this->base.'/'.ltrim($path, '/');
    }

    /**
     * Create MNO order via Selcom API
     */
    public function createMnoOrder(array $data): array
    {
        $payload = [
            'app_id'              => $this->appId,
            'order_firstname'     => (string) ($data['username'] ?? ''),
            'order_lastname'      => 'Customer',
            'order_email'         => 'info@elanbrands.net',
            'order_phone'         => (string) ($data['phone'] ?? ''),
            'amount'              => (string) ($data['amount'] ?? ''),
            'order_id'            => (string) ($data['order_id'] ?? ''),
            'currency'            => 'TZS',
            'order_item_cont'     => 1,
            'service_name'        => 'subscription',
            'is_reference_payment'=> 1,
        ];

        $verify = config('services.selcom.ca_path') ?: config('services.selcom.verify', true);
        $res = Http::withOptions(['verify' => $verify])
            ->asForm()
            ->post($this->url('api/v1/create_mno_order'), $payload);
        if (!$res->ok()) {
            return ['ok' => false, 'message' => 'Selcom error', 'http' => $res->status(), 'body' => $res->body()];
        }
        return ['ok' => true, 'data' => $res->json()];
    }

    /**
     * Initiate USSD push
     */
    public function initiatePushUssd(string $phone, string $orderId): array
    {
        $payload = [
            'project_id'          => $this->appId,
            'phone'               => $phone,
            'order_id'            => $orderId,
            'is_reference_payment'=> 0,
        ];
        $verify = config('services.selcom.ca_path') ?: config('services.selcom.verify', true);
        $res = Http::withOptions(['verify' => $verify])
            ->asForm()
            ->post($this->url('initiatePushUSSD'), $payload);
        if (!$res->ok()) {
            return ['ok' => false, 'message' => 'Selcom error', 'http' => $res->status(), 'body' => $res->body()];
        }
        return ['ok' => true, 'data' => $res->json()];
    }
}
