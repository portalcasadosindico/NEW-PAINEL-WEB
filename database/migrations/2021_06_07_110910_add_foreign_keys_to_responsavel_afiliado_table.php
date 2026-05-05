<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToResponsavelAfiliadoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('responsavel_afiliado', function (Blueprint $table) {
            $table->foreign('afiliado_id')->references('id')->on('afiliado')->onUpdate('RESTRICT')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('responsavel_afiliado', function (Blueprint $table) {
            $table->dropForeign('responsavel_afiliado_afiliado_id_foreign');
        });
    }
}
