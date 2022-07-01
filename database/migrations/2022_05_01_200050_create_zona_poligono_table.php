<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZonaPoligonoTable extends Migration
{
    /**
     * poligonos para mapa
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zona_poligono', function (Blueprint $table) {
            $table->id();
            $table->string('latitud', 50);
            $table->string('longitud', 50);
            $table->bigInteger('zonas_id')->unsigned();
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
        Schema::dropIfExists('zona_poligono');
    }
}
