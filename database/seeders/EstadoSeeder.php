<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstadoSeeder extends Seeder
{
    public function run(): void
    {
        $estados = [
            ['estadoid' => 1, 'nombre' => 'Pendiente',  'descripcion' => 'Donación registrada pero aún no confirmada. Estado inicial por defecto al crear una donación.'],
            ['estadoid' => 2, 'nombre' => 'Confirmada', 'descripcion' => 'Donación validada y disponible para ser utilizada en asignaciones. Es la que cuenta para los reportes de recaudación.'],
            ['estadoid' => 3, 'nombre' => 'Asignada',   'descripcion' => 'Donación que ya fue usada parcialmente en una o más asignaciones, pero aún tiene saldo disponible.'],
            ['estadoid' => 4, 'nombre' => 'Utilizada',  'descripcion' => 'Donación cuyo monto fue usado al 100% en asignaciones. Ya no tiene saldo disponible.'],
            ['estadoid' => 5, 'nombre' => 'Cancelada',  'descripcion' => 'Donación anulada y excluida del flujo normal del sistema.'],
        ];

        foreach ($estados as $e) {
            DB::table('estados')->updateOrInsert(
                ['estadoid' => $e['estadoid']],
                $e
            );
        }
    }
}
