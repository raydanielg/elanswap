<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class OtpVerification extends Model
{
    protected $fillable = [
        'user_id',
        'phone',
        'otp',
        'expires_at',
        'is_verified'
    ];

    protected $dates = [
        'expires_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function generateOtp()
    {
        return str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    public static function createOtp($user, $phone)
    {
        // Delete any existing OTPs for this user
        self::where('user_id', $user->id)->delete();

        $otp = self::generateOtp();
        
        return self::create([
            'user_id' => $user->id,
            'phone' => $phone,
            'otp' => bcrypt($otp), // Store hashed OTP
            'otp_plain' => $otp, // Store plain OTP for debugging (remove in production)
            'expires_at' => now()->addMinutes(15), // OTP valid for 15 minutes
        ]);
    }

    public function isExpired()
    {
        return $this->expires_at->isPast();
    }

    public function verify($otp)
    {
        // Check if OTP is expired first
        if ($this->isExpired()) {
            return false;
        }

        // Verify the OTP using hash comparison
        $isValid = Hash::check($otp, $this->otp);
        
        // If valid, mark as verified
        if ($isValid) {
            $this->update(['is_verified' => true]);
            return true;
        }
        
        return false;
    }
}
