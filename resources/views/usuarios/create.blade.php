@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Crear Nuevo Usuario</h2>

    <form action="{{ route('usuarios.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Email:</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
            @error('email') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label>Contraseña:</label>
            <input type="text" name="contrasena" class="form-control" value="{{ old('contrasena') }}" required>
            @error('contrasena') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="row">
            <div class="mb-3 col-md-6">
                <label>Nombre:</label>
                <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}" required>
                @error('nombre') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
            <div class="mb-3 col-md-6">
                <label>Apellido:</label>
                <input type="text" name="apellido" class="form-control" value="{{ old('apellido') }}" required>
                @error('apellido') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
        </div>

        <div class="mb-3">
            <label>Teléfono:</label>
            <input type="text" name="telefono" class="form-control" value="{{ old('telefono') }}">
            @error('telefono') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label>Imagen URL:</label>
            <input type="text" name="imagenurl" class="form-control" value="{{ old('imagenurl') }}">
            @error('imagenurl') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="row">
            <div class="mb-3 col-md-6 form-check mt-4">
                <input class="form-check-input" type="checkbox" name="activo" id="activo" value="1" {{ old('activo', true) ? 'checked' : '' }}>
                <label class="form-check-label" for="activo">Activo</label>
                @error('activo') <br><small class="text-danger">{{ $message }}</small> @enderror
            </div>
            <div class="mb-3 col-md-6">
                <label>Fecha Registro:</label>
                <input type="datetime-local" name="fecharegistro" class="form-control" value="{{ old('fecharegistro') }}">
                @error('fecharegistro') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
        </div>

        {{-- Opcional: asignar roles aquí --}}
        @if(isset($roles) && $roles->count())
        <div class="mb-3">
            <label>Roles (opcional):</label>
            <select name="roles[]" class="form-select" multiple>
                @foreach($roles as $rol)
                    <option value="{{ $rol->rolid }}" @selected(collect(old('roles', []))->contains($rol->rolid))>
                        {{ $rol->nombre }}
                    </option>
                @endforeach
            </select>
        </div>
        @endif

        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
