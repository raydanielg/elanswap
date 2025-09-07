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
        'otp', // store 6-digit plain to satisfy varchar(6) column
        'otp_hash', // bcrypt hash for verification
        'otp_plain',
        'expires_at',
        'is_verified'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_verified' => 'boolean',
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
            'otp' => $otp, // keep 6-digit plain in varchar(6)
            'otp_hash' => bcrypt($otp), // store secure hash for verification
            'otp_plain' => $otp, // For debugging; remove in production
            'expires_at' => now()->addMinutes(15),
        ]);
    }

    public function isExpired()
    {
        // If there is no expiry set, treat as expired for safety
        if (!$this->expires_at) {
            return true;
        }
        // With casts, expires_at is a Carbon instance
        return $this->expires_at->isPast();
    }

    public function verify($otp)
    {
        // Check if OTP is expired first
        if ($this->isExpired()) {
            return false;
        }

        // Verify the OTP using hash comparison against otp_hash when available
        if (!empty($this->otp_hash)) {
            $isValid = Hash::check($otp, $this->otp_hash);
        } else {
            // Fallback: if legacy records stored hash in 'otp', still check it
            $isValid = Hash::check($otp, $this->otp);
        }
        
        // If valid, mark as verified
        if ($isValid) {
            $this->update(['is_verified' => true]);
            return true;
        }
        
        return false;
    }
}
