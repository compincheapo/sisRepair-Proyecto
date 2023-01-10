<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TipoEquipo;

class TipoEquipoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TipoEquipo::create([ 
            'nombre' => 'Notebook',
            'descripcion' => 'Portatil de mediana-grande prestación',
        ]);
        TipoEquipo::create([ 
            'nombre' => 'Netbook',
            'descripcion' => 'Portatil de pequeñas prestaciones',
        ]);
        TipoEquipo::create([ 
            'nombre' => 'PC Escritorio',
            'descripcion' => 'Computadora de Escritorio de mesa.',
        ]);
        TipoEquipo::create([ 
            'nombre' => 'Impresora',
            'descripcion' => 'Impresoras en general.',
        ]);
    }
}
