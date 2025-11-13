<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;



    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'firstLastName',
        'secondLastName',
        'username',
        'email',
        'password',
        'role',
        'state',
        'remember_token',
        'updated_at',
        'updated_by'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    public $timestamps = false;

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function sales()
{
    return $this->hasMany(Sale::class, 'idUser');
}

// Ventas actualizadas por el usuario
public function updatedSales()
{
    return $this->hasMany(Sale::class, 'updated_by');
}

public function reference()
    {
        return $this->hasOne(UserReference::class, 'idUser', 'id');
    }

    public function userPlans(): HasMany
{
        return $this->hasMany(UserPlan::class, 'idUser', 'id');
}

    // Relación para obtener el ÚNICO plan ACTIVO actual
public function activePlan(): HasOne
{
        // Asume que solo puede haber un plan activo a la vez
        return $this->hasOne(UserPlan::class, 'idUser', 'id')->where('active', 1);
}
}

