<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Donacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CentroMensajesController extends Controller
{
    // Pantalla para elegir usuario
    public function seleccionarUsuario()
    {
        $usuarios = Usuario::orderBy('nombre')->orderBy('apellido')->get();

        return view('mensajes.seleccionar_usuario', compact('usuarios'));
    }

    // Centro de mensajes para un usuario concreto
    public function centroPorUsuario(Request $request)
    {
        $request->validate([
            'usuarioid' => 'required|integer|exists:usuarios,usuarioid',
        ]);

        $usuario = Usuario::findOrFail($request->usuarioid);

        // Donaciones del usuario con campaña
        $donaciones = Donacion::with('campania')
            ->where('usuarioid', $usuario->usuarioid)
            ->get();

        // Mensajes donde es remitente o destinatario
        $mensajes = DB::table('mensajes')
            ->leftJoin('usuarios as remitente', 'mensajes.remitenteid', '=', 'remitente.usuarioid')
            ->leftJoin('usuarios as destinatario', 'mensajes.destinatarioid', '=', 'destinatario.usuarioid')
            ->where(function ($q) use ($usuario) {
                $q->where('mensajes.remitenteid', $usuario->usuarioid)
                  ->orWhere('mensajes.destinatarioid', $usuario->usuarioid);
            })
            ->select(
                'mensajes.*',
                DB::raw("remitente.nombre || ' ' || remitente.apellido as remitente_nombre"),
                DB::raw("destinatario.nombre || ' ' || destinatario.apellido as destinatario_nombre")
            )
            ->orderBy('mensajes.fechaenvio', 'desc')
            ->get();

        // Respuestas agrupadas por mensaje
        $respuestas = DB::table('respuestasmensajes')
            ->leftJoin('usuarios', 'respuestasmensajes.usuarioid', '=', 'usuarios.usuarioid')
            ->whereIn('mensajeid', $mensajes->pluck('mensajeid'))
            ->select(
                'respuestasmensajes.*',
                DB::raw("usuarios.nombre || ' ' || usuarios.apellido as usuario_nombre")
            )
            ->orderBy('fecharespuesta')
            ->get()
            ->groupBy('mensajeid');

        return view('mensajes.centro_usuario', compact('usuario', 'donaciones', 'mensajes', 'respuestas'));
    }
}
