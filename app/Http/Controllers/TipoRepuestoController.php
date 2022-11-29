<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TipoRepuesto;

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
    public function index()
    {
        $tiporepuestos = TipoRepuesto::paginate(5);

        return view('tiporepuestos.index', compact('tiporepuestos'));
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
