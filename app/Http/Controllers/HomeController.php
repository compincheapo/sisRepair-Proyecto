<?php

namespace App\Http\Controllers;

use App\Models\OrdenServicio;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Estante;
use App\Models\Repuesto;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $clientes = User::with("roles")->whereHas("roles", function($q) {
            $q->whereIn("name", ["Cliente"]);
        })->get()->count();

        $tecnicos = User::with("roles")->whereHas("roles", function($q) {
            $q->whereIn("name", ["Tecnico"]);
        })->get()->count();

        $terceros = User::with("roles")->whereHas("roles", function($q) {
            $q->whereIn("name", ["Tercero"]);
        })->get()->count();

        $vendedores = User::with("roles")->whereHas("roles", function($q) {
            $q->whereIn("name", ["Vendedor"]);
        })->get()->count();

        $ordenes = OrdenServicio::all()->count();
        $ordenesPendientes = OrdenServicio::where('finalizado', 0)->count();
        $ordenesFinalizadas = OrdenServicio::where('finalizado', 1)->count();
        
        $estantes = Estante::all()->count();
        $equiposRepuesto = DB::table('equipos_estados_users_ordenes')->where('id_estado', 14)->get()->count();
        $repuestos = Repuesto::all()->count();

        return view('home', compact('clientes', 'tecnicos', 'terceros', 'vendedores', 'ordenes', 'ordenesPendientes', 'ordenesFinalizadas', 'estantes', 'equiposRepuesto', 'repuestos'));
    }
}
