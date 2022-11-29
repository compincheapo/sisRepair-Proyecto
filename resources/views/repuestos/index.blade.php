@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">Repuestos</h3>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                         @can('ver-repuestos')
                        <div class="card-body">
                            @can('crear-repuestos')
                            <a class="btn btn-warning" href="{{route('repuestos.create')}}">Nuevo</a>
                            @endcan
                            <table class="table table-striped mt-2">
                                <thead style="background-color: #6777ef;">
                                    <th style="display:none;">ID</th>
                                    <th style="color: #fff;">Tipo</th>
                                    <th style="color: #fff;">Marca</th>
                                    <th style="color: #fff;">Estante</th>
                                    <th style="color: #fff;">Sección Estante</th>
                                    <th style="color: #fff;">Modelo</th>
                                    <th style="color: #fff;">Cantidad</th>
                                    <th style="color: #fff;">Precio Unitario</th>
                                    <th style="color: #fff;">Acciones</th>
                                </thead>
                                <tbody>
                                    @foreach($repuestos as $repuesto)
                                        <tr>
                                            <td style="display:none">{{$repuesto->id}}</td>
                                            <td>{{$repuesto->tiporepuesto->nombre}}</td>
                                            <td>{{$repuesto->marca->nombre}}</td>
                                            <td>{{$repuesto->seccionestante->estante->nombre}}</td>
                                            <td>{{$repuesto->seccionestante->nombre}}</td>
                                            <td>{{$repuesto->modelo}}</td>
                                            <td>{{$repuesto->cantidad}}</td>
                                            <td>{{$repuesto->precio}}</td>
                                            <td>
                                                @can('editar-repuestos')
                                                <div class="btn-group">
                                                <a class="btn btn-info mr-1" href="{{route('repuestos.edit', $repuesto->id)}}">Editar</a>
                                                @endcan
                                                @can('borrar-repuestos')
                                                {!!Form::open(['method' => 'DELETE', 'route'=>['repuestos.destroy', $repuesto->id], 'style' =>'display:inline']) !!}
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
                                {!! $repuestos->links() !!}
                            </div>
                        </div>
                    @endcan   
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

