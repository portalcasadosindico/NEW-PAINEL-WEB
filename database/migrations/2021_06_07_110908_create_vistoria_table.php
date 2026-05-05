<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVistoriaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vistoria', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('descricao')->nullable();
            $table->date('data_vistoria')->nullable();
            $table->timestamp('data_checkin')->nullable();
            $table->double('latitude_checkin')->nullable();
            $table->double('longitude_checkin')->nullable();
            $table->timestamp('data_checkout')->nullable();
            $table->double('latitude_checkout')->nullable();
            $table->double('longitude_checkout')->nullable();
            $table->unsignedBigInteger('vistoriador_id')->nullable()->index('vistoria_vistoriador_id_foreign');
            $table->unsignedBigInteger('orcamento_id')->index('vistoria_orcamento_id_foreign');
            $table->timestamp('data_cadastro')->useCurrent();
            $table->timestamp('data_atualizacao')->useCurrent();
            $table->softDeletes();
            $table->string('status', 45)->default('pendente');
            $table->integer('show_data_agendamento_sindico')->default(1);
            $table->string('forma_cadastro')->nullable();
            $table->string('forma_checkin')->nullable();
            $table->time('hora_vistoria')->nullable();
            $table->integer('checkin_automatico')->default(1);
            $table->integer('show_data_checkin_checkout_sindico')->default(1);
            $table->text('descricao_vistoriador')->nullable();
            $table->timestamp('data_aceite')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vistoria');
    }
}
