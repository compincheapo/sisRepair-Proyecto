<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Equipo;
use App\Models\TipoPago;
use App\Models\User;
use App\Models\Marca;
use App\Models\Pago;
use App\Models\TipoEquipo;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class PagoDiagnosticoController extends Controller
{
    
    public function index(Request $request){

        $usuarios = User::with("roles")->whereHas("roles", function($q) {
            $q->whereIn("name", ["Cliente"]);
        })->select('id', 'name', 'lastname')->get();

        $marcas = Marca::select('id','nombre')->get();

        $tipospago = TipoPago::all();

        $tiposequipo = TipoEquipo::all();

        $pagos = Pago::paginate(5);

        $usuarioData = null;
        $marcaData = null;
        $tipopagoData = null;
        $tipoequipoData = null;
        $modeloData = $request->modelo;


        
        if($request->usuario || $request->marca || $request->tipopago || $request->tipoequipo || $request->modelo){
            $pagos = Pago::join('ordenservicios_pagos', 'pagos.id', '=', 'ordenservicios_pagos.id_pago')
            ->join('ordenesservicio', 'ordenservicios_pagos.id_orden', '=', 'ordenesservicio.id')
            ->join('equipos', 'ordenesservicio.id_equipo', '=', 'equipos.id')
            ->join('tipoequipos', 'equipos.id_tipoequipo', '=', 'tipoequipos.id')
            ->join('users', 'equipos.id_user', '=', 'users.id')
            ->join('marcas', 'equipos.id_marca', '=', 'marcas.id')
            ->join('tipopagos', 'pagos.id_tipopago', '=', 'tipopagos.id')
            ->whereIn('users.id', [$request->get("usuario")])
            ->whereIn('marcas.id', [$request->get("marca")])
            ->whereIn('tipopagos.id', [$request->get("tipopago")])
            ->whereIn('users.id', [$request->get("usuario")])
            ->whereIn('tipoequipos.id', [$request->tipoequipo])
            ->where('equipos.modelo', 'like', '%'.$request->modelo .'%')
            ->paginate(5); 
            
        }
            
        if($request->usuario){
            $usuarioData = User::where('id', $request->usuario)->select('id', 'name', 'lastname')->first();
        } 

        if($request->marca){
            $marcaData = Marca::where('id', $request->marca)->select('id', 'nombre')->first();
        } 

        if($request->tipopago){
            $tipopagoData = TipoPago::where('id', $request->tipopago)->select('id', 'nombre')->first();
        } 

        if($request->tipoequipo){
            $tipoequipoData = TipoEquipo::where('id', $request->tipoequipo)->select('id', 'nombre')->first();
        } 
        

        return view('pagodiagnostico.index', compact('tipospago', 'usuarios', 'marcas', 'pagos', 'tiposequipo', 'usuarioData', 'marcaData', 'modeloData', 'tipopagoData', 'tipoequipoData'));
    }

    public function create(){

        $tipospago = TipoPago::all();

        return view('pagodiagnostico.create', compact('tipospago'));
    }

    public function registrarPagoDiagnostico(Request $request){
       
        $this->validate($request, [
            'tipopago' => 'required',
            'idEquipos' => 'required',
        ],
        [
            'tipopago.required' => 'Debes elegir un tipo de Pago.',
            'idEquipos.required' => 'Debes elegir uno o varios Equipos.'
        ]
        );

        $equipos = $request->get('idEquipos');
        $cajero = Auth::user();

        $fechapago = Carbon::now();

        $pago = new Pago;
        $pago->id_tipopago = $request->get("tipopago");
        $pago->fechapago = $fechapago;
        $pago->save();

        foreach ($equipos as $equipo) {
            $orden = Equipo::findOrfail($equipo)->orden()->where('finalizado', 1)->where('id_servicio', 1)->orderBy('fechafin', 'desc')
            ->first();         

            DB::table('ordenservicios_pagos')->insert([
                'id_orden' => $orden->id,
                'id_pago' => $pago->id,
            ]); 

            DB::table('equipos_estados_users_ordenes')->insert([
                'id_equipo' => $equipo,
                'id_estado' => 12,
                'id_user' => $cajero->id,
                'id_orden' => $orden->id,
            ]);
            
        }

        return redirect()->route('pagodiagnostico.index');


    }
}
