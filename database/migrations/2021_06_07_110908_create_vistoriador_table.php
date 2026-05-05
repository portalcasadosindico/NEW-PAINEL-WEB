<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVistoriadorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vistoriador', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nome', 45);
            $table->unsignedBigInteger('usuario_app_id')->index('vistoriador_usuario_app_id_foreign');
            $table->timestamp('data_cadastro')->useCurrent();
            $table->timestamp('data_atualizacao')->useCurrent();
            $table->softDeletes();
            $table->unsignedBigInteger('franqueado_id')->nullable()->index('vistoriador_franqueado_id_foreign');
            $table->text('dados_acesso_condominio')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vistoriador');
    }
}
