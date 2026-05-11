<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Rol;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRol  = Rol::where('nombre', 'Administrador')->first()->id;
        $gestorRol = Rol::where('nombre', 'Gestor')->first()->id;
        $clienteRol = Rol::where('nombre', 'Cliente')->first()->id;

        $usuarios = [
            [
                'nombre'    => 'Admin',
                'apellidos' => 'Principal',
                'email'     => 'admin@tienda.com',
                'password'  => bcrypt('password'),
                'rol_id'    => $adminRol,
            ],
            [
                'nombre'    => 'Gestor',
                'apellidos' => 'Catálogo',
                'email'     => 'gestor@tienda.com',
                'password'  => bcrypt('password'),
                'rol_id'    => $gestorRol,
            ],
            [
                'nombre'    => 'Cliente',
                'apellidos' => 'Ejemplo',
                'email'     => 'cliente@tienda.com',
                'password'  => bcrypt('password'),
                'rol_id'    => $clienteRol,
            ],
        ];

        foreach ($usuarios as $usuario) {
            User::firstOrCreate(['email' => $usuario['email']], $usuario);
        }

        // Usuarios adicionales generados con factory
        User::factory(5)
            ->sequence(fn ($sequence) => [
                'email'    => 'usuario' . ($sequence->index + 1) . '@tienda.com',
                'password' => bcrypt('1234'),
                'rol_id'   => $clienteRol,
            ])
            ->create();
    }
}
