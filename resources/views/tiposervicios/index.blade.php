@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">Tipos Servicios</h3>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                         @can('ver-tiposervicios')
                        <div class="card-body">
                            @can('crear-tiposervicios')
                            <a class="btn btn-warning" href="{{route('tiposervicios.create')}}">Nuevo</a>
                            @endcan
                            <table class="table table-striped mt-2">
                                <thead style="background-color: #6777ef;">
                                    <th style="display:none;">ID</th>
                                    <th style="color: #fff;">Nombre</th>
                                    <th style="color: #fff;">Descripción</th>
                                    <th style="color: #fff;">Servicio</th>
                                    <th style="color: #fff;">Acciones</th>
                                </thead>
                                <tbody>
                                    @foreach($tiposervicios as $tiposervicio)
                                        <tr>
                                            <td style="display:none">{{$tiposervicio->id}}</td>
                                            <td>{{$tiposervicio->nombre}}</td>
                                            <td>{{$tiposervicio->descripcion}}</td>
                                            <td>
                                                @if(!empty($tiposervicio->servicio()))
                                                <h5><span class="badge badge-dark">{{$tiposervicio->servicio->nombre}}</span></h5>
                                                @endif
                                            </td>
                                            <td>
                                                @can('editar-tiposervicios')
                                                <div class="btn-group">
                                                <a class="btn btn-info mr-1" href="{{route('tiposervicios.edit', $tiposervicio->id)}}">Editar</a>
                                                @endcan

                                                @can('borrar-tiposervicios')
                                                {!!Form::open(['method' => 'DELETE', 'route'=>['tiposervicios.destroy', $tiposervicio->id], 'style' =>'display:inline']) !!}
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
                                {!! $tiposervicios->links() !!}
                            </div>
                        </div>
                    @endcan   
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

