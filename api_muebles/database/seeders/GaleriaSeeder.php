<?php

namespace Database\Seeders;

use App\Models\Galeria;
use App\Models\Producto;
use Illuminate\Database\Seeder;

class GaleriaSeeder extends Seeder
{
    public function run(): void
    {
        // Productos hardcodeados
        $productosHardcoded = Producto::where('imagen_principal', '!=', 'imagen_default.jpg')->get();

        foreach ($productosHardcoded as $producto) {
            $imagenes = [
                $producto->imagen_principal,
                str_replace('_1.jpg', '_2.jpg', $producto->imagen_principal),
                str_replace('_1.jpg', '_3.jpg', $producto->imagen_principal),
            ];

            foreach ($imagenes as $index => $ruta) {
                Galeria::create([
                    'producto_id'  => $producto->id,
                    'ruta'         => $ruta,
                    'es_principal' => $index === 0,
                    'orden'        => $index + 1,
                ]);
            }
        }

        // Productos de factoria
        $productosAleatorios = Producto::where('imagen_principal', 'imagen_default.jpg')->get();

        foreach ($productosAleatorios as $producto) {
            for ($i = 1; $i <= 3; $i++) {
                Galeria::create([
                    'producto_id'  => $producto->id,
                    'ruta'         => 'imagen_default.jpg',
                    'es_principal' => $i === 1,
                    'orden'        => $i,
                ]);
            }
        }
    }
}
