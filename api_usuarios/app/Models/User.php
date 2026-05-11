<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'nombre',
        'apellidos',
        'email',
        'password',
        'rol_id',
        'intentos_fallidos',
        'bloqueado_hasta',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'bloqueado_hasta'   => 'datetime',
            'password'          => 'hashed',
        ];
    }

    public function rol()
    {
        return $this->belongsTo(Rol::class);
    }
}
