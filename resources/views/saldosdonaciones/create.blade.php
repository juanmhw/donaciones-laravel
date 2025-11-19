@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Nuevo Saldo</h2>

    <form action="{{ route('saldosdonaciones.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Donación:</label>
            <select name="donacionid" class="form-select" required>
                <option value="">-- Seleccione --</option>
                @foreach($donaciones as $d)
                    <option value="{{ $d->donacionid }}" @selected(old('donacionid') == $d->donacionid)>{{ $d->donacionid }}</option>
                @endforeach
            </select>
            @error('donacionid') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="row">
            <div class="mb-3 col-md-4">
                <label>Monto Original:</label>
                <input type="number" step="0.01" name="montooriginal" class="form-control" value="{{ old('montooriginal') }}" required>
                @error('montooriginal') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
            <div class="mb-3 col-md-4">
                <label>Monto Utilizado:</label>
                <input type="number" step="0.01" name="montoutilizado" class="form-control" value="{{ old('montoutilizado') }}">
                @error('montoutilizado') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
            <div class="mb-3 col-md-4">
                <label>Saldo Disponible:</label>
                <input type="number" step="0.01" name="saldodisponible" class="form-control" value="{{ old('saldodisponible') }}" required>
                @error('saldodisponible') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
        </div>

        <div class="mb-3">
            <label>Última Actualización:</label>
            <input type="datetime-local" name="ultimaactualizacion" class="form-control" value="{{ old('ultimaactualizacion') }}">
            @error('ultimaactualizacion') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <button class="btn btn-success">Guardar</button>
        <a href="{{ route('saldosdonaciones.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
