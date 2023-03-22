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
        Schema::create('penduduk', function (Blueprint $table) {
            $table->id('nik');
            $table->string('nama')->nullable();
            $table->string('tmpt_tgl_lahir')->nullable();
            $table->string('jk')->nullable();
            $table->string('pekerjaan')->nullable();
            $table->string('status')->nullable();
            $table->string('agama')->nullable();
            $table->string('pendidikan')->nullable();
            $table->bigInteger('no_kk')->nullable();
            $table->bigInteger('nik_kepala')->nullable();
            $table->string('nama_kepala')->nullable();
            $table->text('alamat_ktp')->nullable();
            //$table->text('alamat_skr')->nullable();
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
        Schema::dropIfExists('penduduk');
    }
};
