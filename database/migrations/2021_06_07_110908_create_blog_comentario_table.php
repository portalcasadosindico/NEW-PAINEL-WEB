<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogComentarioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_comentario', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('titulo')->nullable();
            $table->text('descricao');
            $table->timestamp('data_cadastro')->useCurrent();
            $table->timestamp('data_atualizacao')->useCurrent();
            $table->softDeletes();
            $table->integer('blog_id');
            $table->string('autor')->nullable();
            $table->integer('usuario_app_id')->nullable();
            $table->integer('blog_comentario_pai_id')->nullable();
            $table->string('status', 45)->default('pendente');
            $table->string('email')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blog_comentario');
    }
}
