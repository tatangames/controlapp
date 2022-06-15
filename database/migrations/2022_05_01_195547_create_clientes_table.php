<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientesTable extends Migration
{
    /**
     * registro de clientes
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('usuario', 20)->unique()->nullable();
            $table->string('correo', 100)->unique()->nullable();
            $table->string('codigo_correo',10)->nullable();
            $table->string('password', 255);
            $table->dateTime('fecha');
            $table->boolean('activo');
            $table->string('token_fcm', 100)->nullable();
        });
    }

    /**|
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clientes');
    }
}
