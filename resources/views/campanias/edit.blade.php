@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Editar Campaña</h2>

    <form action="{{ route('campanias.update', $campania->campaniaid) }}" method="POST">
        @csrf @method('PUT')

        <div class="mb-3">
            <label>Título:</label>
            <input type="text" name="titulo" class="form-control" value="{{ old('titulo', $campania->titulo) }}" required>
            @error('titulo') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label>Descripción:</label>
            <textarea name="descripcion" class="form-control" required>{{ old('descripcion', $campania->descripcion) }}</textarea>
            @error('descripcion') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="row">
            <div class="mb-3 col-md-6">
                <label>Fecha Inicio:</label>
                <input type="date" name="fechainicio" class="form-control" value="{{ old('fechainicio', $campania->fechainicio) }}" required>
                @error('fechainicio') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
            <div class="mb-3 col-md-6">
                <label>Fecha Fin:</label>
                <input type="date" name="fechafin" class="form-control" value="{{ old('fechafin', $campania->fechafin) }}">
                @error('fechafin') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
        </div>

        <div class="row">
            <div class="mb-3 col-md-6">
                <label>Meta Recaudación:</label>
                <input type="number" step="0.01" name="metarecaudacion" class="form-control" value="{{ old('metarecaudacion', $campania->metarecaudacion) }}" required>
                @error('metarecaudacion') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
            <div class="mb-3 col-md-6">
                <label>Monto Recaudado:</label>
                <input type="number" step="0.01" name="montorecaudado" class="form-control" value="{{ old('montorecaudado', $campania->montorecaudado) }}">
                @error('montorecaudado') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
        </div>

        <div class="mb-3">
            <label>Creador (Usuario):</label>
            <select name="usuarioidcreador" class="form-select" required>
                @foreach($usuarios as $u)
                    <option value="{{ $u->usuarioid }}" @selected(old('usuarioidcreador', $campania->usuarioidcreador) == $u->usuarioid)>{{ $u->email }}</option>
                @endforeach
            </select>
            @error('usuarioidcreador') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="row">
            <div class="mb-3 col-md-6 form-check mt-4">
                <input type="checkbox" class="form-check-input" name="activa" id="activa" value="1" {{ old('activa', $campania->activa) ? 'checked' : '' }}>
                <label class="form-check-label" for="activa">Activa</label>
            </div>
            <div class="mb-3 col-md-6">
                <label>Imagen URL:</label>
                <input type="text" name="imagenurl" class="form-control" value="{{ old('imagenurl', $campania->imagenurl) }}">
                @error('imagenurl') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
        </div>

        <div class="mb-3">
            <label>Fecha Creación:</label>
            <input type="datetime-local" name="fechacreacion" class="form-control"
                   value="{{ old('fechacreacion', $campania->fechacreacion) ? \Carbon\Carbon::parse(old('fechacreacion', $campania->fechacreacion))->format('Y-m-d\TH:i') : '' }}">
            @error('fechacreacion') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <button class="btn btn-primary">Actualizar</button>
        <a href="{{ route('campanias.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
