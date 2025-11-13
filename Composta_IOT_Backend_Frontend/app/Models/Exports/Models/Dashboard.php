<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dashboard extends Model
{
    use HasFactory;

    // Nombre de la tabla si no sigue la convención plural de Laravel
    protected $table = 'readings';

    // Campos que pueden ser asignados masivamente
    protected $fillable = [
        'idUser',
        'temperature',
        'humidity',
        'status',
        'ds18b20_temp',
        'soil_moisture',
        'mq135',
        'air_quality_status',
        'ammonia',
        'co2',
        'co',
        'benzene',
        'alcohol',
        'smoke',
        'date',
        'time',
    ];

    // Opcional: si no usas timestamps (created_at, updated_at)
    public $timestamps = false;
}
