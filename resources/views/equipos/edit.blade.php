@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">Editar Equipo</h3>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                        @if($errors->any())
                            <div class="alert alert-dark alert-dismissible fade-snow" role="alert">
                                <strong>Error al ingresar los campos.</strong>
                                    @foreach($errors->all() as $error)
                                        <span class="badge badge-danger">{{$error}}</span>
                                    @endforeach
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                            </div>
                        @endif    

                        {!! Form::model($equipo, ['method' => 'PATCH', 'route'=>['equipos.update', $equipo->id]]) !!}
                        <div class="row">
                                <input type="hidden" id="equipo" value="{{$equipo->id}}">

                                <div class="col-xs-6 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <label for="serie">Número Serie</label>
                                        {!! Form::text('serie', null, array('class' => 'form-control'))!!}
                                    </div>
                                </div>

                                <div class="col-xs-6 col-sm-6 col-md-6">
                                    <div class="form-group"> 
                                        <label for="usuario">Modelo</label>
                                        {!! Form::text('modelo', null, array('class' => 'form-control'))!!}
                                    </div>
                                </div>   

                                <div class="col-xs-6 col-sm-6 col-md-6">
                                        <div class="form-group">
                                            <label for="tipoequipo">Tipo Equipo</label>

                                                {!! Form::select('tipoequipo', $tiposequipos, $tipoequipo, ['class' => 'form-control']) !!}
                                        </div>
                                </div>

                                <div class="col-xs-6 col-sm-6 col-md-6">
                                    <div class="form-group"> 
                                        <label for="usuario">Marca</label>
                                        {!! Form::select('marca', $marcas, $marca, ['class' => 'form-control js-example-basic-single']) !!}
                                    </div>
                                </div>  

                                <div class="col-xs-6 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <label for="usuario">Usuario</label>
                                        <input type="text" class="form-control" name="usuario" value="{{$equipo->user->lastname}} {{$equipo->user->name}}" readonly>
                                    </div>
                                </div>             

                                <div class="col-xs-6 col-sm-6 col-md-6">
                                <div class="form-group"> 
                                    <label for="estante">Estante</label>
                                    <select name="estante" class="form-control dynamic" data-dependent="seccion" id="estante">
                                        <option value="">Seleccionar...</option>
                                        @foreach ($estantes as $estante)
                                            @if($equipo->seccionEstante->estante->id == $estante->id)
                                            <option value="{{$estante->id}}" selected>{{$estante->nombre}}</option>
                                            @else
                                            <option value="{{$estante->id}}">{{$estante->nombre}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                </div>

                                <div class="col-xs-6 col-sm-6 col-md-6">
                                <div class="form-group"> 
                                    <label for="seccion">Sección Estante</label>
                                    <select name="seccion" class="form-control" id="seccion">
                                        <option value="">Seleccionar...</option>
                                    </select>
                                </div>
                                </div>

                                <div class="col-xs-6 col-sm-6 col-md-6">
                                    <div class="form-group"> 
                                      <label for="accesorios">Accesorios</label>
                                      <select class="js-example-basic-multiple form-control" name="accesorios[]" multiple="multiple">
                                            @foreach ($tiposaccesorios as $tipoaccesorio)
                                                @if(in_array($tipoaccesorio->id, $accesorios))
                                                 <option value="{{$tipoaccesorio->id}}" selected>{{$tipoaccesorio->nombre}}</option>
                                                @else
                                                    <option value="{{$tipoaccesorio->id}}">{{$tipoaccesorio->nombre}}</option>
                                                @endif
                                            @endforeach
                                      </select>
                                     </div>
                                    </div>

                                
                            </div>
                                <div class="row mt-2">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <button type="submit" class="btn btn-primary">Guardar</button>
                                </div>
                                </div>
                            {!! Form::close() !!}

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script> 
    
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>

        $('.js-example-basic-single').select2();
        $('.js-example-basic-multiple').select2();

        var e = document.getElementById("estante");
        var value = e.value;
        console.log(value);
        var equipo = document.getElementById("equipo").value;
        console.log(equipo);

        if(e.value != ""){
            console.log("ajax");
            $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url:"{{ route('equipos.fetch') }}",
                    method:"POST",
                    dataType:"json",
                    data:{value:value, equipo:equipo},
                    success:function(result){
                        console.log("ok");
                        $('#seccion').html(result);
                    },
                    error:function(err){
                        $('#seccion').html(err.responseText);

                            console.log(err);
                        }
                    })
        }
       
        $(document).ready(function(){

            $('.dynamic').change(function(){
                if($(this).val() != '')
                {

                    var value = $(this).val();
                    var dependent = $(this).data('dependent');
                
                    $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url:"{{ route('equipos.fetch') }}",
                    method:"POST",
                    dataType:"json",
                    data:{value:value},
                    success:function(result){
                        $('#'+dependent).html(result);
                    },
                    error:function(err){
                        $('#'+dependent).html(err.responseText);

                            console.log(err);
                        }
                    })
                }
        });

        $('#estante').change(function(){
            $('#seccion').val('');
        });

    });

    
    </script>
@endsection

