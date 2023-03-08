@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">Estadísticas: preferencias</h3>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="col-xs-12 col-sm-12 col-md-12 float-left">
                                <form class="form" action="{{ route('estadisticas.graficaTorta')}}" method="GET">
                                <div class="form-group">
                                        <label for="criterio">Criterio</label>
                                        <select id="criterio" name="criterio" class="form-control">
                                            @if(!empty($criterio) && $criterio == 'tipopagos')
                                                <option value="tipopagos" selected>Tipos de Pagos</option>
                                                <option value="presupuesto">Decisión Presupuesto</option>
                                            @endif
                                            @if(!empty($criterio) && $criterio == 'presupuesto')
                                                <option value="tipopagos" >Tipos de Pagos</option>
                                                <option value="presupuesto" selected>Decisión Presupuesto</option>>
                                            @endif
                        
                                    </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="fechadesde">Fecha Desde</label>
                                        <input type="date" name="fechadesde" value= "{{$fechadesde}}" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="fechahasta">Fecha Hasta</label>
                                        <input type="date" name="fechahasta" value= "{{$fechahasta}}" class="form-control">
                                    </div>
                                    <input type="submit" name="submitbtn" value="Filtrar" class="btn btn-warning btn btn-icon icon-left"></input>
                                    <a href="{{ url('/graficos/torta') }}" class="btn btn-info">Limpiar</a>
                                </form>
                                <div>
                                    <canvas id="myChart"></canvas>
                                </div>
                                </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-deferred@1.0.2/dist/chartjs-plugin-deferred.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

  <script>
    var datasPago = <?php echo json_encode($datasPago)?>;
    var datasPresupuesto = <?php echo json_encode($datasPresupuesto)?>;
    var criterio = <?php echo json_encode($criterio)?>;
    const ctx = document.getElementById('myChart');

    if(criterio === 'tipopagos'){
        datasTorta = datasPago;
        labels = ['Mercadopago Desde Plataforma','Mercadopago Presencial','Efectivo en local'];
        background = ['rgb(54, 162, 235)','rgb(255, 99, 132)','rgb(255, 205, 86)'];
    } 

    if(criterio === 'presupuesto'){
        datasTorta = datasPresupuesto;
        labels = ['Presupuestos Aceptados','Presupuestos Rechazados'];
        background = ['rgb(54, 162, 235)','rgb(255, 99, 132)'];
    }

    chart = new Chart(ctx, {
    type: 'pie',
    data: {
        labels: labels,
        datasets: [{
            label: 'My First Dataset',
            data: datasTorta,
            backgroundColor: background,
            hoverOffset: 4
        }]
    },
     options: {
            legend: {
                display: true
            },
            events: true,
            animation: {
                onComplete: function () {
                    var ctx = this.chart.ctx;
                    ctx.font='14px LatoRegular, Helvetica,sans-serif';
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'bottom';
                    this.data.datasets.forEach(function (dataset) {
                        for (var i = 0; i < dataset.data.length; i++) {
                            var m = dataset._meta[Object.keys(dataset._meta)[0]].data[i]._model,
                                t = dataset._meta[Object.keys(dataset._meta)[0]].total,
                                 mR = m.innerRadius + (m.outerRadius - m.innerRadius) / 2,
                                sA = m.startAngle,
                                eA = m.endAngle,
                                mA = sA + (eA - sA)/2;
                            var x = mR * Math.cos(mA);
                            var y = mR * Math.sin(mA);
                            ctx.fillStyle = '#fff';

                            var p = String(Math.round(dataset.data[i]/t*100)) + "%";
                            if(dataset.data[i] > 0) {
                                ctx.fillText(dataset.data[i], m.x + x, m.y + y-10);
                                ctx.fillText(p, m.x + x, m.y + y + 5);
                            }
                        }
                    });
                }
            }
        }
    });
    chart.render();


    </script>
@endsection