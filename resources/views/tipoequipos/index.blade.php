@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">Tipos Equipos</h3>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                         @can('ver-tiposequipos')
                        <div class="card-body">
                            @can('crear-tiposequipos')
                            <a class="btn btn-warning" href="{{route('tipoequipos.create')}}">Nuevo</a>
                            @endcan
                            <table class="table table-striped mt-2">
                                <thead style="background-color: #6777ef;">
                                    <th style="display:none;">ID</th>
                                    <th style="color: #fff;">Nombre</th>
                                    <th style="color: #fff;">Descripción</th>
                                    <th style="color: #fff;">Acciones</th>
                                </thead>
                                <tbody>
                                    @foreach($tipoequipos as $tipoequipo)
                                        <tr>
                                            <td style="display:none">{{$tipoequipo->id}}</td>
                                            <td>{{$tipoequipo->nombre}}</td>
                                            <td>{{$tipoequipo->descripcion}}</td>
                                            <td>
                                                @can('editar-tiposequipos')
                                                <a class="btn btn-info" href="{{route('tipoequipos.edit', $tipoequipo->id)}}">Editar</a>
                                                @endcan

                                                @can('borrar-tiposequipos')
                                                {!!Form::open(['method' => 'DELETE', 'route'=>['tipoequipos.destroy', $tipoequipo->id], 'style' =>'display:inline']) !!}
                                                    {!!Form::submit('Borrar', ['class' => 'btn btn-danger'])!!}
                                                {!!Form::close() !!}
                                                @endcan

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="pagination justify-content-end">
                                {!! $tipoequipos->links() !!}
                            </div>
                        </div>
                    @endcan   
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

