<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdenserviciosPagosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ordenservicios_pagos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('id_orden')
            ->nullable()
            ->constrained('ordenesservicio')
            ->cascadeOnUpdate()
            ->nullOnDelete();

            $table->foreignId('id_pago')
            ->nullable()
            ->constrained('pagos')
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
        Schema::dropIfExists('ordenservicios_pagos');
    }
}
