<?php

namespace Database\Seeders;

use App\Models\Producto;
use Illuminate\Database\Seeder;

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        $productos = [
            // Sillas (categoria_id 1)
            ['categoria_id' => 1, 'nombre' => 'Silla de madera clásica',  'descripcion' => 'Silla robusta de roble.',             'precio' => 50.50,  'stock' => 10, 'materiales' => 'Madera de roble',   'dimensiones' => '45x45x90 cm',   'color_principal' => 'Marrón', 'destacado' => true,  'imagen_principal' => 'silla1_1.jpg'],
            ['categoria_id' => 1, 'nombre' => 'Silla tapizada moderna',   'descripcion' => 'Tapizado gris y patas metálicas.',    'precio' => 60.50,  'stock' => 8,  'materiales' => 'Metal y tela',       'dimensiones' => '48x50x88 cm',   'color_principal' => 'Gris',   'destacado' => false, 'imagen_principal' => 'silla2_1.jpg'],
            ['categoria_id' => 1, 'nombre' => 'Silla plegable',           'descripcion' => 'Práctica y ligera, ideal exterior.',  'precio' => 39.90,  'stock' => 15, 'materiales' => 'Aluminio y plástico','dimensiones' => '45x44x85 cm',   'color_principal' => 'Blanco', 'destacado' => false, 'imagen_principal' => 'silla3_1.jpg'],
            // Mesas (categoria_id 2)
            ['categoria_id' => 2, 'nombre' => 'Mesa comedor extensible',  'descripcion' => 'Perfecta para cenas en familia.',     'precio' => 249.99, 'stock' => 5,  'materiales' => 'Madera y metal',    'dimensiones' => '160x90x75 cm',  'color_principal' => 'Marrón', 'destacado' => true,  'imagen_principal' => 'mesa1_1.jpg'],
            ['categoria_id' => 2, 'nombre' => 'Mesa auxiliar redonda',    'descripcion' => 'Compacta y elegante.',               'precio' => 59.95,  'stock' => 12, 'materiales' => 'Madera MDF',         'dimensiones' => '50x50x50 cm',   'color_principal' => 'Blanca', 'destacado' => false, 'imagen_principal' => 'mesa2_1.jpg'],
            ['categoria_id' => 2, 'nombre' => 'Mesa de centro industrial','descripcion' => 'Estilo moderno con base metálica.',   'precio' => 119.90, 'stock' => 6,  'materiales' => 'Hierro y cristal',  'dimensiones' => '100x60x40 cm',  'color_principal' => 'Negro',  'destacado' => false, 'imagen_principal' => 'mesa3_1.jpg'],
            // Camas (categoria_id 3)
            ['categoria_id' => 3, 'nombre' => 'Cama matrimonial de pino', 'descripcion' => 'Estructura maciza de pino.',         'precio' => 349.00, 'stock' => 4,  'materiales' => 'Pino macizo',       'dimensiones' => '160x200x90 cm', 'color_principal' => 'Natural','destacado' => true,  'imagen_principal' => 'cama1_1.jpg'],
            ['categoria_id' => 3, 'nombre' => 'Cama individual moderna',  'descripcion' => 'Diseño minimalista.',                'precio' => 199.00, 'stock' => 7,  'materiales' => 'MDF lacado',         'dimensiones' => '90x200x80 cm',  'color_principal' => 'Blanco', 'destacado' => false, 'imagen_principal' => 'cama2_1.jpg'],
            // Estanterías (categoria_id 4)
            ['categoria_id' => 4, 'nombre' => 'Estantería de pared',      'descripcion' => 'Perfecta para cualquier espacio.',   'precio' => 89.00,  'stock' => 10, 'materiales' => 'Madera y metal',    'dimensiones' => '80x20x100 cm',  'color_principal' => 'Marrón', 'destacado' => false, 'imagen_principal' => 'estanteria1_1.jpg'],
            ['categoria_id' => 4, 'nombre' => 'Librería modular',         'descripcion' => 'Sistema modular ampliable.',         'precio' => 179.00, 'stock' => 5,  'materiales' => 'MDF',               'dimensiones' => '120x30x180 cm', 'color_principal' => 'Blanco', 'destacado' => true,  'imagen_principal' => 'estanteria2_1.jpg'],
            // Sofás (categoria_id 5)
            ['categoria_id' => 5, 'nombre' => 'Sofá 3 plazas gris',       'descripcion' => 'Amplio y muy cómodo.',               'precio' => 599.00, 'stock' => 3,  'materiales' => 'Tela y espuma',     'dimensiones' => '220x90x85 cm',  'color_principal' => 'Gris',   'destacado' => true,  'imagen_principal' => 'imagen_default.jpg'],
            ['categoria_id' => 5, 'nombre' => 'Sofá esquinero beige',     'descripcion' => 'Perfecto para salones grandes.',     'precio' => 849.00, 'stock' => 2,  'materiales' => 'Tela y madera',     'dimensiones' => '280x160x85 cm', 'color_principal' => 'Beige',  'destacado' => false, 'imagen_principal' => 'imagen_default.jpg'],
        ];

        foreach ($productos as $datos) {
            Producto::firstOrCreate(['nombre' => $datos['nombre']], $datos);
        }

        // Productos generados con factorías
        Producto::factory(8)->create();
    }
}
