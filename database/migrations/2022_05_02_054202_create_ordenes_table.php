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
            $table->string('cambio', 20)->nullable();

            $table->boolean('estado_2');
            $table->dateTime('fecha_2')->nullable();

            $table->boolean('estado_3');
            $table->dateTime('fecha_3')->nullable();

            $table->boolean('estado_4');
            $table->dateTime('fecha_4')->nullable();

            $table->boolean('estado_5');
            $table->dateTime('fecha_5')->nullable();

            $table->boolean('estado_6');
            $table->dateTime('fecha_6')->nullable();

            $table->boolean('estado_7');
            $table->dateTime('fecha_7')->nullable();

            $table->string('mensaje_7', 600)->nullable(); // porque fue cancelada

            $table->boolean('visible');
            $table->boolean('visible_p');
            $table->boolean('visible_p2');
            $table->boolean('visible_p3');

            $table->integer('cancelado'); // 0: nadie, 1: cliente, 2: propietario
            $table->boolean('visible_m');

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
