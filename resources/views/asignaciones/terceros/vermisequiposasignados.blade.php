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
            <h3 class="page__heading">Mis Equipos Asignados</h3>
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
                    <div class="table-responsive table-bordered">
                        <table id="example" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th width="10px">ID</th>
                                    <th>Serie</th>
                                    <th>Modelo</th>
                                    <th>Marca</th>
                                    <th>Servicio</th>
                                    <th>Estado</th>
                                    <th>Fecha Retiro</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                        </table>
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
    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary closeBtn" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary d-none" id="btnFinalizarDiag">Finalizar</button> 
      </div>
    </div>
  </div>
</div>
</form>

@endsection
@section('scripts')
    
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/v/dt/dt-1.10.16/sl-1.2.5/datatables.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    
$(document).ready(function() {
    
  var tablita = $('#example').DataTable( {
      "serverSide": true,
      "ajax":  "{{route('misequiposasignados')}}",
      "columns": [
          {data: 'id'},
          {data: 'serie'},
          {data: 'modelo'},
          {data: 'marca.nombre'},
          {data: 'servicio'},
          {data: 'estado'},
          {data: 'fechaRetiro'},
          {data: 'action', name: 'action', orderable: false, searchable:false}
      ],
      'order': [[1, 'asc']],
      "language": {
          "info": "_TOTAL_ registros",
          "search": "Buscar",
          "paginate": {
              "next": "Siguiente",
              "previous": "Anterior",
          },
          "lengthMenu": 'Mostrar <select >'+
                      '<option value="5">5</option>'+
                      '<option value="10">10</option>'+
                      '<option value="50">50</option>'+
                      '<option value="100">100</option>'+
                      '<option value="-1">Todos</option>'+
                      '</select> registros',
          "loadingRecords": "Cargando...",
          "processing": "Procesando...",
          "emptyTable": "No hay datos",
          "zeroRecords": "No hay coincidencias", 
          "infoEmpty": "",
          "infoFiltered": ""
      }
  } );

  $('body').on('click', '.detBtn', function (){

  var id = $(this).data('id');
  var url = '{{route("getAsignacionEquipoTercero", ":id")}}';
  url = url.replace(':id', id);

  $.ajax({
  url: url,
  method: 'GET',
  success: function(response){
      $('.modalCreateForm').modal('show');
      $('.modal-title').html('Detalle Equipo');

      if(response.data[0].estado == 3){
        $('#btnIniciarDiag').addClass("d-none");
      } else {
        $('#btnIniciarDiag').removeClass("d-none");
      }

      $('#btnGuardar').addClass("d-none");
      $('#nameError').addClass("d-none");
      $('#emailError').addClass("d-none");
      $('#btnFinalizarDiag').addClass("d-none");


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
      $('#fechaingreso').val(response.data[0].fechaIngreso);
      $('#fechacompromiso').val(response.data[0].fechacompromiso);
      

      if(document.getElementById('newCreated')){
          document.getElementById('newCreated').remove();
      }

      if(document.getElementById('accordionExample')){
          document.getElementById('accordionExample').remove();
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

        var createdDate = new Date(response.data[0].comentarios[i].created_at);
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
        if(response.data[0].comentarios[i].id_estado == 1){
          buttonH2.innerHTML = 'Detalle ingreso Equipo ' + '<p style="color:black; display:inline; font-size:0.8rem">' + fecha + '<p>';
        }
        if(response.data[0].comentarios[i].id_estado == 4){
          buttonH2.innerHTML = 'Detalle Diagnóstico ' + '<p style="color:black; display:inline; font-size:0.8rem">' + fecha + '<p>';
        }
        if(response.data[0].comentarios[i].id_estado == 5){
          buttonH2.innerHTML = 'Detalle Asignación Reparación ' + '<p style="color:black; display:inline; font-size:0.8rem">' + fecha + '<p>';
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


//----------------------------- Finalizar Diagnóstico -----------------------------

$('body').on('click', '.finBtn', function (){

var id = $(this).data('id');
$('#id').val(id);

var url = '{{route("getFinalizarServicioEquipo", ":id")}}';
url = url.replace(':id', id);


$.ajax({
  url: url,
  method: 'GET',
  success: function(response){
    if(response.error){
      Swal.fire({
      icon: 'error',
      title: 'Error en Finalización del Servicio.',
      text: response.error,
      })
    }     
    if(!response.error){    
      $('.modalCreateForm').modal('show');
      if(response == 2){
        $('.modal-title').html('Finalizar Diagnóstico');
      }
      if(response == 6){
        $('.modal-title').html('Finalizar Reparación');
      }

      $('#btnIniciarDiag').addClass("d-none");
      $('#btnGuardar').addClass("d-none");
      $('#nameError').addClass("d-none");
      $('#emailError').addClass("d-none");
      $('#divRowBro').addClass("d-none");
      $('#btnFinalizarDiag').removeClass("d-none");
      

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
      
        //Modal Body
        var modalBody = document.getElementById('modal-body');

        //Div Col3
        var divCol3 = document.createElement("div");
        divCol3.setAttribute('id', 'divCol3');
        divCol3.setAttribute('class', 'col-xs-12 col-sm-12 col-md-12');

        modalBody.appendChild(divCol3);

        //Div group2
        var divGroup = document.createElement("div");
        divGroup.setAttribute('class', 'form-group mb-3');
        divCol3.appendChild(divGroup);

        //label Group
        var labelGroup2 = document.createElement("label");
        labelGroup2.setAttribute('id', 'labelGroup2');
        labelGroup2.innerHTML = 'Detalle'
        divGroup.appendChild(labelGroup2);

        //Detalle finalización
        var detalle = document.createElement("textarea");
        detalle.setAttribute('style', 'width:100%; resize:none;');
        detalle.setAttribute('rows', '10');
        detalle.setAttribute('id', 'detalle2');
        detalle.setAttribute('name', 'detalle2');
        divGroup.appendChild(detalle);

        modalBody.appendChild(divCol3);
    }
    },
    error: function(error){
        console.log(error);
    }
});



$('body').on('click', '#btnFinalizarDiag', function (){
      var idEquipo = $('#id').val();
      var detalle = $('#detalle2').val();
    
      $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
      });


      $.ajax({
        url: "{{route('finalizarServicioEquipo')}}",
        method: 'POST',
        data: {
          'idEquipo': idEquipo,
          'detalle': detalle,
        },
        success: function(response){
            if(response.success){
              $('#example').DataTable().ajax.reload();
              Swal.fire(
              'Servicio Finalizado!',
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
});


});
</script>
@endsection