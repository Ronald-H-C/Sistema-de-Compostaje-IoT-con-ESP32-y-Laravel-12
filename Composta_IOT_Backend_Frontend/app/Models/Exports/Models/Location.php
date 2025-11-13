<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Fertilizer;

class Location extends Model
{
    use HasFactory;

    protected $table = 'locations';
    protected $primaryKey = 'id';

    protected $fillable = [
        'idFertilizer',
        'latitude',
        'longitude',
        'address',
        'link_google_maps',
    ];
    public $timestamps = false;

    public function product()
    {
        return $this->belongsTo(Fertilizer::class, 'idFertilizer', 'id');
    }
}
