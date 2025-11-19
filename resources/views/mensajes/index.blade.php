@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Mensajes</h2>

    <a href="{{ route('mensajes.create') }}" class="btn btn-primary mb-3">Nuevo mensaje</a>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Remitente</th>
                <th>Destinatario</th>
                <th>Asunto</th>
                <th>Fecha</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($mensajes as $m)
                <tr>
                    <td>{{ $m->mensajeid }}</td>
                    <td>{{ optional($m->remitente)->email }}</td>
                    <td>{{ optional($m->destinatario)->email ?? '—' }}</td>
                    <td>{{ $m->asunto }}</td>
                    <td>{{ $m->fechaenvio }}</td>
                    <td>
                        <a href="{{ route('mensajes.show',$m->mensajeid) }}" class="btn btn-sm btn-info">Ver</a>
                        <a href="{{ route('mensajes.edit',$m->mensajeid) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('mensajes.destroy',$m->mensajeid) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar mensaje?')">
                                Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6">Sin mensajes.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $mensajes->links() }}
</div>
@endsection
