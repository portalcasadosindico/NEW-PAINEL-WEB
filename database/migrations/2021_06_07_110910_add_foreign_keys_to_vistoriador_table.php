<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToVistoriadorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vistoriador', function (Blueprint $table) {
            $table->foreign('franqueado_id')->references('id')->on('franqueado')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->foreign('usuario_app_id')->references('id')->on('usuario_app')->onUpdate('RESTRICT')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vistoriador', function (Blueprint $table) {
            $table->dropForeign('vistoriador_franqueado_id_foreign');
            $table->dropForeign('vistoriador_usuario_app_id_foreign');
        });
    }
}
