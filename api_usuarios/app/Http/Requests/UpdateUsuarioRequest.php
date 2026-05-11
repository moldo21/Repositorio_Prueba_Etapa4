<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUsuarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('usuario');

        return [
            'nombre'    => 'sometimes|string|max:255',
            'apellidos' => 'sometimes|string|max:255',
            'email'     => 'sometimes|email|unique:users,email,' . $userId,
            'password'  => 'sometimes|string|min:4|confirmed',
            'rol_id'    => 'sometimes|integer|exists:roles,id',
        ];
    }
}
