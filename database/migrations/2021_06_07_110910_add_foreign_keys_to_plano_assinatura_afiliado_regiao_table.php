<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToPlanoAssinaturaAfiliadoRegiaoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('plano_assinatura_afiliado_regiao', function (Blueprint $table) {
            $table->foreign('franqueado_regiao_plano_disponibilizado_id', 'fk_franqueado_regiao_plano_disponibilizado_id')->references('id')->on('franqueado_regiao_plano_disponibilizado')->onUpdate('RESTRICT')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('plano_assinatura_afiliado_regiao', function (Blueprint $table) {
            $table->dropForeign('fk_franqueado_regiao_plano_disponibilizado_id');
        });
    }
}
