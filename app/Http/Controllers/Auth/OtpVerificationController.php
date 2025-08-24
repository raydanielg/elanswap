<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\OtpVerification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class OtpVerificationController extends Controller
{
    // Using global helper sendsms() for SMS sending

    /**
     * Show the OTP verification form.
     */
    public function show(Request $request)
    {
        if (!$request->session()->has('otp_verification_user_id')) {
            return redirect()->route('register');
        }

        $userId = $request->session()->get('otp_verification_user_id');
        $user = User::find($userId);
        $phone = $user?->phone;

        return view('auth.verify-otp', compact('phone'));
    }

    /**
     * Verify the OTP code.
     */
    public function verify(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        $userId = $request->session()->get('otp_verification_user_id');
        if (!$userId) {
            return redirect()->route('register');
        }
        
        $user = User::findOrFail($userId);

        $otpVerification = OtpVerification::where('user_id', $user->id)
            ->where('is_verified', false)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if (!$otpVerification) {
            return back()->withErrors([
                'otp' => 'Invalid or expired OTP. Please request a new one.'
            ]);
        }

        if ($otpVerification->verify($request->otp)) {
            // Mark phone as verified (for account verification)
            $user->phone_verified_at = now();
            $user->is_verified = true;
            $user->save();

            $context = $request->session()->get('otp_context');

            if ($context === 'password_reset') {
                // For password reset, do not login yet. Redirect to set new password.
                $request->session()->forget('otp_context');
                $request->session()->put('password_reset_user_id', $user->id);
                // clear otp verification id; password reset will use its own key
                $request->session()->forget('otp_verification_user_id');
                return redirect()->route('password.reset.otp.form')->with('status', 'OTP verified. Please set your new password.');
            }

            // Default behavior: login and optionally send welcome SMS
            Auth::login($user);

            $plainPassword = $request->session()->get('user_plain_password');
            $request->session()->forget('otp_verification_user_id');
            $request->session()->forget('user_plain_password');

            if ($plainPassword) {
                \sendsms($user->id, "Welcome to ElanSwap! Your login is +$user->phone and password: $plainPassword");
            }

            return redirect()->route('home')->with('status', 'Your account has been verified successfully! Welcome to ElanSwap!');
        }

        return back()->withErrors([
            'otp' => 'The provided OTP is invalid.'
        ]);
    }

    /**
     * Resend the OTP code.
     */
    public function resend(Request $request)
    {
        $userId = $request->session()->get('otp_verification_user_id');
        if (!$userId) {
            return redirect()->route('register');
        }
        
        $user = User::findOrFail($userId);

        // Delete any existing OTPs
        $user->otpVerification()->delete();

        // Create and send new OTP
        $otpVerification = OtpVerification::createOtp($user, $user->phone);
        // Send OTP via SMS (use plain OTP)
        \sendsms($user->id, "Your ElanSwap OTP is {$otpVerification->otp_plain}");

        return back()->with('status', 'A new OTP has been sent to your phone number.');
    }

    /**
     * Change the phone number during OTP verification and resend OTP
     */
    public function changeNumber(Request $request)
    {
        $request->validate([
            'phone' => ['required','string','min:9'],
        ]);

        $userId = $request->session()->get('otp_verification_user_id');
        if (!$userId) {
            return redirect()->route('register');
        }

        $user = User::findOrFail($userId);

        // Normalize phone similar to registration
        $rawPhone = preg_replace('/\D+/', '', $request->input('phone'));
        if (str_starts_with($rawPhone, '0') && strlen($rawPhone) === 10) {
            $normalizedPhone = '255' . substr($rawPhone, 1);
        } elseif (strlen($rawPhone) === 9) {
            $normalizedPhone = '255' . $rawPhone;
        } elseif (str_starts_with($rawPhone, '255') && strlen($rawPhone) >= 12) {
            $normalizedPhone = substr($rawPhone, 0, 12);
        } else {
            return back()->withErrors(['phone' => 'Invalid phone format.']);
        }

        // Update phone number
        $user->phone = $normalizedPhone;
        $user->save();

        // Delete existing OTPs and create a new one for the new number
        $user->otpVerification()->delete();
        $otpVerification = OtpVerification::createOtp($user, $user->phone);

        // Resend OTP to the new number (use plain OTP)
        \sendsms($user->id, "Your ElanSwap OTP is {$otpVerification->otp_plain}");

        // Keep session intact; just confirm we still have it
        $request->session()->put('otp_verification_user_id', $user->id);

        return back()->with('status', 'Phone number updated. A new OTP has been sent.');
    }
}
