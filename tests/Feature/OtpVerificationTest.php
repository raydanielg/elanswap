<?php

namespace Tests\Feature;

use App\Models\OtpVerification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class OtpVerificationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the OTP verification page is accessible after registration.
     */
    public function test_otp_verification_page_is_accessible_after_registration()
    {
        // Register a new user
        $user = User::create([
            'name' => 'Test User',
            'phone' => '123456789',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'is_verified' => false,
        ]);

        // Simulate the registration flow by setting the session
        Session::put('otp_verification_user_id', $user->id);

        // Try to access the OTP verification page
        $response = $this->get(route('otp.verify'));
        $response->assertStatus(200);
        $response->assertViewIs('auth.verify-otp');
    }

    /**
     * Test that OTP verification works with a valid OTP.
     */
    public function test_otp_verification_with_valid_otp()
    {
        // Create a user
        $user = User::create([
            'name' => 'Test User',
            'phone' => '123456789',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'is_verified' => false,
        ]);

        // Create an OTP verification record
        $otpVerification = OtpVerification::create([
            'user_id' => $user->id,
            'phone' => $user->phone,
            'otp' => '123456',
            'expires_at' => now()->addMinutes(15),
            'is_verified' => false,
        ]);

        // Simulate the registration flow by setting the session
        Session::put('otp_verification_user_id', $user->id);

        // Submit the OTP
        $response = $this->post(route('otp.verify'), [
            'otp' => '123456',
        ]);

        // Assert the user is redirected to the home page
        $response->assertRedirect(route('home'));
        $response->assertSessionHas('status');

        // Assert the user is marked as verified
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'is_verified' => true,
        ]);

        // Assert the OTP is marked as verified
        $this->assertDatabaseHas('otp_verifications', [
            'id' => $otpVerification->id,
            'is_verified' => true,
        ]);
    }

    /**
     * Test that OTP verification fails with an invalid OTP.
     */
    public function test_otp_verification_with_invalid_otp()
    {
        // Create a user
        $user = User::create([
            'name' => 'Test User',
            'phone' => '123456789',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'is_verified' => false,
        ]);

        // Create an OTP verification record
        OtpVerification::create([
            'user_id' => $user->id,
            'phone' => $user->phone,
            'otp' => '123456',
            'expires_at' => now()->addMinutes(15),
            'is_verified' => false,
        ]);

        // Simulate the registration flow by setting the session
        Session::put('otp_verification_user_id', $user->id);

        // Submit an invalid OTP
        $response = $this->post(route('otp.verify'), [
            'otp' => '654321',
        ]);

        // Assert the user is redirected back with an error
        $response->assertSessionHasErrors('otp');

        // Assert the user is still not verified
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'is_verified' => false,
        ]);
    }

    /**
     * Test that resending OTP works.
     */
    public function test_resend_otp()
    {
        // Create a user
        $user = User::create([
            'name' => 'Test User',
            'phone' => '123456789',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'is_verified' => false,
        ]);

        // Create an OTP verification record
        $otpVerification = OtpVerification::create([
            'user_id' => $user->id,
            'phone' => $user->phone,
            'otp' => '123456',
            'expires_at' => now()->subMinutes(5), // Expired OTP
            'is_verified' => false,
        ]);

        // Simulate the registration flow by setting the session
        Session::put('otp_verification_user_id', $user->id);

        // Request a new OTP
        $response = $this->post(route('otp.resend'));

        // Assert the user is redirected back with a status message
        $response->assertRedirect();
        $response->assertSessionHas('status');

        // Assert the old OTP is deleted
        $this->assertDatabaseMissing('otp_verifications', [
            'id' => $otpVerification->id,
        ]);

        // Assert a new OTP is created
        $this->assertDatabaseHas('otp_verifications', [
            'user_id' => $user->id,
            'phone' => $user->phone,
            'is_verified' => false,
        ]);
    }
}
