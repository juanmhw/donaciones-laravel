<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CampaniaReporteController;
use App\Http\Controllers\UsuarioReporteController;
use App\Http\Controllers\CampaniaCierreController;
use App\Http\Controllers\CentroMensajesController;



use App\Http\Controllers\{
    RoleController,
    UsuarioController,
    UsuariosRolController,
    CampaniaController,
    EstadoController,
    DonacionController,
    AsignacionController,
    DetallesAsignacionController,
    MensajeController,
    RespuestaMensajeController,
    DonacionesAsignacionController,
    SaldosDonacionController
};

/** Home: lista de roles */
Route::get('/', [RoleController::class, 'index'])->name('home');

/** Resources (CRUD) */
Route::resource('roles', RoleController::class);
Route::resource('usuarios', UsuarioController::class);
Route::resource('usuariosroles', UsuariosRolController::class);
Route::resource('campanias', CampaniaController::class);
Route::resource('estados', EstadoController::class);
Route::resource('donaciones', DonacionController::class);
Route::resource('asignaciones', AsignacionController::class);
Route::resource('detallesasignacion', DetallesAsignacionController::class);
Route::resource('mensajes', MensajeController::class);
Route::resource('respuestasmensajes', RespuestaMensajeController::class);
Route::resource('donacionesasignaciones', DonacionesAsignacionController::class);
Route::resource('saldosdonaciones', SaldosDonacionController::class);
Route::resource('mensajes', MensajeController::class)->parameters(['mensajes' => 'id']);
Route::resource('respuestasmensajes', RespuestaMensajeController::class)->parameters(['respuestasmensajes' => 'id']);
/**
 * Rutas extra para el flujo de Asignaciones:
 * - Ver/gestionar detalles (ítems)
 * - Asignar donaciones (con saldos) a una asignación
 */
// Estado de cuenta por donante
Route::get('/usuarios/{usuario}/estado-cuenta', [UsuarioReporteController::class, 'estadoCuenta'])
    ->name('usuarios.estadoCuenta')
    ->whereNumber('usuario');

// Seleccionar usuario para estado de cuenta
Route::get('/estado-cuenta', [UsuarioReporteController::class, 'seleccionarUsuario'])
    ->name('usuarios.estadoCuentaSeleccion');

// Procesar selección y redirigir al estado de cuenta
Route::get('/estado-cuenta/mostrar', [UsuarioReporteController::class, 'mostrarDesdeSeleccion'])
    ->name('usuarios.estadoCuentaMostrar');

// Estado de cuenta de un usuario concreto
Route::get('/usuarios/{usuario}/estado-cuenta', [UsuarioReporteController::class, 'estadoCuenta'])
    ->name('usuarios.estadoCuenta')
    ->whereNumber('usuario');

Route::get('asignaciones/{id}/detalles', [AsignacionController::class, 'detalles'])
    ->name('asignaciones.detalles');
Route::post('asignaciones/{id}/detalles', [AsignacionController::class, 'guardarDetalle'])
    ->name('asignaciones.detalles.store');

Route::get('asignaciones/{id}/asignar', [AsignacionController::class, 'asignar'])
    ->name('asignaciones.asignar');
Route::post('asignaciones/{id}/asignar', [AsignacionController::class, 'guardarAsignacion'])
    ->name('asignaciones.asignar.store');
Route::get('/donaciones/{id}/reasignar', [DonacionController::class, 'reasignarForm'])
     ->name('donaciones.reasignarForm');

Route::post('/donaciones/{id}/reasignar', [DonacionController::class, 'reasignar'])
     ->name('donaciones.reasignar');

// 1) Primero el reporte
Route::get('/campanias/reporte-general', [CampaniaReporteController::class, 'general'])
    ->name('campanias.reporteGeneral');

// Cierre de campaña
Route::get('/campanias/cierre', [CampaniaCierreController::class, 'seleccionarCampania'])
    ->name('campanias.cierreSeleccion');

Route::get('/campanias/cierre/mostrar', [CampaniaCierreController::class, 'mostrarResumen'])
    ->name('campanias.cierreMostrar');

Route::post('/campanias/{campania}/cerrar', [CampaniaCierreController::class, 'cerrarCampania'])
    ->name('campanias.cerrar')
    ->whereNumber('campania');


// 2) Luego la resource, con restricción numérica en el id
Route::resource('campanias', CampaniaController::class)
     ->whereNumber('campania'); // param singular que usa la resource
    
// Centro de mensajes por usuario
Route::get('/centro-mensajes', [CentroMensajesController::class, 'seleccionarUsuario'])
    ->name('mensajes.centroSeleccion');

Route::get('/centro-mensajes/usuario', [CentroMensajesController::class, 'centroPorUsuario'])
    ->name('mensajes.centroUsuario');

    // Vista general y detalle ya vienen por resource
Route::resource('asignaciones', AsignacionController::class);

// Nuevas rutas
Route::get('/asignaciones/{id}/asignar-donacion', 
    [AsignacionController::class, 'asignarDonacionForm'])
    ->name('asignaciones.asignarDonacionForm');

Route::post('/asignaciones/{id}/asignar-donacion', 
    [AsignacionController::class, 'asignarDonacionStore'])
    ->name('asignaciones.asignarDonacionStore');
use App\Http\Controllers\ReporteCierreCajaController;

Route::get('/reporte/cierre-caja', [ReporteCierreCajaController::class, 'index'])
    ->name('reporte.cierreCaja');

Route::get('/reporte/cierre-caja/pdf', [ReporteCierreCajaController::class, 'exportarPDF'])
    ->name('reporte.cierreCaja.pdf');