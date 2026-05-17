<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHorarioTable extends Migration
{
    /**
     * HORARIO DEL LOCAL
     *
     * @return void
     */
    public function up()
    {
        Schema::create('horario', function (Blueprint $table) {
            $table->id();
            $table->time('hora1');
            $table->time('hora2');
            $table->integer('dia'); // Numero de Dia
            $table->boolean('cerrado'); // Que dia esta Cerrado
            $table->string('nombre', 25); // Nombre del Dia
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('horario');
    }
}
