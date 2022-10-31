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
        

        $equipos = Equipo::select('id', 'serie', 'id_marca', 'id_user')->with('marca:id,nombre','user:id,name')->get();

        foreach ($equipos as $key => $value) {
            $ordenEquipo = Equipo::findOrfail($value->id)->orden()->where('finalizado', 0)->first();
            $ultimoEstado = $ordenEquipo->estados()->first();

            if($ultimoEstado->id !== 1){
                unset($equipos[$key]);
            }
        }
        
        
         return DataTables()->collection($equipos)->toJson();
       
        

    }

    public function getTecnicos()
    {
        $userActual = Auth::user()->id;
        $tecnicos = User::role('Tecnico')->select('id', 'name', 'lastname')->get();
        return DataTables()->collection($tecnicos)->toJson();
    }

    public function verDiagnosticos(){
        // $estado = new Estado;
        // $equipos = $estado->equipos()->get();
        // dd($equipos);
        // $equipos = $estado->equipos()->select('equipos.id', 'serie', 'id_marca', 'equipos.id_user')->with('marca:id,nombre','user:id,name')->get();
        // dd($equipos);
        // $equipos = Equipo::select('id', 'serie', 'id_marca', 'id_user')->with('marca:id,nombre','user:id,name')->get();
        // dd($equipos);
        
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

    public function verAsignacionesRealizadas(){
        return view('asignaciones.diagnosticos.asignacionesRealizadas');
    }

    public function getAsignacionesRealizadas(){
        //dd($equipos = Equipo::findOrfail('4')->where('id', 4)->first());

       $equiposAsignados = DB::table('users_ordenes')
       ->join('ordenesservicio', 'users_ordenes.id_orden', '=', 'ordenesservicio.id')
       ->join('equipos', 'ordenesservicio.id_equipo', '=', 'equipos.id')
       ->join('equipos_estados_users_ordenes', 'equipos.id', '=', 'equipos_estados_users_ordenes.id_equipo')
       ->where('estadoAsignacion', 1)
       ->where('finalizado', 0)
       ->where('equipos_estados_users_ordenes.id_estado', 2)
       ->orWhere('equipos_estados_users_ordenes.id_estado', 5)
       ->get();



       $collection = new Collection;
       
        foreach ($equiposAsignados as $equipo) {
            $equipos = Equipo::find($equipo->id_equipo)->select('id', 'serie','id_marca', 'id_user')->where('id', $equipo->id_equipo)->with('marca:id,nombre','user:id,name')->first();
  


            $user = DB::table('equipos')->select('users.name', 'users.lastname')
            ->join('ordenesservicio','equipos.id', '=', 'ordenesservicio.id_equipo' )
            ->join('users_ordenes','ordenesservicio.id', '=', 'users_ordenes.id_orden' )
            ->join('users','users_ordenes.id_user', '=', 'users.id')
            ->where('users_ordenes.estadoAsignacion', 1)
            ->where('equipos.id', $equipo->id_equipo)->first();
            
            $equipos->name = $user->name . " " . $user->lastname;

            $collection->push($equipos);
        }
        return DataTables()->collection($collection)->toJson();

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

        //dd($equiposAsignados);

        
 
 
        $collection = new Collection;
        
         foreach ($equiposAsignados as $equipo) {   
            $estadoEquipo = DB::table('equipos')
            ->select('equipos_estados_users_ordenes.id_estado')
            ->join('equipos_estados_users_ordenes', 'equipos.id', 'equipos_estados_users_ordenes.id_equipo')
            ->where('equipos.id', $equipo->id_equipo)
            ->orderBy('equipos_estados_users_ordenes.created_at', 'desc')
            ->first();
            
            // dd($estadoEquipo->id_estado);
            if($estadoEquipo->id_estado == '2' || $estadoEquipo->id_estado == '3'){
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

        $idEstadoEquipo = DB::table('equipos')
            ->select('equipos_estados_users_ordenes.id_estado')
            ->join('equipos_estados_users_ordenes', 'equipos.id', 'equipos_estados_users_ordenes.id_equipo')
            ->where('equipos.id', $id)
            ->orderBy('equipos_estados_users_ordenes.created_at', 'desc')
            ->first();

        $accesorios = $equipo->accesorios()->select('nombre')->get();
        $orden = $equipo->orden()->where('finalizado', 0)->first();
        $fechaIngreso = DB::table('equipos')
        ->join('equipos_estados_users_ordenes','equipos.id', '=', 'equipos_estados_users_ordenes.id_equipo' )
        ->join('ordenesservicio','equipos_estados_users_ordenes.id_orden', '=', 'ordenesservicio.id')
        ->where('equipos.id', $id)
        ->where('ordenesservicio.finalizado', 0)
        ->where('equipos_estados_users_ordenes.id_estado', 1)
        ->select('equipos_estados_users_ordenes.created_at')
        ->first();

        $comentarios = DB::table('equipos_estados_users_ordenes')
        ->select('equipos_estados_users_ordenes.created_at', 'equipos_estados_users_ordenes.id_estado', 'equipos_estados_users_ordenes.descripcion', 'users.name', 'users.lastname')
        ->join('users', 'equipos_estados_users_ordenes.id_user', 'users.id')
        ->join('users_ordenes', 'equipos_estados_users_ordenes.id_orden', 'users_ordenes.id_orden')
        ->where('users_ordenes.estadoAsignacion', 1)
        ->where('users_ordenes.id_user', $userActual)
        ->where('users_ordenes.id_orden', $orden->id)
        ->whereIn('equipos_estados_users_ordenes.id_estado', [1])
        ->get();

        $equipo->accesorios = $accesorios;    
        $equipo->fechacompromiso = $orden->fechacompromiso;
        $equipo->fechaIngreso = $fechaIngreso;
        $equipo->estado = $idEstadoEquipo->id_estado; 
        $equipo->comentarios = $comentarios;

        $collection->push($equipo);
        
        return DataTables()->collection($collection)->toJson();
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

        $userActual = Auth::user()->id;
        
        $equipo = Equipo::findOrfail($request->get('idEquipo'))->where('id',  $request->get('idEquipo'))->first();
        $estado = Estado::findOrfail(9);
        $orden = Equipo::findOrfail($request->get('idEquipo'))->orden()->where('finalizado', 0)->first();

        DB::table('equipos_estados_users_ordenes')->insert([
                        'id_equipo' => $equipo->id,
                        'id_estado' => $estado->id,
                        'id_user' => $userActual,
                        'id_orden' => $orden->id,
                        'descripcion' => $request->get('detalle'),
        ]);

        DB::table('users_ordenes')
        ->where('id_user', $userActual)
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

    public function getTecnicosReasignacion(){
        $userActual = Auth::user()->id;
        $tecnicos = User::role('Tecnico')->select('id', 'name', 'lastname')->get();
        $tecnicos->prepend(['user_id' => $userActual]);

        return DataTables()->collection($tecnicos)->toJson();
    }

    public function getFinalizarDiagnostico(){
        $collection = new Collection;
        $userActual = Auth::user()->id;
        $collection->push(['user_id' => $userActual]);

        return DataTables()->collection($collection)->toJson(); 
    }

    public function finalizarDiagnostico(Request $request){
        $equipo = Equipo::findOrfail($request->get('idEquipo'))->where('id',  $request->get('idEquipo'))->first();
        $userActual = Auth::user()->id;
        $estado = Estado::findOrfail(4);
        $orden = Equipo::findOrfail($request->get('idEquipo'))->orden()->where('finalizado', 0)->first();

        DB::table('equipos_estados_users_ordenes')->insert([
                        'id_equipo' => $equipo->id,
                        'id_estado' => $estado->id,
                        'id_user' => $userActual,
                        'id_orden' => $orden->id,
                        'descripcion' => $request->get('detalle2'),
        ]);

        DB::table('ordenesservicio')
        ->where('id', $orden->id)
        ->update(['finalizado' => 1, 'fechafin' => '2022-10-30 17:32:46
        ']);

        return response()->json([
            'success' => 'El Diagnóstico ha sido registrado!'
        ]);


    }

    
}
