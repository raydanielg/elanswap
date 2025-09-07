<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Services\SmsService;
use App\Jobs\SendSms;
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
        $amount = (int) (config('services.elanswap.payment_amount', 500)); // TZS
        $latest = $user->payments()->latest('id')->first();
        return view('payment.index', compact('user','latest','amount'));
    }

    /**
     * Mock pay handler. In production, integrate real provider (M-Pesa/TigoPesa/Airtel/Card).
     */
    public function pay(Request $request)
    {
        $request->validate([
            'method' => ['required','in:mpesa,tigopesa,airtel,card'],
        ]);

        $user = $request->user();
        $amount = (int) (config('services.elanswap.payment_amount', 500));

        // Create a payment record. Do NOT force a specific status value to avoid
        // violating existing SQLite CHECK constraints from previous schemas.
        // We mark paid via paid_at and leave status to its default ('pending') for safety.
        $payment = Payment::create([
            'user_id' => $user->id,
            'method' => (string) $request->string('method'),
            'amount' => $amount,
            'currency' => 'TZS',
            'paid_at' => now(),
            'meta' => [
                'mock' => true,
                'note' => 'Marked as paid for testing. Replace with real provider integration.',
            ],
        ]);

        return Redirect::route('payment.index')->with('status', 'Malipo yamekamilika. Asante!');
    }

    /**
     * Initiate a push payment via Selcom APIs (create_mno_order + initiatePushUSSD).
     * POST /payment/push
     */
    public function requestPush(Request $request)
    {
        $request->validate([
            'phone'  => ['required','string','min:9','max:15'],
        ]);

        $user = $request->user();
        $amount = (int) (config('services.elanswap.payment_amount', 500));

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

        // Prepare Selcom config
        $base = rtrim((string) config('services.selcom.base_url'), '/') . '/';
        $appId = (string) config('services.selcom.app_id');
        $timeout = (int) config('services.selcom.timeout', 15);
        $verify = config('services.selcom.verify', true);
        $caPath = config('services.selcom.ca_path');
        // Build a base HTTP client with SSL options
        $baseClient = Http::timeout($timeout);
        if ($caPath) {
            // When a CA bundle is provided, use it (keeps verification on)
            $baseClient = $baseClient->withOptions(['verify' => $caPath]);
        } else {
            // Otherwise honor the verify boolean (can be false for local/cPanel workaround)
            $baseClient = $baseClient->withOptions(['verify' => (bool) $verify]);
        }

        // Build order details
        $orderId = 'ORD_' . now()->format('YmdHis') . '_' . $user->id;

        // 1) Create MNO order (send as form-encoded; retry JSON if 415)
        $http = $baseClient
            ->withHeaders([
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Accept' => 'application/json',
                'X-Requested-With' => 'XMLHttpRequest',
            ])
            ->asForm();

        $createPayload = [
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
            'is_reference_payment' => 1,
        ];

        $createRes = $http->post($base . 'api/v1/create_mno_order', $createPayload);
        if ($createRes->status() === 415) {
            $createRes = $baseClient
                ->withHeaders([
                    'Content-Type' => 'application/json; charset=UTF-8',
                    'Accept' => 'application/json',
                    'X-Requested-With' => 'XMLHttpRequest',
                ])
                ->asJson()
                ->post($base . 'api/v1/create_mno_order', $createPayload);
        }
        if ($createRes->status() === 415) {
            // Fallback: multipart/form-data
            $req = $baseClient
                ->withHeaders([
                    'Accept' => 'application/json',
                    'X-Requested-With' => 'XMLHttpRequest',
                ]);
            foreach ($createPayload as $k => $v) {
                $req = $req->attach($k, (string) $v);
            }
            $createRes = $req->post($base . 'api/v1/create_mno_order');
        }

        if (!$createRes->ok()) {
            \Log::error('Selcom create_mno_order failed', [
                'status' => $createRes->status(),
                'body' => $createRes->body(),
                'payload' => $createPayload,
                'base' => $base,
            ]);
            return response()->json([
                'ok' => false,
                'message' => 'Imeshindikana kuanzisha oda (' . $createRes->status() . ').',
                'status' => $createRes->status(),
                'body' => $createRes->body(),
            ], 502);
        }
        $create = $createRes->json();
        $reference = (string) ($create['reference'] ?? '');
        $paymentUrl = (string) ($create['payment_url'] ?? '');
        if ($reference === '' || !is_numeric($reference)) {
            return response()->json(['ok' => false, 'message' => 'Jibu batili kutoka Selcom.'], 502);
        }

        // Detect provider label for record keeping (optional)
        $method = 'mpesa';
        if (preg_match('/^25565|^25567|^25568|^25569/', $phone)) {
            $method = 'tigopesa';
        } elseif (preg_match('/^25574|^25575|^25576|^25578/', $phone)) {
            $method = 'airtel';
        }

        // Create local payment record as pending
        $payment = Payment::create([
            'user_id' => $user->id,
            'method' => $method,
            'amount' => $amount,
            'currency' => 'TZS',
            'provider_reference' => $reference,
            'meta' => [
                'order_id' => $orderId,
                'phone' => $phone,
                'payment_url' => $paymentUrl,
                'provider' => 'selcom',
                'create_response' => $create,
            ],
        ]);

        // 2) Initiate Push USSD (send as form-encoded; retry JSON if 415)
        $pushPayload = [
            'project_id' => $appId,
            'phone' => $phone,
            'order_id' => $orderId,
            'is_reference_payment' => 0,
        ];
        // Some gateways expose initiatePushUSSD without the api/v1 prefix.
        // Try with api/v1 first, then without if we get a 404.
        $pushPaths = ['initiatePushUSSD', 'initiatePushUSSD'];
        $pushRes = null;
        foreach ($pushPaths as $idx => $path) {
            $pushRes = $baseClient
                ->withHeaders([
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Accept' => 'application/json',
                    'X-Requested-With' => 'XMLHttpRequest',
                ])
                ->asForm()
                ->post($base . $path, $pushPayload);
            if ($pushRes->status() === 415) {
                $pushRes = $baseClient
                    ->withHeaders([
                        'Content-Type' => 'application/json; charset=UTF-8',
                        'Accept' => 'application/json',
                        'X-Requested-With' => 'XMLHttpRequest',
                    ])
                    ->asJson()
                    ->post($base . $path, $pushPayload);
            }
            if ($pushRes->status() === 415) {
                // Fallback: multipart/form-data
                $req2 = $baseClient
                    ->withHeaders([
                        'Accept' => 'application/json',
                        'X-Requested-With' => 'XMLHttpRequest',
                    ]);
                foreach ($pushPayload as $k => $v) {
                    $req2 = $req2->attach($k, (string) $v);
                }
                $pushRes = $req2->post($base . $path);
            }
            // If not a 404, accept this response; otherwise try next path
            if ($pushRes->status() !== 404) {
                break;
            }
            \Log::warning('initiatePushUSSD 404 on path, trying alternative', ['path' => $path, 'base' => $base]);
        }
        $push = $pushRes->ok() ? $pushRes->json() : null;
        if (!$pushRes->ok()) {
            \Log::error('Selcom initiatePushUSSD failed', [
                'status' => $pushRes->status(),
                'body' => $pushRes->body(),
                'payload' => $pushPayload,
                'base' => $base,
            ]);
        }

        // Save push response in meta
        $payment->meta = array_merge((array) $payment->meta, ['push_response' => $push]);
        $payment->save();

        $ok = ($push && isset($push['resultcode']) && (string)$push['resultcode'] === '000');

        // If HTTP succeeded but provider result code is not success, log it for diagnostics
        if ($pushRes->ok() && !$ok) {
            \Log::warning('Selcom push responded with non-success resultcode', [
                'http_status' => $pushRes->status(),
                'provider_result' => $push,
                'payload' => $pushPayload,
            ]);
        }

        $errorMessage = 'Imeshindikana kutuma ombi';
        if (!$ok) {
            if (is_array($push) && isset($push['message']) && $push['message']) {
                $errorMessage = (string) $push['message'];
            } elseif (!$pushRes->ok()) {
                $errorMessage = 'Push haikufaulu (' . $pushRes->status() . ')';
            }
        }

        $response = [
            'ok' => $ok,
            'message' => $ok ? 'Tafadhali thibitisha kwenye simu yako.' : $errorMessage,
            'reference' => $reference,
            'payment_id' => $payment->id,
            'method' => $method,
            'phone' => $phone,
            'order_id' => $orderId,
            'payment_url' => $paymentUrl,
            'status' => $ok ? 200 : $pushRes->status(),
        ];
        // In local environment, include provider debug details to surface exact cause
        if (app()->environment('local')) {
            $response['debug'] = [
                'push_http_status' => $pushRes->status(),
                'push_body' => $push,
            ];
        }
        return response()->json($response, $ok ? 200 : 502);
    }

    /**
     * Provider webhook to listen for payment result [Mock].
     * POST /payment/webhook
     */
    public function webhook(Request $request, SmsService $sms)
    {
        // In production: verify signature / token from provider
        $data = $request->all();
        if (empty($data)) {
            // Fallback to raw JSON (providers sometimes send with non-standard headers)
            $raw = @file_get_contents('php://input');
            if ($raw) {
                $decoded = json_decode($raw, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    $data = $decoded;
                }
            }
        }
        $reference = (string) ($data['reference'] ?? $data['provider_reference'] ?? '');
        $orderId   = (string) ($data['order_id'] ?? '');
        $status    = (string) ($data['status'] ?? $data['payment_status'] ?? ''); // e.g., 'paid', 'COMPLETED'
        $transid   = (string) ($data['transid'] ?? '');
        $channel   = (string) ($data['channel'] ?? '');

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
        if ($channel !== '') { $meta['channel'] = $channel; }

        $updates = [ 'meta' => $meta ];
        $setPaid = in_array(strtolower($status), ['success','completed','paid'], true);
        if ($setPaid) {
            $updates['paid_at'] = now();
            // Also reflect completion in status/meta for reporting compatibility
            $updates['status'] = 'paid';
            $updates['meta'] = array_merge($meta, ['payment_status' => 'COMPLETED']);
        }

        $payment->fill($updates)->save();

        // Send SMS notification upon successful payment
        if ($setPaid && $payment->user_id) {
            $amount = number_format((int) $payment->amount);
            $ref = $payment->provider_reference ?: ($meta['order_id'] ?? '');
            // User-requested SMS format beginning with transid
            $message = trim(($transid !== '' ? ($transid.' ') : '') . "Tumepokea malipo yako ya Tsh {$amount}. Rejea: {$ref}");
            try { SendSms::dispatch($payment->user_id, null, $message); } catch (\Throwable $e) { /* log silently */ }

            // Also trigger domain-specific notification as requested
            $this->onPaymentNotification((string) ($meta['order_id'] ?? $orderId), $payment, $sms);

            // Notify admins
            try {
                $user = \App\Models\User::find($payment->user_id);
                $uname = $user?->name ?: 'Mtumiaji';
                $uphone = $user?->phone ? ('+'. $user->phone) : '';
                $adminMsg = "ElanSwap: Malipo mapya yamepokelewa.\nJina: {$uname}\nSimu: {$uphone}\nKiasi: TZS {$amount}\nRejea: {$ref}";
                SendSms::dispatch(null, '+255 757 756 184', $adminMsg);
                SendSms::dispatch(null, '0742710054', $adminMsg);
            } catch (\Throwable $e) { /* silent */ }
        }

        return response()->json(['ok' => true, 'message' => 'Webhook processed']);
    }

    /**
     * Provider webhook to listen for payment result [Mock].
     * POST /payment/webhook
     */
    public function webhook(Request $request, SmsService $sms)
    {
        // ... (rest of the code remains the same)

        if ($setPaid && $payment->user_id) {
            $amount = number_format((int) $payment->amount);
            $ref = $payment->provider_reference ?: ($meta['order_id'] ?? '');
            $message = trim(($transid !== '' ? ($transid.' ') : '') . "Tumepokea malipo yako ya Tsh {$amount}. Rejea: {$ref}");
            try { SendSms::dispatch($payment->user_id, null, $message); } catch (\Throwable $e) { /* silent */ }

            // Domain actions
            $this->onPaymentNotification((string) ($meta['order_id'] ?? $orderId), $payment, app(SmsService::class));

            // Notify admins
            try {
                $user = \App\Models\User::find($payment->user_id);
                $uname = $user?->name ?: 'Mtumiaji';
                $uphone = $user?->phone ? ('+'. $user->phone) : '';
                $adminMsg = "ElanSwap: Malipo mapya yamepokelewa.\nJina: {$uname}\nSimu: {$uphone}\nKiasi: TZS {$amount}\nRejea: {$ref}";
                SendSms::dispatch(null, '+255 757 756 184', $adminMsg);
                SendSms::dispatch(null, '0742710054', $adminMsg);
            } catch (\Throwable $e) { /* silent */ }
        }

        return response()->json(['ok' => true]);
    }

    /**
     * Alternate provider callback that posts JSON to a 'status' URL
     * (compatible with examples using php://input to read order_id/status).
     * POST /payment/status (or any route you configure to point here)
     */
    public function statusNotify(Request $request)
    {
        // ... (rest of the code remains the same)

        if ($setPaid && $payment->user_id) {
            $amount = number_format((int) $payment->amount);
            $ref = $payment->provider_reference ?: ($meta['order_id'] ?? '');
            $message = trim(($transid !== '' ? ($transid.' ') : '') . "Tumepokea malipo yako ya Tsh {$amount}. Rejea: {$ref}");
            try { SendSms::dispatch($payment->user_id, null, $message); } catch (\Throwable $e) { /* silent */ }

            // Domain actions
            $this->onPaymentNotification((string) ($meta['order_id'] ?? $orderId), $payment, app(SmsService::class));

            // Notify admins
            try {
                $user = \App\Models\User::find($payment->user_id);
                $uname = $user?->name ?: 'Mtumiaji';
                $uphone = $user?->phone ? ('+'. $user->phone) : '';
                $adminMsg = "ElanSwap: Malipo mapya yamepokelewa.\nJina: {$uname}\nSimu: {$uphone}\nKiasi: TZS {$amount}\nRejea: {$ref}";
                SendSms::dispatch(null, '+255 757 756 184', $adminMsg);
                SendSms::dispatch(null, '0742710054', $adminMsg);
            } catch (\Throwable $e) { /* silent */ }
        }

        return response()->json(['ok' => true]);
    }

    protected function onPaymentNotification(string $orderId, Payment $payment, SmsService $sms): bool
    {
        // Custom SMS per user request
        $smsText = "jdjdjdjddj"; // requested message content
        try {
            if ($payment->user_id) {
                SendSms::dispatch($payment->user_id, null, $smsText);
            }
        } catch (\Throwable $e) {
            // Log but do not interrupt flow
            \Log::warning('onPaymentNotification SMS failed: ' . $e->getMessage());
        }

        // Additional recommended actions can be added here, e.g.:
        // - Grant access/roles, mark subscription active in user meta
        // - Emit events, notify admins, enqueue emails, etc.

        return true;
    }

    /**
     * Polling endpoint: return latest payment status for current user
     * GET /payment/status
     */
    public function status(Request $request)
    {
        $user = $request->user();
        $orderId = (string) $request->query('order_id', '');

        $payment = null;
        if ($user) {
            if ($orderId !== '') {
                // Try to find the specific payment for this user by meta->order_id or provider_reference
                foreach ($user->payments()->orderByDesc('id')->get() as $p) {
                    $meta = (array) $p->meta;
                    if ((($meta['order_id'] ?? null) === $orderId) || ($p->provider_reference === $orderId)) {
                        $payment = $p; break;
                    }
                }
            }
            if (!$payment) {
                $payment = $user->payments()->latest('id')->first();
            }
        }

        return response()->json([
            'ok' => true,
            'paid' => (bool) ($payment && $payment->paid_at),
            'status' => $payment?->status,
            'paid_at' => optional($payment?->paid_at)->toIso8601String(),
            'method' => $payment?->method,
            'reference' => $payment?->provider_reference,
        ]);
    }
}
