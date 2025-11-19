@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Nueva Asignación Usuario–Rol</h2>

    <form action="{{ route('usuariosroles.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Usuario:</label>
            <select name="usuarioid" class="form-select" required>
                <option value="">-- Seleccione --</option>
                @foreach($usuarios as $u)
                    <option value="{{ $u->usuarioid }}" @selected(old('usuarioid') == $u->usuarioid)>{{ $u->email }}</option>
                @endforeach
            </select>
            @error('usuarioid') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label>Rol:</label>
            <select name="rolid" class="form-select" required>
                <option value="">-- Seleccione --</option>
                @foreach($roles as $r)
                    <option value="{{ $r->rolid }}" @selected(old('rolid') == $r->rolid)>{{ $r->nombre }}</option>
                @endforeach
            </select>
            @error('rolid') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label>Fecha Asignación:</label>
            <input type="datetime-local" name="fechaasignacion" class="form-control" value="{{ old('fechaasignacion') }}">
            @error('fechaasignacion') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <button class="btn btn-success">Guardar</button>
        <a href="{{ route('usuariosroles.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
