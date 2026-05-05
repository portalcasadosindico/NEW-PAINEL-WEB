<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogsendinblueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logsendinblue', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->dateTime('dataCriacao')->nullable();
            $table->integer('idOrcamento')->nullable();
            $table->longText('retorno')->nullable();
            $table->timestamp('data_cadastro')->nullable();
            $table->timestamp('data_atualizacao')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('logsendinblue');
    }
}
