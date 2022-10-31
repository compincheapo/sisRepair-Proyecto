<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Servicio;

class ServicioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Servicio::create([             
            'nombre' => 'Diagnóstico',
            'descripcion' => 'Revisión en diferentes niveles de detalle del Equipo para determinar el problema y presupuesto.',

        ]);

        Servicio::create([ 
            'nombre' => 'Reparación',
            'descripcion' => 'Luego de determinar el problema y presupuesto mediante el Diagnóstico, se procede a reparar para poner en marcha el Equipo.',
        ]);
    }
}
