<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'avatar_path',
        'password',
        'role',
        'phone',
        'is_verified',
        'phone_verified_at',
        'region_id',
        'district_id',
        'category_id',
        'station_id',
        'is_banned',
        'banned_at',
        'ban_reason',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'is_verified' => 'boolean',
        'is_banned' => 'boolean',
        'banned_at' => 'datetime',
    ];
    
    // Use default primary key (id) for auth identifier; do not override.

    public function otpVerification()
    {
        return $this->hasOne(OtpVerification::class);
    }

    /**
     * Mark the given user's phone as verified.
     *
     * @return bool
     */
    public function markPhoneAsVerified()
    {
        return $this->forceFill([
            'phone_verified_at' => $this->freshTimestamp(),
            'is_verified' => true,
        ])->save();
    }
    
    /**
     * Determine if the user has verified their phone number.
     *
     * @return bool
     */
    public function hasVerifiedPhone()
    {
        return ! is_null($this->phone_verified_at) && $this->is_verified;
    }

    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName()
    {
        return 'remember_token';
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    /**
     * Get the username attribute for the user.
     *
     * @return string
     */
    public function username()
    {
        return 'phone';
    }

    /**
     * Route notifications for the mail channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return array|string
     */
    public function routeNotificationForMail($notification)
    {
        return $this->email;
    }

    /**
     * Route notifications for the database channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return array
     */
    public function routeNotificationForDatabase($notification)
    {
        return ['phone' => $this->phone];
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Check if user has completed their profile
     *
     * @return bool
     */
    public function hasCompletedProfile()
    {
        return !empty($this->name) &&
               !empty($this->phone) &&
               !empty($this->email) &&
               !empty($this->password) &&
               !empty($this->region_id) &&
               !empty($this->district_id) &&
               !empty($this->category_id) &&
               !empty($this->station_id);
    }

    /**
     * Get profile completion percentage
     *
     * @return int
     */
    public function getProfileCompletionPercentage()
    {
        $fields = ['name', 'phone', 'email', 'password', 'region_id', 'district_id', 'category_id', 'station_id'];
        $completed = 0;
        
        foreach ($fields as $field) {
            if (!empty($this->$field)) {
                $completed++;
            }
        }
        
        return round(($completed / count($fields)) * 100);
    }

    /**
     * Get missing profile fields
     *
     * @return array
     */
    public function getMissingProfileFields()
    {
        $fields = [
            'name' => 'Full Name',
            'phone' => 'Phone Number',
            'email' => 'Email Address',
            'region_id' => 'Region (Mkoa)',
            'district_id' => 'District (Wilaya)',
            'category_id' => 'Category (Sekta)',
            'station_id' => 'Work Station (Kituo cha Kazi)',
        ];
        
        $missing = [];
        
        foreach ($fields as $field => $label) {
            if (empty($this->$field)) {
                $missing[] = $label;
            }
        }
        
        return $missing;
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function station(): BelongsTo
    {
        return $this->belongsTo(Station::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
