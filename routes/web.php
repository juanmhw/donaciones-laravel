<?php

use Illuminate\Support\Facades\Route;

// =========================================================================
// IMPORTACIÓN DE CONTROLADORES
// =========================================================================

use App\Http\Controllers\Auth\LoginController;

// Controladores Principales
use App\Http\Controllers\{
    UsuarioController,
    RoleController,
    CampaniaController,
    EstadoController,
    DonacionController,
    AsignacionController,
    DetallesAsignacionController,
    MensajeController,
    RespuestaMensajeController,
    DonacionesAsignacionController,
    SaldosDonacionController,
    DashboardController // Asegúrate de tenerlo o comenta la ruta dashboard abajo
};

// Controladores de Reportes y Procesos Específicos
use App\Http\Controllers\{
    CampaniaReporteController,
    UsuarioReporteController,
    CampaniaCierreController,
    CentroMensajesController,
    ReporteCierreCajaController,
    ReporteTrazabilidadController
};

// Controladores de Sincronización e Integración
use App\Http\Controllers\{
    ApiCampaniaSyncController,
    ApiDonacionSyncController
};

use App\Http\Controllers\Ext\{
    IntegracionExternaController,
    AlmacenesEstructuraController,
    TrazabilidadSyncController
};

/*
|--------------------------------------------------------------------------
| 1. RUTAS DE AUTENTICACIÓN (Públicas)
|--------------------------------------------------------------------------
*/
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
// El logout es POST y requiere estar logueado, lo ponemos abajo en el grupo auth o aquí si prefieres
// pero mejor dentro de auth para protegerlo.

/*
|--------------------------------------------------------------------------
| 2. RUTAS PROTEGIDAS (Requieren Iniciar Sesión)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // ==========================================
    // LOGOUT & HOME
    // ==========================================
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    
    // El dashboard es accesible para todos los roles, cada uno verá su menú filtrado
    Route::get('/', [DashboardController::class, 'index'])->name('home');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


    // ==========================================
    // SINCRONIZACIONES (ACCESO GENERAL AUTENTICADO)
    // ==========================================
    // Como pediste: "todos pueden verla" (mientras estén logueados)
    Route::get('/sincronizar/campanias', [ApiCampaniaSyncController::class, 'sync'])->name('api.campanias.sync');
    Route::get('/sincronizar/donaciones-dinero', [ApiDonacionSyncController::class, 'syncDinero'])->name('api.donaciones.dinero.sync');
    
    Route::prefix('integracion-externa')->group(function () {
        Route::get('/sync/categorias', [IntegracionExternaController::class, 'syncCategoriasProductos'])->name('integracion.sync.categorias');
        Route::get('/sync/almacenes', [IntegracionExternaController::class, 'syncAlmacenes'])->name('integracion.sync.almacenes');
        Route::get('/sync/all', [IntegracionExternaController::class, 'syncAll'])->name('integracion.sync.all');
        Route::get('/sync/trazabilidad-especie', [TrazabilidadSyncController::class, 'syncEspecie'])->name('integracion.sync.trazabilidad.especie');
    });


    // =========================================================================
    // GRUPO A: SOLO ADMINISTRADOR (Gestión Estructural)
    // =========================================================================
    Route::group(['middleware' => ['role:Administrador']], function () {
        // Usuarios y Roles
        Route::resource('usuarios', UsuarioController::class);
        Route::resource('roles', RoleController::class);
        
        // Estados de cuenta de usuarios (Gestión administrativa)
        Route::get('/estado-cuenta', [UsuarioReporteController::class, 'seleccionarUsuario'])->name('usuarios.estadoCuentaSeleccion');
        Route::get('/estado-cuenta/mostrar', [UsuarioReporteController::class, 'mostrarDesdeSeleccion'])->name('usuarios.estadoCuentaMostrar');
        Route::get('/usuarios/{usuario}/estado-cuenta', [UsuarioReporteController::class, 'estadoCuenta'])
            ->name('usuarios.estadoCuenta')
            ->whereNumber('usuario');

        // Configuración Base
        Route::resource('estados', EstadoController::class);
        
        // CRUD Campañas (Crear/Editar campañas es rol de admin)
        Route::resource('campanias', CampaniaController::class)->whereNumber('campania');
    });


    // =========================================================================
    // GRUPO B: ADMINISTRADOR | ALMACENERO
    // =========================================================================
    // "El de Almacenero... solo puede aparecer almacenes..."
    Route::group(['middleware' => ['role:Administrador|Almacenero']], function () {
        Route::get('/almacenes/estructura', [AlmacenesEstructuraController::class, 'index'])->name('almacenes.estructura');
    });


    // =========================================================================
    // GRUPO C: ADMINISTRADOR | REPORTES
    // =========================================================================
    // "Reportes ese puede entrar a donaciones, asignaciones y mensajes"
    Route::group(['middleware' => ['role:Administrador|Reportes']], function () {
        
        // --- DONACIONES ---
        Route::resource('donaciones', DonacionController::class);
        Route::resource('saldosdonaciones', SaldosDonacionController::class);
        Route::get('/donaciones/{id}/reasignar', [DonacionController::class, 'reasignarForm'])->name('donaciones.reasignarForm');
        Route::post('/donaciones/{id}/reasignar', [DonacionController::class, 'reasignar'])->name('donaciones.reasignar');

        // --- ASIGNACIONES ---
        Route::resource('asignaciones', AsignacionController::class);
        Route::resource('detallesasignacion', DetallesAsignacionController::class);
        Route::resource('donacionesasignaciones', DonacionesAsignacionController::class);
        
        // Detalles y acciones específicas
        Route::get('asignaciones/{id}/detalles', [AsignacionController::class, 'detalles'])->name('asignaciones.detalles');
        Route::post('asignaciones/{id}/detalles', [AsignacionController::class, 'guardarDetalle'])->name('asignaciones.detalles.store');
        Route::get('asignaciones/{id}/asignar', [AsignacionController::class, 'asignar'])->name('asignaciones.asignar');
        Route::post('asignaciones/{id}/asignar', [AsignacionController::class, 'guardarAsignacion'])->name('asignaciones.asignar.store');
        Route::get('/asignaciones/{id}/asignar-donacion', [AsignacionController::class, 'asignarDonacionForm'])->name('asignaciones.asignarDonacionForm');
        Route::post('/asignaciones/{id}/asignar-donacion', [AsignacionController::class, 'asignarDonacionStore'])->name('asignaciones.asignarDonacionStore');

        // --- MENSAJES ---
        Route::get('/centro-mensajes', [CentroMensajesController::class, 'seleccionarUsuario'])->name('mensajes.centroSeleccion');
        Route::get('/centro-mensajes/usuario', [CentroMensajesController::class, 'centroPorUsuario'])->name('mensajes.centroUsuario');
        Route::resource('mensajes', MensajeController::class)->parameters(['mensajes' => 'id']);
        Route::resource('respuestasmensajes', RespuestaMensajeController::class)->parameters(['respuestasmensajes' => 'id']);

        // --- REPORTES ESPECÍFICOS DE GESTIÓN ---
        // Reporte General de Campañas
        Route::get('/campanias/reporte-general', [CampaniaReporteController::class, 'general'])->name('campanias.reporteGeneral');
        
        // Cierre de Campaña
        Route::get('/campanias/cierre', [CampaniaCierreController::class, 'seleccionarCampania'])->name('campanias.cierreSeleccion');
        Route::get('/campanias/cierre/mostrar', [CampaniaCierreController::class, 'mostrarResumen'])->name('campanias.cierreMostrar');
        Route::post('/campanias/{campania}/cerrar', [CampaniaCierreController::class, 'cerrarCampania'])
            ->name('campanias.cerrar')
            ->whereNumber('campania');

        // Cierre de Caja
        Route::get('/reporte/cierre-caja', [ReporteCierreCajaController::class, 'index'])->name('reporte.cierreCaja');
        Route::get('/reporte/cierre-caja/pdf', [ReporteCierreCajaController::class, 'exportarPDF'])->name('reporte.cierreCaja.pdf');

                // --- DONACIONES ---
        Route::resource('donaciones', DonacionController::class);
        Route::resource('saldosdonaciones', SaldosDonacionController::class);
        Route::get('/donaciones/{id}/reasignar', [DonacionController::class, 'reasignarForm'])->name('donaciones.reasignarForm');
        Route::post('/donaciones/{id}/reasignar', [DonacionController::class, 'reasignar'])->name('donaciones.reasignar');

        // --- ASIGNACIONES ---
        Route::resource('asignaciones', AsignacionController::class);
        Route::resource('detallesasignacion', DetallesAsignacionController::class);
        Route::resource('donacionesasignaciones', DonacionesAsignacionController::class);

        Route::get('asignaciones/{id}/detalles', [AsignacionController::class, 'detalles'])->name('asignaciones.detalles');
        Route::post('asignaciones/{id}/detalles', [AsignacionController::class, 'guardarDetalle'])->name('asignaciones.detalles.store');
        Route::get('asignaciones/{id}/asignar', [AsignacionController::class, 'asignar'])->name('asignaciones.asignar');
        Route::post('asignaciones/{id}/asignar', [AsignacionController::class, 'guardarAsignacion'])->name('asignaciones.asignar.store');

        // --- MENSAJES (TU SISTEMA ACTUAL) ---
        Route::get('/centro-mensajes', [CentroMensajesController::class, 'seleccionarUsuario'])->name('mensajes.centroSeleccion');
        Route::get('/centro-mensajes/usuario', [CentroMensajesController::class, 'centroPorUsuario'])->name('mensajes.centroUsuario');
        Route::resource('mensajes', MensajeController::class)->parameters(['mensajes' => 'id']);
        Route::resource('respuestasmensajes', RespuestaMensajeController::class)->parameters(['respuestasmensajes' => 'id']);

        // ✅ CHAT 1 A 1 (OPCIÓN B) - NUEVO Y LIMPIO
        Route::get('/chat', [MensajeController::class, 'inbox'])->name('chat.inbox');
        Route::get('/chat/{usuario}', [MensajeController::class, 'conversacion'])->name('chat.conversacion');
        Route::post('/chat/{usuario}/enviar', [MensajeController::class, 'enviar'])->name('chat.enviar');

        // --- REPORTES ---
        Route::get('/campanias/reporte-general', [CampaniaReporteController::class, 'general'])->name('campanias.reporteGeneral');
        Route::get('/reporte/cierre-caja', [ReporteCierreCajaController::class, 'index'])->name('reporte.cierreCaja');
        Route::get('/reporte/cierre-caja/pdf', [ReporteCierreCajaController::class, 'exportarPDF'])->name('reporte.cierreCaja.pdf');
    });


    // =========================================================================
    // GRUPO D: COMPARTIDO (ADMIN | ALMACENERO | REPORTES)
    // =========================================================================
    // "Almacenero... trazabilidad de donaciones" y "Reportes... trazabilidad de donaciones"
    Route::group(['middleware' => ['role:Administrador|Almacenero|Reportes']], function () {
        Route::get('/reportes/trazabilidad', [ReporteTrazabilidadController::class, 'index'])->name('reportes.trazabilidad.index');
    });
    Route::get('/reportes/trazabilidad/pdf', [ReporteTrazabilidadController::class, 'exportarPDF'])
    ->name('reportes.trazabilidad.pdf');
    
    Route::get('/reportes/trazabilidad', [ReporteTrazabilidadController::class, 'index'])
    ->name('reportes.trazabilidad.index');
    Route::get('/reportes/cierre-caja/excel', [ReporteCierreCajaController::class, 'exportarExcel'])
    ->name('reporte.cierreCaja.excel');
    Route::get('/reportes/trazabilidad/excel', [App\Http\Controllers\ReporteTrazabilidadController::class, 'exportarExcel'])
    ->name('reportes.trazabilidad.excel');
    Route::get('/centro-mensajes', [CentroMensajesController::class, 'seleccionarUsuario'])->name('mensajes.centroSeleccion');
    Route::get('/centro-mensajes/usuario', [CentroMensajesController::class, 'centroPorUsuario'])->name('mensajes.centroUsuario');
    Route::resource('mensajes', MensajeController::class)->parameters(['mensajes' => 'id']);
    Route::resource('respuestasmensajes', RespuestaMensajeController::class)->parameters(['respuestasmensajes' => 'id']);

        // ✅ CHAT 1 A 1 (OPCIÓN B) - NUEVO Y LIMPIO
    Route::get('/chat', [MensajeController::class, 'inbox'])->name('chat.inbox');
    Route::get('/chat/{usuario}', [MensajeController::class, 'conversacion'])->name('chat.conversacion');
    Route::post('/chat/{usuario}/enviar', [MensajeController::class, 'enviar'])->name('chat.enviar');

        // --- REPORTES ---
    Route::get('/campanias/reporte-general', [CampaniaReporteController::class, 'general'])->name('campanias.reporteGeneral');
    Route::get('/reporte/cierre-caja', [ReporteCierreCajaController::class, 'index'])->name('reporte.cierreCaja');
    Route::get('/reporte/cierre-caja/pdf', [ReporteCierreCajaController::class, 'exportarPDF'])->name('reporte.cierreCaja.pdf');

    Route::get('/chat', [MensajeController::class, 'inbox'])->name('chat.inbox');
    Route::get('/chat/{usuario}', [MensajeController::class, 'conversacion'])->name('chat.conversacion');
    Route::post('/chat/{usuario}/enviar', [MensajeController::class, 'enviar'])->name('chat.enviar');



    

}); // Fin del grupo Auth