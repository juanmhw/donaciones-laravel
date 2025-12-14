<?php

namespace App\Http\Controllers\Ext;

use App\Http\Controllers\Controller;
use App\Models\Ext\ExtCategoriaProducto;
use App\Models\Ext\ExtProducto;
use App\Models\Ext\ExtAlmacen;
use App\Models\Ext\ExtEstante;
use App\Models\Ext\ExtEspacio;
use Illuminate\Support\Facades\Http;

class IntegracionExternaController extends Controller
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.externos.base_url', 'http://localhost:8000');
    }

    public function syncCategoriasProductos()
    {
        $url = "{$this->baseUrl}/api/categorias";

        $response = Http::get($url);

        if (!$response->successful()) {
            return response()->json([
                'success' => false,
                'message' => 'Error al consumir /api/categorias',
            ], 500);
        }

        $json = $response->json();

        if (!isset($json['data']) || !is_array($json['data'])) {
            return response()->json([
                'success' => false,
                'message' => 'Formato inesperado en /api/categorias',
            ], 500);
        }

        foreach ($json['data'] as $cat) {
            $categoriaLocal = ExtCategoriaProducto::updateOrCreate(
                ['idexterno' => $cat['id_categoria']],
                ['nombre' => $cat['nombre']]
            );

            if (!empty($cat['productos']) && is_array($cat['productos'])) {
                foreach ($cat['productos'] as $prod) {
                    ExtProducto::updateOrCreate(
                        ['idexterno' => $prod['id_producto']],
                        [
                            'categoriaid'   => $categoriaLocal->categoriaid,
                            'nombre'        => $prod['nombre'],
                            'descripcion'   => $prod['descripcion'] ?? null,
                            'unidad_medida' => $prod['unidad_medida'] ?? null,
                        ]
                    );
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Categorías y productos sincronizados correctamente.',
        ]);
    }

    public function syncAlmacenes()
    {
        $url = "{$this->baseUrl}/api/almacenes-completo";

        $response = Http::get($url);

        if (!$response->successful()) {
            return response()->json([
                'success' => false,
                'message' => 'Error al consumir /api/almacenes-completo',
            ], 500);
        }

        $json = $response->json();

        if (!isset($json['data']) || !is_array($json['data'])) {
            return response()->json([
                'success' => false,
                'message' => 'Formato inesperado en /api/almacenes-completo',
            ], 500);
        }

        foreach ($json['data'] as $alm) {
            $almacenLocal = ExtAlmacen::updateOrCreate(
                ['idexterno' => $alm['id_almacen']],
                [
                    'nombre'    => $alm['nombre'],
                    'direccion' => $alm['direccion'] ?? null,
                    'latitud'   => $alm['latitud'] ?? null,
                    'longitud'  => $alm['longitud'] ?? null,
                ]
            );

            if (!empty($alm['estantes']) && is_array($alm['estantes'])) {
                foreach ($alm['estantes'] as $est) {
                    $estanteLocal = ExtEstante::updateOrCreate(
                        ['idexterno' => $est['id_estante']],
                        [
                            'almacenid'      => $almacenLocal->almacenid,
                            'codigo_estante' => $est['codigo_estante'],
                            'descripcion'    => $est['descripcion'] ?? null,
                        ]
                    );

                    if (!empty($est['espacios']) && is_array($est['espacios'])) {
                        foreach ($est['espacios'] as $esp) {
                            ExtEspacio::updateOrCreate(
                                ['idexterno' => $esp['id_espacio']],
                                [
                                    'estanteid'      => $estanteLocal->estanteid,
                                    'codigo_espacio' => $esp['codigo_espacio'],
                                    'estado'         => $esp['estado'] ?? null,
                                ]
                            );
                        }
                    }
                }
            }
        }

        return back()->with('success', 'Almacenes, estantes y espacios sincronizados correctamente.');

    }

    public function syncAll()
    {
        $this->syncCategoriasProductos();
        $this->syncAlmacenes();

        return response()->json([
            'success' => true,
            'message' => 'Sincronización externa completada (categorías/productos/almacenes).',
        ]);
    }
}
