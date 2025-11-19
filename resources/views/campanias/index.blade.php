@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Campañas</h2>

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif

    <a href="{{ route('campanias.create') }}" class="btn btn-primary mb-3">➕ Nueva Campaña</a>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Fecha Inicio</th>
                <th>Fecha Fin</th>
                <th>Meta</th>
                <th>Recaudado</th>
                <th>Activa</th>
                <th>Creador</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($campanias as $c)
                <tr>
                    <td>{{ $c->campaniaid }}</td>
                    <td>{{ $c->titulo }}</td>
                    <td>{{ $c->fechainicio }}</td>
                    <td>{{ $c->fechafin }}</td>
                    <td>{{ $c->metarecaudacion }}</td>
                    <td>{{ $c->montorecaudado }}</td>
                    <td>{{ $c->activa ? 'Sí' : 'No' }}</td>
                    <td>{{ optional($c->creador)->email }}</td>
                    <td>
                        <a href="{{ route('campanias.edit', $c->campaniaid) }}" class="btn btn-warning btn-sm">✏️ Editar</a>
                        <form action="{{ route('campanias.destroy', $c->campaniaid) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar campaña?')">🗑 Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="9" class="text-center">No hay campañas</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
