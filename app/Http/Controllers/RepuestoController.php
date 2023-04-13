<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Repuesto;
use App\Models\TipoRepuesto;
use App\Models\Marca;
use App\Models\Estante;
use App\Models\SeccionesEstante;
use DB;
use PDF;

class RepuestoController extends Controller
{
    function __construct(){
        $this->middleware('permission:ver-repuestos|crear-repuestos|editar-repuestos|borrar-repuestos|secciones-estante', ['only' => ['index']]);
        $this->middleware('permission:crear-repuestos', ['only' => ['create', 'store']]); //Métodos asociados al permiso.
        $this->middleware('permission:editar-repuestos', ['only' => ['edit', 'update']]); 
        $this->middleware('permission:borrar-repuestos', ['only' => ['destroy']]); 
    }

    public function index(Request $request)
    {
        $repuestos = Repuesto::paginate(5);

        $estantes = Estante::select('id', 'nombre')->get();
        $marcas = Marca::select('id','nombre')->get();
        $tiporepuestos = TipoRepuesto::select('id', 'nombre')->get();

        $estanteData = null;
        $marcaData = null;
        $tiporepuestoData = null;
        $modeloData = $request->modelo;

        if($request->marca){
            $marcaData = Marca::where('id', $request->marca)->select('id', 'nombre')->first();
        } 

        if($request->tiporepuesto){
            $tiporepuestoData = TipoRepuesto::where('id', $request->tiporepuesto)->select('id', 'nombre')->first();
        } 

        if($request->estante){
            $estanteData = Estante::where('id', $request->estante)->select('id', 'nombre')->first();
        } 

        if($request->marca ||  $request->tiporepuesto || $request->estante || $request->modelo){
            $repuestos = Repuesto::join('seccionesestante', 'repuestos.id_seccionestante', 'seccionesestante.id')
            ->select('repuestos.*')
            ->when($request->filled('marca'), function ($query) use ($request) {
                return $query->where('repuestos.id_marca', $request->marca);
            })->when($request->filled('tiporepuesto'), function ($query) use ($request) {
                return $query->where('repuestos.id_tiporepuesto', $request->tiporepuesto);
            })->when($request->filled('estante'), function ($query) use ($request) {
                return $query->where('seccionesestante.id_estante', $request->estante);
            })->when($request->filled('modelo'), function ($query) use ($request) {
                return $query->where('repuestos.modelo', $request->modelo);
            })->paginate(5);

        }else {
            if($request->submitbtn == 'PDF'){
                $repuestos = $repuestos = Repuesto::all();
            } elseif($request->submitbtn == 'Filtrar'){
                $repuestos = Repuesto::paginate(5);;
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
                    
                    if($key == 'tiporepuesto'){
                      $key = 'Tipo Repuesto';
                      $selectedTipoRepuesto = TipoRepuesto::findOrfail($value)->where('id', $value)->first();
                      $value = $selectedTipoRepuesto->nombre;
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
                    if($key == 'tiporepuesto'){
                        $key = 'Tipo Repuesto';
                        $selectedTipoRepuesto = TipoRepuesto::findOrfail($value)->where('id', $value)->first();
                        $value = $selectedTipoRepuesto->nombre;
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
                       
            $pdf = PDF::loadView('repuestos.pdf', compact('repuestos', 'filtrado'));
            return $pdf->stream();
        } elseif($request->submitbtn == 'Filtrar'){
            return view('repuestos.index', compact('repuestos', 'estantes', 'marcas', 'tiporepuestos', 'estanteData', 'marcaData', 'tiporepuestoData', 'modeloData'));
        } elseif($request->submitbtn == null){
            $repuestos = Repuesto::paginate(5);
            return view('repuestos.index', compact('repuestos', 'estantes', 'marcas', 'tiporepuestos', 'estanteData', 'marcaData', 'tiporepuestoData', 'modeloData'));
        }
        


        return view('repuestos.index', compact('repuestos'));
    }

    public function create()
    {

        $tiposrepuestos = TipoRepuesto::pluck('nombre', 'id');

        $marcas = Marca::pluck('nombre', 'id');
        
        $estantes = Estante::select('nombre', 'id')->get();

        return view('repuestos.create', compact('tiposrepuestos', 'marcas', 'estantes'));
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'modelo' => 'required',
            'cantidad' => 'required',
            'precio' => 'required',
            'tiporepuesto' => 'required',
            'marca' => 'required',
            'estante' => 'required',
            'seccion' => 'required',
        ]);


        //Creación de Equipo. 
        $input = $request; 

        $repuesto = new Repuesto;
        $repuesto->cantidad = $input->get('cantidad');
        $repuesto->precio = $input->get('precio');
        $repuesto->modelo = $input->get('modelo');
        $repuesto->id_marca = $input->get('marca');
        $repuesto->id_seccionestante = $input->get('seccion');
        $repuesto->id_tiporepuesto = $input->get('tiporepuesto');
        $repuesto->descripcion = $input->get('descripcion');
        $repuesto->save();

        return redirect()->route('repuestos.index');
    }

    public function edit($id)
    {
        $repuesto = Repuesto::find($id);
        $tiposrepuestos = TipoRepuesto::pluck('nombre', 'id');
        $tiporepuesto = TipoRepuesto::findOrfail($repuesto->id_tiporepuesto)->where('id', $repuesto->id_tiporepuesto)->pluck('id', 'nombre')->first();

        $marcas = Marca::pluck('nombre', 'id');
        $marca = Marca::findOrfail($repuesto->id_marca)->where('id', $repuesto->id_marca)->pluck('id', 'nombre')->first();
        
        $estantes = Estante::select('nombre', 'id')->get();
        $estante = SeccionesEstante::findOrfail($repuesto->id_seccionestante)->where('id', $repuesto->id_seccionestante)->first();

        return view('repuestos.edit', compact('repuesto', 'tiposrepuestos', 'tiporepuesto', 'marcas', 'marca', 'estantes', 'estante'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'modelo' => 'required',
            'tiporepuesto' => 'required',
            'cantidad' => 'required',
            'precio' => 'required',
            'marca' => 'required',
            'estante' => 'required',
            'seccion' => 'required',
        ]);        

        $repuesto = Repuesto::find($id);
        $repuesto->modelo = $request->get('modelo');
        $repuesto->id_tiporepuesto = $request->get('tiporepuesto');
        $repuesto->cantidad = $request->get('cantidad');
        $repuesto->precio = $request->get('precio');
        $repuesto->id_marca = $request->get('marca');
        $repuesto->id_seccionestante = $request->get('seccion');
        $repuesto->descripcion = $request->get('descripcion');
        $repuesto->update();

        return redirect()->route('repuestos.index');
    }

    public function destroy($id)
    {
        Repuesto::find($id)->delete();
        return redirect()->route('repuestos.index');
    }

    public function show($id)
    {
        //
    }

    public function getRepuestosReparacion(){

        $repuestos =  Repuesto::where('cantidad', '>=', 1)->with(['marca', 'seccionEstante', 'tiporepuesto', 'seccionEstante.estante' => function ($query) {
            $query->select('id', 'nombre');
        }])
        ->select('id', 'modelo', 'cantidad', 'id_marca', 'id_seccionestante', 'id_tiporepuesto')
        ->get();


        return DataTables()->collection($repuestos)
        ->toJson();
    }


}
