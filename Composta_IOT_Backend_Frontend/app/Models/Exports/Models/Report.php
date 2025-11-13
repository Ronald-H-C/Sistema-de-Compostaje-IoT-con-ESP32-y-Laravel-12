<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $table = 'reports';

    protected $fillable = [
        'type',
        'registrationDate',
        'file_route',
        'generate_for',
        'idReport',
    ];
        public $timestamps = false;
     public function parent()
    {
        return $this->belongsTo(Report::class, 'idReport');
    }

    /**
     * Relación con los reportes hijos (versiones/derivados).
     */
    public function children()
    {
        return $this->hasMany(Report::class, 'idReport');
    }

}
