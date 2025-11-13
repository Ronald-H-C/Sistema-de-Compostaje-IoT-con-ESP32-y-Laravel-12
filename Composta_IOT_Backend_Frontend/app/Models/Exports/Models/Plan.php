<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $table = 'plans';

    protected $fillable = [
        'name',
        'description',
        'cost',
        'duration',
        'state',
        'post_limit',
        'created_by',
        'updated_by',
    ];
}
