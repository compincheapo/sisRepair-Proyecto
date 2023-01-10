<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OwenIt\Auditing\Models\Audit;
use App\Models\User;

class AuditoriaController extends Controller
{
    public function index(Request $request){

        $auditorias = Audit::orderBy('id', 'desc')->paginate(5);
        $usuarios = User::select('id', 'name', 'lastname')->get();
        $modelos = Audit::pluck('auditable_type', 'auditable_type');
        
        // if(!empty($request)){
            //     dd($request->all());
            // }
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
                        ->where('created_at', '<=', $request->hasta)
                        ->paginate(5);   

                    } else if(empty($request->usuario) && empty($request->modelo)){
                        $auditorias = Audit::where('user_id','like','%'.$request->usuario .'%')
                        ->where('auditable_type','like','%'.$request->modelo .'%')
                        ->where('event', 'like', '%'.$request->accion .'%')
                        ->where('created_at', '>=', $request->desde)
                        ->where('created_at', '<=', $request->hasta)
                        ->paginate(5);

                    } else if(empty($request->usuario) && !empty($request->modelo)){
                        $auditorias = Audit::where('user_id','like','%'.$request->usuario .'%')
                        ->where('auditable_type',$request->modelo)
                        ->where('event', 'like', '%'.$request->accion .'%')
                        ->where('created_at', '>=', $request->desde)
                        ->where('created_at', '<=', $request->hasta)
                        ->paginate(5);

                    }  else if(!empty($request->usuario) && empty($request->modelo)){
                        $auditorias = Audit::where('user_id',$request->usuario)
                        ->where('auditable_type','like','%'.$request->modelo .'%')
                        ->where('event', 'like', '%'.$request->accion .'%')
                        ->where('created_at', '>=', $request->desde)
                        ->where('created_at', '<=', $request->hasta)
                        ->paginate(5);

                    } else if(!empty($request->usuario) && !empty($request->modelo)){
                        $auditorias = Audit::where('user_id', $request->usuario)
                        ->where('auditable_type', $request->modelo)
                        ->where('event', 'like', '%'.$request->accion .'%')
                        ->where('created_at', '>=', $request->desde)
                        ->where('created_at', '<=', $request->hasta)
                        ->paginate(5);
                        

                    }  else if(!empty($request->accion) && empty($request->modelo) && empty($request->usuario)){
                        $auditorias = Audit::where('user_id','like',$request->usuario)
                        ->where('auditable_type','like','%'.$request->modelo .'%')
                        ->where('event', $request->accion)
                        ->where('created_at', '>=', $request->desde)
                        ->where('created_at', '<=', $request->hasta)
                        ->paginate(5);
                    } 

            } else if(!empty($request->desde) && empty($request->hasta)){
                    $auditorias = Audit::where('user_id','like','%'.$request->usuario .'%')
                    ->where('auditable_type','like','%'.$request->modelo .'%')
                    ->where('event', 'like', '%'.$request->accion .'%')
                    ->where('created_at', '>=', $request->desde)
                    ->paginate(5);

                    if($request->usuario && $request->modelo){

                        $auditorias = Audit::where('user_id', $request->usuario)
                        ->where('auditable_type', $request->modelo)
                        ->where('event', 'like', '%'.$request->accion .'%')
                        ->where('created_at', '>=', $request->desde)
                        ->paginate(5);
                    } else if($request->usuario){
                        $auditorias = Audit::where('user_id', $request->usuario)
                        ->where('auditable_type','like','%'.$request->modelo .'%')
                        ->where('event', 'like', '%'.$request->accion .'%')
                        ->where('created_at', 'like', '%'.$request->hasta .'%')
                        ->where('created_at', '>=', $request->desde)
                        ->paginate(5);
                    } else if($request->modelo){
                        $auditorias = Audit::where('user_id','like','%'.$request->usuario .'%')
                        ->where('auditable_type', $request->modelo)
                        ->where('event', 'like', '%'.$request->accion .'%')
                        ->where('created_at', 'like', '%'.$request->hasta .'%')
                        ->where('created_at', '>=', $request->desde)
                        ->paginate(5);
                        // dd('hola');
                    } 

            } else if(!empty($request->hasta) && empty($request->desde)) {

                    $auditorias = Audit::where('user_id','like','%'.$request->usuario .'%')
                    ->where('auditable_type','like','%'.$request->modelo .'%')
                    ->where('event', 'like', '%'.$request->accion .'%')
                    ->where('created_at', 'like', '%'.$request->desde .'%')
                    ->where('created_at', '<=', $request->hasta)
                    ->paginate(5);

                    if($request->usuario && $request->modelo){
                        $auditorias = Audit::where('user_id', $request->usuario)
                        ->where('auditable_type', $request->modelo)
                        ->where('event', 'like', '%'.$request->accion .'%')
                        ->where('created_at', '<=', $request->hasta)
                        ->paginate(5);
                    } else if($request->usuario){
                        $auditorias = Audit::where('user_id', $request->usuario)
                        ->where('auditable_type','like','%'.$request->modelo .'%')
                        ->where('event', 'like', '%'.$request->accion .'%')
                        ->where('created_at', '<=', $request->hasta)
                        ->paginate(5);
                    } else if($request->modelo){
                        $auditorias = Audit::where('user_id','like','%'.$request->usuario .'%')
                        ->where('auditable_type', $request->modelo)
                        ->where('event', 'like', '%'.$request->accion .'%')
                        ->where('created_at', '<=', $request->hasta)
                        ->paginate(5);
                        // dd('hola');
                    } 

        
            } else if(empty($request->hasta) && empty($request->desde)) {
                $auditorias = Audit::where('user_id','like','%'.$usuarioData .'%')
                ->where('auditable_type','like','%'.$request->modelo .'%')
                ->where('event', 'like', '%'.$request->accion .'%')
                ->paginate(5);

                if($request->usuario && $request->modelo){
                    $auditorias = Audit::where('user_id', $request->usuario)
                    ->where('auditable_type', $request->modelo)
                    ->where('event', 'like', '%'.$request->accion .'%')
                    ->paginate(5);
                } else if($request->usuario){
                    $auditorias = Audit::where('user_id', $request->usuario)
                    ->where('auditable_type','like','%'.$request->modelo .'%')
                    ->where('event', 'like', '%'.$request->accion .'%')
                    ->paginate(5);
                } else if($request->modelo){
                    $auditorias = Audit::where('user_id','like','%'.$request->usuario .'%')
                    ->where('auditable_type', $request->modelo)
                    ->where('event', 'like', '%'.$request->accion .'%')
                    ->paginate(5);
                    // dd('hola');
                } 
        }else {
               
                    if($request->usuario){
                        $auditorias = Audit::where('user_id', $request->get('usuario'))
                        ->where('auditable_type','like', '%'.$request->modelo .'%')
                        ->where('event', 'like', '%'.$request->accion .'%')
                        ->paginate(5);
                    }
                    if($request->modelo){
                        $auditorias = Audit::where('auditable_type','like','%'.$request->get('usuario') .'%')
                        ->where('auditable_type',$request->modelo)
                        ->where('event', 'like', '%'.$request->accion .'%')
                        ->paginate(5);
                    }
                    if($request->accion){
                        $auditorias = Audit::where('user_id','like','%'.$request->usuario .'%')
                        ->where('auditable_type','like', '%'.$request->modelo .'%')
                        ->where('event', $request->accion)
                        ->paginate(5);
                    }
                    if($request->accion && $request->modelo){
                        $auditorias = Audit::where('user_id','like','%'.$request->usuario .'%')
                        ->where('auditable_type', $request->modelo)
                        ->where('event', $request->accion)
                        ->paginate(5);
                    }          
            } 
                
            
        }
        return view('auditorias.index', compact('auditorias', 'usuarios', 'modelos', 'usuarioData', 'modeloData', 'accionData', 'desdeData', 'hastaData'));

        }
            
        
}

