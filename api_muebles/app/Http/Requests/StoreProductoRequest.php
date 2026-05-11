<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductoRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'categoria_id'    => 'required|exists:categorias,id',
            'nombre'          => 'required|string|max:80',
            'descripcion'     => 'required|string|max:500',
            'precio'          => 'required|numeric|min:0',
            'stock'           => 'required|integer|min:0',
            'materiales'      => 'nullable|string|max:255',
            'dimensiones'     => 'nullable|string|max:255',
            'color_principal' => 'nullable|string|max:50',
            'destacado'       => 'nullable|boolean',
            'imagen'          => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'categoria_id.required' => 'La categoría es obligatoria.',
            'categoria_id.exists'   => 'La categoría seleccionada no existe.',
            'nombre.required'       => 'El nombre del producto es obligatorio.',
            'precio.required'       => 'El precio es obligatorio.',
            'precio.numeric'        => 'El precio debe ser un número.',
            'stock.required'        => 'El stock es obligatorio.',
            'stock.integer'         => 'El stock debe ser un número entero.',
        ];
    }
}
