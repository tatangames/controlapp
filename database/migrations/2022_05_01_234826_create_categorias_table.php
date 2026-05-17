<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriasTable extends Migration
{
    /**
     * CATEGORIAS DEL SERVICIO
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categorias', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_bloque_servicios')->unsigned();

            $table->string('nombre', 200);
            $table->integer('posicion');
            $table->boolean('activo');

            $table->foreign('id_bloque_servicios')->references('id')->on('bloque_servicios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categorias');
    }
}
