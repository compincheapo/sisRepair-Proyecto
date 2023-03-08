@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">Estantes</h3>
            @can('crear-estantes')
             <a class="btn btn-warning section-header-breadcrumb" style="float:right;" href="{{route('estantes.create')}}">Nuevo</a>
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
                            <form class="form" action="{{ route('estantes.index')}}" method="GET">
                            <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="name">Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" value="{{$nombre}}" placeholder="Ej: Estante 1">
                            </div>
                            <div class="form-group col-md-12">
                                <label for="name">Descripción</label>
                                <input type="text" class="form-control" id="descripcion" name="descripcion" value="{{$descripcion}}" placeholder="Ej: Estante para equipos de la marca...">
                            </div>
                            <div class="form-group col-md-12">
                                    <input type="submit" name="submitbtn" value="Filtrar" class="btn btn-light btn btn-icon icon-left"></input>
                                    <input type="submit" name="submitbtn" value="PDF" class="btn btn-warning btn btn-icon icon-left"></input>
                                    <a href="{{ url('/estantes') }}" class="btn btn-info">Limpiar</a>
                                </div>
                            </div>
                            </form>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                         @can('ver-estantes')
                        <div class="card-body">
                            <table class="table table-striped mt-2">
                                <thead style="background-color: #6777ef;">
                                    <th style="display:none;">ID</th>
                                    <th style="color: #fff;">Nombre</th>
                                    <th style="color: #fff;">Descripción</th>
                                    <th style="color: #fff;">Acciones</th>
                                </thead>
                                <tbody>
                                    @foreach($estantes as $estante)
                                        <tr>
                                            <td style="display:none">{{$estante->id}}</td>
                                            <td>{{$estante->nombre}}</td>
                                            <td>{{$estante->descripcion}}</td>
                                            <td>
                                                @can('secciones-estante')
                                                <a class="btn btn-primary" href="{{route('estantes.show', $estante->id)}}">Secciones</a>
                                                @endcan

                                                @can('editar-estantes')
                                                <a class="btn btn-info" href="{{route('estantes.edit', $estante->id)}}">Editar</a>
                                                @endcan

                                                @can('borrar-estantes')
                                                {!!Form::open(['method' => 'DELETE', 'route'=>['estantes.destroy', $estante->id], 'style' =>'display:inline']) !!}
                                                    {!!Form::submit('Borrar', ['class' => 'btn btn-danger'])!!}
                                                {!!Form::close() !!}
                                                @endcan

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="pagination justify-content-end">
                                {!! $estantes->appends(['nombre' => $nombre, 'descripcion' => $descripcion])->links() !!}
                            </div>
                        </div>
                    @endcan   
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

