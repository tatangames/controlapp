<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdenesDescripcionTable extends Migration
{
    /**
     * LISTADO DE PRODUCTO DE LA ORDEN
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ordenes_descripcion', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_ordenes')->unsigned();
            $table->bigInteger('id_producto')->unsigned();
            $table->integer('cantidad');
            $table->string('nota', 400)->nullable();
            $table->decimal('precio', 10,2);

            $table->foreign('id_ordenes')->references('id')->on('ordenes');
            $table->foreign('id_producto')->references('id')->on('producto');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ordenes_descripcion');
    }
}
