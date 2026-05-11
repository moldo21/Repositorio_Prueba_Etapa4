<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoriaTest extends TestCase
{
    use RefreshDatabase;

    public function test_crear_categoria()
    {
        $categoria = Categoria::create([
            'nombre' => 'Sillas',
            'descripcion' => 'Sillas cómodas'
        ]);

        $this->assertEquals('Sillas', $categoria->nombre);
        $this->assertEquals('Sillas cómodas', $categoria->descripcion);
    }

    public function test_buscar_categoria_por_id()
    {
        $categoria = Categoria::create([
            'nombre' => 'Mesas',
            'descripcion' => 'Mesas elegantes'
        ]);

        $encontrada = Categoria::find($categoria->id);

        $this->assertNotNull($encontrada);
        $this->assertEquals('Mesas', $encontrada->nombre);
    }

    public function test_categoria_tiene_relacion_con_productos()
    {
        $categoria = Categoria::create([
            'nombre' => 'Camas',
            'descripcion' => 'Camas king size'
        ]);

        Producto::create([
            'nombre' => 'Cama Imperial',
            'descripcion' => 'Cama grande',
            'precio' => 500,
            'stock' => 5,
            'categoria_id' => $categoria->id,
            'imagen_principal' => 'cama.jpg'
        ]);

        $this->assertCount(1, $categoria->productos);
        $this->assertEquals('Cama Imperial', $categoria->productos->first()->nombre);
    }

    public function test_actualizar_categoria()
    {
        $categoria = Categoria::create([
            'nombre' => 'Estanterías',
            'descripcion' => 'Original'
        ]);

        $categoria->update(['descripcion' => 'Actualizada']);

        $this->assertEquals('Actualizada', $categoria->fresh()->descripcion);
    }

    public function test_eliminar_categoria()
    {
        $categoria = Categoria::create([
            'nombre' => 'Temporal',
            'descripcion' => 'Para borrar'
        ]);

        $id = $categoria->id;
        $categoria->delete();

        $this->assertNull(Categoria::find($id));
    }
}
