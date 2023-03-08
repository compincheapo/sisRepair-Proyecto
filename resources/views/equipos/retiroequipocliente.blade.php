@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/v/dt/dt-1.10.16/sl-1.2.5/datatables.min.css">
    <link rel="stylesheet" href="https://gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/css/dataTables.checkboxes.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/select/1.4.0/css/select.dataTables.min.css">
@endsection

@section('content')

<section class="section">
        <div class="section-header">
            <h3 class="page__heading">Registrar Retiro Equipo/s Cliente</h3>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                    @if($errors->any())
                            <div class="alert alert-dark alert-dismissible fade-snow" role="alert">
                                <strong>Error en la elección.</strong>
                                    @foreach($errors->all() as $error)
                                        <span class="badge badge-danger">{{$error}}</span>
                                    @endforeach
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                            </div>
                    @endif
    
          
                <div id="stepper1" class="bs-stepper">
                        <div class="bs-stepper-header">
                            <div class="step" data-target="#test-l-1">
                                <button type="button" class="btn step-trigger">
                                <span class="bs-stepper-circle">1</span>
                                <span class="bs-stepper-label">Clientes</span>
                                </button>
                            </div>
                            <div class="line"></div>
                            <div class="step" data-target="#test-l-2">
                                <button type="button" class="btn step-trigger">
                                <span class="bs-stepper-circle">2</span>
                                <span class="bs-stepper-label">Equipos del Cliente</span>
                                </button>
                            </div>
                            <div class="line"></div>
                            <div class="step" data-target="#test-l-3">
                                <button type="button" class="btn step-trigger">
                                <span class="bs-stepper-circle">3</span>
                                <span class="bs-stepper-label">Detalle de retiro</span>
                                </button>
                            </div>
                        </div>
                        {!! Form::open(array('route'=> 'equipos.registrarRetiro', 'method'=> 'POST', 'id' => 'frm-example')) !!}

                        <div class="bs-stepper-content">
                            <div id="test-l-1" class="content">
                            <div class=" table-bordered">
                                    <table id="users" class="table" style="width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th width="10px">ID</th>
                                                    <th>Nombre</th>
                                                    <th>Apellido</th>
                                                    <th>Correo</th>
                                                </tr>
                                            </thead>
                                    </table>
                            </div>

                            <button class="btn btn-primary mt-2" onclick="event.preventDefault()" id="clientes">Siguiente</button>
                            
                         </div>
                        <div id="test-l-2" class="content">
                        <div class="table-responsive table-bordered">
                        <table id="example" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tipo Equipo</th>
                                    <th>Serie</th>
                                    <th>Marca</th>
                                    <th>Modelo</th>
                                    <th>Estante</th>
                                    <th>Sección Estante</th>
                        
                                </tr>
                            </thead>
                            
                    </table>
                                </div>
                            <button class="btn btn-secondary mt-2" onclick="stepper1.previous(), event.preventDefault()">Anterior</button>
                            <button class="btn btn-primary mt-2" onclick="event.preventDefault()" id="equipos">Siguiente</button>
                        </div>

                        <div id="test-l-3" class="content">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group"> 
                                        <label for="descripcion">Detalle del retiro del/los Equipo/s</label>
                                        <div class="form-control" style="visibility: hidden; padding:0; height:20px">
                                        </div>
                                        {!! Form::textarea('descripcion', null, ['style' => 'width:100%; resize:none;', 'id'=>'descripcion'])!!}                                        
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-secondary mt-2" onclick="stepper1.previous(), event.preventDefault()">Anterior</button>
                            <input type="submit" value="Asignar" class="btn btn-warning mt-2" id="enviar">
                        </div>
                    </div>
                   
                <pre id="example-console-form"></pre>
                {!! Form::close() !!}
                </div>
        </div>
    </div>                     
</div>
</div>
</section>

@endsection
@section('scripts')
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script src="https://cdn.jsdelivr.net/npm/bs-stepper/dist/js/bs-stepper.min.js"></script>
        
        <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
        <script src="https://cdn.datatables.net/v/dt/dt-1.10.16/sl-1.2.5/datatables.min.js"></script>
        <script src="https://gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/js/dataTables.checkboxes.min.js"></script>
        <script src="https://cdn.datatables.net/select/1.4.0/js/dataTables.select.min.js" ></script>

        <script>

            var stepper1Node = document.querySelector('#stepper1')
            var stepper1 = new Stepper(document.querySelector('#stepper1'),  {
                linear: false,
                animation: true
            })

            
            $(document).ready(function() {
                var tablita;
            
                var table =  $('#users').DataTable({
                    "serverSide": true,
                    "ajax":  "{{route('getClientes')}}",
                    "columns": [
                        {data: 'id'},
                        {data: 'name'},
                        {data: 'lastname'},
                        {data: 'email'},
                
                    ],
                    select: {
                        style: 'single'
                    },
                });

            $('#clientes').on('click', function(e){
                var clienteSeleccionado = table.rows( { selected: true } ).count();

                if(!clienteSeleccionado){
                    Swal.fire('Debe de elegir un Cliente para ver sus Equipos y luego registrar su retiro.')
                } else {

                    if ($.fn.dataTable.isDataTable('#example')) {
                            $('#example').DataTable().destroy();
                    }

                    setTimeout(() => {
                        stepper1.next();
                    }, 1300);

                    var idCliente = table.rows( { selected: true } ).data()[0].id;
                    
                    if(idCliente){

                        var url = '{{route("getEquiposClientePagados", ":idCliente")}}';
                        url = url.replace(':idCliente', idCliente);

                        tablita = $('#example').DataTable( {
                            "serverSide": true,
                            "ajax":  url,
                            method: 'GET',
                            "columns": [
                            {data: 'id'},
                            {data: 'tipoEquipo'},
                            {data: 'serie'},
                            {data: 'marca.nombre'},
                            {data: 'modelo'},
                            {data: 'estante'},
                            {data: 'seccionEstante'},
                            ],
                            'columnDefs': [
                            {
                                'targets': 0,
                                'checkboxes': {
                                'selectRow': true
                                }
                            },

                            ],
                            'select': {
                                'style': 'multi'
                            },
                            'order': [[1, 'asc']],
                            "language": {
                                "info": "_TOTAL_ registros",
                                "search": "Buscar",
                                "paginate": {
                                    "next": "Siguiente",
                                    "previous": "Anterior",
                                },
                                "lengthMenu": 'Mostrar <select >'+
                                            '<option value="10">10</option>'+
                                            '<option value="30">30</option>'+
                                            '<option value="-1">Todos</option>'+
                                            '</select> registros',
                                "loadingRecords": "Cargando...",
                                "processing": "Procesando...",
                                "emptyTable": "No hay datos",
                                "zeroRecords": "No hay coincidencias", 
                                "infoEmpty": "",
                                "infoFiltered": ""
                            },
                            success: function(response){
                            console.log(response);

                            if(response.error){
                                Swal.fire(
                                'Error en la Obtención de Equipos!',
                                response.error, "error")    
                            }

                
                            $('#exampleModal').modal('hide');
                            },
                            error: function(error){
                                if(error){ 
                                console.log(error)
                                }
                            }
                        });
                        tablita.columns.adjust().draw();

                        tablita.ajax.reload();
                        $('#example').DataTable().ajax.reload();
                    } else {
                        Swal.fire('Debe seleccionar un Cliente para obtener sus Equipos con Servicios Pagados.')
                    }
                  
                }

            });

            $( "#frm-example" ).submit(function(e) {
                e.preventDefault();
                var detalle = document.getElementById('descripcion').value;
                var validateDetalle = false;

                if(detalle != ''){                    
                    validateDetalle = true;
                } else {
                    Swal.fire('Debe de especificar un detalle para el registro del retiro del/los Equipo/s.');
                }

                var form = document.getElementById('frm-example');
                var cliente = table.rows( { selected: true } ).data()[0].id;
                var equipos = tablita.column(0).checkboxes.selected();  

                if(validateDetalle && equipos && cliente){
                    if(equipos.length >= 1){
                        // Iterate over all selected checkboxes
                        $.each(equipos, function(index, rowId){
                            // Create a hidden element 
                            $(form).append(
                                $('<input type="text" hidden>')
                                    .attr('name', 'idEquipos[]')
                                    .attr('value',rowId)
                            );
                        });
    
                        $(form).append(
                            $('<input type="text" hidden>')
                                .attr('name', 'idCliente')
                                .attr('value', cliente)
                        );
    
                        Swal.fire(
                            'Registro de Retiro de Equipo/s Exitoso!',
                            'Se registró con éxito el retriro de el/los Equipos.', "success");
    
                        e.currentTarget.submit();
                            
                     } else {
                        Swal.fire(
                            'Error!',
                            'Debe de elegir uno o varios Equipos.', "error");
                            e.preventDefault();
                     }
                }
            });

            $('#equipos').on('click', function(e){
                var equipoSeleccionado = tablita.rows( { selected: true } ).count();
                
                if(!equipoSeleccionado){
                    Swal.fire('Debe de elegir un Equipo para registrar un retiro.')
                } else {
                    stepper1.next();
                    
                }

            });
        });
        </script>
@endsection

    

    

