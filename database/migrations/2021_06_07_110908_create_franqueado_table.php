<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFranqueadoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('franqueado', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('nome');
            $table->text('email')->nullable();
            $table->text('senha')->nullable();
            $table->text('cnpj')->nullable();
            $table->text('inscricao_estadual')->nullable();
            $table->text('inscricao_municipal')->nullable();
            $table->text('cpf_responsavel')->nullable();
            $table->text('rg_responsavel')->nullable();
            $table->text('profissao_responsavel')->nullable();
            $table->text('telefone_responsavel')->nullable();
            $table->text('cep')->nullable();
            $table->text('estado')->nullable();
            $table->text('cidade')->nullable();
            $table->text('bairro')->nullable();
            $table->text('rua')->nullable();
            $table->text('token_asaas_producao')->nullable();
            $table->text('token_asaas_debug')->nullable();
            $table->timestamp('data_cadastro')->useCurrent();
            $table->timestamp('data_atualizacao')->useCurrent();
            $table->softDeletes();
            $table->text('token_autentique')->nullable();
            $table->text('contrato_social')->nullable();
            $table->text('cartao_cnpj')->nullable();
            $table->text('nome_responsavel')->nullable();
            $table->text('foro')->nullable();
            $table->text('razao_social')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('franqueado');
    }
}
