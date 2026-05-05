<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToVistoriaImagemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vistoria_imagem', function (Blueprint $table) {
            $table->foreign('vistoria_id')->references('id')->on('vistoria')->onUpdate('RESTRICT')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vistoria_imagem', function (Blueprint $table) {
            $table->dropForeign('vistoria_imagem_vistoria_id_foreign');
        });
    }
}
