<?php

namespace App\Http\Controllers;

use App\Models\TrazabilidadItem;
use App\Models\Campania;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\TrazabilidadExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class ReporteTrazabilidadController extends Controller
{
    // Método index se mantiene igual...
    public function index(Request $request)
    {
        $campaniaId = $request->input('campaniaid');
        $query = TrazabilidadItem::query()
            ->orderBy('fecha_donacion', 'desc');

        if ($campaniaId) {
            $query->where('campaniaid', $campaniaId);
        }

        $items = $query->paginate(20);
        $campanias = Campania::orderBy('fechainicio', 'desc')->get();

        return view('reportes.trazabilidad.index', compact('items', 'campanias', 'campaniaId'));
    }
    
    public function exportarPDF(Request $request)
    {
        // ... (Tu lógica de filtros anterior se mantiene igual) ...
        $query = TrazabilidadItem::query();
        
        if ($request->filled('campaniaid')) {
            $query->where('campaniaid', $request->campaniaid);
        }
        // ... otros filtros ...

        // Ordenamiento por ubicación física
        $items = $query->orderBy('almacen_nombre', 'asc')
                       ->orderBy('estante_codigo', 'asc')
                       ->orderBy('espacio_codigo', 'asc')
                       ->orderBy('fecha_donacion', 'desc')
                       ->get();

        $campaniaNombre = $request->filled('campaniaid') 
            ? Campania::find($request->campaniaid)?->titulo 
            : 'Reporte General de Todas las Campañas';

        $fechaGeneracion = now()->format('d/m/Y h:i A');

        $pdf = Pdf::loadView('reportes.trazabilidad.pdf', compact(
            'items', 
            'campaniaNombre', 
            'fechaGeneracion'
        ));

        // TAMAÑO CARTA - HORIZONTAL (Para que quepan las columnas cómodamente)
        $pdf->setPaper('letter', 'landscape');

        return $pdf->download('trazabilidad_' . date('Y-m-d_His') . '.pdf');
    }

    public function exportarExcel(Request $request)
    {
        return Excel::download(
            new TrazabilidadExport($request->all()), 
            'trazabilidad_' . date('Y-m-d_His') . '.xlsx'
        );
    }

}