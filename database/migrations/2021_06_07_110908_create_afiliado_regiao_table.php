<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAfiliadoRegiaoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('afiliado_regiao', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('afiliado_id')->index('afiliado_regiao_afiliado_id_foreign');
            $table->unsignedBigInteger('regiao_id')->index('afiliado_regiao_regiao_id_foreign');
            $table->unsignedBigInteger('plano_assinatura_afiliado_regiao_id')->nullable()->index('afiliado_regiao_plano_assinatura_afiliado_regiao_id_foreign');
            $table->timestamp('data_pagamento_plano')->nullable();
            $table->date('data_expiracao_plano')->nullable();
            $table->timestamp('data_cadastro')->useCurrent();
            $table->timestamp('data_atualizacao')->useCurrent();
            $table->softDeletes();
            $table->string('modo', 45)->default('debug');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('afiliado_regiao');
    }
}
