@extends('layouts.app')

@section('title','Cierre de caja')

@section('header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0">Cierre de caja</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
            <li class="breadcrumb-item active">Cierre de caja</li>
        </ol>
    </div>
</div>
@endsection

@section('content')

<div class="row">
    <div class="col-12">

        <!-- FILTROS -->
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-filter mr-2"></i>Filtros de búsqueda</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <form method="GET">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><i class="fas fa-bullhorn mr-1"></i>Campaña</label>
                                <select name="campaniaid" class="form-control">
                                    <option value="">Todas</option>
                                    @foreach($campanias as $c)
                                        <option value="{{ $c->campaniaid }}"
                                            {{ request('campaniaid') == $c->campaniaid ? 'selected' : '' }}>
                                            {{ $c->titulo }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label><i class="fas fa-calendar-alt mr-1"></i>Desde</label>
                                <input type="date" name="desde" class="form-control" value="{{ request('desde') }}">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label><i class="fas fa-calendar-alt mr-1"></i>Hasta</label>
                                <input type="date" name="hasta" class="form-control" value="{{ request('hasta') }}">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label><i class="fas fa-info-circle mr-1"></i>Estado</label>
                                <select name="estadoid" class="form-control">
                                    <option value="">Todos</option>
                                    <option value="1" {{ request('estadoid') == 1 ? 'selected' : '' }}>Pendiente</option>
                                    <option value="2" {{ request('estadoid') == 2 ? 'selected' : '' }}>Confirmada</option>
                                    <option value="3" {{ request('estadoid') == 3 ? 'selected' : '' }}>Asignada</option>
                                    <option value="4" {{ request('estadoid') == 4 ? 'selected' : '' }}>Utilizada</option>
                                    <option value="5" {{ request('estadoid') == 5 ? 'selected' : '' }}>Cancelada</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer d-flex justify-content-between">
                    <button class="btn btn-primary">
                        <i class="fas fa-search mr-1"></i> Aplicar filtros
                    </button>

                    <div>
                        <a href="{{ route('reporte.cierreCaja') }}" class="btn btn-secondary mr-2">
                            <i class="fas fa-eraser mr-1"></i> Limpiar
                        </a>
                        <a href="{{ route('reporte.cierreCaja.pdf', request()->query()) }}"
                           class="btn btn-danger">
                            <i class="fas fa-file-pdf mr-1"></i> Exportar PDF
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- TOTALES GENERALES -->
        <div class="row">
            <div class="col-md-4">
                <div class="info-box bg-gradient-info">
                    <span class="info-box-icon"><i class="fas fa-coins"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total recaudado</span>
                        <span class="info-box-number">Bs {{ number_format($totalGeneral, 2) }}</span>
                        <div class="progress">
                            <div class="progress-bar" style="width: 100%"></div>
                        </div>
                        <span class="progress-description">
                            {{ $donaciones->count() }} {{ $donaciones->count() == 1 ? 'donación' : 'donaciones' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="info-box bg-gradient-success">
                    <span class="info-box-icon"><i class="fas fa-check-circle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Confirmadas</span>
                        <span class="info-box-number">Bs {{ number_format($totalConfirmadas, 2) }}</span>
                        <div class="progress">
                            <div class="progress-bar" style="width: {{ $totalGeneral > 0 ? ($totalConfirmadas/$totalGeneral)*100 : 0 }}%"></div>
                        </div>
                        <span class="progress-description">
                            {{ $totalGeneral > 0 ? number_format(($totalConfirmadas/$totalGeneral)*100, 1) : 0 }}% del total
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="info-box bg-gradient-warning">
                    <span class="info-box-icon"><i class="fas fa-hourglass-half"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Pendientes</span>
                        <span class="info-box-number">Bs {{ number_format($totalPendientes, 2) }}</span>
                        <div class="progress">
                            <div class="progress-bar" style="width: {{ $totalGeneral > 0 ? ($totalPendientes/$totalGeneral)*100 : 0 }}%"></div>
                        </div>
                        <span class="progress-description">
                            {{ $totalGeneral > 0 ? number_format(($totalPendientes/$totalGeneral)*100, 1) : 0 }}% del total
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- DONACIONES POR CAMPAÑA -->
        @php
            $donacionesPorCampania = $donaciones->groupBy('campaniaid');
        @endphp

        @forelse($donacionesPorCampania as $campaniaId => $donacionesCampania)
            @php
                $campania = $donacionesCampania->first()->campania;
                $totalCampania = $donacionesCampania->sum('monto');
                $cantidadDonaciones = $donacionesCampania->count();
                $confirmadas = $donacionesCampania->where('estadoid', 2)->sum('monto');
                $pendientes = $donacionesCampania->where('estadoid', 1)->sum('monto');
            @endphp

            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-bullhorn mr-2"></i>
                        <strong>{{ $campania->titulo }}</strong>
                    </h3>
                    <div class="card-tools">
                        <span class="badge badge-primary mr-2">
                            {{ $cantidadDonaciones }} {{ $cantidadDonaciones == 1 ? 'donación' : 'donaciones' }}
                        </span>
                        <span class="badge badge-info">
                            Total: Bs {{ number_format($totalCampania, 2) }}
                        </span>
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Mini resumen de la campaña -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="info-box bg-light">
                                <span class="info-box-icon bg-success"><i class="fas fa-check"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Confirmadas</span>
                                    <span class="info-box-number text-success">Bs {{ number_format($confirmadas, 2) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box bg-light">
                                <span class="info-box-icon bg-warning"><i class="fas fa-clock"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Pendientes</span>
                                    <span class="info-box-number text-warning">Bs {{ number_format($pendientes, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabla de donaciones -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width: 60px">#ID</th>
                                    <th>Donante</th>
                                    <th style="width: 120px">Monto</th>
                                    <th style="width: 100px">Tipo</th>
                                    <th style="width: 110px">Estado</th>
                                    <th style="width: 150px">Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($donacionesCampania as $d)
                                <!-- Fila principal de donación -->
                                <tr>
                                    <td><span class="badge badge-secondary">{{ $d->donacionid }}</span></td>
                                    <td>
                                        @if($d->esanonima)
                                            <span class="badge badge-secondary">
                                                <i class="fas fa-user-secret mr-1"></i>Anónimo
                                            </span>
                                        @else
                                            <i class="fas fa-user mr-1 text-muted"></i>
                                            {{ optional($d->usuario)->nombre }} {{ optional($d->usuario)->apellido }}
                                        @endif
                                    </td>
                                    <td>
                                        <strong class="text-primary">Bs {{ number_format($d->monto, 2) }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge badge-info">{{ $d->tipodonacion }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $estadoBadge = [
                                                1 => 'warning',
                                                2 => 'success',
                                                3 => 'info',
                                                4 => 'primary',
                                                5 => 'danger'
                                            ];
                                            $badgeClass = $estadoBadge[$d->estadoid] ?? 'secondary';
                                        @endphp
                                        <span class="badge badge-{{ $badgeClass }}">
                                            {{ $d->estado->nombre }}
                                        </span>
                                    </td>
                                    <td>
                                        <i class="far fa-calendar mr-1 text-muted"></i>
                                        {{ \Carbon\Carbon::parse($d->fechadonacion)->format('d/m/Y H:i') }}
                                    </td>
                                </tr>

                                <!-- USO DE LA DONACIÓN -->
                                @if($d->asignacionesPivot->count())
                                <tr>
                                    <td colspan="6" class="p-0" style="background-color: #f4f6f9;">
                                        <div class="p-3">
                                            <div class="callout callout-info mb-0">
                                                <h6 class="mb-3">
                                                    <i class="fas fa-hand-holding-usd mr-2"></i>
                                                    <strong>Uso de esta donación:</strong>
                                                </h6>

                                                @foreach($d->asignacionesPivot as $pivot)
                                                    @php 
                                                        $asig = $pivot->asignacion; 
                                                    @endphp
                                                    
                                                    <div class="card card-outline card-success mb-3">
                                                        <div class="card-header py-2">
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <div>
                                                                    <i class="fas fa-tasks mr-2"></i>
                                                                    <strong>Asignación #{{ $asig->asignacionid }}</strong>
                                                                    <span class="ml-2 badge badge-success">
                                                                        Bs {{ number_format($pivot->montoasignado, 2) }}
                                                                    </span>
                                                                </div>
                                                                <small class="text-muted">
                                                                    <i class="far fa-calendar-alt mr-1"></i>
                                                                    {{ \Carbon\Carbon::parse($asig->fechaasignacion)->format('d/m/Y') }}
                                                                </small>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="card-body py-2">
                                                            @if($asig->descripcion)
                                                            <p class="mb-2">
                                                                <i class="fas fa-info-circle mr-1 text-info"></i>
                                                                <strong>Descripción:</strong> {{ $asig->descripcion }}
                                                            </p>
                                                            @endif

                                                            @if($asig->detalles->count())
                                                            <div class="mt-2">
                                                                <strong class="text-muted d-block mb-2">
                                                                    <i class="fas fa-list mr-1"></i>Detalle de gastos:
                                                                </strong>
                                                                <div class="table-responsive">
                                                                    <table class="table table-sm table-striped mb-0">
                                                                        <thead class="bg-light">
                                                                            <tr>
                                                                                <th>Concepto</th>
                                                                                <th style="width: 80px" class="text-center">Cantidad</th>
                                                                                <th style="width: 100px" class="text-right">P. Unitario</th>
                                                                                <th style="width: 100px" class="text-right">Subtotal</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach($asig->detalles as $det)
                                                                            <tr>
                                                                                <td>{{ $det->concepto }}</td>
                                                                                <td class="text-center">
                                                                                    <span class="badge badge-light">{{ $det->cantidad }}</span>
                                                                                </td>
                                                                                <td class="text-right">
                                                                                    Bs {{ number_format($det->preciounitario, 2) }}
                                                                                </td>
                                                                                <td class="text-right">
                                                                                    <strong class="text-success">
                                                                                        Bs {{ number_format($det->cantidad * $det->preciounitario, 2) }}
                                                                                    </strong>
                                                                                </td>
                                                                            </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                        <tfoot class="bg-light">
                                                                            <tr>
                                                                                <th colspan="3" class="text-right">Total:</th>
                                                                                <th class="text-right text-success">
                                                                                    Bs {{ number_format($asig->detalles->sum(function($d) { 
                                                                                        return $d->cantidad * $d->preciounitario; 
                                                                                    }), 2) }}
                                                                                </th>
                                                                            </tr>
                                                                        </tfoot>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                            @else
                                                            <div class="alert alert-light mb-0 py-2">
                                                                <i class="fas fa-info-circle mr-1"></i>
                                                                <small>Sin detalles de gastos registrados.</small>
                                                            </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                            <tfoot class="bg-light">
                                <tr>
                                    <th colspan="2" class="text-right">Subtotal de campaña:</th>
                                    <th colspan="4">
                                        <span class="text-primary">Bs {{ number_format($totalCampania, 2) }}</span>
                                        <span class="text-muted ml-2">({{ $cantidadDonaciones }} {{ $cantidadDonaciones == 1 ? 'donación' : 'donaciones' }})</span>
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

        @empty
            <div class="card">
                <div class="card-body">
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                        <h4 class="text-muted">No hay donaciones para mostrar</h4>
                        <p class="text-muted">No se encontraron donaciones con los filtros seleccionados.</p>
                    </div>
                </div>
            </div>
        @endforelse

    </div>
</div>

@endsection