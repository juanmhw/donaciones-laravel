@extends('layouts.app')

@section('content')
<style>
    .almacen-card {
        border-radius: 18px;
        border: 0;
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.07);
        overflow: hidden;
    }
    .almacen-header {
        background: linear-gradient(90deg, #4f46e5, #6366f1);
        color: #fff;
    }
    .almacen-header h5 {
        margin: 0;
        font-weight: 700;
    }
    .almacen-header small {
        opacity: .9;
    }
    .stat-pill {
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        padding: .2rem .6rem;
        border-radius: 999px;
        background: rgba(15, 23, 42, .08);
        font-size: .75rem;
    }
    .estante-card {
        border-radius: 14px;
        border: 1px solid #e5e7eb;
        padding: .85rem 1rem;
        margin-bottom: .75rem;
        background: #f9fafb;
    }
    .estante-title {
        font-weight: 600;
        font-size: .9rem;
        margin-bottom: .35rem;
    }
    .chips-container {
        display: flex;
        flex-wrap: wrap;
        gap: .4rem;
    }
    .chip {
        border-radius: 999px;
        padding: .15rem .6rem;
        font-size: .75rem;
        border: 1px solid #e5e7eb;
        background: #fff;
        display: inline-flex;
        align-items: center;
        gap: .25rem;
    }
    .chip-dot {
        width: .5rem;
        height: .5rem;
        border-radius: 999px;
    }
    .chip-dot.disponible { background: #22c55e; }
    .chip-dot.ocupado { background: #ef4444; }
    .chip-dot.desconocido { background: #9ca3af; }
</style>

<div class="container py-4">

    <div class="d-flex align-items-center mb-4">
        <span class="fs-2 me-2">🏬</span>
        <div>
            <h1 class="h3 mb-0 fw-bold">Estructura de Almacenes</h1>
            <small class="text-muted">Visualiza los almacenes, sus estantes y espacios sincronizados desde el sistema externo.</small>
        </div>
    </div>


    @foreach ($almacenes as $almacen)
        @php
            $totalEstantes = $almacen->estantes->count();
            $totalEspacios = $almacen->estantes->flatMap->espacios->count();
            $disponibles = $almacen->estantes->flatMap->espacios->where('estado', 'disponible')->count();
        @endphp

        <div class="card almacen-card mb-4">
            <div class="card-header almacen-header d-flex justify-content-between align-items-center">
                <div>
                    <h5>{{ $almacen->nombre }}</h5>
                    @if($almacen->direccion)
                        <small>📍 {{ $almacen->direccion }}</small>
                    @endif
                </div>
                <div class="text-end">
                    <div class="mb-1">
                        <span class="stat-pill">
                            <i class="fas fa-layer-group"></i>
                            {{ $totalEstantes }} estante{{ $totalEstantes === 1 ? '' : 's' }}
                        </span>
                        <span class="stat-pill ms-1">
                            <i class="fas fa-th-large"></i>
                            {{ $totalEspacios }} espacio{{ $totalEspacios === 1 ? '' : 's' }}
                        </span>
                    </div>
                    @if($almacen->latitud && $almacen->longitud)
                        <small class="d-block">
                            Lat: {{ $almacen->latitud }} · Lng: {{ $almacen->longitud }}
                        </small>
                    @endif
                </div>
            </div>

            <div class="card-body">

                @if ($almacen->estantes->isEmpty())
                    <p class="text-muted mb-0">
                        No hay estantes registrados para este almacén.
                    </p>
                @else
                    <div class="row">
                        @foreach ($almacen->estantes as $estante)
                            <div class="col-md-6 col-lg-4">
                                <div class="estante-card">
                                    <div class="estante-title">
                                        Estante: {{ $estante->codigo_estante }}
                                    </div>
                                    @if($estante->descripcion)
                                        <div class="text-muted mb-1" style="font-size: .8rem;">
                                            {{ $estante->descripcion }}
                                        </div>
                                    @endif>

                                    @php
                                        $espacios = $estante->espacios;
                                    @endphp

                                    @if ($espacios->isEmpty())
                                        <div class="text-muted" style="font-size: .8rem;">
                                            Sin espacios registrados.
                                        </div>
                                    @else
                                        <div class="chips-container mt-1">
                                            @foreach ($espacios as $espacio)
                                                @php
                                                    $estado = $espacio->estado ?? 'desconocido';
                                                    $estadoClase = match($estado) {
                                                        'disponible' => 'disponible',
                                                        'ocupado' => 'ocupado',
                                                        default => 'desconocido',
                                                    };
                                                @endphp
                                                <div class="chip">
                                                    <span class="chip-dot {{ $estadoClase }}"></span>
                                                    <span>{{ $espacio->codigo_espacio }}</span>
                                                    <span class="text-muted" style="font-size: .7rem;">
                                                        {{ ucfirst($estado) }}
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

            </div>
        </div>
    @endforeach

</div>
@endsection
