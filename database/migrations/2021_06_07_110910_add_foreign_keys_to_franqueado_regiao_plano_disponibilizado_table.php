<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToFranqueadoRegiaoPlanoDisponibilizadoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('franqueado_regiao_plano_disponibilizado', function (Blueprint $table) {
            $table->foreign('franqueado_regiao_id', 'fk_franqueado_regiao_id')->references('id')->on('franqueado_regiao')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->foreign('plano_disponivel_franqueado_id', 'fk_plano_disponivel_franqueado_id')->references('id')->on('plano_disponivel_franqueado')->onUpdate('RESTRICT')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('franqueado_regiao_plano_disponibilizado', function (Blueprint $table) {
            $table->dropForeign('fk_franqueado_regiao_id');
            $table->dropForeign('fk_plano_disponivel_franqueado_id');
        });
    }
}
