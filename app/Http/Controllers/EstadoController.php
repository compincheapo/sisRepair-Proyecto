<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Estado;

class EstadoController extends Controller
{

    function __construct(){
        $this->middleware('permission:ver-estados|crear-estados|editar-estados|borrar-estados', ['only' => ['index']]);
        $this->middleware('permission:crear-estados', ['only' => ['create', 'store']]); //Métodos asociados al permiso.
        $this->middleware('permission:editar-estados', ['only' => ['edit', 'update']]); 
        $this->middleware('permission:borrar-estados', ['only' => ['destroy']]); 
    }

    public function index()
    {
        $estados = Estado::paginate(5);
        //dd($servicios);

        return view('estados.index', compact('estados'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('estados.create');
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
            'nombre' => 'required|unique:estados,nombre',
        ]);


        $input = $request->all(); 
        $estado = Estado::create($input);

        return redirect()->route('estados.index');
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
        $estado = Estado::find($id);
        return view('estados.edit', compact('estado'));
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

        $estado = Estado::find($id);
        $estado->update($input);

        return redirect()->route('estados.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Estado::find($id)->delete();
        return redirect()->route('estados.index');
    }
}
