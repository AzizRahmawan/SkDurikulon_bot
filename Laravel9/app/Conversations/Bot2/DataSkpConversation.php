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

class DataSkpConversation extends Conversation
{

    public function run()
    {
        $this->viewDataSkp();
        // $this->cariLagi();
    }
    public function viewDataSkp()
    {
        $user = $this->bot->getUser();
        //if (Skp::where('id_user',1)->exists()){
        if (Skp::where('id_user',$user->getId())->exists()){
            $dataSkp = Skp::where('id_user',$user->getId())->get();
            //$dataSkp = Skp::where('id_user',1)->get();
            $message = '';
            foreach($dataSkp as $skp){
                $message .= "Berikut Data Skp Anda :" . PHP_EOL;
                $message .= "Nama  : " . $skp->nama . PHP_EOL;
                $message .= "Tanggal Lahir   : " . $skp->tmpt_tgl_lahir . PHP_EOL;
                $message .= "No KK : " . $skp->no_kk . PHP_EOL;
                $message .= "NIK      : " . $skp->nik . PHP_EOL;
                $message .= "Jenis Kelamin  : " . $skp->jk . PHP_EOL;
                $message .= "Pekerjaan     : " . $skp->pekerjaan . PHP_EOL;
                $message .= "Status    : " . $skp->status . PHP_EOL;
                $message .= "Agama    : " . $skp->agama . PHP_EOL;
                $message .= "Pendidikan    : " . $skp->pendidikan . PHP_EOL;
                $message .= "Alamat      : " . $skp->alamat_skr . PHP_EOL;
                $this->bot->reply($message);
                $this->say("Silahkan Klik Link Dibawah ini Untuk Mencetak Surat!!" . PHP_EOL ."https://www.skdurikulon.myhost.id/skp/" . $skp->id_skp . "/" . $skp->id_user2 . "/" . $skp->nik);
            }
            $this->askMenuActionSkp();
        }else{
            $this->bot->reply('Maaf Data Tidak Ditemukan Silahkan Buat Terlebih Dahulu /buat_surat');
        }
    }
    public function askMenuActionSkp(){
        $question = Question::create('Berikut Menu Untuk Edit Data SK Penduduk Anda!!')
        ->fallback('Maaf Perintah Tidak Ada')
        ->callbackId('ask_reason')
        ->addButtons([
            Button::create('Edit SK Penduduk')->value('/edit_skp'),
        ]);

        $this->ask($question, function (Answer $answer) {
            // Detect if button was clicked:
        });
    }
}
