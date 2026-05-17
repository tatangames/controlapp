<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdenesDireccionesTable extends Migration
{
    /**
     * DIRECCION DE LA ORDEN
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ordenes_direcciones', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_ordenes')->unsigned();

            $table->string('nombre', 100);
            $table->string('direccion', 400);
            $table->string('punto_referencia', 400)->nullable();
            $table->string('telefono', 10);

            $table->string('version', 100)->nullable();  // version de la app
            $table->foreign('id_ordenes')->references('id')->on('ordenes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ordenes_direcciones');
    }
}
