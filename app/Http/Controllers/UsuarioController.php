<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Equipo;
use App\Models\Estado;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;
use PDF;
use DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use App\Models\Estante;
use App\Models\OrdenServicio;
use Carbon\Carbon;



class UsuarioController extends Controller
{
    function __construct(){
        $this->middleware('permission:ver-usuario|crear-usuario|editar-usuario|borrar-usuario', ['only' => ['index']]);
        $this->middleware('permission:crear-usuario', ['only' => ['create', 'store']]); //Métodos asociados al permiso.
        $this->middleware('permission:editar-usuario', ['only' => ['edit', 'update']]); 
        $this->middleware('permission:borrar-usuario', ['only' => ['destroy']]); 
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $usuarios = User::paginate(5);
        $roles = Role::all();
        $name = $request->name;
        $lastname = $request->lastname;
        $email = $request->email;
        $rolusuario = $request->rol;
        

        //dd($request);
        if($request->name || $request->lastname || $request->email || $request->rol){
            if($request->rol){
                //dd($rolusuario);
                $usuarios = User::join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                ->where('name', 'like', '%'.$request->name .'%')
                ->where('lastname', 'like', '%'.$request->lastname .'%')
                ->where('email', 'like', '%'.$request->email .'%')
                ->Where('role_id', '=', $request->rol)->paginate(5);
            } else {
                $usuarios = User::where('name', 'like', '%'.$request->name .'%')
                ->where('lastname', 'like', '%'.$request->lastname .'%')
                ->where('email', 'like', '%'.$request->email .'%')->paginate(5);
            }
            
        }
        
        return view('usuarios.index', compact('usuarios', 'roles', 'name', 'lastname', 'email', 'rolusuario'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::pluck('name', 'name')->all();

        return view('usuarios.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        


        $this->validate($request, [
            'pepe.*' => 'required',
            'pepo.*' => 'required',
            'name' => 'required',
            'lastname' => 'required',
            'username' => 'required|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'roles' => 'required'
        ],
        [
            'pepe.*.required' => 'The pepe field is required.',
            'pepo.*.required' => 'The pepo field is required.'
            ]);

        


        

        //Una vez validado, interactuamos con los valores.

        $input = $request->all(); 
        $input['password'] = Hash::make($input['password']); //Creamos un Hash del password y almacenamos en la variable que tomó el request

        $user = User::create($input);
        $user->assignRole($request->input('roles'));

        return redirect()->route('usuarios.index');


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::pluck('name', 'name')->all();
        $userRole = $user->roles->pluck('name', 'name')->all();

        return view('usuarios.edit', compact('user', 'roles', 'userRole'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'lastname' => 'required',
            'username' => 'required|unique:users,username,'.$id,
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'same:confirm-password',
            'roles' => 'required'
        ]);

        $input = $request->all();

        if (!empty($input['password'])){
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = Arr::except($input, array('password'));
        }

        $user = User::find($id);
        $user->update($input);

        DB::table('model_has_roles')->where('model_id', $id)->delete();

        $user->assignRole($request->input('roles'));
        return redirect()->route('usuarios.index');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::find($id)->delete();
        return redirect()->route('usuarios.index');
    }

    public function pdfUsuarios(){
        //dd("hola");
        $users = User::all();
        $pdf = PDF::loadView('usuarios.pdf', compact('users'));
        return $pdf->stream();
    }


    public function getEquiposDiagnostico()
    {
        

        $equipos = Equipo::select('id', 'serie', 'modelo', 'id_marca', 'id_user')->with('marca:id,nombre','user:id,name')->get();

        foreach ($equipos as $key => $value) {
            $ordenEquipo = Equipo::findOrfail($value->id)->orden()->where('finalizado', 0)->first();
            if($ordenEquipo != null){
                $ultimoEstado = $ordenEquipo->estados()->first();
                if($ultimoEstado->id != 1){
                    unset($equipos[$key]);
                    
                } else {
                    $value->fechaCompromiso = $ordenEquipo->fechacompromiso;

                    $fechaIngreso = DB::table('equipos')
                    ->select('equipos_estados_users_ordenes.created_at')
                    ->join('equipos_estados_users_ordenes', 'equipos.id', 'equipos_estados_users_ordenes.id_equipo')
                    ->where('equipos.id', $value->id)
                    ->orderBy('equipos_estados_users_ordenes.created_at', 'desc')
                    ->first();

                    $value->fechaIngreso = $fechaIngreso->created_at;

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

    public function getTecnicos()
    {

        $tecnicos = User::with("roles")->whereHas("roles", function($q) {
                $q->whereIn("name", ["Admin","Tecnico"]);
            })->select('id', 'name', 'lastname')->get();
        return DataTables()->collection($tecnicos)->toJson();
    }

    public function verDiagnosticos(){
        
        return view('asignaciones.diagnosticos.index');
    }


    public function asignarDiagnostico(Request $request){

        $this->validate($request, [
            'idTecnico' => 'required',
            'idEquipos' => 'required',
        ],
        [
            'idTecnico.required' => 'Debes elegir un Técnico.',
            'idEquipos.required' => 'Debes elegir uno o varios Equipos.'
        ]
        );
        //dd($request->all());
        $tecnico = User::find($request->get('idTecnico'));
        $equipos = $request->get('idEquipos');
        $estado = Estado::findOrfail(2);
        

        $gerente = Auth::user();

        foreach ($equipos as $equipo) {
            $orden = Equipo::findOrfail($equipo)->orden()->where('finalizado', 0)->first();

            DB::table('equipos_estados_users_ordenes')->insert([
                'id_equipo' => $equipo,
                'id_estado' => $estado->id,
                'id_user' => $gerente->id,
                'id_orden' => $orden->id,
            ]);

            DB::table('users_ordenes')->insert([
                'estadoAsignacion' => true,
                'id_user' => $tecnico->id,
                'id_orden' => $orden->id,
            ]);
            
        }

        return redirect()->route('diagnosticos');

    }

    public function verAsignacionesDiagnosticoRealizadas(){
        return view('asignaciones.diagnosticos.asignacionesRealizadas');
    }

    public function getAsignacionesDiagnosticoRealizadas(){
        //dd($equipos = Equipo::findOrfail('4')->where('id', 4)->first());

       $equiposAsignados = DB::table('users_ordenes')
       ->join('ordenesservicio', 'users_ordenes.id_orden', '=', 'ordenesservicio.id')
       ->join('equipos', 'ordenesservicio.id_equipo', '=', 'equipos.id')
       ->join('equipos_estados_users_ordenes', 'equipos.id', '=', 'equipos_estados_users_ordenes.id_equipo')
       ->where('estadoAsignacion', 1)
       ->where('equipos_estados_users_ordenes.id_estado', 2)
       ->get();

       $collection = new Collection;
       
        foreach ($equiposAsignados as $equipo) {
            $equipos = Equipo::find($equipo->id_equipo)->select('id', 'serie','id_marca', 'id_user')->where('id', $equipo->id_equipo)->with('marca:id,nombre','user:id,name')->first();

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
            
            $equipos->name = $user->name . " " . $user->lastname;
            $equipos->estado = $estado->nombre;
                
            if($estado->id >= 2 && $estado->id <= 4 || $estado->id == 10){
                $collection->push($equipos);
            }  

        }

        return DataTables()->collection($collection)->addColumn('action', function($row){
            return '
            <div style="text-align:center">
            <a href="#" class="btn-sm btn btn-warning detBtn" data-id="'.$row->id.'">Detalle</a>
            <a href="#" class="btn-sm btn btn-danger reBtn" data-id="'.$row->id.'">Reasignar</a>
            <a href="#" class="btn-sm btn btn-success preBtn" data-id="'.$row->id.'">Presupuestar</a>
            </div>';
        })
        ->rawColumns(['action'])
        ->toJson();

    }

    public function getMisAsignacionesDiagnostico(){

        $userActual = Auth::user()->id;
        $equiposAsignados = DB::table('users_ordenes')
        ->select('equipos_estados_users_ordenes.id_equipo')
        ->join('ordenesservicio', 'users_ordenes.id_orden', '=', 'ordenesservicio.id')
        ->join('equipos', 'ordenesservicio.id_equipo', '=', 'equipos.id')
        ->join('equipos_estados_users_ordenes', 'equipos.id', '=', 'equipos_estados_users_ordenes.id_equipo')
        ->where('estadoAsignacion', 1)
        ->where('users_ordenes.id_user', $userActual)
        ->where('ordenesservicio.finalizado', 0)
        ->groupBy('equipos_estados_users_ordenes.id_equipo')
        ->get();
        

        //->whereIn('equipos_estados_users_ordenes.id_estado', [1, 2, 3])

        $collection = new Collection;
        
         foreach ($equiposAsignados as $equipo) {   
            $estadoEquipo = DB::table('equipos')
            ->select('equipos_estados_users_ordenes.id_estado')
            ->join('equipos_estados_users_ordenes', 'equipos.id', 'equipos_estados_users_ordenes.id_equipo')
            ->where('equipos.id', $equipo->id_equipo)
            ->orderBy('equipos_estados_users_ordenes.created_at', 'desc')
            ->first();
            
            // dd($estadoEquipo->id_estado);
            if($estadoEquipo->id_estado == '2' || $estadoEquipo->id_estado == '3' || $estadoEquipo->id_estado == '9'){
                $equipos = Equipo::find($equipo->id_equipo)->select('id', 'serie','id_marca','modelo', 'id_user', 'id_seccionestante')->where('id', $equipo->id_equipo)->with('marca:id,nombre','user:id,name', 'seccionEstante:id,nombre,id_estante')->first();   
    
                $estante = Estante::findOrfail($equipos->seccionEstante->id_estante)->where('id',$equipos->seccionEstante->id_estante)->first();
                   
                $equipos->estante = $estante->nombre;

                // dd($estadoEquipo->id_estado);
                $estado = Estado::find($estadoEquipo->id_estado)->where('id', $estadoEquipo->id_estado)->first();
                
                // dd($estado->nombre);

                $equipos->estado = $estado->nombre;

                $collection->push($equipos);
            }

            }
            
            return DataTables()->collection($collection)->addColumn('action', function($row){
                return '
                <a href="#" class="btn-sm btn btn-warning detBtn" data-id="'.$row->id.'">Detalle</a>
                <a href="#" class="btn-sm btn btn-danger reBtn" data-id="'.$row->id.'">Reasignar</a>
                <a href="#" class="btn-sm btn btn-success finBtn" data-id="'.$row->id.'">Finalizar</a>';
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function verMisAsignacionesRealizadas(){
        return view('asignaciones.diagnosticos.vermisdiagnosticosasignados');
    }


    public function getAsignacionDiagnostico($id){

        $collection = new Collection;

        $equipo = Equipo::findOrfail($id)->where('id', $id)->first();
        $userActual = Auth::user()->id;
        $userIsAdmin = Auth::user()->hasRole('Admin');

        $idEstadoEquipo = DB::table('equipos')
            ->select('equipos_estados_users_ordenes.id_estado')
            ->join('equipos_estados_users_ordenes', 'equipos.id', 'equipos_estados_users_ordenes.id_equipo')
            ->where('equipos.id', $id)
            ->orderBy('equipos_estados_users_ordenes.created_at', 'desc')
            ->first();

        $accesorios = $equipo->accesorios()->select('nombre')->get();
        $orden = $equipo->orden()->where('finalizado', 0)->first();

        if($orden || $userIsAdmin){
            $ordenEquipo = $equipo->orden()->first();


            $fechaIngreso = DB::table('equipos')
            ->join('equipos_estados_users_ordenes','equipos.id', '=', 'equipos_estados_users_ordenes.id_equipo' )
            ->join('ordenesservicio','equipos_estados_users_ordenes.id_orden', '=', 'ordenesservicio.id')
            ->where('equipos.id', $id)
            ->where('equipos_estados_users_ordenes.id_estado', 1)
            ->select('equipos_estados_users_ordenes.created_at')
            ->first();
    
            $comentarios = DB::table('equipos_estados_users_ordenes')
            ->select('equipos_estados_users_ordenes.created_at', 'equipos_estados_users_ordenes.id_estado', 'equipos_estados_users_ordenes.descripcion', 'users.name', 'users.lastname')
            ->join('users', 'equipos_estados_users_ordenes.id_user', 'users.id')
            ->join('users_ordenes', 'equipos_estados_users_ordenes.id_orden', 'users_ordenes.id_orden')
            ->where('users_ordenes.estadoAsignacion', 1)
            ->where('users_ordenes.id_orden', $ordenEquipo->id)
            ->whereIn('equipos_estados_users_ordenes.id_estado', [1, 4, 9, 10])
            ->get();

            
            $equipo->accesorios = $accesorios;    
            $equipo->fechacompromiso = $ordenEquipo->fechacompromiso;
            $equipo->fechaIngreso = $fechaIngreso;
            $equipo->estado = $idEstadoEquipo->id_estado; 
            $equipo->comentarios = $comentarios;

            $ordenPresupuestado = $equipo->orden()->where('finalizado', 1)->first();

            if($ordenPresupuestado){      
                $orden_presupuesto = DB::table('ordenservicios_presupuestos')
                ->where('id_orden', $ordenPresupuestado->id)->first();
            }

            if(!empty($orden_presupuesto)){
                $equipo->presupuesto = $orden_presupuesto->presupuesto;
            }
    
            $collection->push($equipo);

            return DataTables()->collection($collection)->toJson();
        }
        }
        
        
       


    public function iniciarDiagnostico(Request $request){
        $userActual = Auth::user()->id;
        
        $equipo = Equipo::findOrfail($request->get('id'))->where('id',  $request->get('id'))->first();
        $estado = Estado::findOrfail(3);
        $orden = Equipo::findOrfail($request->get('id'))->orden()->where('finalizado', 0)->first();

        DB::table('equipos_estados_users_ordenes')->insert([
                        'id_equipo' => $equipo->id,
                        'id_estado' => $estado->id,
                        'id_user' => $userActual,
                        'id_orden' => $orden->id,
        ]);

        return response()->json([
            'success' => 'El Equipo ha entrado en un estado de Diagnóstico'
        ]);
    }

    public function reasignarDiagnostico(Request $request){
        
        $equipo = Equipo::findOrfail($request->get('idEquipo'))->where('id',  $request->get('idEquipo'))->first();
        $estado = Estado::findOrfail(9);
        $orden = Equipo::findOrfail($request->get('idEquipo'))->orden()->where('finalizado', 0)->first();


        $userActualDiagnosticoEquipo = DB::table('users_ordenes')
            ->where('estadoAsignacion', 1)
            ->where('id_orden', $orden->id)
            ->select('id_user')
            ->pluck('id_user')
            ->first();

       

        if($userActualDiagnosticoEquipo == $request->get('idTecnico')){
                return response()->json([
                    'error' => 'El Equipo esta asignado a usted mismo. Debe elegir otro técnico.'
                ]);
            
        }
        
        $userActual = Auth::user()->id;

        DB::table('equipos_estados_users_ordenes')->insert([
                        'id_equipo' => $equipo->id,
                        'id_estado' => $estado->id,
                        'id_user' => $userActual,
                        'id_orden' => $orden->id,
                        'descripcion' => $request->get('detalle'),
        ]);

        DB::table('users_ordenes')
        ->where('id_user', $userActualDiagnosticoEquipo)
        ->where('id_orden', $orden->id)
        ->update(['estadoAsignacion' => 0]);
        
        DB::table('users_ordenes')->insert([
            'estadoAsignacion' => 1,
            'id_user' => $request->get('idTecnico'),
            'id_orden' => $orden->id,
        ]);

        return response()->json([
            'success' => 'El Equipo ha sido Reasignado'
        ]);
    }

    public function getTecnicosReasignacion($id){

        $estadoEquipo = DB::table('equipos')
        ->select('equipos_estados_users_ordenes.id_estado')
        ->join('equipos_estados_users_ordenes', 'equipos.id', 'equipos_estados_users_ordenes.id_equipo')
        ->where('equipos.id', $id)
        ->orderBy('equipos_estados_users_ordenes.created_at', 'desc')
        ->first();

        if($estadoEquipo->id_estado == 4){
            return response()->json([
                'error' => 'El Equipo no puede ser reasignado, su diagnóstico ha finalizado.'
            ]);
        }

        if($estadoEquipo->id_estado == 10){
            return response()->json([
                'error' => 'El Equipo no puede ser reasignado, su diagnóstico ha sido presupuestado.'
            ]);
        }
        
        if($estadoEquipo->id_estado == 8){
            return response()->json([
                'error' => 'El Equipo no puede ser reasignado, su reparación ha sido realizada.'
            ]);
        }
        
        $tecnicos = User::with("roles")->whereHas("roles", function($q) {
            $q->whereIn("name", ["Admin","Tecnico"]);
        })->select('id', 'name', 'lastname')->get();
        $userActual = Auth::user()->id;

        $tecnicos->prepend(['user_id' => $userActual]);
        
        return DataTables()->collection($tecnicos)->toJson();
    }

    public function getFinalizarDiagnostico($id){

        $estadoEquipo = DB::table('equipos')
        ->select('equipos_estados_users_ordenes.id_estado')
        ->join('equipos_estados_users_ordenes', 'equipos.id', 'equipos_estados_users_ordenes.id_equipo')
        ->where('equipos.id', $id)
        ->orderBy('equipos_estados_users_ordenes.created_at', 'desc')
        ->first();

        if($estadoEquipo->id_estado !== 3){
            return response()->json([
                'error' => 'Su Equipo debe pasar por una etapa de inicio de Diagnóstico, inicielo e intente nuevamente.'
            ]);
        }

        return; 
    }

    public function finalizarDiagnostico(Request $request){ 

            $equipo = Equipo::findOrfail($request->get('idEquipo'))->where('id',  $request->get('idEquipo'))->first();
            $userActual = Auth::user()->id;
            $estado = Estado::findOrfail(4)->where('id', 4)->first();
            $orden = Equipo::findOrfail($request->get('idEquipo'))->orden()->where('finalizado', 0)->first();
            $fechaFin = Carbon::now();
    
            DB::table('equipos_estados_users_ordenes')->insert([
                            'id_equipo' => $equipo->id,
                            'id_estado' => $estado->id,
                            'id_user' => $userActual,
                            'id_orden' => $orden->id,
                            'descripcion' => $request->get('detalle'),
            ]);
            
            DB::table('ordenesservicio')
            ->where('id', $orden->id)
            ->update(['finalizado' => 1, 'fechafin' => $fechaFin]);

            return response()->json([
                'success' => 'El Diagnóstico ha sido registrado!'
            ]);

    }

    public function getDetalleEquipoDiagnostico($id){

        $collection = new Collection;
        
        $equipo = Equipo::findOrfail($id)->where('id', $id)->first();
        $orden = Equipo::findOrfail($id)->orden()->where('finalizado', 0)->first();

        $equipoWithSeccion = Equipo::find($equipo->id)->select('id','id_seccionestante')->where('id', $equipo->id)->with('seccionEstante:id,nombre,id_estante')->first();

        $estante = Estante::findOrfail($equipoWithSeccion->seccionEstante->id_estante)->where('id',$equipoWithSeccion->seccionEstante->id_estante)->first();

        $comentario = DB::table('equipos_estados_users_ordenes')
        ->select('equipos_estados_users_ordenes.created_at', 'equipos_estados_users_ordenes.id_estado', 'equipos_estados_users_ordenes.descripcion', 'users.name', 'users.lastname')
        ->join('users', 'equipos_estados_users_ordenes.id_user', 'users.id')
        ->where('equipos_estados_users_ordenes.id_orden', $orden->id)
        ->where('equipos_estados_users_ordenes.id_estado', 1)
        ->first();

        $accesorios = $equipo->accesorios()->select('nombre')->get();

        $equipo->accesorios = $accesorios;    
        $equipo->comentario = $comentario;
        $equipo->estante = $estante->nombre;
        $equipo->seccionEstante = $equipoWithSeccion->seccionEstante->nombre;

        $collection->push($equipo);

         return DataTables()->collection($collection)->toJson();

        
    }

    public function estaDiagnosticado($id){        

        $estadoEquipo = DB::table('equipos')
        ->select('equipos_estados_users_ordenes.id_estado')
        ->join('equipos_estados_users_ordenes', 'equipos.id', 'equipos_estados_users_ordenes.id_equipo')
        ->where('equipos.id', $id)
        ->orderBy('equipos_estados_users_ordenes.created_at', 'desc')
        ->first();


        if(!empty($estadoEquipo)){
            if($estadoEquipo->id_estado !== 4){
                if($estadoEquipo->id_estado >= 1 && $estadoEquipo->id_estado < 4 || $estadoEquipo->id_estado == 9){
                    return response()->json([
                        'error' => 'El Equipo debe estar Diagnosticado para realizar su presupuesto.'
                    ]);
                } else if($estadoEquipo->id_estado == 10){
                    return response()->json([
                        'error' => 'El Equipo ya ha sido presupuestado.'
                    ]);
                }
            }
        }
        

    }


    public function presupuestarEquipo(Request $request){
        $equipo = Equipo::findOrfail($request->get('idEquipo'))->where('id', $request->get('idEquipo'))->first();
        $orden = Equipo::findOrfail($request->get('idEquipo'))->orden()->where('finalizado', 1)->first();
        $userActual = Auth::user()->id;

        DB::table('ordenservicios_presupuestos')
        ->insert(['presupuesto' => $request->get('presupuesto'), 'id_orden' => $orden->id, 'presupuestado' => true]);

        DB::table('equipos_estados_users_ordenes')->insert([
            'id_equipo' => $equipo->id,
            'id_estado' => 10,
            'id_user' => $userActual,
            'id_orden' => $orden->id,
            'descripcion' => $request->get('detalle'),
        ]);

        return response()->json([
            'success' => 'El Presupuesto ha sido registrado.!'
        ]);

    }
    public function aceptarPresupuesto(Request $request){
        $equipo = Equipo::findOrfail($request->get('idEquipo'))->where('id', $request->get('idEquipo'))->first();
        $orden = Equipo::findOrfail($request->get('idEquipo'))->orden()->where('finalizado', 1)->first();
        $userActual = Auth::user()->id;


        DB::table('equipos_estados_users_ordenes')->insert([
            'id_equipo' => $equipo->id,
            'id_estado' => 11,
            'id_user' => $userActual,
            'id_orden' => $orden->id,
        ]);

        DB::table('equipos_estados_users_ordenes')->insert([
            'id_equipo' => $equipo->id,
            'id_estado' => 11,
            'id_user' => $userActual,
            'id_orden' => $orden->id,
        ]);

        $nuevaOrdenReparacion = new OrdenServicio();
        $nuevaOrdenReparacion->fechacompromiso = $request->get('fechacompaceptacion');
        $nuevaOrdenReparacion->finalizado = 0;
        $nuevaOrdenReparacion->id_equipo = $equipo->id;
        $nuevaOrdenReparacion->id_servicio = 2;
        $nuevaOrdenReparacion->save();

        DB::table('equipos_estados_users_ordenes')->insert([
            'id_equipo' => $equipo->id,
            'id_estado' => 5,
            'id_user' => $userActual,
            'id_orden' => $nuevaOrdenReparacion->id,
            'descripcion' => $request->get('detalleaceptpre')
        ]);


        return response()->json([
            'success' => 'El presupuesto ha sido Aceptado.!'
        ]);

    }

    public function verReparacion(){
        
        return view('asignaciones.reparaciones.index');
    }

    public function getEquiposReparacion(){
        $equipos = Equipo::select('id', 'serie', 'modelo', 'id_marca', 'id_user')->with('marca:id,nombre','user:id,name')->get();

        foreach ($equipos as $key => $value) {
            //Comenzar a utilizar el criterio de "where id_servicio = 1 si es diag, = 2 si es rep para clasificar."
            $ordenEquipo = Equipo::findOrfail($value->id)->orden()->where('finalizado', 0)->where('id_servicio', 2)->first();
            if($ordenEquipo != null){
                $ultimoEstado = $ordenEquipo->estados()->first();
                if($ultimoEstado->id != 5){
                    unset($equipos[$key]);
                    
                } else {
                    $value->fechaCompromiso = $ordenEquipo->fechacompromiso;

                    $fechaIngreso = DB::table('equipos')
                    ->select('equipos_estados_users_ordenes.created_at')
                    ->join('equipos_estados_users_ordenes', 'equipos.id', 'equipos_estados_users_ordenes.id_equipo')
                    ->where('equipos.id', $value->id)
                    ->orderBy('equipos_estados_users_ordenes.created_at', 'desc')
                    ->first();

                    $value->fechaIngreso = $fechaIngreso->created_at;

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

    public function getDetalleEquipoReparacion($id){

        $collection = new Collection;
        
        $equipo = Equipo::findOrfail($id)->where('id', $id)->first();
        $orden = Equipo::findOrfail($id)->orden()->where('finalizado', 1)->first();
        $ordenEquipo = Equipo::findOrfail($id)->orden()->where('finalizado', 0)->where('id_servicio', 2)->first();
        $equipoWithSeccion = Equipo::find($equipo->id)->select('id','id_seccionestante')->where('id', $equipo->id)->with('seccionEstante:id,nombre,id_estante')->first();

        $estante = Estante::findOrfail($equipoWithSeccion->seccionEstante->id_estante)->where('id',$equipoWithSeccion->seccionEstante->id_estante)->first();
        $comentarios = DB::table('equipos_estados_users_ordenes')
            ->select('equipos_estados_users_ordenes.created_at', 'equipos_estados_users_ordenes.id_estado', 'equipos_estados_users_ordenes.descripcion', 'users.name', 'users.lastname')
            ->join('users', 'equipos_estados_users_ordenes.id_user', 'users.id')
            ->whereIn('equipos_estados_users_ordenes.id_orden', [$orden->id, $ordenEquipo->id])
            ->whereIn('equipos_estados_users_ordenes.id_estado', [1, 4, 9, 10, 5])
            ->get();


        $accesorios = $equipo->accesorios()->select('nombre')->get();

        $equipo->accesorios = $accesorios;    
        $equipo->comentarios = $comentarios;
        $equipo->estante = $estante->nombre;
        $equipo->seccionEstante = $equipoWithSeccion->seccionEstante->nombre;

        $collection->push($equipo);

         return DataTables()->collection($collection)->toJson();

        
    }

    public function asignarReparacion(Request $request){
        $this->validate($request, [
            'idTecnico' => 'required',
            'idEquipos' => 'required',
        ],
        [
            'idTecnico.required' => 'Debes elegir un Técnico.',
            'idEquipos.required' => 'Debes elegir uno o varios Equipos.'
        ]
        );

        $tecnico = User::find($request->get('idTecnico'));
        $equipos = $request->get('idEquipos');
        $estado = Estado::findOrfail(6);
        

        $gerente = Auth::user();

        foreach ($equipos as $equipo) {
            $orden = Equipo::findOrfail($equipo)->orden()->where('finalizado', 0)->where('id_servicio', 2)->first();

            DB::table('equipos_estados_users_ordenes')->insert([
                'id_equipo' => $equipo,
                'id_estado' => $estado->id,
                'id_user' => $gerente->id,
                'id_orden' => $orden->id,
            ]);

            DB::table('users_ordenes')->insert([
                'estadoAsignacion' => true,
                'id_user' => $tecnico->id,
                'id_orden' => $orden->id,
            ]);
            
        }

        return redirect()->route('reparaciones');
    }

    public function getAsignacionesReparacionRealizadas(){
        //dd($equipos = Equipo::findOrfail('4')->where('id', 4)->first());

       $equiposAsignados = DB::table('users_ordenes')
       ->join('ordenesservicio', 'users_ordenes.id_orden', '=', 'ordenesservicio.id')
       ->join('equipos', 'ordenesservicio.id_equipo', '=', 'equipos.id')
       ->join('equipos_estados_users_ordenes', 'equipos.id', '=', 'equipos_estados_users_ordenes.id_equipo')
       ->where('estadoAsignacion', 1)
       ->where('equipos_estados_users_ordenes.id_estado', 6)
       ->where('ordenesservicio.id_servicio', 2)
       ->get();

       $collection = new Collection;
       
        foreach ($equiposAsignados as $equipo) {
            $equipos = Equipo::find($equipo->id_equipo)->select('id', 'serie','id_marca', 'id_user')->where('id', $equipo->id_equipo)->with('marca:id,nombre','user:id,name')->first();

            $estadoEquipo = DB::table('equipos')
            ->select('equipos_estados_users_ordenes.id_estado')
            ->join('equipos_estados_users_ordenes', 'equipos.id', 'equipos_estados_users_ordenes.id_equipo')
            ->where('equipos.id', $equipo->id_equipo)
            ->orderBy('equipos_estados_users_ordenes.created_at', 'desc')
            ->first();

            
            $estado = Estado::find($estadoEquipo->id_estado)->where('id', $estadoEquipo->id_estado)->first();

            
            $orden = $equipos->orden()->where('id_servicio', 2)->first();

            $user = DB::table('equipos')->select('users.name', 'users.lastname')
            ->join('ordenesservicio','equipos.id', '=', 'ordenesservicio.id_equipo' )
            ->join('users_ordenes','ordenesservicio.id', '=', 'users_ordenes.id_orden' )
            ->join('users','users_ordenes.id_user', '=', 'users.id')
            ->where('users_ordenes.estadoAsignacion', 1)
            ->where('equipos.id', $equipo->id_equipo)
            ->where('users_ordenes.id_orden', $orden->id)
            ->first();

            
            $equipos->name = $user->name . " " . $user->lastname;
            $equipos->estado = $estado->nombre;
                
            if($estado->id >= 6 && $estado->id <= 9){
                $collection->push($equipos);
            }  

        }

        return DataTables()->collection($collection)->addColumn('action', function($row){
            return '
            <div style="text-align:center">
            <a href="#" class="btn-sm btn btn-warning detBtn" data-id="'.$row->id.'">Detalle</a>
            <a href="#" class="btn-sm btn btn-danger reBtn" data-id="'.$row->id.'">Reasignar</a>
            </div>';
        })
        ->rawColumns(['action'])
        ->toJson();
    }

    public function verAsignacionesReparacionRealizadas(){
        return view('asignaciones.reparaciones.asignacionesRealizadas');
    }

    

    public function getAsignacionReparacion($id){

        $collection = new Collection;

        $equipo = Equipo::findOrfail($id)->where('id', $id)->first();
        $userIsAdmin = Auth::user()->hasRole('Admin');

        $idEstadoEquipo = DB::table('equipos')
            ->select('equipos_estados_users_ordenes.id_estado')
            ->join('equipos_estados_users_ordenes', 'equipos.id', 'equipos_estados_users_ordenes.id_equipo')
            ->where('equipos.id', $id)
            ->orderBy('equipos_estados_users_ordenes.created_at', 'desc')
            ->first();

        $accesorios = $equipo->accesorios()->select('nombre')->get();
        $orden = $equipo->orden()->where('id_servicio', 2)->first();

        if($orden || $userIsAdmin){
            $ordenEquipo = $equipo->orden()->first();       //Tener en cuenta para cuando se habiliten nuevas ordenes, cambiar esta consulta a una ordenada de forma desc respecto a fecha creacion y con id_servicio 1.

            $fechaIngreso = DB::table('equipos')
            ->join('equipos_estados_users_ordenes','equipos.id', '=', 'equipos_estados_users_ordenes.id_equipo' )
            ->join('ordenesservicio','equipos_estados_users_ordenes.id_orden', '=', 'ordenesservicio.id')
            ->where('equipos.id', $id)
            ->where('equipos_estados_users_ordenes.id_estado', 6)
            ->select('equipos_estados_users_ordenes.created_at')
            ->first();

            $comentarios = DB::table('equipos_estados_users_ordenes')
            ->select('equipos_estados_users_ordenes.created_at', 'equipos_estados_users_ordenes.id_estado', 'equipos_estados_users_ordenes.descripcion', 'users.name', 'users.lastname')
            ->join('users', 'equipos_estados_users_ordenes.id_user', 'users.id')
            ->whereIn('equipos_estados_users_ordenes.id_orden', [$orden->id, $ordenEquipo->id])
            ->whereIn('equipos_estados_users_ordenes.id_estado', [1, 4, 8, 9, 10, 5])
            ->get();

            
            $equipo->accesorios = $accesorios;    
            $equipo->fechacompromiso = $orden->fechacompromiso;
            $equipo->fechaIngreso = $fechaIngreso;
            $equipo->estado = $idEstadoEquipo->id_estado; 
            $equipo->comentarios = $comentarios;

    
            $collection->push($equipo);

            return DataTables()->collection($collection)->toJson();
        }
    }

    public function reasignarReparacion(Request $request){
        
        $equipo = Equipo::findOrfail($request->get('idEquipo'))->where('id',  $request->get('idEquipo'))->first();
        $estado = Estado::findOrfail(9);
        $orden = Equipo::findOrfail($request->get('idEquipo'))->orden()->where('finalizado', 0)->where('id_servicio', 2)->first();


        $userActualReparacionEquipo = DB::table('users_ordenes')
            ->where('estadoAsignacion', 1)
            ->where('id_orden', $orden->id)
            ->select('id_user')
            ->pluck('id_user')
            ->first();

        if($userActualReparacionEquipo == $request->get('idTecnico')){
            return response()->json([
                'error' => 'El Equipo esta asignado a usted mismo. Debe elegir otro técnico.'
            ]);
        }
        
        $userActual = Auth::user()->id;

        DB::table('equipos_estados_users_ordenes')->insert([
                        'id_equipo' => $equipo->id,
                        'id_estado' => $estado->id,
                        'id_user' => $userActual,
                        'id_orden' => $orden->id,
                        'descripcion' => $request->get('detalle'),
        ]);

        DB::table('users_ordenes')
        ->where('id_user', $userActualReparacionEquipo)
        ->where('id_orden', $orden->id)
        ->update(['estadoAsignacion' => 0]);
        
        DB::table('users_ordenes')->insert([
            'estadoAsignacion' => 1,
            'id_user' => $request->get('idTecnico'),
            'id_orden' => $orden->id,
        ]);

        return response()->json([
            'success' => 'El Equipo ha sido Reasignado'
        ]);
    }

    public function verMisAsignacionesReparacionRealizadas(){
        return view('asignaciones.reparaciones.vermisreparacionesasignadas');
    }
        
    public function getMisAsignacionesReparacion(){

        $userActual = Auth::user()->id;
        $equiposAsignados = DB::table('users_ordenes')
        ->select('equipos_estados_users_ordenes.id_equipo')
        ->join('ordenesservicio', 'users_ordenes.id_orden', '=', 'ordenesservicio.id')
        ->join('equipos', 'ordenesservicio.id_equipo', '=', 'equipos.id')
        ->join('equipos_estados_users_ordenes', 'equipos.id', '=', 'equipos_estados_users_ordenes.id_equipo')
        ->where('estadoAsignacion', 1)
        ->where('users_ordenes.id_user', $userActual)
        ->where('ordenesservicio.finalizado', 0)
        ->where('ordenesservicio.id_servicio', 2)
        ->groupBy('equipos_estados_users_ordenes.id_equipo')
        ->get();
        

        //->whereIn('equipos_estados_users_ordenes.id_estado', [1, 2, 3])

        $collection = new Collection;
        
         foreach ($equiposAsignados as $equipo) {   
            $estadoEquipo = DB::table('equipos')
            ->select('equipos_estados_users_ordenes.id_estado')
            ->join('equipos_estados_users_ordenes', 'equipos.id', 'equipos_estados_users_ordenes.id_equipo')
            ->where('equipos.id', $equipo->id_equipo)
            ->orderBy('equipos_estados_users_ordenes.created_at', 'desc')
            ->first();
            
            // dd($estadoEquipo->id_estado);
            if($estadoEquipo->id_estado == '6' || $estadoEquipo->id_estado == '7' || $estadoEquipo->id_estado == '9'){
                $equipos = Equipo::find($equipo->id_equipo)->select('id', 'serie','id_marca','modelo', 'id_user', 'id_seccionestante')->where('id', $equipo->id_equipo)->with('marca:id,nombre','user:id,name', 'seccionEstante:id,nombre,id_estante')->first();   
    
                $estante = Estante::findOrfail($equipos->seccionEstante->id_estante)->where('id',$equipos->seccionEstante->id_estante)->first();
                   
                $equipos->estante = $estante->nombre;

                // dd($estadoEquipo->id_estado);
                $estado = Estado::find($estadoEquipo->id_estado)->where('id', $estadoEquipo->id_estado)->first();
                
                // dd($estado->nombre);

                $equipos->estado = $estado->nombre;

                $collection->push($equipos);
            }

            }
            
            return DataTables()->collection($collection)->addColumn('action', function($row){
                return '
                <a href="#" class="btn-sm btn btn-warning detBtn" data-id="'.$row->id.'">Detalle</a>
                <a href="#" class="btn-sm btn btn-danger reBtn" data-id="'.$row->id.'">Reasignar</a>
                <a href="#" class="btn-sm btn btn-success finBtn" data-id="'.$row->id.'">Finalizar</a>';
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function iniciarReparacion(Request $request){
        $userActual = Auth::user()->id;
        
        $equipo = Equipo::findOrfail($request->get('id'))->where('id',  $request->get('id'))->first();
        $estado = Estado::findOrfail(7);
        $orden = Equipo::findOrfail($request->get('id'))->orden()->where('finalizado', 0)->where('id_servicio', 2)->first();

        DB::table('equipos_estados_users_ordenes')->insert([
                        'id_equipo' => $equipo->id,
                        'id_estado' => $estado->id,
                        'id_user' => $userActual,
                        'id_orden' => $orden->id,
        ]);

        return response()->json([
            'success' => 'El Equipo ha entrado en un estado de Reparación'
        ]);
    }

    public function getFinalizarReparacion($id){

        $estadoEquipo = DB::table('equipos')
        ->select('equipos_estados_users_ordenes.id_estado')
        ->join('equipos_estados_users_ordenes', 'equipos.id', 'equipos_estados_users_ordenes.id_equipo')
        ->where('equipos.id', $id)
        ->orderBy('equipos_estados_users_ordenes.created_at', 'desc')
        ->first();

        if($estadoEquipo->id_estado !== 7){
            return response()->json([
                'error' => 'Su Equipo debe pasar por una etapa de inicio de Reparación, inicielo e intente nuevamente.'
            ]);
        }

        return; 
    }

    public function finalizarReparacion(Request $request){ 


        $equipo = Equipo::findOrfail($request->get('idEquipo'))->where('id',  $request->get('idEquipo'))->first();
        $userActual = Auth::user()->id;
        $estado = Estado::findOrfail(8)->where('id', 8)->first();
        $orden = Equipo::findOrfail($request->get('idEquipo'))->orden()->where('finalizado', 0)->where('id_servicio', 2)->first();
        $fechaFin = Carbon::now();

        DB::table('equipos_estados_users_ordenes')->insert([
                        'id_equipo' => $equipo->id,
                        'id_estado' => $estado->id,
                        'id_user' => $userActual,
                        'id_orden' => $orden->id,
                        'descripcion' => $request->get('detalle'),
        ]);

        if($request->get("repuestos")){
            foreach ($request->get("repuestos") as $cantidad) {
                DB::table('repuestos_ordenes')->insert([
                    'id_orden' => $orden->id,
                    'id_repuesto' => $cantidad,
                ]);
            }
        }

        
        
        DB::table('ordenesservicio')
        ->where('id', $orden->id)
        ->update(['finalizado' => 1, 'fechafin' => $fechaFin]);

        return response()->json([
            //'success' => 'La Reparacion ha sido registrada!'
            'success' => $request->all()
        ]);

}


    


    
}
