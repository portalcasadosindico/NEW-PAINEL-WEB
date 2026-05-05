<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContratoSocialTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contrato_social', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('status', 45)->default('pendente')->comment('pendente/aceito/recusado');
            $table->longText('arquivo');
            $table->unsignedBigInteger('afiliado_id')->index('contrato_social_afiliado_id_foreign');
            $table->timestamp('data_cadastro')->useCurrent();
            $table->timestamp('data_atualizacao')->useCurrent();
            $table->softDeletes();
            $table->text('motivo_reprovado')->nullable();
            $table->longText('arquivo_base64')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contrato_social');
    }
}
