<?php

namespace App\Http\Controllers;

use App\Models\Mensaje;
use App\Models\Usuario;
use Illuminate\Http\Request;

class MensajeController extends Controller
{
    public function index()
    {
        $mensajes = Mensaje::with([
            'remitente:usuarioid,nombre,apellido,email',
            'destinatario:usuarioid,nombre,apellido,email',
            'respuestas.usuario:usuarioid,nombre,apellido,email',
        ])
        ->orderByDesc('mensajeid')
        ->paginate(15);

        return view('mensajes.index', compact('mensajes'));
    }

    public function create()
    {
        $usuarios = Usuario::orderBy('nombre')->get(['usuarioid','nombre','apellido','email']);
        return view('mensajes.create', compact('usuarios'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'remitenteid'     => 'required|integer|exists:usuarios,usuarioid',
            'destinatarioid'  => 'nullable|integer|exists:usuarios,usuarioid|different:remitenteid',
            'asunto'          => 'required|string|max:100',
            'contenido'       => 'required|string',
            'fechaenvio'      => 'nullable|date',
            'leido'           => 'nullable|boolean',
            'respondido'      => 'nullable|boolean',
        ]);

        $data['fechaenvio'] = $data['fechaenvio'] ?? now();
        $data['leido']      = (bool)($data['leido'] ?? false);
        $data['respondido'] = (bool)($data['respondido'] ?? false);

        Mensaje::create($data);

        return redirect()->route('mensajes.index')->with('success', 'Mensaje creado.');
    }

    public function show($id)
    {
        $mensaje = Mensaje::with([
            'remitente:usuarioid,nombre,apellido,email',
            'destinatario:usuarioid,nombre,apellido,email',
            'respuestas.usuario:usuarioid,nombre,apellido,email',
        ])->findOrFail($id);

        // 🔹 Transaccional: marcar como leído al abrir
        if (! $mensaje->leido) {
            $mensaje->leido = true;   // o 1
            $mensaje->save();
        }

        return view('mensajes.show', compact('mensaje'));
    }


    public function edit($id)
    {
        $mensaje  = Mensaje::findOrFail($id);
        $usuarios = Usuario::orderBy('nombre')->get(['usuarioid','nombre','apellido','email']);

        return view('mensajes.edit', compact('mensaje','usuarios'));
    }

    public function update(Request $request, $id)
    {
        $mensaje = Mensaje::findOrFail($id);

        $data = $request->validate([
            'remitenteid'     => 'required|integer|exists:usuarios,usuarioid',
            'destinatarioid'  => 'nullable|integer|exists:usuarios,usuarioid|different:remitenteid',
            'asunto'          => 'required|string|max:100',
            'contenido'       => 'required|string',
            'fechaenvio'      => 'nullable|date',
            'leido'           => 'nullable|boolean',
            'respondido'      => 'nullable|boolean',
        ]);

        $data['fechaenvio'] = $data['fechaenvio'] ?? $mensaje->fechaenvio;
        $data['leido']      = (bool)($data['leido'] ?? $mensaje->leido);
        $data['respondido'] = (bool)($data['respondido'] ?? $mensaje->respondido);

        $mensaje->update($data);

        return redirect()->route('mensajes.index')->with('success', 'Mensaje actualizado.');
    }

    public function destroy($id)
    {
        $mensaje = Mensaje::findOrFail($id);
        $mensaje->delete();

        return redirect()->route('mensajes.index')->with('success', 'Mensaje eliminado.');
    }
}
