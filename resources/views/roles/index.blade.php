@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Lista de Roles</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('roles.create') }}" class="btn btn-primary mb-3">➕ Nuevo Rol</a>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($roles as $role)
                <tr>
                    <td>{{ $role->rolid }}</td>
                    <td>{{ $role->nombre }}</td>
                    <td>{{ $role->descripcion }}</td>
                    <td>
                        <a href="{{ route('roles.edit', $role->rolid) }}" class="btn btn-warning btn-sm">✏️ Editar</a>

                        <form action="{{ route('roles.destroy', $role->rolid) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar este rol?')">🗑 Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center">No hay roles registrados</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection