<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBloqueSliderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bloque_slider', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_producto')->unsigned()->nullable();

            $table->string('imagen', 100);
            $table->string('descripcion', 300)->nullable();
            $table->integer('posicion');

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
        Schema::dropIfExists('bloque_slider');
    }
}
