<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarritoExtraTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carrito_extra', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('carrito_temporal_id')->unsigned();
            $table->bigInteger('producto_id')->unsigned();

            $table->string('nota_producto', 400)->nullable();
            $table->integer('cantidad');

            $table->foreign('carrito_temporal_id')->references('id')->on('carrito_temporal');
            $table->foreign('producto_id')->references('id')->on('producto');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carrito_extra');
    }
}
