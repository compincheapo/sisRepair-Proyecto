@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">Auditoria</h3>
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
                    <form class="form" action="{{ route('auditoria.index')}}" method="GET">
                    <div class="row">
                    <div class="form-group col-md-6"> 
                            <label for="usuario">Usuario</label>
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
                            <label for="modelo">Modelo</label>
                            <select name="modelo" class="form-control js-example-basic-single" style="width:100%">
                                <option value="">Seleccione...</option>
                                @foreach($modelos as $modelo)
                                    @if($modelo == $modeloData)
                                        <option value="{{$modelo}}" selected>{{$modelo}}</option>    
                                    @elseif($modelo != $modeloData)
                                        <option value="{{$modelo}}">{{$modelo}}</option>    
                                    @endif
                                @endforeach
                            </select>
                    </div>

                    <div class="form-group col-md-6"> 
                            <label for="accion">Acción</label>
                            <select name="accion" class="form-control">
                                    <option value="">Seleccione...</option>    
                                    <option value="updated" @if($accionData && $accionData == 'updated') selected  @endif>Actualización</option>    
                                    <option value="created" @if($accionData && $accionData == 'created') selected  @endif>Creación</option>    
                                    <option value="deleted" @if($accionData && $accionData == 'deleted') selected  @endif>Eliminación</option>    
                            </select>
                    </div>
                    
                      <div class="form-group col-md-6">
                        <label for="desde">Fecha Desde</label>
                        <input type="datetime-local" name="desde" value= "{{$desdeData}}" class="form-control">
                      </div>
                      <div class="form-group col-md-6">
                        <label for="hasta">Fecha Hasta</label>
                        <input type="datetime-local" name="hasta" value="{{$hastaData}}" class="form-control">
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
                                    <th style="display:none;">ID</th>
                                    <th style="color: #fff;">Usuario</th>
                                    <th style="color: #fff;">Modelo</th>
                                    <th style="color: #fff;">Acción</th>
                                    <th style="color: #fff;">Fecha</th>
                                    <th style="color: #fff;">Detalle</th>
                                    
                                </thead>
                                <tbody>
                                    @foreach($auditorias as $auditoria)
                                        <tr>
                                            <td style="display:none">{{$auditoria->id}}</td>
                                            <td>{{$auditoria->user()->first()->name}} {{$auditoria->user()->first()->lastname}}</td>
                                            <td>{{$auditoria->auditable_type}}</td>
                                            @if($auditoria->event == 'created')
                                                <td>Creación</td>
                                            @elseif($auditoria->event == 'updated')
                                                <td>Actualización</td>
                                            @elseif($auditoria->event == 'deleted')
                                                <td>Eliminación</td>
                                            @endif
                                            
                                            <td>{{$auditoria->created_at}}</td>  
                                            <td style="text-align: center"> <a data-collapse="#{{$auditoria->id}}" class="btn btn-icon btn-info" href="#"><i class="fas fa-plus"></i></a></td>  
                                            
                                        </tr>
                                        <tr class="collapse" id="{{$auditoria->id}}">
                                            <td colspan="12" style="background-color:antiquewhite" >
                                                <div class="card-body" class="collapse" id="{{$auditoria->id}}">
                                                        @foreach ($auditoria->getModified() as $attribute => $modified)
                                                        <ul>
                                                            @if($auditoria->event == 'updated')
                                                                <li>@lang('auditoria.'.$auditoria->auditable_type.'.'.$auditoria->event.'.modified.'.$attribute, $modified)</li>
                                                            @elseif($auditoria->event == 'created')
                                                                <li>@lang('auditoria.'.$auditoria->auditable_type.'.'.$auditoria->event.'.'.$attribute, $modified)</li>
                                                            @elseif($auditoria->event == 'deleted')
                                                                <li>@lang('auditoria.'.$auditoria->auditable_type.'.'.$auditoria->event.'.'.$attribute, $modified)</li>
                                                            @endif

                                                        </ul>
                                                        @endforeach
                                                    </li>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                                {!! $auditorias->appends(['usuario' => $usuarioData, 'modelo' => $modeloData, 'accion' => $accionData, 'desde' => $desdeData, 'hasta' => $hastaData])->links() !!}
                        </div>
                        </div>
                        
                    @endcan   
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script> 
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $('.js-example-basic-single').select2();
    </script>
@endsection
