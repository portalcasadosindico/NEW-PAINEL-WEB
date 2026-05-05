<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContratoRegiaoAfiliadoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contrato_regiao_afiliado', function (Blueprint $table) {
            $table->integer('id', true);
            $table->text('user_id_autentique')->nullable();
            $table->timestamp('signed')->nullable();
            $table->timestamp('viewed')->nullable();
            $table->timestamp('rejected')->nullable();
            $table->text('email');
            $table->timestamp('data_cadastro')->useCurrent();
            $table->integer('regiao_afiliado_id');
            $table->text('nome');
            $table->timestamp('data_atualizacao')->useCurrent();
            $table->softDeletes();
            $table->text('documento_id_autentique')->nullable();
            $table->text('public_id')->nullable();
            $table->integer('tipo_assinatura')->comment('1=>Assinado pelo autentique; 2=>Conferido pelo franqueado');
            $table->text('arquivo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contrato_regiao_afiliado');
    }
}
