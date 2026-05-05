<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToBairroTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bairro', function (Blueprint $table) {
            $table->foreign('cidade_id')->references('id')->on('cidade')->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->foreign('regiao_id')->references('id')->on('regiao')->onUpdate('RESTRICT')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bairro', function (Blueprint $table) {
            $table->dropForeign('bairro_cidade_id_foreign');
            $table->dropForeign('bairro_regiao_id_foreign');
        });
    }
}
