<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarritoItem extends Model
{
    use HasFactory;

    protected $table = 'carrito_items';

    protected $fillable = [
        'carrito_id',
        'producto_id',
        'nombre_producto',
        'imagen_producto',
        'cantidad',
        'precio_unitario',
    ];

    protected $casts = [
        'precio_unitario' => 'decimal:2',
    ];

    public function carrito()
    {
        return $this->belongsTo(Carrito::class);
    }
}
