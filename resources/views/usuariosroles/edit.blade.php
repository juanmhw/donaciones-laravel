@extends('layouts.app')

@section('title', 'Editar asignación usuario–rol')

@section('header')
    <h1>
        <i class="fas fa-user-edit icon-title"></i>
        Editar asignación usuario–rol
    </h1>
    <p class="text-muted mb-0">
        Modifica el usuario, rol o fecha de esta asignación.
    </p>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('usuariosroles.update', $usuariosrol->usuariorolid) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Usuario</label>
                    <select name="usuarioid" class="form-control" required>
                        @foreach($usuarios as $u)
                            <option value="{{ $u->usuarioid }}"
                                {{ old('usuarioid', $usuariosrol->usuarioid) == $u->usuarioid ? 'selected' : '' }}>
                                {{ $u->nombre }} {{ $u->apellido }} — {{ $u->email }}
                            </option>
                        @endforeach
                    </select>
                    @error('usuarioid') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="form-group">
                    <label>Rol</label>
                    <select name="rolid" class="form-control" required>
                        @foreach($roles as $r)
                            <option value="{{ $r->rolid }}"
                                {{ old('rolid', $usuariosrol->rolid) == $r->rolid ? 'selected' : '' }}>
                                {{ $r->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('rolid') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="form-group">
                    <label>Fecha de asignación (opcional)</label>
                    <input type="date" name="fechaasignacion" class="form-control"
                           value="{{ old('fechaasignacion', $usuariosrol->fechaasignacion) }}">
                    @error('fechaasignacion') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="d-flex justify-content-between mt-3">
                    <a href="{{ route('usuariosroles.index') }}" class="btn btn-secondary">
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
