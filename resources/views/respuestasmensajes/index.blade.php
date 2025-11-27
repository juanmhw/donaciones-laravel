@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Respuestas a Mensajes</h2>

    <a href="{{ route('respuestasmensajes.create') }}" class="btn btn-primary mb-3">
        Nueva respuesta
    </a>

    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Mensaje</th>
                <th>Asunto</th>
                <th>Usuario</th>
                <th>Contenido</th>
                <th>Fecha</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($respuestas as $r)

                @php
                    $mensaje = $r->mensaje;
                    $usuario = $r->usuario;
                @endphp

                <tr>
                    <td>{{ $r->respuestaid }}</td>

                    {{-- ID del mensaje con link --}}
                    <td>
                        <a href="{{ route('mensajes.show', $mensaje->mensajeid) }}">
                            #{{ $mensaje->mensajeid }}
                        </a>
                    </td>

                    {{-- Asunto del mensaje --}}
                    <td>
                        <a href="{{ route('mensajes.show', $mensaje->mensajeid) }}">
                            {{ Str::limit($mensaje->asunto, 40) }}
                        </a>
                    </td>

                    {{-- Usuario que respondió --}}
                    <td>
                        {{ $usuario->email ?? '---' }}
                    </td>

                    {{-- Contenido abreviado --}}
                    <td>{{ Str::limit($r->contenido, 50) }}</td>

                    {{-- Fecha --}}
                    <td>{{ $r->fecharespuesta }}</td>

                    {{-- Acciones --}}
                    <td>
                        <a href="{{ route('respuestasmensajes.edit', $r->respuestaid) }}" 
                           class="btn btn-sm btn-warning">Editar</a>

                        <form action="{{ route('respuestasmensajes.destroy', $r->respuestaid) }}" 
                              method="POST" 
                              class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" 
                                    onclick="return confirm('¿Eliminar respuesta?')">
                                Eliminar
                            </button>
                        </form>
                    </td>
                </tr>

            @empty
                <tr>
                    <td colspan="7" class="text-center">Sin respuestas registradas</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $respuestas->links() }}
</div>
@endsection
