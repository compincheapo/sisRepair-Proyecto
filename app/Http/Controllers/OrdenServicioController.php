<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\TipoEquipo;
use App\Models\Equipo;
use App\Models\OrdenServicio;
use App\Models\Servicio;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Models\Pago;

class OrdenServicioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {        
        $usuarios = User::with("roles")->whereHas("roles", function($q) {
            $q->whereIn("name", ["Cliente"]);
        })->get();

        $ordenes = OrdenServicio::paginate(5);
        $servicios = Servicio::select('id','nombre')->get();

        $usuarioData = null;
        $servicioData = $request->servicio;
        $estadoData = $request->estado;
        $fechaCompromisoData = $request->fechacompromiso;
        $fechaFinalizacionDesdeData = $request->fechafindesde;
        $fechaFinalizacionHastaData = $request->fechafinhasta;
        
        if($request->usuario || $request->servicio ||  $request->estado != null || $request->fechacompromiso || $request->fechafindesde || $request->fechafinhasta){
            $ordenes = OrdenServicio::join('equipos', 'ordenesservicio.id_equipo', '=', 'equipos.id')
            ->join('users', 'equipos.id_user', '=', 'users.id')
            ->select('ordenesservicio.*')->when($request->filled('usuario'), function ($query) use ($request) {
                return $query->where('users.id', $request->usuario);
            })->when($request->filled('servicio'), function ($query) use ($request) {
                return $query->where('ordenesservicio.id_servicio', $request->servicio);
            })->when($request->filled('estado'), function ($query) use ($request) {
                return $query->where('ordenesservicio.finalizado', $request->estado);
            })->when($request->filled('fechacompromiso'), function ($query) use ($request) {
                return $query->where('ordenesservicio.fechacompromiso', $request->fechacompromiso);
            })->when($request->filled('fechafindesde'), function ($query) use ($request) {
                return $query->where('ordenesservicio.fechafin', '>=', $request->fechafindesde);
            })->when($request->filled('fechafinhasta'), function ($query) use ($request) {
                return $query->where('ordenesservicio.fechafin', '<=', $request->fechafinhasta);
            })->paginate(5);
        }
            
        if($request->usuario){
            $usuarioData = User::where('id', $request->usuario)->select('id', 'name', 'lastname')->first();
            $usuarioData = $usuarioData->id;
        } 

        if($request->estado && $request->estado == 1){
            $estadoData = 1;
        }
        if($request->estado === '0'){
            $estadoData = "false";
        }

        return view('ordenesservicios.index', compact('ordenes','usuarios', 'servicios', 'usuarioData', 'servicioData', 'estadoData', 'fechaCompromisoData', 'fechaFinalizacionDesdeData', 'fechaFinalizacionHastaData'));
    }

    public function getDetalleOrdenServicio($id){

        $collection = new Collection;
        $orden = OrdenServicio::findOrfail($id)->where('id', $id)->first();
        $equipo = $orden->equipo;
        $equipoSeccionEstante = $equipo->seccionEstante;
        $equipoEstante = $equipoSeccionEstante->estante->nombre;
        $equipoMarca = $equipo->marca->nombre;
        $equipoTipoEquipo = $equipo->tipoEquipo->nombre;

        $estadoEquipo = DB::table('equipos_estados_users_ordenes')
        ->select('equipos_estados_users_ordenes.id_estado')
        ->where('id_equipo', $equipo->id)
        ->where('id_orden', $orden->id)
        ->orderBy('equipos_estados_users_ordenes.created_at', 'desc')
        ->first();

        $userOrden = DB::table('users_ordenes')
        ->select('users.name', 'users.lastname')
        ->join('users', 'users_ordenes.id_user', 'users.id')
        ->where('estadoAsignacion', 1)
        ->where('id_orden', $orden->id)->first();

        if(!empty($userOrden)){
            $equipo->user = $userOrden->name . ' ' . $userOrden->lastname;
        } else {
            $equipo->user = 'No asignado';
        }


        if($orden->id_servicio == 1){
            if($orden->finalizado == 1){
                $equipo->servicio = 'Diagnóstico';
                $presupuestado = DB::table('ordenservicios_presupuestos')
                ->where('id_orden', $orden->id)
                ->where('presupuestado', 1)
                ->first();
    
                if(!empty($presupuestado)){
                    $equipo->presupuesto = $presupuestado->presupuesto;
                    $pago = DB::table('ordenservicios_pagos')
                    ->where('id_orden', $orden->id)
                    ->first();
    
                    if(!empty($pago)){
                        $equipo->pago = 'Pagado';
                    } else {
                        $equipo->pago = 'No pagado';
                    }
            
                } else {
                    $equipo->presupuesto = 'No presupuestado.';
                    $equipo->pago = 'No pagado';
                }
            }   

            $comentarios = DB::table('equipos_estados_users_ordenes')
            ->select('equipos_estados_users_ordenes.created_at', 'equipos_estados_users_ordenes.id_estado', 'equipos_estados_users_ordenes.descripcion', 'users.name', 'users.lastname')
            ->join('users', 'equipos_estados_users_ordenes.id_user', 'users.id')
            ->where('equipos_estados_users_ordenes.id_orden', $orden->id)
            ->whereIn('equipos_estados_users_ordenes.id_estado', [1, 4, 10])
            ->orderBy('equipos_estados_users_ordenes.created_at', 'asc')
            ->get();

        } else if($orden->id_servicio == 2){   
            if($orden->finalizado == 1){
                $pago = DB::table('ordenservicios_pagos')
                ->where('id_orden', $orden->id)
                ->first();
                if(!empty($pago)){
                    $equipo->pago = 'Pagado';
                } else {
                    $equipo->pago = 'No pagado';
                }
            }   

            $comentarios = DB::table('equipos_estados_users_ordenes')
            ->select('equipos_estados_users_ordenes.created_at', 'equipos_estados_users_ordenes.id_estado', 'equipos_estados_users_ordenes.descripcion', 'users.name', 'users.lastname')
            ->join('users', 'equipos_estados_users_ordenes.id_user', 'users.id')
            ->where('equipos_estados_users_ordenes.id_orden', $orden->id)
            ->whereIn('equipos_estados_users_ordenes.id_estado', [5, 8])
            ->orderBy('equipos_estados_users_ordenes.created_at', 'asc')
            ->get();
        }

        $accesorios = $equipo->accesorios()->select('nombre')->get();
        $equipo->id_orden = $orden->id;
        $equipo->accesorios = $accesorios;    
        $equipo->comentarios = $comentarios;
        $equipo->estante = $equipoEstante;
        $equipo->seccionEstante = $equipoSeccionEstante->nombre;
        $equipo->marca = $equipoMarca;
        $equipo->tipoequipo = $equipoTipoEquipo;
        $equipo->servicio = $orden->id_servicio;
        $equipo->estado = $estadoEquipo->id_estado;

        $collection->push($equipo);

         return DataTables()->collection($collection)->toJson();

        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('ordenesservicios.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){

        $this->validate($request, [
            'idCliente' => 'required',
            'idEquipo' => 'required',
            'descripcion' => 'required',
            'fecha' => 'required|'.'after:'.Date('d-m-Y')
        ],
        [
            'idCliente.required' => 'Debes elegir un Cliente para registrar una Orden de Servicio.',
            'descripcion.required' => 'Debes de dar un detalle del Equipo para registrar una nueva Orden de Servicio.',
            'idEquipo.required' => 'Debes elegir un Equipo para registrar una Orden de Servicio.',
            'fecha.required' => 'Debes seleccionar una fecha para asignar los equipos.',
            'fecha.after' => 'Debes seleccionar una fecha prometida posterior a hoy.'
        ]
        );

        $equipo = $request->get('idEquipo');     

        $ordenServicio = new OrdenServicio;
        $ordenServicio->finalizado = false;
        $ordenServicio->fechacompromiso = $request->get('fecha');
        $ordenServicio->id_equipo = $equipo;
        $ordenServicio->id_servicio = 1;
        $ordenServicio->save();

        $cajero = Auth::user();

        DB::table('equipos_estados_users_ordenes')->insert([
            'id_equipo' => $equipo,
            'id_estado' => 1,
            'id_user' => $cajero->id,
            'id_orden' => $ordenServicio->id,
            'descripcion' => $request->get('descripcion'),

        ]);

        return redirect()->route('ordenesservicios.index');
    }

    public function getOrdenesEquipo($id){
        $ordenes = OrdenServicio::where('id_equipo', $id)->orderBy('created_at', 'desc')->paginate(5);
        $ultimaOrden = OrdenServicio::where('id_equipo', $id)->orderBy('created_at', 'desc')->first();
        $ultimoEstadoOrden = DB::table('equipos_estados_users_ordenes')->where('id_orden', $ultimaOrden->id)->orderBy('created_at', 'desc')->first();

        foreach ($ordenes as $orden) {
            if($ultimaOrden->id_servicio == 2 && ($orden->id == $ultimaOrden->id) && $ultimoEstadoOrden->id_estado == 8){
                $orden->pago = 'Si';
            } else {
                $orden->pago = 'No';
            }

            if($ultimaOrden->id_servicio == 2 &&  ($orden->id == $ultimaOrden->id) && $ultimoEstadoOrden->id_estado == 19){
                $existeRetroalimentacion = DB::table('ordenservicios_retroalimentacion')->where('id_orden', $orden->id)->first();

                if(empty($existeRetroalimentacion)){
                    $orden->retroalimentar = 'Si';
                } else {
                    $orden->retroalimentar = 'No';
                }
            } else {
                $orden->retroalimentar = 'No';
            }
        }

        return view('ordenesservicios.ordenesservicioequipo', compact('ordenes'));
    }

    public function pagarOrdenServicio($id){
        $orden = OrdenServicio::findOrfail($id)->where('id', $id)->first();
        $equipo = $orden->equipo;
        $ultimaOrdenDiagnostico = OrdenServicio::where('id_equipo', $equipo->id)->where('id_servicio', 1)->orderBy('created_at', 'desc')->first();
        $presupuesto = DB::table('ordenservicios_presupuestos')->where('id_orden', $ultimaOrdenDiagnostico->id)->first();

        return view('ordenesservicios.pagoorden', compact('orden', 'equipo', 'ultimaOrdenDiagnostico', 'presupuesto'));
    }

    public function getResultadoPagoOrdenSatisfactoria(Request $request, $id){

        if($request->get('status') == 'approved'){
            $cliente = Auth::user();
            $orden = OrdenServicio::findOrfail($id)->where('id', $id)->first();
            $equipo = $orden->equipo;
            $ultimaOrdenDiagnostico = OrdenServicio::where('id_equipo', $equipo->id)->where('id_servicio', 1)->orderBy('created_at', 'desc')->first();
            
            $presupuesto = DB::table('ordenservicios_presupuestos')->where('id_orden', $ultimaOrdenDiagnostico->id)->first();       

            DB::table('pagomercadopago')->insert([
                'collection_id' => $request->get('collection_id'),
                'collection_status' => $request->get('collection_status'),
                'payment_id' => $request->get('payment_id'),
                'status' => $request->get('status'),
                'payment_type' => $request->get('payment_type'),
                'merchant_order_id' => $request->get('merchant_order_id'),
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
    
            ]);

            $pago = new Pago;
            $pago->id_tipopago = 2;
            $pago->fechapago = \Carbon\Carbon::now();
            $pago->precio = $presupuesto->presupuesto;
            $pago->save();

            DB::table('ordenservicios_pagos')->insert([
                'id_pago' => $pago->id,
                'id_orden' => $id,
            ]);

            DB::table('equipos_estados_users_ordenes')->insert([
                'id_equipo' => $equipo->id,
                'id_estado' => 13,
                'id_user' => $cliente->id,
                'id_orden' => $id,
                'descripcion' => '',
    
            ]);

            return redirect()->route('ordenesequipo', [$equipo->id])->with('success', 'Se registró con Éxito el pago de la Orden de Servicio ' . $id); 

        } else {
            return Redirect::back()->withErrors(['msg' => 'El pago de la 
            Orden de Servicio ' . $id , 'ha sido rechazado por MercadoPago, intentelo más tarde.']);
        }
    }
    public function getResultadoPagoOrdenFallo(Request $request, $id){
        return Redirect::back()->withErrors(['msg' => 'Ha surgido un problema con el proceso de pago de Mercadopago para el pago de la 
        Orden de Servicio ' . $id , 'intentelo más tarde.']);
   
    }

}
