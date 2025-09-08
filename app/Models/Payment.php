<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'method', 'provider_reference', 'orderid', 'transid', 'phone', 'amount', 'currency', 'status', 'meta', 'paid_at'
    ];

    protected $casts = [
        'meta' => 'array',
        'paid_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Always store phone as 255XXXXXXXXX (E.164 without plus) when setting the attribute.
     */
    public function setPhoneAttribute($value): void
    {
        $raw = preg_replace('/[^0-9+]/', '', (string) $value) ?? '';
        if ($raw === '') { $this->attributes['phone'] = $raw; return; }
        if (str_starts_with($raw, '+')) { $raw = substr($raw, 1); }

        // Already E.164 TZ with country code
        if (str_starts_with($raw, '255') && strlen($raw) >= 12) {
            $this->attributes['phone'] = substr($raw, 0, 12);
            return;
        }

        // Local formats to E.164 TZ
        if (preg_match('/^0[67][0-9]{8}$/', $raw)) {
            $this->attributes['phone'] = '255' . substr($raw, 1);
            return;
        }
        if (preg_match('/^[67][0-9]{8}$/', $raw)) {
            $this->attributes['phone'] = '255' . $raw;
            return;
        }

        // Fallback: try last 9 digits
        $digits = preg_replace('/[^0-9]/', '', $raw) ?? '';
        if (strlen($digits) >= 9) {
            $this->attributes['phone'] = '255' . substr($digits, -9);
            return;
        }

        // As a last resort, store digits (to avoid data loss)
        $this->attributes['phone'] = $digits;
    }
}
