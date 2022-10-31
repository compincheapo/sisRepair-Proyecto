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
            'name' => 'Fernando',
            'lastname' => 'Nahirñak',
            'username' => 'gerente',
            'email' => 'fer.nahirnak@gmail.com',
            'password' => bcrypt('compinche')
        ])->assignRole('Admin');
        
        for($i = 1; $i <= 10; $i++){
            $user = User::factory()->create();
            $user->assignRole('Cliente');
        }

        for($i = 1; $i <= 5; $i++){
            $user = User::factory()->create();
            $user->assignRole('Vendedor');
        }

        for($i = 1; $i <= 5; $i++){
            $user = User::factory()->create();
            $user->assignRole('Tecnico');
        }

    }
}
