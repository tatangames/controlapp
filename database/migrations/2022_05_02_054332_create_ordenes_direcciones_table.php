<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdenesDireccionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ordenes_direcciones', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('clientes_id')->unsigned();
            $table->bigInteger('ordenes_id')->unsigned();
            $table->bigInteger('zonas_id')->unsigned();

            $table->string('nombre', 100);
            $table->string('direccion', 400);
            $table->string('punto_referencia', 400)->nullable();
            $table->string('latitud', 50)->nullable();
            $table->string('longitud', 50)->nullable();
            $table->string('latitudreal', 50)->nullable();
            $table->string('longitudreal', 50)->nullable();
            $table->string('telefono', 10);
            // version de la app
            $table->string('version', 100)->nullable();

            $table->foreign('clientes_id')->references('id')->on('clientes');
            $table->foreign('ordenes_id')->references('id')->on('ordenes');
            $table->foreign('zonas_id')->references('id')->on('zonas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ordenes_direcciones');
    }
}
