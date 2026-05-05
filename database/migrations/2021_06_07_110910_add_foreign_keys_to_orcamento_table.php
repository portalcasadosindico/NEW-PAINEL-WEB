<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToOrcamentoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orcamento', function (Blueprint $table) {
            $table->foreign('afiliado_id')->references('id')->on('afiliado')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->foreign('categoria_id')->references('id')->on('categoria')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->foreign('condominio_id')->references('id')->on('condominio')->onUpdate('RESTRICT')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orcamento', function (Blueprint $table) {
            $table->dropForeign('orcamento_afiliado_id_foreign');
            $table->dropForeign('orcamento_categoria_id_foreign');
            $table->dropForeign('orcamento_condominio_id_foreign');
        });
    }
}
