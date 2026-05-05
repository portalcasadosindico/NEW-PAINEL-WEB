<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfiguracaoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configuracao', function (Blueprint $table) {
            $table->integer('id', true);
            $table->text('link_android')->nullable();
            $table->text('link_ios')->nullable();
            $table->text('link_facebook')->nullable();
            $table->timestamp('data_cadastro')->useCurrent();
            $table->timestamp('data_atualizacao')->useCurrent();
            $table->softDeletes();
            $table->text('versao_app_android')->nullable();
            $table->text('versao_app_ios')->nullable();
            $table->text('nome_empresa')->nullable();
            $table->text('endereco')->nullable();
            $table->text('logo')->nullable();
            $table->text('cnpj')->nullable();
            $table->double('juros')->nullable();
            $table->double('multa')->nullable();
            $table->integer('dias_inadimplencia_bloqueio')->nullable();
            $table->text('foro')->nullable();
            $table->text('api_key_fcm');
            $table->text('url_admin');
            $table->text('url_site');
            $table->string('modus_operandi', 45)->default('debug');
            $table->string('modus_operandi_pin', 60);
            $table->text('sobre')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('configuracao');
    }
}
