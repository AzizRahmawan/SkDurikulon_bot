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


class TutorialConversation extends Conversation
{
    public $para;
    public $id_user;

    public function tutorialBot()
    {
        $message = '';
        $message .= "Berikut Link Video Cara Penggunaan Bot:". PHP_EOL;
        $message .= "https://youtu.be/O51gpol2zXE";
        $this->bot->reply($message);
    }
    /**
     * Start the conversation
     */
    public function run()
    {
        $this->tutorialBot();
        // $this->cariLagi();
    }
}
