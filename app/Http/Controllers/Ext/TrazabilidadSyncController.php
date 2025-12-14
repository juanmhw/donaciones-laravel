<?php

namespace App\Http\Controllers\Ext;

use App\Http\Controllers\Controller;
use App\Models\Campania;
use App\Models\TrazabilidadItem;
use App\Models\Ext\ExtProducto;
use App\Models\Ext\ExtAlmacen;
use App\Models\Ext\ExtEstante;
use App\Models\Ext\ExtEspacio;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class TrazabilidadSyncController extends Controller
{
    protected string $baseUrl;

    public function __construct()
    {
        // Usa lo que ya tienes configurado
        $this->baseUrl = config('services.externos.base_url', 'http://localhost:8000');
    }

    /**
     * Sincroniza donaciones en especie y llena trazabilidad_items
     */
    public function syncEspecie()
    {
        // 1. Traer todas las donaciones en especie
        $resp = Http::get("{$this->baseUrl}/api/donaciones/especie");

        if ($resp->failed()) {
            return back()->withErrors('No se pudo conectar a /api/donaciones/especie');
        }

        $donaciones = $resp->json();

        DB::transaction(function () use ($donaciones) {

            foreach ($donaciones as $don) {

                $idDonacionExt = $don['id_donacion'];

                // Mapa rápido de detalles de la lista (para obtener id_detalle, cantidad, etc.)
                $detallesLista = [];
                foreach ($don['detalles'] ?? [] as $det) {
                    // clave por id_producto (en tu ejemplo no hay duplicados)
                    $detallesLista[$det['id_producto']] = $det;
                }

                // Buscar campaña local por idexterno (si existe)
                $campaniaLocal = null;
                if (!empty($don['id_campana'])) {
                    $campaniaLocal = Campania::where('idexterno', $don['id_campana'])->first();
                }

                // Donante (solo nombre para reporte)
                $nombreDonante = $don['donante']['nombre'] ?? null;

                // 2. Traer detalle con ubicaciones/paquetes
                $detalleResp = Http::get("{$this->baseUrl}/api/donaciones/especie/{$idDonacionExt}/detalle");

                if ($detalleResp->failed()) {
                    // si falla, saltamos esta donación
                    continue;
                }

                $detalleJson = $detalleResp->json();
                $data = $detalleJson['data'] ?? null;
                if (!$data || empty($data['detalles'])) {
                    continue;
                }

                foreach ($data['detalles'] as $detDetalle) {

                    $productoExt = $detDetalle['producto'] ?? null;
                    if (!$productoExt) {
                        continue;
                    }

                    $idProductoExt = $productoExt['id_producto'];

                    // detalle “base” de la donación (id_detalle, cantidad, unidad, codigo_unico…)
                    $detalleBase = $detallesLista[$idProductoExt] ?? null;

                    $idDetalleExt = $detalleBase['id_detalle'] ?? null;
                    $cantidadDonada = $detalleBase['cantidad'] ?? $detDetalle['cantidad'] ?? null;
                    $unidadMedida   = $detalleBase['unidad_medida'] ?? $detDetalle['unidad_medida'] ?? null;
                    $codigoUnico    = $detalleBase['codigo_unico'] ?? null;

                    // si no hay codigo_unico aún, generamos uno simple
                    if (!$codigoUnico) {
                        $codigoUnico = "DON{$idDonacionExt}-DET" . ($idDetalleExt ?? $idProductoExt);
                    }

                    // Producto local (ext_productos) si existe
                    $productoLocal = ExtProducto::where('idexterno', $idProductoExt)->first();

                    // 3. Ubicaciones de ese detalle
                    $ubicaciones = $detDetalle['ubicaciones'] ?? [];

                    // Si no hay ubicaciones, igual registramos una fila sin almacén
                    if (empty($ubicaciones)) {
                        TrazabilidadItem::updateOrCreate(
                            [
                                'id_donacion_externa' => $idDonacionExt,
                                'id_detalle_externo'  => $idDetalleExt ?? $idProductoExt,
                            ],
                            [
                                'campaniaid'         => $campaniaLocal?->campaniaid,
                                'id_campana_externa' => $don['id_campana'] ?? null,
                                'campania_nombre'    => $don['campana']['nombre'] ?? null,

                                'codigo_unico'       => $codigoUnico,
                                'productoid'         => $productoLocal?->productoid,
                                'nombre_producto'    => $productoExt['nombre'] ?? null,
                                'categoria_producto' => $productoLocal?->categoria->nombre ?? null,
                                'cantidad_donada'    => $cantidadDonada,
                                'cantidad_por_unidad'=> $detalleBase['cantidad_por_unidad'] ?? null,
                                'unidad_empaque'     => $unidadMedida,

                                'fecha_donacion'     => $don['fecha'] ?? null,
                                'tipo_donacion'      => $don['tipo'] ?? 'especie',
                                'nombre_donante'     => $nombreDonante,

                                'estado_actual'      => 'Sin ubicación',
                                'ubicacion_actual'   => null,
                                'fecha_ultima_actualizacion' => now(),
                            ]
                        );

                        continue;
                    }

                    // Si hay ubicaciones, por ahora tomamos la PRIMERA (en tu ejemplo solo hay una)
                    foreach ($ubicaciones as $ubic) {

                        $almacenData = $ubic['almacen'] ?? [];
                        $estanteData = $ubic['estante'] ?? [];
                        $espacioData = $ubic['espacio'] ?? [];

                        $idAlmacenExt = $almacenData['id_almacen'] ?? null;
                        $idEstanteExt = $estanteData['id_estante'] ?? null;
                        $idEspacioExt = $espacioData['id_espacio'] ?? null;

                        $almacenLocal = $idAlmacenExt
                            ? ExtAlmacen::where('idexterno', $idAlmacenExt)->first()
                            : null;

                        $estanteLocal = $idEstanteExt
                            ? ExtEstante::where('idexterno', $idEstanteExt)->first()
                            : null;

                        $espacioLocal = $idEspacioExt
                            ? ExtEspacio::where('idexterno', $idEspacioExt)->first()
                            : null;

                        $ubicacionTexto = trim(
                            ($almacenData['nombre'] ?? '') . ' / ' .
                            ($estanteData['codigo_estante'] ?? '') . ' / ' .
                            ($espacioData['codigo_espacio'] ?? '')
                        );

                        if ($ubicacionTexto === '/') {
                            $ubicacionTexto = null;
                        }

                        TrazabilidadItem::updateOrCreate(
                            [
                                'id_donacion_externa' => $idDonacionExt,
                                'id_detalle_externo'  => $idDetalleExt ?? $idProductoExt,
                            ],
                            [
                                'campaniaid'         => $campaniaLocal?->campaniaid,
                                'id_campana_externa' => $don['id_campana'] ?? null,
                                'campania_nombre'    => $don['campana']['nombre'] ?? null,

                                'codigo_unico'       => $codigoUnico,
                                'productoid'         => $productoLocal?->productoid,
                                'nombre_producto'    => $productoExt['nombre'] ?? null,
                                'categoria_producto' => $productoLocal?->categoria->nombre ?? null,
                                'cantidad_donada'    => $cantidadDonada,
                                'cantidad_por_unidad'=> $detalleBase['cantidad_por_unidad'] ?? null,
                                'unidad_empaque'     => $unidadMedida,

                                'fecha_donacion'     => $don['fecha'] ?? null,
                                'tipo_donacion'      => $don['tipo'] ?? 'especie',
                                'nombre_donante'     => $nombreDonante,

                                'almacenid'          => $almacenLocal?->almacenid,
                                'estanteid'          => $estanteLocal?->estanteid,
                                'espacioid'          => $espacioLocal?->espacioid,
                                'almacen_nombre'     => $almacenData['nombre'] ?? null,
                                'estante_codigo'     => $estanteData['codigo_estante'] ?? null,
                                'espacio_codigo'     => $espacioData['codigo_espacio'] ?? null,
                                'fecha_ingreso_almacen' => null, // la API aún no manda fecha

                                // Por ahora ignoramos paquetes porque vienen vacíos []
                                'estado_actual'      => 'En almacén',
                                'ubicacion_actual'   => $ubicacionTexto,
                                'fecha_ultima_actualizacion' => now(),
                            ]
                        );
                    }
                }
            }
        });

        return back()->with('success', 'Trazabilidad de donaciones en especie sincronizada correctamente.');
    }
}
