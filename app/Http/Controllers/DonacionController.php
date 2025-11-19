<?php

namespace App\Http\Controllers;

use App\Models\{Donacion, Usuario, Campania, Estado, SaldosDonacion};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DonacionController extends Controller
{
    /** LISTADO */
    public function index()
    {
        $donaciones = Donacion::with(['usuario','campania','estado','saldo'])
            ->orderByDesc('donacionid')
            ->get();

        return view('donaciones.index', compact('donaciones'));
    }

    /** FORM CREAR */
    public function create()
    {
        $usuarios  = Usuario::orderByDesc('usuarioid')->get();
        $campanias = Campania::orderByDesc('campaniaid')->get();
        $estados   = Estado::orderBy('estadoid')->get();
        return view('donaciones.create', compact('usuarios','campanias','estados'));
    }

    /** GUARDAR */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'usuarioid'     => 'nullable|integer|exists:usuarios,usuarioid',
            'campaniaid'    => 'required|integer|exists:campanias,campaniaid',
            'monto'         => 'required|numeric|min:0.01',
            'tipodonacion'  => ['required','in:Monetaria,Especie,monetaria,especie'],
            'descripcion'   => 'nullable|string',
            'fechadonacion' => 'nullable|date',
            'estadoid'      => 'required|integer|exists:estados,estadoid',
            'esanonima'     => 'nullable|boolean', // <- CORREGIDO
        ]);

        // Normalizar tipo donación para cumplir el CHECK ('Monetaria','Especie')
        $validated['tipodonacion'] = ucfirst(strtolower($validated['tipodonacion']));

        // Normalizar fecha (por si viene 'datetime-local' con "T")
        if ($request->filled('fechadonacion')) {
            $validated['fechadonacion'] = \Carbon\Carbon::parse($request->input('fechadonacion'))
                ->format('Y-m-d H:i:s');
        }

        // Checkbox: si no viene, queda false
        $validated['esanonima'] = $request->boolean('esanonima', false);

        DB::transaction(function () use ($validated) {
            $don = Donacion::create($validated);

            // Crear saldo si no existe
            SaldosDonacion::firstOrCreate(
                ['donacionid' => $don->donacionid],
                [
                    'montooriginal'       => $don->monto,
                    'montoutilizado'      => 0,
                    'saldodisponible'     => $don->monto,
                    'ultimaactualizacion' => now(),
                ]
            );

            // Actualizar recaudado de la campaña
            $sum = Donacion::where('campaniaid',$don->campaniaid)->sum('monto');
            Campania::where('campaniaid',$don->campaniaid)->update(['montorecaudado'=>$sum]);
        });

        return redirect()->route('donaciones.index')->with('success','Donación creada.');
    }

    /** FORM EDITAR */
    public function edit($id)
    {
        $donacion  = Donacion::findOrFail($id);
        $usuarios  = Usuario::orderByDesc('usuarioid')->get();
        $campanias = Campania::orderByDesc('campaniaid')->get();
        $estados   = Estado::orderBy('estadoid')->get();

        return view('donaciones.edit', compact('donacion','usuarios','campanias','estados'));
    }

    /** ACTUALIZAR */
    public function update(Request $request, $id)
    {
        $donacion = Donacion::findOrFail($id);

        $validated = $request->validate([
            'usuarioid'     => 'nullable|integer|exists:usuarios,usuarioid',
            'campaniaid'    => 'required|integer|exists:campanias,campaniaid',
            'monto'         => 'required|numeric|min:0.01',
            'tipodonacion'  => ['required','in:Monetaria,Especie,monetaria,especie'],
            'descripcion'   => 'nullable|string',
            'fechadonacion' => 'nullable|date',
            'estadoid'      => 'required|integer|exists:estados,estadoid',
            'esanonima'     => 'nullable|boolean', // <- CORREGIDO
        ]);

        $validated['tipodonacion'] = ucfirst(strtolower($validated['tipodonacion']));

        if ($request->filled('fechadonacion')) {
            $validated['fechadonacion'] = \Carbon\Carbon::parse($request->input('fechadonacion'))
                ->format('Y-m-d H:i:s');
        }

        $validated['esanonima'] = $request->boolean('esanonima', false);

        DB::transaction(function () use ($donacion, $validated) {
            $campaniaAnterior = $donacion->campaniaid;
            $donacion->update($validated);

            // Ajustar saldo si cambió el monto
            $saldo = SaldosDonacion::firstOrCreate(
                ['donacionid' => $donacion->donacionid],
                [
                    'montooriginal'       => $donacion->monto,
                    'montoutilizado'      => 0,
                    'saldodisponible'     => $donacion->monto,
                    'ultimaactualizacion' => now(),
                ]
            );

            if ($saldo->montooriginal != $donacion->monto) {
                $delta = $donacion->monto - $saldo->montooriginal;
                $saldo->montooriginal    = $donacion->monto;
                $saldo->saldodisponible  = max(0, $saldo->saldodisponible + $delta);
                $saldo->ultimaactualizacion = now();
                $saldo->save();
            }

            // Actualizar recaudado en campañas
            if ($campaniaAnterior != $donacion->campaniaid) {
                $sumOld = Donacion::where('campaniaid',$campaniaAnterior)->sum('monto');
                Campania::where('campaniaid',$campaniaAnterior)->update(['montorecaudado'=>$sumOld]);
            }
            $sumNew = Donacion::where('campaniaid',$donacion->campaniaid)->sum('monto');
            Campania::where('campaniaid',$donacion->campaniaid)->update(['montorecaudado'=>$sumNew]);
        });

        return redirect()->route('donaciones.index')->with('success','Donación actualizada.');
    }

    /** ELIMINAR */
    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $don = Donacion::findOrFail($id);

            // Bloquear si ya fue usada en asignaciones
            $usos = \App\Models\DonacionesAsignacion::where('donacionid',$id)->count();
            if ($usos > 0) {
                abort(422, 'No se puede eliminar: la donación ya fue asignada.');
            }

            SaldosDonacion::where('donacionid',$id)->delete();
            $don->delete();

            // Actualizar recaudado de la campaña
            $sum = Donacion::where('campaniaid',$don->campaniaid)->sum('monto');
            Campania::where('campaniaid',$don->campaniaid)->update(['montorecaudado'=>$sum]);
        });

        return redirect()->route('donaciones.index')->with('success','Donación eliminada.');
    }
}
