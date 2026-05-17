<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDireccionClienteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('direccion_cliente', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_cliente')->unsigned();

            $table->string('nombre', 100);
            $table->string('direccion', 400);
            $table->string('punto_referencia', 400)->nullable();
            $table->boolean('seleccionado');
            $table->string('telefono', 10);


            $table->foreign('id_cliente')->references('id')->on('clientes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('direccion_cliente');
    }
}
