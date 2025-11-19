@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Editar Registro Donación → Asignación</h2>

    <form action="{{ route('donacionesasignaciones.update', $item->donacionasignacionid) }}" method="POST">
        @csrf @method('PUT')

        <div class="mb-3">
            <label>Donación:</label>
            <select name="donacionid" class="form-select" required>
                @foreach($donaciones as $d)
                    <option value="{{ $d->donacionid }}" @selected(old('donacionid', $item->donacionid) == $d->donacionid)>
                        {{ $d->donacionid }} — {{ optional($d->usuario)->email ?? 'Anón.' }} — ${{ $d->monto }}
                    </option>
                @endforeach
            </select>
            @error('donacionid') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label>Asignación:</label>
            <select name="asignacionid" class="form-select" required>
                @foreach($asignaciones as $a)
                    <option value="{{ $a->asignacionid }}" @selected(old('asignacionid', $item->asignacionid) == $a->asignacionid)>
                        {{ $a->asignacionid }} — {{ $a->descripcion }}
                    </option>
                @endforeach
            </select>
            @error('asignacionid') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label>Monto Asignado:</label>
            <input type="number" step="0.01" name="montoasignado" class="form-control" value="{{ old('montoasignado', $item->montoasignado) }}" required>
            @error('montoasignado') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label>Fecha Asignación:</label>
            <input type="datetime-local" name="fechaasignacion" class="form-control"
                   value="{{ old('fechaasignacion', $item->fechaasignacion) ? \Carbon\Carbon::parse(old('fechaasignacion', $item->fechaasignacion))->format('Y-m-d\TH:i') : '' }}">
            @error('fechaasignacion') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <button class="btn btn-primary">Actualizar</button>
        <a href="{{ route('donacionesasignaciones.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
