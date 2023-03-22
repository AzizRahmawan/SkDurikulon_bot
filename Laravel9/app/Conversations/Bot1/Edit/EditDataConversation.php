<?php

namespace App\Conversations\Bot1\Edit;

use App\Models\Penduduk;
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
use Carbon\Carbon;

class EditDataConversation extends Conversation
{
    public $para;
    public $id_user;
    public $nik_lama;

    public function run()
    {
        //$this->askKonfirmasi();
        $this->askMenuEdit();
    }
    private function askMenuEdit()
    {

        $question = Question::create('Silahkan Pilih Menu Di Bawah!!')
            ->fallback('Maaf Perintah Tidak Ada')
            ->callbackId('ask_reason')
            ->addButtons([
                Button::create('Edit Nama')->value('/edit_nama'),
                Button::create('Edit NIK')->value('/edit_nik'),
                Button::create('Edit Kepala Keluarga')->value('/edit_kepala'),
                Button::create('Edit No KK')->value('/edit_no_kk'),
                Button::create('Edit Tempat Tgl Lahir')->value('/edit_tgl_lahir'),
                Button::create('Edit Pekerjaan')->value('/edit_pekerjaan'),
                Button::create('Edit Status Pernikahan')->value('/edit_status'),
                Button::create('Edit Agama')->value('/edit_agama'),
                Button::create('Edit Pendidikan')->value('/edit_pendidikan'),
                Button::create('Edit Alamat')->value('/edit_alamat'),
            ]);

            return $this->ask($question, function (Answer $answer) {

            });
    }
}
