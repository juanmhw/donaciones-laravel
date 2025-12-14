@extends('layouts.app')

@section('title', 'Nueva campaña')

@section('header')
    <h1>
        <i class="fas fa-bullhorn icon-title"></i>
        Nueva campaña
    </h1>
    <p class="text-muted mb-0">
        Crea una nueva campaña con su meta de recaudación y fechas.
    </p>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('campanias.store') }}" method="POST">
                @csrf

                <div class="row">
                    {{-- Columna izquierda --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Título <span class="text-danger">*</span></label>
                            <input type="text" name="titulo" class="form-control"
                                   value="{{ old('titulo') }}" required>
                            @error('titulo') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="form-group">
                            <label>Descripción <span class="text-danger">*</span></label>
                            <textarea name="descripcion" rows="4" class="form-control" required>{{ old('descripcion') }}</textarea>
                            @error('descripcion') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="form-group">
                            <label>Imagen (URL opcional)</label>
                            <input type="text" name="imagenurl" class="form-control"
                                   value="{{ old('imagenurl') }}" placeholder="https://...">
                            @error('imagenurl') <small class="text-danger">{{ $message }}</small> @enderror
                            <small class="form-text text-muted">
                                URL de una imagen representativa para la campaña.
                            </small>
                        </div>
                    </div>

                    {{-- Columna derecha --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Fecha de inicio <span class="text-danger">*</span></label>
                            <input type="date" name="fechainicio" class="form-control"
                                   value="{{ old('fechainicio') }}" required>
                            @error('fechainicio') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="form-group">
                            <label>Fecha de fin (opcional)</label>
                            <input type="date" name="fechafin" class="form-control"
                                   value="{{ old('fechafin') }}">
                            @error('fechafin') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="form-group">
                            <label>Meta de recaudación (Bs) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="metarecaudacion" class="form-control"
                                   value="{{ old('metarecaudacion') }}" required>
                            @error('metarecaudacion') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="form-group">
                            <label>Monto recaudado inicial (Bs)</label>
                            <input type="number" step="0.01" name="montorecaudado" class="form-control"
                                   value="{{ old('montorecaudado', 0) }}">
                            @error('montorecaudado') <small class="text-danger">{{ $message }}</small> @enderror
                            <small class="form-text text-muted">
                                Puedes dejarlo en 0 y se actualizará con las donaciones.
                            </small>
                        </div>

                        <div class="form-group">
                            <label>Fecha de creación (opcional)</label>
                            <input type="datetime-local" name="fechacreacion" class="form-control"
                                   value="{{ old('fechacreacion') }}">
                            @error('fechacreacion') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="form-group form-check mt-2">
                            <input class="form-check-input" type="checkbox" name="activa" value="1"
                                   id="activaCheck" {{ old('activa', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="activaCheck">
                                Campaña activa
                            </label>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-3">
                    <a href="{{ route('campanias.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Volver
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> Guardar campaña
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection