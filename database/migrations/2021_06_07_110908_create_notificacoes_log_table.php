<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificacoesLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notificacoes_log', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('notificacoes_id');
            $table->integer('usuario_app_id');
            $table->text('email')->nullable();
            $table->integer('statusEnvioEmail')->default(0);
            $table->integer('statusEnvioNotificacao')->default(0);
            $table->timestamp('data_cadastro')->useCurrent();
            $table->timestamp('data_atualizacao')->useCurrent();
            $table->softDeletes();
            $table->string('tipo', 45);
            $table->string('nome');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notificacoes_log');
    }
}
