<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
    <table>
        <thead>
            <tr>
                <td colspan="9" style="font-size: 20px; font-weight: bold; text-align: center; background-color: #343a40; color: #ffffff; vertical-align: middle; border: 2px solid #000000; height: 40px;">
                    REPORTE DE TRAZABILIDAD DE INVENTARIO
                </td>
            </tr>
            <tr>
                <td colspan="9" style="text-align: center; font-size: 12px; background-color: #f8f9fa; border-right: 2px solid #000000; border-left: 2px solid #000000;">
                    Campaña: <strong>{{ $campaniaNombre }}</strong>
                </td>
            </tr>
            <tr>
                <td colspan="9" style="text-align: center; font-size: 11px; background-color: #f8f9fa; border-right: 2px solid #000000; border-left: 2px solid #000000;">
                    Total Ítems Listados: {{ $totalItems }}
                </td>
            </tr>
            <tr>
                <td colspan="9" style="text-align: center; font-size: 10px; color: #555; background-color: #f8f9fa; border-right: 2px solid #000000; border-left: 2px solid #000000; border-bottom: 2px solid #000000;">
                    Generado el: {{ date('d/m/Y H:i') }} | Usuario: {{ auth()->user()->name ?? 'Sistema' }}
                </td>
            </tr>
        </thead>

        <tbody>
            @foreach($grupos as $almacenNombre => $items)
                
                <tr>
                    <td colspan="9">[[SEPARADOR]]</td>
                </tr>

                <tr>
                    <td colspan="9" style="background-color: #0056b3; color: #ffffff; font-weight: bold; font-size: 14px; border: 2px solid #000000; height: 30px; vertical-align: middle;">
                        🏢 ALMACÉN: {{ mb_strtoupper($almacenNombre) }} <span style="font-size: 11px; font-weight: normal;">({{ count($items) }} ítems)</span>
                    </td>
                </tr>

                <tr>
                    <th style="background-color: #e9ecef; border: 1px solid #000000; font-weight: bold; text-align: center;">CÓDIGO</th>
                    <th style="background-color: #e9ecef; border: 1px solid #000000; font-weight: bold; text-align: left;">PRODUCTO</th>
                    <th style="background-color: #e9ecef; border: 1px solid #000000; font-weight: bold; text-align: center;">CATEGORÍA</th>
                    <th style="background-color: #e9ecef; border: 1px solid #000000; font-weight: bold; text-align: center;">TALLA</th>
                    <th style="background-color: #e9ecef; border: 1px solid #000000; font-weight: bold; text-align: center;">UBICACIÓN</th>
                    <th style="background-color: #e9ecef; border: 1px solid #000000; font-weight: bold; text-align: center;">CANT.</th>
                    <th style="background-color: #e9ecef; border: 1px solid #000000; font-weight: bold; text-align: left;">DONANTE</th>
                    <th style="background-color: #e9ecef; border: 1px solid #000000; font-weight: bold; text-align: center;">ESTADO</th>
                    <th style="background-color: #e9ecef; border: 1px solid #000000; font-weight: bold; text-align: center;">INGRESO</th>
                </tr>

                @foreach($items as $item)
                    <tr>
                        <td style="border: 1px solid #cccccc; text-align: center;">{{ $item->codigo_unico }}</td>
                        <td style="border: 1px solid #cccccc;">{{ $item->nombre_producto }}</td>
                        <td style="border: 1px solid #cccccc; text-align: center;">{{ $item->categoria_producto }}</td>
                        <td style="border: 1px solid #cccccc; text-align: center;">{{ $item->talla ?? '-' }}</td>
                        
                        <td style="border: 1px solid #cccccc; text-align: center; background-color: #fdfdfe;">
                            @if($item->estante_codigo)
                                Est: {{ $item->estante_codigo }}<br>Esp: {{ $item->espacio_codigo }}
                            @else
                                -
                            @endif
                        </td>

                        <td style="border: 1px solid #cccccc; text-align: center;">{{ $item->cantidad_donada }} {{ $item->unidad_empaque }}</td>
                        <td style="border: 1px solid #cccccc;">{{ $item->nombre_donante }}</td>
                        
                        <td style="border: 1px solid #cccccc; text-align: center; font-weight: bold; color: {{ \Illuminate\Support\Str::contains(strtolower($item->estado_actual), ['entregado', 'salida']) ? '#28a745' : '#e0a800' }}">
                            {{ $item->estado_actual }}
                        </td>

                        <td style="border: 1px solid #cccccc; text-align: center;">{{ \Carbon\Carbon::parse($item->fecha_donacion)->format('d/m/Y') }}</td>
                    </tr>
                @endforeach

            @endforeach
            
            <tr>
                <td colspan="9">[[SEPARADOR]]</td>
            </tr>
        </tbody>
    </table>
</body>
</html>