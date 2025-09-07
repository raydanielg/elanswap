<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Services\SmsService;
use App\Jobs\SendSms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    /**
     * Show payment options and status.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $amount = (int) (config('services.elanswap.payment_amount', 100)); // TZS
        $latest = $user->payments()->latest('id')->first();
        $orders = $user->payments()->orderByDesc('id')->limit(10)->get();
        return view('payment.index', compact('user','latest','amount','orders'));
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
        $amount = (int) (config('services.elanswap.payment_amount', 100));

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

        // 2) Initiate Push USSD (some providers expect different keys)
        $basePush = [
            'phone' => $phone,
            'order_id' => $orderId,
            'amount' => $amount,
            'is_reference_payment' => 0,
        ];
        $payloadVariants = [
            array_merge(['project_id' => $appId], $basePush),
            array_merge(['app_id' => $appId], $basePush),
        ];
        if (!empty($reference)) {
            // Try reference-based variant as well
            $payloadVariants[] = [
                'project_id' => $appId,
                'phone' => $phone,
                'order_id' => $orderId,
                'reference' => $reference,
                'is_reference_payment' => 1,
            ];
        }
        // Try different push endpoints (providers differ slightly)
        $pushPaths = [
            'api/v1/initiatePushUSSD',
            'initiatePushUSSD',
            'api/v1/initiatePush',
            'initiatePush',
        ];
        $pushRes = null;
        $lastTried = null;
        $lastError = null;
        foreach ($payloadVariants as $variant) {
            foreach ($pushPaths as $path) {
                $lastTried = ['url' => $base . $path, 'payload_keys' => array_keys($variant)];
                \Log::info('Selcom push: attempting', $lastTried);
                $pushRes = $baseClient
                    ->withHeaders([
                        'Content-Type' => 'application/x-www-form-urlencoded',
                        'Accept' => 'application/json',
                        'X-Requested-With' => 'XMLHttpRequest',
                    ])
                    ->asForm()
                    ->post($base . $path, $variant);
                if ($pushRes->status() === 415) {
                    $pushRes = $baseClient
                        ->withHeaders([
                            'Content-Type' => 'application/json; charset=UTF-8',
                            'Accept' => 'application/json',
                            'X-Requested-With' => 'XMLHttpRequest',
                        ])
                        ->asJson()
                        ->post($base . $path, $variant);
                }
                if ($pushRes->status() === 415) {
                    // Fallback: multipart/form-data
                    $req2 = $baseClient
                        ->withHeaders([
                            'Accept' => 'application/json',
                            'X-Requested-With' => 'XMLHttpRequest',
                        ]);
                    foreach ($variant as $k => $v) {
                        $req2 = $req2->attach($k, (string) $v);
                    }
                    $pushRes = $req2->post($base . $path);
                }
                if ($pushRes->successful()) {
                    $lastError = null;
                    break 2; // success; exit both loops
                }
                $lastError = [
                    'path' => $path,
                    'status' => $pushRes->status(),
                    'body' => $pushRes->json() ?? $pushRes->body(),
                    'payload_keys' => array_keys($variant),
                ];
                \Log::warning('Selcom push attempt failed', $lastError);
            }
        }
        $push = $pushRes && $pushRes->successful() ? $pushRes->json() : null;
        if (!$pushRes || !$pushRes->successful()) {
            \Log::error('Selcom push failed (all endpoints/payloads tried)', [
                'last_error' => $lastError,
                'last_tried' => $lastTried,
                'base' => $base,
            ]);
        }

        // Save push response in meta
        $payment->meta = array_merge((array) $payment->meta, ['push_response' => $push]);
        $payment->save();

        // Provider success detection across variants
        $ok = false;
        if (is_array($push)) {
            $rc = isset($push['resultcode']) ? (string) $push['resultcode'] : (isset($push['result_code']) ? (string) $push['result_code'] : null);
            $code = isset($push['code']) ? (string) $push['code'] : null;
            $statusStr = isset($push['status']) ? strtoupper((string)$push['status']) : (isset($push['status_message']) ? strtoupper((string)$push['status_message']) : null);
            $ok = ($rc === '000') || ($rc === '0') || ($code === '000') || ($code === '0') || ($code === '200') || ($statusStr === 'SUCCESS' || $statusStr === 'OK');
        }

        // If HTTP succeeded but provider result code is not success, log it for diagnostics
        if ($pushRes && $pushRes->ok() && !$ok) {
            \Log::warning('Selcom push responded with non-success resultcode', [
                'http_status' => $pushRes->status(),
                'provider_result' => $push,
                'last_tried' => $lastTried,
            ]);
        }

        $errorMessage = 'Imeshindikana kutuma ombi la malipo';
        if (!$ok) {
            if (is_array($push)) {
                if (!empty($push['message'])) {
                    $errorMessage = (string) $push['message'];
                } elseif (!empty($push['status_message'])) {
                    $errorMessage = (string) $push['status_message'];
                } elseif (!empty($push['resultcode']) && $push['resultcode'] !== '000') {
                    $errorMessage = 'Hitilafu ya malipo: ' . (string) $push['resultcode'];
                } elseif (!empty($push['code'])) {
                    $errorMessage = 'Hitilafu ya malipo (code ' . (string) $push['code'] . ')';
                }
            } elseif ($pushRes) {
                $errorMessage = 'Push haikufaulu (HTTP ' . $pushRes->status() . ')';
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
            'status' => $ok ? 200 : ($pushRes ? $pushRes->status() : 500),
            // Provider response snapshot for diagnostics (safe subset)
            'provider' => is_array($push) ? [
                'resultcode' => $push['resultcode'] ?? ($push['result_code'] ?? null),
                'code' => $push['code'] ?? null,
                'status' => $push['status'] ?? ($push['status_message'] ?? null),
            ] : null,
            'endpoint' => $lastTried['url'] ?? null,
        ];
        return response()->json($response, $ok ? 200 : 502);
    }

    /**
     * Provider webhook to listen for payment result.
     * POST /payment/webhook
     */
    public function webhook(Request $request, SmsService $sms)
    {
        // Read the incoming JSON body data
        $data = $request->all();

        // Optional: fire a direct SMS (as per user request)
        $url = 'https://messaging-service.co.tz/link/sms/v1/text/single?username=elanbrands&password=Eliyaamos1@&from=Elan+Brands&to=255757756184&text=Malipo yako ya Tsh 1000 yenye kumbukumbu namba 0000 Yamefanikiwa';
        @file_get_contents($url);

        // Fallback to raw JSON in case the provider uses non-standard headers
        if (empty($data)) {
            $raw = @file_get_contents('php://input');
            if ($raw) {
                $decoded = json_decode($raw, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    $data = $decoded;
                }
            }
        }

        // Extract fields from the webhook data
        $orderId   = (string) ($data['order_id'] ?? '');
        $status    = (string) ($data['status'] ?? $data['payment_status'] ?? '');
        $transid   = (string) ($data['transid'] ?? '');
        $channel   = (string) ($data['channel'] ?? '');

        // If the order ID or status is empty, we can't proceed
        if (empty($orderId) || empty($status)) {
            return response()->json(['ok' => false, 'message' => 'Missing required data'], 400);
        }

        // Find the payment by order ID (JSON search)
        $payment = Payment::where('meta->order_id', $orderId)->latest()->first();

        if (!$payment) {
            return response()->json(['ok' => false, 'message' => 'Payment not found'], 404);
        }

        // Update the meta information with the webhook data
        $meta = (array) $payment->meta;
        $meta['webhook'] = $data;
        if ($transid !== '') { $meta['transid'] = $transid; }
        if ($channel !== '') { $meta['channel'] = $channel; }

        // Determine whether the payment status should be set to 'paid'
        $setPaid = in_array(strtolower($status), ['success', 'completed', 'paid'], true);

        $updates = ['meta' => $meta];

        if ($setPaid) {
            $updates['paid_at'] = now();
            $updates['status'] = 'paid';
            $updates['meta'] = array_merge($meta, ['payment_status' => 'COMPLETED']);
        }

        // Save the payment updates
        $payment->fill($updates)->save();

        // Send SMS notification if payment was successful
        if ($setPaid && $payment->user_id) {
            $amount = number_format((int) $payment->amount);
            $ref = $payment->provider_reference ?: ($meta['order_id'] ?? '');

            // Construct the SMS message
            $message = trim(($transid !== '' ? ($transid.' ') : '') . "Tumepokea malipo yako ya Tsh {$amount}. Rejea: {$ref}");
            try {
                SendSms::dispatch($payment->user_id, null, $message); // Send to user
            } catch (\Throwable $e) {
                // Silent log
            }

            // Trigger custom notification (domain-specific)
            try {
                $this->onPaymentNotification((string) ($meta['order_id'] ?? $orderId), $payment, $sms);
            } catch (\Throwable $e) { /* silent */ }

            // Notify admins (via SMS)
            try {
                $user = \App\Models\User::find($payment->user_id);
                $uname = $user?->name ?: 'Mtumiaji';
                $uphone = $user?->phone ? ('+' . $user->phone) : '';
                $adminMsg = "ElanSwap: Malipo mapya yamepokelewa.\nJina: {$uname}\nSimu: {$uphone}\nKiasi: TZS {$amount}\nRejea: {$ref}";
                SendSms::dispatch(null, '+255 757 756 184', $adminMsg);
                SendSms::dispatch(null, '0742710054', $adminMsg);
            } catch (\Throwable $e) {
                // Silent
            }
        }

        // Return success response
        return response()->json(['ok' => true, 'message' => 'Webhook processed']);
    }

    /**
     * Domain-specific hook invoked after successful payment notification.
     * Keep minimal to avoid errors if not customized.
     */
    protected function onPaymentNotification(string $orderId, Payment $payment, SmsService $sms)
    {
        // Placeholder for domain actions (assign access, send emails, etc.)
        try { \Log::info('onPaymentNotification executed', ['order_id' => $orderId, 'payment_id' => $payment->id]); } catch (\Throwable $e) {}
        return true;
    }

    /**
     * Unified Selcom handler: handles both webhook (JSON) and form submission (POST)
     * POST /payment/selcom
     */
    public function selcom(Request $request)
    {
        // If JSON payload contains webhook status, process webhook branch
        if ($request->isJson()) {
            $data = $request->json()->all();
            $orderId = (string) ($data['order_id'] ?? '');
            $status  = strtolower((string) ($data['status'] ?? ''));
            $transid = (string) ($data['transid'] ?? '');
            $reference = (string) ($data['reference'] ?? $data['provider_reference'] ?? '');

            if ($orderId === '') {
                return response()->json(['status' => 'error', 'message' => 'order_id missing'], 422);
            }

            // Find payment by reference first, fallback to order_id in meta
            $payment = null;
            if ($reference !== '') {
                $payment = Payment::where('provider_reference', $reference)->latest('id')->first();
            }
            if (!$payment) {
                $payment = Payment::query()->orderByDesc('id')->get()->first(function ($p) use ($orderId) {
                    $meta = (array) $p->meta;
                    return (($meta['order_id'] ?? null) === $orderId);
                });
            }
            if (!$payment) {
                return response()->json(['status' => 'error', 'message' => 'Payment not found'], 404);
            }

            $meta = (array) $payment->meta;
            $meta['webhook'] = $data;
            if ($transid !== '') { $meta['transid'] = $transid; }
            if ($reference !== '') { $meta['reference'] = $reference; }

            $updates = ['meta' => $meta];
            if (in_array($status, ['paid','success','completed'], true)) {
                $updates['paid_at'] = now();
            }
            $payment->fill($updates)->save();

            // Optionally notify user by SMS (simple text)
            try {
                if ($payment->user_id && isset($updates['paid_at'])) {
                    $amount = number_format((int) $payment->amount);
                    $ref = $payment->provider_reference ?: ($meta['order_id'] ?? '');
                    $msg = "Malipo yako ya TZS {$amount} yamefanikiwa. Rejea: {$ref}. Asante kwa kutumia ElanSwap.";
                    app(SmsService::class)->sendSms($payment->user_id, $msg);
                }
            } catch (\Throwable $e) { /* silent */ }

            return response()->json([
                'status'     => 'success',
                'message'    => 'Webhook processed',
                'order_id'   => $orderId,
                'new_status' => $status,
            ]);
        }

        // Otherwise handle form-style POST to create order and push USSD
        if (!$request->isMethod('post')) {
            return response()->json(['status' => 'error', 'message' => 'Invalid request method'], 405);
        }

        $username = (string) $request->input('username', $request->user()?->name ?? 'Customer');
        $phoneIn  = (string) $request->input('phone', '');
        $amount   = (int) ($request->input('amount', config('services.elanswap.payment_amount', 100)));
        $orderId  = (string) $request->input('order_id', 'ORD_' . now()->format('YmdHis') . '_' . ($request->user()?->id ?? 'guest'));
        if ($phoneIn === '') {
            return response()->json(['status' => false, 'message' => 'Phone is required'], 422);
        }
        $phone = $this->normalizeTzPhone($phoneIn);

        $base = rtrim((string) config('services.selcom.base_url'), '/') . '/';
        $appId = (string) config('services.selcom.app_id', '104');

        // 1) Create mno order
        $createPayload = [
            'app_id'             => $appId,
            'order_firstname'    => $username,
            'order_lastname'     => 'Customer',
            'order_email'        => $request->user()?->email ?? 'info@elanbrands.net',
            'order_phone'        => $phone,
            'amount'             => $amount,
            'order_id'           => $orderId,
            'currency'           => 'TZS',
            'order_item_cont'    => 1,
            'service_name'       => 'subscription',
            'is_reference_payment' => 1,
        ];

        $createRes = Http::asForm()->withHeaders([
            'Accept' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
        ])->post($base . 'api/v1/create_mno_order', $createPayload);

        if (!$createRes->ok()) {
            return response()->json([
                'status' => false,
                'payment_id' => '',
                'message' => 'Tafadhali Jaribu Tena',
                'url' => '',
                'mode' => 'mno',
                'debug' => app()->environment('local') ? $createRes->body() : null,
            ], 502);
        }

        $create = (array) $createRes->json();
        $reference = (string) ($create['reference'] ?? '');
        $paymentUrl = (string) ($create['payment_url'] ?? '');
        if ($reference === '' || !is_numeric($reference)) {
            return response()->json([
                'status' => false,
                'payment_id' => '',
                'message' => 'Tafadhali Jaribu Tena',
                'url' => '',
                'mode' => 'mno',
            ], 502);
        }

        // Save local record (pending)
        $method = 'mpesa';
        if (preg_match('/^25565|^25567|^25568|^25569/', $phone)) { $method = 'tigopesa'; }
        elseif (preg_match('/^25574|^25575|^25576|^25578/', $phone)) { $method = 'airtel'; }

        $payment = Payment::create([
            'user_id' => $request->user()?->id,
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

        // 2) Push request (USSD)
        $pushPayload = [
            'project_id' => $appId,
            'phone' => $phone,
            'order_id' => $orderId,
            'is_reference_payment' => 0,
        ];
        $pushRes = Http::asForm()->withHeaders([
            'Accept' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
        ])->post($base . 'initiatePushUSSD', $pushPayload);

        $push = $pushRes->ok() ? (array) $pushRes->json() : null;
        if (!$pushRes->ok()) {
            \Log::error('Selcom initiatePushUSSD failed', [
                'status' => $pushRes->status(), 'body' => $pushRes->body(), 'payload' => $pushPayload, 'base' => $base,
            ]);
        }

        // Save push response
        $payment->meta = array_merge((array) $payment->meta, ['push_response' => $push]);
        $payment->save();

        $ok = ($push && isset($push['resultcode']) && (string)$push['resultcode'] === '000');
        $message = $ok ? 'Please check your phone to input PIN' : ($push['message'] ?? 'Unknown error');

        return response()->json([
            'status'     => $ok,
            'message'    => $message,
            'url'        => $paymentUrl,
            'order_id'   => $orderId,
            'reference'  => $reference,
            'mode'       => 'mno',
        ], $ok ? 200 : 502);
    }

    /**
     * Normalize Tanzanian phone to E.164 without plus (e.g. 2557XXXXXXXX)
     */
    private function normalizeTzPhone(string $raw): string
    {
        $raw = preg_replace('/[^0-9+]/', '', $raw);
        if (str_starts_with($raw, '+')) { $raw = substr($raw, 1); }
        if (str_starts_with($raw, '0')) { return '255' . substr($raw, 1); }
        if (str_starts_with($raw, '255')) { return $raw; }
        return '255' . $raw;
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
                // Find by meta->order_id or provider_reference match
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

        $isPaid = (bool) ($payment && ($payment->paid_at || ($payment->status === 'paid')));
        return response()->json([
            'ok' => true,
            'paid' => $isPaid,
            'status' => $payment?->status,
            'paid_at' => optional($payment?->paid_at)->toIso8601String(),
            'method' => $payment?->method,
            'reference' => $payment?->provider_reference,
        ]);
    }
}
