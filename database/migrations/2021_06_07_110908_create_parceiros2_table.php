<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParceiros2Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parceiros2', function (Blueprint $table) {
            $table->integer('id');
            $table->integer('idPlano')->nullable();
            $table->string('nomeParceiro', 50)->nullable();
            $table->string('logoParceiro')->nullable();
            $table->string('linkParceiro')->nullable();
            $table->string('emailParceiro')->nullable();
            $table->string('nomeContato', 100)->nullable();
            $table->string('telefoneContato', 11)->nullable();
            $table->integer('statusParceiro')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parceiros2');
    }
}
