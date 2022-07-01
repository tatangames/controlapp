<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBloquesEventosTable extends Migration
{
    /**
     * bloque para cada evento
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bloques_eventos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 200);
            $table->string('imagen', 100);
            $table->boolean('activo');
            $table->date('fecha');
            $table->integer('posicion');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bloques_eventos');
    }
}
