<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Estado;

class EstadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Estado::create([ 
            'nombre' => 'A Diagnóstico',
            'descripcion' => 'Equipo registrado para asignar a diagnóstico.',
        ]);
        Estado::create([ 
            'nombre' => 'Asignado Diagnóstico',
            'descripcion' => 'Equipo asociado a un Técnico para su diagnóstico.',
        ]);
        Estado::create([ 
            'nombre' => 'En Diagnóstico',
            'descripcion' => 'Equipo en Diagnóstico por parte de un Técnico.',
        ]);
        Estado::create([ 
            'nombre' => 'Diagnosticado',
            'descripcion' => 'Equipo diagnosticado con los detalles del problema.',
        ]);
        Estado::create([ 
            'nombre' => 'A Reparación',
            'descripcion' => 'Equipo registrado para asignar a reparación.',
        ]);
        Estado::create([ 
            'nombre' => 'Asignado Reparación',
            'descripcion' => 'Equipo asociado a un Técnico para su reparación.',
        ]);
        Estado::create([ 
            'nombre' => 'En Reparación',
            'descripcion' => 'Equipo en Reparación por parte de un Técnico.',
        ]);
        Estado::create([ 
            'nombre' => 'Reparado',
            'descripcion' => 'Equipo reparado con los detalles de lo utilizado.',
        ]);
        Estado::create([ 
            'nombre' => 'Reasignado',
            'descripcion' => 'Equipo que se ha reasignado de un Técnico a otro.',
        ]);
        Estado::create([ 
            'nombre' => 'Presupuestado',
            'descripcion' => 'Equipo con diagnóstico finalizado y donde el Gerente realiza el presupuesto de dicho trabajo según los problemas encontrados y que derivan a la solución',
        ]);
        Estado::create([ 
            'nombre' => 'Presupuesto Aceptado',
            'descripcion' => 'Equipo con diagnóstico finalizado y donde el Gerente realiza el presupuesto de dicho trabajo según los problemas encontrados y que derivan a la solución',
        ]);
        Estado::create([ 
            'nombre' => 'Diagnóstico Pagado',
            'descripcion' => 'Equipo con Diagnóstico finalizado y el cliente realiza el pago.',
        ]);
        Estado::create([ 
            'nombre' => 'Reparación Pagada',
            'descripcion' => 'Equipo con Reparación finalizada y el cliente realiza el pago.',
        ]);
        Estado::create([ 
            'nombre' => 'Equipo Repuesto',
            'descripcion' => 'Equipo que se registró para que se utilice como repuesto para reparaciones futuras.',
        ]);
        Estado::create([ 
            'nombre' => 'Equipo Abandonado',
            'descripcion' => 'Equipo del cliente que luego de no pagarse según lo especificado en los términos y condiciones, se registró para que se utilice como repuesto para reparaciones futuras.',
        ]);
        Estado::create([ 
            'nombre' => 'Equipo Retirado por Tercero',
            'descripcion' => 'Equipo del Cliente que luego de asignarse a un Tercero, este lo retira del local para su respecto Diagnóstico o Reparación.',
        ]);
        Estado::create([ 
            'nombre' => 'Equipo Ingresado por Tercero',
            'descripcion' => 'Equipo del Cliente que luego de asignarse a un Tercero, este lo Diagnostica o Repara y realiza la devolución en el respectivo local.',
        ]);
        Estado::create([ 
            'nombre' => 'Presupuesto Rechazado',
            'descripcion' => 'El Presupuesto que ha ofrecido el Gerente para realizar la reparación según el Diagnóstico dado, ha sido rechazado por el Cliente.',
        ]);
        Estado::create([ 
            'nombre' => 'Equipo Retirado por Cliente',
            'descripcion' => 'El Cliente se acerca al Local para realizar el retiro de su Equipo, esto se registra.',
        ]);
    }
}
