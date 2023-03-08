@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">Pagos Reparación</h3>
            @can('crear-usuario')
                <a class="btn btn-warning section-header-breadcrumb" style="float:right;" href="{{route('pagoreparacion.create')}}">Nuevo</a>
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
                    <form class="form" action="{{ route('pagoreparacion.index')}}" method="GET">
                    <div class="row">
                    <div class="form-group col-md-6"> 
                            <label for="orden">Nro Orden</label>
                            <input type="text" name="orden" value="{{$ordenData}}" class="form-control">
                    </div>
                    <div class="form-group col-md-6"> 
                            <label for="tipoequipo">Tipo Equipo</label>
                            <select name="tipoequipo" class="form-control">
                                    <option value="">Seleccione...</option>    
                                    @foreach($tiposequipo as $tipoequipo)
                                        @if($tipoequipoData && $tipoequipo->id == $tipoequipoData->id)
                                            <option value="{{$tipoequipo->id}}" selected>{{$tipoequipo->nombre}}</option>
                                        @else
                                        <option value="{{$tipoequipo->id}}">{{$tipoequipo->nombre}}</option>
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
                                        @else
                                        <option value="{{$marca->id}}">{{$marca->nombre}}</option>
                                        @endif
                                    @endforeach
                            </select>
                    </div>
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
                        <label for="fechapagodesde">Fecha Pago Desde</label>
                        <input type="datetime-local" name="fechapagodesde" value= "{{$fechapagodesdeData}}" class="form-control">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="fechapagohasta">Fecha Pago Hasta</label>
                        <input type="datetime-local" name="fechapagohasta" value= "{{$fechapagohastaData}}" class="form-control">
                    </div>

                    <div class="form-group col-md-6"> 
                            <label for="tipopago">Tipo Pago</label>
                            <select name="tipopago" class="form-control">
                                    <option value="">Seleccione...</option>    
                                    @foreach($tipospago as $tipopago)
                                        @if($tipopagoData && $tipopago->id == $tipopagoData->id)
                                            <option value="{{$tipopago->id}}" selected>{{$tipopago->nombre}}</option>
                                        @else
                                        <option value="{{$tipopago->id}}">{{$tipopago->nombre}}</option>
                                        @endif
                                    @endforeach
                            </select>
                    </div>
                     
                       <div class="form-group col-md-12">
                            <input type="submit" name="submitbtn" value="Filtrar" class="btn btn-light btn btn-icon icon-left"></input>
                            <input type="submit" name="submitbtn" value="PDF" class="btn btn-warning btn btn-icon icon-left"></input>
                            <a href="{{ url('/pagoreparacion') }}" class="btn btn-info">Limpiar</a>
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
                        <table class="table table-striped">
                                <thead style="background-color: #6777ef;">
                                    <th style="display:none;">ID</th>
                                    <th style="color: #fff;">ID</th>
                                    <th style="color: #fff;">Cliente</th>
                                    <th style="color: #fff;">Fecha Pago</th>
                                    <th style="color: #fff;">Tipo Pago</th>
                                    <th style="color: #fff;">Total</th>
                                    <th style="color: #fff;">Acción</th>
                                    
                                </thead>
                                <tbody>
                                   
                                    @foreach($pagos as $pago)
                                        <tr>
                                            <td>{{$pago->id}}</td>
                                            <td>{{$pago->ordenespago()->first()->equipo->user->name}} {{$pago->ordenespago()->first()->equipo->user->lastname}}</td>
                                            <td>{{$pago->fechapago}}</td>
                                            <td>{{$pago->tipopago->nombre}}</td>
                                            <td>{{$pago->precio}}</td>
                                            <td style="text-align: center">
                                                <a class="btn btn-primary mr-2" href="{{route('comprobanteReparacion', $pago->id)}}">Comprobante</a>
                                                <a class="btn btn-warning mr-2" href="{{route('reciboReparacion', $pago->id)}}">Recibo</a>
                                                <a data-collapse="#{{$pago->id}}" class="btn btn-icon btn-info" href="#"><i class="fas fa-plus"></i></a>
                                            </td>
                                        </tr>
                                        <tr class="collapse" id="{{$pago->id}}">
                                            <td colspan="12" style="background-color:#3abaf4; padding: 0px 5px" >
                                                <div class="card-body" class="collapse" id="{{$pago->id}}" style="padding: 5px 0px">
                                                    <table style="width:100%;background-color:white;" class="table-border">
                                                        <thead>
                                                            <th>N° Orden</th>
                                                            <th>Tipo Equipo</th>
                                                            <th>Modelo</th>
                                                            <th>Marca</th>
                                                            <th>Precio</th>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($pago->ordenespago as $orden)
                                                                <tr>
                                                                    <td>{{$orden->id}}</td>
                                                                    <td>{{$orden->equipo->tipoequipo->nombre}}</td>
                                                                    <td>{{$orden->equipo->modelo}}</td>
                                                                    <td>{{$orden->equipo->marca->nombre}}</td>
                                                                    <td>{{$pago->precio/count($pago->ordenespago()->get())}}</td>
                                                                </tr> 
                                                            @endforeach                                                                       
                                                        </tbody>
                                                    </table>

                                                        
                                                    </li>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                                {!! $pagos->appends(['usuario' => $usuarioData, 'modelo' => $modeloData, 'marca' => $marcaData, 'tipopago' => $tipopagoData, 'tipoequipo' => '$tipoequipoData', 'orden' => $ordenData, 'fechapagodesde' =>  $fechapagodesdeData, 'fechapagohasta' => $fechapagohastaData])->links() !!}
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