<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>Reporte Ordenes de Servicio</title>

  <style>
    .page_break {
      page-break-before: always;
    }
    @page { margin: 185px 20px; }
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
        
  </style>
</head>
<body>
	<div id="header">
            <table style="width:100%">
                  <tr>
                    <td style="width: 80%; vertical-align:top">
                      <strong>SIC Servicios Informáticos </strong><br>
                      Apóstoles Misiones. <br>
                      Miguel Zubrzycki 221<br>
                      Celular: (3758) 488098 <br>
                    </td>
                    <td style="width: 20%;">
                    <img src="{{ public_path('img/logo2.jpg') }}" alt="logo" width="145px">
                    </td>
                  </tr>
                  <tr>
                      <td style="width: 100%;text-align: left;font-size:10px" colspan="2">
                      <b>Datos filtrados por: </b>
                      <span>{{$filtrado}}</span>
                      </td>
                    </tr>
              </table>  
  </div>
              


    <main style="margin-top:8px">
        <table class="table" style="font-size:10px">
            <thead>
               
                <tr>
                    <th scope="col">Número de Orden</th>
                    <th scope="col">Cliente</th>
                    <th scope="col">Servicio</th>
                    <th scope="col">Fecha de Compromiso</th>   
                    <th scope="col">Estado</th>   
                    <th scope="col">Fecha Fin</th>   
                </tr>
            </thead>
           <tbody>
             @foreach($ordenes as $orden)
              <tr>
                  <td>{{$orden->id}}</td>
                  <td>{{$orden->equipo->user->name}} {{$orden->equipo->user->lastname}}</td>
                  <td>{{$orden->servicio->nombre}}</td>
                  <td>{{$orden->fechacompromiso}}</td>
                  @if($orden->finalizado == 1)
                      <td>Finalizado</td>
                      <td>{{$orden->fechafin}}</td>
                    
                  @elseif($orden->finalizado == 0)
                      <td>No Finalizado</td>
                      <td></td>
                  @endif
              </tr>
             @endforeach
            </tbody>
        </table>
    </main>
   

    <script type="text/php">
    if (isset($pdf)) {
                $text = "Página {PAGE_NUM} de {PAGE_COUNT}";
                $size = 10;
                $font = $fontMetrics->getFont("Arial");
                $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
                $x = ($pdf->get_width() - $width);
                $y = $pdf->get_height() - 35;
                $pdf->page_text($x, $y, $text, $font, $size);
                $pdf->page_text(40, $y, "Generado por: ". Auth::user()->lastname . " " . Auth::user()->name." - ".now()->format('d/m/Y H:i:s'), $font, $size);
            }
	</script>
</body>
</html>
