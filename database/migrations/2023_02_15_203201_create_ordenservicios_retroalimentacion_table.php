<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdenserviciosRetroalimentacionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ordenservicios_retroalimentacion', function (Blueprint $table) {
            $table->id();

            $table->foreignId('id_orden')
            ->nullable()
            ->constrained('ordenesservicio')
            ->cascadeOnUpdate()
            ->nullOnDelete();

            $table->string('retroalimentacion', 500)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ordenservicios_retroalimentacion');
    }
}
