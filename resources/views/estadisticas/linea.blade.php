@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">Estadísticas: ingresos</h3>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="col-xs-12 col-sm-12 col-md-12 float-left">
                                <form class="form" action="{{ route('estadisticas.graficaLinea')}}" method="GET">
                                <div class="form-group">
                                        <label for="criterio">Criterio</label>
                                        <select id="criterio" name="criterio" class="form-control">
                                            @if(!empty($criterio) && $criterio == 'total')
                                                <option value="total" selected>Total</option>
                                                <option value="servicio">Por Servicio</option>
                                            @endif
                                            @if(!empty($criterio) && $criterio == 'servicio')
                                                <option value="total" >Total</option>
                                                <option value="servicio" selected>Por Servicio</option>
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
                                    <a href="{{ url('/graficos/linea') }}" class="btn btn-info">Limpiar</a>
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
    var datasDiagnostico = <?php echo json_encode($datasDiagnostico)?>;
    var datasReparacion = <?php echo json_encode($datasReparacion)?>;
    var datasTotal = <?php echo json_encode($datasTotal)?>;
    var criterio = <?php echo json_encode($criterio)?>;

    if(criterio == 'total'){
        dataSetTotal = [{
            label: 'Ingresos Total',
            data: datasTotal,
            fill: false,
            borderColor: 'rgb(75, 192, 192)',
            tension: 0.1
        }];

    }

    if(criterio == 'servicio'){
        dataSetTotal = [{
            label: 'Ingresos por Servicios de Diagnóstico',
            data: datasDiagnostico,
            fill: false,
            borderColor: 'rgb(75, 192, 192)',
            tension: 0.1
        }, {
            label: 'Ingresos por Servicios de Reparación',
            data: datasReparacion,
            fill: false,
            borderColor: 'rgb(100, 100, 100)',
            tension: 0.1
        }];
    }

    const ctx = document.getElementById('myChart');

    chart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        datasets: dataSetTotal, 
    },

    });
    chart.render();


    </script>
@endsection