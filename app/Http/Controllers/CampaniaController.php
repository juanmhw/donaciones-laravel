<?php
namespace App\Http\Controllers;

use App\Models\{Campania, Usuario};
use Illuminate\Http\Request;

class CampaniaController extends Controller
{
    public function index() {
        $campanias = Campania::with(['creador'])->get();
        return view('campanias.index', compact('campanias'));
    }

    public function create() {
        $usuarios = Usuario::all();
        return view('campanias.create', compact('usuarios'));
    }

    public function store(Request $request) {
        $request->validate([
            'titulo'=>'required|string|max:100',
            'descripcion'=>'required|string',
            'fechainicio'=>'required|date',
            'fechafin'=>'nullable|date|after_or_equal:fechainicio',
            'metarecaudacion'=>'required|numeric|min:0',
            'montorecaudado'=>'nullable|numeric|min:0',
            'usuarioidcreador'=>'required|integer|exists:usuarios,usuarioid',
            'activa'=>'boolean',
            'imagenurl'=>'nullable|string|max:255',
            'fechacreacion'=>'nullable|date',
        ]);
        Campania::create($request->only([
            'titulo','descripcion','fechainicio','fechafin','metarecaudacion',
            'montorecaudado','usuarioidcreador','activa','imagenurl','fechacreacion'
        ]));
        return redirect()->route('campanias.index')->with('success','Campaña creada.');
    }

    public function edit($id) {
        $campania = Campania::findOrFail($id);
        $usuarios = Usuario::all();
        return view('campanias.edit', compact('campania','usuarios'));
    }

    public function update(Request $request, $id) {
        $campania = Campania::findOrFail($id);
        $request->validate([
            'titulo'=>'required|string|max:100',
            'descripcion'=>'required|string',
            'fechainicio'=>'required|date',
            'fechafin'=>'nullable|date|after_or_equal:fechainicio',
            'metarecaudacion'=>'required|numeric|min:0',
            'montorecaudado'=>'nullable|numeric|min:0',
            'usuarioidcreador'=>'required|integer|exists:usuarios,usuarioid',
            'activa'=>'boolean',
            'imagenurl'=>'nullable|string|max:255',
            'fechacreacion'=>'nullable|date',
        ]);
        $campania->update($request->only([
            'titulo','descripcion','fechafin','fechainicio','metarecaudacion',
            'montorecaudado','usuarioidcreador','activa','imagenurl','fechacreacion'
        ]));
        return redirect()->route('campanias.index')->with('success','Campaña actualizada.');
    }

    public function destroy($id) {
        Campania::findOrFail($id)->delete();
        return redirect()->route('campanias.index')->with('success','Campaña eliminada.');
    }

        public function show($id)
    {
        $campania = Campania::with([
            'donaciones',
            'asignaciones',
        ])->findOrFail($id);

        return view('campanias.show', compact('campania'));
    }

}
