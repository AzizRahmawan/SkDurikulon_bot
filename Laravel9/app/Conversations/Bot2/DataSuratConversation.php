<?php

namespace App\Conversations\Bot2;

use App\Models\Skp;
use App\Models\Sktm;
use Illuminate\Foundation\Inspiring;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Outgoing\Question as BotManQuestion;
use BotMan\BotMan\Messages\Incoming\Answer as BotManAnswer;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Test;

class DataSuratConversation extends Conversation
{

    public function run()
    {
        $this->viewDataSurat();
        // $this->cariLagi();
    }
    public function viewDataSurat()
    {
        $question = Question::create('Silahkan Pilih Menu Data Surat Di Bawah!!')
            ->fallback('Maaf Perintah Tidak Ada')
            ->callbackId('ask_reason')
            ->addButtons([
                Button::create('Data SKTM')->value('/data_sktm'),
                Button::create('Data SK Penduduk!')->value('/data_skp'),
                Button::create('Data SK Domisili!')->value('/data_skd'),
            ]);

        $this->ask($question, function (Answer $answer) {
        });
    }
}
