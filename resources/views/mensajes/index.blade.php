@extends('layouts.app')

@section('title', 'Mensajes')

@section('header')
    <h1>
        <i class="fas fa-envelope icon-title"></i>
        Mensajes
    </h1>
    <p class="text-muted mb-0">
        Gestión de mensajes enviados y recibidos entre usuarios del sistema.
    </p>
@endsection

@section('content')
    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle mr-1"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">
                <i class="fas fa-list mr-1"></i> Listado de mensajes
            </h3>
            <a href="{{ route('mensajes.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle mr-1"></i> Nuevo mensaje
            </a>
        </div>

        <div class="card-body table-responsive p-0">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Remitente</th>
                        <th>Destinatario</th>
                        <th>Asunto</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th class="text-right" style="width: 180px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mensajes as $mensaje)
                        <tr>
                            <td>{{ $mensaje->mensajeid }}</td>
                            <td>
                                {{ optional($mensaje->remitente)->nombre }}
                                {{ optional($mensaje->remitente)->apellido }}<br>
                                <small class="text-muted">{{ optional($mensaje->remitente)->email }}</small>
                            </td>
                            <td>
                                {{ optional($mensaje->destinatario)->nombre }}
                                {{ optional($mensaje->destinatario)->apellido }}<br>
                                <small class="text-muted">
                                    {{ optional($mensaje->destinatario)->email ?? '—' }}
                                </small>
                            </td>
                            <td>
                                <strong>{{ $mensaje->asunto }}</strong><br>
                                <small class="text-muted">
                                    {{ \Illuminate\Support\Str::limit($mensaje->contenido, 40) }}
                                </small>
                            </td>
                            <td>
                                <span class="badge badge-{{ $mensaje->leido ? 'success' : 'secondary' }}">
                                    {{ $mensaje->leido ? 'Leído' : 'No leído' }}
                                </span>
                                <span class="badge badge-{{ $mensaje->respondido ? 'info' : 'light' }}">
                                    {{ $mensaje->respondido ? 'Respondido' : 'Sin respuesta' }}
                                </span>
                            </td>
                            <td>
                                {{ $mensaje->fechaenvio }}
                            </td>
                            <td class="text-right">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('mensajes.show', $mensaje->mensajeid) }}"
                                       class="btn btn-outline-secondary" title="Ver detalle">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('mensajes.edit', $mensaje->mensajeid) }}"
                                       class="btn btn-outline-primary" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('mensajes.destroy', $mensaje->mensajeid) }}"
                                          method="POST"
                                          onsubmit="return confirm('¿Eliminar este mensaje?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-outline-danger" title="Eliminar">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                No hay mensajes registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($mensajes, 'links'))
            <div class="card-footer">
                {{ $mensajes->links() }}
            </div>
        @endif
    </div>
@endsection
