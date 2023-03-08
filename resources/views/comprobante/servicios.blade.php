<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>Comprobante Pago</title>

  <style>
    .page_break {
      page-break-before: always;
    }
    @page { margin: 185px 20px;  size: 15cm 15cm; }
    #header { position: fixed; left: 0px; right: 0px; height: 200px; text-align: center; }


        #header { position: fixed; left: 0px; top: -180px; right: 0px; text-align: center; }
        
        body {
          font: normal medium/1.4 sans-serif;
        }
        table {
          border-collapse: collapse;
          width: 100%;
        }
        th, td {
          padding: 0.25rem;
          text-align: left;
          border: 1px solid #ccc;
        }
        col:nth-child(3) {
          background: yellow; 
        }

        footer {
            position: fixed;
            bottom: 0cm;
            left: 0cm;
            right: 0cm;
            height: 2cm;
            background-color: #F93855;
            color: white;
            text-align: center;
            line-height: 35px;
        }

        .inner_fixed {
            box-sizing: border-box;
            height: 150px;
            border: 1px solid #ccc;
        }
        
  </style>
</head>
<body>
	<div id="header">
        <table style="width:100%" >
                        <tr>
                            <td style="width: 80%; vertical-align:top;font-size: 10pt">
                            <strong style="font-size: 12pt">{{!empty($informacionGeneral) ? $informacionGeneral->nombre : ''}} </strong><br>
                            {{!empty($informacionGeneral) ? $informacionGeneral->localidad : ''}} {{!empty($informacionGeneral) ? $informacionGeneral->provincia : ''}} <br>
                            {{!empty($informacionGeneral) ? $informacionGeneral->direccion : ''}}<br>
                            Celular: {{!empty($informacionGeneral) ? $informacionGeneral->celular : ''}} <br>
                            CUIT: {{!empty($informacionGeneral) ? $informacionGeneral->cuit : ''}} <br>
                            </td>
                            <td style="width: 20%;">
                            <img src="{{ public_path('img/logo2.jpg') }}" alt="logo" width="115px">
                            </td>
                        </tr>

                        <tr>
                            <td style="width: 100%;text-align: center; font-weight: bold;line-height:15px;" colspan="2">
                            <strong style="font-size: 16pt;">Comprobante de pago - {{$servicio}} </strong><br>
                            <strong style="font-size: 8pt;">Nro de Comprobante: {{$comprobante->id}} </strong><br>
                            <strong style="font-size: 8pt;">Cliente: {{$user}} </strong><br>
                            <strong style="font-size: 8pt;">Fecha pago: {{$pago->fechapago}} </strong><br>
                            <strong style="font-size: 8pt;">Tipo pago: {{$tipopago}}</strong><br>
                            </td>
                           
                            </td>
                            
                        </tr>
                        <tr>
                            <td style="width: 100%;text-align: center;  font-size: 10pt;" colspan="2">
                            <strong>Importante:</strong>
                            <span>El presente comprobante ha sido emitido de acuerdo con los servicios prestados
                                por SIC Servicios Informáticos y debe ser utilizada exclusivamente para el pago.</span><br>
                            </td>
                           
                            </td>
                        </tr>

                        
        </table>  
        <div class="inner_fixed" style="text-align: left">
          @foreach($pago->ordenespago as $orden)
               <span style="float: left; margin-left:5px;font-size: 10pt;">Orden de Servicio {{$orden->id}}</span>
              @if($orden->id_servicio == 2)
                <span style="float: right; margin-right:10px;font-size: 10pt;">${{$orden->equipo->orden()->where('id_servicio', 1)->orderBy('created_at', 'desc')->first()->presupuestoOrden->presupuesto}}</span>
                <br>
              @elseif($orden->id_servicio == 1)
                
                <span style="float: right; margin-right:10px;font-size: 10pt;">${{$pago->precio/count($pago->ordenespago()->get())}}</span>
                <br>
              @endif
            @endforeach
        </div>
        <div class="inner_fixed" style="text-align: left;  height: 30px; font-size: 10pt;">
            <strong style="float: left; margin-left:10px; margin-top:5px">Total</strong>
            @if($orden->id_servicio == 2)
              <strong style="float: right; margin-right:10px; margin-top:5px">${{$pago->precio}}</strong>
            @elseif($orden->id_servicio == 1)
              <strong style="float: right; margin-right:10px; margin-top:5px">${{$pago->precio}}</strong>
            @endif
        </div>
        <div class="inner_fixed" style="border-top:0px;font-size: 10pt; height: 50px;">
            <strong>Recordar:</strong>
            <span>No tirar este comprobante, ya que puede utilizarlo para realizar un reclamo respecto a la Orden de Servicio prestada.</span><br>
        </div>
    </div>

    <script type="text/php">
    if (isset($pdf)) {
                  $text = "";
                  $size = 10;
                $font = $fontMetrics->getFont("Arial");
                $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
                $x = ($pdf->get_width() - $width);
                $y = $pdf->get_height() - 35;
                $pdf->page_text($x, $y, $text, $font, $size);
                $pdf->page_text(40, $y, "Comprobante generado por: ". Auth::user()->lastname . ' '. Auth::user()->name. ' '." - ".now()->format('d/m/Y H:i:s'), $font, $size);
            }
	</script>
</body>
</html>