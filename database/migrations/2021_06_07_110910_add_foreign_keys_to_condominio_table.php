<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToCondominioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('condominio', function (Blueprint $table) {
            $table->foreign('bairro_id')->references('id')->on('bairro')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->foreign('sindico_id')->references('id')->on('sindico')->onUpdate('RESTRICT')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('condominio', function (Blueprint $table) {
            $table->dropForeign('condominio_bairro_id_foreign');
            $table->dropForeign('condominio_sindico_id_foreign');
        });
    }
}
