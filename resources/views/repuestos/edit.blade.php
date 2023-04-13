@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">Editar Repuesto</h3>
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

                        {!! Form::model($repuesto, ['method' => 'PATCH', 'route'=>['repuestos.update', $repuesto->id]]) !!}
                            <div class="row">
                                <input type="hidden" id="repuesto" value="{{$repuesto->id}}">
                                <div class="col-xs-6 col-sm-6 col-md-6">
                                    <div class="form-group"> 
                                        <label for="usuario">Tipo Repuesto</label>
                                        {!! Form::select('tiporepuesto', $tiposrepuestos, $tiporepuesto, ['class' => 'form-control js-example-basic-single']) !!}
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
                                        <label for="usuario">Modelo</label>
                                        {!! Form::text('modelo', null, array('class' => 'form-control'))!!}
                                    </div>
                                </div>                

                                <div class="col-xs-6 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <label for="serie">Cantidad</label>
                                        {!! Form::text('cantidad', null, array('class' => 'form-control'))!!}
                                    </div>
                                </div>

                                <div class="col-xs-6 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <label for="modelo">Precio</label>
                                        {!! Form::text('precio', null, array('class' => 'form-control'))!!}
                                    </div>
                                </div>

                                <div class="col-xs-6 col-sm-6 col-md-6">
                                <div class="form-group"> 
                                    <label for="estante">Estante</label>
                                    <select name="estante" class="form-control dynamic" data-dependent="seccion" id="estante">
                                        <option value="">Seleccionar...</option>
                                        @foreach ($estantes as $estante)
                                            @if($repuesto->seccionestante->estante->id == $estante->id)
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


                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group"> 
                                        <label for="descripcion">Detalles del Repuesto</label>
                                        <div class="form-control" style="visibility: hidden; padding:0; height:20px">
                                        </div>
                                        {!! Form::textarea('descripcion', null, ['style' => 'width:100%; resize:none;'])!!}                                        
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
        var repuesto = document.getElementById("repuesto").value;
        console.log(repuesto);

        if(e.value != ""){
            console.log("ajax");
            $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url:"{{ route('equipos.fetch') }}",
                    method:"POST",
                    dataType:"json",
                    data:{value:value, repuesto:repuesto},
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
        //console.log(value);
        //var text = e.options[e.selectedIndex].text;
        
       
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

