<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\TipoServicioController;
use App\Http\Controllers\EstadoController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\TipoEquipoController;
use App\Http\Controllers\TipoAccesorioController;
use App\Http\Controllers\EstanteController;
use App\Http\Controllers\SeccionesEstanteController;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\TipoRepuestoController;
use App\Http\Controllers\RepuestoController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });


Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => ['auth']], function(){
    Route::resource('roles', RolController::class);
    Route::resource('usuarios', UsuarioController::class);
    Route::resource('marcas', MarcaController::class);
    Route::get('downloadpdf', [UsuarioController::class, 'pdfUsuarios'])->name('usuarios.pdf');


});
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::resource('/estados', EstadoController::class)->middleware('auth');

Route::resource('/tipoequipos', TipoEquipoController::class)->middleware('auth');

Route::resource('/tipoaccesorios', TipoAccesorioController::class)->middleware('auth');

Route::resource('/tiporepuestos', TipoRepuestoController::class)->middleware('auth');

Route::resource('/servicios', ServicioController::class)->middleware('auth');
    
Route::resource('/tiposervicios', TipoServicioController::class)->middleware('auth');

Route::resource('/estantes', EstanteController::class)->middleware('auth');

Route::resource('/seccionesestante', SeccionesEstanteController::class)->middleware('auth');

Route::resource('/equipos', EquipoController::class)->middleware('auth');

Route::get('/seccionesestante/{id}/create', [SeccionesEstanteController::class, 'create'])->name('sectioncreate');

Route::post('/equipos/fetch', [EquipoController::class, 'fetch'])->name('equipos.fetch');

Route::get('/tecnicos', [UsuarioController::class, 'getTecnicos'])->name('tecnicos');

Route::resource('/repuestos', RepuestoController::class)->middleware('auth');




// ------------------ Módulo Diagnóstico ------------------ 
Route::get('/equiposDiagnostico', [UsuarioController::class, 'getEquiposDiagnostico'])->name('equiposDiagnostico');

Route::get('/asignacion/diagnostico', [UsuarioController::class, 'verDiagnosticos'])->name('diagnosticos');

Route::get('/asignacion/diagnosticosasignados', [UsuarioController::class, 'verAsignacionesDiagnosticoRealizadas'])->name('diagnosticosasignados');

Route::get('/asignacion/misdiagnosticosasignados', [UsuarioController::class, 'getMisAsignacionesDiagnostico'])->name('misdiagnosticosasignados');

Route::get('/asignacion/diagnostico/{id}', [UsuarioController::class, 'getAsignacionDiagnostico'])->name('diagnosticoasignado');

Route::get('/asignacion/estadiagnosticado/{id}', [UsuarioController::class, 'estaDiagnosticado'])->name('estaDiagnosticado');

Route::get('/equiposDiagnostico/detalle/{id}', [UsuarioController::class, 'getDetalleEquipoDiagnostico'])->name('getDetalleEquipoDiagnostico');

Route::post('/asignacion/reasignardiagnostico', [UsuarioController::class, 'reasignarDiagnostico'])->name('reasignardiagnostico');

Route::get('/asignacion/tecnicosreasignacion/{id}', [UsuarioController::class, 'getTecnicosReasignacion'])->name('getTecnicosReasignacion');

Route::get('/asignacion/finalizardiagnostico/{id}', [UsuarioController::class, 'getFinalizarDiagnostico'])->name('getFinalizarDiagnostico');

Route::post('/asignacion/finalizardiagnostico', [UsuarioController::class, 'finalizarDiagnostico'])->name('finalizarDiagnostico');

Route::post('/asignacion/presupuestarEquipo', [UsuarioController::class, 'presupuestarEquipo'])->name('presupuestarEquipo');

Route::post('/asignacion/aceptarrpresupuesto', [UsuarioController::class, 'aceptarPresupuesto'])->name('aceptarPresupuesto');

Route::get('/asignacion/vermisdiagnosticosasignados', [UsuarioController::class, 'verMisAsignacionesRealizadas'])->name('vermisdiagnosticosasignados');

Route::post('/asignacion/iniciardiagnostico', [UsuarioController::class, 'iniciarDiagnostico'])->name('iniciarDiagnostico');

Route::get('/asignacionesdiagnosticorealizadas', [UsuarioController::class, 'getAsignacionesDiagnosticoRealizadas'])->name('asignacionesdiagnosticorealizadas');

Route::post('/asignacion/creardiagnostico', [UsuarioController::class, 'asignarDiagnostico'])->name('asignarDiagnostico');




//------------------ Módulo Reparación ------------------ 

Route::get('/equiposReparacion', [UsuarioController::class, 'getEquiposReparacion'])->name('equiposReparacion');

Route::get('/asignacion/reparacion', [UsuarioController::class, 'verReparacion'])->name('reparaciones');

Route::get('/equiposReparacion/detalle/{id}', [UsuarioController::class, 'getDetalleEquipoReparacion'])->name('getDetalleEquipoReparacion');

Route::post('/asignacion/crearreparacion', [UsuarioController::class, 'asignarReparacion'])->name('asignarReparacion');

Route::get('/asignacion/reparacionesasignadas', [UsuarioController::class, 'verAsignacionesReparacionRealizadas'])->name('reparacionesasignadas');

Route::get('/asignacion/reparacion/{id}', [UsuarioController::class, 'getAsignacionReparacion'])->name('reparacionasignada');

Route::post('/asignacion/reasignarreparacion', [UsuarioController::class, 'reasignarReparacion'])->name('reasignarreparacion');

Route::get('/asignacionesreparacionrealizadas', [UsuarioController::class, 'getAsignacionesReparacionRealizadas'])->name('asignacionesreparacionrealizadas');

Route::get('/asignacion/vermisreparacionesasignadas', [UsuarioController::class, 'verMisAsignacionesReparacionRealizadas'])->name('vermisreparacionesasignadas');

Route::get('/asignacion/misreparacionesasignadas', [UsuarioController::class, 'getMisAsignacionesReparacion'])->name('misreparacionesasignadas');

Route::post('/asignacion/iniciarreparacion', [UsuarioController::class, 'iniciarReparacion'])->name('iniciarReparacion');

Route::get('/asignacion/finalizarreparacion/{id}', [UsuarioController::class, 'getFinalizarReparacion'])->name('getFinalizarReparacion');

Route::post('/asignacion/finalizarreparacion', [UsuarioController::class, 'finalizarReparacion'])->name('finalizarReparacion');

Route::get('/repuestosReparacion', [RepuestoController::class, 'getRepuestosReparacion'])->name('repuestosReparacion');

