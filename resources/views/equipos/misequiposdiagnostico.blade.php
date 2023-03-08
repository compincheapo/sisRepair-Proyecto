@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">Mis Equipos</h3>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-striped mt-2">
                                <thead style="background-color: #6777ef;">
                                    <th style="display:none;">ID</th>
                                    <th style="color: #fff;">Serie</th>
                                    <th style="color: #fff;">Modelo</th>
                                    <th style="color: #fff;">Tipo Equipo</th>
                                    <th style="color: #fff;">Ultima Orden</th>
                                    <th style="color: #fff;">Estado Orden</th>
                                    <th style="color: #fff;">Servicio</th>
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

                                            @if(!empty($equipo->orden()->orderBy('created_at', 'desc')->first()->id))
                                                <td>{{$equipo->orden()->orderBy('created_at', 'desc')->first()->id}}</td>
                                            @else
                                                <td>Sin Ordenes</td>
                                            @endif
                                            @if($equipo->orden()->first() && $equipo->orden()->orderBy('created_at', 'desc')->first()->finalizado == 1)
                                                <td>Finalizado</td>
                                            @elseif($equipo->orden()->first() && $equipo->orden()->orderBy('created_at', 'desc')->first()->finalizado == 0)
                                                <td>No Finalizado</td>
                                            @elseif(empty($equipo->orden()->first()))
                                                <td style="text-align:center"> - </td>
                                            @endif


                                            @if($equipo->orden()->first() && $equipo->orden()->orderBy('created_at', 'desc')->first()->id_servicio == 1)
                                                <td>Diagnóstico</td>
                                            @elseif($equipo->orden()->first() && $equipo->orden()->orderBy('created_at', 'desc')->first()->id_servicio == 2)
                                                <td>Reparación</td>
                                            @elseif(empty($equipo->orden()->first()))
                                                <td style="text-align:center"> - </td>
                                            @endif

                                            <td>
                                            @foreach($equipo->accesorios as $accesorio)
                                            <span class="badge badge-dark mb-1">{{$accesorio->nombre}}</span>
                                            @endforeach
                                            </td>
                                            <td>{{$equipo->user->name . ' ' . $equipo->user->lastname}}</td>

                                            @if($equipo->orden()->first())
                                            <td>
                                                <div class="btn-group">
                                                <a class="btn btn-info mr-1" href="{{route('ordenesequipo', $equipo->id)}}">Ordenes</a>
                                                </div>

                                            </td>
                                            @else
                                            <td>
                                                <div class="btn-group">
                                                <a class="btn btn-secondary mr-1" href="#" onclick="event.preventDefault()">Ordenes</a>
                                                </div>

                                            </td>
                                            @endif
                                        
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="pagination justify-content-end">
                                {!! $equipos->links() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

