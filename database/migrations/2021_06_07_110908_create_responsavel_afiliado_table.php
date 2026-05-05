<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResponsavelAfiliadoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('responsavel_afiliado', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nome')->nullable();
            $table->string('email')->nullable();
            $table->string('numero_documento', 45)->nullable();
            $table->string('CPF', 45)->nullable();
            $table->string('telefone', 45)->nullable();
            $table->string('cargo')->nullable();
            $table->unsignedBigInteger('afiliado_id')->nullable()->index('responsavel_afiliado_afiliado_id_foreign');
            $table->timestamp('data_cadastro')->useCurrent();
            $table->timestamp('data_atualizacao')->useCurrent();
            $table->softDeletes();
            $table->string('rua', 200)->nullable();
            $table->string('numero', 45)->nullable();
            $table->text('complemento')->nullable();
            $table->string('bairro')->nullable();
            $table->string('cidade')->nullable();
            $table->string('estado')->nullable();
            $table->string('cep', 45)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('responsavel_afiliado');
    }
}
