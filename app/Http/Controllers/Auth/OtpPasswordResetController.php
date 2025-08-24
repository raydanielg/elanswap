<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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

        $user->password = Hash::make($request->input('password'));
        $user->save();

        // Clear session keys
        $request->session()->forget('password_reset_user_id');

        return redirect()->route('login')->with('status', 'Password updated successfully. You can now log in.');
    }
}
