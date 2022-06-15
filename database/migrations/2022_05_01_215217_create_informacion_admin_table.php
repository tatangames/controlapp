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

            // cerrado por evento
            $table->boolean('cerrado');
            $table->string('mensaje_cerrado', 300);

            // para el CRON que envia notificacion a cada propietario si hay ordenes sin contestar
            $table->boolean('activo_noti');
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
