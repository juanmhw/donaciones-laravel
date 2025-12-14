<?php

namespace App\Http\Controllers;

use App\Models\Campania;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Importante para Auth::id()

class CampaniaController extends Controller
{
    public function index()
    {
        // 👇 AQUÍ ESTÁ LA MAGIA DEL REPORTE
        $campanias = Campania::with(['creador'])
            ->withSum(['donaciones as montorecaudado' => function ($q) {
                $q->where('tipodonacion', 'Monetaria')
                  ->whereIn('estadoid', [2, 3, 4]); // Confirmada, Asignada, Utilizada
            }], 'monto')
            ->orderBy('campaniaid')
            ->get();

        return view('campanias.index', compact('campanias'));
    }

    public function create()
    {
        // YA NO enviamos $usuarios porque el creador es automático
        return view('campanias.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo'           => 'required|string|max:100',
            'descripcion'      => 'required|string',
            'fechainicio'      => 'required|date',
            'fechafin'         => 'nullable|date|after_or_equal:fechainicio',
            'metarecaudacion'  => 'required|numeric|min:0',
            'montorecaudado'   => 'nullable|numeric|min:0',
            // 'usuarioidcreador' => 'required...', // ELIMINADO de la validación
            'activa'           => 'boolean',
            'imagenurl'        => 'nullable|string|max:255',
            'fechacreacion'    => 'nullable|date',
        ]);

        $data = $request->only([
            'titulo','descripcion','fechainicio','fechafin','metarecaudacion',
            'montorecaudado','activa','imagenurl','fechacreacion'
        ]);

        // ASIGNACIÓN AUTOMÁTICA DEL USUARIO LOGUEADO
        $data['usuarioidcreador'] = Auth::id();

        Campania::create($data);

        return redirect()->route('campanias.index')->with('success', 'Campaña creada.');
    }

    public function edit($id)
    {
        $campania = Campania::findOrFail($id);
        // Aquí podrías querer mantener $usuarios si el admin va a cambiar el dueño, 
        // pero para editar lo básico no es necesario.
        return view('campanias.edit', compact('campania'));
    }

    public function update(Request $request, $id)
    {
        $campania = Campania::findOrFail($id);

        $request->validate([
            'titulo'           => 'required|string|max:100',
            'descripcion'      => 'required|string',
            'fechainicio'      => 'required|date',
            'fechafin'         => 'nullable|date|after_or_equal:fechainicio',
            'metarecaudacion'  => 'required|numeric|min:0',
            'montorecaudado'   => 'nullable|numeric|min:0',
            'activa'           => 'boolean',
            'imagenurl'        => 'nullable|string|max:255',
            'fechacreacion'    => 'nullable|date',
        ]);

        // Actualizamos todo excepto el creador (para no cambiarlo por error)
        $campania->update($request->only([
            'titulo','descripcion','fechainicio','fechafin','metarecaudacion',
            'montorecaudado','activa','imagenurl','fechacreacion'
        ]));

        return redirect()->route('campanias.index')->with('success', 'Campaña actualizada.');
    }

    public function destroy($id)
    {
        Campania::findOrFail($id)->delete();
        return redirect()->route('campanias.index')->with('success', 'Campaña eliminada.');
    }
}