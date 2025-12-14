<?php

namespace App\Http\Controllers;

use App\Models\Campania;
use App\Models\Donacion;
use App\Models\Usuario;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ApiDonacionSyncController extends Controller
{
    public function syncDinero()
    {
        // 1) Consumir API externa
        $response = Http::get('http://localhost:8000/api/donaciones/dinero');

        if ($response->failed()) {
            return back()->withErrors('No se pudo conectar a la API de donaciones de dinero.');
        }

        $donacionesExternas = $response->json();

        DB::transaction(function () use ($donacionesExternas) {

            foreach ($donacionesExternas as $d) {

                // ------------------------
                // 1. USUARIO (DONANTE)
                // ------------------------
                $usuario = null;

                if (!empty($d['donante']['email'])) {

                    $email   = $d['donante']['email'];
                    $nombre  = $d['donante']['nombre']   ?? 'Donante';
                    $telefono= $d['donante']['telefono'] ?? null;

                    // crea si no existe, si existe lo reutiliza
                    $usuario = Usuario::firstOrCreate(
                        ['email' => $email],
                        [
                            'contrasena' => bcrypt('password'), // valor por defecto
                            'nombre'     => $nombre,
                            'apellido'   => '',
                            'telefono'   => $telefono,
                            'activo'     => true,
                        ]
                    );

                    // si quieres, puedes actualizar datos básicos si ya existía:
                    // $usuario->update(['nombre' => $nombre, 'telefono' => $telefono]);
                }

                // ------------------------
                // 2. CAMPAÑA (opcional)
                // ------------------------
                $campania = null;
                if (!empty($d['id_campana'])) {
                    $campania = Campania::where('idexterno', $d['id_campana'])->first();
                }

                // ------------------------
                // 3. ESTADO INTERNO
                // ------------------------
                // Ignoramos el estado de la API (dinero.estado)
                // y usamos nuestros estadoid:
                // 1 = Pendiente, 2 = Confirmada, etc.
                // Por ahora dejamos todas como PENDIENTE (1).
                $estadoId = 2;

                // ------------------------
                // 4. MAPEAR A NUESTRA TABLA
                // ------------------------
                $monto       = $d['dinero']['monto']          ?? 0;
                $descripcion = $d['observaciones']            ?? null;
                $fecha       = $d['fecha']                    ?? now();

                Donacion::updateOrCreate(
                    ['idexterno' => $d['id_donacion']], // clave externa de la API
                    [
                        'usuarioid'     => $usuario?->usuarioid,
                        'campaniaid'    => $campania?->campaniaid,   // puede quedar null
                        'monto'         => $monto,
                        'tipodonacion'  => 'Monetaria',
                        'descripcion'   => $descripcion,
                        'fechadonacion' => $fecha,
                        'estadoid'      => $estadoId,
                        'esanonima'     => $usuario ? false : true,
                    ]
                );
            }
        });

        return back()->with('success', 'Donaciones de dinero sincronizadas correctamente desde la API externa.');
    }
}
