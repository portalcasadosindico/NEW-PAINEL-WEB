<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAfiliadoCategoriaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('afiliado_categoria', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('afiliado_id')->index('afiliado_categoria_afiliado_id_foreign');
            $table->unsignedBigInteger('categoria_id')->index('afiliado_categoria_categoria_id_foreign');
            $table->timestamp('data_cadastro')->useCurrent();
            $table->timestamp('data_atualizacao')->useCurrent();
            $table->softDeletes();
            $table->string('status', 45)->default('aprovado');
            $table->text('motivo_reprovado')->nullable();
            $table->integer('franqueado_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('afiliado_categoria');
    }
}
