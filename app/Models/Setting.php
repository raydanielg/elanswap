<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'site_name',
        'tagline',
        'contact_email',
        'contact_phone',
        'contact_address',
        'logo_path',
        'favicon_path',
        'social_links',
        'mail_from_name',
        'mail_from_address',
        'smtp_host',
        'smtp_port',
        'smtp_username',
        'smtp_password',
        'smtp_encryption',
    ];

    protected $casts = [
        'social_links' => 'array',
    ];
}
