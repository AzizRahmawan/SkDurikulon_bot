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


class DataSuratConversation extends Conversation
{
    public $para;
    public $id_user;

    public function askMenuSurat()
    {
        $question = Question::create('Silahkan Pilih Jenis Surat Di Bawah!!')
            ->fallback('Maaf Perintah Tidak Ada')
            ->callbackId('ask_reason')
            ->addButtons([
                Button::create('Data SKTM')->value('/data_sktm'),
                Button::create('Data SK Penduduk!')->value('/data_skp'),
                Button::create('Data SK Domisili!')->value('/data_skd'),
            ]);

        $this->ask($question, function (Answer $answer) {
            // Detect if button was clicked:
        });
    }
    /**
     * Start the conversation
     */
    public function run()
    {
        $this->askMenuSurat();
        // $this->cariLagi();
    }
}
