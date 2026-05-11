<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'productos';

    protected $fillable = [
        'categoria_id',
        'nombre',
        'descripcion',
        'precio',
        'stock',
        'materiales',
        'dimensiones',
        'color_principal',
        'destacado',
        'imagen_principal',
    ];

    protected $casts = [
        'precio'    => 'decimal:2',
        'destacado' => 'boolean',
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function galerias()
    {
        return $this->hasMany(Galeria::class);
    }
}
