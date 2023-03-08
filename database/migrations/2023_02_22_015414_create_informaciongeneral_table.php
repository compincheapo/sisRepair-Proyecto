<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInformaciongeneralTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('informaciongeneral', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('cuit');
            $table->string('provincia');
            $table->string('localidad');
            $table->string('direccion');
            $table->string('celular');
            $table->string('diadesde');
            $table->string('diahasta');
            $table->time('horadesde');
            $table->time('horahasta');
            $table->text('terminos');
            $table->string('cant_notif_cliente');
            $table->string('frecuencia_notif_cliente');
            $table->string('cant_notif_tercero');
            $table->string('frecuencia_notif_tercero');
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
        Schema::dropIfExists('informaciongeneral');
    }
}
