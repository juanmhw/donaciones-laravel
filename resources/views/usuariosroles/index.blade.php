@extends('layouts.app')

@section('title', 'Usuarios – Roles')

@section('header')
    <h1>
        <i class="fas fa-user-shield icon-title"></i>
        Usuarios – Roles
    </h1>
    <p class="text-muted mb-0">
        Asignaciones entre usuarios y roles del sistema.
    </p>
@endsection

@section('content')
    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle mr-1"></i>
            {{ session('success') }}
        </div>
    @endif

    @php
        $totalAsignaciones = $usuariosroles->count();
        $usuariosUnicos    = $usuariosroles->pluck('usuarioid')->unique()->count();
    @endphp

    {{-- Resumen --}}
    <div class="saldo-summary-grid mb-3">
        <div class="saldo-summary-card total-original">
            <h5>TOTAL ASIGNACIONES</h5>
            <div class="saldo-value">{{ $totalAsignaciones }}</div>
            <small class="text-muted">Número de relaciones usuario–rol.</small>
        </div>
        <div class="saldo-summary-card total-utilizado">
            <h5>USUARIOS CON ROL</h5>
            <div class="saldo-value">{{ $usuariosUnicos }}</div>
            <small class="text-muted">Usuarios que tienen al menos un rol asignado.</small>
        </div>
    </div>

    {{-- Tabla --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">
                <i class="fas fa-list mr-1"></i> Asignaciones usuario–rol
            </h3>
            <a href="{{ route('usuariosroles.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle mr-1"></i> Nueva asignación
            </a>
        </div>

        <div class="card-body table-responsive p-0">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Fecha de asignación</th>
                        <th class="text-right" style="width: 150px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($usuariosroles as $ur)
                        @php
                            $u = optional($ur->usuario);
                            $r = optional($ur->rol);
                        @endphp
                        <tr>
                            <td>{{ $ur->usuariorolid }}</td>
                            <td>
                                {{ $u->nombre }} {{ $u->apellido }}<br>
                                @if(!$u)
                                    <small class="text-danger">Usuario no encontrado</small>
                                @endif
                            </td>
                            <td>{{ $u->email ?? '—' }}</td>
                            <td>
                                @if($r)
                                    <span class="role-chip">{{ $r->nombre }}</span><br>
                                    <small class="text-muted">
                                        {{ \Illuminate\Support\Str::limit($r->descripcion, 40) }}
                                    </small>
                                @else
                                    <span class="text-danger">Rol no encontrado</span>
                                @endif
                            </td>
                            <td>{{ $ur->fechaasignacion ?? '—' }}</td>
                            <td class="text-right">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('usuariosroles.edit', $ur->usuariorolid) }}"
                                       class="btn btn-outline-primary" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('usuariosroles.destroy', $ur->usuariorolid) }}"
                                          method="POST"
                                          onsubmit="return confirm('¿Eliminar esta asignación?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-outline-danger" title="Eliminar">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                No hay asignaciones registradas aún.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
