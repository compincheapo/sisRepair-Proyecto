<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InformacionGeneral;
use Illuminate\Support\Facades\DB;
use App\Models\Precio;
use PDF;
use Carbon\Carbon;


class InformacionGeneralController extends Controller
{
    public function index(){

       $informacionGeneral = InformacionGeneral::first();
       $precioDiagnostico = Precio::where('id_servicio', 1)->orderBy('created_at', 'desc')->first();
       $diasSemana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
       
       if(!empty($informacionGeneral)){
           $terminos = DB::table('terminos')->where('id_informaciongeneral', $informacionGeneral->id)->orderBy('created_at', 'desc')->get();
       } else {
            $terminos = [];
       }


       return view('informaciongeneral.index', compact('informacionGeneral', 'precioDiagnostico', 'diasSemana', 'terminos'));
    }

    public function actualizarInformacionGeneral(Request $request){
        
        $existeInformacionGeneral = InformacionGeneral::get()->first();
        $fechaAhora = Carbon::now();

        if(empty($existeInformacionGeneral)){
            $informacionGeneral = new InformacionGeneral();
            $informacionGeneral->nombre = $request->nombre;
            $informacionGeneral->provincia = $request->provincia;
            $informacionGeneral->localidad = $request->localidad;
            $informacionGeneral->direccion = $request->direccion;
            $informacionGeneral->cuit = $request->cuit;
            $informacionGeneral->celular = $request->celular;
            $informacionGeneral->diadesde = $request->diadesde;
            $informacionGeneral->diahasta = $request->diahasta;
            $informacionGeneral->horadesde = $request->horadesde;
            $informacionGeneral->horahasta = $request->horahasta;
            $informacionGeneral->cant_notif_cliente = $request->cantidadcliente;
            $informacionGeneral->frecuencia_notif_cliente = $request->frecuenciacliente;
            $informacionGeneral->cant_notif_tercero = $request->cantidadtercero;
            $informacionGeneral->frecuencia_notif_tercero = $request->frecuenciatercero;
            $informacionGeneral->save();

            $informacionGeneralView = $informacionGeneral;
        } else {
            $existeInformacionGeneral->nombre = $request->nombre;
            $existeInformacionGeneral->provincia = $request->provincia;
            $existeInformacionGeneral->localidad = $request->localidad;
            $existeInformacionGeneral->direccion = $request->direccion;
            $existeInformacionGeneral->cuit = $request->cuit;
            $existeInformacionGeneral->celular = $request->celular;
            $existeInformacionGeneral->diadesde = $request->diadesde;
            $existeInformacionGeneral->diahasta = $request->diahasta;
            $existeInformacionGeneral->horadesde = $request->horadesde;
            $existeInformacionGeneral->horahasta = $request->horahasta;
            $existeInformacionGeneral->cant_notif_cliente = $request->cantidadcliente;
            $existeInformacionGeneral->frecuencia_notif_cliente = $request->frecuenciacliente;
            $existeInformacionGeneral->cant_notif_tercero = $request->cantidadtercero;
            $existeInformacionGeneral->frecuencia_notif_tercero = $request->frecuenciatercero;
            $existeInformacionGeneral->update();

            $informacionGeneralView = $existeInformacionGeneral;

            DB::table('terminos')->where('id_informaciongeneral', $informacionGeneralView->id)->delete();
            
        }

        if($request->preciodiagnostico){
            $existPrecio = Precio::where('id_servicio', 1)->orderBy('created_at', 'desc')->first();

            if(empty($existPrecio)){
                $nuevoPrecioDiagnostico = new Precio();
                $nuevoPrecioDiagnostico->precio = $request->preciodiagnostico;
                $nuevoPrecioDiagnostico->id_servicio = 1;
                $nuevoPrecioDiagnostico->save();
            }

            if(!empty($existPrecio)){
                if($existPrecio->precio != $request->preciodiagnostico){
                    $nuevoPrecioDiagnostico = new Precio();
                    $nuevoPrecioDiagnostico->precio = $request->preciodiagnostico;
                    $nuevoPrecioDiagnostico->id_servicio = 1;
                    $nuevoPrecioDiagnostico->save();
                }
            }
        }

        
        $terminosToPDF = [];
        foreach ($request->terminos as $termino) {
            if($termino){
                DB::table('terminos')->insert([
                    'id_informaciongeneral' => $informacionGeneralView->id,
                    'termino' => $termino,
                    'created_at' => $fechaAhora,
                    'updated_at' => $fechaAhora
                ]);

                array_push($terminosToPDF, $termino);
            } 
        }

        $pdf = PDF::loadView('informaciongeneral.pdf', compact('informacionGeneralView', 'terminosToPDF'));

        $path = public_path('\assets\pdf');
        $fileName = 'terminosycondiciones.pdf';
        $pdf->save($path . '/' . $fileName);
        $pdf->download($fileName);

        $infoGeneral = InformacionGeneral::get()->first();
        $mediaid = app('App\Http\Controllers\WhatsappController')->getIdMediaPDF();
        $infoGeneral->mediaid = $mediaid;
        $infoGeneral->update();

        return redirect()->route('informaciongeneral.index');

    }

}
