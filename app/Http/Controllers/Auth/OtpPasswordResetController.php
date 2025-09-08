<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Jobs\SendSms;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class OtpPasswordResetController extends Controller
{
    /**
     * Show new password form after OTP verification.
     */
    public function create(Request $request): View|RedirectResponse
    {
        $userId = $request->session()->get('password_reset_user_id');
        if (!$userId) {
            return redirect()->route('password.request')->withErrors(['phone' => 'Your session expired. Please request a new OTP.']);
        }
        return view('auth.reset-password-otp');
    }

    /**
     * Store new password.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $userId = $request->session()->get('password_reset_user_id');
        if (!$userId) {
            return redirect()->route('password.request')->withErrors(['phone' => 'Your session expired. Please request a new OTP.']);
        }

        $user = User::find($userId);
        if (!$user) {
            return redirect()->route('password.request')->withErrors(['phone' => 'Account not found. Please request a new OTP.']);
        }

        $plain = (string) $request->input('password');
        $user->password = Hash::make($plain);
        $user->save();

        // Queue SMS with credentials (name, phone, password, region only)
        try {
            $region = $user->region?->name ?? '-';
            $message = "ElanSwap: Neno siri limebadilishwa.\nJina: {$user->name}\nSimu: +{$user->phone}\nMkoa: {$region}\nPassword: {$plain}";
            // Dispatch queued SMS to the user's phone using user_id
            SendSms::dispatch($user->id, null, $message);
        } catch (\Throwable $e) {
            \Log::warning('Failed to dispatch password reset SMS: ' . $e->getMessage());
        }

        // Clear session keys
        $request->session()->forget('password_reset_user_id');

        return redirect()->route('login')->with('status', 'Password updated successfully. You can now log in.');
    }
}
