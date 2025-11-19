@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Saldos de Donaciones</h2>

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif

    <a href="{{ route('saldosdonaciones.create') }}" class="btn btn-primary mb-3">➕ Nuevo Saldo</a>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Donación</th>
                <th>Monto Original</th>
                <th>Utilizado</th>
                <th>Saldo Disponible</th>
                <th>Última Actualización</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($saldos as $s)
                <tr>
                    <td>{{ $s->saldoid }}</td>
                    <td>{{ optional($s->donacion)->donacionid }}</td>
                    <td>{{ $s->montooriginal }}</td>
                    <td>{{ $s->montoutilizado }}</td>
                    <td>{{ $s->saldodisponible }}</td>
                    <td>{{ $s->ultimaactualizacion }}</td>
                    <td>
                        <a href="{{ route('saldosdonaciones.edit', $s->saldoid) }}" class="btn btn-warning btn-sm">✏️ Editar</a>
                        <form action="{{ route('saldosdonaciones.destroy', $s->saldoid) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar saldo?')">🗑 Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center">No hay saldos</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
