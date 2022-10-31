<?php

namespace App\Http\Controllers;

use App\Models\SeccionesEstante;
use Illuminate\Http\Request;
use App\Models\Estante;

class SeccionesEstanteController extends Controller
{

    function __construct(){
        $this->middleware('permission:ver-seccionesestante|crear-seccionesestante|editar-seccionesestante|borrar-seccionesestante|secciones-estante', ['only' => ['index']]);
        $this->middleware('permission:crear-seccionesestante', ['only' => ['create', 'store']]); //Métodos asociados al permiso.
        $this->middleware('permission:editar-seccionesestante', ['only' => ['edit', 'update']]); 
        $this->middleware('permission:borrar-seccionesestante', ['only' => ['destroy']]); 
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $seccionesestante = Estante::paginate(5);
        $estante = $seccionesestante->estante();

        return view('seccionesestante.index', compact('seccionesestante', 'estante'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $estante = Estante::findOrfail($id);
        //dd($estante);
        return view('seccionesestante.create', compact('estante'));
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
            'nombre' => 'required|unique:seccionesestante,nombre',
        ]);


        $input = $request->all(); 
        $seccionestante = SeccionesEstante::create($input);

        return redirect()->route('estantes.show', $input['id_estante']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SeccionesEstante  $seccionesEstante
     * @return \Illuminate\Http\Response
     */
    public function show(SeccionesEstante $seccionesEstante)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SeccionesEstante  $seccionesEstante
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $seccionestante = SeccionesEstante::find($id);
        $estante = $seccionestante->estante()->first();
        //dd($estante);

        return view('seccionesestante.edit', compact('seccionestante', 'estante'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SeccionesEstante  $seccionesEstante
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'nombre' => 'required',
        ]);

        $input = $request->all();

        $seccionestante = SeccionesEstante::find($id);
        $seccionestante->update($input);

        return redirect()->route('estantes.show', $input['id_estante']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SeccionesEstante  $seccionesEstante
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $seccionestante = SeccionesEstante::findOrfail($id);
        $estante = $seccionestante->estante()->first()->id;
        $seccionestante->delete();
        
        return redirect()->route('estantes.show', compact('estante'));
    }
}
