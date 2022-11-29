<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdenserviciosPresupuestosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ordenservicios_presupuestos', function (Blueprint $table) {
            $table->id();
            $table->float('presupuesto')->nullable();
            $table->boolean('presupuestado')->default(false);

            $table->foreignId('id_orden')
                ->nullable()
                ->constrained('ordenesservicio')
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
        Schema::dropIfExists('ordenservicios_presupuestos');
    }
}
