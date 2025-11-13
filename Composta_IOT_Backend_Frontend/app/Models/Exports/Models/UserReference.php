<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserReference extends Model
{
    use HasFactory;

    protected $table = 'user_references';

    protected $fillable = [
        'idUser',
        'phone',
        'contact_email',
        'whatsapp_link',
        'facebook_link',
        'instagram_link',
        'youtube_link',
        'tiktok_link',
        'qr_image',
        'updated_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'idUser', 'id');
    }
}
