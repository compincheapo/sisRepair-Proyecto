@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">Roles</h3>
              <!-- Con esta directiva verificamos si el usuario tiene el permiso para esta ver la vista. -->
              @can('crear-rol')
                 <a class="btn btn-warning section-header-breadcrumb" style="float:right;" href="{{route('roles.create')}}">Nuevo</a>
              @endcan
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Filtros de Búsqueda</h4>
                            <div class="card-header-action">
                            <a data-collapse="#mycard-collapse" class="btn btn-icon btn-info" href="#"><i class="fas fa-plus"></i></a>
                            </div>
                        </div>
                        <div class="collapse" id="mycard-collapse">
                            <div class="card-body">
                            <form class="form" action="{{ route('roles.index')}}" method="GET">
                            <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="name">Nombre</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{$name}}" placeholder="Ej: Admin">
                            </div>
                            <div class="form-group col-md-12">
                                    <input type="submit" name="submitbtn" value="Filtrar" class="btn btn-light btn btn-icon icon-left"></input>
                                    <input type="submit" name="submitbtn" value="PDF" class="btn btn-warning btn btn-icon icon-left"></input>
                                    <a href="{{ url('/roles') }}" class="btn btn-info">Limpiar</a>
                                </div>
                            </div>
                            </form>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        @can('ver-rol')
                        <div class="card-body">
                            <table class="table table-striped ">
                                <thead style="background-color: #6777ef;">
                                    <th style="color: #fff; text-align:center">Rol</th>
                                    <th style="color: #fff; text-align:center">Acciones</th>
                                </thead>
                                <tbody>
                                    @foreach($roles as $role)
                                        <tr>
                                            <td style="text-align:center">{{$role->name}}</td>
                                            
                                            <td style="text-align:center">
                                            <div class="btn-group">
                                                @can('editar-rol')
                                                <a class="btn btn-info mr-1" href="{{route('roles.edit', $role->id)}}">Editar</a>
                                                @endcan

                                                @can('borrar-rol')
                                                {!!Form::open(['method' => 'DELETE', 'route'=>['roles.destroy', $role->id], 'style' =>'display:inline']) !!}
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
                                {!! $roles->appends(['name' => $name])->links() !!}
                            </div>  
                           


                        </div>
                    @endcan    
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

