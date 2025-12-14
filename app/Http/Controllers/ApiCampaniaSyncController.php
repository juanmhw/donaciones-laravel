<?php

namespace App\Http\Controllers;

use App\Models\Campania;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class ApiCampaniaSyncController extends Controller
{
    public function sync()
    {
        // 1. Llamar a la API externa
        $response = Http::get('http://localhost:8000/api/campanas');

        if ($response->failed()) {
            return back()->withErrors('No se pudo conectar a la API de campañas.');
        }

        $campanasExternas = $response->json();

        DB::transaction(function () use ($campanasExternas) {

            foreach ($campanasExternas as $c) {

                // Buscamos la campaña por idexterno
                $campania = Campania::firstOrNew([
                    'idexterno' => $c['id_campana'],
                ]);

                // Actualizamos solo los datos que vienen de la API
                $campania->titulo      = $c['nombre'];
                $campania->descripcion = $c['descripcion'];
                $campania->fechainicio = $c['fecha_inicio'];
                $campania->fechafin    = $c['fecha_fin'];
                $campania->imagenurl   = $c['imagen_banner'] ?? null;

                // Estos campos SON LOCALES, no los tocamos si ya existen
                if (! $campania->exists) {
                    // Solo para campañas nuevas puedes poner valores por defecto
                    $campania->metarecaudacion = 0;
                    $campania->montorecaudado  = 0;
                    $campania->usuarioidcreador = 1;
                    $campania->activa = true;
                }

                $campania->save();
            }
        });

        return back()->with('success', 'Campañas sincronizadas exitosamente desde la API externa.');
    }
}
