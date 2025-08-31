<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Services\SelcomClient;
use App\Services\SmsClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BillingController extends Controller
{
    private int $amountTzs = 5000; // configurable later

    public function index()
    {
        $user = Auth::user();
        $latest = Payment::where('user_id', $user->id)->latest()->first();
        return view('billing.index', [
            'amount' => $this->amountTzs,
            'latest' => $latest,
        ]);
    }

    public function create(Request $request, SelcomClient $selcom, SmsClient $sms)
    {
        $data = $request->validate([
            'method' => 'required|in:mpesa,airtel,tigo,card',
            'phone' => 'nullable|string|max:20',
        ]);

        $orderId = 'ORD_'.uniqid();

        // Persist initial payment row
        $payment = Payment::create([
            'user_id' => Auth::id(),
            'provider' => $data['method'],
            'method' => $data['method'],
            'currency' => 'TZS',
            'amount' => $this->amountTzs * 100, // minor units
            'status' => 'pending',
            'meta' => [
                'order_id' => $orderId,
                'phone' => $data['phone'] ?? null,
                'initiated_ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ],
        ]);

        // Create MNO Order
        $create = $selcom->createMnoOrder([
            'username' => Auth::user()->name ?? 'Customer',
            'phone' => (string)($data['phone'] ?? ''),
            'amount' => (string)$this->amountTzs,
            'order_id' => $orderId,
        ]);

        if (!($create['ok'] ?? false)) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['ok' => false, 'message' => 'Payment error: could not create order.'], 400);
            }
            return redirect()->route('billing.index')->with('status', 'Payment error: could not create order.');
        }

        $payload = $create['data'] ?? [];
        $reference = $payload['reference'] ?? null;
        $paymentUrl = $payload['payment_url'] ?? null;

        // Update meta
        $meta = $payment->meta ?? [];
        $meta['reference'] = $reference;
        $meta['payment_url'] = $paymentUrl;
        $payment->update(['meta' => $meta]);

        // Initiate USSD push (if phone provided)
        if (!empty($data['phone'])) {
            $push = $selcom->initiatePushUssd($data['phone'], $orderId);
            // Optionally check $push['ok'] etc.
        }

        // Notify user via SMS (optional)
        if (!empty($data['phone'])) {
            $sms->send($data['phone'], 'Tafadhali thibitisha malipo yako kwa ElanSwap. Kiasi: '.number_format($this->amountTzs).' TZS.', Auth::id());
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'ok' => true,
                'message' => 'Payment initiated. Check your phone to complete.',
                'order_id' => $orderId,
                'reference' => $reference,
                'payment_url' => $paymentUrl,
            ]);
        }

        return redirect()->route('billing.index')->with('status', 'Payment initiated. Check your phone to complete.');
    }

    // Webhook to update payment status
    public function webhook(Request $request, SmsClient $sms)
    {
        $payload = json_decode($request->getContent() ?: '{}', true);
        $orderId = $payload['order_id'] ?? null;
        $status  = $payload['status'] ?? null;
        $transid = $payload['transid'] ?? null;

        if (!$orderId) {
            return response()->json(['ok' => false, 'message' => 'Missing order_id'], 400);
        }

        $payment = Payment::where('meta->order_id', $orderId)->latest()->first();
        if (!$payment) {
            return response()->json(['ok' => false, 'message' => 'Payment not found'], 404);
        }

        if ($status === 'paid') {
            $meta = $payment->meta ?? [];
            $meta['transid'] = $transid;
            $payment->update([
                'status' => 'success',
                'paid_at' => now(),
                'meta' => $meta,
            ]);

            // SMS notify user
            $phone = $meta['phone'] ?? null;
            if ($phone) {
                $sms->send($phone, 'Malipo yako yamekamilika. Karibu ElanSwap!', $payment->user_id);
            }
        }

        return response()->json([
            'status'     => 'success',
            'message'    => 'Webhook processed',
            'order_id'   => $orderId,
            'new_status' => $status,
        ]);
    }

    // Simple polling endpoint to check if user has paid
    public function status()
    {
        $user = Auth::user();
        $paid = Payment::where('user_id', $user->id)->where('status', 'success')->exists();
        return response()->json(['ok' => true, 'paid' => $paid]);
    }

    // Demo endpoints for marking success/failure locally (optional)
    public function demoSuccess(Payment $payment)
    {
        // authorize owner
        abort_unless($payment->user_id === Auth::id(), 403);
        $payment->update(['status' => 'success', 'paid_at' => now()]);
        return redirect()->route('dashboard')->with('status', 'Payment successful.');
    }

    public function demoFail(Payment $payment)
    {
        abort_unless($payment->user_id === Auth::id(), 403);
        $payment->update(['status' => 'failed']);
        return redirect()->route('billing.index')->with('status', 'Payment failed. Try again.');
    }
}
