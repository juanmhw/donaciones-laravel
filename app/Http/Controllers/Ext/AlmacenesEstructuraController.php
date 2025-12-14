<?php

namespace App\Http\Controllers\Ext;

use App\Http\Controllers\Controller;
use App\Models\Ext\ExtAlmacen;

class AlmacenesEstructuraController extends Controller
{
    public function index()
    {
        // Trae almacenes con estantes y espacios
        $almacenes = ExtAlmacen::with(['estantes.espacios'])
            ->orderBy('nombre')
            ->get();

        return view('almacenes.estructura', compact('almacenes'));
    }
}
