<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParceirosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parceiros', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nome', 50)->nullable();
            $table->string('logo')->nullable();
            $table->string('link')->nullable();
            $table->string('email')->nullable();
            $table->string('nome_responsavel', 100)->nullable();
            $table->string('telefone', 45)->nullable();
            $table->string('status', 45)->default('pendente')->comment('ativo/pendente/inativo');
            $table->timestamp('data_cadastro')->useCurrent();
            $table->timestamp('data_atualizacao')->useCurrent();
            $table->softDeletes();
            $table->integer('franqueado_id')->nullable();
            $table->integer('plano_id')->nullable();
            $table->text('asaas_customer_id')->nullable();
            $table->text('asaas_assinatura_id')->nullable();
            $table->longText('logo_base64')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parceiros');
    }
}
