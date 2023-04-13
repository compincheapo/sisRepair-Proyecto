<?php

namespace App\Http\Controllers;

use CURLFile;
use Illuminate\Http\Request;
use App\Models\InformacionGeneral;

class WhatsappController extends Controller
{
    public function bienvenida(){
    //TOKEN QUE NOS DA FACEBOOK
    $token = 'EABWfGlT9ZCxIBAOlJPxdRUXnNWQ1ddjp0av2vuLuNM8vkQ1LMmxjZAfIbQFW2rPAnu0eoi7Cqj7VHmhzZCwsGKjbKhYKr6sabE67VNMx4ZAis25SYFqaOBacr9vHmsdk4coispNVGOPfeBE1tX4GrZA5PWiYOUQEAjT9TfsyykhjMIRSm8yH3e1pspw3zosJaGJZCNbdyhEwZDZD';
    //NUESTRO TELEFONO
    $telefono = '543758484995';
    //URL A DONDE SE MANDARA EL MENSAJE
    $url = 'https://graph.facebook.com/v16.0/113287718350861/messages';

    //CONFIGURACION DEL MENSAJE
    $mensaje = ''
            . '{'
            . '"messaging_product": "whatsapp", '
            . '"to": "'.$telefono.'", '
            . '"type": "template", '
            . '"template": '
            . '{'
            . '     "name": "hello_world",'
            . '     "language":{ "code": "en_US" } '
            . '} '
            . '}';
    //DECLARAMOS LAS CABECERAS
    $header = array("Authorization: Bearer " . $token, "Content-Type: application/json",);
    //INICIAMOS EL CURL
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $mensaje);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    //OBTENEMOS LA RESPUESTA DEL ENVIO DE INFORMACION
    $response = json_decode(curl_exec($curl), true);
    //IMPRIMIMOS LA RESPUESTA 
    print_r($response);
    //OBTENEMOS EL CODIGO DE LA RESPUESTA
    $status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    //CERRAMOS EL CURL
    curl_close($curl);
    }

    public function mensajePersonalizado($numero, $mensaje){
        //TOKEN QUE NOS DA FACEBOOK
        $token = 'EABWfGlT9ZCxIBAOlJPxdRUXnNWQ1ddjp0av2vuLuNM8vkQ1LMmxjZAfIbQFW2rPAnu0eoi7Cqj7VHmhzZCwsGKjbKhYKr6sabE67VNMx4ZAis25SYFqaOBacr9vHmsdk4coispNVGOPfeBE1tX4GrZA5PWiYOUQEAjT9TfsyykhjMIRSm8yH3e1pspw3zosJaGJZCNbdyhEwZDZD';
        //NUESTRO TELEFONO
        $telefono = $numero;
        //URL A DONDE SE MANDARA EL MENSAJE
        $url = 'https://graph.facebook.com/v16.0/113287718350861/messages';
        //CUERPO MENSAJE
        $body = $mensaje;

        //CONFIGURACION DEL MENSAJE
        $mensaje = ''
                . '{'
                . '"messaging_product": "whatsapp", '
                . '"to": "'.$telefono.'", '
                . '"type": "text", '
                . '"text": '
                . '{'
                . '     "preview_url": "true",'
                . '     "body": "'.$body.'" '
                . '} '
                . '}';
        //DECLARAMOS LAS CABECERAS
        $header = array("Authorization: Bearer " . $token, "Content-Type: application/json",);
        //INICIAMOS EL CURL
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $mensaje);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        //OBTENEMOS LA RESPUESTA DEL ENVIO DE INFORMACION
        $response = json_decode(curl_exec($curl), true);
        //IMPRIMIMOS LA RESPUESTA 
        // print_r($response);
        //OBTENEMOS EL CODIGO DE LA RESPUESTA
        $status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        //CERRAMOS EL CURL
        curl_close($curl);
    }

    public function getIdMediaPDF(){
        $token = 'EABWfGlT9ZCxIBAOlJPxdRUXnNWQ1ddjp0av2vuLuNM8vkQ1LMmxjZAfIbQFW2rPAnu0eoi7Cqj7VHmhzZCwsGKjbKhYKr6sabE67VNMx4ZAis25SYFqaOBacr9vHmsdk4coispNVGOPfeBE1tX4GrZA5PWiYOUQEAjT9TfsyykhjMIRSm8yH3e1pspw3zosJaGJZCNbdyhEwZDZD';
        $phoneId = '113287718350861';
        $target= public_path('assets\pdf\terminosycondiciones.pdf');
        $filename = basename($target);
        $mime=mime_content_type($target);

        $file = new CURLFile($target);
        $file->setMimeType($mime);
        $file->setPostFilename($filename);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://graph.facebook.com/v16.0/'.$phoneId.'/media',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => array("messaging_product" => "whatsapp", "type"=> $mime, "file"=> $file),
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer ".$token,
            ),
        ));
        $resultWhatsAppMedia = json_decode(curl_exec($curl), true);

        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        $MEDIA_OBJECT_ID = $resultWhatsAppMedia['id']; //MEDIA OBJECT ID

        return $MEDIA_OBJECT_ID;
    
    }

    public function envioPDF($numero){
        $url = 'https://graph.facebook.com/v16.0/113287718350861/messages';
        $token = 'EABWfGlT9ZCxIBAOlJPxdRUXnNWQ1ddjp0av2vuLuNM8vkQ1LMmxjZAfIbQFW2rPAnu0eoi7Cqj7VHmhzZCwsGKjbKhYKr6sabE67VNMx4ZAis25SYFqaOBacr9vHmsdk4coispNVGOPfeBE1tX4GrZA5PWiYOUQEAjT9TfsyykhjMIRSm8yH3e1pspw3zosJaGJZCNbdyhEwZDZD';
        $infoGeneral = InformacionGeneral::get()->first();
        $mediaId = $infoGeneral->mediaid;
        $telefono = $numero;
        $FileName="terminosycondiciones";

        $mensaje = ''
                . '{'
                . '"messaging_product": "whatsapp", '
                . '"to": "'.$telefono.'", '
                . '"type": "document", '
                . '"document": '
                . '{'
                . '     "id": "'.$mediaId.'",'
                . '     "caption": "'.$FileName.'" '
                . '} '
                . '}';


         //DECLARAMOS LAS CABECERAS
         $header = array("Authorization: Bearer " . $token, "Content-Type: application/json",);
         //INICIAMOS EL CURL
         $curl = curl_init();
         curl_setopt($curl, CURLOPT_URL, $url);
         curl_setopt($curl, CURLOPT_POSTFIELDS, $mensaje);
         curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
         curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
         //OBTENEMOS LA RESPUESTA DEL ENVIO DE INFORMACION
         $response = json_decode(curl_exec($curl), true);
         //IMPRIMIMOS LA RESPUESTA 
         print_r($response);
         //OBTENEMOS EL CODIGO DE LA RESPUESTA
         $status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
         //CERRAMOS EL CURL
         curl_close($curl);
    }
}
