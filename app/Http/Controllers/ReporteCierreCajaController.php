<?php

namespace App\Http\Controllers;

use App\Models\Campania;
use App\Models\Donacion;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReporteCierreCajaController extends Controller
{
    public function index(Request $request)
    {
        // Filtros dinámicos
        $query = Donacion::with([
            'usuario',
            'campania',
            'estado',
            // 🔹 pivote -> asignacion -> detalles
            'asignacionesPivot.asignacion.detalles',
        ]);

        if ($request->filled('campaniaid')) {
            $query->where('campaniaid', $request->campaniaid);
        }

        if ($request->filled('desde')) {
            $query->whereDate('fechadonacion', '>=', $request->desde);
        }

        if ($request->filled('hasta')) {
            $query->whereDate('fechadonacion', '<=', $request->hasta);
        }

        if ($request->filled('estadoid')) {
            $query->where('estadoid', $request->estadoid);
        }

        $donaciones = $query->orderBy('fechadonacion', 'desc')->get();

        // Totales
        $totalGeneral     = $donaciones->sum('monto');
        $totalConfirmadas = $donaciones->where('estadoid', 2)->sum('monto');
        $totalPendientes  = $donaciones->where('estadoid', 1)->sum('monto');

        $campanias = Campania::orderBy('titulo')->get();

        return view('reportes.cierre_caja', compact(
            'donaciones',
            'campanias',
            'totalGeneral',
            'totalConfirmadas',
            'totalPendientes'
        ));
    }

    public function exportarPDF(Request $request)
    {
        // Filtros dinámicos
        $query = Donacion::with([
            'usuario',
            'campania',
            'estado',
            'asignacionesPivot.asignacion.detalles', // 🔹 igual aquí
        ]);

        if ($request->filled('campaniaid')) {
            $query->where('campaniaid', $request->campaniaid);
        }

        if ($request->filled('desde')) {
            $query->whereDate('fechadonacion', '>=', $request->desde);
        }

        if ($request->filled('hasta')) {
            $query->whereDate('fechadonacion', '<=', $request->hasta);
        }

        if ($request->filled('estadoid')) {
            $query->where('estadoid', $request->estadoid);
        }

        $donaciones = $query->orderBy('fechadonacion', 'desc')->get();

        $totalGeneral     = $donaciones->sum('monto');
        $totalConfirmadas = $donaciones->where('estadoid', 2)->sum('monto');
        $totalPendientes  = $donaciones->where('estadoid', 1)->sum('monto');
        $campanias        = Campania::orderBy('titulo')->get();

        $pdf = Pdf::loadView('reportes.cierre_caja_pdf', compact(
            'donaciones',
            'totalGeneral',
            'totalConfirmadas',
            'totalPendientes',
            'campanias'
        ));

        $pdf->setPaper('letter', 'portrait');

        return $pdf->download('cierre_caja_' . date('Y-m-d_His') . '.pdf');
    }
}
