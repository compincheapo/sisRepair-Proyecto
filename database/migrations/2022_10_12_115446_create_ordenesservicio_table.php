<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdenesservicioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ordenesservicio', function (Blueprint $table) {
            $table->id();
            $table->date('fechacompromiso')->nullable();
            $table->boolean('finalizado');
            $table->timestamp('fechafin')->nullable();

            $table->foreignId('id_equipo')
                ->nullable()
                ->constrained('equipos')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            
            $table->foreignId('id_servicio')
                  ->nullable()
                  ->constrained('servicios')
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
        Schema::dropIfExists('ordenesservicio');
    }
}
