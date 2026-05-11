<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // tienda_principal solo gestiona carritos localmente.
        // Usuarios, productos y categorías se gestionan en sus respectivas APIs.
    }
}
