@extends('layouts.app')

@section('css')
<style>
    .main {
        padding-top: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .input-plus {
        display: flex;
    }

    .inputs-set {
        border: none;
    }
    .email-input__w {
        display: flex;
        align-items: center;
        margin-bottom: 4px;
    }

    .input-field {
        border: none;
        border: 1px solid rgb(209, 209, 209);
        padding: 8px;
        margin-right: 4px;
        display: block;
    }

    .btn-submit,
    .btn-add-input,
    .btn-del-input {
    border: none;
    padding: 8px 12px;
    }

    .btn-submit {
        background-color: rgb(152, 247, 199);
    }

    .btn-add-input {
        background-color: rgb(127, 187, 255);
        margin-right: 4px;
    }
    .btn-del-input {
        background-color: rgb(255, 127, 148);
    }
</style>
@endsection

@section('content')    
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading">Información General</h3>
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
                                <h6>Detalles del local</h6>
                                {!! Form::open(array('route'=> 'actualizarInformacionGeneral', 'method'=> 'POST')) !!}
                                <div class="row">
                                    <div class="col-xs-4 col-sm-4 col-md-4">
                                        <div class="form-group">
                                            <label for="nombre">Nombre del local</label>
                                            <input type="text" class="form-control" name="nombre" value="{{!empty($informacionGeneral) ? $informacionGeneral->nombre : ''}}">
                                        </div>
                                    </div>
                                    <div class="col-xs-4 col-sm-4 col-md-4">
                                        <div class="form-group">
                                            <label for="provincia">Provincia</label>
                                            <input type="text" class="form-control" name="provincia" value="{{!empty($informacionGeneral) ? $informacionGeneral->provincia : ''}}">
                                        </div>
                                    </div>
                                    <div class="col-xs-4 col-sm-4 col-md-4">
                                        <div class="form-group">
                                            <label for="localidad">Localidad</label>
                                            <input type="text" class="form-control" name="localidad" value="{{!empty($informacionGeneral) ? $informacionGeneral->localidad : ''}}">
                                        </div>
                                    </div>
                                    <div class="col-xs-4 col-sm-4 col-md-4">
                                        <div class="form-group">
                                            <label for="direccion">Dirección</label>
                                            <input type="text" class="form-control" name="direccion" value="{{!empty($informacionGeneral) ? $informacionGeneral->direccion : ''}}">
                                        </div>
                                    </div>
                                    <div class="col-xs-4 col-sm-4 col-md-4">
                                        <div class="form-group">
                                            <label for="cuit">CUIT</label>
                                            <input type="text" class="form-control" name="cuit" value="{{!empty($informacionGeneral) ? $informacionGeneral->cuit : ''}}">
                                        </div>
                                    </div>
                                    <div class="col-xs-4 col-sm-4 col-md-4">
                                        <div class="form-group">
                                            <label for="celular">Celular</label>
                                            <input type="text" class="form-control" name="celular" value="{{!empty($informacionGeneral) ? $informacionGeneral->celular : ''}}">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4 col-xs-4 col-sm-4"> 
                                        <label for="diadesde">Día desde</label>
                                        <select name="diadesde" class="form-control">
                                                <option value="">Seleccione...</option>  
                                                @foreach($diasSemana as $diaSemana)
                                                    @if(!empty($informacionGeneral) && $informacionGeneral->diadesde == $diaSemana)
                                                     <option value="{{$diaSemana}}" selected>{{$diaSemana}}</option>                    
                                                    @else
                                                    <option value="{{$diaSemana}}">{{$diaSemana}}</option>  
                                                    @endif
                                                @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4 col-xs-4 col-sm-4"> 
                                        <label for="diahasta">Día hasta</label>
                                        <select name="diahasta" class="form-control">
                                                <option value="">Seleccione...</option>  
                                                @foreach($diasSemana as $diaSemana)
                                                     @if(!empty($informacionGeneral) && $informacionGeneral->diahasta == $diaSemana)
                                                     <option value="{{$diaSemana}}" selected>{{$diaSemana}}</option>                    
                                                    @else
                                                    <option value="{{$diaSemana}}">{{$diaSemana}}</option>  
                                                    @endif              
                                                @endforeach
                                        </select>
                                    </div>
                                   
                                    <div class="col-xs-4 col-sm-4 col-md-4">
                                        <div class="form-group">
                                            <label for="horadesde">Hora desde</label>
                                            <input type="time" class="form-control" name="horadesde" value="{{!empty($informacionGeneral) ? $informacionGeneral->horadesde : ''}}">
                                        </div>
                                    </div>
                                    <div class="col-xs-4 col-sm-4 col-md-4">
                                        <div class="form-group">
                                            <label for="horahasta">Hora hasta</label>
                                            <input type="time" class="form-control" name="horahasta" value="{{!empty($informacionGeneral) ? $informacionGeneral->horahasta : ''}}">
                                        </div>
                                    </div>
                    
                                </div>    
                                <h6>Detalles Notificaciones</h6> 
                                <div class="row">  
                                    
                                    <div class="col-xs-3 col-sm-3 col-md-3">
                                        <div class="form-group">
                                            <label for="cantidadcliente">Cantidad veces a notificar Cliente</label>
                                            <input type="text" class="form-control" name="cantidadcliente" value="{{!empty($informacionGeneral) ? $informacionGeneral->cant_notif_cliente : ''}}">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-3"> 
                                        <label for="frecuenciacliente">Frecuencia</label>
                                        <select name="frecuenciacliente" class="form-control">
                                                <option value="">Seleccione...</option>  
                                                @if(!empty($informacionGeneral) && $informacionGeneral->frecuencia_notif_cliente == 'semanal')  
                                                <option value="semanal" selected>Semanal</option>
                                                <option value="mensual">Mensual</option>
                                                <option value="anual">Anual</option>
                                                @elseif(!empty($informacionGeneral) && $informacionGeneral->frecuencia_notif_cliente == 'mensual')
                                                <option value="semanal">Semanal</option>                                                        
                                                <option value="mensual" selected>Mensual</option>                                                        
                                                <option value="anual">Anual</option>                                                        
                                                @elseif(!empty($informacionGeneral) && $informacionGeneral->frecuencia_notif_cliente == 'anual')
                                                <option value="semanal">Semanal</option>  
                                                <option value="mensual">Mensual</option>
                                                <option value="anual" selected>Anual</option>                    
                                                @else
                                                <option value="semanal">Semanal</option>  
                                                <option value="mensual">Mensual</option>
                                                <option value="anual">Anual</option>      
                                                @endif
                                        </select>
                                    </div>
                                    <div class="col-xs-3 col-sm-3 col-md-3">
                                        <div class="form-group">
                                            <label for="cantidadtercero">Cantidad veces a notificar Tercero</label>
                                            <input type="text" class="form-control" name="cantidadtercero" value="{{!empty($informacionGeneral) ? $informacionGeneral->cant_notif_tercero : ''}}">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-3"> 
                                        <label for="frecuenciatercero">Frecuencia</label>
                                        <select name="frecuenciatercero" class="form-control">
                                                <option value="">Seleccione...</option>    
                                                @if(!empty($informacionGeneral) && $informacionGeneral->frecuencia_notif_tercero == 'semanal')  
                                                <option value="semanal" selected>Semanal</option>
                                                <option value="mensual">Mensual</option>
                                                <option value="anual">Anual</option>
                                                @elseif(!empty($informacionGeneral) && $informacionGeneral->frecuencia_notif_tercero == 'mensual')
                                                <option value="semanal">Semanal</option>                                                        
                                                <option value="mensual" selected>Mensual</option>                                                        
                                                <option value="anual">Anual</option>                                                        
                                                @elseif(!empty($informacionGeneral) && $informacionGeneral->frecuencia_notif_tercero == 'anual')
                                                <option value="semanal">Semanal</option>  
                                                <option value="mensual">Mensual</option>
                                                <option value="anual" selected>Anual</option>             
                                                @else
                                                <option value="semanal">Semanal</option>  
                                                <option value="mensual">Mensual</option>
                                                <option value="anual">Anual</option>             
                                                @endif                                                                 
                                        </select>
                                    </div>
                                
                                </div>
                                <h6>Precios Servicio</h6> 
                                <div class="row">  
                                        <div class="col-xs-3 col-sm-3 col-md-3">
                                            <div class="form-group">
                                                <label for="preciodiagnostico">Precio Diagnóstico</label>
                                                <input type="text" class="form-control" name="preciodiagnostico" value="{{!empty($precioDiagnostico) ? $precioDiagnostico->precio : ''}}">
                                            </div>
                                        </div>
                                </div>
                                <h6>Términos y Condiciones</h6> 
                                <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <fieldset class="inputs-set" id="email-list" class="input-field">
                                        @if(empty($terminos))
                                            <div class="email-input__w">
                                                <input class="input-field form-control" type="text" name="terminos[]"/>
                                                <button class="btn-add-input" onclick="addEmailField()" type="button">
                                                +
                                                </button>
                                            </div>                                            
                                        @elseif(!empty($terminos) && $terminos)
                                            <div class="email-input__w">
                                                <input class="input-field form-control" value="{{ !empty($terminos->first()) ? $terminos->first()->termino : ''}}" type="text" name="terminos[]"/>
                                                <button class="btn-add-input" onclick="addEmailField()" type="button">
                                                +
                                                </button>
                                            </div>
                                             @foreach($terminos->skip(1) as $termino) 
                                             <div class="email-input__w">
                                                <input class="input-field form-control"value="{{$termino->termino}}" type="text" name="terminos[]"/>
                                                <button class="btn-del-input"  onClick="removeElement(this)" type="button">
                                                -
                                                </button>
                                            </div>
                                             @endforeach
                                        @endif
                                    </fieldset>
                                </div>
                                    
                                </div>
                                <div class="row mt-4">  
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                       <button type="submit" class="btn btn-primary">Guardar</button>
                                </div>
                                </div>

                                {!! Form::close() !!}  
                    </div>
                </div>
            </div>
        </div>
    
    </section>
    <script>

 function removeElement(el){
    const field = el.parentElement;
    field.remove();
 }
        const myForm = document.getElementById("email-list");

function addEmailField() {
  // Create elements
  const nef_wrapper = document.createElement("div");
  const nef = document.createElement("input");
  const btnDel = document.createElement("button");

  // Add Class to main wrapper
  nef_wrapper.classList.add("email-input__w");

  // set button DEL
  btnDel.type = "button";
  btnDel.classList.add("btn-del-input");
  btnDel.innerText = "-";

  // set Input field
  nef.type = "text";
  nef.name = "terminos[]";
  nef.classList.add("input-field");
  nef.classList.add("form-control");

  //append elements to main wrapper
  nef_wrapper.appendChild(nef);
  nef_wrapper.appendChild(btnDel);

  // append element to DOM
  myForm.appendChild(nef_wrapper);
  btnDel.addEventListener("click", removeEmailField);
}

//remove element from DOM
function removeEmailField(el) {
  const field = el.target.parentElement;
  field.remove();
}
    </script>

@endsection
