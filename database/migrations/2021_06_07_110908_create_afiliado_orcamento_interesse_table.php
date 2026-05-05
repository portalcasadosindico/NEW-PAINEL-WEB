<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAfiliadoOrcamentoInteresseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('afiliado_orcamento_interesse', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('afiliado_id')->index('fk_afiliado_has_orcamento_afiliado1_idx');
            $table->integer('orcamento_id')->index('fk_afiliado_has_orcamento_orcamento1_idx');
            $table->integer('interessado')->default(1);
            $table->timestamp('data_cadastro')->useCurrent();
            $table->timestamp('data_atualizacao')->useCurrent();
            $table->integer('nao_interessante')->default(0);
            $table->softDeletes();
            $table->integer('descartado')->default(0);
            $table->float('valor_orcamento', 10)->nullable();
            $table->integer('descartado_sindico')->default(-1);
            $table->integer('descartado_afiliado')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('afiliado_orcamento_interesse');
    }
}
