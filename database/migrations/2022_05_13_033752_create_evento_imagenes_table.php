<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventoImagenesTable extends Migration
{
    /**
     * imagenes para eventos
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evento_imagenes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('evento_id')->unsigned();
            $table->string('imagen', 100);
            $table->integer('posicion');

            $table->foreign('evento_id')->references('id')->on('bloques_eventos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('evento_imagenes');
    }
}
