<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    function run(): void
    {
        // 1. Crear Roles primero
        $this->call(RoleSeeder::class); // <--- AGREGA ESTA LÍNEA

        // 2. Luego crear usuarios (opcional)
        // \App\Models\Usuario::factory()->create([ ... ]);
    }
}
