<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMotoristasOrdenesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('motoristas_ordenes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('ordenes_id')->unsigned();
            $table->bigInteger('motoristas_id')->unsigned();

            // fecha cuando agarro la orden el motorista
            $table->dateTime('fecha_agarrada');

            $table->foreign('ordenes_id')->references('id')->on('ordenes');
            $table->foreign('motoristas_id')->references('id')->on('motoristas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('motoristas_ordenes');
    }
}
