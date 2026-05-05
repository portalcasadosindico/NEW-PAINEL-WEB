<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificacoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notificacoes', function (Blueprint $table) {
            $table->integer('id', true);
            $table->text('titulo');
            $table->text('corpo');
            $table->timestamp('data_cadastro')->useCurrent();
            $table->integer('isSendEmail')->default(0);
            $table->integer('isSendNotification')->default(0);
            $table->softDeletes();
            $table->timestamp('data_atualizacao')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notificacoes');
    }
}
