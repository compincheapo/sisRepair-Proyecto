<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF</title>
    

    <style>
        .page-break {
            page-break-after: always;
        }
        .bg-grey {
            background: #F3F3F3;
        }
        .text-right {
            text-align: right;
        }
        .w-full {
            width: 100%;
        }
        .small-width {
            width: 15%;
        }
        .invoice {
            background: white;
            border: 1px solid #CCC;
            font-size: 14px;
            padding: 48px;
            margin: 20px 0;
        }
    </style>

</head>
<body >
    
<div class="container container-smaller">
    <div class="row">

      <div class="col-lg-10 col-lg-offset-1">
        <header>
        <div class="row">
              <div class="col-sm-6 d-inline" style="text-align: left;">
                <address>
                  <strong>SIC Servicios Informáticos </strong><br>
                  Apóstoles Misiones. <br>
                  Miguel Zubrzycki 221<br>
                  Celular: (3758) 488098 <br>
                </address>
              </div>

              <div class="col-sm-4 d-inline" style="text-align: right; ">
                <img src="https://scontent.fpss4-1.fna.fbcdn.net/v/t39.30808-6/304777660_516727370453388_6671136609261858505_n.jpg?_nc_cat=107&ccb=1-7&_nc_sid=09cbfe&_nc_ohc=ElNJgyrKfHQAX9ohehj&_nc_ht=scontent.fpss4-1.fna&oh=00_AT9w27ndCJ-UT_PPzryQWdHXSXGLMFTxoZ23J1J0hyBkFQ&oe=6343429B" alt="logo" width="100px">
              </div>
            </div>
        </header>
         
      


            <div class="table">
              <table class="table invoice-table">
                <thead style="background: #F5F5F5;">
                  <tr>
                    <th>Item List</th>
                    <th></th>
                    <th class="text-right">Price</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>
                        <strong>Service</strong>
                        <p>Description here. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Expedita perferendis doloribus, quaerat molestias est eum, adipisci dolorem nulla rerum voluptatibus.</p>
                    </td>
                    <td></td>
                    <td class="text-right">$600</td>
                  </tr>

                  <tr>
                    <td>
                        <strong>Service</strong>
                        <p>Description here. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Expedita perferendis doloribus, quaerat molestias est eum, adipisci dolorem nulla rerum voluptatibus.</p>
                    </td>
                    <td></td>
                    <td class="text-right">$600</td>
                  </tr>

                  </tbody>
                </table>
              </div>

              <table class="table invoice-total">
                <tbody>
                  <tr>
                    <td class="text-right"><strong>Balance Due :</strong></td>
                    <td class="text-right small-width">$600</td>
                  </tr>
                </tbody>
              </table>

              <hr>

              <div class="row">
                <div class="col-lg-8">
                  <div class="invbody-terms">
                    Thank you for your business. <br>
                    <br>
                    <h4>Payment Terms and Methods</h4>
                    <p>
                      Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusantium cumque neque velit tenetur pariatur perspiciatis dignissimos corporis laborum doloribus, inventore.
                    </p>
                  </div>
                </div>
              </div>
            </div>
        </div>
      </div>
    </div>
</body>
</html> -->


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>Reporte Usuarios</title>

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

        /* header {
            position: fixed;
            top: 0cm;
            left: 0cm;
            right: 0cm;
            height: 2cm;
            background-color: #F93855;
            color: white;
            text-align: center;
        } */

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
                    <img src="https://scontent.fpss4-1.fna.fbcdn.net/v/t39.30808-6/304777660_516727370453388_6671136609261858505_n.jpg?_nc_cat=107&ccb=1-7&_nc_sid=09cbfe&_nc_ohc=ElNJgyrKfHQAX9ohehj&_nc_ht=scontent.fpss4-1.fna&oh=00_AT9w27ndCJ-UT_PPzryQWdHXSXGLMFTxoZ23J1J0hyBkFQ&oe=6343429B" alt="logo" width="145px">
                    </td>
                  </tr>
              </table>  
  </div>
              


    <main>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Id</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Apellido</th>
                    <th scope="col">Username</th>
                    <th scope="col">Correo</th>
                    <th scope="col">Verified At</th>
                    
                </tr>
            </thead>
           <tbody>
             @foreach($users as $user)
              <tr>
                  <td>{{$user->id}}</td>
                  <td>{{$user->name}}</td>
                  <td>{{$user->lastname}}</td>
                  <td>{{$user->username}}</td>
                  <td>{{$user->email}}</td>
                  <td>{{$user->email_verified_at}}</td>
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
                $pdf->page_text(40, $y, "Generado por: ".Auth::user()->name." - ".now()->format('d/m/Y H:i:s'), $font, $size);
            }
	</script>
</body>
</html>

<!-- <html>
<head>
  <style>
    @page { margin: 180px 50px; }
    #header { position: fixed; left: 0px; top: -180px; right: 0px; height: 150px; background-color: orange; text-align: center; }
    #footer { position: fixed; left: 0px; bottom: -180px; right: 0px; height: 150px; background-color: lightblue; }
    #footer .page:after { content: counter(page, upper-roman); }
  </style>
<body>
  <div id="header">
    <h1>Widgets Express</h1>
  </div>
  <div id="footer">
    <p class="page">Page </p>
  </div>
  <div id="content">
    <p>the first page</p>
    <p style="page-break-before: always;">the second page</p>
  </div>
</body>
</html> -->