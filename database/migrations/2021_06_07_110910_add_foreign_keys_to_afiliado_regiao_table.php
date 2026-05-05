<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToAfiliadoRegiaoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('afiliado_regiao', function (Blueprint $table) {
            $table->foreign('afiliado_id')->references('id')->on('afiliado')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->foreign('plano_assinatura_afiliado_regiao_id')->references('id')->on('plano_assinatura_afiliado_regiao')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->foreign('regiao_id')->references('id')->on('regiao')->onUpdate('RESTRICT')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('afiliado_regiao', function (Blueprint $table) {
            $table->dropForeign('afiliado_regiao_afiliado_id_foreign');
            $table->dropForeign('afiliado_regiao_plano_assinatura_afiliado_regiao_id_foreign');
            $table->dropForeign('afiliado_regiao_regiao_id_foreign');
        });
    }
}
