<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanChangeRequest extends Model
{
    use HasFactory;

    protected $table = 'plan_change_requests';

    protected $fillable = [
        'idUser',
        'idPlan',
        'image',
        'state',
        'observations',
        'created_at',
    ];

    public $timestamps = true;

    /**
     * Relaciones
     */
   public function user()
    {
        return $this->belongsTo(User::class, 'idUser', 'id');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'idPlan', 'id');
    }
}
