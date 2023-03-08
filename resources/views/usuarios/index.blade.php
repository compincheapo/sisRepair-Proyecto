@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">Usuarios</h3>
            @can('crear-usuario')
                <a class="btn btn-warning section-header-breadcrumb" style="float:right;" href="{{route('usuarios.create')}}">Nuevo</a>
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
                    <form class="form" action="{{ route('usuarios.index')}}" method="GET">
                    <div class="form-row">
                      <div class="form-group col-md-6">
                        <label for="name">Nombre</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{$name}}" placeholder="Ej: Pepe">
                      </div>
                      <div class="form-group col-md-6">
                        <label for="lastname">Apellido</label>
                        <input type="text" class="form-control" id="lastname" name="lastname" value="{{$lastname}}" placeholder="Ej: Argento">
                      </div>
                      <div class="form-group col-md-6">
                        <label for="correo">Correo</label>
                        <input type="text" class="form-control" id="email" name="email" value="{{$email}}" placeholder="Ej: pepe.argento@gmail.com">
                      </div>
                      <div class="form-group col-md-6">
                        <label for="rol">Rol</label>
                        <select id="rol" name="rol" class="form-control">
                        
                        <option value="" selected>Todos</option>

                            
                            @foreach($roles as $rol)
                                @if($rol->id == $rolusuario)
                                    <option value="{{$rol->id}}" selected>{{$rol->name}}</option>
                                @elseif($rol->id != $rolusuario)
                                    <option value="{{$rol->id}}">{{$rol->name}}</option>
                                @endif
                            @endforeach
                        </select>
                       </div>
                       <div class="form-group col-md-6">
                            
                            <input type="submit" name="submitbtn" value="Filtrar" class="btn btn-light btn btn-icon icon-left"></input>
                            <input type="submit" name="submitbtn" value="PDF" class="btn btn-warning btn btn-icon icon-left"></input>
                            <a href="{{ url('/usuarios') }}" class="btn btn-info">Limpiar</a>
                        </div>
                    </div>
                    </form>
                    </div>
                  </div>
                </div>
                    <div class="card">
                         @can('ver-usuario')
                        <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped ">
                                <thead style="background-color: #6777ef;">
                                    <th style="display:none;">ID</th>
                                    <th style="color: #fff;">Nombres</th>
                                    <th style="color: #fff;">Apellidos</th>
                                    <th style="color: #fff;">E-mail</th>
                                    <th style="color: #fff;">Rol</th>
                                    <th style="color: #fff;">Acciones</th>
                                </thead>
                                <tbody>
                                    @foreach($usuarios as $usuario)
                                        <tr>
                                            <td style="display:none">{{$usuario->id}}</td>
                                            <td>{{$usuario->name}}</td>
                                            <td>{{$usuario->lastname}}</td>
                                            <td>{{$usuario->email}}</td>
                                            <td>
                                                @if(!empty($usuario->getRoleNames()))
                                                @foreach($usuario->getRoleNames() as $rolName)
                                                <h5><span class="badge badge-dark">{{$rolName}}</span></h5>
                                                @endforeach
                                                @endif
                                            </td>
                                            <td>
                                            <div class="btn-group">
                                                @can('editar-usuario')
                                                <a class="btn btn-info mr-1" href="{{route('usuarios.edit', $usuario->id)}}">Editar</a>
                                                @endcan

                                                @can('borrar-usuario')
                                                {!!Form::open(['method' => 'DELETE', 'route'=>['usuarios.destroy', $usuario->id], 'style' =>'display:inline']) !!}
                                                    {!!Form::submit('Borrar', ['class' => 'btn btn-danger'])!!}
                                                {!!Form::close() !!}
                                                @endcan
                                            </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                                {!! $usuarios->appends(['name' => $name, 'lastname' => $lastname, 'email' => $email, 'rol' => $rolusuario])->links() !!}
                        </div>
                        </div>
                        
                    @endcan   
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

