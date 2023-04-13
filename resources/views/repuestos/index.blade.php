@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">Repuestos</h3>
            @can('crear-repuestos')
                <a class="btn btn-warning section-header-breadcrumb" style="float:right;" href="{{route('repuestos.create')}}">Nuevo</a>
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
                        <form class="form" action="{{ route('repuestos.index')}}" method="GET">
                        <div class="row">
                        <div class="form-group col-md-6">
                                <label for="modelo">Modelo</label>
                                <input type="text" class="form-control" id="modelo" name="modelo" value="{{$modeloData}}">
                        </div>

                        <div class="form-group col-md-6"> 
                                <label for="tiporepuesto">Tipo Repuesto</label>
                                <select name="tiporepuesto" class="form-control">
                                        <option value="">Seleccione...</option>    
                                        @foreach($tiporepuestos as $tiporepuesto)
                                            @if($tiporepuestoData && $tiporepuesto->id == $tiporepuestoData->id)
                                                <option value="{{$tiporepuesto->id}}" selected>{{$tiporepuesto->nombre}}</option>
                                            @else
                                                <option value="{{$tiporepuesto->id}}">{{$tiporepuesto->nombre}}</option>
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
                                            @else
                                                <option value="{{$marca->id}}">{{$marca->nombre}}</option>
                                            @endif
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
                                            @else
                                                <option value="{{$estante->id}}">{{$estante->nombre}}</option>
                                            @endif
                                        @endforeach
                                </select>
                        </div>
                        
                        <div class="form-group col-md-12">
                            <input type="submit" name="submitbtn" value="Filtrar" class="btn btn-light btn btn-icon icon-left"></input>
                            <input type="submit" name="submitbtn" value="PDF" class="btn btn-warning btn btn-icon icon-left"></input>
                            <a href="{{ url('/repuestos') }}" class="btn btn-info">Limpiar</a>
                            </div>
                        </div>
                        </form>
                        </div>
                    </div>
                    </div>
                    <div class="card">
                         @can('ver-repuestos')
                        <div class="card-body table-responsive">
                           
                            <table class="table table-striped mt-2">
                                <thead style="background-color: #6777ef;">
                                    <th style="display:none;">ID</th>
                                    <th style="color: #fff;">Modelo</th>
                                    <th style="color: #fff;">Tipo</th>
                                    <th style="color: #fff;">Marca</th>
                                    <th style="color: #fff;">Estante</th>
                                    <th style="color: #fff;">Sección Estante</th>
                                    <th style="color: #fff;">Cantidad</th>
                                    <th style="color: #fff;">Precio Unitario</th>
                                    <th style="color: #fff;">Acciones</th>
                                </thead>
                                <tbody>
                                    @foreach($repuestos as $repuesto)
                                        <tr>
                                            <td style="display:none">{{$repuesto->id}}</td>
                                            <td>{{$repuesto->modelo}}</td>
                                            <td>{{$repuesto->tiporepuesto->nombre}}</td>
                                            <td>{{$repuesto->marca->nombre}}</td>
                                            <td>{{$repuesto->seccionestante->estante->nombre}}</td>
                                            <td>{{$repuesto->seccionestante->nombre}}</td>
                                            <td>{{$repuesto->cantidad}}</td>
                                            <td>{{$repuesto->precio}}</td>
                                            <td>
                                                @can('editar-repuestos')
                                                <div class="btn-group">
                                                <a class="btn btn-info mr-1" href="{{route('repuestos.edit', $repuesto->id)}}">Editar</a>
                                                @endcan
                                                @can('borrar-repuestos')
                                                {!!Form::open(['method' => 'DELETE', 'route'=>['repuestos.destroy', $repuesto->id], 'style' =>'display:inline']) !!}
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
                                {!! $repuestos->appends(['marca' => $marcaData, 'tiporepuesto' => '$tiporepuestoData', 'estante' => '$estanteData', 'modeloData' => $modeloData])->links() !!}
                            </div>
                        </div>
                    @endcan   
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

