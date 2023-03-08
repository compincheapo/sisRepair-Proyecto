@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">Estadísticas: cantidades</h3>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="col-xs-12 col-sm-12 col-md-12 float-left">
                                <form class="form" action="{{ route('estadisticas.graficaBarras')}}" method="GET">
                                    <div class="form-group">
                                        <label for="modelo">Criterio</label>
                                        <select id="modelo" name="modelo" class="form-control">
                                            @if(!empty($modelo) && $modelo == 'users')
                                                <option value="users" selected>Usuarios</option>
                                                <option value="repuestos">Repuestos</option>
                                                <option value="ordenesservicio">Ordenes de Servicio</option>
                                                <option value="equipos">Equipos</option>
                                                <option value="pagos">Pagos</option>
                                            @endif
                                            @if(!empty($modelo) && $modelo == 'repuestos')
                                                <option value="users">Usuarios</option>
                                                <option value="repuestos" selected>Repuestos</option>
                                                <option value="ordenesservicio">Ordenes de Servicio</option>
                                                <option value="equipos">Equipos</option>
                                                <option value="pagos">Pagos</option>
                                            @endif
                                            @if(!empty($modelo) && $modelo == 'ordenesservicio')
                                                <option value="users">Usuarios</option>
                                                <option value="repuestos">Repuestos</option>
                                                <option value="ordenesservicio" selected>Ordenes de Servicio</option>
                                                <option value="equipos">Equipos</option>
                                                <option value="pagos">Pagos</option>
                                            @endif
                                            @if(!empty($modelo) && $modelo == 'equipos')
                                                <option value="users">Usuarios</option>
                                                <option value="repuestos">Repuestos</option>
                                                <option value="ordenesservicio">Ordenes de Servicio</option>
                                                <option value="equipos" selected>Equipos</option>
                                                <option value="pagos">Pagos</option>
                                            @endif
                                            @if(!empty($modelo) && $modelo == 'pagos')
                                                <option value="users">Usuarios</option>
                                                <option value="repuestos">Repuestos</option>
                                                <option value="ordenesservicio">Ordenes de Servicio</option>
                                                <option value="equipos">Equipos</option>
                                                <option value="pagos" selected>Pagos</option>
                                            @endif  
                                            
                                    </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="anio">Año</label>
                                        <select id="anio" name="anio" class="form-control">
                                            @foreach($anios as $anio)
                                                @if($anio == $anioSelected)
                                                    <option value="{{$anio}}" selected>{{$anio}}</option>
                                                @else
                                                    <option value="{{$anio}}">{{$anio}}</option>
                                                @endif
                                            @endforeach
                                    </select>
                                    </div>
                                    <input type="submit" name="submitbtn" value="Filtrar" class="btn btn-warning btn btn-icon icon-left"></input>
                                    <a href="{{ url('/graficos/barra') }}" class="btn btn-info">Limpiar</a>
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
        var datasBarra = <?php echo json_encode($datas)?>;
        var modelo = <?php echo json_encode($modelo)?>;

        if(modelo == 'users'){
            modelo = 'Usuarios';
        } else if(modelo == 'repuestos'){
            modelo = 'Repuestos';
        } else if(modelo == 'ordenesservicio'){
            modelo = 'Ordenes de Servicio';
        } else if(modelo == 'equipos'){
            modelo = 'Equipos';
        } else if(modelo == 'pagos'){
            modelo = 'Pagos';
        }

        const ctx = document.getElementById('myChart');

        new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            datasets: [{
            label: 'Número de ' +  modelo  + ' dados de alta en el año 2023.',
            data: datasBarra,
            borderWidth: 1,
            backgroundColor: 
            ["rgb(239, 83, 80)",
            "rgb(236, 64, 122)",
            "rgb(171, 71, 188)",
            "rgb(92, 107, 192)",
            "rgb(102, 187, 106)",
            "rgb(212, 225, 87)",
            "rgb(255, 238, 88)",
            "rgb(255, 167, 38)",
            "rgb(161, 136, 127)",
            "rgb(189, 189, 189)",
            "rgb(189, 189, 189)",
            "rgb(120, 144, 156)" ], 
            }]
        },
        options: {
            scales: {
            y: {
                beginAtZero: true
            }
            }
        }
        });
    </script>
@endsection