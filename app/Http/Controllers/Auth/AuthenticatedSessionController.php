<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\OtpVerification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View|RedirectResponse
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user && in_array($user->role ?? 'user', ['admin','superadmin'], true)) {
                return to_route('admin.dashboard');
            }
            return to_route('dashboard');
        }
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // This will attempt with normalized phone (handled in LoginRequest)
        $request->authenticate();

        $user = Auth::user();

        // If not verified, force OTP verification
        if ($user && (int)($user->is_verified ?? 0) !== 1) {
            // Immediately logout to prevent access
            Auth::logout();

            // Create a new OTP tied to the current (normalized) phone
            $otpVerification = OtpVerification::createOtp($user, $user->phone);

            // Send the OTP via our global helper (use plain OTP)
            \sendsms($user->id, "Your ElanSwap OTP is {$otpVerification->otp_plain}");

            // Put user ID in session for the OTP flow
            $request->session()->put('otp_verification_user_id', $user->id);

            return redirect()->route('otp.verify')->with('status', 'We sent you a new OTP to verify your number.');
        }

        // Verified: proceed as normal, redirect based on role
        $request->session()->regenerate();

        $welcome = 'Successfully logged in. Welcome back, '.($user?->name ?? '');

        if ($user && in_array($user->role, ['admin','superadmin'], true)) {
            return redirect('/admin')->with('status', $welcome);
        }
        return to_route('dashboard')->with('status', $welcome);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
