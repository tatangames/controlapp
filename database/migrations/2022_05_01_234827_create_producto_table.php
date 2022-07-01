<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('producto', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('categorias_id')->unsigned();

            $table->string('nombre', 150);
            $table->string('imagen', 100)->nullable();
            $table->string('descripcion', 2000)->nullable();
            $table->decimal('precio', 10,2);
            $table->boolean('disponibilidad');
            $table->boolean('activo');
            $table->integer('posicion');
            $table->boolean('utiliza_nota');
            $table->string('nota', 500)->nullable();
            $table->boolean('utiliza_imagen');

            $table->foreign('categorias_id')->references('id')->on('categorias');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('producto');
    }
}
