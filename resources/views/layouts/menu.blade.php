<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<style>


.main-sidebar .sidebar-brand{
    background-color: #6777ef;
}

 .primario{
    background-color: #6777ef !important;
    color: #FFF !important;
    padding: 2px !important;
 }

 .primario:hover{
    background-color:#4056fc !important;
 }

 .main-sidebar .sidebar-menu li ul.dropdown-menu li.active > a {
    color: #ffa426 !important;
 }

 .main-sidebar {
    background-color: #6777ef !important;
 }

 #ascrail2000{
    left:250px !important;
 }

 .main-sidebar {
    width:250px;
}

body.sidebar-mini .main-sidebar:after {
    background-color: #6777ef;
}
/* 
 .navbar-bg{
    background-color: #f4f6f9;
 } */

 .main-sidebar .sidebar-menu li ul.dropdown-menu li a {
    padding-left: 30px !important;
 }

 .dropdown-menu .dropdown-title {
    background-color: #6777ef;
    color: #FFF !important;
}

.main-sidebar .sidebar-menu li a {
    padding: 0px;
    color:white;
}

</style>

@if(Auth::user()->getRoleNames()->first() == "Vendedor" || Auth::user()->getRoleNames()->first() == "Admin")
<li id="drop-1" class="dropdown pepe">
        <a href="#" class="nav-link has-dropdown primario"><i class="fas fa-cogs"></i><span>Módulo Configuración</span></a>
        <ul id="dropmenu-1" class="dropdown-menu">
                @if(Auth::user()->getRoleNames()->first() == "Admin")
                <hr style="margin-top:0.5px; margin-bottom:0.5px;">
                <li class="{{ Request::is('informaciongeneral') ? 'active' : '' }} nav-item">
                    <a href="{{ url('/informaciongeneral') }}" class="primario">Información General</a>
                </li>
                @endif
                @if(Auth::user()->getRoleNames()->first() == "Vendedor" || Auth::user()->getRoleNames()->first() == "Admin")
                <hr style="margin-top:0.5px; margin-bottom:0.5px">
                <li class="{{ Request::is('usuarios') ? 'active' : '' }} nav-item">
                    <a href="{{ url('/usuarios') }}" class="primario">Usuarios</a>
                </li>
                @endif
                @if(Auth::user()->getRoleNames()->first() == "Admin")
                <hr style="margin-top:0.5px; margin-bottom:0.5px">
                <li class="{{ Request::is('roles') ? 'active' : '' }}">
                    <a href="{{ url('/roles') }}" class="primario">Roles</a>
                </li>
                @endif
                @if(Auth::user()->getRoleNames()->first() == "Vendedor" || Auth::user()->getRoleNames()->first() == "Admin")
                <hr style="margin-top:0.5px; margin-bottom:0.5px">
                <li class="{{ Request::is('marcas') ? 'active' : '' }}">
                    <a href="{{ url('/marcas') }}" class="primario">Marcas</a>
                </li>
                @endif
                <!-- @can('ver-estados')
                <hr style="margin-top:1px; margin-bottom:1px">
                <li class="{{ Request::is('estados') ? 'active' : '' }}">
                    <a href="{{ url('/estados') }}" class="primario">Estados</a>
                </li>
                @endcan -->
                @if(Auth::user()->getRoleNames()->first() == "Vendedor" || Auth::user()->getRoleNames()->first() == "Admin")
                <hr style="margin-top:0.5px; margin-bottom:0.5px">
                <li class="{{ Request::is('tipoequipos') ? 'active' : '' }}">
                    <a href="{{ url('/tipoequipos') }}" class="primario">Tipos Equipos</a>
                </li>
                @endif
                @if(Auth::user()->getRoleNames()->first() == "Vendedor" || Auth::user()->getRoleNames()->first() == "Admin")
                <hr style="margin-top:0.5px; margin-bottom:0.5px">
                <li class="{{ Request::is('tipoaccesorios') ? 'active' : '' }}">
                    <a href="{{ url('/tipoaccesorios') }}" class="primario">Tipos Accesorios</a>
                </li>
                @endif
                <!-- @can('ver-servicios')
                <hr style="margin-top:1px; margin-bottom:1px">
                <li class="{{ Request::is('servicios') ? 'active' : '' }}">
                    <a href="{{ url('/servicios') }}" class="primario">Servicios</a>
                </li>
                @endcan -->
                @if(Auth::user()->getRoleNames()->first() == "Vendedor" || Auth::user()->getRoleNames()->first() == "Admin")
                <hr style="margin-top:0.5px; margin-bottom:0.5px">
                <li class="{{ Request::is('tiporepuestos') ? 'active' : '' }}">
                    <a href="{{ url('/tiporepuestos') }}" class="primario">Tipos Repuestos</a>
                </li>
                <hr style="margin-top:1px; margin-bottom:1px">
                @endif
        </ul>
</li>
@endif
@if(Auth::user()->getRoleNames()->first() == "Vendedor" || Auth::user()->getRoleNames()->first() == "Admin" || Auth::user()->getRoleNames()->first() == "Cliente")
<li id="drop-2" class="dropdown pepe">
        <a href="#" class="nav-link has-dropdown primario"><i class="fas fa-user"></i><span>Módulo Clientes</span></a>
        <ul id="dropmenu-2" class="dropdown-menu">
           @if(Auth::user()->getRoleNames()->first() == "Vendedor" || Auth::user()->getRoleNames()->first() == "Admin")
                <hr style="margin-top:1px; margin-bottom:1px">
                <li class="{{ Request::is('equipos') ? 'active' : '' }}">
                    <a href="{{ url('/equipos') }}" class="primario">Gestión Equipos Cliente </a> 
                </li>
            @endif

           @if(Auth::user()->getRoleNames()->first() == "Vendedor" || Auth::user()->getRoleNames()->first() == "Admin")
                <hr style="margin-top:0.5px; margin-bottom:0.5px">
                <li class="{{ Request::is('ordenesservicios') ? 'active' : '' }}">
                    <a href="{{ url('/ordenesservicios') }}" class="primario">Ordenes de Servicio</a> 
                </li>
            @endif

            @if(Auth::user()->getRoleNames()->first() == "Admin" || Auth::user()->getRoleNames()->first() == "Vendedor")
                <hr style="margin-top:0.5px; margin-bottom:0.5px">
                <li class="{{ Request::is('pagodiagnostico') ? 'active' : '' }}">
                    <a href="{{ url('/pagodiagnostico') }}" class="primario">Registrar Pago Diagnóstico </a>
                </li>
                <hr style="margin-top:0.5px; margin-bottom:0.5px">
            @endif

            @if(Auth::user()->getRoleNames()->first() == "Admin" || Auth::user()->getRoleNames()->first() == "Vendedor")
                <li class="{{ Request::is('pagoreparacion') ? 'active' : '' }}">
                    <a href="{{ url('/pagoreparacion') }}" class="primario">Registrar Pago Reparación </a>
                </li>
            @endif

            @if(Auth::user()->getRoleNames()->first() == "Cliente")
                <hr style="margin-top:0.5px; margin-bottom:0.5px">
                <li class="{{ Request::is('equipos/misequiposdiagnostico') ? 'active' : '' }}">
                    <a href="{{ url('/equipos/misequiposdiagnostico') }}" class="primario">Mis Equipos</a>
                </li>
            @endif

            @if(Auth::user()->getRoleNames()->first() == "Vendedor" || Auth::user()->getRoleNames()->first() == "Admin")
                <hr style="margin-top:0.5px; margin-bottom:0.5px">
                <li class="{{ Request::is('registroRetiro') ? 'active' : '' }}">
                    <a href="{{ url('/registroRetiro') }}" class="primario">Registrar Retiro Equipo/s</a>
                </li>
                <hr style="margin-top:0.5px; margin-bottom:0.5px">
            @endif
        </ul>
</li>
@endif
@if(Auth::user()->getRoleNames()->first() == "Admin" || Auth::user()->getRoleNames()->first() == "Tercero" || Auth::user()->getRoleNames()->first() == "Vendedor")
<li id="drop-3" class="dropdown pepe">
        <a href="#" class="nav-link has-dropdown primario"><i class="fas fa-hands-helping"></i><span>Módulo Terceros</span></a>
        <ul id="dropmenu-3" class="dropdown-menu">
            @if(Auth::user()->getRoleNames()->first() == "Admin")
                <hr style="margin-top:0.5px; margin-bottom:0.5px">
                <li class="{{ Request::is('asignacion/diagnostico/terceros') ? 'active' : '' }}">
                    <a href="{{ url('/asignacion/diagnostico/terceros') }}" class="primario">Asignar Equipos </a>
                </li>
                <hr style="margin-top:0.5px; margin-bottom:0.5px">
            @endif 
            @if(Auth::user()->getRoleNames()->first() == "Admin")
                <li class="{{ Request::is('asignacion/equipostercerossasignados') ? 'active' : '' }}">
                    <a href="{{ url('/asignacion/equipostercerossasignados') }}" class="primario">Asignaciones Realizadas </a>
                </li>
                <hr style="margin-top:0.5px; margin-bottom:0.5px">
            @endif 
            @if(Auth::user()->getRoleNames()->first() == "Tercero")
                <hr style="margin-top:1px; margin-bottom:1px">
                <li class="{{ Request::is('asignacion/vermisequiposasignados') ? 'active' : '' }}">
                    <a href="{{ url('/asignacion/vermisequiposasignados') }}" class="primario">Mis Asignaciones </a>
                </li>
                 <hr style="margin-top:0.5px; margin-bottom:0.5px">
            @endif
            @if(Auth::user()->getRoleNames()->first() == "Admin" || Auth::user()->getRoleNames()->first() == "Vendedor")
                <li class="{{ Request::is('asignacion/diagnostico/retirosterceros') ? 'active' : '' }}">
                    <a href="{{ url('/asignacion/diagnostico/retirosterceros') }}" class="primario">Registrar Devolución </a>
                </li>
                <hr style="margin-top:0.5px; margin-bottom:0.5px">
            @endif
        </ul>
</li>
@endif

@if(Auth::user()->getRoleNames()->first() == "Admin" || Auth::user()->getRoleNames()->first() == "Tecnico")
<li id="drop-4" class="dropdown pepe">
        <a href="#" class="nav-link has-dropdown primario"><i class="fas fa-diagnoses"></i><span>Módulo Diagnóstico</span></a>
        <ul id="dropmenu-4" class="dropdown-menu">
            @if(Auth::user()->getRoleNames()->first() == "Admin")
                <hr style="margin-top:0.5px; margin-bottom:0.5px">
                <li class="{{ Request::is('asignacion/diagnostico') ? 'active' : '' }}">
                    <a href="{{ url('/asignacion/diagnostico') }}" class="primario">Asignar Diagnóstico</a>
                </li>
                <hr style="margin-top:0.5px; margin-bottom:0.5px">
            @endif
            @if(Auth::user()->getRoleNames()->first() == "Admin")
                <li class="{{ Request::is('asignacion/diagnosticosasignados') ? 'active' : '' }}">
                    <a href="{{ url('asignacion/diagnosticosasignados') }}" class="primario">Asignaciones Realizadas</a>
                </li>
                <hr style="margin-top:0.5px; margin-bottom:0.5px">
            @endif
            @if(Auth::user()->getRoleNames()->first() == "Tecnico" || Auth::user()->getRoleNames()->first() == "Admin")
                <li class="{{ Request::is('asignacion/vermisdiagnosticosasignados') ? 'active' : '' }}">
                    <a href="{{ url('/asignacion/vermisdiagnosticosasignados') }}" class="primario">Mis Asignaciones</a>
                </li>
                <hr style="margin-top:0.5px; margin-bottom:0.5px">
            @endif

        </ul>
</li>
@endif

@if(Auth::user()->getRoleNames()->first() == "Tecnico" || Auth::user()->getRoleNames()->first() == "Admin")
<li id="drop-5" class="dropdown pepe">
        <a href="#" class="nav-link has-dropdown primario"><i class="fas fa-hammer"></i><span>Módulo Reparación</span></a>
        <ul id="dropmenu-5" class="dropdown-menu">
            @if(Auth::user()->getRoleNames()->first() == "Admin")
                <hr style="margin-top:0.5px; margin-bottom:0.5px">
                <li class="{{ Request::is('asignacion/reparacion') ? 'active' : '' }}">
                    <a href="{{ url('/asignacion/reparacion') }}" class="primario">Asignar Reparación</a>
                </li>
                <hr style="margin-top:0.5px; margin-bottom:0.5px">
            @endif
            @if(Auth::user()->getRoleNames()->first() == "Admin")
                <li class="{{ Request::is('asignacion/reparacionesasignadas') ? 'active' : '' }}">
                    <a href="{{ url('/asignacion/reparacionesasignadas') }}" class="primario">Asignaciones Realizadas</a>
                </li>
                <hr style="margin-top:0.5px; margin-bottom:0.5px">
            @endif
            @if(Auth::user()->getRoleNames()->first() == "Admin" || Auth::user()->getRoleNames()->first() == "Tecnico")
                <li class="{{ Request::is('asignacion/vermisreparacionesasignadas') ? 'active' : '' }}">
                    <a href="{{ url('/asignacion/vermisreparacionesasignadas') }}" class="primario">Mis Asignaciones</a>
                </li>
                <hr style="margin-top:0.5px; margin-bottom:0.5px">
            @endif
        </ul>
</li>
@endif

@if(Auth::user()->getRoleNames()->first() == "Admin")
<li id="drop-6" class="dropdown pepe">
        <a href="#" class="nav-link has-dropdown primario"><i class="fas fa-cubes"></i><span>Módulo Inventario</span></a>
        <ul id="dropmenu-6" class="dropdown-menu">
            @can('ver-estantes')
            <hr style="margin-top:0.5px; margin-bottom:0.5px">
            <li class="{{ Request::is('estantes') ? 'active' : '' }}">
                <a href="{{ url('/estantes') }}" class="primario">Gestionar Estantes</a>
            </li>
            <hr style="margin-top:0.5px; margin-bottom:0.5px">
            @endcan
            <li class="{{ Request::is('equipos/equiposabandonados') ? 'active' : '' }}">
                <a href="{{ url('/equipos/equiposabandonados') }}" class="primario">Registrar Abandono</a>
            </li>
            <hr style="margin-top:0.5px; margin-bottom:0.5px">
            <li class="{{ Request::is('equiporepuestos') ? 'active' : '' }}">
                <a href="{{ url('/equiporepuestos') }}" class="primario" style="height:50px">Gestionar Equipo Repuesto</a>
            </li>
            <hr style="margin-top:0.5px; margin-bottom:0.5px">
            <li class="{{ Request::is('repuestos') ? 'active' : '' }}">
                <a href="{{ url('/repuestos') }}" class="primario">Gestionar Repuesto</a>
            </li>
            <hr style="margin-top:0.5px; margin-bottom:0.5px">
        </ul>
</li>
@endif

@if(Auth::user()->getRoleNames()->first() == "Admin")
<li id="drop-7" class="dropdown pepe">
        <a href="#" class="nav-link has-dropdown primario"><i class="fas fa-cubes"></i><span>Módulo Estadísticas</span></a>
        <ul id="dropmenu-7" class="dropdown-menu">
            @can('ver-estantes')
            <hr style="margin-top:0.5px; margin-bottom:0.5px">
            <li class="{{ Request::is('graficos/barra') ? 'active' : '' }}">
                <a href="{{ url('/graficos/barra') }}" class="primario">Cantidades</a>
            </li>
            <hr style="margin-top:0.5px; margin-bottom:0.5px">
            @endcan
            <li class="{{ Request::is('graficos/torta') ? 'active' : '' }}">
                <a href="{{ url('/graficos/torta') }}" class="primario">Preferencias</a>
            </li>
            <hr style="margin-top:0.5px; margin-bottom:0.5px">
            <li class="{{ Request::is('graficos/linea') ? 'active' : '' }}">
                <a href="{{ url('/graficos/linea') }}" class="primario">Ingresos</a>
            </li>
            <hr style="margin-top:0.5px; margin-bottom:0.5px">
        </ul>
</li>
@endif
@if(Auth::user()->getRoleNames()->first() == "Admin")

@can('ver-estantes') <!-- Crear permiso y actualizar esta parte. -->
</li><li class="{{ Request::is('/auditoria') ? 'active' : '' }}"><a class="nav-link primario" href="{{ url('/auditoria') }}"><i class="fas fa-book"></i> <span>Auditoría</span></a></li>
@endcan

@endif

<script>

    $(document).ready(function(){
        

        $dropmenu1 = document.getElementById("dropmenu-1");
        $drop1 = document.getElementById("drop-1");
        $dropmenu2 = document.getElementById("dropmenu-2");
        $drop2 = document.getElementById("drop-2");
        $dropmenu3 = document.getElementById("dropmenu-3");
        $drop3 = document.getElementById("drop-3");
        $dropmenu4 = document.getElementById("dropmenu-4");
        $drop4 = document.getElementById("drop-4");
        $dropmenu5 = document.getElementById("dropmenu-5");
        $drop5 = document.getElementById("drop-5");
        $dropmenu6 = document.getElementById("dropmenu-6");
        $drop6 = document.getElementById("drop-6");
        
        //console.log($drop1.classList);
        
        if ($dropmenu1 != null && $dropmenu1.hasChildNodes()) {
            var children1 = $dropmenu1.childNodes;
            for (var i = 0; i < children1.length; i++) {
                
                if(typeof(children1[i].classList) != 'undefined'){
                    if(children1[i].classList.contains("active")){
                        $drop1.classList.add("active");
                        //console.log($dropmenu1.style.display);
                    }   
                }
            }
        }
        if ($dropmenu2 != null && $dropmenu2.hasChildNodes()) {
            var children2 = $dropmenu2.childNodes;
            for (var i = 0; i < children2.length; i++) {
                
                if(typeof(children2[i].classList) != 'undefined'){
                    if(children2[i].classList.contains("active")){
                        $drop2.classList.add("active");
                        //console.log($dropmenu1.style.display);
                    }   
                }
            }
        }
        if ($dropmenu3 != null && $dropmenu3.hasChildNodes()) {
            var children3 = $dropmenu3.childNodes;
            for (var i = 0; i < children3.length; i++) {
                
                if(typeof(children3[i].classList) != 'undefined'){
                    if(children3[i].classList.contains("active")){
                        $drop3.classList.add("active");
                        //console.log($dropmenu1.style.display);
                    }   
                }
            }
        }
        if ($dropmenu4 != null && $dropmenu4.hasChildNodes()) {
            var children4 = $dropmenu4.childNodes;
            for (var i = 0; i < children4.length; i++) {
                
                if(typeof(children4[i].classList) != 'undefined'){
                    if(children4[i].classList.contains("active")){
                        $drop4.classList.add("active");
                        //console.log($dropmenu1.style.display);
                    }   
                }
            }
        }
        if ($dropmenu5 != null && $dropmenu5.hasChildNodes()) {
            var children5 = $dropmenu5.childNodes;
            for (var i = 0; i < children5.length; i++) {
                
                if(typeof(children5[i].classList) != 'undefined'){
                    if(children5[i].classList.contains("active")){
                        $drop5.classList.add("active");
                        //console.log($dropmenu1.style.display);
                    }   
                }
            }
        }

        if ($dropmenu6 != null && $dropmenu6.hasChildNodes()) {
            var children6 = $dropmenu6.childNodes;
            for (var i = 0; i < children6.length; i++) {
                
                if(typeof(children6[i].classList) != 'undefined'){
                    if(children6[i].classList.contains("active")){
                        $drop6.classList.add("active");
                        //console.log($dropmenu6.style.display);
                    }   
                }
            }
        }


});
</script>

<script>

    document.addEventListener('DOMContentLoaded', init, false);
    function init(){
    
    //Llamado a Hamburguesa
    $hamburguesa = document.getElementById('hamburguer');

    
    
    //Llamado a Módulos.
    var elements = document.querySelectorAll('.pepe');
    

   

    //Definición e implementación de Función para los Módulos y así se mantenga iluminada la 
    //opción actual.
    var myFunction = function() {
         
        $splitLocationLength = window.location.pathname.split("/").length - 1;
        $splitLocationSearched = window.location.pathname.split("/")[$splitLocationLength];

        $hijos = this.children[1].children;

        for(i = 0; i < $hijos.length; i++){ 
            
            $hijo =  $hijos[i];
           
            if ($hijo.children[0] != undefined){
                $longitudHref = $hijos[i].children[0].href.split("/").length - 3;
                if($splitLocationLength == $longitudHref){
                   $splitHref = $hijos[i].children[0].href.split("/")[$longitudHref + 2];        
                   if($splitHref == $splitLocationSearched){
                    $hijo.children[0].setAttribute( 'style', 'color: #ffa426 !important');
                   }
                }   
            }
            
        }
    };

    var hamburguer = function(){
        var $dropdowns = document.querySelectorAll('[id^="drop-"]');
        //console.log($dropdowns);

        for(i=0; i < $dropdowns.length; i++){
            if($dropdowns[i].classList.contains("active")){
                $dropdowns[i].classList.remove("active");
            }
        }
        
    }
    
    //Asignación de eventos para los Módulos.
    for (var i = 0; i < elements.length; i++) {
        elements[i].addEventListener('click', myFunction, true);
    }
    
    //Asignación de evento para Hamburguesa.
    $hamburguesa.addEventListener('click', hamburguer, true);
    
};
    
    
    
//     $('.pepe').on("click", function() {
//         var $activo;
//         var $segmento;

//         console.log($(this).text());
//         $segmento = "<?php echo Request::segment(1)?>" //Tomamos el valor del segmento

//         console.log($segmento);

//         //console.log($(this).next().children()[1]);


//         for (var i = 0; i < $(this).next().children().length; i++){
//             if($(this).next().children()[i].tagName == 'LI'){
//                 hijito = $(this).children()[0];
//                 console.log(hijito);
//             }
//         }

//         if ($(this).hasChildNodes()) {
//         var children = $(this).childNodes;
//         for (var i = 0; i < children.length; i++) {
//             if(typeof(children[i].classList) != 'undefined'){
//                 if(children2[i].classList.contains("active")){
//                     $drop2.classList.add("active");
//                 }   
//             }
//         }
//     }
// });


</script>

 




