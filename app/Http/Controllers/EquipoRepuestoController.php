<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Equipo;
use Illuminate\Support\Facades\DB;
use App\Models\TipoEquipo;
use App\Models\Marca;
use App\Models\Estante;
use App\Models\TipoAccesorio;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\SeccionesEstante;
use PDF;


class EquipoRepuestoController extends Controller
{
    function __construct(){
        $this->middleware('permission:ver-equipos|crear-equipos|editar-equipos|borrar-equipos|secciones-estante', ['only' => ['index']]);
        $this->middleware('permission:crear-equipos', ['only' => ['create', 'store']]); //Métodos asociados al permiso.
        $this->middleware('permission:editar-equipos', ['only' => ['edit', 'update']]); 
        $this->middleware('permission:borrar-equipos', ['only' => ['destroy']]); 
    }

    public function index(Request $request)
    {
        $equiposRepuestoCollection = DB::table('equipos')
        ->select('equipos.id')
        ->join('equipos_estados_users_ordenes', 'equipos_estados_users_ordenes.id_equipo', 'equipos.id')
        ->where('equipos_estados_users_ordenes.id_estado', 14)
        ->orderBy('equipos_estados_users_ordenes.created_at', 'desc')
        ->pluck('equipos.id');

        $equiporepuestos = Equipo::whereIn('equipos.id', $equiposRepuestoCollection);

        $estantes = Estante::select('id', 'nombre')->get();
        $marcas = Marca::select('id','nombre')->get();
        $tiposequipo = TipoEquipo::select('id', 'nombre')->get();

        $estanteData = null;
        $marcaData = null;
        $tipoequipoData = null;
        $serieData = $request->serie;
        $modeloData = $request->modelo;

        if($request->marca){
            $marcaData = Marca::where('id', $request->marca)->select('id', 'nombre')->first();
        } 

        if($request->tipoequipo){
            $tipoequipoData = TipoEquipo::where('id', $request->tipoequipo)->select('id', 'nombre')->first();
        } 

        if($request->estante){
            $estanteData = Estante::where('id', $request->estante)->select('id', 'nombre')->first();
        } 

        if($request->marca ||  $request->tipoequipo || $request->estante || $request->serie || $request->modelo){
            $equiporepuestos = $equiporepuestos->join('seccionesestante', 'equipos.id_seccionestante', 'seccionesestante.id')
            ->select('equipos.*')
            ->when($request->filled('marca'), function ($query) use ($request) {
                return $query->where('equipos.id_marca', $request->marca);
            })->when($request->filled('tipoequipo'), function ($query) use ($request) {
                return $query->where('equipos.id_tipoequipo', $request->tipoequipo);
            })->when($request->filled('estante'), function ($query) use ($request) {
                return $query->where('seccionesestante.id_estante', $request->estante);
            })->when($request->filled('serie'), function ($query) use ($request) {
                return $query->where('equipos.serie', $request->serie);
            })->when($request->filled('modelo'), function ($query) use ($request) {
                return $query->where('equipos.modelo', $request->modelo);
            })->paginate(5);

        }else {
            if($request->submitbtn == 'PDF'){
                $equiporepuestos = $equiporepuestos->get();
            } elseif($request->submitbtn == 'Filtrar'){
                $equiporepuestos = $equiporepuestos->paginate(5);
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
                    if($key == 'marca'){
                      $selectedMarca = Marca::findOrfail($value)->where('id', $value)->first();
                      $value = $selectedMarca->nombre;
                    }
                    if($key == 'estante'){
                      $selectedEstante = Estante::findOrfail($value)->where('id', $value)->first();
                      $value = $selectedEstante->nombre;
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
                      if($key == 'marca'){
                        $selectedMarca = Marca::findOrfail($value)->where('id', $value)->first();
                        $value = $selectedMarca->nombre;
                      }
                      if($key == 'estante'){
                        $selectedEstante = Estante::findOrfail($value)->where('id', $value)->first();
                        $value = $selectedEstante->nombre;
                      }
                     
                    $key = ucfirst($key);
                    $filtrado = $filtrado . $key . ':' . $value . ', ';
                }
                $filtrado = rtrim($filtrado, ", ");
                $filtrado = $filtrado . '.';
           }
                       
            $pdf = PDF::loadView('equiporepuestos.pdf', compact('equiporepuestos', 'filtrado'));
            return $pdf->stream();
        } elseif($request->submitbtn == 'Filtrar'){
            return view('equiporepuestos.index', compact('equiporepuestos', 'estantes', 'marcas', 'tiposequipo', 'estanteData', 'marcaData', 'tipoequipoData', 'serieData', 'modeloData'));
        } elseif($request->submitbtn == null){
            $equiporepuestos = $equiporepuestos->paginate(5);
            return view('equiporepuestos.index', compact('equiporepuestos', 'estantes', 'marcas', 'tiposequipo', 'estanteData', 'marcaData', 'tipoequipoData', 'serieData', 'modeloData'));
        }
        
        return view('equiporepuestos.index', compact('equipos', 'estantes', 'marcas', 'tiposequipo', 'estanteData', 'marcaData', 'tipoequipoData', 'serieData', 'modeloData'));
    }

    public function create()
    {
        $tiposequipo = TipoEquipo::pluck('nombre', 'id');
        $marcas = Marca::pluck('nombre', 'id');        
        $estantes = Estante::select('nombre', 'id')->get();
        $tiposaccesorios = TipoAccesorio::select('nombre', 'id')->get();

        return view('equiporepuestos.create', compact('tiposequipo', 'marcas', 'estantes', 'tiposaccesorios'));
    }


    public function store(Request $request)
    {
        //Validaciones Equipo.
        $this->validate($request, [
            'serie' => 'unique:equipos,serie',
            'tipoequipo' => 'required',
            'marca' => 'required',
            'estante' => 'required',
            'seccion' => 'required',
        ]);
        
        $gerenteId = User::with("roles")->whereHas("roles", function($q) {
            $q->whereIn("name", ["Admin"])->where('id', 1);
        })->select('id')->pluck('id')->first();

        //Creación de Equipo. 
        $input = $request; 

        $equipo = new Equipo;
        $equipo->serie = $input->get('serie');
        $equipo->modelo = $input->get('modelo');
        $equipo->id_marca = $input->get('marca');
        $equipo->id_seccionestante = $input->get('seccion');
        $equipo->id_user = $gerenteId;
        $equipo->id_tipoequipo = $input->get('tipoequipo');
        $equipo->save();

        //Asociación de Accesorios al Equipo.
        if($input->accesorios && isset($input->accesorios)){
            foreach ($input->accesorios as $accesorio) {
                $equipo->accesorios()->attach($accesorio); 
            }
        }

        //Asociación del Estado "Repuesto", Cajero o Vendedor al Equipo.
        $cajero = Auth::user();
        //dd($equipo->id, $estado->id, $cajero->id, $ordenServicio->id);
    

        DB::table('equipos_estados_users_ordenes')->insert([
            'id_equipo' => $equipo->id,
            'id_estado' => 14,
            'id_user' => $cajero->id,
            'id_orden' => null,
            'descripcion' => $input->get('descripcion'),

        ]);

        return redirect()->route('equiporepuestos.index');
    }

    public function edit($id)
    {
        $equipo = Equipo::find($id);  

        $descripcion = DB::table('equipos_estados_users_ordenes')
        ->select('descripcion')
        ->where('equipos_estados_users_ordenes.id_equipo', $equipo->id)
        ->where('equipos_estados_users_ordenes.id_estado', 1)
        ->pluck('descripcion')
        ->first();

        $tiposaccesorios = TipoAccesorio::select('nombre', 'id')->get();
        $accesorios = $equipo->accesorios()->pluck('id_accesorio')->toArray();


        // foreach ($accesorios as $accesorio) {
        //     dd(in_array($accesorio, $tiposaccesorios->pluck('id')->toArray()));
            
        // }


        $tiposequipos = TipoEquipo::pluck('nombre', 'id');  
        $tipoequipo = TipoEquipo::findOrfail($equipo->id_tipoequipo)->where('id', $equipo->id_tipoequipo)->pluck('id', 'nombre')->first();

        $marcas = Marca::pluck('nombre', 'id');
        $marca = Marca::findOrfail($equipo->id_marca)->where('id', $equipo->id_marca)->pluck('id', 'nombre')->first();
        
        $estantes = Estante::select('nombre', 'id')->get();
        $estante = SeccionesEstante::findOrfail($equipo->id_seccionestante)->where('id', $equipo->id_seccionestante)->first();

        return view('equiporepuestos.edit', compact('equipo', 'tiposequipos', 'tipoequipo', 'tiposaccesorios', 'accesorios', 'marcas', 'marca', 'estantes', 'estante', 'descripcion'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'serie' => 'required',     //Verificar que al editar no se repita el número de serie con el id actual dado.     
            'tipoequipo' => 'required',
            'marca' => 'required',
            'estante' => 'required',
            'seccion' => 'required',
        ]);        

        $gerenteId = User::with("roles")->whereHas("roles", function($q) {
            $q->whereIn("name", ["Admin"])->where('id', 1);
        })->select('id')->pluck('id')->first();
        
        DB::table('equipos_estados_users_ordenes')
        ->where('equipos_estados_users_ordenes.id_equipo', $id)
        ->where('equipos_estados_users_ordenes.id_estado', 1)
        ->update(['equipos_estados_users_ordenes.descripcion' => $request->get('descripcion')]);

        DB::table('equipos_accesorios')
        ->where('equipos_accesorios.id_equipo', $id)
        ->delete();        

        $equipo = Equipo::find($id);
        $equipo->serie = $request->get('serie');
        $equipo->modelo = $request->get('modelo');
        $equipo->id_tipoequipo = $request->get('tipoequipo');
        $equipo->id_marca = $request->get('marca');
        $equipo->id_seccionestante = $request->get('seccion');
        $equipo->id_user = $gerenteId;  
        $equipo->update();

        //Asociación de Accesorios al Equipo.
        $accesorios = $request->get('accesorios');
        if($accesorios && isset($accesorios)){
            foreach ($accesorios as $accesorio) {
                $equipo->accesorios()->attach($accesorio); 
            }
        }

        return redirect()->route('equiporepuestos.index');
    }

    public function destroy($id)
    {
        Equipo::find($id)->delete();
        return redirect()->route('equiporepuestos.index');
    }

    public function show($id)
    {
        //
    }
}
