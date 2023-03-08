<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GraficoController extends Controller
{
    public function graficaBarras(Request $request){

        
        if(!$request->modelo){
            $modelo = 'users';
        } else {
            $modelo = $request->modelo;
        }

        if($request->modelo == 'pagos'){
            $columna = 'fechapago';
        } else {
            $columna = 'created_at';
        }

        $anioSelected = $request->anio;

        $resultado = DB::table($modelo)
        ->select(DB::raw("COUNT(*) as count"), DB::raw("Month(".$columna.") as month"))
        ->whereYear($columna, date('Y'))
        ->groupBy(DB::raw("Month(".$columna.")"))->pluck('count', 'month');

        $anios =  DB::table($modelo)
        ->select(DB::raw("Year(".$columna.") as year"))
        ->groupBy(DB::raw("Year(".$columna. ")"))->pluck('year');

        $datas = array(0,0,0,0,0,0,0,0,0,0,0,0);

        foreach ($resultado as $key => $value) {
            $datas[$key-1] = $value;
        }

        return view('estadisticas.barras',compact('datas', 'anios', 'modelo', 'anioSelected'));
    }

    public function graficaTorta(Request $request){

        if(!$request->criterio){
            $criterio = 'tipopagos';
        } else {
            $criterio = $request->criterio;
        }

        $fechadesde = $request->fechadesde;
        $fechahasta = $request->fechahasta;

        $presupuestos = DB::table('equipos_estados_users_ordenes')
        ->select(DB::raw("COUNT(*) as count"), 'id_estado')
        ->whereIn('id_estado', [11, 18])
        ->groupBy('id_estado')->pluck('count', 'id_estado')->toArray();

        $pagos = DB::table('pagos')
        ->select(DB::raw("COUNT(*) as count"), 'id_tipopago')
        ->groupBy('id_tipopago')->pluck('count', 'id_tipopago')->toArray();

        $tipospago = DB::table('tipopagos')->pluck('id')->toArray();

        $estados = DB::table('estados')->whereIn('id', [11,18])->pluck('id')->toArray();

        $pagomercadopago = DB::table('pagomercadopago')
        ->select(DB::raw("COUNT(*) as count"))
        ->pluck('count')->first();

        if($request->fechadesde || $request->fechahasta){
            if(!($request->criterio === 'presupuesto')){
                $pagos = DB::table('pagos')
                ->select(DB::raw("COUNT(*) as count"), 'id_tipopago')
                ->when($request->filled('fechadesde'), function ($query) use ($request) {
                    return $query->where('pagos.fechapago', '>=',$request->fechadesde);
                })
                ->when($request->filled('fechahasta'), function ($query) use ($request) {
                    return $query->where('pagos.fechapago', '<=', $request->fechahasta);
                })
                ->groupBy('id_tipopago')->pluck('count', 'id_tipopago')->toArray();
    
                $pagomercadopago = DB::table('pagomercadopago')
                ->when($request->filled('fechadesde'), function ($query) use ($request) {
                    return $query->where('pagomercadopago.created_at', '>=',$request->fechadesde);
                })
                ->when($request->filled('fechahasta'), function ($query) use ($request) {
                    return $query->where('pagomercadopago.created_at', '<=', $request->fechahasta);
                })
                ->select(DB::raw("COUNT(*) as count"))
                ->pluck('count')->first();
            } else {
                $presupuestos = DB::table('equipos_estados_users_ordenes')
                ->select(DB::raw("COUNT(*) as count"), 'id_estado')
                ->whereIn('id_estado', [11, 18])
                ->when($request->filled('fechadesde'), function ($query) use ($request) {
                    return $query->where('equipos_estados_users_ordenes.created_at', '>=',$request->fechadesde);
                })
                ->when($request->filled('fechahasta'), function ($query) use ($request) {
                    return $query->where('equipos_estados_users_ordenes.created_at', '<=', $request->fechahasta);
                })
                ->groupBy('id_estado')->pluck('count', 'id_estado')->toArray();
            }
            
        }

        $datasPago = array(0,0,0);
        if($criterio === 'tipopagos'){
            foreach ($tipospago as $key => $value) {
                if($value == 1){
                    if(array_key_exists($value, $pagos)){
                        $datasPago[2] = $pagos[$value];
                    } else {
                        $datasPago[2] = 0;
                    }
                }
               
                if($value == 2){
                    if(array_key_exists($value, $pagos)){
                        $datasPago[1] = $pagos[$value];
                    } else {
                        $datasPago[1] = 0;
                    }
                }  
            }
            
            $datas[0] = $pagomercadopago;
        } 

        $datasPresupuesto = array(0,0);
        if($criterio === 'presupuesto'){

            foreach ($estados as $key => $value) {
                if($value == 11){
                    if(array_key_exists($value, $presupuestos)){
                        $datasPresupuesto[0] = $presupuestos[$value];
                    } else {
                        $datasPresupuesto[0] = 0;
                    }
                }
               
                if($value == 18){
                    if(array_key_exists($value, $presupuestos)){
                        $datasPresupuesto[1] = $presupuestos[$value];
                    } else {
                        $datasPresupuesto[1] = 0;
                    }
                }  
            }

        }


        

        
        return view('estadisticas.torta', compact('datasPago', 'datasPresupuesto', 'criterio', 'fechadesde', 'fechahasta'));
    }

    public function graficaLinea(Request $request){ 
        $fechadesde = $request->fechadesde;
        $fechahasta = $request->fechahasta;

        if(!$request->criterio){
            $criterio = 'total';
        } else {
            $criterio = $request->criterio;
        }

        $pagosDiagnostico = DB::table('ordenesservicio')
        ->join('ordenservicios_pagos', 'ordenesservicio.id', 'ordenservicios_pagos.id_orden')
        ->groupBy('ordenservicios_pagos.id_pago')
        ->where('ordenesservicio.id_servicio', 1)
        ->pluck('ordenservicios_pagos.id_pago');

        $pagosReparacion = DB::table('ordenesservicio')
        ->join('ordenservicios_pagos', 'ordenesservicio.id', 'ordenservicios_pagos.id_orden')
        ->groupBy('ordenservicios_pagos.id_pago')
        ->where('ordenesservicio.id_servicio', 2)
        ->pluck('ordenservicios_pagos.id_pago');        

        $totalDiagnostico = DB::table('pagos')
        ->select(DB::raw("SUM(pagos.precio) as sum"), DB::raw("Month(pagos.fechapago) as month"))
        ->whereIn('pagos.id', $pagosDiagnostico)
        ->whereYear("pagos.fechapago", date('Y'))
        ->groupBy(DB::raw("Month(pagos.fechapago)"))->pluck('sum', 'month')->toArray();

        $totalReparacion = DB::table('pagos')
        ->select(DB::raw("SUM(pagos.precio) as sum"), DB::raw("Month(pagos.fechapago) as month"))
        ->whereIn('pagos.id', $pagosReparacion)
        ->whereYear("pagos.fechapago", date('Y'))
        ->groupBy(DB::raw("Month(pagos.fechapago)"))->pluck('sum', 'month')->toArray();

        $totalPago = DB::table('pagos')
        ->select(DB::raw("SUM(pagos.precio) as sum"), DB::raw("Month(pagos.fechapago) as month"))
        ->whereYear("pagos.fechapago", date('Y'))
        ->groupBy(DB::raw("Month(pagos.fechapago)"))->pluck('sum', 'month')->toArray();

        if($request->fechadesde || $request->fechahasta){
            if(!($request->criterio === 'total')){
                $totalDiagnostico = DB::table('pagos')
                ->select(DB::raw("SUM(pagos.precio) as sum"), DB::raw("Month(pagos.fechapago) as month"))
                ->whereIn('pagos.id', $pagosDiagnostico)
                ->when($request->filled('fechadesde'), function ($query) use ($request) {
                    return $query->where('pagos.fechapago', '>=',$request->fechadesde);
                })
                ->when($request->filled('fechahasta'), function ($query) use ($request) {
                    return $query->where('pagos.fechapago', '<=', $request->fechahasta);
                })
                ->groupBy(DB::raw("Month(pagos.fechapago)"))->pluck('sum', 'month')->toArray();

                $totalReparacion = DB::table('pagos')
                ->select(DB::raw("SUM(pagos.precio) as sum"), DB::raw("Month(pagos.fechapago) as month"))
                ->whereIn('pagos.id', $pagosReparacion)
                ->when($request->filled('fechadesde'), function ($query) use ($request) {
                    return $query->where('pagos.fechapago', '>=',$request->fechadesde);
                })
                ->when($request->filled('fechahasta'), function ($query) use ($request) {
                    return $query->where('pagos.fechapago', '<=', $request->fechahasta);
                })
                ->groupBy(DB::raw("Month(pagos.fechapago)"))->pluck('sum', 'month')->toArray();
    
            } else {
                $totalPago = DB::table('pagos')
                ->select(DB::raw("SUM(pagos.precio) as sum"), DB::raw("Month(pagos.fechapago) as month"))
                ->when($request->filled('fechadesde'), function ($query) use ($request) {
                    return $query->where('pagos.fechapago', '>=',$request->fechadesde);
                })
                ->when($request->filled('fechahasta'), function ($query) use ($request) {
                    return $query->where('pagos.fechapago', '<=', $request->fechahasta);
                })
                ->groupBy(DB::raw("Month(pagos.fechapago)"))->pluck('sum', 'month')->toArray(); 
            }
            
        }

        $datasDiagnostico = array(0,0,0,0,0,0,0,0,0,0,0,0);

        foreach ($totalDiagnostico as $key => $value) {
            $datasDiagnostico[$key-1] = $value;
        }
        
        $datasReparacion = array(0,0,0,0,0,0,0,0,0,0,0,0);

        foreach ($totalReparacion as $key => $value) {
            $datasReparacion[$key-1] = $value;
        }

        $datasTotal = array(0,0,0,0,0,0,0,0,0,0,0,0);

        foreach ($totalPago as $key => $value) {
            $datasTotal[$key-1] = $value;
        }


        return view('estadisticas.linea', compact('fechadesde', 'fechahasta', 'criterio', 'datasReparacion', 'datasDiagnostico', 'datasTotal'));
    }
}
