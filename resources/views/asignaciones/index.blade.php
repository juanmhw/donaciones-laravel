@extends('layouts.app')

@php use Illuminate\Support\Str; @endphp

@section('title', 'Lista de Asignaciones')

@section('header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Asignaciones</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                <li class="breadcrumb-item active">Asignaciones</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Listado de Asignaciones</h3>
                    <div class="card-tools">
                        <a href="{{ route('asignaciones.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Nueva Asignación
                        </a>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 60px">ID</th>
                                    <th>Campaña</th>
                                    <th>Descripción</th>
                                    <th class="text-right" style="width: 140px">Monto</th>
                                    <th style="width: 160px">Fecha</th>
                                    <th>Usuario</th>
                                    <th>Comprobante</th>
                                    <th class="text-center" style="width: 90px">Ítems</th>
                                    <th class="text-center" style="width: 120px">Donaciones</th>
                                    <th class="text-center" style="width: 210px">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($asignaciones as $a)
                                    @php
                                        $detCount = $a->detalles_count ?? ($a->relationLoaded('detalles') ? $a->detalles->count() : 0);
                                        $donCount = $a->donaciones_count ?? ($a->relationLoaded('donacionesPivot') ? $a->donacionesPivot->count() : 0);
                                    @endphp
                                    <tr>
                                        <td>{{ $a->asignacionid }}</td>
                                        <td>
                                            @if($a->campania)
                                                <span class="badge badge-info">{{ $a->campania->titulo }}</span>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>{{ Str::limit($a->descripcion, 50) }}</td>
                                        <td class="text-right"><strong>Bs {{ number_format($a->monto, 2) }}</strong></td>
                                        <td>
                                            @if($a->fechaasignacion)
                                                <small>{{ \Carbon\Carbon::parse($a->fechaasignacion)->format('d/m/Y H:i') }}</small>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($a->usuario)
                                                <small>{{ $a->usuario->email }}</small>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($a->comprobante)
                                                <small title="{{ $a->comprobante }}">{{ Str::limit($a->comprobante, 18) }}</small>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-secondary">{{ $detCount }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-success">{{ $donCount }}</span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('asignaciones.detalles', $a->asignacionid) }}"
                                                   class="btn btn-info" title="Ver / Agregar ítems">
                                                    <i class="fas fa-list"></i>
                                                </a>
                                                <a href="{{ route('asignaciones.asignar', $a->asignacionid) }}"
                                                   class="btn btn-success" title="Asignar donaciones">
                                                    <i class="fas fa-donate"></i>
                                                </a>
                                                <a href="{{ route('asignaciones.edit', $a->asignacionid) }}"
                                                   class="btn btn-warning" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('asignaciones.destroy', $a->asignacionid) }}"
                                                      method="POST" style="display:inline;"
                                                      onsubmit="return confirm('¿Eliminar esta asignación?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-danger" title="Eliminar">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center text-muted py-4">
                                            <i class="fas fa-inbox fa-3x mb-3"></i>
                                            <p>No hay asignaciones registradas</p>
                                            <a href="{{ route('asignaciones.create') }}" class="btn btn-primary">
                                                <i class="fas fa-plus"></i> Crear primera asignación
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Si más adelante paginas, esto se activa automáticamente --}}
                @if($asignaciones instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    <div class="card-footer clearfix">
                        {{ $asignaciones->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    setTimeout(function(){ $('.alert').fadeOut('slow'); }, 5000);
</script>
@endpush
