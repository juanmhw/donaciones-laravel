@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Reasignar Donación #{{ $donacion->donacionid }}</h2>

    <form action="{{ route('donaciones.reasignar', $donacion->donacionid) }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Monto de la Donación</label>
            <input type="text" class="form-control" value="{{ $donacion->monto }}" disabled>
        </div>

        <div class="mb-3">
            <label>Campaña Actual</label>
            <input type="text" class="form-control"
                   value="{{ $donacion->campania->titulo }}" disabled>
        </div>

        <div class="mb-3">
            <label>Nueva Campaña</label>
            <select name="campaniaid" class="form-select" required>
                <option value="">Seleccione campaña</option>
                @foreach($campanias as $c)
                    <option value="{{ $c->campaniaid }}">
                        {{ $c->titulo }}
                    </option>
                @endforeach
            </select>
            @error('campaniaid') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <button class="btn btn-primary">Reasignar</button>
        <a href="{{ route('donaciones.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
