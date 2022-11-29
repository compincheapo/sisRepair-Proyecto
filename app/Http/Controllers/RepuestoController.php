<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Repuesto;
use App\Models\TipoRepuesto;
use App\Models\Marca;
use App\Models\Estante;
use App\Models\SeccionesEstante;
use DB;

class RepuestoController extends Controller
{
    function __construct(){
        $this->middleware('permission:ver-repuestos|crear-repuestos|editar-repuestos|borrar-repuestos|secciones-estante', ['only' => ['index']]);
        $this->middleware('permission:crear-repuestos', ['only' => ['create', 'store']]); //Métodos asociados al permiso.
        $this->middleware('permission:editar-repuestos', ['only' => ['edit', 'update']]); 
        $this->middleware('permission:borrar-repuestos', ['only' => ['destroy']]); 
    }

    public function index()
    {
        $repuestos = Repuesto::paginate(5);

        $repuesto = Repuesto::first();

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
            'serie' => 'unique:repuestos,serie',
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
        $repuesto->serie = $input->get('serie');
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

        // $userRole = $user->roles->pluck('name', 'name')->all();

        return view('repuestos.edit', compact('repuesto', 'tiposrepuestos', 'tiporepuesto', 'marcas', 'marca', 'estantes', 'estante'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'serie' => 'required',              //Verificar que al editar no se repita el número de serie con el id actual dado.
            'modelo' => 'required',
            'tiporepuesto' => 'required',
            'cantidad' => 'required',
            'precio' => 'required',
            'marca' => 'required',
            'estante' => 'required',
            'seccion' => 'required',
        ]);        

        $repuesto = Repuesto::find($id);
        $repuesto->serie = $request->get('serie');
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
        //$repuestos = Repuesto::where('cantidad', '>=', 1)->with('marca:id,nombre', 'seccionEstante', 'tiporepuesto')->get(['id', 'serie', 'modelo']);

        $repuestos =  Repuesto::where('cantidad', '>=', 1)->with(['marca', 'seccionEstante', 'tiporepuesto', 'seccionEstante.estante' => function ($query) {
            $query->select('id', 'nombre');
        }])
        ->select('id', 'serie', 'modelo', 'cantidad', 'id_marca', 'id_seccionestante', 'id_tiporepuesto')
        ->get();


        return DataTables()->collection($repuestos)
        ->toJson();
    }


}
