<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Estante;

class EstanteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Estante::create([ 
            'nombre' => 'Estante 1',
            'descripcion' => 'Estante para equipos de repuesto.',
        ]);
        Estante::create([ 
            'nombre' => 'Estante 2',
            'descripcion' => 'Estante para equipos cliente tipo Notebook, Netbook.',
        ]);
        Estante::create([ 
            'nombre' => 'Estante 3',
            'descripcion' => 'Estante para impresoras.',
        ]);
    }
}
