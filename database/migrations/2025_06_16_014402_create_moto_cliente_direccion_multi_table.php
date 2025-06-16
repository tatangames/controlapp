<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMotoClienteDireccionMultiTable extends Migration
{
    /**
     * DIRECCION EXTRA DEL MISMO CLIENTE
     *
     * @return void
     */
    public function up()
    {
        Schema::create('moto_cliente_direccion_multi', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('id_cliente_direc')->unsigned();

            $table->string('nombre', 100)->nullable();
            $table->string('direccion', 500)->nullable();
            $table->string('referencia', 500)->nullable();
            $table->string('telefono', 20)->nullable();

            // MAPA
            $table->string('latitud', 100)->nullable();
            $table->string('longitud', 100)->nullable();


            $table->foreign('id_cliente_direc')->references('id')->on('cliente_direcciones');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('moto_cliente_direccion_multi');
    }
}
