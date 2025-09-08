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

        // Follow script: use /api/v1/create_mno_order
        $ch = curl_init(rtrim($apiUrl, '/') . '/api/v1/create_mno_order');
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
        usleep(2500000); // 2.5s

        // 2. Initiate Push USSD (exactly as the working script)
        $pushFields = [
            'project_id' => $appId,
            'phone' => $phoneLocal,
            'order_id' => $orderId,
            'is_reference_payment' => 0,
        ];
        \Log::info('PAYMENT: initiatePushUSSD payload (script fidelity)', ['payload' => $pushFields]);
        $pushUrl = rtrim($apiUrl, '/') . '/initiatePushUSSD'; // e.g. https://elan.co.tz/api/payments/selcom/initiatePushUSSD

        // Prepare headers and retry a few times in case order is not yet indexed provider-side
        $headers = [
            'Content-Type: application/x-www-form-urlencoded',
            'Accept: application/json',
            'X-Requested-With: XMLHttpRequest',
        ];

        $maxAttempts = 7;
        $attempt = 0;
        $pushResponse = null;
        $pushErr = null;
        do {
            $attempt++;
            // Fresh cURL handle for each attempt to avoid stale options
            $pch = curl_init($pushUrl);
            curl_setopt($pch, CURLOPT_POST, true);
            curl_setopt($pch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($pch, CURLOPT_POSTFIELDS, http_build_query($pushFields));
            curl_setopt($pch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($pch, CURLOPT_TIMEOUT, $timeout);
            if ($caPath !== '') {
                curl_setopt($pch, CURLOPT_CAINFO, $caPath);
            } else {
                curl_setopt($pch, CURLOPT_SSL_VERIFYPEER, $verify);
            }
            $pushResponse = curl_exec($pch);
            $pushErr = curl_error($pch);
            curl_close($pch);

            if ($pushResponse === false) {
                \Log::error('PAYMENT: initiatePushUSSD cURL failed', ['attempt' => $attempt, 'error' => $pushErr]);
                // if network error, small delay then retry (exponential)
                usleep(300000 * $attempt); // 300ms * attempt
                continue;
            }

            \Log::info('PAYMENT: initiatePushUSSD response', ['attempt' => $attempt, 'response' => $pushResponse]);
            $tmp = json_decode($pushResponse);
            if ($tmp && isset($tmp->resultcode) && (string)$tmp->resultcode === '403' && stripos((string)($tmp->message ?? ''), 'No order') !== false) {
                // provider not ready: wait briefly and retry with backoff
                usleep(600000 * $attempt); // backoff
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
    public function webhook()
    {
        
        header('Content-Type: application/json');
$input = json_decode(file_get_contents('php://input'), true);

if (isset($input['order_id'], $input['status'], $input['transid'], $input['reference'])) {
    // Process the transaction (e.g., save to database)
    http_response_code(200);
    echo json_encode([
        'status' => 'success',
        'message' => 'Transaction processed successfully',
        'transaction_id' => $input['transid']
    ]);
} else {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Missing required fields'
    ]);
}
       // $raw = file_get_contents('php://input');
    
        // // Decode JSON as associative array
        // $data = json_decode($raw, true);
    
        // // Ensure required fields are available
        // $order_id = $data['order_id'] ?? null;
        // $status = $data['status'] ?? null;
        // $transid = $data['transid'] ?? null;
        // $reference = $data['reference'] ?? null;
    
        // if (!$order_id || !$status || !$transid || !$reference) {
        //     $url = 'https://messaging-service.co.tz/link/sms/v1/text/single?username=elanbrands&password=Eliyaamos1@&from=Elan+Brands&to=255757756184&text=' . urlencode("Nothing received");
        //     $response = Http::get($url);
    
        //     return response()->json(['error' => 'Missing required fields'], 400);
        // }
    
        // $text = "Malipo yako ya Tsh 1000 yenye kumbukumbu namba $reference Yamefanikiwa";
        // $url = 'https://messaging-service.co.tz/link/sms/v1/text/single?username=elanbrands&password=Eliyaamos1@&from=Elan+Brands&to=255757756184&text=' . urlencode($text);
        // $response = Http::get($url);
    
        // return response()->json(['message' => 'Webhook processed successfully']);
    }


    

public function twebhook()
    {
        
        
        $result = json_decode(file_get_contents('php://input'));
      $order_id = $result->order_id;
        $status = $result->status;
        $transid = $result->transid;
        $reference = $result->reference;
       
		// URL to fetch
$url = 'https://messaging-service.co.tz/link/sms/v1/text/single?username=elanbrands&password=Eliyaamos1@&from=Elan+Brands&to=255757756184&text=$transid+Malipo+yako+ya+Tsh+1000+yenye+kumbukumbu+namba+$reference+Yamefanikiwa';

// Initialize cURL session
$ch = curl_init();

// Set the URL and other options
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the response as a string
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Follow redirects (if any)
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // Disable SSL verification (not recommended for production)
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL verification (not recommended for production)

// Execute the cURL request
$response = curl_exec($ch);

// Check for errors
if($response === false) {
    echo 'cURL Error: ' . curl_error($ch);
} else {
    echo $response;
}

// Close the cURL session
curl_close($ch);


		
		// NOTE: Parameters (Request $request, SmsService $sms) intentionally removed.
        // We use Laravel helpers to access the current request and resolve services when needed.
        // $request = request();
        
        // // Handle GET requests (for testing/verification)
        // if ($request->isMethod('GET')) {
        //     return response()->json([
        //         'ok' => true,
        //         'message' => 'Webhook endpoint is active',
        //         'method' => 'GET',
        //         'timestamp' => now()->toIso8601String(),
        //     ]);
        // }
        
        // // Handle POST requests (actual webhook processing)
        // // In production: verify signature / token from provider
        // $data = $request->all();
        // $reference = (string) ($data['reference'] ?? $data['provider_reference'] ?? '');
        // $orderId   = (string) ($data['order_id'] ?? '');
        // $status    = (string) ($data['status'] ?? ''); // Selcom may send 'paid'
        // $transid   = (string) ($data['transid'] ?? '');

        // $payment = null;
        // if ($reference !== '') {
        //     $payment = Payment::where('provider_reference', $reference)->latest('id')->first();
        // }
        // if (!$payment && $orderId !== '') {
        //     // Fallback: match via meta->order_id (works on SQLite with JSON casting)
        //     foreach (Payment::orderByDesc('id')->get() as $p) {
        //         $meta = (array) $p->meta;
        //         if (($meta['order_id'] ?? null) === $orderId) { $payment = $p; break; }
        //     }
        // }
        // if (!$payment) {
        //     return response()->json(['ok' => false, 'message' => 'Payment not found'], 404);
        // }

        // $meta = (array) $payment->meta;
        // $meta['webhook'] = $data;
        // if ($transid !== '') { $meta['transid'] = $transid; }

        // $updates = [ 'meta' => $meta ];
        // $setPaid = in_array(strtolower($status), ['success','completed','paid'], true);
        // if ($setPaid) {
        //     $updates['paid_at'] = now();
        // }

        // $payment->fill($updates)->save();

        // // Send SMS notification upon successful payment
        // if ($setPaid && $payment->user_id) {
        //     $amount = number_format((int) $payment->amount);
        //     $ref = $payment->provider_reference ?: ($meta['order_id'] ?? '');
        //     $message = "Malipo yako ya TZS {$amount} yamefanikiwa. Rejea: {$ref}. Asante kwa kutumia ElanSwap.";
        //     try {
        //         // Resolve SmsService on demand since it's no longer injected
        //         app(\App\Services\SmsService::class)->sendSms($payment->user_id, $message);
        //     } catch (\Throwable $e) { /* log silently */ }
        // }

        // return response()->json(['ok' => true, 'message' => 'Webhook processed']);
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
        if ($orderId !== '') {
            foreach ($user?->payments()->orderByDesc('id')->get() ?? [] as $p) {
                $meta = (array) $p->meta;
                if (($meta['order_id'] ?? null) === $orderId) { $payment = $p; break; }
            }
        }
        if (!$payment) {
            $payment = $user?->payments()->latest('id')->first();
        }

        return response()->json([
            'ok' => true,
            'paid' => (bool) ($payment && $payment->paid_at),
            'status' => $payment?->status,
            'paid_at' => optional($payment?->paid_at)->toIso8601String(),
            'method' => $payment?->method,
            'reference' => $payment?->provider_reference,
            'order_id' => (string) (($payment?->meta['order_id'] ?? '') ?: ''),
        ]);
    }
}