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
use DB;

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

        // $equipos = Equipo::with(['marca' => function ($query) {
        //         $query->select('id', 'nombre as nombreMarca');
        //     }])
        //     ->select('id_marca', 'serie')
        //     ->get();

        // $equipos = Equipo::join('marcas', 'equipos.id_marca', 'marcas.id')
        // ->join('users', 'equipos.id_user', 'user.id')
        // ->select('equipos.serie', 'marcas.nombre')
        // ->orderBy('equipos.serie', 'DESC')
        // ->get();
        //dd($equipos);
        //dd($equipos);

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
                     })->pluck('name', 'id');
        
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
            'fecha' => 'required',
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

        //Creación y asociación de Orden de Servicio al Equipo
        $ordenServicio = new OrdenServicio;
        $ordenServicio->finalizado = false;
        $ordenServicio->fechacompromiso = $request->get('fecha');
        $ordenServicio->id_equipo = $equipo->id;
        $ordenServicio->save();


        //Asociación del Estado "A Diagnóstico", nro de Orden, Cajero o Vendedor al Equipo.
        $cajero = Auth::user();
        $estado = Estado::findOrfail(1);
        //dd($equipo->id, $estado->id, $cajero->id, $ordenServicio->id);
    

        DB::table('equipos_estados_users_ordenes')->insert([
            'id_equipo' => $equipo->id,
            'id_estado' => $estado->id,
            'id_user' => $cajero->id,
            'id_orden' => $ordenServicio->id,
            'descripcion' => $input->get('descripcion'),

        ]);

        return redirect()->route('equipos.index');
    }

    public function edit($id)
    {
        $equipo = Equipo::find($id);
        return view('equipos.edit', compact('equipos'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'nombre' => 'required',
        ]);

        $input = $request->except('descripcion');

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
            $output .= '<option value="'.$seccion->id.'">'.$seccion->nombre.'</option>';
            }
            echo $output;
    }

}
