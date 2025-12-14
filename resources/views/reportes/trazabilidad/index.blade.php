@extends('layouts.app')

@section('title', 'Reporte de Trazabilidad')

@section('header')
    <h1 class="m-0 text-dark">
        <i class="fas fa-route mr-2"></i>
        Reporte de Trazabilidad
    </h1>
    <p class="text-muted mb-0">
        Seguimiento completo de productos donados desde su origen hasta el destino final
    </p>
@endsection

@section('content')
<div class="container-fluid">

    {{-- FILTROS --}}
    <div class="row mb-3">
        <div class="col-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-filter mr-2"></i>
                        Filtros de búsqueda
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <form method="GET" action="{{ route('reportes.trazabilidad.index') }}">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="campaniaid">
                                        <i class="fas fa-bullhorn mr-1 text-info"></i>
                                        Campaña
                                    </label>
                                    <select name="campaniaid" id="campaniaid" class="form-control select2">
                                        <option value="">-- Todas las campañas --</option>
                                        @foreach($campanias as $campania)
                                            <option value="{{ $campania->campaniaid }}"
                                                {{ $campaniaId == $campania->campaniaid ? 'selected' : '' }}>
                                                {{ $campania->titulo }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="estado">
                                        <i class="fas fa-info-circle mr-1 text-warning"></i>
                                        Estado
                                    </label>
                                    <select name="estado" id="estado" class="form-control">
                                        <option value="">-- Todos los estados --</option>
                                        <option value="En almacén" {{ request('estado') == 'En almacén' ? 'selected' : '' }}>En almacén</option>
                                        <option value="En tránsito" {{ request('estado') == 'En tránsito' ? 'selected' : '' }}>En tránsito</option>
                                        <option value="Entregado" {{ request('estado') == 'Entregado' ? 'selected' : '' }}>Entregado</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="buscar">
                                        <i class="fas fa-search mr-1 text-success"></i>
                                        Buscar código
                                    </label>
                                    <input type="text" name="buscar" id="buscar" class="form-control" 
                                           value="{{ request('buscar') }}"
                                           placeholder="Código único del producto">
                                </div>
                            </div>

                            <div class="col-md-2 d-flex align-items-end">
                                <div class="form-group w-100">
                                    <button class="btn btn-primary btn-block" type="submit">
                                        <i class="fas fa-search mr-1"></i>
                                        Filtrar
                                    </button>
                                </div>
                            </div>
                            <a href="{{ route('reportes.trazabilidad.pdf', ['campaniaid' => request('campaniaid')]) }}" 
                                class="btn btn-danger" 
                                target="_blank">
                                    <i class="fas fa-file-pdf"></i> Exportar a PDF
                            </a>
                            {{-- Botón Exportar Excel --}}
                            <a href="{{ route('reportes.trazabilidad.excel', request()->all()) }}" class="btn btn-success" target="_blank">
                                <i class="fas fa-file-excel"></i> Exportar Excel
                            </a>
                            
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ESTADÍSTICAS RÁPIDAS --}}
    <div class="row mb-3">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-info elevation-1">
                    <i class="fas fa-boxes"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Productos</span>
                    <span class="info-box-number">
                        {{ $items->total() }}
                    </span>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-warning elevation-1">
                    <i class="fas fa-warehouse"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">En Almacén</span>
                    <span class="info-box-number">
                        {{ $items->getCollection()->where('estado_actual', 'En almacén')->count() }}
                    </span>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-primary elevation-1">
                    <i class="fas fa-truck"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">En Tránsito</span>
                    <span class="info-box-number">
                        {{ $items->getCollection()->where('estado_actual', 'En tránsito')->count() }}
                    </span>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-success elevation-1">
                    <i class="fas fa-check-circle"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Entregados</span>
                    <span class="info-box-number">
                        {{ $items->getCollection()->where('estado_actual', 'Entregado')->count() }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- TABLA DE TRAZABILIDAD --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-list mr-2"></i>
                        Detalle de Trazabilidad
                    </h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-valign-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Código</th>
                                    <th>Producto</th>
                                    <th>Categoría</th>
                                    <th>Campaña</th>
                                    <th class="text-center">Cantidad</th>
                                    <th>Estado</th>
                                    <th>Ubicación</th>
                                    <th>Cronología</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($items as $item)
                                    @php
                                        $estadoBadge = [
                                            'En almacén' => 'warning',
                                            'En tránsito' => 'primary',
                                            'Entregado' => 'success',
                                            'Pendiente' => 'secondary'
                                        ];
                                        $estadoIcon = [
                                            'En almacén' => 'warehouse',
                                            'En tránsito' => 'truck',
                                            'Entregado' => 'check-circle',
                                            'Pendiente' => 'clock'
                                        ];
                                        $estado = $item->estado_actual ?? 'Sin definir';
                                        $badgeClass = $estadoBadge[$estado] ?? 'secondary';
                                        $icon = $estadoIcon[$estado] ?? 'info-circle';
                                    @endphp

                                    <tr>
                                        <td>{{ $loop->iteration + $items->firstItem() - 1 }}</td>
                                        
                                        <td>
                                            <span class="badge badge-secondary">
                                                <i class="fas fa-barcode mr-1"></i>
                                                {{ $item->codigo_unico }}
                                            </span>
                                        </td>

                                        <td>
                                            <strong>{{ $item->nombre_producto }}</strong>
                                        </td>

                                        <td>
                                            <span class="badge badge-light">
                                                {{ $item->categoria_producto }}
                                            </span>
                                        </td>

                                        <td>
                                            <i class="fas fa-bullhorn text-muted mr-1"></i>
                                            {{ $item->campania->titulo ?? $item->campania_nombre }}
                                        </td>

                                        <td class="text-center">
                                            <span class="badge badge-info">
                                                {{ $item->cantidad_donada }}
                                            </span>
                                        </td>

                                        <td>
                                            <span class="badge badge-{{ $badgeClass }}">
                                                <i class="fas fa-{{ $icon }} mr-1"></i>
                                                {{ $estado }}
                                            </span>
                                        </td>

                                        <td>
                                            <i class="fas fa-map-marker-alt text-danger mr-1"></i>
                                            {{ $item->ubicacion_actual }}
                                        </td>

                                        <td>
                                            <small class="text-muted">
                                                @if($item->fecha_donacion)
                                                    <i class="fas fa-calendar-plus text-success mr-1"></i>
                                                    Donación: {{ \Carbon\Carbon::parse($item->fecha_donacion)->format('d/m/Y') }}
                                                    <br>
                                                @endif
                                                @if($item->fecha_salida)
                                                    <i class="fas fa-calendar-check text-primary mr-1"></i>
                                                    Salida: {{ \Carbon\Carbon::parse($item->fecha_salida)->format('d/m/Y') }}
                                                    <br>
                                                @endif
                                                @if($item->destino_final)
                                                    <i class="fas fa-flag-checkered text-warning mr-1"></i>
                                                    Destino: {{ $item->destino_final }}
                                                @endif
                                            </small>
                                        </td>

                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm">
                                                <button type="button" class="btn btn-info" 
                                                        data-toggle="modal" 
                                                        data-target="#modalDetalle{{ $item->trazabilidadid }}"
                                                        title="Ver detalles">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-5">
                                            <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                                            <h5 class="text-muted">No hay datos disponibles</h5>
                                            <p class="text-muted">
                                                Intenta ajustar los filtros de búsqueda
                                            </p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- PAGINACIÓN --}}
                @if($items->hasPages())
                    <div class="card-footer clearfix">
                        <div class="float-left">
                            <p class="text-muted mb-0">
                                Mostrando {{ $items->firstItem() }} a {{ $items->lastItem() }} 
                                de {{ $items->total() }} registros
                            </p>
                        </div>
                        <div class="float-right">
                            {{ $items->withQueryString()->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- MODALES DE DETALLE (fuera de la tabla) --}}
    @foreach ($items as $item)
        @php
            $estadoBadge = [
                'En almacén' => 'warning',
                'En tránsito' => 'primary',
                'Entregado' => 'success',
                'Pendiente' => 'secondary'
            ];
            $estado = $item->estado_actual ?? 'Sin definir';
            $badgeClass = $estadoBadge[$estado] ?? 'secondary';
        @endphp

        <div class="modal fade" id="modalDetalle{{ $item->trazabilidadid }}" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-info">
                        <h5 class="modal-title">
                            <i class="fas fa-info-circle mr-2"></i>
                            Detalle de Trazabilidad - {{ $item->codigo_unico }}
                        </h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-bold">Información del Producto</h6>
                                <dl class="row">
                                    <dt class="col-sm-5">Código:</dt>
                                    <dd class="col-sm-7">{{ $item->codigo_unico }}</dd>
                                    
                                    <dt class="col-sm-5">Producto:</dt>
                                    <dd class="col-sm-7">{{ $item->nombre_producto }}</dd>
                                    
                                    <dt class="col-sm-5">Categoría:</dt>
                                    <dd class="col-sm-7">{{ $item->categoria_producto }}</dd>
                                    
                                    <dt class="col-sm-5">Cantidad:</dt>
                                    <dd class="col-sm-7">{{ $item->cantidad_donada }}</dd>
                                </dl>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-bold">Estado y Ubicación</h6>
                                <dl class="row">
                                    <dt class="col-sm-5">Estado Actual:</dt>
                                    <dd class="col-sm-7">
                                        <span class="badge badge-{{ $badgeClass }}">
                                            {{ $estado }}
                                        </span>
                                    </dd>
                                    
                                    <dt class="col-sm-5">Ubicación:</dt>
                                    <dd class="col-sm-7">{{ $item->ubicacion_actual }}</dd>
                                    
                                    <dt class="col-sm-5">Destino Final:</dt>
                                    <dd class="col-sm-7">{{ $item->destino_final ?? 'N/A' }}</dd>
                                </dl>
                            </div>
                        </div>

                        <hr>

                        <h6 class="text-bold">
                            <i class="fas fa-clock mr-2"></i>
                            Cronología
                        </h6>
                        <div class="timeline">
                            @if($item->fecha_donacion)
                                <div class="time-label">
                                    <span class="bg-success">
                                        {{ \Carbon\Carbon::parse($item->fecha_donacion)->format('d/m/Y') }}
                                    </span>
                                </div>
                                <div>
                                    <i class="fas fa-hand-holding-heart bg-success"></i>
                                    <div class="timeline-item">
                                        <h3 class="timeline-header">Donación Recibida</h3>
                                        <div class="timeline-body">
                                            Producto ingresado al sistema
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($item->fecha_salida)
                                <div class="time-label">
                                    <span class="bg-primary">
                                        {{ \Carbon\Carbon::parse($item->fecha_salida)->format('d/m/Y') }}
                                    </span>
                                </div>
                                <div>
                                    <i class="fas fa-truck bg-primary"></i>
                                    <div class="timeline-item">
                                        <h3 class="timeline-header">Salida del Almacén</h3>
                                        <div class="timeline-body">
                                            Producto en tránsito hacia destino final
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div>
                                <i class="fas fa-clock bg-gray"></i>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

</div>
@endsection

@push('styles')
<style>
    .timeline {
        position: relative;
        margin: 0 0 30px 0;
        padding: 0;
        list-style: none;
    }
    .timeline:before {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        width: 4px;
        background: #ddd;
        left: 31px;
        margin: 0;
    }
    .timeline > div {
        margin-bottom: 15px;
        position: relative;
    }
    .timeline > div > .timeline-item {
        box-shadow: 0 1px 1px rgba(0,0,0,.1);
        border-radius: .25rem;
        background: #fff;
        color: #495057;
        margin-left: 60px;
        margin-right: 15px;
        margin-top: 0;
        padding: 10px;
        position: relative;
    }
    .timeline > div > .fa,
    .timeline > div > .fas,
    .timeline > div > .far {
        width: 30px;
        height: 30px;
        font-size: 15px;
        line-height: 30px;
        position: absolute;
        color: #fff;
        background: #999;
        border-radius: 50%;
        text-align: center;
        left: 18px;
        top: 0;
    }
    .timeline > .time-label > span {
        font-weight: 600;
        padding: 5px;
        display: inline-block;
        background-color: #fff;
        border-radius: .25rem;
    }
    .timeline-header {
        margin: 0;
        color: #495057;
        border-bottom: 1px solid rgba(0,0,0,.125);
        padding: 5px 0;
        font-size: 16px;
        line-height: 1.1;
    }
    .timeline-body {
        padding: 10px 0 0 0;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        if($.fn.select2) {
            $('.select2').select2({
                theme: 'bootstrap4',
                placeholder: 'Seleccione una opción'
            });
        }
    });
</script>
@endpush