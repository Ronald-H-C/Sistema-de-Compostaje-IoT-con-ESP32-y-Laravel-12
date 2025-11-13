<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentProduct extends Model
{
    use HasFactory;

    protected $table = 'payment_products'; // Nombre real de la tabla
    protected $primaryKey = 'id';

    protected $fillable = [
        'idSale',
        'idFertilizer',
        'amount',
        'price',
        'subtotal',
        'updated_by',
    ];

    public function fertilizer()
    {
        return $this->belongsTo(Fertilizer::class, 'idFertilizer', 'id');
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'idSale');
    }

}
