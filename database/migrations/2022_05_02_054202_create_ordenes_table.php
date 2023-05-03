<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdenesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ordenes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('clientes_id')->unsigned();

            $table->string('nota', 600)->nullable();
            $table->decimal('precio_consumido', 10,2); // total de la orden

            $table->dateTime('fecha_orden');

            $table->boolean('estado_iniciada');
            $table->dateTime('fecha_iniciada')->nullable();

            // el cliente es el que finaliza la orden
            $table->boolean('estado_finalizada');
            $table->dateTime('fecha_finalizada')->nullable();

            $table->boolean('estado_cancelada');
            $table->dateTime('fecha_cancelada')->nullable();

            $table->string('mensaje_cancelada', 600)->nullable(); // porque fue cancelada

            // orden cancelada por
            // 0 nada
            // 1 -> cliente
            // 2 -> propietario

            $table->integer('cancelada_por');


            $table->boolean('visible');


            $table->integer('estrellas');
            $table->string('mensaje_estrellas', 600)->nullable();


            $table->foreign('clientes_id')->references('id')->on('clientes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ordenes');
    }
}
