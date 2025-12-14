<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role; // <--- EL MODELO REAL DE SPATIE

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Spatie usa 'name' (obligatorio) y 'guard_name' (automático).
        // Nosotros agregamos 'descripcion' extra.
        
        Role::create(['name' => 'Administrador', 'descripcion' => 'Tiene acceso total a todos los módulos.']);
        Role::create(['name' => 'Almacenero',    'descripcion' => 'Encargado de inventario y almacenes.']);
        Role::create(['name' => 'Reportes',    'descripcion' => 'Apoya en la logística.']);
    }
}