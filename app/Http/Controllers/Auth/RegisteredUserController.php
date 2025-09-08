<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\OtpVerification;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    // Using global helper sendsms() for SMS sending

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            // Accept common TZ formats: 0712xxxxxxx, 712xxxxxx, +2557xxxxxxx, 2557xxxxxxx
            'phone' => ['required', 'string', 'min:9'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            // Password: allow any characters, minimum 4
            'password' => ['required', 'string', 'min:4', 'confirmed'],
        ], [
            'name.required' => 'The name field is required.',
            'phone.required' => 'The phone number is required.',
            'email.required' => 'The email field is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already registered.',
            'password.required' => 'The password field is required.',
            'password.confirmed' => 'The password confirmation does not match.',
        ]);

        // Normalize phone to international format without plus: 2557XXXXXXXX
        $rawPhone = preg_replace('/\D+/', '', $request->phone ?? '');
        if (str_starts_with($rawPhone, '0') && strlen($rawPhone) === 10) {
            // 07XXXXXXXX -> 2557XXXXXXXX
            $normalizedPhone = '255' . substr($rawPhone, 1);
        } elseif (strlen($rawPhone) === 9) {
            // 7XXXXXXXX -> 2557XXXXXXXX
            $normalizedPhone = '255' . $rawPhone;
        } elseif (str_starts_with($rawPhone, '255') && strlen($rawPhone) === 12) {
            $normalizedPhone = $rawPhone;
        } elseif (str_starts_with($rawPhone, '255') && strlen($rawPhone) > 12) {
            $normalizedPhone = substr($rawPhone, 0, 12);
        } elseif (str_starts_with($rawPhone, '2557') && strlen($rawPhone) === 13) {
            // Rare case with extra digit; trim to 12
            $normalizedPhone = substr($rawPhone, 0, 12);
        } elseif (str_starts_with($rawPhone, '255') && strlen($rawPhone) < 12) {
            // Incomplete, fail later
            $normalizedPhone = $rawPhone;
        } elseif (str_starts_with($rawPhone, '255') === false && str_starts_with($rawPhone, '7') === false && str_starts_with($rawPhone, '0') === false) {
            $normalizedPhone = $rawPhone;
        } else {
            $normalizedPhone = $rawPhone;
        }

        // Basic sanity: 255 + 9 digits => length 12 (e.g., 2556..., 2557...)
        if (!preg_match('/^255\d{9}$/', $normalizedPhone)) {
            return back()->withErrors([
                'phone' => 'Please enter a valid Tanzanian phone number (e.g., 0712xxxxxxx, 712xxxxxxx, or +255xxxxxxxxx).',
            ])->withInput();
        }

        // Uniqueness on normalized phone
        if (User::where('phone', $normalizedPhone)->exists()) {
            return back()->withErrors([
                'phone' => 'This phone number is already registered.',
            ])->withInput();
        }

        try {
            // Create the user and mark as verified (OTP not required)
            $user = User::create([
                'name' => $request->name,
                'phone' => $normalizedPhone,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'user',
                'is_verified' => true,
                'email_verified_at' => now(),
            ]);

            // Dispatch registered event
            event(new Registered($user));

            // Log the user in directly
            Auth::login($user);

            // SMS notifications: welcome user + notify admins
            try {
                // Welcome SMS to the user
                $welcome = "Karibu ElanSwap, {$user->name}! Akaunti yako imesajiliwa kikamilifu. Unaweza kuanza kutumia mfumo sasa.";
                if (function_exists('sendsms')) {
                    @sendsms($user->id, $welcome);
                }

                // Notify admins about new registration
                $admins = ['+255 757 756 184', '0742710054'];
                $adminMsg = "ElanSwap: Mtumiaji mpya amejisajili.\nJina: {$user->name}\nSimu: {$normalizedPhone}\nMuda: " . now()->format('Y-m-d H:i');
                if (function_exists('sendsms_to_number')) {
                    foreach ($admins as $adminPhone) {
                        @sendsms_to_number($adminPhone, $adminMsg, $user->id);
                    }
                }
            } catch (\Throwable $e) {
                \Log::warning('SMS notification failed on register: ' . $e->getMessage());
            }

            // Redirect based on role
            if ($user->role === 'superadmin') {
                return redirect()->route('superadmin.dashboard');
            }
            if (in_array($user->role, ['admin'], true)) {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('dashboard');

        } catch (\Exception $e) {
            return back()->withErrors([
                'registration' => 'An error occurred during registration. Please try again.'
            ]);
        }
    }
}
