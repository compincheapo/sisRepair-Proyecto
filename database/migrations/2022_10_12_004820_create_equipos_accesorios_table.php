<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquiposAccesoriosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipos_accesorios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_equipo')
                  ->nullable()
                  ->constrained('equipos')
                  ->cascadeOnUpdate()
                  ->nullOnDelete();

            $table->foreignId('id_accesorio')
                  ->nullable()
                  ->constrained('tipoaccesorios')
                  ->cascadeOnUpdate()
                  ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *  
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('equipos_accesorios');
    }
}
