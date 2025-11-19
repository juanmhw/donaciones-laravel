@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Asignaciones Usuario–Rol</h2>

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif

    <a href="{{ route('usuariosroles.create') }}" class="btn btn-primary mb-3">➕ Nueva Asignación</a>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Rol</th>
                <th>Fecha Asignación</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($usuariosroles as $ur)
                <tr>
                    <td>{{ $ur->usuariorolid }}</td>
                    <td>{{ optional($ur->usuario)->email }}</td>
                    <td>{{ optional($ur->rol)->nombre }}</td>
                    <td>{{ $ur->fechaasignacion }}</td>
                    <td>
                        <a href="{{ route('usuariosroles.edit', $ur->usuariorolid) }}" class="btn btn-warning btn-sm">✏️ Editar</a>
                        <form action="{{ route('usuariosroles.destroy', $ur->usuariorolid) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar asignación?')">🗑 Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center">No hay asignaciones</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
