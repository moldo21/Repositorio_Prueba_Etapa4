<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'nombre'    => $this->nombre,
            'apellidos' => $this->apellidos,
            'email'     => $this->email,
            'rol'       => $this->whenLoaded('rol', fn() => [
                'id'     => $this->rol->id,
                'nombre' => $this->rol->nombre,
            ]),
            'creado_en' => $this->created_at?->toDateTimeString(),
        ];
    }
}
