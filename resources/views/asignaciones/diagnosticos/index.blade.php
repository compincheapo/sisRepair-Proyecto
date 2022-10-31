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
            <h3 class="page__heading">Asignación Equipo para Diagnóstico</h3>
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
                                <span class="bs-stepper-label">Equipos Clientes</span>
                                </button>
                            </div>
                            <div class="line"></div>
                            <div class="step" data-target="#test-l-2">
                                <button type="button" class="btn step-trigger">
                                <span class="bs-stepper-circle">2</span>
                                <span class="bs-stepper-label">Técnicos</span>
                                </button>
                            </div>
                            <div class="line"></div>
                            <div class="step" data-target="#test-l-3">
                                <button type="button" class="btn step-trigger">
                                <span class="bs-stepper-circle">3</span>
                                <span class="bs-stepper-label">Asginación</span>
                                </button>
                            </div>
                        </div>
                        {!! Form::open(array('route'=> 'asignarDiagnostico', 'method'=> 'POST', 'id' => 'frm-example')) !!}

                        <div class="bs-stepper-content">
                            <div id="test-l-1" class="content">
                            <div class=" table-bordered">
                                    <table id="users" class="table" style="width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th width="10px">ID</th>
                                                    <th>Serie</th>
                                                    <th>Marca</th>
                                                    <th>Cliente</th>
                                                </tr>
                                            </thead>
                                    </table>
                            </div>

                            <button class="btn btn-primary mt-2" onclick="event.preventDefault()" id="equipos">Siguiente</button>
                            
                         </div>
                        <div id="test-l-2" class="content">
                        <div class="table-responsive table-bordered">
                        <table id="example" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Lastname</th>
                        
                                </tr>
                            </thead>
                            
                    </table>
                                </div>
                            <button class="btn btn-secondary mt-2" onclick="stepper1.previous(), event.preventDefault()">Anterior</button>
                            <button class="btn btn-primary mt-2" onclick="event.preventDefault()" id="tecnicos">Siguiente</button>
                        </div>

                        <div id="test-l-3" class="content">

                    <table id="selected-equipment" class="table" style="width:100%">
                        <thead>
                            <tr>
                                <th>Serie</th>
                                <th>Marca</th>
                                <th>Cliente</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    
                    </table>


                            <p class="text-center">¿Está seguro de asignar estos Equipos?</p>
                            <button class="btn btn-secondary mt-2" onclick="stepper1.previous(), event.preventDefault()">Anterior</button>
                            <input type="submit" value="Asignar" class="btn btn-warning mt-2" id="enviar">
                        </div>
                    </div>
                    <a href="#" onclick="e.preventDefault()"id="selected">Touch Me</a>
                    <!-- <p>Press <b>Submit</b> and check console for URL-encoded form data that would be submitted.</p>

                    <p><button>Submit</button></p>

                    <p><b>Selected rows data:</b></p>
                    <pre id="example-console-rows"></pre>

                    <p><b>Form data as submitted to the server:</b></p> -->
                <pre id="example-console-form"></pre>
                {!! Form::close() !!}
                </div>
        </div>
    </div>                     
</div>
</div>

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

            stepper1Node.addEventListener('show.bs-stepper', function (event) {
                console.warn('show.bs-stepper', event)
            })
            stepper1Node.addEventListener('shown.bs-stepper', function (event) {
                console.warn('shown.bs-stepper', event)
            })
            
            $(document).ready(function() {
                
                var tablita = $('#example').DataTable( {
                    "serverSide": true,
                    "ajax":  "{{route('tecnicos')}}",
                    "columns": [
                        {data: 'id'},
                        {data: 'name'},
                        {data: 'lastname'},
                
                    ],
                    select: {
                        style: 'single'
                    }
                } );

               

                var table =  $('#users').DataTable({
                    "serverSide": true,
                    "ajax":  "{{route('equiposDiagnostico')}}",
                    "columns": [
                        {data: 'id'},
                        {data: 'serie'},
                        {data: 'marca.nombre'},
                        {data: 'user.name'},
                
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
                }
                });

            function uncheckEquipment(e){
                var idEquipment = e.target.id;
                console.log('id elemento entrante ', e.target.id);
                e.preventDefault();
                console.log('equipment', idEquipment);
                var equipment = document.getElementById(idEquipment);
                var filaEquipment = equipment.parentNode.parentNode;
                var rows_selected = table.column(0).checkboxes.selected().count();

                // console.log('checks ', table.columns().checkboxes.selected()[0].length);
                // console.log('Elementos array ', table.columns().checkboxes.selected()[0][0]);
                // console.log('Entrante ', idEquipment);
                for (let i = 0; i <= table.columns().checkboxes.selected()[0].length; i++) {
                    console.log('iterador', i);
                    console.log(table.row(i).data().id);
                    
                    console.log(rows_selected);
                    if(rows_selected <= 1){
                        Swal.fire('Como mínimo debe ser 1 Equipo.')
                    } else if(rows_selected > 1) {
                        if(table.row(i).data().id == idEquipment){
                        console.log('a borrar ',table.row(i).data().id);    
                        table.row(i).deselect();
                        filaEquipment.parentNode.removeChild(filaEquipment);
}
                        
                    }
                    
                }
                //console.log(table.row(1).data());
                // console.log(table.row(1).selected(true));
                // console.log(table.row(0).deselect());


                //console.log(table.rows({selected: true}).checkboxes.deselect(true));
                //selected = table.columns().checkboxes.selected()[0][0];
                //console.log(table.columns().checkboxes);
                //console.log(table.columns().checkboxes.selected()[0][0]);
                //console.log(table.rows({selected: true})[0][0].deselect(true));
                //console.log(table.rows({selected: true}).data()[0]);
                //table.columns().checkboxes.deselect(true);
                //table.rows( { selected: true } ).data().pluck(0).toArray();
            }
                

            // Handle form submission event 
            $( "#enviar" ).click(function() {
                var form = document.getElementById('frm-example');
                var rows_selected = table.column(0).checkboxes.selected();
                // Iterate over all selected checkboxes
                $.each(rows_selected, function(index, rowId){
                    console.log("si pasa")

                    // Create a hidden element 
                    $(form).append(
                        $('<input type="text" hidden>')
                            .attr('name', 'idEquipos[]')
                            .attr('value',rowId)
                    );
                });


                var form = document.getElementById('frm-example');
                console.log(tablita.rows( { selected: true } ).data()[0].id);
                var tecnico = tablita.rows( { selected: true } ).data()[0].id;

                if(tecnico){
                    $(form).append(
                        $('<input type="text" hidden>')
                            .attr('name', 'idTecnico')
                            .attr('value', tecnico)
                    );
                }
            $( "#enviar" ).submit();
            });

            $('#equipos').on('click', function(e){
                var tbodySelectedEquipments = document.getElementById('selected-equipment').getElementsByTagName('tbody')[0];

                tbodySelectedEquipments.innerHTML = "";

                for (let index = 0; index < table.rows({selected: true})[0].length; index++) {
                    console.log(table.rows({selected: true}).data()[index].id);
                    //Insertando Fila
                    var newRow = tbodySelectedEquipments.insertRow();

                    
                    //Celda Serie
                    var celdaSerie = newRow.insertCell();
                    //Insertando Contenido tipo texto.
                    var contenidoSerie = document.createTextNode(table.rows({selected: true}).data()[index].serie);
                    //Insertando sobre la celda el contenido tipo texto.
                    celdaSerie.appendChild(contenidoSerie);

                    //Celda Marca
                    var celdaMarca = newRow.insertCell();
                    //Insertando Contenido tipo texto.
                    var contenidoMarca = document.createTextNode(table.rows({selected: true}).data()[index].marca.nombre);
                    //Insertando sobre la celda el contenido tipo texto.
                    celdaMarca.appendChild(contenidoMarca);

                    //Celda User
                    var celdaUser = newRow.insertCell();
                    //Insertando Contenido tipo texto.
                    var contenidoUser = document.createTextNode(table.rows({selected: true}).data()[index].user.name);
                    //Insertando sobre la celda el contenido tipo texto.
                    celdaUser.appendChild(contenidoUser);


                    //Celda Btn
                    var celdaBtn = newRow.insertCell();
                    celdaBtn.setAttribute('style', 'text-align:center;');
                    var btn = document.createElement("button");
                    btn.innerHTML = "X";
                    btn.setAttribute('class', 'btn btn-danger');
                    btn.setAttribute('id', table.rows({selected: true}).data()[index].id);
                    
                    btn.onclick = function (e) {    
                        uncheckEquipment(e);
                    };

                    celdaBtn.appendChild(btn);
                }

                
                

                // btn.addEventListener("click", function () {
                // alert("Button is clicked");
                // });
                

                var rows_selected = table.column(0).checkboxes.selected().count();
                console.log(rows_selected);

                if(!rows_selected){
                    Swal.fire('Debe de elegir al menos un Equipo.')
                } else {
                    stepper1.next();
                }

                

            //     e.preventDefault();
            //     var form = document.getElementById('frm-example');;
            //     var rows_selected = table.column(0).checkboxes.selected();
            //     //console.log(table.column(0).checkboxes.selected()[0])
            //     // Iterate over all selected checkboxes
            //     $.each(rows_selected, function(index, rowId){
            //         console.log("si pasa")

            //         // Create a hidden element 
            //         $(form).append(
            //             $('<input type="text" hidden>')
            //                 .attr('name', 'idEquipos[]')
            //                 .attr('value',rowId)
            //         );
            //     });

            //     // FOR DEMONSTRATION ONLY
            //     // The code below is not needed in production
                
            //     // Output form data to a console     
            //     $('#example-console-rows').text(rows_selected.join(","));
                
            //     // Output form data to a console     
            //     $('#example-console-form').text($(form).serialize());
                
            //     // Remove added elements
            //     //$('input[name="id\[\]"]', form).remove();
            //     table.column(0).checkboxes.selected()[0];
            //     // Prevent actual form submission
               
            });

            $('#tecnicos').on('click', function(e){
                var tecnicoSeleccionado = tablita.rows( { selected: true } ).count();

                if(!tecnicoSeleccionado){
                    Swal.fire('Debe de elegir un Técnico para asignar el Diagnóstico.')
                } else {
                    stepper1.next();
                }
            //     var form = document.getElementById('frm-example');;
            //     console.log(tablita.rows( { selected: true } ).data()[0][0]);
            //     var tecnico = tablita.rows( { selected: true } ).data()[0][0];

            //     if(tecnico){
            //         $(form).append(
            //             $('<input type="text" hidden>')
            //                 .attr('name', 'idTecnico[]')
            //                 .attr('value', tecnico)
            //         );
            //     }
               

            });
            
            });
        </script>
@endsection

    

    

