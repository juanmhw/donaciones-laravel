<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CampaniaReporteController;

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

// 2) Luego la resource, con restricción numérica en el id
Route::resource('campanias', CampaniaController::class)
     ->whereNumber('campania'); // param singular que usa la resource