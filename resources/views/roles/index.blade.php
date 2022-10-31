@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">Roles</h3>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        @can('ver-rol')
                        <div class="card-body">

                        <!-- Con esta directiva verificamos si el usuario tiene el permiso para esta ver la vista. -->
                            @can('crear-rol')
                            <a class="btn btn-warning" href="{{route('roles.create')}}">Nuevo</a>
                            @endcan
                            <table class="table table-striped ">
                                <thead style="background-color: #6777ef;">
                                    <th style="color: #fff;">Rol</th>
                                    <th style="color: #fff;">Acciones</th>
                                </thead>
                                <tbody>
                                    @foreach($roles as $role)
                                        <tr>
                                            <td>{{$role->name}}</td>
                                            
                                            <td>
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
                                {!! $roles->links() !!}
                            </div>  
                           


                        </div>
                    @endcan    
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

