<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToImagemOrcamentoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('imagem_orcamento', function (Blueprint $table) {
            $table->foreign('orcamento_id')->references('id')->on('orcamento')->onUpdate('RESTRICT')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('imagem_orcamento', function (Blueprint $table) {
            $table->dropForeign('imagem_orcamento_orcamento_id_foreign');
        });
    }
}
