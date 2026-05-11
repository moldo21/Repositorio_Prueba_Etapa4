<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Galeria;
use App\Models\Carrito;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Crear categoría de prueba
        Categoria::create([
            'id' => 1,
            'nombre' => 'Sillas',
            'descripcion' => 'Sillas de oficina'
        ]);
    }

    public function test_crear_producto()
    {
        $producto = Producto::create([
            'nombre' => 'Silla Gamer',
            'descripcion' => 'Silla ergonómica',
            'precio' => 150.50,
            'stock' => 10,
            'categoria_id' => 1,
            'imagen_principal' => 'silla.jpg',
        ]);

        $this->assertEquals('Silla Gamer', $producto->nombre);
        $this->assertEquals(150.50, $producto->precio);
        $this->assertEquals(10, $producto->stock);
    }

    public function test_relacion_con_categoria()
    {
        $producto = Producto::create([
            'nombre' => 'Mesa Escritorio',
            'descripcion' => 'Mesa amplia',
            'precio' => 200,
            'stock' => 5,
            'categoria_id' => 1,
            'imagen_principal' => 'mesa.jpg'
        ]);

        $this->assertInstanceOf(Categoria::class, $producto->categoria);
        $this->assertEquals('Sillas', $producto->categoria->nombre);
    }

    public function test_producto_tiene_galerias()
    {
        $producto = Producto::create([
            'nombre' => 'Cama King',
            'descripcion' => 'Cama grande',
            'precio' => 500,
            'stock' => 3,
            'categoria_id' => 1,
            'imagen_principal' => 'cama.jpg'
        ]);

        Galeria::create([
            'producto_id' => $producto->id,
            'ruta' => 'imagen1.jpg',
            'es_principal' => true,
            'orden' => 1
        ]);

        Galeria::create([
            'producto_id' => $producto->id,
            'ruta' => 'imagen2.jpg',
            'es_principal' => false,
            'orden' => 2
        ]);

        $this->assertCount(2, $producto->galerias);
    }

    public function test_producto_destacado_cast_boolean()
    {
        $producto = Producto::create([
            'nombre' => 'Producto Destacado',
            'descripcion' => 'Destacado',
            'precio' => 100,
            'stock' => 5,
            'categoria_id' => 1,
            'imagen_principal' => 'img.jpg',
            'destacado' => 1
        ]);

        $this->assertTrue($producto->destacado);
        $this->assertIsBool($producto->destacado);
    }

    public function test_precio_cast_decimal()
    {
        $producto = Producto::create([
            'nombre' => 'Producto Precio',
            'descripcion' => 'Test',
            'precio' => 99.99,
            'stock' => 10,
            'categoria_id' => 1,
            'imagen_principal' => 'img.jpg'
        ]);

        $this->assertEquals('99.99', $producto->precio);
    }
}
