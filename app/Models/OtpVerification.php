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

        return self::create([
            'user_id' => $user->id,
            'phone' => $phone,
            'otp' => self::generateOtp(),
            'expires_at' => now()->addMinutes(15), // OTP valid for 15 minutes
        ]);
    }

    public function isExpired()
    {
        return $this->expires_at->isPast();
    }

    public function verify($otp)
    {
        if ($this->otp === $otp && !$this->isExpired()) {
            $this->update(['is_verified' => true]);
            return true;
        }
        return false;
    }
}
