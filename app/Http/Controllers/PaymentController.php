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

        // Normalize phone to LOCAL format (leading 0), as required by your API
        $raw = preg_replace('/[^0-9+]/', '', (string) $request->string('phone'));
        if (str_starts_with($raw, '+')) { $raw = substr($raw, 1); }
        $phoneLocal = $raw;
        if (str_starts_with($raw, '255') && strlen($raw) >= 12) {
            // 2557XXXXXXXX -> 07XXXXXXXX
            $phoneLocal = '0' . substr($raw, 3);
        } elseif (str_starts_with($raw, '0')) {
            $phoneLocal = $raw;
        } elseif (preg_match('/^[67][0-9]{8}$/', $raw)) {
            $phoneLocal = '0' . $raw;
        } else {
            // last-resort sanitize to local style if possible
            $phoneLocal = '0' . ltrim($raw, '0');
        }
        \Log::info('PAYMENT: Normalized phone', ['input' => (string) $request->string('phone'), 'local' => $phoneLocal]);

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
            'phone' => $phoneLocal,
            'amount' => $amount,
            'api_url' => $apiUrl,
        ]);

        // 1. Create MNO order
        $createPayload = http_build_query([
            'app_id'             => $appId,
            'order_firstname'    => $user->name ?? 'Customer',
            'order_lastname'     => 'Customer',
            'order_email'        => $user->email ?? 'info@elanbrands.net',
            'order_phone'        => $phoneLocal,
            'amount'             => $amount,
            'order_id'           => $orderId,
            'currency'           => 'TZS',
            'order_item_cont'    => 1,
            'order_item_count'   => 1,
            'service_name'       => 'subscription',
            'is_reference_payment' => 1
        ]);

        $ch = curl_init(rtrim($apiUrl, '/') . '/create_mno_order');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $createPayload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/x-www-form-urlencoded',
            'Accept: application/json',
            'X-Requested-With: XMLHttpRequest',
        ]);
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

        $createData = json_decode($createResponse, true);
        // Try multiple locations/keys for reference
        $reference = $createData['reference']
            ?? ($createData['ref'] ?? null)
            ?? ($createData['data']['reference'] ?? null);
        $resultCode = (string)($createData['resultcode'] ?? '');
        $result     = (string)($createData['result'] ?? '');
        $provMsg    = (string)($createData['message'] ?? '');
        $paymentUrl = (string)($createData['payment_url'] ?? '');

        // If provider indicates success but reference missing, fallback to order_id
        if ((strtoupper($result) === 'SUCCESS' || $resultCode === '000' || $paymentUrl !== '') && empty($reference)) {
            \Log::warning('PAYMENT: Provider success but missing reference; falling back to order_id', [
                'order_id' => $orderId,
                'provider_payload' => $createData,
            ]);
            $reference = $orderId; // push can work with order_id; we keep reference for record
        }

        if (empty($reference)) {
            \Log::warning('PAYMENT: Missing reference from provider; proceeding with order_id fallback', [
                'order_id' => $orderId,
                'provider_payload' => $createData,
                'provider_message' => $provMsg,
            ]);
            $reference = $orderId;
        }

        // Create local payment record
        $method = 'mpesa'; // Default
        // Detect method by local MSISDN prefix
        if (preg_match('/^06(5|7|8|9)/', $phoneLocal)) $method = 'tigopesa';
        elseif (preg_match('/^07(4|5|6|8)/', $phoneLocal)) $method = 'airtel';

        $payment = Payment::create([
            'user_id' => $user->id,
            'method' => $method,
            'amount' => $amount,
            'currency' => 'TZS',
            'provider_reference' => $reference,
            'meta' => [
                'order_id' => $orderId,
                'phone' => $phoneLocal,
                'payment_url' => $paymentUrl ?? ($createData['payment_url'] ?? ''),
                'provider' => 'selcom',
                'create_response' => $createData,
            ],
        ]);

        // Small delay to allow provider to index the order before push
        \Log::info('PAYMENT: Sleeping briefly before push to allow provider indexing');
        usleep(600000); // 600ms

        // 2. Initiate Push USSD
        $pushFields = [
            'project_id' => $appId,
            'phone' => $phoneLocal,
            'order_id' => $orderId,
            'is_reference_payment' => 0,
            // include reference for gateways that accept either order_id or reference
            'reference' => $reference,
        ];
        $pushUrl = rtrim($apiUrl, '/') . '/initiatePushUSSD'; // e.g. https://elan.co.tz/api/payments/selcom/initiatePushUSSD

        // Prepare headers and retry a few times in case order is not yet indexed provider-side
        $headers = [
            'Content-Type: application/x-www-form-urlencoded',
            'Accept: application/json',
            'X-Requested-With: XMLHttpRequest',
        ];

        $maxAttempts = 3;
        $attempt = 0;
        $pushResponse = null;
        $pushErr = null;
        do {
            $attempt++;
            curl_setopt($ch, CURLOPT_URL, $pushUrl);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($pushFields));
            $pushResponse = curl_exec($ch);
            $pushErr = curl_error($ch);

            if ($pushResponse === false) {
                \Log::error('PAYMENT: initiatePushUSSD cURL failed', ['attempt' => $attempt, 'error' => $pushErr]);
                // if network error, small delay then retry
                usleep(400000); // 400ms
                continue;
            }

            \Log::info('PAYMENT: initiatePushUSSD response', ['attempt' => $attempt, 'response' => $pushResponse]);
            $tmp = json_decode($pushResponse);
            if ($tmp && isset($tmp->resultcode) && (string)$tmp->resultcode === '403' && stripos((string)($tmp->message ?? ''), 'No order') !== false) {
                // provider not ready: wait briefly and retry
                usleep(700000); // 700ms
                continue;
            }
            break; // otherwise accept response
        } while ($attempt < $maxAttempts);

        curl_close($ch);

        if ($pushResponse === false) {
            return response()->json(['ok' => false, 'message' => 'Push request failed via cURL: ' . $pushErr], 500);
        }

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