<?php

namespace App\Http\Controllers;

use App\Models\Payment;
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
        $amount = (int) (config('services.elanswap.payment_amount', 5000)); // TZS
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
        $amount = (int) (config('services.elanswap.payment_amount', 5000));

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
        $amount = (int) (config('services.elanswap.payment_amount', 5000));

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

        // Build order details
        $orderId = 'ORD_' . now()->format('YmdHis') . '_' . $user->id;

        // 1) Create MNO order
        $createRes = Http::timeout($timeout)
            ->asForm()
            ->post($base . 'api/v1/create_mno_order', [
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
            ]);

        if (!$createRes->ok()) {
            return response()->json(['ok' => false, 'message' => 'Imeshindikana kuanzisha oda. Tafadhali jaribu tena.'], 502);
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

        // 2) Initiate Push USSD
        $pushRes = Http::timeout($timeout)
            ->asForm()
            ->post($base . 'initiatePushUSSD', [
                'project_id' => $appId,
                'phone' => $phone,
                'order_id' => $orderId,
                'is_reference_payment' => 0,
            ]);
        $push = $pushRes->ok() ? $pushRes->json() : null;

        // Save push response in meta
        $payment->meta = array_merge((array) $payment->meta, ['push_response' => $push]);
        $payment->save();

        $ok = ($push && isset($push['resultcode']) && (string)$push['resultcode'] === '000');

        return response()->json([
            'ok' => $ok,
            'message' => $ok ? 'Tafadhali thibitisha kwenye simu yako.' : ($push['message'] ?? 'Imeshindikana kutuma ombi'),
            'reference' => $reference,
            'payment_id' => $payment->id,
            'method' => $method,
            'phone' => $phone,
            'order_id' => $orderId,
            'payment_url' => $paymentUrl,
        ], $ok ? 200 : 502);
    }

    /**
     * Provider webhook to listen for payment result [Mock].
     * POST /payment/webhook
     */
    public function webhook(Request $request)
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
        if (in_array(strtolower($status), ['success','completed','paid'], true)) {
            $updates['paid_at'] = now();
        }

        $payment->fill($updates)->save();

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
