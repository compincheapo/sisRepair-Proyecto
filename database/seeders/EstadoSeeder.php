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
    }
}
