@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Editar Donación</h2>

    <form action="{{ route('donaciones.update', $donacion->donacionid) }}" method="POST">
        @csrf @method('PUT')

        <div class="mb-3">
            <label>Usuario (opcional):</label>
            <select name="usuarioid" class="form-select">
                <option value="">— Anónima —</option>
                @foreach($usuarios as $u)
                    <option value="{{ $u->usuarioid }}" @selected(old('usuarioid', $donacion->usuarioid) == $u->usuarioid)>{{ $u->email }}</option>
                @endforeach
            </select>
            @error('usuarioid') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label>Campaña:</label>
            <select name="campaniaid" class="form-select" required>
                @foreach($campanias as $c)
                    <option value="{{ $c->campaniaid }}" @selected(old('campaniaid', $donacion->campaniaid) == $c->campaniaid)>{{ $c->titulo }}</option>
                @endforeach
            </select>
            @error('campaniaid') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="row">
            <div class="mb-3 col-md-6">
                <label>Monto:</label>
                <input type="number" step="0.01" name="monto" class="form-control" value="{{ old('monto', $donacion->monto) }}" required>
                @error('monto') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
            <div class="mb-3 col-md-6">
                <label>Tipo de Donación:</label>
                <select name="tipodonacion" class="form-select" required>
                    <option value="monetaria" @selected(old('tipodonacion', $donacion->tipodonacion)=='monetaria')>Monetaria</option>
                    <option value="especie" @selected(old('tipodonacion', $donacion->tipodonacion)=='especie')>Especie</option>
                </select>
                @error('tipodonacion') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
        </div>

        <div class="mb-3">
            <label>Descripción:</label>
            <textarea name="descripcion" class="form-control">{{ old('descripcion', $donacion->descripcion) }}</textarea>
            @error('descripcion') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="row">
            <div class="mb-3 col-md-6">
                <label>Fecha Donación:</label>
                <input type="datetime-local" name="fechadonacion" class="form-control"
                       value="{{ old('fechadonacion', $donacion->fechadonacion) ? \Carbon\Carbon::parse(old('fechadonacion', $donacion->fechadonacion))->format('Y-m-d\TH:i') : '' }}">
                @error('fechadonacion') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
            <div class="mb-3 col-md-6">
                <label>Estado:</label>
                <select name="estadoid" class="form-select" required>
                    @foreach($estados as $e)
                        <option value="{{ $e->estadoid }}" @selected(old('estadoid', $donacion->estadoid) == $e->estadoid)>{{ $e->nombre }}</option>
                    @endforeach
                </select>
                @error('estadoid') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
        </div>

        <div class="form-check mt-2">
        <input class="form-check-input" type="checkbox" name="esanonima" id="esanonima"
                value="1" {{ old('esanonima', $donacion->esanonima ?? false) ? 'checked' : '' }}>
        <label class="form-check-label" for="esanonima">Donación anónima</label>
        </div>

        <button class="btn btn-primary">Actualizar</button>
        <a href="{{ route('donaciones.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
