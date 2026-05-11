<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PreferenciasControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_puede_ver_formulario_de_preferencias()
    {
        $response = $this->get(route('preferencias.edit', ['sesionId' => 'test-session']));

        $response->assertStatus(200);
        $response->assertViewIs('preferencias.edit');
    }

    public function test_puede_cambiar_tema_y_persiste_en_cookies()
    {
        $response = $this->post(route('preferencias.update'), [
            'tema' => 'oscuro',
            'moneda' => 'EUR',
            'paginacion' => 9,
            'sesionId' => 'abc123',
        ]);

        $response->assertRedirect(route('preferencias.edit', ['sesionId' => 'abc123']));
        $response->assertSessionHas('success', 'Preferencias guardadas correctamente.');
        $response->assertCookie('tema', 'oscuro');
        $response->assertCookie('moneda', 'EUR');
        $response->assertCookie('paginacion', '9');
    }

    public function test_puede_cambiar_moneda()
    {
        $response = $this->post(route('preferencias.update'), [
            'tema' => 'claro',
            'moneda' => 'USD',
            'paginacion' => 6,
            'sesionId' => 'test123',
        ]);

        $response->assertCookie('moneda', 'USD');
    }

    public function test_valores_por_defecto_si_no_hay_cookies()
    {
        $response = $this->get(route('preferencias.edit', ['sesionId' => 'new-session']));

        $response->assertStatus(200);
        $response->assertViewHas('tema', 'claro');
        $response->assertViewHas('moneda', 'EUR');
        $response->assertViewHas('paginacion', 6);
    }
}
