<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSolicitacaoorcamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solicitacaoorcamentos', function (Blueprint $table) {
            $table->integer('id', true);
            $table->timestamp('dataCriacao')->nullable()->useCurrent();
            $table->integer('idCategoria')->nullable();
            $table->string('nomeCondominio');
            $table->string('nomeSolicitante')->nullable();
            $table->string('emailSolicitante')->nullable();
            $table->string('telefoneSolicitante', 11)->nullable();
            $table->string('endereco', 45)->nullable();
            $table->string('numero', 10)->nullable();
            $table->string('complemento')->nullable();
            $table->string('bairro', 100)->nullable();
            $table->integer('idCidade')->nullable();
            $table->integer('idEstado')->nullable();
            $table->string('cep', 8)->nullable();
            $table->string('tipoServico')->nullable();
            $table->longText('detalhesSolicitacao')->nullable();
            $table->string('ipSolicitante', 50);
            $table->string('nomeResponsavelEmpresa');
            $table->string('nomeEmpresa');
            $table->string('emailEmpresa');
            $table->integer('statusEnvio')->nullable()->default(0);
            $table->timestamp('data_cadastro')->nullable();
            $table->timestamp('data_atualizacao')->nullable();
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
        Schema::dropIfExists('solicitacaoorcamentos');
    }
}
