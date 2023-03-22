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
        Schema::create('edit_pendidikan_penduduk', function (Blueprint $table) {
            $table->id();
            $table->string('nik_lama')->nullable();
            $table->string('pendidikan_baru')->nullable();
            $table->integer('id_user')->nullable();
            $table->integer('id_user2')->nullable();
            $table->string('nama_user')->nullable();
            $table->string('status_acc')->nullable();
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
        Schema::dropIfExists('edit_pendidikan_penduduk');
    }
};
