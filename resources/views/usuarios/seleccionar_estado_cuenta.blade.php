@extends('layouts.app')

@section('title', 'Seleccionar donante')

@section('content')
<div class="container mt-4">
    <h2>Estado de cuenta de donante</h2>
    <p class="text-muted">Selecciona el usuario para ver su estado de cuenta.</p>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('usuarios.estadoCuentaMostrar') }}" method="GET">
                <div class="form-group">
                    <label for="usuarioid">Usuario</label>
                    <select name="usuarioid" id="usuarioid" class="form-control" required>
                        <option value="">-- Seleccionar usuario --</option>
                        @foreach ($usuarios as $usuario)
                            <option value="{{ $usuario->usuarioid }}">
                                {{ $usuario->nombre }} {{ $usuario->apellido }} ({{ $usuario->email }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-primary mt-3">
                    Ver estado de cuenta
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
