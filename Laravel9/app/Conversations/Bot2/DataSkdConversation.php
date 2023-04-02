<?php

namespace App\Conversations\Bot2;

use App\Models\skd;
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

class DataSkdConversation extends Conversation
{

    public function run()
    {
        $this->viewDataskd();
        // $this->cariLagi();
    }
    public function viewDataskd()
    {
        $user = $this->bot->getUser();
        //if (skd::where('id_user',1)->exists()){
        if (skd::where('id_user',$user->getId())->exists()){
            $dataSkd = Skd::where('id_user',$user->getId())->get();
            //$dataskd = skd::where('id_user',1)->get();
            $message = '';
            foreach($dataSkd as $skd){
                $message .= "Berikut Data skd Anda :" . PHP_EOL;
                $message .= "Nama  : " . $skd->nama . PHP_EOL;
                $message .= "Tanggal Lahir   : " . $skd->tmpt_tgl_lahir . PHP_EOL;
                $message .= "No KK : " . $skd->no_kk . PHP_EOL;
                $message .= "NIK      : " . $skd->nik . PHP_EOL;
                $message .= "Jenis Kelamin  : " . $skd->jk . PHP_EOL;
                $message .= "Pekerjaan     : " . $skd->pekerjaan . PHP_EOL;
                $message .= "Status    : " . $skd->status . PHP_EOL;
                $message .= "Agama    : " . $skd->agama . PHP_EOL;
                $message .= "Pendidikan    : " . $skd->pendidikan . PHP_EOL;
                $message .= "Alamat pada KTP      : " . $skd->alamat_ktp . PHP_EOL;
                $message .= "Alamat Saat Ini      : " . $skd->alamat_skr . PHP_EOL;
                $this->bot->reply($message);
                $this->say("Silahkan Klik Link Dibawah ini Untuk Mencetak Surat!!" . PHP_EOL ."https://www.skdurikulon.myhost.id/skd/" . $skd->id_skd . "/" . $skd->id_user2 . "/" . $skd->nik);
            }
            $this->askMenuActionskd();
        }else{
            $this->bot->reply('Maaf Data Tidak Ditemukan Silahkan Buat Terlebih Dahulu /buat_surat');
        }
    }
    public function askMenuActionskd(){
        $question = Question::create('Berikut Menu Untuk Edit Data SK Domisili Anda!!')
        ->fallback('Maaf Perintah Tidak Ada')
        ->callbackId('ask_reason')
        ->addButtons([
            Button::create('Edit SK Domisili')->value('/edit_skd'),
        ]);

        $this->ask($question, function (Answer $answer) {
            // Detect if button was clicked:
        });
    }
}
