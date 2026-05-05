<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlanoDisponivelFranqueadoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plano_disponivel_franqueado', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nome');
            $table->text('descricao')->nullable();
            $table->decimal('valor', 10);
            $table->decimal('valor_comissao', 10)->nullable();
            $table->integer('statusPlano');
            $table->integer('quantidade_meses_vigencia')->default(1);
            $table->integer('dias_trial')->default(0);
            $table->unsignedBigInteger('usuario_sistema_admin_id')->index('plano_disponivel_franqueado_usuario_sistema_admin_id_foreign');
            $table->unsignedBigInteger('regiao_id')->nullable()->index('plano_disponivel_franqueado_regiao_id_foreign');
            $table->timestamp('data_cadastro')->useCurrent();
            $table->timestamp('data_atualizacao')->useCurrent();
            $table->softDeletes();
            $table->integer('tipo')->nullable();
            $table->text('valor_plano_extenso')->nullable();
            $table->double('desconto', 10, 2)->default(0.00);
            $table->integer('isTerceirizada')->default(0);
            $table->string('ciclo', 45);
            $table->text('valor_extenso')->nullable();
            $table->integer('is_public')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plano_disponivel_franqueado');
    }
}
