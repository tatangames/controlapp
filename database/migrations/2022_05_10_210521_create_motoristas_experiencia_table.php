<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMotoristasExperienciaTable extends Migration
{
    /**
     * calificacion entrega
     *
     * @return void
     */
    public function up()
    {
        Schema::create('motoristas_experiencia', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('ordenes_id')->unsigned();
            $table->bigInteger('motoristas_id')->unsigned();
            $table->integer('experiencia');
            $table->string('mensaje', 500)->nullable();
            $table->dateTime('fecha');

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
        Schema::dropIfExists('motoristas_experiencia');
    }
}
