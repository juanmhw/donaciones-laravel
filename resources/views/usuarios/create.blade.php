@extends('layouts.app')

@section('title', 'Nuevo usuario')

@section('header')
    <h1>
        <i class="fas fa-user-plus icon-title"></i>
        Crear nuevo usuario
    </h1>
    <p class="text-muted mb-0">
        Registra un nuevo usuario y asigna sus roles.
    </p>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('usuarios.store') }}" method="POST">
                @csrf

                <div class="row">
                    {{-- Columna izquierda --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control"
                                   value="{{ old('email') }}" required>
                            @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="form-group">
                            <label>Contraseña</label>
                            <input type="text" name="contrasena" class="form-control"
                                   value="{{ old('contrasena') }}" required>
                            @error('contrasena') <small class="text-danger">{{ $message }}</small> @enderror
                            <small class="form-text text-muted">
                                (Se guarda tal como lo ingreses; si luego usas hashing, aquí se ajusta el backend).
                            </small>
                        </div>

                        <div class="form-group">
                            <label>Nombre</label>
                            <input type="text" name="nombre" class="form-control"
                                   value="{{ old('nombre') }}" required>
                            @error('nombre') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="form-group">
                            <label>Apellido</label>
                            <input type="text" name="apellido" class="form-control"
                                   value="{{ old('apellido') }}" required>
                            @error('apellido') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="form-group">
                            <label>Teléfono (opcional)</label>
                            <input type="text" name="telefono" class="form-control"
                                   value="{{ old('telefono') }}">
                            @error('telefono') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    </div>

                    {{-- Columna derecha --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>URL de imagen (opcional)</label>
                            <input type="text" name="imagenurl" class="form-control"
                                   value="{{ old('imagenurl') }}">
                            @error('imagenurl') <small class="text-danger">{{ $message }}</small> @enderror
                            <small class="form-text text-muted">
                                Puedes guardar aquí un enlace a la foto del usuario.
                            </small>
                        </div>

                        <div class="form-group">
                            <label>Fecha de registro (opcional)</label>
                            <input type="datetime-local" name="fecharegistro" class="form-control"
                                   value="{{ old('fecharegistro') }}">
                            @error('fecharegistro') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="form-group form-check mt-2">
                            <input class="form-check-input" type="checkbox" name="activo" value="1"
                                   id="activoCheck" {{ old('activo', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="activoCheck">
                                Usuario activo
                            </label>
                        </div>

                        <div class="form-group mt-3">
                            <label>Roles</label>
                            <div class="border rounded p-2" style="max-height: 180px; overflow-y: auto;">
                                @foreach($roles as $rol)
                                    <div class="form-check">
                                        <input class="form-check-input"
                                               type="checkbox"
                                               name="roles[]"
                                               value="{{ $rol->rolid }}"
                                               id="rol_{{ $rol->rolid }}"
                                               {{ (collect(old('roles'))->contains($rol->rolid)) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="rol_{{ $rol->rolid }}">
                                            {{ $rol->nombre }}
                                            @if($rol->descripcion)
                                                <small class="text-muted">— {{ $rol->descripcion }}</small>
                                            @endif
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('roles') <small class="text-danger d-block">{{ $message }}</small> @enderror
                            <small class="form-text text-muted">
                                Puedes asignar uno o varios roles al usuario.
                            </small>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-3">
                    <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Volver
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> Guardar usuario
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
