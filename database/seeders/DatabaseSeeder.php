<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(SeederTablaPermisos::class);
        

        $this->call(UserSeeder::class);
        $this->call(EstadoSeeder::class);
        $this->call(EstanteSeeder::class);
        $this->call(SeccionEstanteSeeder::class);
        $this->call(MarcaSeeder::class);
        $this->call(ServicioSeeder::class);
        $this->call(TipoAccesorioSeeder::class);
        $this->call(TipoEquipoSeeder::class);
    }
}
