<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $fillable = [
        'usuario_id',
        'estado',
        'subtotal',
        'impuestos',
        'total',
    ];

    public function items()
    {
        return $this->hasMany(PedidoItem::class);
    }
}
