@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">Equipos</h3>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                         @can('ver-equipos')
                        <div class="card-body">
                            @can('crear-equipos')
                            <a class="btn btn-warning" href="{{route('equipos.create')}}">Nuevo</a>
                            @endcan
                            <table class="table table-striped mt-2">
                                <thead style="background-color: #6777ef;">
                                    <th style="display:none;">ID</th>
                                    <th style="color: #fff;">Serie</th>
                                    <th style="color: #fff;">Modelo</th>
                                    <th style="color: #fff;">Tipo Equipo</th>
                                    <th style="color: #fff;">Estante</th>
                                    <th style="color: #fff;">Sección Estante</th>
                                    <th style="color: #fff;">Accesorios</th>
                                    <th style="color: #fff;">Cliente</th>
                                    <th style="color: #fff;">Acciones</th>
                                </thead>
                                <tbody>
                                    @foreach($equipos as $equipo)
                                        <tr>
                                            <td style="display:none">{{$equipo->id}}</td>
                                            <td>{{$equipo->serie}}</td>
                                            <td>{{$equipo->modelo}}</td>
                                            <td>{{$equipo->tipoequipo->nombre}}</td>
                                            <td>{{$equipo->seccionestante->estante->nombre}}</td>
                                            <td>{{$equipo->seccionestante->nombre}}</td>
                                            <td>
                                            @foreach($equipo->accesorios as $accesorio)
                                            <span class="badge badge-dark mb-1">{{$accesorio->nombre}}</span>
                                            @endforeach
                                            </td>
                                            <td>{{$equipo->user->name . ' ' . $equipo->user->lastname}}</td>
                                            <td>
                                                @can('editar-equipos')
                                                <div class="btn-group">
                                                <a class="btn btn-info mr-1" href="{{route('equipos.edit', $equipo->id)}}">Editar</a>
                                                @endcan
                                                @can('borrar-equipos')
                                                {!!Form::open(['method' => 'DELETE', 'route'=>['equipos.destroy', $equipo->id], 'style' =>'display:inline']) !!}
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
                                {!! $equipos->links() !!}
                            </div>
                        </div>
                    @endcan   
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

