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
        Schema::create('skd', function (Blueprint $table) {
            $table->id('id_skd');
            $table->bigInteger('nik')->nullable();
            $table->string('nama')->nullable();
            $table->string('tmpt_tgl_lahir')->nullable();
            $table->string('jk')->nullable();
            $table->string('pekerjaan')->nullable();
            $table->string('status')->nullable();
            $table->string('agama')->nullable();
            $table->bigInteger('no_kk')->nullable();
            $table->text('alamat_ktp')->nullable();
            $table->text('alamat_skr')->nullable();
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
        Schema::dropIfExists('skd');
    }
};
