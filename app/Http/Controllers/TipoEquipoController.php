<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TipoEquipo;
use PDF;

class TipoEquipoController extends Controller
{
    function __construct(){
        $this->middleware('permission:ver-tiposequipos|crear-tiposequipos|editar-tiposequipos|borrar-tiposequipos', ['only' => ['index']]);
        $this->middleware('permission:crear-tiposequipos', ['only' => ['create', 'store']]); //Métodos asociados al permiso.
        $this->middleware('permission:editar-tiposequipos', ['only' => ['edit', 'update']]); 
        $this->middleware('permission:borrar-tiposequipos', ['only' => ['destroy']]); 
    }

    public function index(Request $request)
    {
        $tipoequipos = TipoEquipo::paginate(5);
        $nombre = $request->nombre;
        $descripcion = $request->descripcion;

        if($request->nombre || $request->descripcion){
            $tipoequipos = TipoEquipo::where('nombre', 'like', '%'.$request->nombre .'%')
            ->where('descripcion', 'like', '%'.$request->descripcion .'%');

            if($request->submitbtn == 'PDF'){
                $tipoequipos = $tipoequipos->get();
            } elseif($request->submitbtn == 'Filtrar'){
                $tipoequipos = $tipoequipos->paginate(5);
            }
        } else {
            if($request->submitbtn == 'PDF'){
                $tipoequipos = TipoEquipo::all();
            } elseif($request->submitbtn == 'Filtrar'){
                $tipoequipos = TipoEquipo::paginate(5);
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
                       
            $pdf = PDF::loadView('tipoequipos.pdf', compact('tipoequipos', 'filtrado'));
            return $pdf->stream();
        } elseif($request->submitbtn == 'Filtrar'){
            return view('tipoequipos.index', compact('tipoequipos', 'nombre', 'descripcion'));
        } elseif($request->submitbtn == null){
            $tipoequipos = TipoEquipo::paginate(5);
            return view('tipoequipos.index', compact('tipoequipos', 'nombre', 'descripcion'));
        }
        
        return view('tipoequipos.index', compact('tipoequipos','nombre', 'descripcion'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('tipoequipos.create');
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
            'nombre' => 'required|unique:tipoequipos,nombre',
        ]);


        $input = $request->all(); 
        $tipoequipo = TipoEquipo::create($input);

        return redirect()->route('tipoequipos.index');
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
        $tipoequipo = TipoEquipo::find($id);
        return view('tipoequipos.edit', compact('tipoequipo'));
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

        $tipoequipo = TipoEquipo::find($id);
        $tipoequipo->update($input);

        return redirect()->route('tipoequipos.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        TipoEquipo::find($id)->delete();
        return redirect()->route('tipoequipos.index');
    }
}
