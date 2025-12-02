@extends('layouts.app')

@section('title', 'Nueva asignación usuario–rol')

@section('header')
    <h1>
        <i class="fas fa-user-shield icon-title"></i>
        Nueva asignación usuario–rol
    </h1>
    <p class="text-muted mb-0">
        Asigna un rol a un usuario del sistema.
    </p>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('usuariosroles.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label>Usuario</label>
                    <select name="usuarioid" class="form-control" required>
                        <option value="">Seleccione un usuario...</option>
                        @foreach($usuarios as $u)
                            <option value="{{ $u->usuarioid }}"
                                {{ old('usuarioid') == $u->usuarioid ? 'selected' : '' }}>
                                {{ $u->nombre }} {{ $u->apellido }} — {{ $u->email }}
                            </option>
                        @endforeach
                    </select>
                    @error('usuarioid') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="form-group">
                    <label>Rol</label>
                    <select name="rolid" class="form-control" required>
                        <option value="">Seleccione un rol...</option>
                        @foreach($roles as $r)
                            <option value="{{ $r->rolid }}"
                                {{ old('rolid') == $r->rolid ? 'selected' : '' }}>
                                {{ $r->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('rolid') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="form-group">
                    <label>Fecha de asignación (opcional)</label>
                    <input type="date" name="fechaasignacion" class="form-control"
                           value="{{ old('fechaasignacion') }}">
                    @error('fechaasignacion') <small class="text-danger">{{ $message }}</small> @enderror
                    <small class="form-text text-muted">
                        Si lo dejas vacío, puedes considerar que es la fecha actual.
                    </small>
                </div>

                <div class="d-flex justify-content-between mt-3">
                    <a href="{{ route('usuariosroles.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Volver
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> Guardar asignación
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
