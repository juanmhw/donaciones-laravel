@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Nuevo mensaje</h2>

    <form action="{{ route('mensajes.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Remitente</label>
            <select name="remitenteid" class="form-select" required>
                <option value="">— Seleccione —</option>
                @foreach($usuarios as $u)
                    <option value="{{ $u->usuarioid }}" @selected(old('remitenteid') == $u->usuarioid)>
                        {{ $u->email }} — {{ $u->nombre }} {{ $u->apellido }}
                    </option>
                @endforeach
            </select>
            @error('remitenteid') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label>Destinatario (opcional)</label>
            <select name="destinatarioid" class="form-select">
                <option value="">— Ninguno —</option>
                @foreach($usuarios as $u)
                    <option value="{{ $u->usuarioid }}" @selected(old('destinatarioid') == $u->usuarioid)>
                        {{ $u->email }} — {{ $u->nombre }} {{ $u->apellido }}
                    </option>
                @endforeach
            </select>
            @error('destinatarioid') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label>Asunto</label>
            <input type="text" name="asunto" class="form-control" value="{{ old('asunto') }}" required>
            @error('asunto') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label>Contenido</label>
            <textarea name="contenido" class="form-control" rows="5" required>{{ old('contenido') }}</textarea>
            @error('contenido') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <button class="btn btn-primary">Guardar</button>
        <a href="{{ route('mensajes.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
