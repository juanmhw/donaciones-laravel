@extends('layouts.app')

@push('css')
<style>
  /* Ajustes mínimos (AdminLTE hace lo demás) */
  .almx-space-btn{
    border-radius: 999px;
    padding: .2rem .55rem;
    line-height: 1.1;
    margin: .18rem .18rem 0 0;
  }
  .almx-space-wrap{ display:flex; flex-wrap:wrap; }
  .almx-muted{ color:#6c757d; font-size:.85rem; }
  .almx-badge{ font-size:.72rem; padding:.35rem .5rem; border-radius:999px; }

  /* Fixes típicos AdminLTE + Bootstrap modal */
  .modal { overflow-y:auto; }
  .modal-backdrop { z-index:1040 !important; }
  .modal { z-index:1050 !important; }
</style>
@endpush

@section('content')
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-8">
        <h1 class="m-0">Estructura de Almacenes</h1>
        <p class="text-muted mb-0">Visualiza almacenes, estantes y espacios sincronizados.</p>
      </div>
      <div class="col-sm-4">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
          <li class="breadcrumb-item active">Almacenes</li>
        </ol>
      </div>
    </div>
  </div>
</section>

<section class="content">
  <div class="container-fluid">

    @forelse ($almacenes as $almacen)
      @php
        $totalEstantes = $almacen->estantes->count();
        $totalEspacios = $almacen->estantes->flatMap->espacios->count();
      @endphp

      <div class="card card-primary card-outline">
        <div class="card-header">
          <div class="d-flex justify-content-between align-items-start flex-wrap" style="gap:10px;">
            <div>
              <h3 class="card-title font-weight-bold">
                <i class="fas fa-warehouse mr-1"></i> {{ $almacen->nombre }}
              </h3>
              <div class="almx-muted mt-1">
                @if($almacen->direccion)
                  <i class="fas fa-map-marker-alt mr-1"></i> {{ $almacen->direccion }}
                @endif

                @if($almacen->latitud && $almacen->longitud)
                  <span class="ml-2">
                    <i class="fas fa-location-arrow mr-1"></i>
                    {{ $almacen->latitud }} · {{ $almacen->longitud }}
                  </span>
                @endif
              </div>
            </div>

            <div class="card-tools d-flex" style="gap:.5rem;">
              <span class="badge badge-info almx-badge">
                <i class="fas fa-layer-group mr-1"></i> {{ $totalEstantes }} estantes
              </span>
              <span class="badge badge-secondary almx-badge">
                <i class="fas fa-th mr-1"></i> {{ $totalEspacios }} espacios
              </span>

              <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Contraer">
                <i class="fas fa-minus"></i>
              </button>
              <button type="button" class="btn btn-tool" data-card-widget="remove" title="Cerrar">
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>
        </div>

        <div class="card-body">

          {{-- Resumen --}}
          <div class="row mb-3">
            <div class="col-md-6 col-lg-3">
              <div class="info-box">
                <span class="info-box-icon bg-info"><i class="fas fa-layer-group"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Estantes</span>
                  <span class="info-box-number">{{ $totalEstantes }}</span>
                </div>
              </div>
            </div>

            <div class="col-md-6 col-lg-3">
              <div class="info-box">
                <span class="info-box-icon bg-secondary"><i class="fas fa-th-large"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Espacios</span>
                  <span class="info-box-number">{{ $totalEspacios }}</span>
                </div>
              </div>
            </div>

            <div class="col-md-12 col-lg-6">
              <div class="callout callout-info mb-0">
                <div class="d-flex flex-wrap" style="gap:.5rem;">
                  <span class="badge badge-success almx-badge">
                    <i class="fas fa-circle mr-1"></i> Disponible
                  </span>
                  <span class="badge badge-danger almx-badge">
                    <i class="fas fa-circle mr-1"></i> Ocupado
                  </span>
                  <span class="badge badge-light almx-badge">
                    <i class="fas fa-circle mr-1"></i> Desconocido
                  </span>
                </div>
                <small class="text-muted d-block mt-1">
                  Tip: los espacios con contenido se pueden abrir para ver detalle.
                </small>
              </div>
            </div>
          </div>

          {{-- Estantes --}}
          @if ($almacen->estantes->isEmpty())
            <div class="alert alert-warning mb-0">
              <i class="fas fa-exclamation-triangle mr-1"></i>
              No hay estantes registrados para este almacén.
            </div>
          @else
            <div class="row">
              @foreach ($almacen->estantes as $estante)
                @php $espacios = $estante->espacios; @endphp

                <div class="col-md-6 col-lg-4">
                  <div class="card card-outline card-secondary">
                    <div class="card-header">
                      <h3 class="card-title">
                        <i class="fas fa-stream mr-1 text-muted"></i>
                        Estante: <strong>{{ $estante->codigo_estante }}</strong>
                      </h3>
                      <div class="card-tools">
                        <span class="badge badge-light">{{ $espacios->count() }} espacios</span>
                      </div>
                    </div>

                    <div class="card-body">
                      @if($estante->descripcion)
                        <p class="text-muted mb-2">{{ $estante->descripcion }}</p>
                      @endif

                      @if($espacios->isEmpty())
                        <div class="text-muted">Sin espacios registrados.</div>
                      @else
                        <div class="almx-space-wrap">
                          @foreach ($espacios as $espacio)
                            @php
                              $items = $espacio->items;
                              $tieneItems = $items->count() > 0;

                              // Botón por estado
                              $btnClass = 'btn-outline-secondary';
                              $badge = 'badge-light';
                              $estadoTxt = 'Desconocido';

                              if ($tieneItems) { $btnClass = 'btn-outline-danger'; $badge='badge-danger'; $estadoTxt='Ocupado'; }
                              elseif (($espacio->estado ?? '') === 'disponible') { $btnClass = 'btn-outline-success'; $badge='badge-success'; $estadoTxt='Disponible'; }

                              // Payload estable (ya formateado) para el modal
                              $payload = $items->map(function($item){
                                return [
                                  'nombre_producto' => $item->nombre_producto,
                                  'cantidad_donada' => $item->cantidad_donada,
                                  'unidad_empaque'  => $item->unidad_empaque,
                                  'nombre_donante'  => $item->nombre_donante,
                                  'fecha'           => \Carbon\Carbon::parse($item->fecha_donacion)->format('d/m/Y'),
                                  'codigo_unico'    => $item->codigo_unico,
                                  'url'             => $item->codigo_paquete ? route('reportes.trazabilidad.paquete', $item->codigo_paquete) : null,
                                ];
                              })->values();
                            @endphp

                            @if($tieneItems)
                              <button type="button"
                                class="btn btn-sm {{ $btnClass }} almx-space-btn"
                                data-toggle="modal"
                                data-target="#modalEspacioGlobal"
                                data-espacio="{{ $espacio->codigo_espacio }}"
                                data-almacen="{{ $almacen->nombre }}"
                                data-estante="{{ $estante->codigo_estante }}"
                                data-estado="{{ $estadoTxt }}"
                                data-badge="{{ $badge }}"
                                data-items='@json($payload)'>
                                <i class="fas fa-box mr-1"></i>
                                {{ $espacio->codigo_espacio }}
                                <span class="badge {{ $badge }} ml-1">{{ $items->count() }}</span>
                              </button>
                            @else
                              <span class="btn btn-sm {{ $btnClass }} almx-space-btn disabled" title="Espacio vacío">
                                <i class="fas fa-circle mr-1"></i>
                                {{ $espacio->codigo_espacio }}
                              </span>
                            @endif
                          @endforeach
                        </div>
                      @endif
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
          @endif

        </div>
      </div>

    @empty
      <div class="alert alert-info">
        <i class="fas fa-info-circle mr-1"></i>
        No existen almacenes registrados.
      </div>
    @endforelse

  </div>
</section>

{{-- ✅ MODAL GLOBAL ÚNICO (fuera de loops) --}}
<div class="modal fade" id="modalEspacioGlobal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
    <div class="modal-content">

      <div class="modal-header bg-primary">
        <h5 class="modal-title">
          <i class="fas fa-box-open mr-1"></i>
          <span id="almxModalTitulo">Contenido</span>
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <div class="d-flex align-items-center flex-wrap" style="gap:.5rem;">
          <span class="badge" id="almxModalEstadoBadge">-</span>
          <small class="text-muted" id="almxModalUbicacion">-</small>
        </div>

        <hr class="my-3">

        <div class="table-responsive">
          <table class="table table-sm table-hover">
            <thead class="thead-light">
              <tr>
                <th>Producto</th>
                <th class="text-center">Cantidad</th>
                <th>Donante</th>
                <th>Fecha</th>
                <th>Código</th>
                <th class="text-right">Acción</th>
              </tr>
            </thead>
            <tbody id="almxModalBody"></tbody>
          </table>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>

    </div>
  </div>
</div>

@push('scripts')
<script>
(function () {
  // Limpia backdrop si quedó “colgado” por algún cierre raro
  $('#modalEspacioGlobal').on('hidden.bs.modal', function () {
    $('body').removeClass('modal-open');
    $('.modal-backdrop').remove();
  });

  $('#modalEspacioGlobal').on('show.bs.modal', function (event) {
    const button = $(event.relatedTarget);

    const espacio = button.data('espacio') || '';
    const almacen = button.data('almacen') || '';
    const estante = button.data('estante') || '';
    const estado  = button.data('estado')  || 'Desconocido';
    const badge   = button.data('badge')   || 'badge-light';

    const items = button.data('items') || [];

    $('#almxModalTitulo').text(`Contenido en ${espacio}`);
    $('#almxModalUbicacion').text(`${almacen} » ${estante}`);
    $('#almxModalEstadoBadge').attr('class', `badge ${badge}`).text(estado);

    let rows = '';

    if (!items.length) {
      rows = `<tr><td colspan="6" class="text-center text-muted">Sin contenido</td></tr>`;
    } else {
      items.forEach(item => {
        const url = item.url;
        const accion = url
          ? `<a class="btn btn-xs btn-primary" href="${url}">
               <i class="fas fa-eye"></i> Ver Detalles
             </a>`
          : `<span class="text-muted">-</span>`;

        rows += `
          <tr>
            <td class="font-weight-bold text-primary">${item.nombre_producto ?? ''}</td>
            <td class="text-center">${item.cantidad_donada ?? ''} ${item.unidad_empaque ?? ''}</td>
            <td>${item.nombre_donante ?? ''}</td>
            <td>${item.fecha ?? ''}</td>
            <td class="text-nowrap">${item.codigo_unico ?? ''}</td>
            <td class="text-right">${accion}</td>
          </tr>
        `;
      });
    }

    $('#almxModalBody').html(rows);
  });
})();
</script>
@endpush

@endsection
