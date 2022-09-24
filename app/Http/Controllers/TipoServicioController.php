<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TipoServicio;
use App\Models\Servicio;

class TipoServicioController extends Controller
{

    function __construct(){
        $this->middleware('permission:ver-tiposervicios|crear-tiposervicios|editar-tiposervicios|borrar-tiposervicios', ['only' => ['index']]);
        $this->middleware('permission:crear-tiposervicios', ['only' => ['create', 'store']]); //Métodos asociados al permiso.
        $this->middleware('permission:editar-tiposervicios', ['only' => ['edit', 'update']]); 
        $this->middleware('permission:borrar-tiposervicios', ['only' => ['destroy']]); 
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tiposervicios = TipoServicio::paginate(5);
        //dd($tiposervicios->servicio);

        return view('tiposervicios.index', compact('tiposervicios'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        //$services = Servicio::pluck('nombre', 'nombre')->all();
        //dd($services);
        $servicios = Servicio::all()->pluck('nombre', 'id');

        //dd($servicios);

        return view('tiposervicios.create', compact('servicios'));
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
            'nombre' => 'required|unique:tiposervicios,nombre',
            'id_servicio' => 'required'
        ]);

        
        $input = $request->all(); 
        
        //dd($request->get('servicios'));
        

        $tiposervicio = TipoServicio::create($input);
        

        return redirect()->route('tiposervicios.index');
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
        $tiposervicio = TipoServicio::find($id);
        $servicios = Servicio::all()->pluck('nombre', 'id');

        return view('tiposervicios.edit', compact('tiposervicio', 'servicios'));
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
            'id_servicio' => 'required'
        ]);

        $input = $request->all();
        $tiposervicio = TipoServicio::find($id);
        $tiposervicio->update($input);

        return redirect()->route('tiposervicios.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        TipoServicio::find($id)->delete();
        return redirect()->route('tiposervicios.index');
    }
}
