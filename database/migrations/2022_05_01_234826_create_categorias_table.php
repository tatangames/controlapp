<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categorias', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('bloqueservicio_id')->unsigned();

            $table->string('nombre', 200);
            $table->integer('posicion');
            $table->boolean('activo');
            $table->boolean('visible');

            $table->time('hora1');
            $table->time('hora2');
            $table->boolean('usahorario');

            $table->foreign('bloqueservicio_id')->references('id')->on('bloque_servicios');
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
