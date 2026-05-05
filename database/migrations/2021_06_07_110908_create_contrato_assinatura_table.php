<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContratoAssinaturaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contrato_assinatura', function (Blueprint $table) {
            $table->integer('id', true);
            $table->text('user_id_autentique')->nullable();
            $table->timestamp('signed')->nullable();
            $table->timestamp('viewed')->nullable();
            $table->timestamp('rejected')->nullable();
            $table->text('email');
            $table->text('tipo_usuario');
            $table->timestamp('data_cadastro')->useCurrent();
            $table->text('nome');
            $table->timestamp('data_atualizacao')->useCurrent();
            $table->softDeletes();
            $table->integer('afiliado_id')->nullable();
            $table->integer('franqueado_id')->nullable();
            $table->text('documento_id_autentique');
            $table->text('public_id')->nullable();
            $table->integer('plano_assinatura_afiliado_regiao_id')->nullable();
            $table->integer('regiao_id')->nullable();
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
        Schema::dropIfExists('contrato_assinatura');
    }
}
