@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Donaciones</h2>

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif

    <a href="{{ route('donaciones.create') }}" class="btn btn-primary mb-3">➕ Nueva Donación</a>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Campaña</th>
                <th>Monto</th>
                <th>Tipo</th>
                <th>Estado</th>
                <th>¿Anónima?</th>
                <th>Saldo</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($donaciones as $d)
                <tr>
                    <td>{{ $d->donacionid }}</td>
                    <td>{{ optional($d->usuario)->email ?: '—' }}</td>
                    <td>{{ optional($d->campania)->titulo }}</td>
                    <td>{{ $d->monto }}</td>
                    <td>{{ $d->tipodonacion }}</td>
                    <td>{{ optional($d->estado)->nombre }}</td>
                    <td>{{ $d->esanomina ? 'Sí' : 'No' }}</td>
                    <td>{{ optional($d->saldo)->saldodisponible }}</td>
                    <td>
                        <a href="{{ route('donaciones.edit', $d->donacionid) }}" class="btn btn-warning btn-sm">✏️ Editar</a>
                        <form action="{{ route('donaciones.destroy', $d->donacionid) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar donación?')">🗑 Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="9" class="text-center">No hay donaciones</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
