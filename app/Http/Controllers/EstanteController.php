<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Estante;
use PDF;

class EstanteController extends Controller
{
    function __construct(){
        $this->middleware('permission:ver-estantes|crear-estantes|editar-estantes|borrar-estantes|secciones-estante', ['only' => ['index']]);
        $this->middleware('permission:crear-estantes', ['only' => ['create', 'store']]); //Métodos asociados al permiso.
        $this->middleware('permission:editar-estantes', ['only' => ['edit', 'update']]); 
        $this->middleware('permission:borrar-estantes', ['only' => ['destroy']]); 
        $this->middleware('permission:secciones-estante', ['only' => ['show']]); 
    }

    public function index(Request $request)
    {
        $estantes = Estante::paginate(5);
        $nombre = $request->nombre;
        $descripcion = $request->descripcion;

        if($request->nombre || $request->descripcion){
            $estantes = Estante::where('nombre', 'like', '%'.$request->nombre .'%')
            ->where('descripcion', 'like', '%'.$request->descripcion .'%');

            if($request->submitbtn == 'PDF'){
                $estantes = $estantes->get();
            } elseif($request->submitbtn == 'Filtrar'){
                $estantes = $estantes->paginate(5);
            }
        } else {
            if($request->submitbtn == 'PDF'){
                $estantes = Estante::all();
            } elseif($request->submitbtn == 'Filtrar'){
                $estantes = Estante::paginate(5);
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
                       
            $pdf = PDF::loadView('estantes.pdf', compact('estantes', 'filtrado'));
            return $pdf->stream();
        } elseif($request->submitbtn == 'Filtrar'){
            return view('estantes.index', compact('estantes', 'nombre', 'descripcion'));
        } elseif($request->submitbtn == null){
            $estantes = Estante::paginate(5);
            return view('estantes.index', compact('estantes', 'nombre', 'descripcion'));
        }
        
        return view('estantes.index', compact('estantes','nombre', 'descripcion'));
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


    public function show($id, Request $request)
    {
        $estante = Estante::findOrfail($id);
        $seccionesestante = $estante->seccionesEstante();

        $nombre = $request->nombre;
        $descripcion = $request->descripcion;

        if($request->nombre || $request->descripcion){
            $seccionesestante = $seccionesestante->where('nombre', 'like', '%'.$request->nombre .'%')
            ->where('descripcion', 'like', '%'.$request->descripcion .'%');

            if($request->submitbtn == 'PDF'){
                $seccionesestante = $seccionesestante->get();
            } elseif($request->submitbtn == 'Filtrar'){
                $seccionesestante = $seccionesestante->paginate(5);
            }
        } else {
            if($request->submitbtn == 'PDF'){
                $seccionesestante = $seccionesestante->get();
            } elseif($request->submitbtn == 'Filtrar'){
                $seccionesestante = $seccionesestante->paginate(5);
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
                       
            $pdf = PDF::loadView('seccionesestante.pdf', compact('seccionesestante', 'filtrado'));
            return $pdf->stream();
        } elseif($request->submitbtn == 'Filtrar'){
            return view('seccionesestante.index', compact('seccionesestante', 'estante', 'nombre', 'descripcion'));
        } elseif($request->submitbtn == null){
            $seccionesestante = $seccionesestante->paginate(5);
            return view('seccionesestante.index', compact('seccionesestante', 'estante', 'nombre', 'descripcion'));
        }
        
        return view('seccionesestante.index', compact('seccionesestante', 'estante', 'nombre', 'descripcion'));

    }

}
