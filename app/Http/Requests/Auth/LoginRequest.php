<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'phone' => ['required', 'string', 'min:9'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // Normalize phone to international format without plus: 255XXXXXXXXX
        $rawPhone = preg_replace('/\D+/', '', (string) $this->input('phone'));
        if (str_starts_with($rawPhone, '0') && strlen($rawPhone) === 10) {
            $normalizedPhone = '255' . substr($rawPhone, 1);
        } elseif (strlen($rawPhone) === 9) {
            $normalizedPhone = '255' . $rawPhone;
        } elseif (str_starts_with($rawPhone, '255') && strlen($rawPhone) >= 12) {
            $normalizedPhone = substr($rawPhone, 0, 12);
        } else {
            $normalizedPhone = $rawPhone; // will fail Auth::attempt and show error
        }

        // Get the credentials from the request
        $credentials = [
            'phone' => $normalizedPhone,
            'password' => $this->input('password')
        ];

        if (! Auth::attempt($credentials, $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'phone' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'phone' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            'phone.required' => 'The phone number is required.',
            'phone.regex' => 'Please enter a valid 9-digit phone number (without the leading 0).',
            'password.required' => 'The password field is required.',
        ];
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    protected function throttleKey(): string
    {
        $rawPhone = preg_replace('/\D+/', '', (string) $this->input('phone'));
        if (str_starts_with($rawPhone, '0') && strlen($rawPhone) === 10) {
            $normalizedPhone = '255' . substr($rawPhone, 1);
        } elseif (strlen($rawPhone) === 9) {
            $normalizedPhone = '255' . $rawPhone;
        } elseif (str_starts_with($rawPhone, '255') && strlen($rawPhone) >= 12) {
            $normalizedPhone = substr($rawPhone, 0, 12);
        } else {
            $normalizedPhone = $rawPhone;
        }
        return Str::transliterate(Str::lower($normalizedPhone).'|'.$this->ip());
    }
}
