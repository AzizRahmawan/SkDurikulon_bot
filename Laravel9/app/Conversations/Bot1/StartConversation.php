<?php

namespace App\Conversations\Bot1;

use Illuminate\Foundation\Inspiring;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Outgoing\Question as BotManQuestion;
use BotMan\BotMan\Messages\Incoming\Answer as BotManAnswer;
use Illuminate\Support\Facades\DB;


class StartConversation extends Conversation
{
    public $para;
    public $id_user;

    public function run()
    {
        $this->say('Selamat Datang di Sistem Pelayanan Surat Keterangan Durikulon Berbasis Bot.');
        $this->say('Setelah Mencoba Sistem/Bot Saya Mohon Bantuannya Untuk Mengisi Kuesinor Berikut:');
        $this->say('https://docs.google.com/forms/d/e/1FAIpQLSfK1QQEWZwAq8HInlYtZLX2iXge_WsHYrv5rhwvMoCx2Ru1uw/viewform?usp=sf_link');
        $this->say('Silahkan Pilih Menu Tutorial untuk melihat cara menggunakan Bot.');
        $this->askMenuStart();
        // $this->cariLagi();
    }

    public function askMenuStart()
    {
        $question = Question::create('Silahkan Pilih Menu Di Bawah.')
            ->fallback('Maaf Perintah Tidak Ada')
            ->callbackId('ask_reason')
            ->addButtons([
                Button::create('Tutorial')->value('/tutorial'),
                Button::create('Buat Surat')->value('/buat_surat'),
                Button::create('Cek NIK')->value('/cek_nik'),
                Button::create('Edit Data Penduduk')->value('/edit_data'),
                Button::create('Tentang')->value('/tentang'),
            ]);

        $this->ask($question, function (Answer $answer) {
            // Detect if button was clicked:
        });
    }

}
