<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prototype extends Model
{
    use HasFactory;

    protected $table = 'prototypes';

    protected $fillable = [
        'idUser',
        'name',
        'code',
        'state',
        'updated_by',
    ];

    public $timestamps = true;

    /**
     * Relaciones
     */
   public function user()
    {
        return $this->belongsTo(User::class, 'idUser', 'id');
    }
}
