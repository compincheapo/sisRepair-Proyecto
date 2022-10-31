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

Route::resource('/servicios', ServicioController::class)->middleware('auth');
    
Route::resource('/tiposervicios', TipoServicioController::class)->middleware('auth');

Route::resource('/estantes', EstanteController::class)->middleware('auth');

Route::resource('/seccionesestante', SeccionesEstanteController::class)->middleware('auth');

Route::resource('/equipos', EquipoController::class)->middleware('auth');

Route::get('/seccionesestante/{id}/create', [SeccionesEstanteController::class, 'create'])->name('sectioncreate');

Route::post('/equipos/fetch', [EquipoController::class, 'fetch'])->name('equipos.fetch');

Route::get('/tecnicos', [UsuarioController::class, 'getTecnicos'])->name('tecnicos');




// ------------------ Módulo Diagnóstico ------------------ 
Route::get('/equiposUsuarios', [UsuarioController::class, 'getEquiposDiagnostico'])->name('equiposDiagnostico');

Route::get('/asignacion/diagnostico', [UsuarioController::class, 'verDiagnosticos'])->name('diagnosticos');

Route::get('/asignacion/diagnosticosasignados', [UsuarioController::class, 'verAsignacionesRealizadas'])->name('diagnosticosasignados');

Route::get('/asignacion/misdiagnosticosasignados', [UsuarioController::class, 'getMisAsignacionesDiagnostico'])->name('misdiagnosticosasignados');

Route::get('/asignacion/diagnostico/{id}', [UsuarioController::class, 'getAsignacionDiagnostico'])->name('diagnosticoasignado');

Route::post('/asignacion/reasignardiagnostico', [UsuarioController::class, 'reasignarDiagnostico'])->name('reasignardiagnostico');

Route::get('/asignacion/tecnicosreasignacion', [UsuarioController::class, 'getTecnicosReasignacion'])->name('getTecnicosReasignacion');

Route::get('/asignacion/finalizardiagnostico', [UsuarioController::class, 'getFinalizarDiagnostico'])->name('getFinalizarDiagnostico');

Route::post('/asignacion/finalizardiagnostico', [UsuarioController::class, 'finalizarDiagnostico'])->name('finalizarDiagnostico');

Route::get('/asignacion/vermisdiagnosticosasignados', [UsuarioController::class, 'verMisAsignacionesRealizadas'])->name('vermisdiagnosticosasignados');

Route::post('/asignacion/iniciardiagnostico', [UsuarioController::class, 'iniciarDiagnostico'])->name('iniciarDiagnostico');

Route::get('/asignacionesrealizadas', [UsuarioController::class, 'getAsignacionesRealizadas'])->name('asignacionesrealizadas');

Route::post('/asignacion/creardiagnostico', [UsuarioController::class, 'asignarDiagnostico'])->name('asignarDiagnostico');




// ------------------ Módulo Reparación ------------------ 



