<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Marca;

class MarcaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Marca::create([ 
            'nombre' => 'Samsung',
            'descripcion' => 'Marca de Equipos tipo Notebook, Netbook, Monitores, etc.',
        ]);
        Marca::create([ 
            'nombre' => 'DELL',
            'descripcion' => 'Marca de Equipos tipo Notebook, Netbook, Monitores, etc.',
        ]);
        Marca::create([ 
            'nombre' => 'Giga-Byte Technology',
            'descripcion' => 'Marca de fuentes de alimentación, repuestos pc, etc.',
        ]);
    }
}
