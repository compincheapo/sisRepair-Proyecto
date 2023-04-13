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
                                <span class="bs-stepper-label">Asignación</span>
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
                                                    <th>Modelo</th>
                                                    <th>Cliente</th>
                                                    <th>Fecha Ingreso</th>
                                                    <th>Fecha Estimada</th>
                                                    <th>Acciones</th>
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
                                <th>Modelo</th>
                                <th>Cliente</th>
                                <th>Fecha Ingreso</th>
                                <th>Fecha Compromiso</th>
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
                <pre id="example-console-form"></pre>
                {!! Form::close() !!}
                </div>
        </div>
    </div>                     
</div>
</div>
</section>
<form id="modalCreateForm">
<div class="modal fade modalCreateForm" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="exampleModalLabel">Titulo</h3>
        <button type="button" class="close closeBtn" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="modal-body">
         <input type="hidden" name="id" id="id">
         <div class="row" id="divRowBro">
             <div class="col-xs-6 col-sm-6 col-md-6">
              <div class="form-group mb-3">
                 <label for="estante">Estante</label>
                 <input type="text" name="estante" id="estante" class="form-control" disabled>  
              </div>
             </div>
             <div class="col-xs-6 col-sm-6 col-md-6">
             <div class="form-group mb-3">
                <label for="seccionEstante">Sección Estante</label>
                <input type="text" name="seccionEstante" id="seccionEstante" class="form-control" disabled>
             </div>
            </div>
         </div>       
    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary closeBtn" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
</form>

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
                        {data: 'modelo'},
                        {data: 'user.name'},
                        {data: 'fechaIngreso'},
                        {data: 'fechaCompromiso'},
                        {data: 'action', name: 'action', orderable: false, searchable:false}
                
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

                     //Celda Modelo
                     var celdaModelo = newRow.insertCell();
                    //Insertando Contenido tipo texto.
                    var contenidoModelo = document.createTextNode(table.rows({selected: true}).data()[index].modelo);
                    //Insertando sobre la celda el contenido tipo texto.
                    celdaModelo.appendChild(contenidoModelo);

                    //Celda User
                    var celdaUser = newRow.insertCell();
                    //Insertando Contenido tipo texto.
                    var contenidoUser = document.createTextNode(table.rows({selected: true}).data()[index].user.name);
                    //Insertando sobre la celda el contenido tipo texto.
                    celdaUser.appendChild(contenidoUser);

                    //Celda Ingreso
                    var celdaIngreso = newRow.insertCell();
                    //Insertando Contenido tipo texto.
                    var contenidoIngreso = document.createTextNode(table.rows({selected: true}).data()[index].fechaIngreso);
                    //Insertando sobre la celda el contenido tipo texto.
                    celdaIngreso.appendChild(contenidoIngreso);

                    //Celda Compromiso
                    var celdaCompromiso = newRow.insertCell();
                    //Insertando Contenido tipo texto.
                    var contenidoCompromiso = document.createTextNode(table.rows({selected: true}).data()[index].fechaCompromiso);
                    //Insertando sobre la celda el contenido tipo texto.
                    celdaCompromiso.appendChild(contenidoCompromiso);


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

            $('body').on('click', '.detBtn', function (){

            var id = $(this).data('id');
            var url = '{{route("getDetalleEquipoDiagnostico", ":id")}}';
            url = url.replace(':id', id);

            $.ajax({
            url: url,
            method: 'GET',
            success: function(response){
                $('.modalCreateForm').modal('show');
                $('.modal-title').html('Detalle Equipo');


                if(document.getElementById('labelGroup')){
                $('#labelGroup').addClass("d-none");
                }


                $('#divRowBro').removeClass("d-none");
                

                $('#id').val(response.data[0].id);
                $('#estante').val(response.data[0].estante);
                $('#seccionEstante').val(response.data[0].seccionEstante);
                

                if(document.getElementById('newCreated')){
                    document.getElementById('newCreated').remove();
                }

                if(document.getElementById('accordionExample')){
                    document.getElementById('accordionExample').remove();
                }

                if(response.data[0].accesorios){
                    //Div hermano.
                    var divBro = document.getElementById('divRowBro').parentNode;

                    //Div contenedor.
                    var divForm = document.createElement("div");
                    divForm.setAttribute('class', 'form-group mt-3 mb-1');
                    divForm.setAttribute('id', 'newCreated');
                    divBro.insertBefore(divForm, divForm.nextSibling);

                    //Label Descripción
                    var labelAccesorios = document.createElement("label");
                    labelAccesorios.innerHTML = 'Accesorios';
                    divForm.appendChild(labelAccesorios);

                    //Div Select Group
                    var divSelectGroup = document.createElement("div");
                    divSelectGroup.setAttribute('class', 'selectgroup selectgroup-pills');
                    divForm.appendChild(divSelectGroup);


                    //-------------------- RECORRIDO ACCESORIOS ---------------------------

                    for (let i = 0; i < response.data[0].accesorios.length; i++) {
                    //Label Group
                    var labelGroup = document.createElement("label");
                    labelGroup.setAttribute('class', 'selectgroup-item mr-1');
                    divSelectGroup.appendChild(labelGroup);

                    //Checkbox
                    var checkboxAccesorio = document.createElement("INPUT");
                    checkboxAccesorio.setAttribute("type", "checkbox");
                    checkboxAccesorio.setAttribute('class', 'selectgroup-input');
                    checkboxAccesorio.setAttribute('checked', true);
                    checkboxAccesorio.setAttribute('disabled', true);

                    //Span Group
                    var spanGroup = document.createElement("span");
                    spanGroup.setAttribute('class', 'selectgroup-button');
                    spanGroup.innerHTML = response.data[0].accesorios[i].nombre;
                    
                    labelGroup.appendChild(checkboxAccesorio);
                    labelGroup.appendChild(spanGroup);
                        
                    }
                    
                }

                if(response.data[0].comentario){

                //Div hermano.
                var divRowBro = document.getElementById('divRowBro').parentNode;
                
                //Div Accordion.
                var divAccordion = document.createElement("div");
                divAccordion.setAttribute('class', 'accordion');
                divAccordion.setAttribute('style', 'margin-top:15px;');
                divAccordion.setAttribute('id', 'accordionExample');

                divRowBro.insertBefore(divAccordion, divAccordion.nextSibling);


                //-------------------- RECORRIDO COMENTARIO ---------------------------
            
                    
                //Div Card
                var divCard = document.createElement("div");
                divCard.setAttribute('class', 'card');
                divCard.setAttribute('style', 'border: 1px solid #6777ef !important;');

                divAccordion.appendChild(divCard);

                //Div Card-header
                var divCardHeader = document.createElement("div");
                divCardHeader.setAttribute('class', 'card-header');
                divCardHeader.setAttribute('id', 'headingOne');
                divCardHeader.setAttribute('style', 'border-bottom: 1px solid #6777ef !important;');

                divCard.appendChild(divCardHeader);

                //H2
                var headerH2 = document.createElement("h2");

                divCardHeader.appendChild(headerH2);


                //Button H2
                var buttonH2 = document.createElement("button");
                buttonH2.setAttribute('class', 'btn btn-link btn-block text-left');
                buttonH2.setAttribute('type', 'button');
                buttonH2.setAttribute('data-toggle', 'collapse');
                buttonH2.setAttribute('data-target', '#collapseOne');
                buttonH2.setAttribute('aria-expanded', 'true');
                buttonH2.setAttribute('aria-controls', 'collapseOne');
                buttonH2.setAttribute('style', 'color:#6777ef; padding-left:0px; font-size: 1rem;');

                var createdDate = new Date(response.data[0].comentario.created_at);
                var hours = createdDate.getHours();
                var minutes = createdDate.getMinutes();
                var ampm = hours >= 12 ? 'pm' : 'am';
                hours = hours % 12;
                hours = hours ? hours : 12; // the hour '0' should be '12'
                minutes = minutes < 10 ? '0'+minutes : minutes;
                var strTime = hours + ':' + minutes + ' ' + ampm;

                var mes = '';
                if(parseInt((createdDate.getMonth()+1)) <= 9){
                    mes = '0' + (createdDate.getMonth()+1)
                } else {
                    mes = (createdDate.getMonth()+1);
                }

                var fecha = createdDate.getDate() + "-" + mes + "-" + createdDate.getFullYear() + "  " + strTime;

                //Control de Tipo de Comentario o Detalle
                buttonH2.innerHTML = 'Detalle ingreso Equipo ' + '<p style="color:black; display:inline; font-size:0.8rem">' + fecha + '<p>';
        

                headerH2.appendChild(buttonH2);

                //divCollapse
                var divCollapse = document.createElement("div");
                divCollapse.setAttribute('data-collapse', '#mycard-collapse')
                divCollapse.setAttribute('id', 'collapseOne')
                divCollapse.setAttribute('class', 'collapse')
                divCollapse.setAttribute('aria-labelledby', 'headingOne')
                divCollapse.setAttribute('data-parent', '#accordionExample')

                divCard.appendChild(divCollapse);

                //div Card-Body
                var divCardBody = document.createElement("div");
                divCardBody.setAttribute('class', 'card-body')
                divCardBody.innerHTML = response.data[0].comentario.descripcion;

                divCollapse.appendChild(divCardBody);

                //Div Colapsable card-footer
                var divCardFooter = document.createElement("div");
                divCardFooter.setAttribute('class', 'card-footer');
                divCardFooter.innerHTML = 'Escrito por:'

                divCollapse.appendChild(divCardFooter);

                //Parrafo card-footer

                var pCardFooter = document.createElement("p");
                pCardFooter.setAttribute('style', 'font-size: 1rem; font-weight: bold; margin-bottom: 0px')
                pCardFooter.innerHTML = response.data[0].comentario.lastname + ' ' + response.data[0].comentario.name;

                divCardFooter.appendChild(pCardFooter);

                
                }     
            
            },
            error: function(){
                console.log(error);
            }
            });
            });
            
            });
        </script>
@endsection

    

    

