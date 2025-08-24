<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'user_id',
        'from_region_id',
        'from_district_id',
        'from_station_id',
        'to_region_id',
        'to_district_id',
        'reason',
        'status',
        'submitted_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function fromRegion(): BelongsTo { return $this->belongsTo(Region::class, 'from_region_id'); }
    public function fromDistrict(): BelongsTo { return $this->belongsTo(District::class, 'from_district_id'); }
    public function fromStation(): BelongsTo { return $this->belongsTo(Station::class, 'from_station_id'); }
    public function toRegion(): BelongsTo { return $this->belongsTo(Region::class, 'to_region_id'); }
    public function toDistrict(): BelongsTo { return $this->belongsTo(District::class, 'to_district_id'); }
    public function pairedApplication(): BelongsTo { return $this->belongsTo(Application::class, 'paired_application_id'); }
}
