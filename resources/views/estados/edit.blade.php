@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Editar Estado</h2>

    <form action="{{ route('estados.update', $estado->estadoid) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-3">
            <label>Nombre:</label>
            <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $estado->nombre) }}" required>
            @error('nombre') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
        <div class="mb-3">
            <label>Descripción:</label>
            <textarea name="descripcion" class="form-control">{{ old('descripcion', $estado->descripcion) }}</textarea>
            @error('descripcion') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
        <button class="btn btn-primary">Actualizar</button>
        <a href="{{ route('estados.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
