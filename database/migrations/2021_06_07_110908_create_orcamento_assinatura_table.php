<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrcamentoAssinaturaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orcamento_assinatura', function (Blueprint $table) {
            $table->integer('id', true);
            $table->text('user_id_autentique')->nullable();
            $table->timestamp('signed')->nullable();
            $table->timestamp('viewed')->nullable();
            $table->timestamp('rejected')->nullable();
            $table->text('email');
            $table->text('tipo_usuario');
            $table->timestamp('data_cadastro')->useCurrent();
            $table->integer('orcamento_id');
            $table->text('nome');
            $table->timestamp('data_atualizacao')->useCurrent();
            $table->softDeletes();
            $table->integer('afiliado_id')->nullable();
            $table->integer('sindico_id')->nullable();
            $table->integer('franqueado_id')->nullable();
            $table->text('documento_id_autentique');
            $table->text('public_id');
            $table->text('nome_assinante')->nullable();
            $table->text('short_link')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orcamento_assinatura');
    }
}
