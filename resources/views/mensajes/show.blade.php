@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Detalle del mensaje #{{ $mensaje->mensajeid }}</h2>

    <dl class="row">
        <dt class="col-sm-3">Remitente</dt>
        <dd class="col-sm-9">{{ optional($mensaje->remitente)->email }}</dd>

        <dt class="col-sm-3">Destinatario</dt>
        <dd class="col-sm-9">{{ optional($mensaje->destinatario)->email ?? '—' }}</dd>

        <dt class="col-sm-3">Asunto</dt>
        <dd class="col-sm-9">{{ $mensaje->asunto }}</dd>

        <dt class="col-sm-3">Contenido</dt>
        <dd class="col-sm-9">{{ $mensaje->contenido }}</dd>

        <dt class="col-sm-3">Fecha envío</dt>
        <dd class="col-sm-9">{{ $mensaje->fechaenvio }}</dd>
    </dl>

    <hr>

    <h4>Respuestas</h4>
    @forelse($mensaje->respuestas as $r)
        <div class="border rounded p-2 mb-2">
            <strong>{{ optional($r->usuario)->email }}</strong>
            <small class="text-muted">{{ $r->fecharespuesta }}</small>
            <p class="mb-0">{{ $r->contenido }}</p>
        </div>
    @empty
        <p>No hay respuestas.</p>
    @endforelse

    <a href="{{ route('respuestasmensajes.create') }}" class="btn btn-sm btn-primary mt-2">
        Nueva respuesta
    </a>

    <a href="{{ route('mensajes.index') }}" class="btn btn-secondary mt-2">Volver</a>
</div>
@endsection
