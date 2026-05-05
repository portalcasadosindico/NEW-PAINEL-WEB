<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlanoAssinaturaAfiliadoRegiaoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plano_assinatura_afiliado_regiao', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('nome');
            $table->text('descricao')->nullable();
            $table->decimal('valor', 10);
            $table->decimal('valor_comissao', 10)->nullable();
            $table->string('statusPlano', 45);
            $table->integer('quantidade_meses_vigencia');
            $table->integer('dias_trial')->default(0);
            $table->unsignedBigInteger('franqueado_regiao_plano_disponibilizado_id')->index('fk_franqueado_regiao_plano_disponibilizado_id');
            $table->timestamp('data_pagamento')->nullable();
            $table->timestamp('data_cancelamento')->nullable();
            $table->date('data_expiracao')->nullable();
            $table->timestamp('data_cadastro')->useCurrent();
            $table->timestamp('data_atualizacao')->useCurrent();
            $table->softDeletes();
            $table->timestamp('data_ativacao')->nullable();
            $table->text('asaas_assinatura_id')->nullable();
            $table->timestamp('data_contrato')->nullable();
            $table->text('arquivo_original')->nullable();
            $table->text('arquivo_assinado')->nullable();
            $table->text('documento_id_autentique')->nullable();
            $table->text('arquivo_original_autentique')->nullable();
            $table->timestamp('data_assinatura')->nullable();
            $table->integer('tipo_assinatura')->default(4);
            $table->text('titulo_contrato')->nullable();
            $table->integer('status')->nullable();
            $table->integer('status_afiliado')->nullable();
            $table->integer('status_testemunha1')->nullable();
            $table->integer('status_testemunha2')->nullable();
            $table->text('asaas_customer_id')->nullable();
            $table->double('desconto', 10, 2)->default(0.00);
            $table->integer('isTerceirizada')->default(0);
            $table->string('ciclo', 45);
            $table->text('valor_extenso')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plano_assinatura_afiliado_regiao');
    }
}
