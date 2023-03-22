<?php

namespace App\Conversations\Bot2;

use Illuminate\Foundation\Inspiring;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Outgoing\Question as BotManQuestion;
use BotMan\BotMan\Messages\Incoming\Answer as BotManAnswer;
use Illuminate\Support\Facades\DB;


class EditSuratConversation extends Conversation
{
    public $para;
    public $id_user;

    public function askMenuEdit()
    {
        $question = Question::create('Silahkan Pilih Menu Di Bawah!!')
            ->fallback('Maaf Perintah Tidak Ada')
            ->callbackId('ask_reason')
            ->addButtons([
                Button::create('Edit SKTM')->value('/edit_sktm'),
                Button::create('Edit SK Penduduk')->value('/edit_skp'),
                Button::create('Edit SK Domisili')->value('/edit_skd'),
            ]);

        return $this->ask($question, function (Answer $answer) {

        });
    }
    /**
     * Start the conversation
     */
    public function run()
    {
        $this->askMenuEdit();
        // $this->cariLagi();
    }
}
