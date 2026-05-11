<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Usuario;
use App\Models\Rol;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Crear roles
        Rol::create(['id' => 1, 'nombre' => 'Admin']);
        Rol::create(['id' => 3, 'nombre' => 'Cliente']);

        // Crear usuario para tests
        Usuario::create([
            'nombre' => 'Test',
            'apellidos' => 'Usuario',
            'email' => 'test@test.com',
            'password' => Hash::make('password123'),
            'rol_id' => 3,
        ]);
    }

    public function test_puede_ver_formulario_login()
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
        $response->assertViewIs('login.show');
    }

    public function test_puede_ver_formulario_registro()
    {
        $response = $this->get(route('register.show'));

        $response->assertStatus(200);
        $response->assertViewIs('login.register');
    }

    public function test_login_exitoso_con_credenciales_validas()
    {
        $response = $this->post(route('login.store'), [
            'email' => 'test@test.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('principal'));
        $this->assertAuthenticatedAs(Usuario::where('email', 'test@test.com')->first());
    }

    public function test_login_rechazado_con_credenciales_invalidas()
    {
        $response = $this->post(route('login.store'), [
            'email' => 'test@test.com',
            'password' => 'wrongpassword'
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest();
    }

    public function test_registro_crea_nuevo_usuario()
    {
        $response = $this->post(route('register.store'), [
            'nombre' => 'Nuevo',
            'apellidos' => 'Usuario',
            'email' => 'nuevo@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('login'));
        $this->assertDatabaseHas('usuarios', [
            'email' => 'nuevo@test.com',
            'nombre' => 'Nuevo'
        ]);
    }

    public function test_logout_cierra_sesion()
    {
        $usuario = Usuario::where('email', 'test@test.com')->first();

        $response = $this->actingAs($usuario)->post(route('login.logout'));

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_bloqueo_temporal_despues_de_tres_intentos_fallidos()
    {
        // Primer intento fallido
        $this->post(route('login.store'), [
            'email' => 'test@test.com',
            'password' => 'wrong1'
        ]);

        // Segundo intento fallido
        $this->post(route('login.store'), [
            'email' => 'test@test.com',
            'password' => 'wrong2'
        ]);

        // Tercer intento fallido (debería bloquear)
        $response = $this->post(route('login.store'), [
            'email' => 'test@test.com',
            'password' => 'wrong3'
        ]);

        $usuario = Usuario::where('email', 'test@test.com')->first();
        $this->assertEquals(3, $usuario->intentos_fallidos);
        $this->assertNotNull($usuario->bloqueado_hasta);
    }
}
