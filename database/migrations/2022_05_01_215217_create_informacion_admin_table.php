<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInformacionAdminTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('informacion_admin', function (Blueprint $table) {
            $table->id();

            // cerrar aplicacion y establecer mensaje
            $table->boolean('cerrado');
            $table->string('mensaje_cerrado', 300);

            // mensaje de cerrado por horario normal de cierre
            $table->string('cerrado_horario', 300);

            // mensaje de cerrado este dia
            $table->string('cerrado_estedia', 300);



            // establecer opcion para recoger en local o no
            // 0- solo habra domicilio
            // 1- si habra local y domicilio
            $table->boolean('domicilio');

            // ocultar slider
            $table->boolean('activo_slider');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('informacion_admin');
    }
}
