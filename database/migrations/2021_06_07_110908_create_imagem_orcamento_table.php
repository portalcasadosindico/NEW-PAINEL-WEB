<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImagemOrcamentoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('imagem_orcamento', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('descricao')->nullable();
            $table->text('caminho_imagem');
            $table->unsignedBigInteger('orcamento_id')->index('imagem_orcamento_orcamento_id_foreign');
            $table->timestamp('data_cadastro')->useCurrent();
            $table->timestamp('data_atualizacao')->useCurrent();
            $table->softDeletes();
            $table->longText('imagem_base64')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('imagem_orcamento');
    }
}
