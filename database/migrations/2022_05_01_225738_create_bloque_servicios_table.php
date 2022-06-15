<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBloqueServiciosTable extends Migration
{
    /**
     * Mostrara bloque de servicios horizontales tales como
     * neveria
     * pupuseria
     * cafeteria
     * etc
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bloque_servicios', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('tiposervicio_id')->unsigned();
            $table->string('imagen', 100);
            $table->integer('posicion');
            $table->boolean('activo');
            $table->string('nombre', 100)->nullable();

            $table->foreign('tiposervicio_id')->references('id')->on('tipo_servicio');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bloque_servicios');
    }
}
