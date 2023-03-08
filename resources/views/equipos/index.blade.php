@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">Equipos</h3>
            @can('crear-equipos')
                 <a class="btn btn-warning section-header-breadcrumb"  style="float:right;" href="{{route('equipos.create')}}">Nuevo</a>
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
                        <form class="form" action="{{ route('equipos.index')}}" method="GET">
                        <div class="row">
                        <div class="form-group col-md-6">
                                <label for="serie">Serie</label>
                                <input type="text" class="form-control" id="serie" name="serie" value="{{$serieData}}">
                        </div>
                        <div class="form-group col-md-6">
                                <label for="modelo">Modelo</label>
                                <input type="text" class="form-control" id="modelo" name="modelo" value="{{$modeloData}}">
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
                        
                        <div class="form-group col-md-12">
                            <input type="submit" name="submitbtn" value="Filtrar" class="btn btn-light btn btn-icon icon-left"></input>
                            <input type="submit" name="submitbtn" value="PDF" class="btn btn-warning btn btn-icon icon-left"></input>
                            <a href="{{ url('/equipos') }}" class="btn btn-info">Limpiar</a>
                            </div>
                        </div>
                        </form>
                        </div>
                    </div>
                    </div>
                    <div class="card">
                         @can('ver-equipos')
                        <div class="card-body">
                            <table class="table table-striped mt-2">
                                <thead style="background-color: #6777ef;">
                                    <th style="display:none;">ID</th>
                                    <th style="color: #fff;">Serie</th>
                                    <th style="color: #fff;">Modelo</th>
                                    <th style="color: #fff;">Tipo Equipo</th>
                                    <th style="color: #fff;">Marca</th>
                                    <th style="color: #fff;">Estante</th>
                                    <th style="color: #fff;">Sección Estante</th>
                                    <th style="color: #fff;">Accesorios</th>
                                    <th style="color: #fff;">Cliente</th>
                                    <th style="color: #fff;">Acciones</th>
                                </thead>
                                <tbody>
                                    @foreach($equipos as $equipo)
                                        <tr>
                                            <td style="display:none">{{$equipo->id}}</td>
                                            <td>{{$equipo->serie}}</td>
                                            <td>{{$equipo->modelo}}</td>
                                            <td>{{$equipo->tipoequipo->nombre}}</td>
                                            <td>{{$equipo->marca->nombre}}</td>
                                            <td>{{$equipo->seccionestante->estante->nombre}}</td>
                                            <td>{{$equipo->seccionestante->nombre}}</td>
                                            <td>
                                            @foreach($equipo->accesorios as $accesorio)
                                            <span class="badge badge-dark mb-1">{{$accesorio->nombre}}</span>
                                            @endforeach
                                            </td>
                                            <td>{{$equipo->user->name . ' ' . $equipo->user->lastname}}</td>
                                            <td>
                                                @can('editar-equipos')
                                                <div class="btn-group">
                                                <a class="btn btn-info mr-1" href="{{route('equipos.edit', $equipo->id)}}">Editar</a>
                                                @endcan
                                                @can('borrar-equipos')
                                                {!!Form::open(['method' => 'DELETE', 'route'=>['equipos.destroy', $equipo->id], 'style' =>'display:inline']) !!}
                                                    {!!Form::submit('Borrar', ['class' => 'btn btn-danger'])!!}
                                                {!!Form::close() !!}
                                                @endcan
                                                </div>

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="pagination justify-content-end">
                                {!! $equipos->appends(['usuario' => $usuarioData, 'marca' => $marcaData, 'tipoequipo' => '$tipoequipoData', 'estante' => '$estanteData', 'serie' => $serieData, 'modeloData' => $modeloData])->links() !!}
                            </div>
                        </div>
                    @endcan   
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

