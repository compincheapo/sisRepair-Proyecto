<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TipoAccesorio;

class TipoAccesorioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TipoAccesorio::create([ 
            'nombre' => 'Cargador',
            'descripcion' => 'Accesorio para realizar la carga del Equipo.',
        ]);
        TipoAccesorio::create([ 
            'nombre' => 'Funda',
            'descripcion' => 'Accesorio para proteger el Equipo de rayones o marcas.',
        ]);
        TipoAccesorio::create([ 
            'nombre' => 'Cable Alimentación',
            'descripcion' => 'Accesorio para alimentar el Equipo de forma eléctrica.',
        ]);
        TipoAccesorio::create([ 
            'nombre' => 'Mouse',
            'descripcion' => 'Accesorio para movilidad del puntero en el Sistema',
        ]);
        TipoAccesorio::create([ 
            'nombre' => 'Teclado',
            'descripcion' => 'Accesorio para escritura en el Sistema.',
        ]);
    }
}
