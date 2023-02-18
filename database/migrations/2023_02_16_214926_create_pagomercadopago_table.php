<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagomercadopagoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pagomercadopago', function (Blueprint $table) {
            $table->id();
            $table->string('collection_id', 50);
            $table->string('collection_status', 50);
            $table->string('payment_id', 50);
            $table->string('status', 50);
            $table->string('payment_type', 50);
            $table->string('merchant_order_id', 50);
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
        Schema::dropIfExists('pagomercadopago');
    }
}
