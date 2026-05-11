<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Session;

class CarritoControllerTest extends TestCase
{
    private function iniciarSesionUsuario($id = 2, $rol = 'USUARIO')
    {
        $sesionId = 'test_sesion_' . $id;
        $usuarioActivo = [
            'id' => $id,
            'email' => "user{$id}@correo.com",
            'nombre' => "Usuario{$id}",
            'rol' => $rol,
            'fecha_ingreso' => now()->toDateTimeString(),
            'sesionId' => $sesionId
        ];
        Session::put('usuarios', [$sesionId => json_encode($usuarioActivo)]);
        Session::put('autorizacion_usuario', true);
        return $sesionId;
    }

    public function test_agregar_mueble_al_carrito_desde_listado()
    {
        $sesionId = $this->iniciarSesionUsuario();
        $muebleId = 1;

        $response = $this->post(route('carrito.add', ['mueble' => $muebleId, 'sesionId' => $sesionId, 'testing' => true]));
        $response->assertRedirect(route('carrito.show', ['sesionId' => $sesionId]));
        $response->assertSessionHas('success');
    }

    public function test_aumentar_y_disminuir_cantidad()
    {
        $sesionId = $this->iniciarSesionUsuario();
        $muebleId = 1;

        $this->post(route('carrito.add', ['mueble' => $muebleId, 'sesionId' => $sesionId, 'testing' => true]));

        $response = $this->post(route('carrito.aumentar', ['id' => $muebleId, 'sesionId' => $sesionId]));
        $response->assertRedirect();
        $carrito = Session::get('carrito_2');
        $this->assertEquals(2, $carrito[$muebleId]['cantidad']);

        $response = $this->post(route('carrito.disminuir', ['id' => $muebleId, 'sesionId' => $sesionId]));
        $response->assertRedirect();
        $carrito = Session::get('carrito_2');
        $this->assertEquals(1, $carrito[$muebleId]['cantidad']);
    }

    public function test_no_se_puede_agregar_mas_del_stock()
    {
        $sesionId = $this->iniciarSesionUsuario();
        $muebleId = 1;

        $carrito = [
            $muebleId => [
                'nombre' => 'MuebleTest',
                'precio' => 100,
                'cantidad' => 5,
                'stock' => 5
            ]
        ];
        Session::put('carrito_2', $carrito);

        $response = $this->post(route('carrito.aumentar', ['id' => $muebleId, 'sesionId' => $sesionId]));
        $response->assertSessionHasErrors();
    }

    public function test_eliminar_y_vaciar_carrito()
    {
        $sesionId = $this->iniciarSesionUsuario();
        $muebleId = 1;

        $this->post(route('carrito.add', ['mueble' => $muebleId, 'sesionId' => $sesionId, 'testing' => true]));

        $response = $this->post(route('carrito.remove', ['mueble' => $muebleId, 'sesionId' => $sesionId]));
        $response->assertRedirect();
        $carrito = Session::get('carrito_2');
        $this->assertEmpty($carrito);

        $this->post(route('carrito.add', ['mueble' => $muebleId, 'sesionId' => $sesionId, 'testing' => true]));
        $response = $this->post(route('carrito.clear', ['sesionId' => $sesionId]));
        $response->assertRedirect();
        $carrito = Session::get('carrito_2');
        $this->assertEmpty($carrito);
    }
}
