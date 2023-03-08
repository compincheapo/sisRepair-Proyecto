@extends('layouts.app')


@section('content')
    @php
        require base_path('/vendor/autoload.php');
        // Agrega credenciales
        MercadoPago\SDK::setAccessToken(config('services.mercadopago.token'));

        // Crea un objeto de preferencia
        $preference = new MercadoPago\Preference();

        // Crea un ítem en la preferencia
        $item = new MercadoPago\Item();

        if($orden->id_servicio == 1){
            $item->title = 'Orden de Servicio ' . $orden->id . ': Diagnostico.';

        } 
        if($orden->id_servicio == 2){
            $item->title = 'Orden de Servicio ' . $orden->id . ': Reparación.';

        } 

        $preference->back_urls = array(
            "success" =>  route('getResultadoPagoOrdenSatisfactoria', $orden->id),
            "failure" =>  route('getResultadoPagoOrdenFallo', $orden->id) ,
        );

        $preference->auto_return = "approved";
        $preference->binary_mode = true;

        $item->quantity = 1;

        if($presupuesto != null){
            $item->unit_price = $presupuesto->presupuesto;
        } else {
            $item->unit_price = $precio->precio;
        }
        $preference->items = array($item);
        $preference->save();
    @endphp
    
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">Pago Orden de Servicio {{$orden->id}}</h3>
            <a class="btn btn-info section-header-breadcrumb" style="float:right;" href="{{route('ordenesequipo', $equipo->id)}}">Volver</a>
        </div>
        @if($errors->any())
        <div class="alert alert-danger">
                <ul>
                    <li>{{$errors->first()}}</li>
                </ul>
        </div>
        @endif
        
        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                                <h6>Detalles de la Orden</h6>
                                <div class="row">
                                    <div class="col-xs-3 col-sm-3 col-md-3">
                                        <div class="form-group">
                                            <label for="serie">Servicio</label>
                                            @if($orden->id_servicio == 1)
                                                <input type="text" class="form-control" value="Diagnóstico" readonly>

                                            @elseif($orden->id_servicio == 2)
                                                <input type="text" class="form-control" value="Reparación" readonly>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-xs-3 col-sm-3 col-md-3">
                                        <div class="form-group">
                                            <label for="serie">Estado</label>
                                            <input type="text" class="form-control" value="Finalizado" readonly>
                                        </div>
                                    </div>
                                    <div class="col-xs-3 col-sm-3 col-md-3">
                                        <div class="form-group">
                                            <label for="serie">Fecha Finalización</label>
                                            <input type="text" class="form-control" value="{{$orden->fechafin}}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-xs-3 col-sm-3 col-md-3">
                                        <div class="form-group">
                                            @if($presupuesto)
                                                <label for="serie">Presupuesto</label>
                                                <input type="text" class="form-control" value="{{$presupuesto->presupuesto}}" readonly>
                                            @else
                                                <label for="serie">Precio</label>
                                                <input type="text" class="form-control" value="{{$precio->precio}}" readonly>
                                            @endif
                                            
                                        </div>
                                    </div>
                                </div>    
                                <h6>Detalles del Equipo</h6> 
                                <div class="row">  
                                    
                                    <div class="col-xs-3 col-sm-3 col-md-3">
                                        <div class="form-group">
                                            <label for="serie">Tipo Equipo</label>
                                            <input type="text" class="form-control" value="{{$equipo->tipoequipo->nombre}}" readonly>
                                        </div>
                                    </div>

                                    <div class="col-xs-3 col-sm-3 col-md-3">
                                        <div class="form-group">
                                            <label for="serie">Serie</label>
                                            <input type="text" class="form-control" value="{{$equipo->serie}}" readonly>
                                        </div>
                                    </div>
                                
                                    <div class="col-xs-3 col-sm-3 col-md-3">
                                        <div class="form-group">
                                            <label for="serie">Modelo</label>
                                            <input type="text" class="form-control" value="{{$equipo->modelo}}" readonly>
                                        </div>
                                    </div>
                               
                                    <div class="col-xs-3 col-sm-3 col-md-3">
                                        <div class="form-group">
                                            <label for="serie">Marca</label>
                                            <input type="text" class="form-control" value="{{$equipo->marca->nombre}}" readonly>
                                        </div>
                                    </div>
                                </div>
                                
                    </div>
                </div>
            </div>
        </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                        @if($presupuesto)  
                            <span style="font-weight:bold; font-size:1rem">Total a pagar: {{$presupuesto->presupuesto}}</span>
                        @else
                            <span style="font-weight:bold; font-size:1rem">Total a pagar: {{$precio->precio}}</span>
                        @endif
                        <!-- <a class="btn btn-success section-header-breadcrumb"  href="{{route('ordenesequipo', $equipo->id)}}">Realizar Pago  <i class="far fa-credit-card"></i></a> -->
                        <div class="cho-container" style="float:right;"></div>   
                          
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://sdk.mercadopago.com/js/v2"></script>
    
    <script>
    const mp = new MercadoPago("{{config('services.mercadopago.key')}}", {
        locale: 'es-AR'
    });

    mp.checkout({
        preference: {
        id: '{{$preference->id}}'
        },
        render: {
        container: '.cho-container',
        label: 'Pagar',
        }
    });
    </script>

@endsection

