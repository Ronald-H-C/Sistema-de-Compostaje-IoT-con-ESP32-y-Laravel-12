<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPlan extends Model
{
    use HasFactory;

    protected $table = 'user_plans'; // nombre explícito de la tabla

    public $timestamps = true; // para created_at y updated_at

    protected $fillable = [
        'idUser',
        'idPlan',
        'started_at',
        'expires_at',
        'active'
    ];

    /**
     * Relación: este plan pertenece a un usuario
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'idUser', 'id');
    }

    /**
     * Relación: este plan pertenece a un plan definido
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class, 'idPlan', 'id');
    }
}
