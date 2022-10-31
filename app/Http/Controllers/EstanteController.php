<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Estante;

class EstanteController extends Controller
{
    function __construct(){
        $this->middleware('permission:ver-estantes|crear-estantes|editar-estantes|borrar-estantes|secciones-estante', ['only' => ['index']]);
        $this->middleware('permission:crear-estantes', ['only' => ['create', 'store']]); //Métodos asociados al permiso.
        $this->middleware('permission:editar-estantes', ['only' => ['edit', 'update']]); 
        $this->middleware('permission:borrar-estantes', ['only' => ['destroy']]); 
        $this->middleware('permission:secciones-estante', ['only' => ['show']]); 
    }

    public function index()
    {
        $estantes = Estante::paginate(5);
        //dd($estantes);

        return view('estantes.index', compact('estantes'));
    }

    public function create()
    {
        return view('estantes.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'nombre' => 'required|unique:estantes,nombre',
        ]);


        $input = $request->all(); 
        $estante = Estante::create($input);

        return redirect()->route('estantes.index');
    }

    public function edit($id)
    {
        $estante = Estante::find($id);
        return view('estantes.edit', compact('estante'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'nombre' => 'required',
        ]);

        $input = $request->all();

        $estante = Estante::find($id);
        $estante->update($input);

        return redirect()->route('estantes.index');
    }

    public function destroy($id)
    {
        Estante::find($id)->delete();
        return redirect()->route('estantes.index');
    }


    public function show($id)
    {
        $estante = Estante::findOrfail($id);
        //dd($estante);
        $seccionesestante = $estante->seccionesEstante()->paginate(5);
        //dd($seccionesestante);

        return view('seccionesestante.index', compact('seccionesestante', 'estante'));
    }

}
