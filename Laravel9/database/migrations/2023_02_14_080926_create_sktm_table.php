<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sktm', function (Blueprint $table) {
            $table->id('id_sktm');
            $table->string('nama')->nullable();
            $table->bigInteger('nik')->nullable();
            $table->string('tmpt_tgl_lahir')->nullable();
            $table->string('jk')->nullable();
            $table->string('nama_kepala')->nullable();
            $table->bigInteger('nik_kepala')->nullable();
            $table->text('alamat_ktp')->nullable();
            $table->text('keperluan')->nullable();
            $table->integer('id_user')->nullable();
            $table->integer('id_user2')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sktm');
    }
};
