<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Usuario;
use App\Models\Rol;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UsuarioTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Crear roles necesarios
        Rol::create(['id' => 1, 'nombre' => 'Admin']);
        Rol::create(['id' => 2, 'nombre' => 'Gestor']);
        Rol::create(['id' => 3, 'nombre' => 'Cliente']);
    }

    public function test_crear_usuario()
    {
        $usuario = Usuario::create([
            'nombre' => 'Juan',
            'apellidos' => 'Pérez',
            'email' => 'juan@test.com',
            'password' => bcrypt('1234'),
            'rol_id' => 1,
        ]);

        $this->assertEquals('Juan', $usuario->nombre);
        $this->assertEquals('juan@test.com', $usuario->email);
        $this->assertEquals(1, $usuario->rol_id);
    }

    public function test_relacion_con_rol()
    {
        $usuario = Usuario::create([
            'nombre' => 'Test',
            'apellidos' => 'Usuario',
            'email' => 'test@test.com',
            'password' => bcrypt('pass'),
            'rol_id' => 1,
        ]);

        $this->assertInstanceOf(Rol::class, $usuario->rol);
        $this->assertEquals('Admin', $usuario->rol->nombre);
    }

    public function test_password_se_oculta_en_array()
    {
        $usuario = Usuario::create([
            'nombre' => 'Test',
            'apellidos' => 'Secreto',
            'email' => 'secreto@test.com',
            'password' => bcrypt('password'),
            'rol_id' => 3,
        ]);

        $array = $usuario->toArray();
        $this->assertArrayNotHasKey('password', $array);
    }

    public function test_usuario_puede_tener_intentos_fallidos_y_bloqueo()
    {
        $usuario = Usuario::create([
            'nombre' => 'Bloqueado',
            'apellidos' => 'Test',
            'email' => 'bloq@test.com',
            'password' => bcrypt('pass'),
            'rol_id' => 3,
            'intentos_fallidos' => 3,
            'bloqueado_hasta' => now()->addMinutes(5),
        ]);

        $this->assertEquals(3, $usuario->intentos_fallidos);
        $this->assertNotNull($usuario->bloqueado_hasta);
        $this->assertTrue($usuario->bloqueado_hasta->isFuture());
    }
}
