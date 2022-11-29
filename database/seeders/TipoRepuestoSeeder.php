<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TipoRepuesto;

class TipoRepuestoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TipoRepuesto::create([ 
            'nombre' => 'Display',
            'descripcion' => 'Pantalla que se utilizará de repuesto en dicho caso.',
        ]);
        TipoRepuesto::create([ 
            'nombre' => 'Disco',
            'descripcion' => 'Disco de almacenamiento para notebook, netbook, pc escritorio.',
        ]);
        TipoRepuesto::create([ 
            'nombre' => 'Cooler',
            'descripcion' => 'Sistema de ventilación para notebook, netbook, pc escritorio.',
        ]);
        TipoRepuesto::create([ 
            'nombre' => 'Fuente Alimentación',
            'descripcion' => 'Sistema de alimentación para PC u otro tipo de Equipo.',
        ]);
        TipoRepuesto::create([ 
            'nombre' => 'Pin de Carga',
            'descripcion' => 'Puerto por donde el Equipo como Notebook recibe la alimentación requerida.',
        ]);
        TipoRepuesto::create([ 
            'nombre' => 'RAM',
            'descripcion' => 'Memoria para Notebook, Netbook, PC, etc.',
        ]);
    }
}
