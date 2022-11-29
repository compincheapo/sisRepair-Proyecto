<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRepuestosOrdenesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repuestos_ordenes', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('id_orden')
                ->nullable()
                ->constrained('ordenesservicio')
                ->cascadeOnUpdate()
                ->nullOnDelete();
        
            $table->foreignId('id_repuesto')
            ->nullable()
            ->constrained('repuestos')
            ->cascadeOnUpdate()
            ->nullOnDelete();

            $table->integer('cantidad');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('repuestos_ordenes');
    }
}
