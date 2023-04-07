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


class TentangConversation extends Conversation
{
    public $para;
    public $id_user;

    public function tutorialBot()
    {
        $message = '';
        $this->bot->reply("SK Durikulon Bot merupakan sebuah sistem yang dibuat oleh Abdul Aziz Rahmawan sebagai salah satu syarat menyelesaikan pendidikan di Politeknik Negeri Jember. Bot ini diharapkan dapat membantu memudah masyarakat Durikulon dalam mengajukan pembuatan Surat Keterangan.");
        $message .= 'Saya Mohon Bantuannya Untuk Mengisi Kuesinor Kepuasan Terhadap Bot Berikut:'. PHP_EOL;
        $message .= 'https://docs.google.com/forms/d/e/1FAIpQLSfK1QQEWZwAq8HInlYtZLX2iXge_WsHYrv5rhwvMoCx2Ru1uw/viewform?usp=sf_link';
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
