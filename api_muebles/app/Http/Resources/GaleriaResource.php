<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GaleriaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'ruta'         => $this->ruta,
            'url'          => url('/imagenes/' . $this->ruta),
            'es_principal' => $this->es_principal,
            'orden'        => $this->orden,
        ];
    }
}
