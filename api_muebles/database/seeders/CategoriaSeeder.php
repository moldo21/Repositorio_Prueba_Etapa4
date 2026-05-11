<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            ['nombre' => 'Sillas',       'descripcion' => 'Comodidad y estilo para tu hogar'],
            ['nombre' => 'Mesas',        'descripcion' => 'Funcionales y elegantes para cualquier espacio'],
            ['nombre' => 'Camas',        'descripcion' => 'Descanso y diseño en un solo lugar'],
            ['nombre' => 'Estanterías',  'descripcion' => 'Organiza con estilo y practicidad'],
            ['nombre' => 'Sofás',        'descripcion' => 'Confort y diseño para tu sala de estar'],
        ];

        foreach ($categorias as $cat) {
            Categoria::firstOrCreate(['nombre' => $cat['nombre']], $cat);
        }
    }
}
