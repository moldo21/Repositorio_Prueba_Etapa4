<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Galeria extends Model
{
    use HasFactory;

    protected $table = 'galerias';

    protected $fillable = [
        'producto_id',
        'ruta',
        'es_principal',
        'orden',
    ];

    protected $casts = [
        'es_principal' => 'boolean',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
