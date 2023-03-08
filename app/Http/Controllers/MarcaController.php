<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use App\Models\Marca;
use PDF;

class MarcaController extends Controller
{

    function __construct(){
        $this->middleware('permission:ver-marcas|crear-marcas|editar-marcas|borrar-marcas', ['only' => ['index']]);
        $this->middleware('permission:crear-marcas', ['only' => ['create', 'store']]); //Métodos asociados al permiso.
        $this->middleware('permission:editar-marcas', ['only' => ['edit', 'update']]); 
        $this->middleware('permission:borrar-marcas', ['only' => ['destroy']]); 
    }

    public function index(Request $request)
    {
        $marcas = Marca::paginate(5);
        $nombre = $request->nombre;
        $descripcion = $request->descripcion;

        if($request->nombre || $request->descripcion){
            $marcas = Marca::where('nombre', 'like', '%'.$request->nombre .'%')
            ->where('descripcion', 'like', '%'.$request->descripcion .'%');

            if($request->submitbtn == 'PDF'){
                $marcas = $marcas->get();
            } elseif($request->submitbtn == 'Filtrar'){
                $marcas = $marcas->paginate(5);
            }
        } else {
            if($request->submitbtn == 'PDF'){
                $marcas = Marca::all();
            } elseif($request->submitbtn == 'Filtrar'){
                $marcas = Marca::paginate(5);
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
                       
            $pdf = PDF::loadView('marcas.pdf', compact('marcas', 'filtrado'));
            return $pdf->stream();
        } elseif($request->submitbtn == 'Filtrar'){
            return view('marcas.index', compact('marcas', 'nombre', 'descripcion'));
        } elseif($request->submitbtn == null){
            $marcas = Marca::paginate(5);
            return view('marcas.index', compact('marcas', 'nombre', 'descripcion'));
        }
        
        return view('marcas.index', compact('roles','nombre', 'descripcion'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('marcas.create');
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
            'nombre' => 'required|unique:marcas,nombre',
        ]);


        $input = $request->all(); 
        $marca = Marca::create($input);

        return redirect()->route('marcas.index');
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
        $marca = Marca::find($id);
        return view('marcas.edit', compact('marca'));
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

        $marca = Marca::find($id);
        $marca->update($input);

        return redirect()->route('marcas.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Marca::find($id)->delete();
        return redirect()->route('marcas.index');
    }
}
