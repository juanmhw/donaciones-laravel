@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Asignación de Donaciones a Asignaciones</h2>

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif

    <a href="{{ route('donacionesasignaciones.create') }}" class="btn btn-primary mb-3">➕ Nuevo Registro</a>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Donación</th>
                <th>Asignación</th>
                <th>Monto Asignado</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $i)
                <tr>
                    <td>{{ $i->donacionasignacionid }}</td>
                    <td>{{ optional($i->donacion)->donacionid }} — {{ optional($i->donacion)->monto }}</td>
                    <td>{{ optional($i->asignacion)->asignacionid }} — {{ optional($i->asignacion)->descripcion }}</td>
                    <td>{{ $i->montoasignado }}</td>
                    <td>{{ $i->fechaasignacion }}</td>
                    <td>
                        <a href="{{ route('donacionesasignaciones.edit', $i->donacionasignacionid) }}" class="btn btn-warning btn-sm">✏️ Editar</a>
                        <form action="{{ route('donacionesasignaciones.destroy', $i->donacionasignacionid) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar registro?')">🗑 Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center">No hay registros</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
