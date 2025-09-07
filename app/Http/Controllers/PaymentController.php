<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    /**
     * Show payment options and status.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $amount = (int) (config('services.elanswap.payment_amount', 2500)); // TZS
        $latest = $user->payments()->latest('id')->first();
        return view('payment.index', compact('user','latest','amount'));
    }

    /**
     * Initiate payment by automatically sending push notification to user's phone.
     */
    public function pay(Request $request)
    {
        $user = $request->user();

        // Automatically trigger push payment
        $pushRequest = new Request([
            'phone' => $request->string('phone'),
        ]);
        $pushRequest->setUserResolver(function() use ($user) {
            return $user;
        });

        // Call the requestPush method directly and return its JSON response
        return $this->requestPush($pushRequest);
    }

    /**
     * Initiate a push payment via Selcom APIs (create_mno_order + initiatePushUSSD).
     * POST /payment/push
     */
    public function requestPush(Request $request)
    {
        // Force the phone input to be treated as a string before validation
        $request->merge(['phone' => (string) $request->input('phone')]);

        $request->validate([
            'phone'  => ['required', 'string', 'min:9', 'max:15'],
        ]);

        $user = $request->user();
        $amount = (int) (config('services.elanswap.payment_amount', 2500));

        // Normalize phone to TZ E.164 without plus (e.g., 2557XXXXXXXX)
        $rawPhone = preg_replace('/[^0-9+]/', '', (string) $request->string('phone'));
        $phone = $rawPhone;
        if (str_starts_with($rawPhone, '+')) {
            $rawPhone = substr($rawPhone, 1);
        }
        if (str_starts_with($rawPhone, '0')) {
            $phone = '255'.substr($rawPhone, 1);
        } elseif (str_starts_with($rawPhone, '255')) {
            $phone = $rawPhone;
        } else {
            // Fallback: assume local without leading zero
            $phone = '255'.$rawPhone;
        }

        // Use cURL implementation based on user's script
        $orderId = 'ORD_' . now()->format('YmdHis') . '_' . $user->id;
        $appId = (string) config('services.selcom.app_id', '104');
        $apiUrl = rtrim((string) config('services.selcom.base_url'), '/');
        $timeout = (int) config('services.selcom.timeout', 15);
        $verify = (bool) config('services.selcom.verify', true);
        $caPath = (string) (config('services.selcom.ca_path') ?? '');

        \Log::info('PAYMENT: Preparing create_mno_order', [
            'order_id' => $orderId,
            'app_id' => $appId,
            'phone' => $phone,
            'amount' => $amount,
            'api_url' => $apiUrl,
        ]);

        // 1. Create MNO order
        $createPayload = http_build_query([
            'app_id'             => $appId,
            'order_firstname'    => $user->name ?? 'Customer',
            'order_lastname'     => 'Customer',
            'order_email'        => $user->email ?? 'info@elanbrands.net',
            'order_phone'        => $phone,
            'amount'             => $amount,
            'order_id'           => $orderId,
            'currency'           => 'TZS',
            'order_item_cont'    => 1,
            'service_name'       => 'subscription',
            'is_reference_payment' => 1
        ]);

        $ch = curl_init($apiUrl . '/api/v1/create_mno_order');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $createPayload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        if ($caPath !== '') {
            curl_setopt($ch, CURLOPT_CAINFO, $caPath);
        } else {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $verify);
        }
        $createResponse = curl_exec($ch);
        $curlErr = curl_error($ch);

        if ($createResponse === false) {
            \Log::error('PAYMENT: create_mno_order cURL failed', ['error' => $curlErr]);
            return response()->json(['ok' => false, 'message' => 'cURL Error: ' . $curlErr], 500);
        }
        \Log::info('PAYMENT: create_mno_order response', ['response' => $createResponse]);

        $createData = json_decode($createResponse);
        $reference = $createData->reference ?? null;

        if (empty($reference) || !is_numeric($reference)) {
            return response()->json(['ok' => false, 'message' => 'Failed to create order. Invalid reference from provider.'], 502);
        }

        // Create local payment record
        $method = 'mpesa'; // Default
        if (preg_match('/^25565|^25567|^25568|^25569/', $phone)) $method = 'tigopesa';
        elseif (preg_match('/^25574|^25575|^25576|^25578/', $phone)) $method = 'airtel';

        $payment = Payment::create([
            'user_id' => $user->id,
            'method' => $method,
            'amount' => $amount,
            'currency' => 'TZS',
            'provider_reference' => $reference,
            'meta' => [
                'order_id' => $orderId,
                'phone' => $phone,
                'payment_url' => $createData->payment_url ?? '',
                'provider' => 'selcom',
                'create_response' => $createData,
            ],
        ]);

        // 2. Initiate Push USSD
        $pushPayload = http_build_query([
            'project_id' => $appId,
            'phone' => $phone,
            'order_id' => $orderId,
            'is_reference_payment' => 0
        ]);

        curl_setopt($ch, CURLOPT_URL, $apiUrl . '/initiatePushUSSD');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $pushPayload);
        $pushResponse = curl_exec($ch);
        $pushErr = curl_error($ch);
        curl_close($ch);

        if ($pushResponse === false) {
            \Log::error('PAYMENT: initiatePushUSSD cURL failed', ['error' => $pushErr]);
            return response()->json(['ok' => false, 'message' => 'Push request failed via cURL: ' . $pushErr], 500);
        }
        \Log::info('PAYMENT: initiatePushUSSD response', ['response' => $pushResponse]);

        $pushData = json_decode($pushResponse);
        $ok = (!empty($pushData->resultcode) && $pushData->resultcode == '000');

        // Save push response
        $payment->meta = array_merge((array) $payment->meta, ['push_response' => $pushData]);
        $payment->save();

        return response()->json([
            'ok' => $ok,
            'message' => $ok ? 'Tafadhali thibitisha kwenye simu yako.' : ($pushData->message ?? 'Unknown error'),
            'reference' => $reference,
            'payment_id' => $payment->id,
            'order_id' => $orderId,
        ], $ok ? 200 : 502);
    }

    /**
     * Provider webhook to listen for payment result [Mock].
     * POST /payment/webhook
     */
    public function webhook(Request $request, SmsService $sms)
    {
        // In production: verify signature / token from provider
        $data = $request->all();
        $reference = (string) ($data['reference'] ?? $data['provider_reference'] ?? '');
        $orderId   = (string) ($data['order_id'] ?? '');
        $status    = (string) ($data['status'] ?? ''); // Selcom may send 'paid'
        $transid   = (string) ($data['transid'] ?? '');

        $payment = null;
        if ($reference !== '') {
            $payment = Payment::where('provider_reference', $reference)->latest('id')->first();
        }
        if (!$payment && $orderId !== '') {
            // Fallback: match via meta->order_id (works on SQLite with JSON casting)
            foreach (Payment::orderByDesc('id')->get() as $p) {
                $meta = (array) $p->meta;
                if (($meta['order_id'] ?? null) === $orderId) { $payment = $p; break; }
            }
        }
        if (!$payment) {
            return response()->json(['ok' => false, 'message' => 'Payment not found'], 404);
        }

        $meta = (array) $payment->meta;
        $meta['webhook'] = $data;
        if ($transid !== '') { $meta['transid'] = $transid; }

        $updates = [ 'meta' => $meta ];
        $setPaid = in_array(strtolower($status), ['success','completed','paid'], true);
        if ($setPaid) {
            $updates['paid_at'] = now();
        }

        $payment->fill($updates)->save();

        // Send SMS notification upon successful payment
        if ($setPaid && $payment->user_id) {
            $amount = number_format((int) $payment->amount);
            $ref = $payment->provider_reference ?: ($meta['order_id'] ?? '');
            $message = "Malipo yako ya TZS {$amount} yamefanikiwa. Rejea: {$ref}. Asante kwa kutumia ElanSwap.";
            try { $sms->sendSms($payment->user_id, $message); } catch (\Throwable $e) { /* log silently */ }
        }

        return response()->json(['ok' => true, 'message' => 'Webhook processed']);
    }

    /**
     * Polling endpoint: return latest payment status for current user
     * GET /payment/status
     */
    public function status(Request $request)
    {
        $user = $request->user();
        $latest = $user?->payments()->latest('id')->first();
        return response()->json([
            'ok' => true,
            'paid' => (bool) ($latest && $latest->paid_at),
            'status' => $latest?->status,
            'paid_at' => optional($latest?->paid_at)->toIso8601String(),
            'method' => $latest?->method,
            'reference' => $latest?->provider_reference,
        ]);
    }
}