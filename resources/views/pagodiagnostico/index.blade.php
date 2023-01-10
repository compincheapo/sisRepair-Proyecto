@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">Pagos Diagnóstico</h3>
            @can('crear-usuario')
                <a class="btn btn-warning section-header-breadcrumb" style="float:right;" href="{{route('pagodiagnostico.create')}}">Nuevo</a>
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
                            <label for="cliente">Cliente</label>
                            <select name="cliente" class="form-control js-example-basic-single" style="width:100%">
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
                            <input type="text" name="modelo" value="{{$modeloData}}" class="form-control">
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
                            <label for="tipopago">Tipo Pago</label>
                            <select name="tipopago" class="form-control">
                                    <option value="">Seleccione...</option>    
                                    @foreach($tipospago as $tipopago)
                                        @if($tipopagoData && $tipopago->id == $tipopagoData->id)
                                            <option value="{{$tipopago->id}}" selected>{{$tipopago->nombre}}</option>
                                        @endif
                                        <option value="{{$tipopago->id}}">{{$tipopago->nombre}}</option>
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
                    
                      {{--<div class="form-group col-md-6">
                        <label for="desde">Fecha Desde</label>
                        <input type="datetime-local" name="desde" value= "{{$desdeData}}" class="form-control">
                      </div>
                      <div class="form-group col-md-6">
                        <label for="hasta">Fecha Hasta</label>
                        <input type="datetime-local" name="hasta" value="{{$hastaData}}" class="form-control">
                      </div>--}}
                     
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
                                    <th style="color: #fff;">N° Orden</th>
                                    <th style="color: #fff;">Tipo Equipo</th>
                                    <th style="color: #fff;">Modelo</th>
                                    <th style="color: #fff;">Marca</th>
                                    <th style="color: #fff;">Cliente</th>
                                    <th style="color: #fff;">Fecha Pago</th>
                                    <th style="color: #fff;">Tipo Pago</th>
                                    <th style="color: #fff;">Presupuesto</th>
                                    
                                </thead>
                                <tbody>
                                   
                                    @foreach($pagos as $pago)
                                        @foreach($pago->ordenespago as $orden)
                                        <tr>
                                            <td>{{$orden->id}}</td>
                                            <td>{{$orden->equipo->tipoequipo->nombre}}</td>
                                            <td>{{$orden->equipo->modelo}}</td>
                                            <td>{{$orden->equipo->marca->nombre}}</td>
                                            <td>{{$orden->equipo->user->name}} {{$orden->equipo->user->lastname}}</td>
                                            <td>{{$pago->fechapago}}</td>
                                            <td>{{$pago->tipopago->nombre}}</td>
                                            <td>{{$orden->presupuestoOrden->presupuesto}}</td>
                                           
                                                                                        
                                        </tr>

                                        @endforeach
                                        
                                    @endforeach
                                </tbody>
                            </table>
                                {!! $pagos->appends(['usuario' => $usuarioData, 'modelo' => $modeloData, 'marca' => $marcaData, 'tipopago' => $tipopagoData, 'tipoequipo' => '$tipoequipoData'])->links() !!}
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
