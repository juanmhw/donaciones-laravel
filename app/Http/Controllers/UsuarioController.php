<?php
namespace App\Http\Controllers;

use App\Models\{Usuario, Role};
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function index() {
        $usuarios = Usuario::with(['roles'])->get();
        return view('usuarios.index', compact('usuarios'));
    }

    public function create() {
        $roles = Role::all();
        return view('usuarios.create', compact('roles'));
    }

    public function store(Request $request) {
        $request->validate([
            'email'=>'required|email|max:100|unique:usuarios,email',
            'contrasena'=>'required|string|max:255',
            'nombre'=>'required|string|max:50',
            'apellido'=>'required|string|max:50',
            'telefono'=>'nullable|string|max:20',
            'imagenurl'=>'nullable|string|max:255',
            'activo'=>'boolean',
            'fecharegistro'=>'nullable|date',
            'roles' => 'array' // opcional si asignas roles aquí
        ]);
        $usuario = Usuario::create($request->only(['email','contrasena','nombre','apellido','telefono','imagenurl','activo','fecharegistro']));
        // asignar roles (opcional)
        if ($request->filled('roles')) {
            $usuario->roles()->sync($request->input('roles')); // requiere tabla pivote conforme
        }
        return redirect()->route('usuarios.index')->with('success','Usuario creado.');
    }

    public function edit($id) {
        $usuario = Usuario::with('roles')->findOrFail($id);
        $roles = Role::all();
        return view('usuarios.edit', compact('usuario','roles'));
    }

    public function update(Request $request, $id) {
        $usuario = Usuario::findOrFail($id);
        $request->validate([
            'email'=>'required|email|max:100|unique:usuarios,email,' . $id . ',usuarioid',
            'contrasena'=>'required|string|max:255',
            'nombre'=>'required|string|max:50',
            'apellido'=>'required|string|max:50',
            'telefono'=>'nullable|string|max:20',
            'imagenurl'=>'nullable|string|max:255',
            'activo'=>'boolean',
            'fecharegistro'=>'nullable|date',
            'roles'=>'array'
        ]);
        $usuario->update($request->only(['email','contrasena','nombre','apellido','telefono','imagenurl','activo','fecharegistro']));
        if ($request->has('roles')) {
            $usuario->roles()->sync($request->input('roles'));
        }
        return redirect()->route('usuarios.index')->with('success','Usuario actualizado.');
    }

    public function destroy($id) {
        Usuario::findOrFail($id)->delete();
        return redirect()->route('usuarios.index')->with('success','Usuario eliminado.');
    }
}
