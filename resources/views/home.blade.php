@extends('layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h4 class="page__heading">Bienvenido Gerente</h4>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                        <h5>Usuarios</h5>
                        <div class="row">
                            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-primary">
                                <i class="far fa-user"></i>
                                </div>
                                <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Clientes</h4>
                                </div>
                                <div class="card-body">
                                     {{$clientes}}
                                </div>
                                </div>
                            </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-danger">
                                <i class="fas fa-user-cog"></i>
                                </div>
                                <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Técnicos</h4>
                                </div>
                                <div class="card-body">
                                       {{$tecnicos}}
                                </div>
                                </div>
                            </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-warning">
                                <i class="fas fa-user-friends"></i>
                                </div>
                                <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Terceros</h4>
                                </div>
                                <div class="card-body">
                                     {{$terceros}}
                                </div>
                                </div>
                            </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-success">
                                <i class="fas fa-user-tie"></i>
                                </div>
                                <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Vendedores</h4>
                                </div>
                                <div class="card-body">
                                      {{$vendedores}}
                                </div>
                                </div>
                            </div>
                            </div>                  
                        </div>
                        <h5>Ordenes</h5>
                        <div class="row">
                            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-danger">
                                <i class="fas fa-box"></i>
                                </div>
                                <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Ordenes</h4>
                                </div>
                                <div class="card-body">
                                     {{$ordenes}}
                                </div>
                                </div>
                            </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-warning">
                                <i class="fas fa-box"></i>
                                <i class="far fa-clock"></i>
                                </div>
                                <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Ordenes Pendientes</h4>
                                </div>
                                <div class="card-body">
                                       {{$ordenesPendientes}}
                                </div>
                                </div>
                            </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-success">
                                <i class="fas fa-box"></i>
                                <i class="far fa-check-circle"></i>
                                </div>
                                <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Ordenes Finalizadas</h4>
                                </div>
                                <div class="card-body">
                                     {{$ordenesFinalizadas}}
                                </div>
                                </div>
                            </div>
                            </div>
                                        
                        </div>
                        <h5>Inventario</h5>
                        <div class="row">
                            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-danger">
                                <i class="fas fa-layer-group"></i>
                                </div>
                                <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Estantes</h4>
                                </div>
                                <div class="card-body">
                                     {{$estantes}}
                                </div>
                                </div>
                            </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-success">
                                <i class="fas fa-laptop"></i>
                                <i class="fas fa-screwdriver"></i>
                                </div>
                                <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Equipos Repuesto</h4>
                                </div>
                                <div class="card-body">
                                     {{$equiposRepuesto}}
                                </div>
                                </div>
                            </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-primary">
                                <i class="fas fa-screwdriver"></i>
                                </div>
                                <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Repuestos</h4>
                                </div>
                                <div class="card-body">
                                     {{$repuestos}}
                                </div>
                                </div>
                            </div>
                            </div>
                                        
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

