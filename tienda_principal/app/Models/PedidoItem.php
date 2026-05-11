<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PedidoItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'pedido_id',
        'producto_id',
        'nombre_producto',
        'imagen_producto',
        'cantidad',
        'precio_unitario',
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }
}
