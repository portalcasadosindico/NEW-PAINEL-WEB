<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToPlanoDisponivelFranqueadoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('plano_disponivel_franqueado', function (Blueprint $table) {
            $table->foreign('regiao_id')->references('id')->on('regiao')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->foreign('usuario_sistema_admin_id')->references('id')->on('usuario_sistema_admin')->onUpdate('RESTRICT')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('plano_disponivel_franqueado', function (Blueprint $table) {
            $table->dropForeign('plano_disponivel_franqueado_regiao_id_foreign');
            $table->dropForeign('plano_disponivel_franqueado_usuario_sistema_admin_id_foreign');
        });
    }
}
