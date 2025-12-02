@extends('layouts.app')

@section('title', 'Editar rol')

@section('header')
    <h1>
        <i class="fas fa-edit icon-title"></i>
        Editar rol
    </h1>
    <p class="text-muted mb-0">
        Modifica el nombre o la descripción del rol seleccionado.
    </p>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('roles.update', $role->rolid) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Nombre del rol</label>
                    <input type="text" name="nombre" class="form-control"
                           value="{{ old('nombre', $role->nombre) }}" required>
                    @error('nombre') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="form-group">
                    <label>Descripción</label>
                    <textarea name="descripcion" rows="3" class="form-control">{{ old('descripcion', $role->descripcion) }}</textarea>
                    @error('descripcion') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="d-flex justify-content-between mt-3">
                    <a href="{{ route('roles.index') }}" class="btn btn-secondary">
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
