<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Material extends Model
{
    protected $fillable = [
        'name',
        'image',
        'description',
        'clasification',
        'aptitude',
        'type_category'
    ];
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return null;
        }

        // asset() genera la URL completa basada en tu dominio
        return asset($this->image);
    }
}
