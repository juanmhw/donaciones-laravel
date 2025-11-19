<?php
namespace App\Http\Controllers;

use App\Models\{UsuariosRol, Usuario, Role};
use Illuminate\Http\Request;

class UsuariosRolController extends Controller
{
    public function index() {
        $usuariosroles = UsuariosRol::with(['usuario','rol'])->get();
        return view('usuariosroles.index', compact('usuariosroles'));
    }

    public function create() {
        $usuarios = Usuario::all();
        $roles = Role::all();
        return view('usuariosroles.create', compact('usuarios','roles'));
    }

    public function store(Request $request) {
        $request->validate([
            'usuarioid'=>'required|integer|exists:usuarios,usuarioid',
            'rolid'=>'required|integer|exists:roles,rolid',
            'fechaasignacion'=>'nullable|date'
        ]);
        UsuariosRol::create($request->only(['usuarioid','rolid','fechaasignacion']));
        return redirect()->route('usuariosroles.index')->with('success','Asignación creada.');
    }

    public function edit($id) {
        $usuariosrol = UsuariosRol::findOrFail($id);
        $usuarios = Usuario::all();
        $roles = Role::all();
        return view('usuariosroles.edit', compact('usuariosrol','usuarios','roles'));
    }

    public function update(Request $request, $id) {
        $ur = UsuariosRol::findOrFail($id);
        $request->validate([
            'usuarioid'=>'required|integer|exists:usuarios,usuarioid',
            'rolid'=>'required|integer|exists:roles,rolid',
            'fechaasignacion'=>'nullable|date'
        ]);
        $ur->update($request->only(['usuarioid','rolid','fechaasignacion']));
        return redirect()->route('usuariosroles.index')->with('success','Asignación actualizada.');
    }

    public function destroy($id) {
        UsuariosRol::findOrFail($id)->delete();
        return redirect()->route('usuariosroles.index')->with('success','Asignación eliminada.');
    }
}
