<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $table = 'sales';

    protected $fillable = [
        'idClient',
        'idUser',
        'date',
        'total',
        'pay',
        'image',
        'state',
        'updated_by',
    ];


    public function products()
    {
        return $this->hasMany(PaymentProduct::class, 'idSale');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'idUser');
    }

    // Usuario que actualizó la venta
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    

    public function client()
    {
        return $this->belongsTo(User::class, 'idClient');
    }
    
}
