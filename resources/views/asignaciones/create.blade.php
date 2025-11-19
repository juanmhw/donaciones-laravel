@extends('layouts.app')

@section('title', 'Nueva Asignación')

@section('header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Nueva Asignación</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                <li class="breadcrumb-item"><a href="{{ route('asignaciones.index') }}">Asignaciones</a></li>
                <li class="breadcrumb-item active">Nueva</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Formulario de Nueva Asignación</h3>
                </div>

                <form action="{{ route('asignaciones.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        
                        {{-- Campaña --}}
                        <div class="form-group">
                            <label for="campaniaid">
                                Campaña <span class="text-danger">*</span>
                            </label>
                            <select name="campaniaid" id="campaniaid" class="form-control @error('campaniaid') is-invalid @enderror" required>
                                <option value="">-- Seleccione una campaña --</option>
                                @foreach($campanias as $c)
                                    <option value="{{ $c->campaniaid }}" @selected(old('campaniaid') == $c->campaniaid)>
                                        {{ $c->titulo }}
                                    </option>
                                @endforeach
                            </select>
                            @error('campaniaid')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Descripción --}}
                        <div class="form-group">
                            <label for="descripcion">
                                Descripción <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="descripcion" 
                                   id="descripcion" 
                                   class="form-control @error('descripcion') is-invalid @enderror" 
                                   value="{{ old('descripcion') }}" 
                                   placeholder="Ingrese la descripción de la asignación"
                                   required>
                            @error('descripcion')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Monto y Fecha --}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="monto">
                                        Monto <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Bs</span>
                                        </div>
                                        <input type="number" 
                                               step="0.01" 
                                               name="monto" 
                                               id="monto" 
                                               class="form-control @error('monto') is-invalid @enderror" 
                                               value="{{ old('monto') }}" 
                                               placeholder="0.00"
                                               required>
                                        @error('monto')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fechaasignacion">Fecha de Asignación</label>
                                    <input type="datetime-local" 
                                           name="fechaasignacion" 
                                           id="fechaasignacion" 
                                           class="form-control @error('fechaasignacion') is-invalid @enderror" 
                                           value="{{ old('fechaasignacion') }}">
                                    @error('fechaasignacion')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Imagen URL y Usuario --}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="imagenurl">URL de Imagen</label>
                                    <input type="url" 
                                           name="imagenurl" 
                                           id="imagenurl" 
                                           class="form-control @error('imagenurl') is-invalid @enderror" 
                                           value="{{ old('imagenurl') }}"
                                           placeholder="https://ejemplo.com/imagen.jpg">
                                    @error('imagenurl')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="usuarioid">
                                        Usuario Responsable <span class="text-danger">*</span>
                                    </label>
                                    <select name="usuarioid" id="usuarioid" class="form-control @error('usuarioid') is-invalid @enderror" required>
                                        <option value="">-- Seleccione un usuario --</option>
                                        @foreach($usuarios as $u)
                                            <option value="{{ $u->usuarioid }}" @selected(old('usuarioid') == $u->usuarioid)>
                                                {{ $u->email }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('usuarioid')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Comprobante --}}
                        <div class="form-group">
                            <label for="comprobante">Comprobante</label>
                            <input type="text" 
                                   name="comprobante" 
                                   id="comprobante" 
                                   class="form-control @error('comprobante') is-invalid @enderror" 
                                   value="{{ old('comprobante') }}"
                                   placeholder="Número o referencia del comprobante">
                            @error('comprobante')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Guardar Asignación
                        </button>
                        <a href="{{ route('asignaciones.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection