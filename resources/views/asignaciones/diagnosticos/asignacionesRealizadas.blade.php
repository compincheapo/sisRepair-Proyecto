@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/v/dt/dt-1.10.16/sl-1.2.5/datatables.min.css">
@endsection

@section('content')

<section class="section">
        <div class="section-header">
            <h3 class="page__heading">Asignaciones Realizadas</h3>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                    @if($errors->any())
                            <div class="alert alert-dark alert-dismissible fade-snow" role="alert">
                                <strong>Error en la elección.</strong>
                                    @foreach($errors->all() as $error)
                                        <span class="badge badge-danger">{{$error}}</span>
                                    @endforeach
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                            </div>
                    @endif
                    <div class="container">
                    <div class="table-responsive table-bordered">
                        <table id="example" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th width="10px">ID</th>
                                    <th>Nombre</th>
                                    <th>Marca</th>
                                    <th>Cliente</th>
                                    <th>Técnico</th>
                                </tr>
                            </thead>
                        </table>
                    </div>     
                    </div>  
                    </div>
                    </div>
                            
            </div>
        </div>
</section>

@endsection
@section('scripts')
    
        <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
        <script src="https://cdn.datatables.net/v/dt/dt-1.10.16/sl-1.2.5/datatables.min.js"></script>

        <script>

            
            $(document).ready(function() {
                
                var tablita = $('#example').DataTable( {
                    "serverSide": true,
                    "ajax":  "{{route('asignacionesrealizadas')}}",
                    "columns": [
                        {data: 'id'},
                        {data: 'serie'},
                        {data: 'marca.nombre'},
                        {data: 'user.name'},
                        {data: 'name'},

                
                    ],
                } );

      

        
            });
        </script>
@endsection