@extends('layouts.app')

@section('title', 'Asignar Donaciones')

@section('header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Asignar Donaciones a #{{ $asignacion->asignacionid }}</h1>
            <small class="text-muted">Campaña: {{ optional($asignacion->campania)->titulo ?? 'N/A' }}</small>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                <li class="breadcrumb-item"><a href="{{ route('asignaciones.index') }}">Asignaciones</a></li>
                <li class="breadcrumb-item active">Asignar</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <p class="mb-3">
        <span class="badge badge-primary">Total asignación (ítems): Bs {{ number_format($asignacion->monto ?? 0,2) }}</span>
        <span class="badge badge-success">Ya asignado: Bs {{ number_format($yaAsignado ?? 0,2) }}</span>
        <span class="badge badge-warning">Faltante: Bs {{ number_format($faltante ?? 0,2) }}</span>
    </p>

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Revisa los errores:</strong>
            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="card">
        <div class="card-header"><h3 class="card-title">Nueva asignación de donación</h3></div>
        <div class="card-body">
            <form action="{{ route('asignaciones.asignar.store', $asignacion->asignacionid) }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label>Donación con saldo disponible <span class="text-danger">*</span></label>
                    <select name="donacionid" id="donacionid" class="form-control" required>
                        <option value="">-- Seleccione --</option>
                        @foreach($saldos as $s)
                            <option value="{{ $s->donacionid }}" data-saldo="{{ $s->saldodisponible }}">
                                Donación #{{ $s->donacionid }} — Saldo: Bs {{ number_format($s->saldodisponible,2) }}
                            </option>
                        @endforeach
                    </select>
                    @error('donacionid') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label>Monto a asignar <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="0.01" name="montoasignado" id="montoasignado"
                               class="form-control @error('montoasignado') is-invalid @enderror"
                               value="{{ old('montoasignado') }}" required>
                        <small class="text-muted" id="ayudaMonto">
                            El monto no debe superar el saldo de la donación ni el faltante de la asignación.
                        </small>
                        @error('montoasignado') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Saldo de la donación</label>
                        <input type="text" id="saldoSeleccionado" class="form-control" value="—" readonly>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label>Faltante de la asignación</label>
                        <input type="text" id="faltanteAsignacion" class="form-control"
                               value="Bs {{ number_format($faltante ?? 0,2) }}" readonly>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button class="btn btn-success"><i class="fas fa-check"></i> Asignar</button>
                    <a href="{{ route('asignaciones.detalles', $asignacion->asignacionid) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sel = document.getElementById('donacionid');
    const saldoTxt = document.getElementById('saldoSeleccionado');
    const ayuda = document.getElementById('ayudaMonto');
    const montoInput = document.getElementById('montoasignado');
    const faltante = {{ (float) ($faltante ?? 0) }};

    function refrescar() {
        const opt = sel.options[sel.selectedIndex];
        const saldo = parseFloat(opt?.getAttribute('data-saldo') || 0);
        saldoTxt.value = isNaN(saldo) ? '—' : ('Bs ' + saldo.toFixed(2));

        const maxPermitido = Math.max(0, Math.min(saldo, faltante));
        if (montoInput) {
            montoInput.max = maxPermitido || '';
            ayuda.textContent = 'Máximo permitido: Bs ' + (maxPermitido ? maxPermitido.toFixed(2) : '0.00');
        }
    }

    sel?.addEventListener('change', refrescar);
});
</script>
@endpush
