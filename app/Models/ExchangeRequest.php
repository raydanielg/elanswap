<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExchangeRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'requester_id',
        'owner_id',
        'application_id',
        'requester_application_id',
        'message',
        'status',
    ];

    protected $attributes = [
        'status' => 'pending',
    ];

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class, 'application_id');
    }

    public function requesterApplication(): BelongsTo
    {
        return $this->belongsTo(Application::class, 'requester_application_id');
    }
}
