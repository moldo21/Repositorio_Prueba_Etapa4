<?php

namespace Database\Factories;

use App\Models\Producto;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductoFactory extends Factory
{
    protected $model = Producto::class;

    public function definition(): array
    {
        $sufijos = [
            'Clásico', 'Moderno', 'Vintage', 'Premium', 'Económico', 'Deluxe',
            'Minimalista', 'Rústico', 'Industrial', 'Elegante', 'Escandinavo',
            'Contemporáneo', 'Retro', 'Nórdico', 'Colonial', 'Barroco', 'Art Deco',
            'Provenzal', 'Japonés', 'Mediterráneo',
        ];

        $categoriasProductos = [
            1 => [
                'Silla Comedor', 'Silla Oficina', 'Silla Gaming', 'Silla Plegable',
                'Silla Bar', 'Silla Infantil', 'Silla Terraza', 'Silla Mecedora',
                'Silla Giratoria', 'Silla Apilable', 'Silla Ergonómica', 'Silla Tapizada',
                'Silla Escritorio', 'Silla Jardín', 'Silla Balancín',
            ],
            2 => [
                'Mesa Comedor', 'Mesa Centro', 'Mesa Escritorio', 'Mesa Auxiliar',
                'Mesa Cocina', 'Mesa Extensible', 'Mesa Consola', 'Mesa Lateral',
                'Mesa Plegable', 'Mesa Noche', 'Mesa Café', 'Mesa Ordenador',
                'Mesa Estudio', 'Mesa Apoyo', 'Mesa Redonda',
            ],
            3 => [
                'Cama King Size', 'Cama Matrimonial', 'Cama Individual', 'Cama Litera',
                'Cama Nido', 'Cama Abatible', 'Cama Juvenil', 'Cama Tatami',
                'Cama Dosel', 'Cama Canapé', 'Cama Futón', 'Cama Articulada',
                'Cama Alta', 'Cama Baja', 'Cama Plegable',
            ],
            4 => [
                'Estantería Libros', 'Estantería Pared', 'Estantería Modular', 'Estantería Esquina',
                'Estantería Cubo', 'Estantería Industrial', 'Estantería Baja', 'Estantería Alta',
                'Estantería Divisoria', 'Estantería Escalera', 'Estantería Suspendida', 'Estantería Invisible',
                'Estantería Torre', 'Estantería Asimétrica', 'Estantería Giratoria',
            ],
            5 => [
                'Sofá 2 Plazas', 'Sofá 3 Plazas', 'Sofá Cama', 'Sofá Chaise Longue',
                'Sofá Esquinero', 'Sofá Reclinable', 'Sofá Chester', 'Sofá Modular',
                'Sofá Rinconera', 'Sofá Orejero', 'Sofá Loveseat', 'Sofá Futón',
                'Sofá Clic Clac', 'Sofá Tresillo', 'Sofá Capitoné',
            ],
        ];

        $descripciones = [
            'Cómoda y elegante, perfecta para cualquier sala.',
            'Diseño moderno, resistente y fácil de limpiar.',
            'Ideal para espacios pequeños y grandes familias.',
            'Fabricado con materiales de alta calidad.',
            'Estilo clásico que combina con cualquier decoración.',
            'Perfecto para el descanso y confort diario.',
            'Diseño versátil y funcional, combina con cualquier decoración.',
            'Hecho a mano con atención al detalle.',
        ];

        $materiales  = ['Madera', 'Metal', 'Plástico', 'Cuero', 'Tela', 'Vidrio'];
        $dimensiones = ['100x50x75 cm', '120x60x80 cm', '150x80x60 cm', '200x100x90 cm', '180x90x100 cm', '220x110x95 cm'];
        $colores     = ['Rojo', 'Azul', 'Verde', 'Negro', 'Blanco', 'Marrón', 'Gris', 'Beige'];

        $categoriaId = $this->faker->numberBetween(1, 5);
        $nombreBase  = $this->faker->unique()->randomElement($categoriasProductos[$categoriaId]);
        $sufijo      = $this->faker->randomElement($sufijos);

        return [
            'categoria_id'    => $categoriaId,
            'nombre'          => $nombreBase . ' ' . $sufijo,
            'descripcion'     => $this->faker->randomElement($descripciones),
            'precio'          => $this->faker->randomFloat(2, 50, 2000),
            'stock'           => $this->faker->numberBetween(1, 50),
            'materiales'      => $this->faker->randomElement($materiales),
            'dimensiones'     => $this->faker->randomElement($dimensiones),
            'color_principal' => $this->faker->randomElement($colores),
            'destacado'       => $this->faker->boolean(30),
            'imagen_principal' => 'imagen_default.jpg',
        ];
    }
}
