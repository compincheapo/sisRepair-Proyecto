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

});
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::resource('/estados', EstadoController::class)->middleware('auth');;


Route::resource('/tipoequipos', TipoEquipoController::class)->middleware('auth');;

Route::resource('/tipoaccesorios', TipoAccesorioController::class)->middleware('auth');;

Route::resource('/servicios', ServicioController::class)->middleware('auth');;
    
Route::resource('/tiposervicios', TipoServicioController::class)->middleware('auth');;

