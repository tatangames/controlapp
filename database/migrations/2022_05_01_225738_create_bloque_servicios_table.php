<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBloqueServiciosTable extends Migration
{
    /**
     * BLOQUE DE SERVICIOS (PUPUSAS, HELADOS, BEBIDAS)
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bloque_servicios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100)->nullable();
            $table->string('imagen', 100);
            $table->integer('posicion');
            $table->boolean('activo')->default(true);
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
