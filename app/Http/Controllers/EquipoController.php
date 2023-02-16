<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Equipo;
use App\Models\TipoEquipo;
use App\Models\Marca;
use App\Models\User;
use App\Models\Estante;
use App\Models\SeccionesEstante;
use App\Models\Servicio;
use App\Models\TipoAccesorio;
use \Illuminate\Support\Facades\Auth;
use App\Models\Estado;
use App\Models\OrdenServicio;
use App\Models\Repuesto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Carbon\Carbon;


class EquipoController extends Controller
{
    function __construct(){
        $this->middleware('permission:ver-equipos|crear-equipos|editar-equipos|borrar-equipos|secciones-estante', ['only' => ['index']]);
        $this->middleware('permission:crear-equipos', ['only' => ['create', 'store']]); //Métodos asociados al permiso.
        $this->middleware('permission:editar-equipos', ['only' => ['edit', 'update']]); 
        $this->middleware('permission:borrar-equipos', ['only' => ['destroy']]); 
    }

    public function index()
    {
        $equipos = Equipo::paginate(5);


        return view('equipos.index', compact('equipos'));
    }

    public function create()
    {
        // $equipo = Equipo::findOrfail(4);
        // dd($equipo->orden()->where('finalizado', 0)->get());
        $tiposequipo = TipoEquipo::pluck('nombre', 'id');
        $marcas = Marca::pluck('nombre', 'id');

        //Usuarios con rol cliente
        $usuarios = User::with("roles")->whereHas("roles", function($q) {
                    $q->whereIn("name", ['cliente']);
                     })->select('id', 'name', 'lastname')->get();
        
        //dd($usuarios);
        $estantes = Estante::select('nombre', 'id')->get();
        //dd($estantes);
        $tiposaccesorios = TipoAccesorio::select('nombre', 'id')->get();

        //dd($tiposequipo);

        return view('equipos.create', compact('tiposequipo', 'marcas', 'usuarios', 'estantes', 'tiposaccesorios'));
    }


    public function store(Request $request)
    {
        //dd($request->all());
        //Validaciones Equipo.
        $this->validate($request, [
            'serie' => 'unique:equipos,serie',
            'tipoequipo' => 'required',
            'marca' => 'required',
            'usuario' => 'required',
            'estante' => 'required',
            'seccion' => 'required',
        ]);


        //Creación de Equipo. 
        $input = $request; 

        $equipo = new Equipo;
        $equipo->serie = $input->get('serie');
        $equipo->modelo = $input->get('modelo');
        $equipo->id_marca = $input->get('marca');
        $equipo->id_seccionestante = $input->get('seccion');
        $equipo->id_tipoequipo = $input->get('tipoequipo');
        $equipo->id_user = $input->get('usuario');
        $equipo->save();

        //Asociación de Accesorios al Equipo.
        if($input->accesorios && isset($input->accesorios)){
            foreach ($input->accesorios as $accesorio) {
                $equipo->accesorios()->attach($accesorio); 
            }
        }

        return redirect()->route('equipos.index');
    }

    public function edit($id)
    {
        $equipo = Equipo::find($id);  

        $tiposaccesorios = TipoAccesorio::select('nombre', 'id')->get();
        $accesorios = $equipo->accesorios()->pluck('id_accesorio')->toArray();

        $tiposequipos = TipoEquipo::pluck('nombre', 'id');  
        $tipoequipo = TipoEquipo::findOrfail($equipo->id_tipoequipo)->where('id', $equipo->id_tipoequipo)->pluck('id', 'nombre')->first();

        $marcas = Marca::pluck('nombre', 'id');
        $marca = Marca::findOrfail($equipo->id_marca)->where('id', $equipo->id_marca)->pluck('id', 'nombre')->first();
        
        $estantes = Estante::select('nombre', 'id')->get();
        $estante = SeccionesEstante::findOrfail($equipo->id_seccionestante)->where('id', $equipo->id_seccionestante)->first();

        return view('equipos.edit', compact('equipo', 'tiposequipos', 'tipoequipo', 'tiposaccesorios', 'accesorios', 'marcas', 'marca', 'estantes', 'estante'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'nombre' => 'required',
        ]);

        $input = $request->all();

        $equipo = Equipo::find($id);
        $equipo->update($input);

        return redirect()->route('equipos.index');
    }

    public function destroy($id)
    {
        Equipo::find($id)->delete();
        return redirect()->route('equipos.index');
    }

    public function show($id)
    {
        //
    }

    public function fetch(Request $request)
    {
        $idEstante = $request->get('value');
        $secciones = Estante::findOrfail($idEstante)->seccionesEstante()->get(); 

        $seleccione = "";

        $output = '<option value="">Seleccionar... '.ucfirst($seleccione).'</option>';
        foreach($secciones as $seccion)
            {
            if($request->get("repuesto")){
                $repuesto = Repuesto::where('id',$request->get("repuesto"))->first();
                if($repuesto->id_seccionestante == $seccion->id){
                    $output .= '<option value="'.$seccion->id.'" selected>'.$seccion->nombre.'</option>';
                } else {
                    $output .= '<option value="'.$seccion->id.'">'.$seccion->nombre.'</option>';
                }
            } else if($request->get("equipo")){
                $equipo = Equipo::where('id',$request->get("equipo"))->first();
                if($equipo->id_seccionestante == $seccion->id){
                    $output .= '<option value="'.$seccion->id.'" selected>'.$seccion->nombre.'</option>';
                } else {
                    $output .= '<option value="'.$seccion->id.'">'.$seccion->nombre.'</option>';
                }
            }
            else{
                $output .= '<option value="'.$seccion->id.'" selected>'.$seccion->nombre.'</option>';
            }
            }
            echo $output;
    }

    public function getEquiposPresupuestados(){
        $equiposDiagnosticoPresupuestado = DB::table('equipos_estados_users_ordenes')
        ->select('equipos_estados_users_ordenes.id_equipo')
        ->join('ordenesservicio', 'equipos_estados_users_ordenes.id_orden', '=', 'ordenesservicio.id')
        ->where('equipos_estados_users_ordenes.id_estado', 18)
        ->where('ordenesservicio.id_servicio', 1)
        ->where('ordenesservicio.finalizado', 1)
        ->get();

       $collection = new Collection;
       
        foreach ($equiposDiagnosticoPresupuestado as $equipo) {
            $equipos = Equipo::find($equipo->id_equipo)->select('id', 'serie','id_marca', 'id_user', 'id_tipoequipo', 'modelo')->where('id', $equipo->id_equipo)->with('marca:id,nombre','user:id,name', 'tipoequipo:id,nombre')->first();

            $estadoEquipo = DB::table('equipos')
            ->select('equipos_estados_users_ordenes.id_estado')
            ->join('equipos_estados_users_ordenes', 'equipos.id', 'equipos_estados_users_ordenes.id_equipo')
            ->where('equipos.id', $equipo->id_equipo)
            ->orderBy('equipos_estados_users_ordenes.created_at', 'desc')
            ->first();

            
            $estado = Estado::find($estadoEquipo->id_estado)->where('id', $estadoEquipo->id_estado)->first();

            $user = DB::table('equipos')->select('users.name', 'users.lastname')
            ->join('ordenesservicio','equipos.id', '=', 'ordenesservicio.id_equipo' )
            ->join('users_ordenes','ordenesservicio.id', '=', 'users_ordenes.id_orden' )
            ->join('users','users_ordenes.id_user', '=', 'users.id')
            ->where('users_ordenes.estadoAsignacion', 1)
            ->where('equipos.id', $equipo->id_equipo)->first();

            $fechaIngreso = DB::table('equipos')
            ->join('equipos_estados_users_ordenes','equipos.id', '=', 'equipos_estados_users_ordenes.id_equipo' )
            ->join('ordenesservicio','equipos_estados_users_ordenes.id_orden', '=', 'ordenesservicio.id')
            ->where('equipos.id', $equipo->id_equipo)
            ->where('equipos_estados_users_ordenes.id_estado', 1)
            ->select('equipos_estados_users_ordenes.created_at')
            ->first();
            
            $equipos->name = $user->name . " " . $user->lastname;
            $equipos->estado = $estado->nombre;
            $equipos->fechaIngreso = $fechaIngreso->created_at;
                
            if($estado->id == 18){
                $collection->push($equipos);
            }  

        }

        return DataTables()->collection($collection)->toJson();
    }
    

    public function getEquiposAbandonados(Request $request){
        $usuarios = User::with("roles")->whereHas("roles", function($q) {
            $q->whereIn("name", ["Cliente"]);
        })->get();

        $estantes = Estante::select('id', 'nombre')->get();
        $marcas = Marca::select('id','nombre')->get();
        $tiposequipo = TipoEquipo::select('id', 'nombre')->get();
        $equiposAbandonados = Equipo::join('equipos_estados_users_ordenes', 'equipos.id', 'equipos_estados_users_ordenes.id_equipo')
        ->select('equipos.*')
        ->where('equipos_estados_users_ordenes.id_estado', 15)->paginate(5);

        $estanteData = null;
        $usuarioData = null;
        $marcaData = null;
        $tipoequipoData = null;
        $estanteData = null;

        
        if($request->usuario || $request->marca ||  $request->tipoequipo){
            $equiposAbandonados = Equipo::join('equipos_estados_users_ordenes', 'equipos.id', '=', 'equipos_estados_users_ordenes.id_equipo')
            ->join('tipoequipos', 'equipos.id_tipoequipo', '=', 'tipoequipos.id')
            ->join('users', 'equipos.id_user', '=', 'users.id')
            ->join('marcas', 'equipos.id_marca', '=', 'marcas.id')
            ->join('seccionesestante', 'equipos.id_seccionesestante', '=', 'seccionesestante.id')
            ->join('estantes', 'seccionesestante.id_estante', '=', 'estantes.id')
            ->whereIn('users.id', [$request->get("usuario")])
            ->whereIn('marcas.id', [$request->get("marca")])
            ->whereIn('tipoequipos.id', [$request->tipoequipo])
            ->whereIn('estantes.id', [$request->estante])
            ->select('equipos.*')
            ->paginate(5); 
        }
            
        if($request->usuario){
            $usuarioData = User::where('id', $request->usuario)->select('id', 'name', 'lastname')->first();
        } 

        if($request->marca){
            $marcaData = Marca::where('id', $request->marca)->select('id', 'nombre')->first();
        } 

        if($request->tipoequipo){
            $tipoequipoData = TipoEquipo::where('id', $request->tipoequipo)->select('id', 'nombre')->first();
        } 

        if($request->estante){
            $estanteData = Estante::where('id', $request->estante)->select('id', 'nombre')->first();
        } 
        

        return view('registroabandono.index', compact('equiposAbandonados','usuarios', 'marcas', 'tiposequipo', 'estantes', 'usuarioData', 'marcaData', 'tipoequipoData', 'estanteData'));
    }

    public function createEquiposAbandonados(){
        return view('registroabandono.create');
    }

    public function getEquiposReparados(){
        $equiposReparados = DB::table('equipos_estados_users_ordenes')
        ->select('equipos_estados_users_ordenes.id_equipo')
        ->join('ordenesservicio', 'equipos_estados_users_ordenes.id_orden', '=', 'ordenesservicio.id')
        ->where('equipos_estados_users_ordenes.id_estado', 8)
        ->where('ordenesservicio.id_servicio', 2)
        ->where('ordenesservicio.finalizado', 1)
        ->get();

       $collection = new Collection;
       
        foreach ($equiposReparados as $equipo) {
            $equipos = Equipo::find($equipo->id_equipo)->select('id', 'serie','id_marca', 'id_user', 'id_tipoequipo', 'modelo')->where('id', $equipo->id_equipo)->with('marca:id,nombre','user:id,name', 'tipoequipo:id,nombre')->first();

            $estadoEquipo = DB::table('equipos')
            ->select('equipos_estados_users_ordenes.id_estado', 'equipos_estados_users_ordenes.created_at')
            ->join('equipos_estados_users_ordenes', 'equipos.id', 'equipos_estados_users_ordenes.id_equipo')
            ->where('equipos.id', $equipo->id_equipo)
            ->orderBy('equipos_estados_users_ordenes.created_at', 'desc')
            ->first();

            
            $estado = Estado::find($estadoEquipo->id_estado)->where('id', $estadoEquipo->id_estado)->first();

            $user = DB::table('equipos')->select('users.name', 'users.lastname')
            ->join('ordenesservicio','equipos.id', '=', 'ordenesservicio.id_equipo' )
            ->join('users_ordenes','ordenesservicio.id', '=', 'users_ordenes.id_orden' )
            ->join('users','users_ordenes.id_user', '=', 'users.id')
            ->where('users_ordenes.estadoAsignacion', 1)
            ->where('equipos.id', $equipo->id_equipo)->first();

            $fechaIngreso = DB::table('equipos')
            ->join('equipos_estados_users_ordenes','equipos.id', '=', 'equipos_estados_users_ordenes.id_equipo' )
            ->join('ordenesservicio','equipos_estados_users_ordenes.id_orden', '=', 'ordenesservicio.id')
            ->where('equipos.id', $equipo->id_equipo)
            ->where('equipos_estados_users_ordenes.id_estado', 1)
            ->select('equipos_estados_users_ordenes.created_at')
            ->first();
            
            $equipos->name = $user->name . " " . $user->lastname;
            $equipos->estado = $estado->nombre;
            $equipos->fechaIngreso = $fechaIngreso->created_at;
            $equipos->fechaReparacion = $estadoEquipo->created_at;
                
            if($estado->id == 8){
                $collection->push($equipos);
            }  

        }

        return DataTables()->collection($collection)->addColumn('action', function($row){
            return '
            <a href="#" class="btn-sm btn btn-warning detBtn" data-id="'.$row->id.'">Detalle</a>';
        })
        ->rawColumns(['action'])
        ->toJson();
    }

    public function getDetalleEquipoReparado($id){

        $collection = new Collection;
        
        $equipo = Equipo::findOrfail($id)->where('id', $id)->first();
        $ordenEquipo = $equipo->orden()->orderBy('id', 'desc')->limit(2)->pluck('id')->toArray();

        $equipoWithSeccion = Equipo::find($equipo->id)->select('id','id_seccionestante')->where('id', $equipo->id)->with('seccionEstante:id,nombre,id_estante')->first();

        $estante = Estante::findOrfail($equipoWithSeccion->seccionEstante->id_estante)->where('id',$equipoWithSeccion->seccionEstante->id_estante)->first();
        $comentarios = DB::table('equipos_estados_users_ordenes')
            ->select('equipos_estados_users_ordenes.created_at', 'equipos_estados_users_ordenes.id_estado', 'equipos_estados_users_ordenes.descripcion', 'users.name', 'users.lastname')
            ->join('users', 'equipos_estados_users_ordenes.id_user', 'users.id')
            ->whereIn('equipos_estados_users_ordenes.id_orden', $ordenEquipo)
            ->whereIn('equipos_estados_users_ordenes.id_estado', [1, 4, 9, 10, 11, 8, 5])
            ->get();


        $accesorios = $equipo->accesorios()->select('nombre')->get();

        $equipo->accesorios = $accesorios;    
        $equipo->comentarios = $comentarios;
        $equipo->estante = $estante->nombre;
        $equipo->seccionEstante = $equipoWithSeccion->seccionEstante->nombre;

        $collection->push($equipo);

         return DataTables()->collection($collection)->toJson();

        
    }


    public function registrarAbandono(Request $request){
        $this->validate($request, [
            'descripcion' => 'nullable',
            'idEquipos' => 'required',
        ],
        [
            'idEquipos.required' => 'Debes elegir uno o varios Equipos.'
        ]
        );

        $equipos = $request->get('idEquipos');
        $gerente = Auth::user();

        foreach ($equipos as $equipo) {
            $orden = Equipo::findOrfail($equipo)->orden()->where('finalizado', 1)->where('id_servicio', 2)->orderBy('fechafin', 'desc')
            ->first();         

            DB::table('equipos_estados_users_ordenes')->insert([
                'id_equipo' => $equipo,
                'id_estado' => 15,
                'id_user' => $gerente->id,
                'id_orden' => $orden->id,
                'descripcion' => $request->descripcion  
            ]);
            
        }

        return redirect()->route('registroabandono.index');
    }


    public function getDetalleEquipoAbandonado($id){

        $collection = new Collection;
        
        $equipo = Equipo::findOrfail($id)->where('id', $id)->first();
        $ordenEquipo = $equipo->orden()->orderBy('id', 'desc')->limit(2)->pluck('id');
        $ordenReparacion = $ordenEquipo->values()->get(0);
        $ordenDiagnostico = $ordenEquipo->values()->get(1);

        $presupuesto = DB::table('ordenservicios_presupuestos')
        ->where('id_orden', $ordenDiagnostico)
        ->pluck('presupuesto')
        ->first();

        $fechaFinReparacion = DB::table('equipos_estados_users_ordenes')
        ->where('equipos_estados_users_ordenes.id_orden', $ordenReparacion)
        ->where('equipos_estados_users_ordenes.id_estado', 8)
        ->pluck('created_at')
        ->first();

        $fechaIngreso = DB::table('equipos_estados_users_ordenes')
        ->where('equipos_estados_users_ordenes.id_orden', $ordenDiagnostico)
        ->where('equipos_estados_users_ordenes.id_estado', 1)
        ->pluck('created_at')
        ->first();

        $equipoWithSeccion = Equipo::find($equipo->id)->select('id','id_seccionestante')->where('id', $equipo->id)->with('seccionEstante:id,nombre,id_estante')->first();

        $estante = Estante::findOrfail($equipoWithSeccion->seccionEstante->id_estante)->where('id',$equipoWithSeccion->seccionEstante->id_estante)->first();
        $comentarios = DB::table('equipos_estados_users_ordenes')
            ->select('equipos_estados_users_ordenes.created_at', 'equipos_estados_users_ordenes.id_estado', 'equipos_estados_users_ordenes.descripcion', 'users.name', 'users.lastname')
            ->join('users', 'equipos_estados_users_ordenes.id_user', 'users.id')
            ->whereIn('equipos_estados_users_ordenes.id_orden', $ordenEquipo->toArray())
            ->whereIn('equipos_estados_users_ordenes.id_estado', [1, 4, 9, 10, 11, 8, 5, 15])
            ->get();


        $accesorios = $equipo->accesorios()->select('nombre')->get();

        $equipo->accesorios = $accesorios;    
        $equipo->comentarios = $comentarios;
        $equipo->estante = $estante->nombre;
        $equipo->seccionEstante = $equipoWithSeccion->seccionEstante->nombre;
        $equipo->presupuesto = $presupuesto;
        $equipo->fechaFinReparacion = $fechaFinReparacion;
        $equipo->fechaIngreso = $fechaIngreso;

        $collection->push($equipo);

         return DataTables()->collection($collection)->toJson();

        
    }


    public function getEquiposTerceros()
    {
        

        $equipos = Equipo::select('id', 'serie', 'modelo', 'id_marca', 'id_user')->with('marca:id,nombre','user:id,name')->get();

        foreach ($equipos as $key => $value) {
            $ordenEquipo = Equipo::findOrfail($value->id)->orden()->where('finalizado', 0)->first();
            if($ordenEquipo != null){
                $ultimoEstado = $ordenEquipo->estados()->first();
                if($ultimoEstado->id != 1 && $ultimoEstado->id != 5){
                    unset($equipos[$key]);
                    
                } else {
                    $value->fechaCompromiso = $ordenEquipo->fechacompromiso;

                    $fechaIngreso = DB::table('equipos')
                    ->select('equipos_estados_users_ordenes.created_at')
                    ->join('equipos_estados_users_ordenes', 'equipos.id', 'equipos_estados_users_ordenes.id_equipo')
                    ->where('equipos.id', $value->id)
                    ->where('equipos_estados_users_ordenes.id_estado', 1)
                    ->orderBy('equipos_estados_users_ordenes.created_at', 'desc')
                    ->first();

                    $servicio = Servicio::findOrfail($ordenEquipo->id_servicio)->where('id', $ordenEquipo->id_servicio)->first();

                    $value->fechaIngreso = $fechaIngreso->created_at;
                    $value->servicio = $servicio->nombre;

                }
            } else {
                unset($equipos[$key]);
            }
        }
        
         return DataTables()->collection($equipos)->addColumn('action', function($row){
            return '
            <a href="#" class="btn-sm btn btn-warning detBtn" data-id="'.$row->id.'">Detalle</a>';
        })
        ->rawColumns(['action'])
        ->toJson();
       
        

    }

    public function getEquiposTercerosRetirados()
    {
        

        $equipos = Equipo::select('id', 'serie', 'modelo', 'id_marca', 'id_user')->with('marca:id,nombre','user:id,name')->get();

        foreach ($equipos as $key => $value) {
            $ordenEquipo = Equipo::findOrfail($value->id)->orden()->where('finalizado', 0)->first();
            if($ordenEquipo != null){
                $ultimoEstado = $ordenEquipo->estados()->first();

                $estadosEquipo = DB::table('equipos')
                ->select('equipos_estados_users_ordenes.id_estado', 'equipos_estados_users_ordenes.descripcion')
                ->join('equipos_estados_users_ordenes', 'equipos.id', 'equipos_estados_users_ordenes.id_equipo')
                ->where('equipos.id', $value->id)
                ->orderBy('equipos_estados_users_ordenes.created_at', 'desc');

                $ultimoEstadoEquipoDescripcion =  $estadosEquipo->first();
                $anteUltimoEstadoEquipo = $estadosEquipo->limit(2)->skip(1)->first();
                

                if($ultimoEstado->id == 16 || ($anteUltimoEstadoEquipo->id_estado == 16 && $ultimoEstado->id != 16)){
                    $value->fechaCompromiso = $ordenEquipo->fechacompromiso;

                    $fechaIngreso = DB::table('equipos')
                    ->select('equipos_estados_users_ordenes.created_at')
                    ->join('equipos_estados_users_ordenes', 'equipos.id', 'equipos_estados_users_ordenes.id_equipo')
                    ->where('equipos.id', $value->id)
                    ->where('equipos_estados_users_ordenes.id_estado', 1)
                    ->orderBy('equipos_estados_users_ordenes.created_at', 'desc')
                    ->first();

                    $servicio = Servicio::findOrfail($ordenEquipo->id_servicio)->where('id', $ordenEquipo->id_servicio)->first();

                    $value->fechaIngreso = $fechaIngreso->created_at;
                    $value->servicio = $servicio->nombre;
                    $value->estado = $ultimoEstado->id;
                    $value->descripcion = $ultimoEstadoEquipoDescripcion->descripcion;

                    $user = DB::table('equipos')->select('users.name', 'users.lastname')
                    ->join('ordenesservicio','equipos.id', '=', 'ordenesservicio.id_equipo' )
                    ->join('users_ordenes','ordenesservicio.id', '=', 'users_ordenes.id_orden' )
                    ->join('users','users_ordenes.id_user', '=', 'users.id')
                    ->where('users_ordenes.estadoAsignacion', 1)
                    ->where('equipos.id', $value->id)->first();
            
                    $value->name = $user->name . " " . $user->lastname;
                    
                    
                } else {
                    unset($equipos[$key]);
                }
            } else {
                unset($equipos[$key]);
            }
        }
        
         return DataTables()->collection($equipos)->addColumn('action', function($row){
            return '
            <a href="#" class="btn-sm btn btn-warning detBtn" data-id="'.$row->id.'">Detalle</a>';
        })
        ->rawColumns(['action'])
        ->toJson();
       
        

    }

    public function getEquiposCliente($id){
        $equipos = Equipo::where('id_user', $id)->get();

        $collection = new Collection;
        
        if(empty($equipos)){
            return response()->json([
                'error' => 'El Cliente no cuenta con Equipos registrados.'
            ]);
        }
        
        foreach ($equipos as $equipo) {
            $seccionEstante = $equipo->seccionEstante;
            $estante = $seccionEstante->estante;
            $marca = $equipo->marca->nombre;
            $tipoEquipo = $equipo->tipoEquipo->nombre;
            

            $equipo->estante = $estante->nombre;
            $equipo->marca = $marca;
            $equipo->tipoEquipo = $tipoEquipo;
            $equipo->seccionEstante = $seccionEstante->nombre;   

            $ultimaOrdenServicioEquipo = OrdenServicio::where('id_equipo', $equipo->id)
            ->where('finalizado', 1)
            ->orderBy('created_at', 'desc')->first();

            
            $esValidaParaServicio = false;

            if(!empty($ultimaOrdenServicioEquipo)){
                $ultimoEstadoOrden = DB::table('equipos_estados_users_ordenes')
                ->where('id_orden', $ultimaOrdenServicioEquipo->id)
                ->orderBy('created_at', 'desc')
                ->first();

                if($ultimoEstadoOrden->id_estado == 12 || $ultimoEstadoOrden->id_estado == 13){
                    $esValidaParaServicio = true;
                }
            }

            
            $estaEnServicio = DB::table('ordenesservicio')
            ->where('id_equipo', $equipo->id)
            ->where('finalizado', 0)
            ->first();
            
            if((empty($estaEnServicio) && $esValidaParaServicio) || $equipo->orden->isEmpty()){
                $collection->push($equipo);
            }
            
        }

        return DataTables()->collection($collection)->toJson();
    }

    public function getMisEquiposDiagnostico(){
        $cliente = Auth::user()->id;
        $equipos = Equipo::where('id_user', $cliente)->paginate(5);

        return view('equipos.misequiposdiagnostico', compact('equipos'));
    }

    public function getMisEquiposReparacion(){

        return view('equipos.misequiposreparacion');
    }

}
