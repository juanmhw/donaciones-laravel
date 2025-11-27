<?php

namespace App\Http\Controllers;

use App\Models\Campania;
use App\Models\Donacion;
use Illuminate\Http\Request;

class CampaniaCierreController extends Controller
{
    // ➊ Pantalla para seleccionar campaña
    public function seleccionarCampania()
    {
        // Puedes filtrar solo activas si quieres
        $campanias = Campania::orderBy('activa', 'desc')
            ->orderBy('fechainicio', 'desc')
            ->get();

        return view('campanias.seleccionar_cierre', compact('campanias'));
    }

    // ➋ Mostrar resumen de una campaña seleccionada
    public function mostrarResumen(Request $request)
    {
        $request->validate([
            'campaniaid' => 'required|integer|exists:campanias,campaniaid',
        ]);

        $campania = Campania::findOrFail($request->campaniaid);

        // Traemos todas las donaciones de la campaña con relaciones necesarias
        $donaciones = Donacion::with([
                'usuario',
                'estado',
                'saldo',
                'asignacionesPivot.asignacion',
            ])
            ->where('campaniaid', $campania->campaniaid)
            ->get();

        // Totales generales
        $totalDonado = $donaciones->sum('monto');

        $totalAsignado = $donaciones->sum(function ($d) {
            return $d->asignacionesPivot
                ? $d->asignacionesPivot->sum('montoasignado')
                : 0;
        });

        $totalSaldo = $donaciones->sum(function ($d) {
            return optional($d->saldo)->saldodisponible ?? 0;
        });

        $totales = [
            'total_donado'   => $totalDonado,
            'total_asignado' => $totalAsignado,
            'total_saldo'    => $totalSaldo,
        ];

        return view('campanias.resumen_cierre', compact('campania', 'donaciones', 'totales'));
    }

    // ➌ Acción para cerrar la campaña
    public function cerrarCampania(Request $request, $campaniaid)
    {
        $campania = Campania::findOrFail($campaniaid);

        if (! $campania->activa) {
            return back()->with('status', 'La campaña ya está cerrada.');
        }

        $campania->activa = false;

        if (! $campania->fechafin) {
            $campania->fechafin = now()->toDateString();
        }

        $campania->save();

        return redirect()
            ->route('campanias.cierreMostrar', ['campaniaid' => $campania->campaniaid])
            ->with('status', 'La campaña se cerró correctamente.');
    }
}
