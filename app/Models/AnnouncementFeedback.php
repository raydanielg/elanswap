<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnnouncementFeedback extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'feature_id',
        'reaction', // 'like' or 'dislike'
    ];
}
