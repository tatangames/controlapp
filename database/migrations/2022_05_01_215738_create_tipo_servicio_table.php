<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTipoServicioTable extends Migration
{
    /**
     * tipo de servicios
     * 1- eventos
     * 2- alimentos
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipo_servicio', function (Blueprint $table) {
            $table->id();
            $table->string('nombre',50);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tipo_servicio');
    }
}
