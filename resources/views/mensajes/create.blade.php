@extends('layouts.app')

@section('title', 'Nuevo mensaje')

@section('header')
    <h1>
        <i class="fas fa-paper-plane icon-title"></i>
        Nuevo mensaje
    </h1>
    <p class="text-muted mb-0">
        Redacta un nuevo mensaje para cualquier usuario del sistema.
    </p>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('mensajes.store') }}" method="POST">
                @csrf

                <div class="row">
                    {{-- Columna izquierda: remitente/destinatario --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Remitente</label>
                            <select name="remitenteid" class="form-control" required>
                                <option value="">Seleccione un remitente</option>
                                @foreach($usuarios as $u)
                                    <option value="{{ $u->usuarioid }}" {{ old('remitenteid') == $u->usuarioid ? 'selected' : '' }}>
                                        {{ $u->nombre }} {{ $u->apellido }} — {{ $u->email }}
                                    </option>
                                @endforeach
                            </select>
                            @error('remitenteid') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="form-group">
                            <label>Destinatario (opcional)</label>
                            <select name="destinatarioid" class="form-control">
                                <option value="">Sin destinatario específico</option>
                                @foreach($usuarios as $u)
                                    <option value="{{ $u->usuarioid }}" {{ old('destinatarioid') == $u->usuarioid ? 'selected' : '' }}>
                                        {{ $u->nombre }} {{ $u->apellido }} — {{ $u->email }}
                                    </option>
                                @endforeach
                            </select>
                            @error('destinatarioid') <small class="text-danger">{{ $message }}</small> @enderror
                            <small class="form-text text-muted">
                                Si lo dejas vacío, puedes usar el mensaje para anuncios generales.
                            </small>
                        </div>
                    </div>

                    {{-- Columna derecha: asunto + flags --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Asunto</label>
                            <input type="text" name="asunto" class="form-control"
                                   value="{{ old('asunto') }}" required>
                            @error('asunto') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="form-group">
                            <label>Fecha de envío</label>
                            <input type="datetime-local" name="fechaenvio" class="form-control"
                                   value="{{ old('fechaenvio') }}">
                            @error('fechaenvio') <small class="text-danger">{{ $message }}</small> @enderror
                            <small class="form-text text-muted">
                                Si lo dejas vacío, se tomará la fecha y hora actual.
                            </small>
                        </div>

                        <div class="form-group form-row">
                            <div class="col-auto">
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" name="leido"
                                           value="1" {{ old('leido') ? 'checked' : '' }}>
                                    <label class="form-check-label">Marcar como leído</label>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" name="respondido"
                                           value="1" {{ old('respondido') ? 'checked' : '' }}>
                                    <label class="form-check-label">Marcar como respondido</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Contenido tipo chat --}}
                <div class="form-group mt-3">
                    <label>Contenido</label>
                    <textarea name="contenido" rows="5" class="form-control"
                              placeholder="Escribe aquí el mensaje...">{{ old('contenido') }}</textarea>
                    @error('contenido') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('mensajes.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Volver
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane mr-1"></i> Enviar mensaje
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
