<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TipoPago;

class TipoPagoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TipoPago::create([ 
            'nombre' => 'Efectivo',
            'descripcion' => 'Pago en efectivo de manera presencial en local.',
        ]);
        TipoPago::create([ 
            'nombre' => 'Mercadopago',
            'descripcion' => 'Pago de manera presencial (a través de QR, alias o número) o virtual (a través de la plataforma web).',
        ]);
    }
}
