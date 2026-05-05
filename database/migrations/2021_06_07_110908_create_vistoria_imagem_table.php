<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVistoriaImagemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vistoria_imagem', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('descricao')->nullable();
            $table->text('caminho_imagem');
            $table->unsignedBigInteger('vistoria_id')->index('vistoria_imagem_vistoria_id_foreign');
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
        Schema::dropIfExists('vistoria_imagem');
    }
}
