<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSessaousuarioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sessaousuario', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('idUsuario')->nullable();
            $table->dateTime('inicioSessao')->nullable();
            $table->dateTime('fimSessao')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sessaousuario');
    }
}
