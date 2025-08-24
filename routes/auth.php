<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\OtpPasswordResetController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\OtpVerificationController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

// OTP Verification Routes - outside guest middleware to allow access after registration
Route::middleware('web')->group(function () {
    Route::get('/verify-otp', [OtpVerificationController::class, 'show'])
        ->middleware(\App\Http\Middleware\EnsureUserIsUnverified::class)
        ->name('otp.verify');
        
    Route::post('/verify-otp', [OtpVerificationController::class, 'verify'])
        ->middleware(\App\Http\Middleware\EnsureUserIsUnverified::class)
        ->name('otp.verify.submit');
        
    Route::post('/resend-otp', [OtpVerificationController::class, 'resend'])
        ->middleware(\App\Http\Middleware\EnsureUserIsUnverified::class)
        ->name('otp.resend');

    Route::post('/change-number', [OtpVerificationController::class, 'changeNumber'])
        ->middleware(\App\Http\Middleware\EnsureUserIsUnverified::class)
        ->name('otp.change_number');
});

Route::middleware('guest')->group(function () {

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    // OTP-based password reset flow (after OTP verification)
    Route::get('reset-password-otp', [OtpPasswordResetController::class, 'create'])
        ->name('password.reset.otp.form');

    Route::post('reset-password-otp', [OtpPasswordResetController::class, 'store'])
        ->name('password.reset.otp.store');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
