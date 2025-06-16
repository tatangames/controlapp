<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMotoClienteDireccionTable extends Migration
{
    /**
     * LISTADO CLIENTES DIRECCIONES
     *
     * @return void
     */
    public function up()
    {
        Schema::create('moto_cliente_direccion', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100)->nullable();
            $table->string('direccion', 500)->nullable();
            $table->string('referencia', 500)->nullable();
            $table->string('telefono', 20)->nullable();

            // MAPA
            $table->string('latitud', 100)->nullable();
            $table->string('longitud', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('moto_cliente_direccion');
    }
}
