@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">Secciones del Estante: <b>{{$estante->nombre}}<b></h3>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                         @can('ver-seccionesestante')
                        <div class="card-body">
                            @can('crear-seccionesestante')
                            <a class="btn btn-warning" href="{{route('sectioncreate', $estante->id)}}">Nuevo</a>
                            @endcan
                            <table class="table table-striped mt-2">
                                <thead style="background-color: #6777ef;">
                                    <th style="display:none;">ID</th>
                                    <th style="color: #fff;">Nombre</th>
                                    <th style="color: #fff;">Descripción</th>
                                    <th style="color: #fff;">Acciones</th>
                                </thead>
                                <tbody>
                                    @foreach($seccionesestante as $seccionestante)
                                        <tr>
                                            <td style="display:none">{{$seccionestante->id}}</td>
                                            <td>{{$seccionestante->nombre}}</td>
                                            <td>{{$seccionestante->descripcion}}</td>
                                            <td>
                                                @can('editar-seccionesestante')
                                                <a class="btn btn-info" href="{{route('seccionesestante.edit', $seccionestante->id)}}">Editar</a>
                                                @endcan

                                                @can('borrar-seccionesestante')
                                                {!!Form::open(['method' => 'DELETE', 'route'=>['seccionesestante.destroy', $seccionestante->id], 'style' =>'display:inline']) !!}
                                                    {!!Form::submit('Borrar', ['class' => 'btn btn-danger'])!!}
                                                {!!Form::close() !!}
                                                @endcan

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="pagination justify-content-end">
                                {!! $seccionesestante->links() !!}
                            </div>
                        </div>
                    @endcan   
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

