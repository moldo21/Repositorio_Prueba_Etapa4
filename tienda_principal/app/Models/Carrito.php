<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrito extends Model
{
    use HasFactory;

    protected $table = 'carritos';

    protected $fillable = [
        'usuario_id',
        'sesionId',
    ];

    // Relación: un carrito tiene muchos items
    public function items()
    {
        return $this->hasMany(CarritoItem::class);
    }
}
