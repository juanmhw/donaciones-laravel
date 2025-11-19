{{-- create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Nueva respuesta</h2>

    <form action="{{ route('respuestasmensajes.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Mensaje</label>
            <select name="mensajeid" class="form-select" required>
                <option value="">— Seleccione —</option>
                @foreach($mensajes as $m)
                    <option value="{{ $m->mensajeid }}" @selected(old('mensajeid') == $m->mensajeid)>
                        #{{ $m->mensajeid }} - {{ $m->asunto }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Usuario</label>
            <select name="usuarioid" class="form-select" required>
                <option value="">— Seleccione —</option>
                @foreach($usuarios as $u)
                    <option value="{{ $u->usuarioid }}" @selected(old('usuarioid') == $u->usuarioid)>
                        {{ $u->email }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Contenido</label>
            <textarea name="contenido" rows="4" class="form-control" required>{{ old('contenido') }}</textarea>
        </div>

        <button class="btn btn-primary">Guardar</button>
        <a href="{{ route('respuestasmensajes.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
