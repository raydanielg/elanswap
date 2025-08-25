<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','title','slug','excerpt','body','image_path','published_at'
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }

    public function tags(): BelongsToMany { return $this->belongsToMany(Tag::class); }
}
