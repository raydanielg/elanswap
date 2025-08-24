<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Region extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public $timestamps = false;

    public function districts(): HasMany
    {
        return $this->hasMany(District::class);
    }

    public function applicationsFrom(): HasMany
    {
        return $this->hasMany(Application::class, 'from_region_id');
    }

    public function applicationsTo(): HasMany
    {
        return $this->hasMany(Application::class, 'to_region_id');
    }
}
