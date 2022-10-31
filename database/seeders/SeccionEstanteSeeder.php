<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SeccionesEstante;
class SeccionEstanteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SeccionesEstante::create([ 
            'nombre' => 'Seccion A',
            'descripcion' => 'Equipos Marca DELL.',
            'id_estante' => '1'
        ]);
        SeccionesEstante::create([ 
            'nombre' => 'Seccion B',
            'descripcion' => 'Equipos Marca ACER.',
            'id_estante' => '1'
        ]);
        SeccionesEstante::create([ 
            'nombre' => 'Seccion A',
            'descripcion' => 'Notebook DELL.',
            'id_estante' => '2'
        ]);
    }
}
