@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">Estantes</h3>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                         @can('ver-estantes')
                        <div class="card-body">
                            @can('crear-estantes')
                            <a class="btn btn-warning" href="{{route('estantes.create')}}">Nuevo</a>
                            @endcan
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
                                {!! $estantes->links() !!}
                            </div>
                        </div>
                    @endcan   
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

