@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Respuestas a mensajes</h2>

    <a href="{{ route('respuestasmensajes.create') }}" class="btn btn-primary mb-3">Nueva respuesta</a>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Mensaje</th>
                <th>Usuario</th>
                <th>Contenido</th>
                <th>Fecha</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($respuestas as $r)
                <tr>
                    <td>{{ $r->respuestaid }}</td>
                    <td>{{ $r->mensajeid }}</td>
                    <td>{{ optional($r->usuario)->email }}</td>
                    <td>{{ Str::limit($r->contenido,50) }}</td>
                    <td>{{ $r->fecharespuesta }}</td>
                    <td>
                        <a href="{{ route('respuestasmensajes.edit',$r->respuestaid) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('respuestasmensajes.destroy',$r->respuestaid) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar respuesta?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6">Sin respuestas.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $respuestas->links() }}
</div>
@endsection
