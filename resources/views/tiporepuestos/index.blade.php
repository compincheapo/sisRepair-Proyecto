@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">Tipos Repuestos</h3>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                         @can('ver-tiporepuestos')
                        <div class="card-body">
                            @can('crear-tiporepuestos')
                            <a class="btn btn-warning" href="{{route('tiporepuestos.create')}}">Nuevo</a>
                            @endcan
                            <table class="table table-striped mt-2">
                                <thead style="background-color: #6777ef;">
                                    <th style="display:none;">ID</th>
                                    <th style="color: #fff;">Nombre</th>
                                    <th style="color: #fff;">Descripción</th>
                                    <th style="color: #fff;">Acciones</th>
                                </thead>
                                <tbody>
                                    @foreach($tiporepuestos as $tiporepuesto)
                                        <tr>
                                            <td style="display:none">{{$tiporepuesto->id}}</td>
                                            <td>{{$tiporepuesto->nombre}}</td>
                                            <td>{{$tiporepuesto->descripcion}}</td>
                                            <td>
                                                @can('editar-tiporepuestos')
                                                <a class="btn btn-info" href="{{route('tiporepuestos.edit', $tiporepuesto->id)}}">Editar</a>
                                                @endcan

                                                @can('borrar-tiporepuestos')
                                                {!!Form::open(['method' => 'DELETE', 'route'=>['tiporepuestos.destroy', $tiporepuesto->id], 'style' =>'display:inline']) !!}
                                                    {!!Form::submit('Borrar', ['class' => 'btn btn-danger'])!!}
                                                {!!Form::close() !!}
                                                @endcan

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="pagination justify-content-end">
                                {!! $tiporepuestos->links() !!}
                            </div>
                        </div>
                    @endcan   
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

