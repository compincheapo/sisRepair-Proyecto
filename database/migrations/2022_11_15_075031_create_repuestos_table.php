<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRepuestosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repuestos', function (Blueprint $table) {
            $table->id();
            $table->string('modelo');
            $table->integer('cantidad');
            $table->float('precio');
            $table->string('descripcion', 200)->nullable();

            $table->foreignId('id_seccionestante')
                  ->nullable()
                  ->constrained('seccionesestante')
                  ->cascadeOnUpdate()
                  ->nullOnDelete();

            $table->foreignId('id_marca')
                  ->nullable()
                  ->constrained('marcas')
                  ->cascadeOnUpdate()
                  ->nullOnDelete();

            $table->foreignId('id_tiporepuesto')
                  ->nullable()
                  ->constrained('tiporepuestos')
                  ->cascadeOnUpdate()
                  ->nullOnDelete();

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
        Schema::dropIfExists('repuestos');
    }
}
