<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'nombre'          => $this->nombre,
            'descripcion'     => $this->descripcion,
            'precio'          => (float) $this->precio,
            'stock'           => $this->stock,
            'materiales'      => $this->materiales,
            'dimensiones'     => $this->dimensiones,
            'color_principal' => $this->color_principal,
            'destacado'       => $this->destacado,
            'imagen_principal' => $this->imagen_principal,
            'imagen_url'      => $this->imagen_principal
                ? url('/imagenes/' . $this->imagen_principal)
                : null,
            'categoria'       => $this->whenLoaded('categoria', fn() => new CategoriaResource($this->categoria)),
            'galerias'        => $this->whenLoaded('galerias', fn() => GaleriaResource::collection($this->galerias)),
            'creado_en'       => $this->created_at?->toDateTimeString(),
        ];
    }
}
