<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Session;

// Estos Tests son de la anterior etapa
class AdministracionCategoriasTest extends TestCase
{
    private function iniciarSesionAdmin()
    {
        $sesionId = 'admin_sesion';
        $usuarioActivo = [
            'id' => 1,
            'email' => 'admin@correo.com',
            'nombre' => 'juan',
            'rol' => 'ADMIN',
            'fecha_ingreso' => now()->toDateTimeString(),
            'sesionId' => $sesionId
        ];
        Session::put('usuarios', [$sesionId => json_encode($usuarioActivo)]);
        Session::put('autorizacion_usuario', true);
        return $sesionId;
    }

    private function iniciarSesionUsuarioNormal()
    {
        $sesionId = 'user_sesion';
        $usuarioActivo = [
            'id' => 2,
            'email' => 'user@correo.com',
            'nombre' => 'usuario',
            'rol' => 'USUARIO',
            'fecha_ingreso' => now()->toDateTimeString(),
            'sesionId' => $sesionId
        ];
        Session::put('usuarios', [$sesionId => json_encode($usuarioActivo)]);
        Session::put('autorizacion_usuario', true);
        return $sesionId;
    }
}
