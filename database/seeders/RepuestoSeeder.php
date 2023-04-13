<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Repuesto;

class RepuestoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Repuesto::create([ 
            'modelo' => 'MA1300',
            'cantidad' => 10,
            'precio' => 5000,
            'descripcion' => 'Capacitancia: 1800 F. Voltaje máximo: 6.3V. Temperatura mínima de funcionamiento - Temperatura máxima de funcionamiento: 105 °C - 105 °C ',
            'id_seccionestante' => 1,
            'id_marca' => 3,
            'id_tiporepuesto' => 4,
        ]);

    }
}
