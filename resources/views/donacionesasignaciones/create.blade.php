@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Nuevo Registro Donación → Asignación</h2>

    <form action="{{ route('donacionesasignaciones.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Donación:</label>
            <select name="donacionid" class="form-select" required>
                <option value="">-- Seleccione --</option>
                @foreach($donaciones as $d)
                    <option value="{{ $d->donacionid }}" @selected(old('donacionid') == $d->donacionid)>
                        {{ $d->donacionid }} — {{ optional($d->usuario)->email ?? 'Anón.' }} — ${{ $d->monto }}
                    </option>
                @endforeach
            </select>
            @error('donacionid') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label>Asignación:</label>
            <select name="asignacionid" class="form-select" required>
                <option value="">-- Seleccione --</option>
                @foreach($asignaciones as $a)
                    <option value="{{ $a->asignacionid }}" @selected(old('asignacionid') == $a->asignacionid)>
                        {{ $a->asignacionid }} — {{ $a->descripcion }}
                    </option>
                @endforeach
            </select>
            @error('asignacionid') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label>Monto Asignado:</label>
            <input type="number" step="0.01" name="montoasignado" class="form-control" value="{{ old('montoasignado') }}" required>
            @error('montoasignado') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label>Fecha Asignación:</label>
            <input type="datetime-local" name="fechaasignacion" class="form-control" value="{{ old('fechaasignacion') }}">
            @error('fechaasignacion') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <button class="btn btn-success">Guardar</button>
        <a href="{{ route('donacionesasignaciones.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
