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
            <h3 class="page__heading">Nueva Orden de Servicio</h3>
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
                                <span class="bs-stepper-label">Detalle y Fecha Prometida</span>
                                </button>
                            </div>
                        </div>
                        {!! Form::open(array('route'=> 'ordenesservicios.store', 'method'=> 'POST', 'id' => 'frm-example')) !!}

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
                                        <label for="descripcion">Detalle de la nueva Orden de Servicio</label>
                                        <div class="form-control" style="visibility: hidden; padding:0; height:20px">
                                        </div>
                                        {!! Form::textarea('descripcion', null, ['style' => 'width:100%; resize:none;', 'id'=>'descripcion'])!!}                                        
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-6 col-sm-6 col-md-6">
                                            <div class="form-group"> 
                                                <label for="fecha">Fecha Estimada</label>
                                            {!!Form::date('fecha', null, ['class' => 'form-control', 'id'=>'fecha'] )!!} 
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

            
            $(document).ready(function() {
            
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
                

            $( "#frm-example" ).submit(function(e) {
                e.preventDefault();
                var fecha = document.getElementById('fecha').value;
                var detalle = document.getElementById('descripcion').value;
                var validateFecha = false;
                var validateDetalle = false;

                if(fecha != '' || detalle != ''){

                    if(fecha != ''){
                        var fechaElegida = new Date(document.getElementById('fecha').value);
                        var fechaActual = new Date();
                        var fechaActualFormat = new Date(fechaActual.getFullYear(),fechaActual.getMonth(),fechaActual.getDate());

                        if(fechaElegida < fechaActualFormat){
                            Swal.fire('Debe de elegir una fecha estimada superior a la actual.')
                        } else {
                            validateFecha = true;
                        }
                        
                    } else {
                        Swal.fire('Debe de elegir una fecha estimada.')
                    }
                    
                    if(detalle != ''){
                        validateDetalle = true;
                    } else {
                        Swal.fire('Debe de especificar un detalle para el registro de la Orden de Servicio.')
                    }

                    if(validateFecha === true && validateDetalle === true){
                        stepper1.next();
                    }
                   
                } else {
                    Swal.fire('Debe de elegir una fecha estimada.');
                }

                var form = document.getElementById('frm-example');
                var cliente = table.rows( { selected: true } ).data()[0].id;
                var equipo = tablita.rows( { selected: true } ).data()[0].id;

                if(cliente && equipo && validateDetalle && validateFecha){
                    $(form).append(
                        $('<input type="text" hidden>')
                            .attr('name', 'idCliente')
                            .attr('value', cliente)
                    );

                    $(form).append(
                        $('<input type="text" hidden>')
                            .attr('name', 'idEquipo')
                            .attr('value', equipo)
                    ); 
                
                    e.currentTarget.submit();
                }
            });

            var tablita;
            $('#clientes').on('click', function(e){
                var clienteSeleccionado = table.rows( { selected: true } ).count();

                if(!clienteSeleccionado){
                    Swal.fire('Debe de elegir un Cliente para ver sus Equipos y luego registrar una Orden.')
                } else {

                    if ($.fn.dataTable.isDataTable('#example')) {
                            $('#example').DataTable().destroy();
                    }

                    setTimeout(() => {
                        stepper1.next();
                    }, 1300);

                    var idCliente = table.rows( { selected: true } ).data()[0].id;
                    
                    if(idCliente){

                        var url = '{{route("getEquiposCliente", ":idCliente")}}';
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
                            select: {
                                style: 'single'
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
                        Swal.fire('Debe seleccionar un Cliente para obtener sus Equipos.')
                    }
                  
                }

            });

            $('#equipos').on('click', function(e){
                var equipoSeleccionado = tablita.rows( { selected: true } ).count();
                // console.log(tablita.rows( { selected: true } ).data()[0].id);  
                
                if(!equipoSeleccionado){
                    Swal.fire('Debe de elegir un Equipo para registrar una nueva Orden de Servicio.')
                } else {
                    stepper1.next();
                    
                }

            });

            $('body').on('click', '.detBtn', function (){

            var id = $(this).data('id');
            var url = '{{route("getDetalleEquipoServicio", ":id")}}';
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
                
                if(response.data[0].comentarios.length){

                //Div hermano.
                var divRowBro = document.getElementById('divRowBro').parentNode;

                //Div Accordion.
                var divAccordion = document.createElement("div");
                divAccordion.setAttribute('class', 'accordion');
                divAccordion.setAttribute('style', 'margin-top:15px;');
                divAccordion.setAttribute('id', 'accordionExample');

                divRowBro.insertBefore(divAccordion, divAccordion.nextSibling);


                //-------------------- RECORRIDO COMENTARIOS ---------------------------

                for (let i = 0; i < response.data[0].comentarios.length; i++) {
                    
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
                buttonH2.setAttribute('data-target', '#collapseOne'+i);
                buttonH2.setAttribute('aria-expanded', 'true');
                buttonH2.setAttribute('aria-controls', 'collapseOne');
                buttonH2.setAttribute('style', 'color:#6777ef; padding-left:0px; font-size: 1rem;');

                //Control de Tipo de Comentario o Detalle
                if(response.data[0].comentarios[i].id_estado == 1){
                    buttonH2.innerHTML = 'Detalle ingreso Equipo ' + '<p style="color:black; display:inline; font-size:0.8rem">' + response.data[0].comentarios[i].created_at + '<p>';
                }

                if(response.data[0].comentarios[i].id_estado == 9){
                    buttonH2.innerHTML = 'Detalle Reasignación ' + '<p style="color:black; display:inline; font-size:0.8rem">' + response.data[0].comentarios[i].created_at + '<p>';
                }

                if(response.data[0].comentarios[i].id_estado == 4){
                    buttonH2.innerHTML = 'Detalle Diagnóstico Finalizado ' + '<p style="color:black; display:inline; font-size:0.8rem">' + response.data[0].comentarios[i].created_at + '<p>';
                }

                if(response.data[0].comentarios[i].id_estado == 5){
                    buttonH2.innerHTML = 'Detalle Inicio Reparación ' + '<p style="color:black; display:inline; font-size:0.8rem">' + response.data[0].comentarios[i].created_at + '<p>';
                }

                if(response.data[0].comentarios[i].id_estado == 10){
                    buttonH2.innerHTML = 'Detalle Presupuesto ' + '<p style="color:black; display:inline; font-size:0.8rem">' + response.data[0].comentarios[i].created_at + '<p>';
                }
                if(response.data[0].comentarios[i].id_estado == 16){
                    buttonH2.innerHTML = 'Detalle Retiro Equipo por Tercero ' + '<p style="color:black; display:inline; font-size:0.8rem">' + response.data[0].comentarios[i].created_at + '<p>';
                }

                headerH2.appendChild(buttonH2);

                //divCollapse
                var divCollapse = document.createElement("div");
                divCollapse.setAttribute('data-collapse', '#mycard-collapse')
                divCollapse.setAttribute('id', 'collapseOne'+i)
                divCollapse.setAttribute('class', 'collapse')
                divCollapse.setAttribute('aria-labelledby', 'headingOne')
                divCollapse.setAttribute('data-parent', '#accordionExample')

                divCard.appendChild(divCollapse);

                //div Card-Body
                var divCardBody = document.createElement("div");
                divCardBody.setAttribute('class', 'card-body')
                divCardBody.innerHTML = response.data[0].comentarios[i].descripcion;

                divCollapse.appendChild(divCardBody);

                //Div Colapsable card-footer
                var divCardFooter = document.createElement("div");
                divCardFooter.setAttribute('class', 'card-footer');
                divCardFooter.innerHTML = 'Escrito por:'

                divCollapse.appendChild(divCardFooter);

                //Parrafo card-footer

                var pCardFooter = document.createElement("p");
                pCardFooter.setAttribute('style', 'font-size: 1rem; font-weight: bold; margin-bottom: 0px')
                pCardFooter.innerHTML = response.data[0].comentarios[i].lastname + ' ' + response.data[0].comentarios[i].name;

                divCardFooter.appendChild(pCardFooter);

                }
                } else {
                //Div hermano.
                var divRowBro = document.getElementById('divRowBro').parentNode;

                //Div Accordion.
                var divAccordion = document.createElement("div");
                divAccordion.setAttribute('class', 'accordion');
                divAccordion.setAttribute('style', 'margin-top:15px;');
                divAccordion.setAttribute('id', 'accordionExample');

                divRowBro.insertBefore(divAccordion, divAccordion.nextSibling);

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

                buttonH2.innerHTML = 'Detalle ingreso Equipo ' + '<p style="color:black; display:inline; font-size:0.8rem">' + response.data[0].comentarios.created_at + '<p>';

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
                divCardBody.innerHTML = response.data[0].comentarios.descripcion;

                divCollapse.appendChild(divCardBody);

                //Div Colapsable card-footer
                var divCardFooter = document.createElement("div");
                divCardFooter.setAttribute('class', 'card-footer');
                divCardFooter.innerHTML = 'Escrito por:'

                divCollapse.appendChild(divCardFooter);

                //Parrafo card-footer

                var pCardFooter = document.createElement("p");
                pCardFooter.setAttribute('style', 'font-size: 1rem; font-weight: bold; margin-bottom: 0px')
                pCardFooter.innerHTML = response.data[0].comentarios.lastname + ' ' + response.data[0].comentarios.name;

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

    

    

