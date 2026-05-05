<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrcamentoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orcamento', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('nome')->nullable();
            $table->text('descricao')->nullable();
            $table->tinyInteger('status')->nullable()->default(1);
            $table->tinyInteger('status_sindico')->nullable()->default(1);
            $table->tinyInteger('status_afiliado')->nullable();
            $table->unsignedBigInteger('condominio_id')->index('orcamento_condominio_id_foreign');
            $table->unsignedBigInteger('afiliado_id')->nullable()->index('orcamento_afiliado_id_foreign');
            $table->unsignedBigInteger('categoria_id')->index('orcamento_categoria_id_foreign');
            $table->unsignedBigInteger('regiao_id')->nullable()->index('orcamento_regiao_id_foreign');
            $table->timestamp('data_cadastro')->useCurrent();
            $table->softDeletes();
            $table->text('contrato')->nullable();
            $table->string('documento_id_autentique', 60)->nullable();
            $table->text('contrato_assinado')->nullable();
            $table->text('contrato_original')->nullable();
            $table->double('valor_contrato')->nullable();
            $table->text('titulo_contrato')->nullable();
            $table->integer('formato_contrato_atual')->default(4);
            $table->text('motivo_cancelamento')->nullable();
            $table->integer('urgente')->default(0);
            $table->float('avaliacao', 3, 1)->nullable();
            $table->timestamp('data_atualizacao')->useCurrent();
            $table->timestamp('data_inicio_operacao')->nullable();
            $table->timestamp('data_fim_operacao')->nullable();
            $table->string('modo', 45)->default('debug');
            $table->string('status_testemunha1')->nullable();
            $table->string('status_testemunha2')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orcamento');
    }
}
