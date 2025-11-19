@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Editar Saldo</h2>

    <form action="{{ route('saldosdonaciones.update', $saldo->saldoid) }}" method="POST">
        @csrf @method('PUT')

        <div class="mb-3">
            <label>Donación:</label>
            <select name="donacionid" class="form-select" required>
                @foreach($donaciones as $d)
                    <option value="{{ $d->donacionid }}" @selected(old('donacionid', $saldo->donacionid) == $d->donacionid)>{{ $d->donacionid }}</option>
                @endforeach
            </select>
            @error('donacionid') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="row">
            <div class="mb-3 col-md-4">
                <label>Monto Original:</label>
                <input type="number" step="0.01" name="montooriginal" class="form-control" value="{{ old('montooriginal', $saldo->montooriginal) }}" required>
                @error('montooriginal') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
            <div class="mb-3 col-md-4">
                <label>Monto Utilizado:</label>
                <input type="number" step="0.01" name="montoutilizado" class="form-control" value="{{ old('montoutilizado', $saldo->montoutilizado) }}">
                @error('montoutilizado') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
            <div class="mb-3 col-md-4">
                <label>Saldo Disponible:</label>
                <input type="number" step="0.01" name="saldodisponible" class="form-control" value="{{ old('saldodisponible', $saldo->saldodisponible) }}" required>
                @error('saldodisponible') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
        </div>

        <div class="mb-3">
            <label>Última Actualización:</label>
            <input type="datetime-local" name="ultimaactualizacion" class="form-control"
                   value="{{ old('ultimaactualizacion', $saldo->ultimaactualizacion) ? \Carbon\Carbon::parse(old('ultimaactualizacion', $saldo->ultimaactualizacion))->format('Y-m-d\TH:i') : '' }}">
            @error('ultimaactualizacion') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <button class="btn btn-primary">Actualizar</button>
        <a href="{{ route('saldosdonaciones.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
