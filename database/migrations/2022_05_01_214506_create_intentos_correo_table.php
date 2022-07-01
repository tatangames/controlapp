<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIntentosCorreoTable extends Migration
{
    /**
     * Registro de intentos que se han hecho para recuperar contraseña
     *
     * @return void
     */
    public function up()
    {
        Schema::create('intentos_correo', function (Blueprint $table) {
            $table->id();
            $table->string('correo', 100);
            $table->dateTime('fecha');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('intentos_correo');
    }
}
