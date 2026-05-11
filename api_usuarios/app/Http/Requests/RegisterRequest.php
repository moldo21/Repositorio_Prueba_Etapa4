<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre'    => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|string|min:4|confirmed',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required'    => 'El nombre es obligatorio.',
            'apellidos.required' => 'Los apellidos son obligatorios.',
            'email.required'     => 'El email es obligatorio.',
            'email.email'        => 'El email no tiene un formato válido.',
            'email.unique'       => 'Este email ya está registrado.',
            'password.required'  => 'La contraseña es obligatoria.',
            'password.min'       => 'La contraseña debe tener al menos 4 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ];
    }
}
