@extends('layouts.app')

@section('css')
    <style>
        .modal-lg {
            max-width: 70% !important;
        }

    </style>
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">Ordenes de Servicio</h3>
                <a class="btn btn-info section-header-breadcrumb" style="float:right;" href="{{route('equipos.misequiposdiagnostico')}}">Volver</a>
        </div>
        @if (\Session::has('success'))
            <div class="alert alert-success">
                <ul>
                    <li>{!! \Session::get('success') !!}</li>
                </ul>
            </div>
        @endif
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                         @can('ver-usuario') <!-- Crear y adaptar esta parte con el permiso para auditoria -->
                        <div class="card-body">
                        <div class="table-responsive table-bordered">
                            <table class="table table-striped ">
                                <thead style="background-color: #6777ef;">
                                    <th style="color: #fff;">Nro Orden</th>
                                    <th style="color: #fff;">Cliente</th>
                                    <th style="color: #fff;">Servicio</th>
                                    <th style="color: #fff;">Fecha Compromiso</th>
                                    <th style="color: #fff;">Estado</th>
                                    <th style="color: #fff;">Fecha Fin</th>
                                    <th style="color: #fff;">Acción</th>
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
                                           
                                            <td>
                                                <div class="btn-group">
                                                     <a class="btn btn-warning mr-2 detBtn" data-id="{{$orden->id}}" onclick="event.preventDefault();">Detalle</a>
                                                     @if($orden->pago == 'Si')
                                                       <a class="btn btn-success mr-2" href="{{route('pagarOrdenServicio', $orden->id)}}">Pagar</a>
                                                     @endif
                                                     
                                                     @if($orden->retroalimentar == 'Si')
                                                         <a class="btn btn-primary retBtn" data-id="{{$orden->id}}" onclick="event.preventDefault();">Retroalimentar</a>
                                                     @endif

                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                                {!! $ordenes->links() !!}
                        </div>
                        </div>
                        
                    @endcan   
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
                    <h6>Detalles del Equipo</h6>
                    <div class="row mb-3" id="divRowBro">
                        <div class="col-xs-3 col-sm-3 col-md-3">
                            <div class="form-group mb-3">
                                <label for="tipoequipo">Tipo Equipo</label>
                                <input type="text" name="tipoequipo" id="tipoequipo" class="form-control" disabled>  
                            </div>
                        </div>
                        <div class="col-xs-3 col-sm-3 col-md-3">
                            <div class="form-group mb-3">
                                <label for="serie">Serie</label>
                                <input type="text" name="serie" id="serie" class="form-control" disabled>  
                            </div>
                        </div>
                        <div class="col-xs-3 col-sm-3 col-md-3">
                            <div class="form-group mb-3">
                                <label for="marca">Marca</label>
                                <input type="text" name="marca" id="marca" class="form-control" disabled>  
                            </div>
                        </div>
                        <div class="col-xs-3 col-sm-3 col-md-3">
                            <div class="form-group mb-3">
                                <label for="modelo">Modelo</label>
                                <input type="text" name="modelo" id="modelo" class="form-control" disabled>  
                            </div>
                        </div>
                        
                    </div>     
                    <h6>Detalles Presupuesto y Pago</h6> 
                    <p id="no-presupuesto-pago" style="display:none">No hay detalles de presupuesto y pago disponibles.</p> 
                    <div class="row" id="divRowBro">
                        <div class="col-xs-3 col-sm-3 col-md-3" id="div-presupuesto">
                            <div class="form-group mb-3" >
                                <label for="presupuesto" id="label-presupuesto">Presupuesto</label>
                                <input type="text" name="presupuesto" id="presupuesto" class="form-control" disabled>  
                            </div>
                        </div>
                        <div class="col-xs-3 col-sm-3 col-md-3" id="div-pago">
                            <div class="form-group mb-3">
                                <label for="pago">Pago</label>
                                <input type="text" name="pago" id="pago" class="form-control" disabled>  
                            </div>
                        </div>
                        
                    </div>       
                </div>
                <div class="modal-footer">
                    <div class="btn-group">
                            <a class="btn btn-danger mr-2 rechBtn d-none" id="{{$orden->id}}"  onclick="event.preventDefault();">Rechazar Presupuesto</a>
                            <a class="btn btn-info aceptBtn d-none" id="{{$orden->id}}" onclick="event.preventDefault();">Aceptar Presupuesto</a>
                    </div>
                    <button type="button" class="btn btn-secondary closeBtn" data-dismiss="modal">Cerrar</button>
                </div>
                </div>
            </div>
        </div>
    </form>

    <form id="modalCreateForm2">
        <div class="modal fade modalCreateForm" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel2" aria-hidden="true">
            <div class="modal-dialog modal-lg" style=" max-width: 50% !important;">
                <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel2">Titulo</h3>
                    <button type="button" class="close closeBtn" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="modal-body2">
                        <input type="hidden" name="retroid" id="retroid">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group mb-3">
                                <label for="detalleaceptpre" id="labelDetalle">Ingrese un detalle a tener en cuenta para la reparacion de su Equipo</label>
                                <textarea name="detalleaceptpre" id="detalleaceptpre" cols="30" rows="10" style="width:100%; resize:none;"></textarea>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary d-none" id="btnAceptarPre2">Aceptar</button>
                    <button type="button" class="btn btn-primary d-none" id="btnAceptarRetro">Aceptar</button>
                    <button type="button" class="btn btn-secondary closeBtn" data-dismiss="modal">Cerrar</button>
                </div>
                </div>
            </div>
        </div>
    </form> 
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script> 
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $('.js-example-basic-single').select2();

        $('body').on('click', '.retBtn', function (){
            var id = $(this).data('id');
            $('#retroid').val(id);  
            $('#exampleModal2').modal('show');
            $('#exampleModalLabel2').html('Retroalimentación Orden de Servicio');
            $('#labelDetalle').html('Retroalimentación');
            $('#detalleaceptpre').val('');
            $('#btnAceptarPre2').addClass("d-none");
            $('#btnAceptarRetro').removeClass("d-none");


        });

        $('body').on('click', '#btnAceptarRetro', function (){
            var id = $('#retroid').val();
            var detalleaceptpre = $('#detalleaceptpre').val();

            $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            });

            $.ajax({
                url: "{{route('registrarRetroalimentacion')}}",
                method: 'POST',
                data: {
                    'id_orden': id,
                    'detalleaceptpre': detalleaceptpre
                },
                success: function(response){
                  
                    Swal.fire(
                    'Retroalimentación Realizada!',
                    '', "success")           
                    

                    $('#exampleModal2').modal('hide');

                    location.reload();
                },
                error: function(error){
                    if(error){ 
                    console.log(error)
                    }
                }
            })
         });

        $('body').on('click', '.detBtn', function (){

        var id = $(this).data('id');
        var url = '{{route("getDetalleOrdenServicio", ":id")}}';
        url = url.replace(':id', id);

        $.ajax({
        url: url,
        method: 'GET',
        success: function(response){

            $('#exampleModal2').modal('hide');
            $('#exampleModal').modal('show');
            $('.modal-title').html('Detalle Orden de Servicio ' + response.data[0].id_orden);

            $('#btnGuardar').addClass("d-none");
            $('#nameError').addClass("d-none");
            $('#emailError').addClass("d-none");
            $('#btnFinalizarRep').addClass("d-none");
            $('#pago').val("");
            $('#presupuesto').val("");
            $('#no-presupuesto-pago').attr('style', 'display:none');
            $("#div-presupuesto").removeAttr("style", "display: none");
            $("#div-pago").removeAttr("style", "display: none");
            $('#divRowBro').removeClass("d-none");

            if(response.data[0].servicio == 1 && response.data[0].estado == 10){
                $('.rechBtn').removeClass("d-none");
                $('.aceptBtn').removeClass("d-none");
            }

            if(response.data[0].presupuesto){
                $('#presupuesto').val(response.data[0].presupuesto);
            } else {
                $("#div-presupuesto").attr("style", "display: none");
            }

            if(response.data[0].pago){
                $('#pago').val(response.data[0].pago);
            } else {
                $("#div-pago").attr("style", "display: none");
            }

            if(!response.data[0].pago && !response.data[0].presupuesto){
                $('#no-presupuesto-pago').removeAttr('style');

            }


            $('#id').val(response.data[0].id);
            $('#serie').val(response.data[0].serie);
            $('#modelo').val(response.data[0].modelo);
            $('#marca').val(response.data[0].marca.nombre);
            $('#tipoequipo').val(response.data[0].tipoequipo);
            $('#pago').val(response.data[0].pago);

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
                var labelAccesorios = document.createElement("h6");
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

            if(response.data[0].comentarios){

            //Label Comentarios
            var labelComentarios = document.createElement("h6");
            labelComentarios.innerHTML = 'Comentarios';
            labelComentarios.setAttribute('class', 'mt-3');
            divForm.appendChild(labelComentarios);

            //Div hermano.
            var divRowBro = document.getElementById('divRowBro').parentNode;
            
            //Div Accordion.
            var divAccordion = document.createElement("div");
            divAccordion.setAttribute('class', 'accordion');
            divAccordion.setAttribute('style', 'margin-top:15px;');
            divAccordion.setAttribute('id', 'accordionExample');

            divRowBro.insertBefore(divAccordion, divAccordion.nextSibling);


            //-------------------- RECORRIDO COMENTARIOS ---------------------------

            console.log(response.data[0].comentarios.length, 'cantidad comentarios');
            console.log(response.data[0].presupuesto, 'presupuesto');


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

            if(response.data[0].comentarios[i].id_estado == 8){
                buttonH2.innerHTML = 'Detalle Reparación ' + '<p style="color:black; display:inline; font-size:0.8rem">' + response.data[0].comentarios[i].created_at + '<p>';
            }

            if(response.data[0].comentarios[i].id_estado == 4){
                buttonH2.innerHTML = 'Detalle Diagnóstico ' + '<p style="color:black; display:inline; font-size:0.8rem">' + response.data[0].comentarios[i].created_at + '<p>';
            }

            if(response.data[0].comentarios[i].id_estado == 5){
                buttonH2.innerHTML = 'Detalle Ingreso a Reparación ' + '<p style="color:black; display:inline; font-size:0.8rem">' + response.data[0].comentarios[i].created_at + '<p>';
            }

            if(response.data[0].comentarios[i].id_estado == 10){
                buttonH2.innerHTML = 'Detalle Presupuesto ' + '<p style="color:black; display:inline; font-size:0.8rem">' + response.data[0].comentarios[i].created_at + '<p>';
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

            }     

        },
        error: function(error){
            console.log(error);
        }
        });

        
        $('body').on('click', '.rechBtn', function (){

            Swal.fire({
            title: 'Estas seguro de Rechazar el Presupuesto?',
            text: "Al rechazar el Presupuesto, deberá realizar el pago del diagnóstico y realizar el retiro de su Equipo!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, rechazar!',
            cancelButtonText: 'Cancelar'
            }).then((result) => {
            if (result.isConfirmed) {
                var id = $('#id').val();
        
                $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
                });

                $.ajax({
                    url: "{{route('rechazarPresupuesto')}}",
                    method: 'POST',
                    data: {
                    'id': id,
                    'cliente': 'true'
                    },
                    success: function(response){
                        if(response){
                            Swal.fire(
                            'Presupuesto Rechazado!',
                            response.success, "success")           
                            }
                            $('.modalCreateForm').modal('hide');

                            location.reload();
                    },
                    error: function(error){
                        if(error){ 
                        console.log(error)
                        }
                    }
                })
            }
            })
       
    });
        $('body').on('click', '.aceptBtn', function (){
            Swal.fire({
            title: 'Estas seguro de Aceptar el Presupuesto?',
            text: "Al aceptar el Presupuesto, se creará una nueva Orden de Servicio para Reparación y se procederá a reparar el Equipo",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, aceptar!',
            cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#btnAceptarPre2').removeClass("d-none");
                    $('#btnAceptarRetro').addClass("d-none");
                    $('#exampleModalLabel2').html('Detalle aceptación presupuesto');
                    $('#exampleModal').modal('hide');
                    $('#exampleModal2').modal('show');
                }
            });
    });


    $('body').on('click', '#btnAceptarPre2', function (){
        var id = $('#id').val();
        var detalleaceptpre = $('#detalleaceptpre').val();

        $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
        });

        $.ajax({
            url: "{{route('aceptarPresupuesto')}}",
            method: 'POST',
            data: {
                'id_orden': id,
                'detalleaceptpre': detalleaceptpre,
                'cliente': 'true'
            },
            success: function(response){
               
                Swal.fire(
                'Presupuesto Aceptado!',
                '', "success")           
               

                $('#exampleModal2').modal('hide');

                location.reload();
            },
            error: function(error){
                if(error){ 
                console.log(error)
                }
            }
        })
    });

});
    </script>
@endsection