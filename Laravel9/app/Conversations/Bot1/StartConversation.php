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
        $this->askMenuStart();
        // $this->cariLagi();
    }

    public function askMenuStart()
    {
        $question = Question::create('Silahkan Pilih Menu Di Bawah!!')
            ->fallback('Maaf Perintah Tidak Ada')
            ->callbackId('ask_reason')
            ->addButtons([
                Button::create('Buat Surat')->value('/buat_surat'),
                //Button::create('Data Surat')->value('/data_surat'),
                Button::create('Tutorial')->value('/tutorial'),
                Button::create('Tentang')->value('/tentang'),
            ]);

        $this->ask($question, function (Answer $answer) {
            // Detect if button was clicked:
        });
    }

}
