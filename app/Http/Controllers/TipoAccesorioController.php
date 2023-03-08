<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TipoAccesorio;
use PDF;

class TipoAccesorioController extends Controller
{

    function __construct(){
        $this->middleware('permission:ver-tiposaccesorios|crear-tiposaccesorios|editar-tiposaccesorios|borrar-tiposaccesorios', ['only' => ['index']]);
        $this->middleware('permission:crear-tiposaccesorios', ['only' => ['create', 'store']]); //Métodos asociados al permiso.
        $this->middleware('permission:editar-tiposaccesorios', ['only' => ['edit', 'update']]); 
        $this->middleware('permission:borrar-tiposaccesorios', ['only' => ['destroy']]); 
    }

    public function index(Request $request)
    {
        $tipoaccesorios = TipoAccesorio::paginate(5);
        $nombre = $request->nombre;
        $descripcion = $request->descripcion;

        if($request->nombre || $request->descripcion){
            $tipoaccesorios = TipoAccesorio::where('nombre', 'like', '%'.$request->nombre .'%')
            ->where('descripcion', 'like', '%'.$request->descripcion .'%');

            if($request->submitbtn == 'PDF'){
                $tipoaccesorios = $tipoaccesorios->get();
            } elseif($request->submitbtn == 'Filtrar'){
                $tipoaccesorios = $tipoaccesorios->paginate(5);
            }
        } else {
            if($request->submitbtn == 'PDF'){
                $tipoaccesorios = TipoAccesorio::all();
            } elseif($request->submitbtn == 'Filtrar'){
                $tipoaccesorios = TipoAccesorio::paginate(5);
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
                       
            $pdf = PDF::loadView('tipoaccesorios.pdf', compact('tipoaccesorios', 'filtrado'));
            return $pdf->stream();
        } elseif($request->submitbtn == 'Filtrar'){
            return view('tipoaccesorios.index', compact('tipoaccesorios', 'nombre', 'descripcion'));
        } elseif($request->submitbtn == null){
            $tipoaccesorios = TipoAccesorio::paginate(5);
            return view('tipoaccesorios.index', compact('tipoaccesorios', 'nombre', 'descripcion'));
        }
        
        return view('tipoaccesorios.index', compact('tipoaccesorios','nombre', 'descripcion'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('tipoaccesorios.create');
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
            'nombre' => 'required|unique:tipoaccesorios,nombre',
        ]);


        $input = $request->all(); 
        $tipoaccesorio = TipoAccesorio::create($input);

        return redirect()->route('tipoaccesorios.index');
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
        $tipoaccesorio = TipoAccesorio::find($id);
        return view('tipoaccesorios.edit', compact('tipoaccesorio'));
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

        $tipoaccesorio = TipoAccesorio::find($id);
        $tipoaccesorio->update($input);

        return redirect()->route('tipoaccesorios.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        TipoAccesorio::find($id)->delete();
        return redirect()->route('tipoaccesorios.index');
    }
}
