<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([                  //Definimos nuestra credencial para ingresar al sistema.
            'name' => 'Compinche',
            'email' => 'compincheapo@gmail.com',
            'password' => bcrypt('compinche')

        ])->assignRole('Admin');
        
        User::factory(99)->create(); //Crea 99 usuarios en la BD.
    }
}
