<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsuarioAppTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuario_app', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('email', 45);
            $table->string('senha', 60);
            $table->string('tipo', 45)->comment('sindico/prestador/vistoriador');
            $table->text('token_notification')->nullable();
            $table->timestamp('data_cadastro')->useCurrent();
            $table->timestamp('data_atualizacao')->useCurrent();
            $table->softDeletes();
            $table->timestamp('data_confirmacao')->nullable();
            $table->text('imagem')->nullable();
            $table->integer('isEmail')->nullable()->default(0);
            $table->integer('isFacebook')->nullable()->default(0);
            $table->timestamp('data_aceite_termos')->nullable();
            $table->longText('imagem_base64')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usuario_app');
    }
}
