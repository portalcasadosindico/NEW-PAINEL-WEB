<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFranqueadoRegiaoPlanoDisponibilizadoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('franqueado_regiao_plano_disponibilizado', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('franqueado_regiao_id')->index('fk_franqueado_regiao_id');
            $table->unsignedBigInteger('plano_disponivel_franqueado_id')->index('fk_plano_disponivel_franqueado_id');
            $table->timestamp('data_cadastro')->useCurrent();
            $table->timestamp('data_atualizacao')->useCurrent();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('franqueado_regiao_plano_disponibilizado');
    }
}
