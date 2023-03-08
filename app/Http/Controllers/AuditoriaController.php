<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OwenIt\Auditing\Models\Audit;
use App\Models\User;
use PDF;

class AuditoriaController extends Controller
{
    public function index(Request $request){

        $auditorias = Audit::orderBy('id', 'desc');
        $usuarios = User::select('id', 'name', 'lastname')->get();
        $modelos = Audit::pluck('auditable_type', 'auditable_type');
        

        $usuario = $request->usuario;
        $modeloData = $request->modelo;
        $accionData = $request->accion;
        $desdeData = $request->desde;
        $hastaData = $request->hasta;
        $usuarioData = null;
        
        if($request->usuario){
            $usuarioData = User::where('id', $request->usuario)->select('id', 'name', 'lastname')->first();
        } 

        if($request->usuario || $request->modelo || $request->accion || $request->desde || $request->hasta){

            if(!empty($request->desde) && !empty($request->hasta)){
                if($request->usuario && $request->accion && $request->modelo){
                    $auditorias = Audit::where('user_id', $request->usuario)
                    ->where('auditable_type', '=', $request->modelo)
                    ->where('event', $request->accion)
                    ->where('created_at', '>=', $request->desde)
                    ->where('created_at', '<=', $request->hasta);
                       

                } else if(empty($request->usuario) && empty($request->modelo)){
                    $auditorias = Audit::where('user_id','like','%'.$request->usuario .'%')
                    ->where('auditable_type','like','%'.$request->modelo .'%')
                    ->where('event', 'like', '%'.$request->accion .'%')
                    ->where('created_at', '>=', $request->desde)
                    ->where('created_at', '<=', $request->hasta);

                } else if(empty($request->usuario) && !empty($request->modelo)){
                    $auditorias = Audit::where('user_id','like','%'.$request->usuario .'%')
                    ->where('auditable_type',$request->modelo)
                    ->where('event', 'like', '%'.$request->accion .'%')
                    ->where('created_at', '>=', $request->desde)
                    ->where('created_at', '<=', $request->hasta);

                }  else if(!empty($request->usuario) && empty($request->modelo)){
                    $auditorias = Audit::where('user_id',$request->usuario)
                    ->where('auditable_type','like','%'.$request->modelo .'%')
                    ->where('event', 'like', '%'.$request->accion .'%')
                    ->where('created_at', '>=', $request->desde)
                    ->where('created_at', '<=', $request->hasta);

                } else if(!empty($request->usuario) && !empty($request->modelo)){
                    $auditorias = Audit::where('user_id', $request->usuario)
                    ->where('auditable_type', $request->modelo)
                    ->where('event', 'like', '%'.$request->accion .'%')
                    ->where('created_at', '>=', $request->desde)
                    ->where('created_at', '<=', $request->hasta);
                    

                }  else if(!empty($request->accion) && empty($request->modelo) && empty($request->usuario)){
                    $auditorias = Audit::where('user_id','like',$request->usuario)
                    ->where('auditable_type','like','%'.$request->modelo .'%')
                    ->where('event', $request->accion)
                    ->where('created_at', '>=', $request->desde)
                    ->where('created_at', '<=', $request->hasta);
                } 

        } else if(!empty($request->desde) && empty($request->hasta)){
                $auditorias = Audit::where('user_id','like','%'.$request->usuario .'%')
                ->where('auditable_type','like','%'.$request->modelo .'%')
                ->where('event', 'like', '%'.$request->accion .'%')
                ->where('created_at', '>=', $request->desde);

                if($request->usuario && $request->modelo){

                    $auditorias = Audit::where('user_id', $request->usuario)
                    ->where('auditable_type', $request->modelo)
                    ->where('event', 'like', '%'.$request->accion .'%')
                    ->where('created_at', '>=', $request->desde);
                   
                } else if($request->usuario){
                    $auditorias = Audit::where('user_id', $request->usuario)
                    ->where('auditable_type','like','%'.$request->modelo .'%')
                    ->where('event', 'like', '%'.$request->accion .'%')
                    ->where('created_at', 'like', '%'.$request->hasta .'%')
                    ->where('created_at', '>=', $request->desde);
                    
                } else if($request->modelo){
                    $auditorias = Audit::where('user_id','like','%'.$request->usuario .'%')
                    ->where('auditable_type', $request->modelo)
                    ->where('event', 'like', '%'.$request->accion .'%')
                    ->where('created_at', 'like', '%'.$request->hasta .'%')
                    ->where('created_at', '>=', $request->desde);
                } 

        } else if(!empty($request->hasta) && empty($request->desde)) {

                $auditorias = Audit::where('user_id','like','%'.$request->usuario .'%')
                ->where('auditable_type','like','%'.$request->modelo .'%')
                ->where('event', 'like', '%'.$request->accion .'%')
                ->where('created_at', 'like', '%'.$request->desde .'%')
                ->where('created_at', '<=', $request->hasta);

                if($request->usuario && $request->modelo){
                    $auditorias = Audit::where('user_id', $request->usuario)
                    ->where('auditable_type', $request->modelo)
                    ->where('event', 'like', '%'.$request->accion .'%')
                    ->where('created_at', '<=', $request->hasta);
                    
                } else if($request->usuario){
                    $auditorias = Audit::where('user_id', $request->usuario)
                    ->where('auditable_type','like','%'.$request->modelo .'%')
                    ->where('event', 'like', '%'.$request->accion .'%')
                    ->where('created_at', '<=', $request->hasta);
                   
                } else if($request->modelo){
                    $auditorias = Audit::where('user_id','like','%'.$request->usuario .'%')
                    ->where('auditable_type', $request->modelo)
                    ->where('event', 'like', '%'.$request->accion .'%')
                    ->where('created_at', '<=', $request->hasta);    
                } 

    
        } else if(empty($request->hasta) && empty($request->desde)) {
            $auditorias = Audit::where('user_id','like','%'.$usuarioData .'%')
            ->where('auditable_type','like','%'.$request->modelo .'%')
            ->where('event', 'like', '%'.$request->accion .'%');
            
            if($request->usuario && $request->modelo){
                $auditorias = Audit::where('user_id', $request->usuario)
                ->where('auditable_type', $request->modelo)
                ->where('event', 'like', '%'.$request->accion .'%');
                
            } else if($request->usuario){
                $auditorias = Audit::where('user_id', $request->usuario)
                ->where('auditable_type','like','%'.$request->modelo .'%')
                ->where('event', 'like', '%'.$request->accion .'%');
               
            } else if($request->modelo){
                $auditorias = Audit::where('user_id','like','%'.$request->usuario .'%')
                ->where('auditable_type', $request->modelo)
                ->where('event', 'like', '%'.$request->accion .'%');
            } 
         }else {
            
                if($request->usuario){
                    $auditorias = Audit::where('user_id', $request->get('usuario'))
                    ->where('auditable_type','like', '%'.$request->modelo .'%')
                    ->where('event', 'like', '%'.$request->accion .'%');
                    
                }
                if($request->modelo){
                    $auditorias = Audit::where('auditable_type','like','%'.$request->get('usuario') .'%')
                    ->where('auditable_type',$request->modelo)
                    ->where('event', 'like', '%'.$request->accion .'%');
                    
                }
                if($request->accion){
                    $auditorias = Audit::where('user_id','like','%'.$request->usuario .'%')
                    ->where('auditable_type','like', '%'.$request->modelo .'%')
                    ->where('event', $request->accion);
                    ;
                }
                if($request->accion && $request->modelo){
                    $auditorias = Audit::where('user_id','like','%'.$request->usuario .'%')
                    ->where('auditable_type', $request->modelo)
                    ->where('event', $request->accion);
                }          
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
                    
                    if($key == 'usuario'){
                      $selectedUser = User::findOrfail($value)->where('id', $value)->first();
                      $value = $selectedUser->name . ' ' . $selectedUser->lastname;
                    }

                    $key = ucfirst($key);
                    $filtrado = $key . ': ' . $value. '.'; 
                }
           }

           if(count($filtros) > 1){
                $filtrado = '';
                foreach($filtros as $key => $value) {
                      if($key == 'usuario'){
                        $selectedUser = User::findOrfail($value)->where('id', $value)->first();
                        $value = $selectedUser->name . ' ' . $selectedUser->lastname;
                      }

                    $key = ucfirst($key);
                    $filtrado = $filtrado . $key . ':' . $value . ', ';
                }
                $filtrado = rtrim($filtrado, ", ");
                $filtrado = $filtrado . '.';
           }
            $auditorias = $auditorias->get();    
            $pdf = PDF::loadView('auditorias.pdf', compact('auditorias', 'filtrado'));
            return $pdf->stream();
        } elseif($request->submitbtn == 'Filtrar'  || $request->submitbtn == null){
            $auditorias = $auditorias->paginate(5);
            return view('auditorias.index', compact('auditorias', 'usuarios', 'modelos', 'usuarioData', 'modeloData', 'accionData', 'desdeData', 'hastaData'));
        }

        }
            
        
}

