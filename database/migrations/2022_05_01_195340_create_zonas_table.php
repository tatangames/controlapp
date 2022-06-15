<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZonasTable extends Migration
{
    /**
     * zonas de mapa
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zonas', function (Blueprint $table) {
            $table->id();

            // nombre de la zona
            $table->string('nombre', 100);

            // ubicar punto central del mapa
            $table->string('latitud', 50);
            $table->string('longitud', 50);

            // si tenemos problemas de envio a esta zona
            $table->boolean('saturacion');
            $table->string('mensaje_bloqueo',200)->nullable();

            // horario domicilio a esta zona
            $table->time('hora_abierto_delivery');
            $table->time('hora_cerrado_delivery');

            // visibilidad de la zona en el mapa
            $table->boolean('activo');

            // minimo de consumo para envio a $0.00
            $table->decimal('minimo_consumo', 10, 2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('zonas');
    }
}
