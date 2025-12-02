@extends('layouts.app')

@section('title', 'Editar mensaje')

@section('header')
    <h1>
        <i class="fas fa-edit icon-title"></i>
        Editar mensaje
    </h1>
    <p class="text-muted mb-0">
        Modifica el contenido o los datos del mensaje seleccionado.
    </p>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('mensajes.update', $mensaje->mensajeid) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Remitente</label>
                            <select name="remitenteid" class="form-control" required>
                                @foreach($usuarios as $u)
                                    <option value="{{ $u->usuarioid }}"
                                        {{ old('remitenteid',$mensaje->remitenteid) == $u->usuarioid ? 'selected' : '' }}>
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
                                    <option value="{{ $u->usuarioid }}"
                                        {{ old('destinatarioid',$mensaje->destinatarioid) == $u->usuarioid ? 'selected' : '' }}>
                                        {{ $u->nombre }} {{ $u->apellido }} — {{ $u->email }}
                                    </option>
                                @endforeach
                            </select>
                            @error('destinatarioid') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Asunto</label>
                            <input type="text" name="asunto" class="form-control"
                                   value="{{ old('asunto',$mensaje->asunto) }}" required>
                            @error('asunto') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="form-group">
                            <label>Fecha de envío</label>
                            <input type="datetime-local" name="fechaenvio" class="form-control"
                                   value="{{ old('fechaenvio', \Carbon\Carbon::parse($mensaje->fechaenvio)->format('Y-m-d\TH:i')) }}">
                            @error('fechaenvio') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="form-group form-row">
                            <div class="col-auto">
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" name="leido"
                                           value="1" {{ old('leido',$mensaje->leido) ? 'checked' : '' }}>
                                    <label class="form-check-label">Marcar como leído</label>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" name="respondido"
                                           value="1" {{ old('respondido',$mensaje->respondido) ? 'checked' : '' }}>
                                    <label class="form-check-label">Marcar como respondido</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group mt-3">
                    <label>Contenido</label>
                    <textarea name="contenido" rows="5" class="form-control">{{ old('contenido',$mensaje->contenido) }}</textarea>
                    @error('contenido') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('mensajes.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Volver
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> Guardar cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
