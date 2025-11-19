<?php
namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index() {
        $roles = Role::with('usuariosroles')->get();
        return view('roles.index', compact('roles'));
    }

    public function create() { return view('roles.create'); }

    public function store(Request $request) {
        $request->validate([
            'nombre' => 'required|string|max:50|unique:roles,nombre',
            'descripcion' => 'nullable|string|max:255',
        ]);
        Role::create($request->only(['nombre','descripcion']));
        return redirect()->route('roles.index')->with('success','Rol creado.');
    }

    public function edit($id) {
        $role = Role::findOrFail($id);
        return view('roles.edit', compact('role'));
    }

    public function update(Request $request, $id) {
        $role = Role::findOrFail($id);
        $request->validate([
            'nombre' => 'required|string|max:50|unique:roles,nombre,' . $id . ',rolid',
            'descripcion' => 'nullable|string|max:255',
        ]);
        $role->update($request->only(['nombre','descripcion']));
        return redirect()->route('roles.index')->with('success','Rol actualizado.');
    }

    public function destroy($id) {
        Role::findOrFail($id)->delete();
        return redirect()->route('roles.index')->with('success','Rol eliminado.');
    }
}
