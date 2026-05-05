<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFranqueadoRegiaoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('franqueado_regiao', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('status', 45)->default('ativo')->comment('inativo');
            $table->unsignedBigInteger('franqueado_id')->index('franqueado_regiao_franqueado_id_foreign');
            $table->unsignedBigInteger('regiao_id')->index('franqueado_regiao_regiao_id_foreign');
            $table->unsignedBigInteger('usuario_sistema_admin_id')->index('franqueado_regiao_usuario_sistema_admin_id_foreign');
            $table->date('data_inicio_atividade')->nullable();
            $table->date('data_fim_atividade')->nullable();
            $table->timestamp('data_cadastro')->useCurrent();
            $table->timestamp('data_atualizacao')->useCurrent();
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
        Schema::dropIfExists('franqueado_regiao');
    }
}
