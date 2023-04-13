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
use PDF;
use App\Models\InformacionGeneral;
use App\Notifications\RegistrarPagoNotification;


class PagoDiagnosticoController extends Controller
{
    
    public function index(Request $request){

        $usuarios = User::with("roles")->whereHas("roles", function($q) {
            $q->whereIn("name", ["Cliente"]);
        })->select('id', 'name', 'lastname')->get();

        $marcas = Marca::select('id','nombre')->get();

        $tipospago = TipoPago::all();

        $tiposequipo = TipoEquipo::all();

        $pagos = Pago::join('ordenservicios_pagos', 'pagos.id', 'ordenservicios_pagos.id_pago')
        ->join('ordenesservicio', 'ordenservicios_pagos.id_orden', 'ordenesservicio.id')
        ->where('ordenesservicio.id_servicio', 1)
        ->select('pagos.*')
        ->groupBy('pagos.id')
        ->orderBy('fechapago', 'desc')
        ->paginate(5);

        $usuarioData = null;
        $marcaData = null;
        $tipopagoData = null;
        $tipoequipoData = null;
        $modeloData = $request->modelo;
        $ordenData = $request->orden;
        $precioData = $request->precio;
        $fechapagodesdeData = $request->fechapagodesde;
        $fechapagohastaData = $request->fechapagohasta;

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

        if($request->usuario || $request->marca ||  $request->tipoequipo || $request->tipopago || $request->modelo || $request->orden || $request->precio || $request->fechapagodesde || $request->fechapagohasta){

            $pagos = Pago::join('ordenservicios_pagos', 'pagos.id', '=', 'ordenservicios_pagos.id_pago')
            ->join('ordenesservicio', 'ordenservicios_pagos.id_orden', '=', 'ordenesservicio.id')
            ->join('ordenservicios_presupuestos', 'ordenesservicio.id', '=', 'ordenservicios_presupuestos.id_orden')
            ->join('equipos', 'ordenesservicio.id_equipo', '=', 'equipos.id')
            ->join('tipoequipos', 'equipos.id_tipoequipo', '=', 'tipoequipos.id')
            ->join('users', 'equipos.id_user', '=', 'users.id')
            ->join('marcas', 'equipos.id_marca', '=', 'marcas.id')
            ->join('tipopagos', 'pagos.id_tipopago', '=', 'tipopagos.id')
            ->where('ordenesservicio.id_servicio', 1)
            ->select('pagos.*')
            ->when($request->filled('usuario'), function ($query) use ($request) {
                return $query->where('users.id', $request->usuario);
            })->when($request->filled('marca'), function ($query) use ($request) {
                return $query->where('equipos.id_marca', $request->marca);
            })->when($request->filled('tipoequipo'), function ($query) use ($request) {
                return $query->where('tipoequipos.id', $request->tipoequipo);
            })->when($request->filled('tipopago'), function ($query) use ($request) {
                return $query->where('tipopagos.id', $request->tipopago);
            })->when($request->filled('orden'), function ($query) use ($request) {
                return $query->where('ordenesservicio.id', $request->orden);
            })->when($request->filled('modelo'), function ($query) use ($request) {
                return $query->where('equipos.modelo', $request->modelo);
            })->when($request->filled('precio'), function ($query) use ($request) {
                return $query->where('pagos.precio', $request->precio);
            })->when($request->filled('fechapagodesde'), function ($query) use ($request) {
                return $query->where('pagos.fechapago', '>=', $request->fechapagodesde);
            })->when($request->filled('fechapagohasta'), function ($query) use ($request) {
                return $query->where('pagos.fechapago', '<=', $request->fechapagohasta);
            })
            ->paginate(5);
        }else {
            if($request->submitbtn == 'PDF'){
                $pagos = Pago::join('ordenservicios_pagos', 'pagos.id', 'ordenservicios_pagos.id_pago')
                ->join('ordenesservicio', 'ordenservicios_pagos.id_orden', 'ordenesservicio.id')
                ->where('ordenesservicio.id_servicio', 1)
                ->select('pagos.*')
                ->get();
            } elseif($request->submitbtn == 'Filtrar'){
                $pagos = Pago::join('ordenservicios_pagos', 'pagos.id', 'ordenservicios_pagos.id_pago')
                ->join('ordenesservicio', 'ordenservicios_pagos.id_orden', 'ordenesservicio.id')
                ->where('ordenesservicio.id_servicio', 1)
                ->select('pagos.*')
                ->paginate(5);
            }
        }
            
        if($request->submitbtn == 'PDF'){
            $filtros = [];
            foreach ($request->all() as $key => $value) {
                if($value != null && $key != 'submitbtn'){
                    $filtros[$key] = $value;
                }
            }

           $filtrado = 'Todos.';
           if(count($filtros) === 1){
                foreach($filtros as $key => $value) {
                    
                    if($key == 'tipoequipo'){
                      $key = 'Tipo Equipo';
                      $selectedTipoEquipo = TipoEquipo::findOrfail($value)->where('id', $value)->first();
                      $value = $selectedTipoEquipo->nombre;
                    }
                    if($key == 'tipopago'){
                      $key = 'Tipo Pago';
                      $selectedTipoPago = TipoPago::findOrfail($value)->where('id', $value)->first();;
                      $value = $selectedTipoPago->nombre;
                    }
                    if($key == 'marca'){
                      $selectedMarca = Marca::findOrfail($value)->where('id', $value)->first();
                      $value = $selectedMarca->nombre;
                    }
                    if($key == 'usuario'){
                      $selectedUser = User::findOrfail($value)->where('id', $value)->first();
                      $value = $selectedUser->name . ' ' . $selectedUser->lastname;
                    }
                    if($key == 'fechapagodesde'){
                      $key = 'Fecha Pago Desde';
                    }
                    if($key == 'fechapagohasta'){
                      $key = 'Fecha Pago Hasta';
                    }
                    

                    $key = ucfirst($key);
                    $filtrado = $key . ': ' . $value. '.'; 
                }
           }

           if(count($filtros) > 1){
                $filtrado = '';
                foreach($filtros as $key => $value) {
                    if($key == 'tipoequipo'){
                        $key = 'Tipo Equipo';
                        $selectedTipoEquipo = TipoEquipo::findOrfail($value)->where('id', $value)->first();
                        $value = $selectedTipoEquipo->nombre;
                      }
                      if($key == 'tipopago'){
                        $key = 'Tipo Pago';
                        $selectedTipoPago = DB::table('tipopagos')->where('id', $request->tipopago)->first();
                        $value = $selectedTipoPago->nombre;
                      }
                      if($key == 'marca'){
                        $selectedMarca = Marca::findOrfail($value)->where('id', $value)->first();
                        $value = $selectedMarca->nombre;
                      }
                      if($key == 'usuario'){
                        $selectedUser = User::findOrfail($value)->where('id', $value)->first();
                        $value = $selectedUser->name . ' ' . $selectedUser->lastname;
                      }
                      if($key == 'fechapagodesde'){
                        $key = 'Fecha Pago Desde';
                      }
                      if($key == 'fechapagohasta'){
                        $key = 'Fecha Pago Hasta';
                      }

                    $key = ucfirst($key);
                    $filtrado = $filtrado . $key . ':' . $value . ', ';
                }
                $filtrado = rtrim($filtrado, ", ");
                $filtrado = $filtrado . '.';
           }
                       
            $pdf = PDF::loadView('pagodiagnostico.pdf', compact('pagos', 'filtrado'));
            return $pdf->stream();
        } elseif($request->submitbtn == 'Filtrar'){
            return view('pagodiagnostico.index', compact('tipospago', 'usuarios', 'marcas', 'pagos', 'tiposequipo', 'usuarioData', 'marcaData', 'modeloData', 'tipopagoData', 'tipoequipoData', 'ordenData', 'precioData', 'fechapagodesdeData', 'fechapagohastaData'));
        } elseif($request->submitbtn == null){
            $pagos = Pago::join('ordenservicios_pagos', 'pagos.id', 'ordenservicios_pagos.id_pago')
            ->join('ordenesservicio', 'ordenservicios_pagos.id_orden', 'ordenesservicio.id')
            ->where('ordenesservicio.id_servicio', 1)
            ->select('pagos.*')
            ->groupBy('pagos.id')
            ->orderBy('fechapago', 'desc')
            ->paginate(5);
            return view('pagodiagnostico.index', compact('tipospago', 'usuarios', 'marcas', 'pagos', 'tiposequipo', 'usuarioData', 'marcaData', 'modeloData', 'tipopagoData', 'tipoequipoData', 'ordenData', 'precioData', 'fechapagodesdeData', 'fechapagohastaData'));
        }        

        return view('pagodiagnostico.index', compact('tipospago', 'usuarios', 'marcas', 'pagos', 'tiposequipo', 'usuarioData', 'marcaData', 'modeloData', 'tipopagoData', 'tipoequipoData', 'ordenData', 'precioData', 'fechapagodesdeData', 'fechapagohastaData'));
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

        $precioServicioDiagnostico = DB::table('precios')
        ->where('precios.id_servicio', 1)
        ->orderBy('precios.created_at', 'desc')
        ->first();

        $pago = new Pago;
        $pago->id_tipopago = $request->get("tipopago");
        $pago->fechapago = $fechapago;
        $pago->save();

        DB::table('comprobantepago')->insert([
            'id_pago' => $pago->id,
            'created_at' => $fechapago,
            'updated_at' => $fechapago
        ]);

        DB::table('recibopago')->insert([
            'id_pago' => $pago->id,
            'created_at' => $fechapago,
            'updated_at' => $fechapago
        ]);
        
        $precioPagoDiagnostico = 0;
        $ordenes = [];
        foreach ($equipos as $equipo) {
            $orden = Equipo::findOrfail($equipo)->orden()->where('finalizado', 1)->where('id_servicio', 1)->orderBy('ordenesservicio.created_at', 'desc')
            ->first();
            
            $ordenes = $orden;

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

            DB::table('notificacionpago')
                ->where('id', $orden->id)
                ->delete();
            
            $precioPagoDiagnostico = $precioPagoDiagnostico + $precioServicioDiagnostico->precio;
        }

        $pago->precio = $precioPagoDiagnostico;
        $pago->update();

        return redirect()->route('pagodiagnostico.index');
    }

    public function getComprobacionPrecioDiagnostico(){        

        $precioServicioDiagnostico = DB::table('precios')
        ->where('precios.id_servicio', 1)
        ->first();

        if(!empty($precioServicioDiagnostico)){
            return response()->json([
                'success' => 'Todo ok.'
            ]); 
        } else {
            return response()->json([
                'error' => 'El Servicio de Diagnóstico no cuenta con un Precio definido, definalo en Modulo Configuración > Información General.'
            ]);
        
        }

    }

    public function getComprobanteDiagnostico($id){
        $informacionGeneral = InformacionGeneral::first();
        $comprobante = DB::table('comprobantepago')->where('id_pago', $id)->first();
        $pago = Pago::findOrfail($id)->where('id', $id)->first();
        $user = $pago->ordenespago()->first()->equipo->user->name . ' ' . $pago->ordenespago()->first()->equipo->user->lastname; 
        $tipopago = $pago->tipopago->nombre;
        $servicio = $pago->ordenespago()->first()->servicio->nombre;
        $pdf = PDF::loadView('comprobante.servicios', compact('informacionGeneral', 'comprobante', 'pago', 'user', 'tipopago', 'servicio'));
        return $pdf->stream();
    }

    public function getReciboDiagnostico($id){
        $informacionGeneral = InformacionGeneral::first();
        $recibo = DB::table('recibopago')->where('id_pago', $id)->first();
        $pago = Pago::findOrfail($id)->where('id', $id)->first();
        $user = $pago->ordenespago()->first()->equipo->user->name . ' ' . $pago->ordenespago()->first()->equipo->user->lastname; 
        $tipopago = $pago->tipopago->nombre;
        $servicio = $pago->ordenespago()->first()->servicio->nombre;
        $pdf = PDF::loadView('recibo.servicios', compact('informacionGeneral', 'recibo', 'pago', 'user', 'tipopago', 'servicio'));
        return $pdf->stream();
    }

    
}
