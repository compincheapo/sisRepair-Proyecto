<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TipoRepuesto;
use PDF;

class TipoRepuestoController extends Controller
{
    function __construct(){
        $this->middleware('permission:ver-tiporepuestos|crear-tiporepuestos|editar-tiporepuestos|borrar-tiporepuestos', ['only' => ['index']]);
        $this->middleware('permission:crear-tiporepuestos', ['only' => ['create', 'store']]); //Métodos asociados al permiso.
        $this->middleware('permission:editar-tiporepuestos', ['only' => ['edit', 'update']]); 
        $this->middleware('permission:borrar-tiporepuestos', ['only' => ['destroy']]); 
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $tiporepuestos = TipoRepuesto::paginate(5);
        $nombre = $request->nombre;
        $descripcion = $request->descripcion;

        if($request->nombre || $request->descripcion){
            $tiporepuestos = TipoRepuesto::where('nombre', 'like', '%'.$request->nombre .'%')
            ->where('descripcion', 'like', '%'.$request->descripcion .'%');

            if($request->submitbtn == 'PDF'){
                $tiporepuestos = $tiporepuestos->get();
            } elseif($request->submitbtn == 'Filtrar'){
                $tiporepuestos = $tiporepuestos->paginate(5);
            }
        } else {
            if($request->submitbtn == 'PDF'){
                $tiporepuestos = TipoRepuesto::all();
            } elseif($request->submitbtn == 'Filtrar'){
                $tiporepuestos = TipoRepuesto::paginate(5);
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
                    $key = ucfirst($key);
                    $filtrado = $key . ': ' . $value. '.'; 
                }
           }

           if(count($filtros) > 1){
                $filtrado = '';
                foreach($filtros as $key => $value) {
                    $key = ucfirst($key);
                    $filtrado = $filtrado . $key . ':' . $value . ', ';
                }
                $filtrado = rtrim($filtrado, ", ");
                $filtrado = $filtrado . '.';
           }
                       
            $pdf = PDF::loadView('tiporepuestos.pdf', compact('tiporepuestos', 'filtrado'));
            return $pdf->stream();
        } elseif($request->submitbtn == 'Filtrar'){
            return view('tiporepuestos.index', compact('tiporepuestos', 'nombre', 'descripcion'));
        } elseif($request->submitbtn == null){
            $tiporepuestos = TipoRepuesto::paginate(5);
            return view('tiporepuestos.index', compact('tiporepuestos', 'nombre', 'descripcion'));
        }
        
        return view('tiporepuestos.index', compact('tiporepuestos','nombre', 'descripcion'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('tiporepuestos.create');
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
            'nombre' => 'required|unique:tiporepuestos,nombre',
            
        ]);

        
        $input = $request->all(); 
        

        $tiporepuesto = TipoRepuesto::create($input);
        

        return redirect()->route('tiporepuestos.index');
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
        $tiporepuesto = TipoRepuesto::find($id);

        return view('tiporepuestos.edit', compact('tiporepuesto'));
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
            'nombre' => 'required',
        ]);

        $input = $request->all();
        $tiporepuesto = TipoRepuesto::find($id);
        $tiporepuesto->update($input);

        return redirect()->route('tiporepuestos.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        TipoRepuesto::find($id)->delete();
        return redirect()->route('tiporepuestos.index');
    }
}
