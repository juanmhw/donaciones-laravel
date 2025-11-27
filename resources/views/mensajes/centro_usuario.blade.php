@extends('layouts.app')

@section('title', 'Centro de mensajes - Usuario')

@section('content')
<div class="container mt-4">
    <h2>Centro de mensajes del usuario</h2>

    {{-- Datos del usuario --}}
    <div class="card mb-3">
        <div class="card-body">
            <h4 class="card-title">
                {{ $usuario->nombre }} {{ $usuario->apellido }}
            </h4>
            <p class="mb-1"><strong>Email:</strong> {{ $usuario->email }}</p>
            <p class="mb-1"><strong>Teléfono:</strong> {{ $usuario->telefono ?? '—' }}</p>
        </div>
    </div>

    {{-- Resumen de donaciones --}}
    <div class="card mb-4">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Donaciones del usuario</h5>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Campaña</th>
                        <th>Fecha</th>
                        <th>Monto</th>
                        <th>Estado</th>
                        <th>Anónima</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($donaciones as $donacion)
                        <tr>
                            <td>{{ $donacion->donacionid }}</td>
                            <td>{{ optional($donacion->campania)->titulo ?? '—' }}</td>
                            <td>{{ $donacion->fechadonacion ?? '—' }}</td>
                            <td>{{ number_format($donacion->monto, 2) }} Bs</td>
                            <td>{{ optional($donacion->estado)->nombre ?? '—' }}</td>
                            <td>{{ $donacion->esanonima ? 'Sí' : 'No' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Este usuario no tiene donaciones registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Conversaciones (mensajes + respuestas) --}}
    <div class="card mb-3">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Conversaciones</h5>
        </div>
        <div class="card-body">
            @forelse ($mensajes as $mensaje)
                <div class="border rounded p-3 mb-3">
                    <div class="d-flex justify-content-between">
                        <div>
                            <strong>Asunto:</strong> {{ $mensaje->asunto }}<br>
                            <strong>De:</strong> {{ $mensaje->remitente_nombre ?? '—' }}<br>
                            <strong>Para:</strong> {{ $mensaje->destinatario_nombre ?? '—' }}
                        </div>
                        <div class="text-end">
                            <small class="text-muted">
                                Enviado: {{ $mensaje->fechaenvio ?? '—' }}
                            </small><br>
                            <small class="text-muted">
                                Leído: {{ $mensaje->leido ? 'Sí' : 'No' }} |
                                Respondido: {{ $mensaje->respondido ? 'Sí' : 'No' }}
                            </small>
                        </div>
                    </div>

                    <hr>

                    <p>{{ $mensaje->contenido }}</p>

                    @php
                        $resps = $respuestas[$mensaje->mensajeid] ?? collect();
                    @endphp

                    @if ($resps->count())
                        <div class="mt-3">
                            <strong>Respuestas:</strong>
                            <ul class="mt-2">
                                @foreach ($resps as $resp)
                                    <li class="mb-1">
                                        <strong>{{ $resp->usuario_nombre ?? 'Usuario' }}</strong>
                                        ({{ $resp->fecharespuesta ?? '—' }}):<br>
                                        {{ $resp->contenido }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            @empty
                <p class="text-muted mb-0">No hay mensajes relacionados con este usuario.</p>
            @endforelse
        </div>
    </div>

    <a href="{{ route('mensajes.centroSeleccion') }}" class="btn btn-secondary">
        ⬅ Volver a seleccionar usuario
    </a>
</div>
@endsection
