<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductoTable extends Migration
{
    /**
     * LISTA DE PRODUCTOS
     *
     * @return void
     */
    public function up()
    {
        Schema::create('producto', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_categorias')->unsigned();

            $table->string('nombre', 150);
            $table->string('imagen', 100)->nullable();
            $table->string('descripcion', 2000)->nullable();
            $table->decimal('precio', 10,2);
            $table->boolean('activo');
            $table->integer('posicion');
            $table->boolean('utiliza_nota')->default(false);
            $table->string('nota', 500)->nullable();
            $table->boolean('utiliza_imagen');

            $table->foreign('id_categorias')->references('id')->on('categorias');
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
