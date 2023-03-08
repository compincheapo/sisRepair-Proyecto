<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquiposTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipos', function (Blueprint $table) {
            $table->id();
            $table->string('serie');
            $table->string('modelo');

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
            
            $table->foreignId('id_tipoequipo')
                  ->nullable()
                  ->constrained('tipoequipos')
                  ->cascadeOnUpdate()
                  ->nullOnDelete();

            $table->foreignId('id_user')
                    ->nullable()
                    ->constrained('users')
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
        Schema::dropIfExists('equipos');
    }
}
