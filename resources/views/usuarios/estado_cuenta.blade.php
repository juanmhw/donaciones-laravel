@extends('layouts.app')

@section('title', 'Estado de cuenta del donante')

@section('content')
<div class="container mt-4">
    <h2>Estado de cuenta del donante</h2>

    <div class="card mb-3">
        <div class="card-body">
            <h4 class="card-title mb-3">
                {{ $usuario->nombre }} {{ $usuario->apellido }}
            </h4>
            <p class="mb-1"><strong>Email:</strong> {{ $usuario->email }}</p>
            <p class="mb-1"><strong>Teléfono:</strong> {{ $usuario->telefono ?? '—' }}</p>
            <p class="mb-0"><strong>Activo:</strong> {{ $usuario->activo ? 'Sí' : 'No' }}</p>
        </div>
    </div>

    {{-- Resumen general --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ number_format($totales['total_donado'], 2) }} Bs</h3>
                    <p>Total donado</p>
                </div>
                <div class="icon">
                    <i class="fas fa-hand-holding-heart"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ number_format($totales['total_asignado'], 2) }} Bs</h3>
                    <p>Total asignado a gastos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-receipt"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ number_format($totales['total_saldo'], 2) }} Bs</h3>
                    <p>Saldo disponible</p>
                </div>
                <div class="icon">
                    <i class="fas fa-piggy-bank"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabla de donaciones --}}
    <div class="card">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Detalle por donación</h5>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Campaña</th>
                        <th>Fecha</th>
                        <th>Monto donado</th>
                        <th>Asignado</th>
                        <th>Saldo</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($donaciones as $donacion)
                        @php
                            $saldo = optional($donacion->saldo)->saldodisponible ?? 0;
                        @endphp
                        <tr>
                            <td>{{ $donacion->donacionid }}</td>
                            <td>{{ optional($donacion->campania)->titulo ?? '—' }}</td>
                            <td>{{ $donacion->fechadonacion ?? '—' }}</td>
                            <td>{{ number_format($donacion->monto, 2) }} Bs</td>
                            <td>{{ number_format($donacion->total_asignado ?? 0, 2) }} Bs</td>
                            <td>{{ number_format($saldo, 2) }} Bs</td>
                            <td>{{ optional($donacion->estado)->nombre ?? '—' }}</td>
                        </tr>
                        {{-- Asignaciones y detalles debajo de cada donación --}}
                        @if ($donacion->asignacionesPivot && $donacion->asignacionesPivot->count())
                            <tr>
                                <td colspan="7">
                                    <strong>Asignaciones de esta donación:</strong>
                                    <ul class="mb-0">
                                        @foreach ($donacion->asignacionesPivot as $pivot)
                                            @php
                                                $asignacion = $pivot->asignacion;
                                            @endphp
                                            <li class="mt-2">
                                                <strong>Asignación #{{ $asignacion->asignacionid ?? $pivot->asignacionid }}</strong>
                                                – {{ $asignacion->descripcion ?? 'Sin descripción' }}
                                                – Monto asignado desde esta donación:
                                                <strong>{{ number_format($pivot->montoasignado, 2) }} Bs</strong>

                                                @if ($asignacion && $asignacion->detalles && $asignacion->detalles->count())
                                                    <br>
                                                    <span>Detalles de gasto:</span>
                                                    <ul>
                                                        @foreach ($asignacion->detalles as $det)
                                                            <li>
                                                                {{ $det->concepto }}
                                                                ({{ $det->cantidad }} x {{ number_format($det->preciounitario, 2) }} Bs)
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Este usuario no tiene donaciones registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">
            ⬅ Volver a la lista de usuarios
        </a>
    </div>
</div>
@endsection
