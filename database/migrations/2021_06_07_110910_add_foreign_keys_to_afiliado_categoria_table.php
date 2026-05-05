<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToAfiliadoCategoriaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('afiliado_categoria', function (Blueprint $table) {
            $table->foreign('afiliado_id')->references('id')->on('afiliado')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->foreign('categoria_id')->references('id')->on('categoria')->onUpdate('RESTRICT')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('afiliado_categoria', function (Blueprint $table) {
            $table->dropForeign('afiliado_categoria_afiliado_id_foreign');
            $table->dropForeign('afiliado_categoria_categoria_id_foreign');
        });
    }
}
