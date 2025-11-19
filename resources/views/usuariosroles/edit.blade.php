@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Editar Asignación</h2>

    <form action="{{ route('usuariosroles.update', $usuariosrol->usuariorolid) }}" method="POST">
        @csrf @method('PUT')

        <div class="mb-3">
            <label>Usuario:</label>
            <select name="usuarioid" class="form-select" required>
                @foreach($usuarios as $u)
                    <option value="{{ $u->usuarioid }}" @selected(old('usuarioid', $usuariosrol->usuarioid) == $u->usuarioid)>{{ $u->email }}</option>
                @endforeach
            </select>
            @error('usuarioid') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label>Rol:</label>
            <select name="rolid" class="form-select" required>
                @foreach($roles as $r)
                    <option value="{{ $r->rolid }}" @selected(old('rolid', $usuariosrol->rolid) == $r->rolid)>{{ $r->nombre }}</option>
                @endforeach
            </select>
            @error('rolid') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label>Fecha Asignación:</label>
            <input type="datetime-local" name="fechaasignacion" class="form-control"
                   value="{{ old('fechaasignacion', $usuariosrol->fechaasignacion) ? \Carbon\Carbon::parse(old('fechaasignacion', $usuariosrol->fechaasignacion))->format('Y-m-d\TH:i') : '' }}">
            @error('fechaasignacion') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <button class="btn btn-primary">Actualizar</button>
        <a href="{{ route('usuariosroles.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
