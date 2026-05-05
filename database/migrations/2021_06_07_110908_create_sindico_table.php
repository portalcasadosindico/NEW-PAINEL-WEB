<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSindicoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sindico', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('nome');
            $table->text('CPF')->nullable();
            $table->text('numero_documento')->nullable();
            $table->text('telefone')->nullable();
            $table->unsignedBigInteger('usuario_app_id')->nullable()->index('sindico_usuario_app_id_foreign');
            $table->timestamp('data_cadastro')->useCurrent();
            $table->timestamp('data_atualizacao')->useCurrent();
            $table->softDeletes();
            $table->unsignedBigInteger('franqueado_id')->nullable()->index('sindico_franqueado_id_foreign');
            $table->date('data_inicio_mandato')->nullable();
            $table->date('data_fim_mandato')->nullable();
            $table->string('email')->nullable();
            $table->string('forma_cadastro', 60)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sindico');
    }
}
