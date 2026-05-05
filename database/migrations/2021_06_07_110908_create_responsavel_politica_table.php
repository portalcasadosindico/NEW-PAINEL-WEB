<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResponsavelPoliticaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('responsavel_politica', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nome', 45);
            $table->string('email', 45);
            $table->string('telefone', 45);
            $table->string('cpf', 45);
            $table->timestamp('data_cadastro')->useCurrent();
            $table->unsignedBigInteger('politica_privacidade_id')->index('politica_privacidade_id_foreign');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('responsavel_politica');
    }
}
