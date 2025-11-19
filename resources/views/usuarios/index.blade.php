@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Lista de Usuarios</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('usuarios.create') }}" class="btn btn-primary mb-3">➕ Nuevo Usuario</a>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Email</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Teléfono</th>
                <th>Activo</th>
                <th>Roles</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($usuarios as $usuario)
                <tr>
                    <td>{{ $usuario->usuarioid }}</td>
                    <td>{{ $usuario->email }}</td>
                    <td>{{ $usuario->nombre }}</td>
                    <td>{{ $usuario->apellido }}</td>
                    <td>{{ $usuario->telefono }}</td>
                    <td>{{ $usuario->activo ? 'Sí' : 'No' }}</td>
                    <td>
                        @if($usuario->relationLoaded('roles'))
                            {{ $usuario->roles->pluck('nombre')->implode(', ') }}
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('usuarios.edit', $usuario->usuarioid) }}" class="btn btn-warning btn-sm">✏️ Editar</a>
                        <form action="{{ route('usuarios.destroy', $usuario->usuarioid) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar este usuario?')">🗑 Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center">No hay usuarios registrados</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
