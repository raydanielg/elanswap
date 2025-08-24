<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\OtpVerification;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'phone' => ['required', 'string', 'min:9'],
        ]);

        // Normalize phone to 255XXXXXXXXX
        $raw = preg_replace('/\D+/', '', $request->input('phone'));
        if (str_starts_with($raw, '0') && strlen($raw) === 10) {
            $normalized = '255' . substr($raw, 1);
        } elseif (strlen($raw) === 9) {
            $normalized = '255' . $raw;
        } elseif (str_starts_with($raw, '255') && strlen($raw) >= 12) {
            $normalized = substr($raw, 0, 12);
        } else {
            return back()->withInput($request->only('phone'))
                ->withErrors(['phone' => 'Invalid phone format.']);
        }

        $user = User::where('phone', $normalized)->first();
        if (!$user) {
            return back()->withInput($request->only('phone'))
                ->withErrors(['phone' => 'We could not find an account with that phone number.']);
        }

        // Create and send OTP (use plain OTP value in SMS)
        $otp = OtpVerification::createOtp($user, $normalized);
        \sendsms($user->id, "Your ElanSwap password reset OTP is {$otp->otp_plain}");

        // Store session context for OTP flow
        $request->session()->put('otp_verification_user_id', $user->id);
        $request->session()->put('otp_context', 'password_reset');

        return redirect()->route('otp.verify')->with('status', 'We have sent an OTP to your phone. Enter it to continue.');
    }
}
