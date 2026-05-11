<?php

namespace Database\Factories;

use App\Models\Galeria;
use Illuminate\Database\Eloquent\Factories\Factory;

class GaleriaFactory extends Factory
{
    protected $model = Galeria::class;

    public function definition(): array
    {
        return [
            'ruta'         => 'imagen_default.jpg',
            'es_principal' => false,
            'orden'        => $this->faker->numberBetween(1, 10),
        ];
    }

    public function principal(): static
    {
        return $this->state(fn () => [
            'es_principal' => true,
            'orden'        => 0,
        ]);
    }
}
