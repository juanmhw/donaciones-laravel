@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Estados</h2>

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif

    <a href="{{ route('estados.create') }}" class="btn btn-primary mb-3">➕ Nuevo Estado</a>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th># Donaciones</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($estados as $e)
                <tr>
                    <td>{{ $e->estadoid }}</td>
                    <td>{{ $e->nombre }}</td>
                    <td>{{ $e->descripcion }}</td>
                    <td>{{ $e->donaciones_count ?? '' }}</td>
                    <td>
                        <a href="{{ route('estados.edit', $e->estadoid) }}" class="btn btn-warning btn-sm">✏️ Editar</a>
                        <form action="{{ route('estados.destroy', $e->estadoid) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar estado?')">🗑 Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center">No hay estados</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
