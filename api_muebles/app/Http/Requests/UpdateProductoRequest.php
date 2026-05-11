<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductoRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'categoria_id'    => 'sometimes|exists:categorias,id',
            'nombre'          => 'sometimes|string|max:80',
            'descripcion'     => 'sometimes|string|max:500',
            'precio'          => 'sometimes|numeric|min:0',
            'stock'           => 'sometimes|integer|min:0',
            'materiales'      => 'nullable|string|max:255',
            'dimensiones'     => 'nullable|string|max:255',
            'color_principal' => 'nullable|string|max:50',
            'destacado'       => 'nullable|boolean',
            'imagen'          => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }
}
