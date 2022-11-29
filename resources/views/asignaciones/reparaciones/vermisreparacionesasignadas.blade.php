@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/v/dt/dt-1.10.16/sl-1.2.5/datatables.min.css">
    <style>
        .modal-lg {
            max-width: 70% !important;
        }

    </style>
@endsection

@section('content')

<section class="section">
        <div class="section-header">
            <h3 class="page__heading">Mis Asignaciones Reparación</h3>
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
                    <div class="container">
                    <div class="table-responsive table-bordered">
                        <table id="example" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th width="10px">ID</th>
                                    <th>Serie</th>
                                    <th>Modelo</th>
                                    <th>Marca</th>
                                    <th>Estante</th>
                                    <th>Sección Estante</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                        </table>
                    </div>     
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
                 <label for="fechacompromiso">Fecha Compromiso</label>
                 <input type="text" name="fechacompromiso" id="fechacompromiso" class="form-control" disabled>  
                 <span id="emailError" class="text-danger error-message d-none"></span>
              </div>
             </div>
             <div class="col-xs-6 col-sm-6 col-md-6">
             <div class="form-group mb-3">
                <label for="fechaingreso">Fecha Ingreso</label>
                <input type="text" name="fechaingreso" id="fechaingreso" class="form-control" disabled>
                <span id="nameError" class="text-danger error-message d-none"></span>
             </div>
            </div>
         </div>
         
         <div id="stepper1" class="bs-stepper">
                        <div class="bs-stepper-header">
                            <div class="step" data-target="#test-l-1">
                                <button type="button" class="btn step-trigger">
                                <span class="bs-stepper-circle">1</span>
                                <span class="bs-stepper-label">Repuestos Utilizados</span>
                                </button>
                            </div>
                            <div class="line"></div>
                            <div class="step" data-target="#test-l-2">
                                <button type="button" class="btn step-trigger">
                                <span class="bs-stepper-circle">2</span>
                                <span class="bs-stepper-label">Detalles Reparación</span>
                                </button>
                            </div>
                            <div class="line"></div>
                            <div class="step" data-target="#test-l-3">
                                <button type="button" class="btn step-trigger">
                                <span class="bs-stepper-circle">3</span>
                                <span class="bs-stepper-label">Finalización</span>
                                </button>
                            </div>
                        </div>

                        <div class="bs-stepper-content">
                            <div id="test-l-1" class="content">
                              <div class=" table-bordered">
                                      <table id="users" class="table" style="width: 100%;">
                                              <thead>
                                                  <tr>
                                                      <th width="10px">ID</th>
                                                      <th>Tipo Repuesto</th>
                                                      <th>Marca</th>
                                                      <th>Modelo</th>
                                                      <th>Serie</th>
                                                      <th>Estante</th>
                                                      <th>Seccion Estante</th>
                                                      <th>Stock</th>
                                                      <th>Cantidad Ut.</th>
                                                  </tr>
                                              </thead>
                                      </table>
                              </div>

                              <button class="btn btn-primary mt-2" onclick="event.preventDefault()" id="equipos">Siguiente</button>
                            
                          </div>
                          <div id="test-l-2" class="content">
                              <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group"> 
                                        <label for="descripcion">Detalles del Equipo</label>
                                        <div class="form-control" style="visibility: hidden; padding:0; height:20px">
                                      </div>
                                        {!! Form::textarea('descripcion', null, ['style' => 'width:100%; resize:none;', 'id'=>'descripcion'])!!}                                        
                                    </div>
                                </div>
                              <button class="btn btn-secondary mt-2" onclick="stepper1.previous(), event.preventDefault()">Anterior</button>
                              <button class="btn btn-primary mt-2" onclick="event.preventDefault()" id="tecnicos">Siguiente</button>
                          </div>

                        <div id="test-l-3" class="content">

                    <table id="selected-equipment" class="table" style="width:100%">
                        <thead>
                            <tr>
                                <th>Tipo Repuesto</th>
                                <th>Marca</th>
                                <th>Modelo</th>
                                <th>Serie</th>
                                <th>Estante</th>
                                <th>Seccion Estante</th>
                                <th>Cantidad Ut.</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    
                    </table>


                            <p class="text-center">¿Está seguro de asignar estos Equipos?</p>
                            <button class="btn btn-secondary mt-2" onclick="stepper1.previous(), event.preventDefault()">Anterior</button>
                            <input type="submit" value="Asignar" class="btn btn-warning mt-2" id="enviar" onclick="event.preventDefault()">
                        </div>
                    </div>
                </div>
    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary closeBtn" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" id="btnGuardar">Reasignar</button>
        <button type="button" class="btn btn-primary d-none" id="btnIniciarRep">Iniciar Reparación</button> 
      </div>
    </div>
  </div>
</div>
</form>

@endsection
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bs-stepper/dist/js/bs-stepper.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/v/dt/dt-1.10.16/sl-1.2.5/datatables.min.js"></script>
<script src="https://gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/js/dataTables.checkboxes.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
var stepper1Node = document.querySelector('#stepper1')
var stepper1 = new Stepper(document.querySelector('#stepper1'),  {
    linear: false,
    animation: true
})

stepper1Node.addEventListener('show.bs-stepper', function (event) {
    //console.warn('show.bs-stepper', event)
})
stepper1Node.addEventListener('shown.bs-stepper', function (event) {
    //console.warn('shown.bs-stepper', event)
})

$(document).ready(function() {
    
  var tablita = $('#example').DataTable( {
      "serverSide": true,
      "ajax":  "{{route('misreparacionesasignadas')}}",
      "columns": [
          {data: 'id'},
          {data: 'serie'},
          {data: 'modelo'},
          {data: 'marca.nombre'},
          {data: 'estante'},
          {data: 'seccion_estante.nombre'},
          {data: 'estado'},
          {data: 'action', name: 'action', orderable: false, searchable:false}
      ],
  } );

  $('body').on('click', '.detBtn', function (){

  var id = $(this).data('id');
  var url = '{{route("reparacionasignada", ":id")}}';
  url = url.replace(':id', id);

  $.ajax({
  url: url,
  method: 'GET',
  success: function(response){
      $('.modalCreateForm').modal('show');
      $('.modal-title').html('Detalle Equipo');

      if(response.data[0].estado == 7){
        $('#btnIniciarRep').addClass("d-none");
      } else {
        $('#btnIniciarRep').removeClass("d-none");
      }

      $('#btnGuardar').addClass("d-none");
      $('#nameError').addClass("d-none");
      $('#emailError').addClass("d-none");
      $('#btnFinalizarRep').addClass("d-none");
      $('#stepper1').addClass("d-none");      


      if(document.getElementById('selectTecnicos')){
        $('#selectTecnicos').addClass("d-none");
      }

      if(document.getElementById('labelGroup')){
        $('#labelGroup').addClass("d-none");
      }
      
      if(document.getElementById('labelGroup2')){
        $('#labelGroup2').addClass("d-none");
      
      }
      if(document.getElementById('detalle')){
        $('#detalle').addClass("d-none");
      }

      if(document.getElementById('divCol')){
        document.getElementById('divCol').remove();
      }

      if(document.getElementById('divCol2')){
        document.getElementById('divCol2').remove();
      }

      if(document.getElementById('divCol3')){
        document.getElementById('divCol3').remove();
      }

      $('#divRowBro').removeClass("d-none");
      

      $('#id').val(response.data[0].id);
      $('#fechaingreso').val(response.data[0].fechaIngreso.created_at);
      $('#fechacompromiso').val(response.data[0].fechacompromiso);
      

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
  error: function(){
      console.log(error);
  }
  });
  });

  $('body').on('click', '#btnIniciarRep', function (){
      var id = $('#id').val();
    
      $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
      });


      $.ajax({
        url: "{{route('iniciarReparacion')}}",
        method: 'POST',
        data: {
          'id': id
        },
        success: function(response){
            if(response){
              $('#example').DataTable().ajax.reload();
              Swal.fire(
              'Reparación Iniciada!',
              response.success, "success")           
            }
            $('.modalCreateForm').modal('hide');
        },
        error: function(error){
            if(error){ 
              console.log(error)
            }
        }
      })
  });


$('body').on('click', '.reBtn', function (){

  var id = $(this).data('id');
  $('#id').val(id);

  var url = '{{route("getTecnicosReasignacion", ":id")}}';
  url = url.replace(':id', id);

  $.ajax({
    url: url,
    method: 'GET',
    success: function(response){
      console.log(response);
      $('.modalCreateForm').modal('show');
      $('.modal-title').html('Reasignación Equipo');

      $('#btnIniciarRep').addClass("d-none");
      $('#btnGuardar').removeClass("d-none");
      $('#nameError').addClass("d-none");
      $('#emailError').addClass("d-none");
      $('#divRowBro').addClass("d-none");
      $('#btnFinalizarRep').addClass("d-none");
      $('#stepper1').addClass("d-none");

      

      if(document.getElementById('accordionExample')){
              document.getElementById('accordionExample').remove();
      }

      if(document.getElementById('newCreated')){
              document.getElementById('newCreated').remove();
      }

      if(document.getElementById('divCol')){
          document.getElementById('divCol').remove();
      }

      if(document.getElementById('divCol2')){
          document.getElementById('divCol2').remove();
      }

      if(document.getElementById('divCol3')){
          document.getElementById('divCol3').remove();
      }

      if(response.data.length){

        //Modal Body
        var modalBody = document.getElementById('modal-body');

        //Div Col
        var divCol = document.createElement("div");
        divCol.setAttribute('id', 'divCol');
        divCol.setAttribute('class', 'col-xs-12 col-sm-12 col-md-12');

        modalBody.appendChild(divCol);

        //Div group
        var divGroup = document.createElement("div");
        divGroup.setAttribute('class', 'form-group mb-3');
        divCol.appendChild(divGroup);

        //label Group
        var labelGroup = document.createElement("label");
        labelGroup.setAttribute('id', 'labelGroup');
        labelGroup.innerHTML = 'Técnicos'
        divGroup.appendChild(labelGroup);

        //Select técnicos
        var selectTecnicos = document.createElement("select");
        selectTecnicos.setAttribute('class', 'form-control');
        selectTecnicos.setAttribute('id', 'selectTecnicos');
        selectTecnicos.setAttribute('name', 'selectTecnicos');
        divGroup.appendChild(selectTecnicos);

        //Options
        for (let i = 1; i < response.data.length ; i++) {
          if(response.data[i].id != response.data[0].user_id){
            var option = document.createElement("option");
            option.setAttribute('value', response.data[i].id);
            option.innerHTML =  response.data[i].lastname + ' ' + response.data[i].name;
            selectTecnicos.appendChild(option);
          }
        }
        
        //Div Col2
        var divCol2 = document.createElement("div");
        divCol2.setAttribute('id', 'divCol2');
        divCol2.setAttribute('class', 'col-xs-12 col-sm-12 col-md-12');

        modalBody.appendChild(divCol2);

        //Div group2
        var divGroup = document.createElement("div");
        divGroup.setAttribute('class', 'form-group mb-3');
        divCol2.appendChild(divGroup);

        //label Group
        var labelGroup2 = document.createElement("label");
        labelGroup2.setAttribute('id', 'labelGroup2');
        labelGroup2.innerHTML = 'Detalle'
        divGroup.appendChild(labelGroup2);

        //Detalle reasignacion
        var detalle = document.createElement("textarea");
        detalle.setAttribute('style', 'width:100%; resize:none;');
        detalle.setAttribute('rows', '10');
        detalle.setAttribute('id', 'detalle');
        detalle.setAttribute('name', 'detalle');
        divGroup.appendChild(detalle);

        modalBody.appendChild(divCol2);

      }

      },
      error: function(error){
          console.log(error);
      }
  });
});
  
//----------------------------- Reasignar Diagnóstico -----------------------------

$('body').on('click', '#btnGuardar', function (){
      var idEquipo = $('#id').val();
      var detalle = $('#detalle').val();
      var idTecnico = document.getElementById('selectTecnicos').value;
    
      $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
      });


      $.ajax({
        url: "{{route('reasignarreparacion')}}",
        method: 'POST',
        data: {
          'idEquipo': idEquipo,
          'detalle': detalle,
          'idTecnico': idTecnico,
        },
        success: function(response){
            if(response){
              console.log(response);
              $('#example').DataTable().ajax.reload();
              Swal.fire(
              'Reasignación Realizada!',
              response.success, "success")           
            }
            $('.modalCreateForm').modal('hide');
        },
        error: function(error){
            if(error){ 
              console.log(error)
            }
        }
      })
  });


//----------------------------- Finalizar Reparación -----------------------------

$('body').on('click', '.finBtn', function (){

var id = $(this).data('id');
$('#id').val(id);

var url = '{{route("getFinalizarReparacion", ":id")}}';
url = url.replace(':id', id);


$.ajax({
  url: url,
  method: 'GET',
  success: function(response){
    
    if(response.error){
      Swal.fire({
      icon: 'error',
      title: 'Error en Finalización de Reparación.',
      text: response.error,
      })
    }     
    if(!response.error){    
      $('.modalCreateForm').modal('show');
      $('.modal-title').html('Finalizar Reparación');

      $('#btnIniciarRep').addClass("d-none");
      $('#btnGuardar').addClass("d-none");
      $('#nameError').addClass("d-none");
      $('#emailError').addClass("d-none");
      $('#divRowBro').addClass("d-none");
      $('#btnFinalizarRep').removeClass("d-none");
      $('#stepper1').removeClass("d-none");
      

      if(document.getElementById('accordionExample')){
              document.getElementById('accordionExample').remove();
      }

      if(document.getElementById('newCreated')){
              document.getElementById('newCreated').remove();
      }

      if(document.getElementById('divCol')){
          document.getElementById('divCol').remove();
      }

      if(document.getElementById('divCol2')){
          document.getElementById('divCol2').remove();
      }

      if(document.getElementById('divCol3')){
          document.getElementById('divCol3').remove();
      }
      
        // //Modal Body
        // var modalBody = document.getElementById('modal-body');

        // //Div Col3
        // var divCol3 = document.createElement("div");
        // divCol3.setAttribute('id', 'divCol3');
        // divCol3.setAttribute('class', 'col-xs-12 col-sm-12 col-md-12');

        // modalBody.appendChild(divCol3);

        // //Div group2
        // var divGroup = document.createElement("div");
        // divGroup.setAttribute('class', 'form-group mb-3');
        // divCol3.appendChild(divGroup);

        // //label Group
        // var labelGroup2 = document.createElement("label");
        // labelGroup2.setAttribute('id', 'labelGroup2');
        // labelGroup2.innerHTML = 'Detalle'
        // divGroup.appendChild(labelGroup2);

        // //Detalle finalización
        // var detalle = document.createElement("textarea");
        // detalle.setAttribute('style', 'width:100%; resize:none;');
        // detalle.setAttribute('rows', '10');
        // detalle.setAttribute('id', 'detalle2');
        // detalle.setAttribute('name', 'detalle2');
        // divGroup.appendChild(detalle);

        // modalBody.appendChild(divCol3);
    }
    },
    error: function(error){
        console.log(error);
    }
});
});


$('body').on('click', '#enviar', function (){
      var idEquipo = $('#id').val();
      var detalle = $('#descripcion').val();

      console.log(idEquipo, "idEquipo");
      console.log(detalle, "detalle");
      console.log(global, "arreglo repuestos");
    
      $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
      });


      $.ajax({
        url: "{{route('finalizarReparacion')}}",
        method: 'POST',
        data: {
          'idEquipo': idEquipo,
          'detalle': detalle,
          'repuestos': global
        },
        success: function(response){
            console.log(response);
            if(response.success){
              $('#example').DataTable().ajax.reload();
              Swal.fire(
              'Reparación Finalizada!',
              response.success, "success")           
            }

            $('.modalCreateForm').modal('hide');
        },
        error: function(error){
            if(error){ 
              console.log(error)
            }
        }
      })
});


    
    var tablita = $('#example2').DataTable( {
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
        "ajax":  "{{route('repuestosReparacion')}}",
        "columns": [
            {data: 'id'},
            {data: 'tiporepuesto.nombre'},
            {data: 'marca.nombre'},
            {data: 'modelo'},
            {data: 'serie'},
            {data: 'seccion_estante.estante.nombre'},
            {data: 'seccion_estante.nombre'},
            {data: 'cantidad'},
            {data: 'cant',
            render: function(data) {
                return "<input type='number' onclick='event.preventDefault(), event.stopPropagation();' min='1'>";
            },}
    
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

    //Ocultamos columna "Stock"
    table.column(7).visible(false);



    function uncheckEquipment(e){
                var idEquipment = e.target.id;
                console.log('id elemento entrante ', e.target.id);
                e.preventDefault();
                console.log('equipment', idEquipment);
                var equipment = document.getElementById(idEquipment);
                var filaEquipment = equipment.parentNode.parentNode;
                var rows_selected = table.column(0).checkboxes.selected().count();


                for (let i = 0; i <= table.columns().checkboxes.selected()[0].length; i++) {
                    console.log('iterador', i);
                    console.log(table.row(i).data().id);
                    
                    console.log(rows_selected);
                    if(rows_selected <= 1){
                        Swal.fire('Como mínimo debe ser 1 Repuesto.')
                    } else if(rows_selected > 1) {
                        if(table.row(i).data().id == idEquipment){
                          console.log('a borrar ',table.row(i).data().id);    
                          table.row(i).deselect();
                          filaEquipment.parentNode.removeChild(filaEquipment);
                        }
                        
                    }
                    
                }
            }
    
    var global = [];

    $('#equipos').on('click', function(e){
                var tbodySelectedEquipments = document.getElementById('selected-equipment').getElementsByTagName('tbody')[0];
                var rows_selected = table.column(0).checkboxes.selected().count();
                var limitStock = false;
                var whiteSpace = false;
                var zeroNumber = false;
                var eCaracter = false;
                var negativeNumber = false;


                table.rows( {selected: true} ).every( function () {

                //Con node podemos interactuar con el input.  
                rowNode = this.node();

                //Con data podemos interactuar de forma mas sencilla con datos de tabla como lo dado abajo en el for.
                rowData = this.data();
                console.log(rowData);
                
                console.log($(rowNode).find("input[type='number']").val(), 'holi');

                //Esta seccion borra los 
                $(rowNode).find("input[type='number']").val("");
              } );
              
            
                
                for (let index = 0; index < table.rows({selected: true})[0].length; index++) {
                  console.log(table.$('input[type=number]')[index].value);
                  if(table.$('input[type=number]')[index].value != ""){
                    if(table.rows({selected: true}).data()[index].cantidad < table.$('input[type=number]')[index].value){
                      Swal.fire({
                        icon: 'error',
                        title: 'Error en cantidad repuestos especificada, es mayor al Stock (' + table.rows({selected: true}).data()[index].cantidad
                        + ')  disponible.',
                        text: 'En Repuesto: ' + table.rows({selected: true}).data()[index].tiporepuesto.nombre + ', Marca: ' + 
                        table.rows({selected: true}).data()[index].marca.nombre + ', Modelo:' +
                        table.rows({selected: true}).data()[index].modelo + ', Serie:' +
                        table.rows({selected: true}).data()[index].serie  + '.',
                      })

                        limitStock = true;
                    }
                  } else {
                      Swal.fire({
                          icon: 'error',
                          title: 'No ha especificado cantidad en Repuesto.',
                          text: 'En Repuesto: ' + table.rows({selected: true}).data()[index].tiporepuesto.nombre + ', Marca: ' + 
                          table.rows({selected: true}).data()[index].marca.nombre + ', Modelo:' +
                          table.rows({selected: true}).data()[index].modelo + ', Serie:' +
                          table.rows({selected: true}).data()[index].serie  + '.',
                        })
                      
                      whiteSpace = true;
                  }

                  if (table.$('input[type=number]')[index].value == '0'){
                    Swal.fire({
                          icon: 'error',
                          title: 'No es válido 0 Repuestos',
                          text: 'En Repuesto: ' + table.rows({selected: true}).data()[index].tiporepuesto.nombre + ', Marca: ' + 
                          table.rows({selected: true}).data()[index].marca.nombre + ', Modelo:' +
                          table.rows({selected: true}).data()[index].modelo + ', Serie:' +
                          table.rows({selected: true}).data()[index].serie  + '.',
                        })
                        
                      zeroNumber = true;
                  }

                  if (table.$('input[type=number]')[index].value < 0){
                    Swal.fire({
                          icon: 'error',
                          title: 'No es válido números negativos',
                          text: 'En Repuesto: ' + table.rows({selected: true}).data()[index].tiporepuesto.nombre + ', Marca: ' + 
                          table.rows({selected: true}).data()[index].marca.nombre + ', Modelo:' +
                          table.rows({selected: true}).data()[index].modelo + ', Serie:' +
                          table.rows({selected: true}).data()[index].serie  + '.',
                        })
                        
                      negativeNumber = true;
                  }
                  }
                    

                if(!rows_selected){
                    Swal.fire('Debe de elegir al menos un Repuesto.')
                } else if(rows_selected && !limitStock && !whiteSpace && !zeroNumber && !eCaracter && !negativeNumber){
                    stepper1.next();
                }

                
                tbodySelectedEquipments.innerHTML = "";

                for (let index = 0; index < table.rows({selected: true})[0].length; index++) {
                    console.log(table.rows({selected: true}).data()[index].id);
                   //console.log(table.rows({selected: true}).data()[index]);
                    //Insertando Fila
                    var newRow = tbodySelectedEquipments.insertRow();

                    
                    //Celda Tipo Repuesto
                    var celdaTipoRepuesto = newRow.insertCell();
                    //Insertando Contenido tipo texto.
                    var contenidoTipoRepuesto = document.createTextNode(table.rows({selected: true}).data()[index].tiporepuesto.nombre);
                    //Insertando sobre la celda el contenido tipo texto.
                    celdaTipoRepuesto.appendChild(contenidoTipoRepuesto);

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

                    //Celda Serie
                    var celdaSerie = newRow.insertCell();
                    //Insertando Contenido tipo texto.
                    var contenidoSerie = document.createTextNode(table.rows({selected: true}).data()[index].serie);
                    //Insertando sobre la celda el contenido tipo texto.
                    celdaSerie.appendChild(contenidoSerie);

                    //Celda Estante
                    var celdaEstante = newRow.insertCell();
                    //Insertando Contenido tipo texto.
                    var contenidoEstante = document.createTextNode(table.rows({selected: true}).data()[index].seccion_estante.estante.nombre);
                    //Insertando sobre la celda el contenido tipo texto.
                    celdaEstante.appendChild(contenidoEstante);

                    //Celda Seccion Estante
                    var celdaSeccionEstante = newRow.insertCell();
                    //Insertando Contenido tipo texto.
                    var contenidoSeccionEstante = document.createTextNode(table.rows({selected: true}).data()[index].seccion_estante.nombre);
                    //Insertando sobre la celda el contenido tipo texto.
                    celdaSeccionEstante.appendChild(contenidoSeccionEstante);

                    //Celda Cantidad
                    var celdaCantidad = newRow.insertCell();
                    //Insertando Contenido tipo texto.
                    var contenidoCantidad = document.createTextNode(table.$('input[type=number]')[index].value);
                    //Insertando sobre la celda el contenido tipo texto.
                    celdaCantidad.appendChild(contenidoCantidad);


                    global.push(table.$('input[type=number]')[index].value);

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

               
            });

            $('#tecnicos').on('click', function(e){
                    stepper1.next();
            });


});
</script>
@endsection