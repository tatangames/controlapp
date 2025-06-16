<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMotoristasTable extends Migration
{
    /**
     * MOTORISTAS
     *
     * @return void
     */
    public function up()
    {
        Schema::create('motoristas', function (Blueprint $table) {
            $table->id();
            $table->string('usuario', 20)->unique();
            $table->string('password', 255);

            $table->string('nombre', 100)->nullable();

            // CADA VEZ QUE SE ACTUALIZE PANEL O EL MOTORISTA AJUSTE DIRECCION GPS
            // SE DEBERA ACTUALIZAR LAS APP DESCARGANDO NUEVOS DATOS NUEVAMENTE

            $table->boolean('actualizar');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('motoristas');
    }
}
