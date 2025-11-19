<?php

namespace App\Http\Controllers;

use App\Models\{
    Asignacion,
    Campania,
    Usuario,
    DetallesAsignacion,
    DonacionesAsignacion,
    SaldosDonacion,
    Donacion
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AsignacionController extends Controller
{
    /** LISTADO */
    public function index()
    {
        $asignaciones = \App\Models\Asignacion::with(['campania','usuario'])
            ->withCount([
                'detalles as detalles_count',
                'donacionesPivot as donaciones_count',
            ])
            ->orderByDesc('asignacionid')
            ->get();

        return view('asignaciones.index', compact('asignaciones'));
    }



    /** FORM CREAR (recurso) */
    public function create()
    {
        $campanias = Campania::orderByDesc('campaniaid')->get();
        $usuarios  = Usuario::orderByDesc('usuarioid')->get();
        return view('asignaciones.create', compact('campanias','usuarios'));
    }

    /** GUARDAR (recurso) */
    public function store(Request $request)
    {
        $data = $request->validate([
            'campaniaid'      => 'required|integer|exists:campanias,campaniaid',
            'descripcion'     => 'required|string|max:255',
            'monto'           => 'required|numeric|min:0',   // se recalculará con los detalles, pero permitimos inicial
            'fechaasignacion' => 'nullable|date',
            'imagenurl'       => 'nullable|string|max:255',
            'usuarioid'       => 'required|integer|exists:usuarios,usuarioid',
            'comprobante'     => 'nullable|string|max:255',
        ]);

        // Si llega fecha tipo "datetime-local" con "T", normalizamos
        if ($request->filled('fechaasignacion')) {
            $data['fechaasignacion'] = \Carbon\Carbon::parse($request->input('fechaasignacion'))->format('Y-m-d H:i:s');
        }

        Asignacion::create($data);

        return redirect()->route('asignaciones.index')->with('success', 'Asignación creada.');
    }

    /** FORM EDITAR (recurso) */
    public function edit($id)
    {
        $asignacion = Asignacion::findOrFail($id);
        $campanias  = Campania::orderByDesc('campaniaid')->get();
        $usuarios   = Usuario::orderByDesc('usuarioid')->get();

        return view('asignaciones.edit', compact('asignacion','campanias','usuarios'));
    }

    /** ACTUALIZAR (recurso) */
    public function update(Request $request, $id)
    {
        $asignacion = Asignacion::findOrFail($id);

        $data = $request->validate([
            'campaniaid'      => 'required|integer|exists:campanias,campaniaid',
            'descripcion'     => 'required|string|max:255',
            'monto'           => 'required|numeric|min:0',
            'fechaasignacion' => 'nullable|date',
            'imagenurl'       => 'nullable|string|max:255',
            'usuarioid'       => 'required|integer|exists:usuarios,usuarioid',
            'comprobante'     => 'nullable|string|max:255',
        ]);

        if ($request->filled('fechaasignacion')) {
            $data['fechaasignacion'] = \Carbon\Carbon::parse($request->input('fechaasignacion'))->format('Y-m-d H:i:s');
        }

        $asignacion->update($data);

        return redirect()->route('asignaciones.index')->with('success', 'Asignación actualizada.');
    }

    /** ELIMINAR (recurso) */
    public function destroy($id)
    {
        try {
            DB::transaction(function () use ($id) {
                // devolver saldos de donaciones previamente asignadas a esta asignación
                $rows = DonacionesAsignacion::where('asignacionid',$id)->get();
                foreach ($rows as $r) {
                    $saldo = SaldosDonacion::where('donacionid',$r->donacionid)->first();
                    if ($saldo) {
                        $saldo->saldodisponible += $r->montoasignado;
                        $saldo->montoutilizado  -= $r->montoasignado;
                        $saldo->ultimaactualizacion = now();
                        $saldo->save();

                        // Ajustar estado de la donación
                        $don = Donacion::find($r->donacionid);
                        if ($don) {
                            if ($saldo->saldodisponible >= $saldo->montooriginal) {
                                // nada usado -> Confirmada (id=2 en tus datos seed)
                                $don->estadoid = 2;
                            } elseif ($saldo->saldodisponible <= 0) {
                                // todo usado
                                $don->estadoid = 4; // Utilizada
                            } else {
                                $don->estadoid = 3; // Asignada (parcial)
                            }
                            $don->save();
                        }
                    }
                }

                DonacionesAsignacion::where('asignacionid',$id)->delete();
                DetallesAsignacion::where('asignacionid',$id)->delete();
                Asignacion::where('asignacionid',$id)->delete();
            });

            return redirect()->route('asignaciones.index')->with('success','Asignación eliminada.');
        } catch (\Throwable $e) {
            return back()->withErrors('Error al eliminar: '.$e->getMessage());
        }
    }

    /* ============================
     *  EXTRAS: DETALLES & ASIGNAR
     * ============================
     */

    /** VISTA: Detalles de una asignación + formulario para agregar ítems */
    public function detalles($id)
    {
        $asignacion = Asignacion::with('campania')->findOrFail($id);
        $detalles   = DetallesAsignacion::where('asignacionid',$id)->orderByDesc('detalleid')->get();
        $donacionesAsignadas = DonacionesAsignacion::where('asignacionid',$id)->orderByDesc('donacionasignacionid')->get();

        return view('asignaciones.detalles', compact('asignacion','detalles','donacionesAsignadas'));
    }

    /** POST: guardar un detalle (ítem) y recalcular total de la asignación */
    public function guardarDetalle(Request $request, $id)
    {
        $asignacion = Asignacion::findOrFail($id);

        $data = $request->validate([
            'concepto'       => 'required|string|max:100',
            'cantidad'       => 'required|integer|min:1',
            'preciounitario' => 'required|numeric|min:0',
            'imagen'         => 'nullable|image|max:4096',
        ]);

        $detalle = new DetallesAsignacion();
        $detalle->asignacionid = $asignacion->asignacionid;
        $detalle->concepto     = $data['concepto'];
        $detalle->cantidad     = $data['cantidad'];
        $detalle->preciounitario = $data['preciounitario'];

        $path = $request->file('imagen')->store('detalles', 'public');
        $detalle->imagenurl = Storage::url($path);   // ✅ más compatible


        DB::transaction(function () use ($detalle, $asignacion) {
            $detalle->save();

            // Recalcular total = suma(cantidad*precio)
            $total = DetallesAsignacion::where('asignacionid', $asignacion->asignacionid)
                    ->select(DB::raw('COALESCE(SUM(cantidad*preciounitario),0) as total'))
                    ->value('total');

            $asignacion->monto = $total;
            $asignacion->save();
        });

        return back()->with('success','Ítem agregado. Total actualizado.');
    }

    /** VISTA: Asignar donaciones con saldo a esta asignación */
    public function asignar($id)
    {
        $asignacion = Asignacion::with('campania')->findOrFail($id);

        // ya asignado a esta asignación
        $yaAsignado = DonacionesAsignacion::where('asignacionid',$id)->sum('montoasignado');
        $faltante   = max(0, ($asignacion->monto ?? 0) - $yaAsignado);

        // Garantizar saldo para todas las donaciones existentes
        $todasDonaciones = Donacion::get();
        foreach ($todasDonaciones as $don) {
            SaldosDonacion::firstOrCreate(
                ['donacionid' => $don->donacionid],
                [
                    'montooriginal'      => $don->monto,
                    'montoutilizado'     => 0,
                    'saldodisponible'    => $don->monto,
                    'ultimaactualizacion'=> now(),
                ]
            );
        }

        // saldos disponibles de la misma campaña
        $saldos = SaldosDonacion::query()
            ->join('donaciones','donaciones.donacionid','=','saldosdonaciones.donacionid')
            ->where('saldosdonaciones.saldodisponible','>',0)
            ->where('donaciones.campaniaid', $asignacion->campaniaid)
            ->select('saldosdonaciones.*')
            ->get();

        return view('asignaciones.asignar', compact('asignacion','yaAsignado','faltante','saldos'));
    }

    /** POST: guardar asignación de una donación (monto) y actualizar saldos/estados */
    public function guardarAsignacion(Request $request, $id)
    {
        $asignacion = Asignacion::findOrFail($id);

        $data = $request->validate([
            'donacionid'    => 'required|exists:donaciones,donacionid',
            'montoasignado' => 'required|numeric|min:0.01',
        ]);

        $saldo = SaldosDonacion::where('donacionid',$data['donacionid'])->first();
        if (!$saldo) {
            return back()->withErrors('No se encontró el saldo de la donación.');
        }

        // Faltante en la asignación
        $yaAsignado = DonacionesAsignacion::where('asignacionid',$asignacion->asignacionid)->sum('montoasignado');
        $disponibleAsignacion = max(0, ($asignacion->monto ?? 0) - $yaAsignado);

        if ($disponibleAsignacion <= 0) {
            return back()->withErrors('Esta asignación ya fue cubierta al 100%.');
        }

        $maxPermitido = min($disponibleAsignacion, $saldo->saldodisponible);
        if ($data['montoasignado'] > $maxPermitido) {
            return back()->withErrors('Monto inválido. Máximo permitido: Bs '.number_format($maxPermitido,2,'.',','));
        }

        DB::transaction(function () use ($data, $asignacion, $saldo) {
            // Registro pivot donaciones_asignaciones
            DonacionesAsignacion::create([
                'donacionid'      => $data['donacionid'],
                'asignacionid'    => $asignacion->asignacionid,
                'montoasignado'   => $data['montoasignado'],
                'fechaasignacion' => now(),
            ]);

            // Actualizar saldo
            $saldo->saldodisponible     -= $data['montoasignado'];
            $saldo->montoutilizado      += $data['montoasignado'];
            $saldo->ultimaactualizacion  = now();
            $saldo->save();

            // Actualizar estado de la donación
            $don = Donacion::find($data['donacionid']);
            if ($don) {
                if ($saldo->saldodisponible <= 0) {
                    $don->estadoid = 4; // Utilizada
                } else {
                    $don->estadoid = 3; // Asignada (parcial)
                }
                $don->save();
            }
        });

        return redirect()->route('asignaciones.index')->with('success','Donación asignada correctamente.');
    }
}
