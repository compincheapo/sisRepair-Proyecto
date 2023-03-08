<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//agregamos
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use PDF;

class RolController extends Controller
{
    function __construct(){
        $this->middleware('permission:ver-rol|crear-rol|editar-rol|borrar-rol', ['only' => ['index']]);
        $this->middleware('permission:crear-rol', ['only' => ['create', 'store']]); //Métodos asociados al permiso.
        $this->middleware('permission:editar-rol', ['only' => ['edit', 'update']]); 
        $this->middleware('permission:borrar-rol', ['only' => ['destroy']]); 
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $roles = Role::paginate(5);
        $name = $request->name;
        

        if($request->name){
            $roles = Role::where('name', 'like', '%'.$request->name .'%');

            if($request->submitbtn == 'PDF'){
                $roles = $roles->get();
            } elseif($request->submitbtn == 'Filtrar'){
                $roles = $roles->paginate(5);
            }
        } else {
            if($request->submitbtn == 'PDF'){
                $roles = Role::all();
            } elseif($request->submitbtn == 'Filtrar'){
                $roles = Role::paginate(5);
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
                if($key == 'name'){
                    $filtrado = 'Nombre: ' . $value. '.';
                } else {
                    $key = ucfirst($key);
                    $filtrado = $key . ': ' . $value. '.'; 
                }
            }
           }


           if(count($filtros) > 1){
            $filtrado = '';
            foreach($filtros as $key => $value) {
                if($key == 'name'){
                   $key = 'Nombre';
                } else {

                }
                if($key == 'rol'){
                    $rol = Role::findOrfail($value)->first();
                    $value = $rol->name;
                    $key = ucfirst($key);
                }

                $filtrado = $filtrado . $key . ':' . $value . ', ';
            }
            $filtrado = rtrim($filtrado, ", ");
            $filtrado = $filtrado . '.';
           }
                       
            $pdf = PDF::loadView('roles.pdf', compact('roles', 'filtrado'));
            return $pdf->stream();
        } elseif($request->submitbtn == 'Filtrar'){
            return view('roles.index', compact('roles', 'name'));
        } elseif($request->submitbtn == null){
            $roles = Role::paginate(5);
            return view('roles.index', compact('roles', 'name'));
        }
        
        return view('roles.index', compact('roles','name'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permission = Permission::get();            //Al crear un rol, debemos asociarle uno o varios permisos.
        
        return view('roles.create', compact('permission'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, ['name' => 'required|unique:roles,name', 'permission' => 'required']);
        $role = Role::create(['name' => $request->input('name')]);
        $role->syncPermissions($request->input('permission'));

        return redirect()->route('roles.index');
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
        $role = Role::find($id);
        $permission = Permission::get();
        $rolePermissions = DB::table('role_has_permissions')->where('role_has_permissions.role_id', $id)
        ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
        ->all();

        return view('roles.edit', compact('role', 'permission', 'rolePermissions'));

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
        $this->validate($request, ['name' => 'required', 'permission' => 'required']);

        $role = Role::find($id);
        $role->name = $request->input('name');
        $role->save();

        $role->syncPermissions($request->input('permission'));

        return redirect()->route('roles.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('roles')->where('id', $id)->delete();
        return redirect()->route('roles.index');
    }
}
