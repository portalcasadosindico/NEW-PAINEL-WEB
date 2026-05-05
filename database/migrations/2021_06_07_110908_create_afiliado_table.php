<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAfiliadoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('afiliado', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('razao_social')->nullable();
            $table->string('nome_fantasia')->nullable();
            $table->string('telefone', 45)->nullable();
            $table->string('email')->nullable();
            $table->string('cnpj', 45)->nullable();
            $table->string('cartao_cnpj')->nullable();
            $table->string('inscricao_estadual', 45)->nullable();
            $table->string('inscricao_municipal', 45)->nullable();
            $table->string('cep', 20)->nullable();
            $table->string('estado', 80)->nullable();
            $table->string('cidade', 80)->nullable();
            $table->string('bairro', 100)->nullable();
            $table->string('rua')->nullable();
            $table->string('numero', 10)->nullable();
            $table->string('complemento')->nullable();
            $table->string('rumo_atividade')->nullable();
            $table->integer('numero_funcionarios')->nullable();
            $table->string('logo')->nullable()->default('no-image-perfil.png');
            $table->longText('logo_base64')->nullable();
            $table->string('status', 45)->default('ativo')->comment('pendente/ativo/inativo');
            $table->unsignedBigInteger('usuario_app_id')->nullable()->index('afiliado_usuario_app_id_foreign');
            $table->date('data_contrato')->nullable();
            $table->timestamp('data_cadastro')->useCurrent();
            $table->timestamp('data_atualizacao')->useCurrent();
            $table->softDeletes();
            $table->text('asaas_customer_id')->nullable();
            $table->timestamp('data_ativacao')->nullable()->useCurrent();
            $table->text('contrato_social')->nullable();
            $table->text('contrato_gerado')->nullable();
            $table->integer('plano_id')->nullable();
            $table->text('chave')->nullable();
            $table->string('forma_cadastro')->default('site');
            $table->integer('franqueado_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('afiliado');
    }
}
