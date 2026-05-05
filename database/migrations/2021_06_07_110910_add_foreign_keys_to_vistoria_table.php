<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToVistoriaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vistoria', function (Blueprint $table) {
            $table->foreign('orcamento_id')->references('id')->on('orcamento')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->foreign('vistoriador_id')->references('id')->on('vistoriador')->onUpdate('RESTRICT')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vistoria', function (Blueprint $table) {
            $table->dropForeign('vistoria_orcamento_id_foreign');
            $table->dropForeign('vistoria_vistoriador_id_foreign');
        });
    }
}
