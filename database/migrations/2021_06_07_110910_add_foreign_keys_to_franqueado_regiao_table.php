<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToFranqueadoRegiaoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('franqueado_regiao', function (Blueprint $table) {
            $table->foreign('franqueado_id')->references('id')->on('franqueado')->onUpdate('RESTRICT')->onDelete('RESTRICT');
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
        Schema::table('franqueado_regiao', function (Blueprint $table) {
            $table->dropForeign('franqueado_regiao_franqueado_id_foreign');
            $table->dropForeign('franqueado_regiao_regiao_id_foreign');
            $table->dropForeign('franqueado_regiao_usuario_sistema_admin_id_foreign');
        });
    }
}
