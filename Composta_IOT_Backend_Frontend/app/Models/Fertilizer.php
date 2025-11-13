<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Location;
use App\Models\User;

class Fertilizer extends Model
{
    use HasFactory;

    protected $table = 'fertilizers';
    protected $primaryKey = 'id';

    protected $fillable = [
        'idUser',
        'title',
        'description',
        'type',
        'amount',
        'stock',
        'price',
        'image',
        'state',
        'updated_by',
    ];

    public function location()
    {
        return $this->hasOne(Location::class, 'idFertilizer', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'idUser', 'id');
    }

    public function modifier()
    {
        return $this->belongsTo(User::class, 'idModifier', 'id');
    }
}
