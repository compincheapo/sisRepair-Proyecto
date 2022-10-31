<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquiposEstadosUsersOrdenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipos_estados_users_ordenes', function (Blueprint $table) {
            $table->id();
           

            $table->foreignId('id_equipo')
            ->nullable()
            ->constrained('equipos')
            ->cascadeOnUpdate()
            ->nullOnDelete();

            $table->foreignId('id_estado')
            ->nullable()
            ->constrained('estados')
            ->cascadeOnUpdate()
            ->nullOnDelete();

            $table->foreignId('id_user')
            ->nullable()
            ->constrained('users')
            ->cascadeOnUpdate()
            ->nullOnDelete();
            
            $table->foreignId('id_orden')
            ->nullable()
            ->constrained('ordenesservicio')
            ->cascadeOnUpdate()
            ->nullOnDelete();

            $table->timestamp('created_at');

            $table->string('descripcion', 300)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('equipos_estados_users_ordenes');
    }
}
