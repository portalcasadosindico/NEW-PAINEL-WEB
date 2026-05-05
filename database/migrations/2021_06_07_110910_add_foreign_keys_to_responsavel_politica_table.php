<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToResponsavelPoliticaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('responsavel_politica', function (Blueprint $table) {
            $table->foreign('politica_privacidade_id')->references('id')->on('politica_privacidade')->onUpdate('RESTRICT')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('responsavel_politica', function (Blueprint $table) {
            $table->dropForeign('responsavel_politica_politica_privacidade_id_foreign');
        });
    }
}
