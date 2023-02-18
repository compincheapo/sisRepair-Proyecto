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
use App\Http\Controllers\AuditoriaController;
use App\Http\Controllers\EquipoRepuestoController;
use App\Http\Controllers\OrdenServicioController;
use App\Http\Controllers\PagoDiagnosticoController;

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

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('auth');

Route::resource('/estados', EstadoController::class)->middleware('auth');

Route::resource('/tipoequipos', TipoEquipoController::class)->middleware('auth');

Route::resource('/tipoaccesorios', TipoAccesorioController::class)->middleware('auth');

Route::resource('/tiporepuestos', TipoRepuestoController::class)->middleware('auth');

Route::resource('/servicios', ServicioController::class)->middleware('auth');
    
Route::resource('/tiposervicios', TipoServicioController::class)->middleware('auth');

Route::resource('/estantes', EstanteController::class)->middleware('auth');

Route::resource('/seccionesestante', SeccionesEstanteController::class)->middleware('auth');

//-------------------------- Registro Abandono Equipo --------------------------

Route::get('/equipos/equiposabandonados', [EquipoController::class, 'getEquiposAbandonados'])->name('equipos.equiposAbandonados')->middleware('auth');

Route::get('/equipos/equiposabandonados/create', [EquipoController::class, 'createEquiposAbandonados'])->name('equipos.equiposAbandonados.create')->middleware('auth');

Route::get('/equipos/equiposreparados', [EquipoController::class, 'getEquiposReparados'])->name('equipos.equiposreparados')->middleware('auth');

Route::post('/equipos/registrarabandono', [EquipoController::class, 'registrarAbandono'])->name('equipos.registrarAbandono')->middleware('auth');

Route::get('/equipos/equiposreparados/detalle/{id}', [EquipoController::class, 'getDetalleEquipoReparado'])->name('getDetalleEquipoReparado')->middleware('auth');

Route::get('/equipos/equiposabandonados/detalle/{id}', [EquipoController::class, 'getDetalleEquipoAbandonado'])->name('getDetalleEquipoAbandonado')->middleware('auth');

// --------------------------

//Mis Equipos

Route::get('/equipos/misequiposdiagnostico', [EquipoController::class, 'getMisEquiposDiagnostico'])->name('equipos.misequiposdiagnostico')->middleware('auth');

Route::get('/equipos/misequiposreparacion', [EquipoController::class, 'getMisEquiposReparacion'])->name('equipos.misequiposreparacion')->middleware('auth');

Route::post('/equipos/ordenes/rechazarpresupuesto', [UsuarioController::class, 'rechazarPresupuesto'])->name('rechazarPresupuesto')->middleware('auth');

Route::get('/ordenes/orden/pago/{id}', [OrdenServicioController::class, 'pagarOrdenServicio'])->name('pagarOrdenServicio')->middleware('auth');

Route::get('/ordenes/orden/pago/success/{id}', [OrdenServicioController::class, 'getResultadoPagoOrdenSatisfactoria'])->name('getResultadoPagoOrdenSatisfactoria')->middleware('auth');

Route::get('/ordenes/orden/pago/failure/{id}', [OrdenServicioController::class, 'getResultadoPagoOrdenFallo'])->name('getResultadoPagoOrdenFallo')->middleware('auth');


// --------------------------

Route::resource('/equipos', EquipoController::class)->middleware('auth');

Route::get('/seccionesestante/{id}/create', [SeccionesEstanteController::class, 'create'])->name('sectioncreate')->middleware('auth');

Route::post('/equipos/fetch', [EquipoController::class, 'fetch'])->name('equipos.fetch')->middleware('auth');

Route::get('/tecnicos', [UsuarioController::class, 'getTecnicos'])->name('tecnicos')->middleware('auth');

Route::get('/terceros', [UsuarioController::class, 'getTerceros'])->name('terceros')->middleware('auth');

Route::resource('/repuestos', RepuestoController::class)->middleware('auth');

Route::resource('/equiporepuestos', EquipoRepuestoController::class)->middleware('auth');


// ------------------ Ordenes de Servicio ------------------ 

Route::resource('/ordenesservicios', OrdenServicioController::class)->middleware('auth');

Route::get('/ordenes/detalle/{id}', [OrdenServicioController::class, 'getDetalleOrdenServicio'])->name('getDetalleOrdenServicio')->middleware('auth');

Route::get('/ordenes/equipo/{id}', [OrdenServicioController::class, 'getOrdenesEquipo'])->name('ordenesequipo')->middleware('auth');

Route::get('/users/clientes', [UsuarioController::class, 'getClientes'])->name('getClientes')->middleware('auth');

Route::get('/equipos/cliente/{id}', [EquipoController::class, 'getEquiposCliente'])->name('getEquiposCliente')->middleware('auth');

Route::post('/ordenes/retroalimentacion', [UsuarioController::class, 'registrarRetroalimentacion'])->name('registrarRetroalimentacion')->middleware('auth');


// --------------------------

// ------------------ Módulo Terceros ------------------ 

Route::get('/asignacion/diagnostico/terceros', [UsuarioController::class, 'verDiagnosticosTercero'])->name('diagnosticosTercero')->middleware('auth');

Route::post('/asignacion/creardiagnostico/tercero', [UsuarioController::class, 'asignarEquipoaTercero'])->name('asignarEquipoaTercero')->middleware('auth');

Route::get('/asignacion/equiposTerceros', [EquipoController::class, 'getEquiposTerceros'])->name('getEquiposTerceros')->middleware('auth');

Route::get('/asignacionestercerosrealizadas', [UsuarioController::class, 'getAsignacionesTercerosRealizadas'])->name('asignacionestercerosrealizadas')->middleware('auth');

Route::get('/asignacion/equipostercerossasignados', [UsuarioController::class, 'verAsignacionesEquiposTercerosRealizadas'])->name('equipostercerosasignados')->middleware('auth');

Route::get('/asignacion/tecnicosytercerosreasignacion/{id}', [UsuarioController::class, 'getTecnicosyTercerosReasignacion'])->name('getTecnicosyTercerosReasignacion')->middleware('auth');

Route::post('/asignacion/registrarRetiro/tercero', [UsuarioController::class, 'registrarRetiro'])->name('registrarRetiro');

Route::get('/asignacion/diagnostico/retirosterceros', [UsuarioController::class, 'verRetirosTercero'])->name('retirosTerceros')->middleware('auth');

Route::get('/asignacion/equiposTercerosRetirados', [EquipoController::class, 'getEquiposTercerosRetirados'])->name('getEquiposTercerosRetirados')->middleware('auth');

Route::post('/ingreso/ingresarEquipos/tercero', [UsuarioController::class, 'registrarIngresoEquiposTercero'])->name('registrarIngresoEquiposTercero')->middleware('auth');

Route::get('/asignacion/vermisequiposasignados', [UsuarioController::class, 'verMisEquiposAsignados'])->name('vermisequiposasignados')->middleware('auth');

Route::get('/asignacion/misequiposasignados', [UsuarioController::class, 'getMisEquiposAsignados'])->name('misequiposasignados')->middleware('auth');

Route::get('/asignacion/finalizarservicioequipo/{id}', [UsuarioController::class, 'getFinalizarServicioEquipo'])->name('getFinalizarServicioEquipo')->middleware('auth');

Route::post('/asignacion/finalizarservicioequipo', [UsuarioController::class, 'finalizarServicioEquipo'])->name('finalizarServicioEquipo')->middleware('auth');

Route::get('/equiposServicio/detalle/{id}', [UsuarioController::class, 'getDetalleEquipoServicio'])->name('getDetalleEquipoServicio')->middleware('auth');

Route::get('/asignacion/equipo/{id}', [UsuarioController::class, 'getAsignacionEquipoServicio'])->name('equipoasignado')->middleware('auth');

Route::get('/asignacion/estapresupuestado/{id}', [UsuarioController::class, 'estaPresupuestado'])->name('estaPresupuestado')->middleware('auth');



// ------------------ Módulo Diagnóstico ------------------ 
Route::get('/equiposDiagnostico', [UsuarioController::class, 'getEquiposDiagnostico'])->name('equiposDiagnostico')->middleware('auth');

Route::get('/asignacion/diagnostico', [UsuarioController::class, 'verDiagnosticos'])->name('diagnosticos')->middleware('auth');

Route::get('/asignacion/diagnosticosasignados', [UsuarioController::class, 'verAsignacionesDiagnosticoRealizadas'])->name('diagnosticosasignados')->middleware('auth');

Route::get('/asignacion/misdiagnosticosasignados', [UsuarioController::class, 'getMisAsignacionesDiagnostico'])->name('misdiagnosticosasignados')->middleware('auth');

Route::get('/asignacion/diagnostico/{id}', [UsuarioController::class, 'getAsignacionDiagnostico'])->name('diagnosticoasignado')->middleware('auth');

Route::get('/asignacion/estadiagnosticado/{id}', [UsuarioController::class, 'estaDiagnosticado'])->name('estaDiagnosticado')->middleware('auth');

Route::get('/equiposDiagnostico/detalle/{id}', [UsuarioController::class, 'getDetalleEquipoDiagnostico'])->name('getDetalleEquipoDiagnostico')->middleware('auth');

Route::post('/asignacion/reasignardiagnostico', [UsuarioController::class, 'reasignarDiagnostico'])->name('reasignardiagnostico')->middleware('auth');

Route::get('/asignacion/tecnicosreasignacion/{id}', [UsuarioController::class, 'getTecnicosReasignacion'])->name('getTecnicosReasignacion')->middleware('auth');

Route::get('/asignacion/finalizardiagnostico/{id}', [UsuarioController::class, 'getFinalizarDiagnostico'])->name('getFinalizarDiagnostico')->middleware('auth');

Route::post('/asignacion/finalizardiagnostico', [UsuarioController::class, 'finalizarDiagnostico'])->name('finalizarDiagnostico')->middleware('auth');

Route::post('/asignacion/presupuestarEquipo', [UsuarioController::class, 'presupuestarEquipo'])->name('presupuestarEquipo')->middleware('auth');

Route::post('/asignacion/aceptarrpresupuesto', [UsuarioController::class, 'aceptarPresupuesto'])->name('aceptarPresupuesto')->middleware('auth');

Route::get('/asignacion/vermisdiagnosticosasignados', [UsuarioController::class, 'verMisAsignacionesRealizadas'])->name('vermisdiagnosticosasignados')->middleware('auth');

Route::post('/asignacion/iniciardiagnostico', [UsuarioController::class, 'iniciarDiagnostico'])->name('iniciarDiagnostico')->middleware('auth');

Route::get('/asignacionesdiagnosticorealizadas', [UsuarioController::class, 'getAsignacionesDiagnosticoRealizadas'])->name('asignacionesdiagnosticorealizadas')->middleware('auth');

Route::post('/asignacion/creardiagnostico', [UsuarioController::class, 'asignarDiagnostico'])->name('asignarDiagnostico')->middleware('auth');




//------------------ Módulo Reparación ------------------ 

Route::get('/equiposReparacion', [UsuarioController::class, 'getEquiposReparacion'])->name('equiposReparacion')->middleware('auth');

Route::get('/asignacion/reparacion', [UsuarioController::class, 'verReparacion'])->name('reparaciones')->middleware('auth');

Route::get('/equiposReparacion/detalle/{id}', [UsuarioController::class, 'getDetalleEquipoReparacion'])->name('getDetalleEquipoReparacion')->middleware('auth');

Route::post('/asignacion/crearreparacion', [UsuarioController::class, 'asignarReparacion'])->name('asignarReparacion')->middleware('auth');

Route::get('/asignacion/reparacionesasignadas', [UsuarioController::class, 'verAsignacionesReparacionRealizadas'])->name('reparacionesasignadas')->middleware('auth');

Route::get('/asignacion/reparacion/{id}', [UsuarioController::class, 'getAsignacionReparacion'])->name('reparacionasignada')->middleware('auth');

Route::post('/asignacion/reasignarreparacion', [UsuarioController::class, 'reasignarReparacion'])->name('reasignarreparacion')->middleware('auth');

Route::get('/asignacionesreparacionrealizadas', [UsuarioController::class, 'getAsignacionesReparacionRealizadas'])->name('asignacionesreparacionrealizadas')->middleware('auth');

Route::get('/asignacion/vermisreparacionesasignadas', [UsuarioController::class, 'verMisAsignacionesReparacionRealizadas'])->name('vermisreparacionesasignadas')->middleware('auth');

Route::get('/asignacion/misreparacionesasignadas', [UsuarioController::class, 'getMisAsignacionesReparacion'])->name('misreparacionesasignadas')->middleware('auth');

Route::post('/asignacion/iniciarreparacion', [UsuarioController::class, 'iniciarReparacion'])->name('iniciarReparacion')->middleware('auth');

Route::get('/asignacion/finalizarreparacion/{id}', [UsuarioController::class, 'getFinalizarReparacion'])->name('getFinalizarReparacion')->middleware('auth');

Route::post('/asignacion/finalizarreparacion', [UsuarioController::class, 'finalizarReparacion'])->name('finalizarReparacion')->middleware('auth');

Route::get('/repuestosReparacion', [RepuestoController::class, 'getRepuestosReparacion'])->name('repuestosReparacion')->middleware('auth');


//Auditoria

Route::get('/auditoria', [AuditoriaController::class, 'index'])->name('auditoria.index')->middleware('auth');

//Pagos

Route::resource('/pagodiagnostico', PagoDiagnosticoController::class)->except(['update', 'edit', 'destroy', 'show'])->middleware('auth');

Route::get('/equiposPresupuestados', [EquipoController::class, 'getEquiposPresupuestados'])->name('equiposPresupuestados')->middleware('auth');

Route::post('/pagos/registrarpagodiagnostico', [PagoDiagnosticoController::class, 'registrarPagoDiagnostico'])->name('registrarPagoDiagnostico')->middleware('auth');

Route::get('/pagos/comprobarpreciodiagnostico', [PagoDiagnosticoController::class, 'getComprobacionPrecioDiagnostico'])->name('getComprobacionPrecioDiagnostico')->middleware('auth');

Route::get('/pagos/diagnostico/equipo/detalle/{id}', [UsuarioController::class, 'getDetalleEquipoDiagnosticoPago'])->name('getDetalleEquipoDiagnosticoPago')->middleware('auth');

Route::get('/pagos/reparacion/equipo/detalle/{id}', [UsuarioController::class, 'getDetalleEquipoReparacionPago'])->name('getDetalleEquipoReparacionPago')->middleware('auth');
