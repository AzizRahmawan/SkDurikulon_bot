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
        Schema::create('surat', function (Blueprint $table) {
            $table->id('id_surat');
            $table->integer('id_user')->nullable();
            $table->string('nama')->nullable();
            $table->string('nik')->nullable();
            $table->string('tgl_lahir')->nullable();
            $table->string('jk')->nullable();
            $table->string('nama_kk')->nullable();
            $table->string('nik_kk')->nullable();
            $table->text('alamat')->nullable();
            $table->text('keperluan')->nullable();
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
        Schema::dropIfExists('surat');
    }
};
