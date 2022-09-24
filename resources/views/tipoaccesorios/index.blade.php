@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">Tipos Accesorios</h3>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                         @can('ver-tiposaccesorios')
                        <div class="card-body">
                            @can('crear-tiposaccesorios')
                            <a class="btn btn-warning" href="{{route('tipoaccesorios.create')}}">Nuevo</a>
                            @endcan
                            <table class="table table-striped mt-2">
                                <thead style="background-color: #6777ef;">
                                    <th style="display:none;">ID</th>
                                    <th style="color: #fff;">Nombre</th>
                                    <th style="color: #fff;">Descripción</th>
                                    <th style="color: #fff;">Acciones</th>
                                </thead>
                                <tbody>
                                    @foreach($tipoaccesorios as $tipoaccesorio)
                                        <tr>
                                            <td style="display:none">{{$tipoaccesorio->id}}</td>
                                            <td>{{$tipoaccesorio->nombre}}</td>
                                            <td>{{$tipoaccesorio->descripcion}}</td>
                                            <td>
                                                @can('editar-tiposaccesorios')
                                                <a class="btn btn-info" href="{{route('tipoaccesorios.edit', $tipoaccesorio->id)}}">Editar</a>
                                                @endcan

                                                @can('borrar-tiposaccesorios')
                                                {!!Form::open(['method' => 'DELETE', 'route'=>['tipoaccesorios.destroy', $tipoaccesorio->id], 'style' =>'display:inline']) !!}
                                                    {!!Form::submit('Borrar', ['class' => 'btn btn-danger'])!!}
                                                {!!Form::close() !!}
                                                @endcan

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="pagination justify-content-end">
                                {!! $tipoaccesorios->links() !!}
                            </div>
                        </div>
                    @endcan   
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

