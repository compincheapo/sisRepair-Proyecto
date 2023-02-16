@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">Equipos Abandonados</h3>
            @can('crear-usuario')
                <a class="btn btn-warning section-header-breadcrumb" style="float:right;" href="{{route('equipos.equiposAbandonados.create')}}">Nuevo</a>
            @endcan
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                <div class="card">
                  <div class="card-header">
                    <h4>Filtros de Búsqueda</h4>
                    <div class="card-header-action">
                      <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-plus"></i></a>
                    </div>
                  </div>
                  <div class="collapse" id="mycard-collapse">
                    <div class="card-body">
                    <form class="form" action="{{ route('pagodiagnostico.index')}}" method="GET">
                    <div class="row">
                    <div class="form-group col-md-6"> 
                            <label for="usuario">Cliente</label>
                            <select name="usuario" class="form-control js-example-basic-single" style="width:100%">
                                <option value="">Seleccione...</option>
                                @foreach($usuarios as $usuario)
                                
                                    @if(!empty($usuarioData) && $usuario->id == $usuarioData->id)
                                        <option value="{{$usuario->id}}" selected>{{$usuario->name}} {{$usuario->lastname}}</option>    
                                    @else
                                        <option value="{{$usuario->id}}">{{$usuario->name}} {{$usuario->lastname}}</option>    
                                    @endif
                                @endforeach
                            </select>
                    </div>

                    <div class="form-group col-md-6"> 
                            <label for="marca">Marca</label>
                            <select name="marca" class="form-control">
                                    <option value="">Seleccione...</option>    
                                    @foreach($marcas as $marca)
                                        @if($marcaData && $marca->id == $marcaData->id)
                                            <option value="{{$marca->id}}" selected>{{$marca->nombre}}</option>
                                        @endif
                                        <option value="{{$marca->id}}">{{$marca->nombre}}</option>
                                    @endforeach
                            </select>
                    </div>

                    <div class="form-group col-md-6"> 
                            <label for="tipoequipo">Tipo Equipo</label>
                            <select name="tipoequipo" class="form-control">
                                    <option value="">Seleccione...</option>    
                                    @foreach($tiposequipo as $tipoequipo)
                                        @if($tipoequipoData && $tipoequipo->id == $tipoequipoData->id)
                                            <option value="{{$tipoequipo->id}}" selected>{{$tipoequipo->nombre}}</option>
                                        @endif
                                        <option value="{{$tipoequipo->id}}">{{$tipoequipo->nombre}}</option>
                                    @endforeach
                            </select>
                    </div>

                    <div class="form-group col-md-6"> 
                            <label for="estante">Estante</label>
                            <select name="estante" class="form-control">
                                    <option value="">Seleccione...</option>    
                                    @foreach($estantes as $estante)
                                        @if($estanteData && $estante->id == $estanteData->id)
                                            <option value="{{$estante->id}}" selected>{{$estante->nombre}}</option>
                                        @endif
                                        <option value="{{$estante->id}}">{{$estante->nombre}}</option>
                                    @endforeach
                            </select>
                    </div>
                     
                       <div class="form-group col-md-12">
                            <button class="btn btn-light btn btn-icon icon-left"><i class="fas fa-filter"></i>Filtrar</button>
                            <a href="{{route('usuarios.pdf')}}" class="btn btn-warning">PDF</a>
                            <a href="{{ url('/auditoria') }}" class="btn btn-info">Limpiar</a>
                        </div>
                    </div>
                    </form>
                    </div>
                  </div>
                </div>
                    <div class="card">
                         @can('ver-usuario') <!-- Crear y adaptar esta parte con el permiso para auditoria -->
                        <div class="card-body">
                        <div class="table-responsive table-bordered">
                            <table class="table table-striped ">
                                <thead style="background-color: #6777ef;">
                                    <th style="color: #fff;">Serie</th>
                                    <th style="color: #fff;">Modelo</th>
                                    <th style="color: #fff;">Tipo Equipo</th>
                                    <th style="color: #fff;">Marca</th>
                                    <th style="color: #fff;">Estante</th>
                                    <th style="color: #fff;">Sección Estante</th>
                                    <th style="color: #fff;">Cliente</th>
                                    <th style="color: #fff;">Acción</th>
                                </thead>
                                <tbody>  
                                    @foreach($equiposAbandonados as $equipo)
                                        <tr>
                                            <td>{{$equipo->serie}}</td>
                                            <td>{{$equipo->modelo}}</td>
                                            <td>{{$equipo->tipoEquipo->nombre}}</td>
                                            <td>{{$equipo->marca->nombre}}</td>
                                            <td>{{$equipo->seccionEstante->estante->nombre}}</td>
                                            <td>{{$equipo->seccionEstante->nombre}}</td>
                                            <td>{{$equipo->user->name}} {{$equipo->user->lastname}}</td>
                                            <td>
                                                <a class="btn btn-warning mr-1 detBtn" id="{{$equipo->id}}" onclick="event.preventDefault();">Detalle</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                                {!! $equiposAbandonados->appends(['usuario' => $usuarioData, 'marca' => $marcaData, 'tipoequipo' => '$tipoequipoData', 'estante' => '$estanteData' ])->links() !!}
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
                    <div class="row" id="divRowBro">
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group mb-3">
                                <label for="presupuesto">Presupuesto</label>
                                <input type="text" name="presupuesto" id="presupuesto" class="form-control" disabled>  
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group mb-3">
                                <label for="fechaingreso">Fecha Ingreso</label>
                                <input type="text" name="fechaingreso" id="fechaingreso" class="form-control" disabled>  
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group mb-3">
                                <label for="fechareparacion">Fecha Reparación</label>
                                <input type="text" name="fechareparacion" id="fechareparacion" class="form-control" disabled>  
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script> 
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $('.js-example-basic-single').select2();

        $('body').on('click', '.detBtn', function (){

        var id = $(this).attr('id');
        console.log(id);
        var url = '{{route("getDetalleEquipoAbandonado", ":id")}}';
        url = url.replace(':id', id);

        $.ajax({
        url: url,
        method: 'GET',
        success: function(response){
            $('.modalCreateForm').modal('show');
            $('.modal-title').html('Detalle Equipo');

            $('#btnGuardar').addClass("d-none");
            $('#nameError').addClass("d-none");
            $('#emailError').addClass("d-none");
            $('#btnFinalizarRep').addClass("d-none");


            $('#divRowBro').removeClass("d-none");
            

            $('#id').val(response.data[0].id);
            $('#presupuesto').val(response.data[0].presupuesto);
            $('#fechareparacion').val(response.data[0].fechaFinReparacion);
            $('#fechaingreso').val(response.data[0].fechaIngreso);

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

            if(response.data[0].comentarios){

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

            if(response.data[0].comentarios[i].id_estado == 9){
                buttonH2.innerHTML = 'Detalle Reasignación ' + '<p style="color:black; display:inline; font-size:0.8rem">' + response.data[0].comentarios[i].created_at + '<p>';
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
            if(response.data[0].comentarios[i].id_estado == 11){
                buttonH2.innerHTML = 'Presupuesto Aceptado ' + '<p style="color:black; display:inline; font-size:0.8rem">' + response.data[0].comentarios[i].created_at + '<p>';
            }
            if(response.data[0].comentarios[i].id_estado == 15){
                buttonH2.innerHTML = 'Detalle Abandono ' + '<p style="color:black; display:inline; font-size:0.8rem">' + response.data[0].comentarios[i].created_at + '<p>';
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
        });
    </script>
@endsection
