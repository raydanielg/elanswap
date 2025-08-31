<?php

namespace App\Http\Middleware;

use App\Models\Payment;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePaymentCompleted
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (!$user) {
            return redirect()->route('login');
        }

        $hasSuccessfulPayment = Payment::where('user_id', $user->id)
            ->where('status', 'success')
            ->exists();

        if (!$hasSuccessfulPayment) {
            // Allow access to billing routes themselves to make payment
            if ($request->is('billing*')) {
                return $next($request);
            }

            return redirect()->route('billing.index')
                ->with('status', 'Please complete your payment to continue.');
        }

        return $next($request);
    }
}
