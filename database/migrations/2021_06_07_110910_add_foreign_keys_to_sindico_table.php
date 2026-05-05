<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToSindicoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sindico', function (Blueprint $table) {
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
        Schema::table('sindico', function (Blueprint $table) {
            $table->dropForeign('sindico_franqueado_id_foreign');
            $table->dropForeign('sindico_usuario_app_id_foreign');
        });
    }
}
