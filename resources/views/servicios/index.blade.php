@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">Servicios</h3>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                         @can('ver-servicios')
                        <div class="card-body">
                            @can('crear-servicios')
                            <a class="btn btn-warning" href="{{route('servicios.create')}}">Nuevo</a>
                            @endcan
                            <table class="table table-striped mt-2">
                                <thead style="background-color: #6777ef;">
                                    <th style="display:none;">ID</th>
                                    <th style="color: #fff;">Nombre</th>
                                    <th style="color: #fff;">Descripción</th>
                                    <th style="color: #fff;">Acciones</th>
                                </thead>
                                <tbody>
                                    @foreach($servicios as $servicio)
                                        <tr>
                                            <td style="display:none">{{$servicio->id}}</td>
                                            <td>{{$servicio->nombre}}</td>
                                            <td>{{$servicio->descripcion}}</td>
                                            <td>
                                                @can('editar-servicios')
                                                <div class="btn-group">
                                                <a class="btn btn-info mr-1" href="{{route('servicios.edit', $servicio->id)}}">Editar</a>
                                                @endcan
                                                @can('borrar-servicios')
                                                {!!Form::open(['method' => 'DELETE', 'route'=>['servicios.destroy', $servicio->id], 'style' =>'display:inline']) !!}
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
                                {!! $servicios->links() !!}
                            </div>
                        </div>
                    @endcan   
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

