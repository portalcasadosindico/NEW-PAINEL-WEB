<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCondominioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('condominio', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nome', 60)->nullable();
            $table->string('cep', 45)->nullable();
            $table->string('bairro', 100)->nullable();
            $table->string('endereco')->nullable();
            $table->string('estado')->nullable();
            $table->string('cidade')->nullable();
            $table->string('numero')->nullable();
            $table->string('complemento')->nullable();
            $table->unsignedBigInteger('sindico_id')->index('condominio_sindico_id_foreign');
            $table->unsignedBigInteger('bairro_id')->nullable()->index('condominio_regiao_id_foreign');
            $table->timestamp('data_cadastro')->useCurrent();
            $table->timestamp('data_atualizacao')->useCurrent();
            $table->softDeletes();
            $table->string('cnpj')->nullable();
            $table->text('chave')->nullable();
            $table->string('status', 45)->default('ativo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('condominio');
    }
}
