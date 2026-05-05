<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartaoCnpjTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cartao_cnpj', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('status', 45)->default('pendente')->comment('pendente/aprovado/recusado');
            $table->longText('arquivo');
            $table->unsignedBigInteger('afiliado_id')->index('cartao_cnpj_afiliado_id_foreign');
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
        Schema::dropIfExists('cartao_cnpj');
    }
}
