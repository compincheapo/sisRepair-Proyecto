@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">Estados</h3>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                         @can('ver-estados')
                        <div class="card-body">
                            @can('crear-estados')
                            <a class="btn btn-warning" href="{{route('estados.create')}}">Nuevo</a>
                            @endcan
                            <table class="table table-striped mt-2">
                                <thead style="background-color: #6777ef;">
                                    <th style="display:none;">ID</th>
                                    <th style="color: #fff;">Nombre</th>
                                    <th style="color: #fff;">Descripción</th>
                                    <th style="color: #fff;">Acciones</th>
                                </thead>
                                <tbody>
                                    @foreach($estados as $estado)
                                        <tr>
                                            <td style="display:none">{{$estado->id}}</td>
                                            <td>{{$estado->nombre}}</td>
                                            <td>{{$estado->descripcion}}</td>
                                            <td>
                                                @can('editar-estados')
                                                <a class="btn btn-info" href="{{route('estados.edit', $estado->id)}}">Editar</a>
                                                @endcan

                                                @can('borrar-estados')
                                                {!!Form::open(['method' => 'DELETE', 'route'=>['estados.destroy', $estado->id], 'style' =>'display:inline']) !!}
                                                    {!!Form::submit('Borrar', ['class' => 'btn btn-danger'])!!}
                                                {!!Form::close() !!}
                                                @endcan

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="pagination justify-content-end">
                                {!! $estados->links() !!}
                            </div>
                        </div>
                    @endcan   
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

