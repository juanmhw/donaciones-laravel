<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cierre de caja</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; }
        h2 { text-align:center; margin-bottom:15px; }
        table { width:100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { border:1px solid #333; padding:4px; vertical-align: top; }
        th { background:#eee; }
        ul { margin: 0 0 0 12px; padding: 0; }
    </style>
</head>
<body>

<h2>Cierre de caja – Reporte General</h2>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Campaña</th>
            <th>Donante</th>
            <th>Monto</th>
            <th>Tipo</th>
            <th>Estado</th>
            <th>Fecha</th>
            <th>Uso de la donación</th>
        </tr>
    </thead>

    <tbody>
    @foreach($donaciones as $d)
        <tr>
            <td>{{ $d->donacionid }}</td>
            <td>{{ $d->campania->titulo }}</td>
            <td>
                @if($d->esanonima)
                    Anónimo
                @else
                    {{ optional($d->usuario)->nombre }} {{ optional($d->usuario)->apellido }}
                @endif
            </td>
            <td>Bs {{ number_format($d->monto,2) }}</td>
            <td>{{ $d->tipodonacion }}</td>
            <td>{{ $d->estado->nombre }}</td>
            <td>{{ $d->fechadonacion }}</td>
            <td>
                @if($d->asignacionesPivot->count())
                <ul>
                    @foreach($d->asignacionesPivot as $pivot)
                    @php $asig = $pivot->asignacion; @endphp
                    <li>
                        <strong>Asig #{{ $asig->asignacionid }}:</strong>
                        {{ $asig->descripcion }} —
                        <strong>Bs {{ number_format($pivot->montoasignado,2) }}</strong>

                        @if($asig->detalles->count())
                            <br><em>Detalle:</em>
                            <ul>
                                @foreach($asig->detalles as $det)
                                <li>
                                    {{ $det->concepto }}
                                    ({{ $det->cantidad }} × Bs {{ number_format($det->preciounitario,2) }})
                                </li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                    @endforeach
                </ul>
                @else
                    No utilizada / sin asignaciones
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

</body>
</html>
