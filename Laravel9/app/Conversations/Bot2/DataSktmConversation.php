<?php

namespace App\Conversations\Bot2;

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

class DataSktmConversation extends Conversation
{

    public function run()
    {
        $this->viewDataSktm();
        // $this->cariLagi();
    }
    public function viewDataSktm()
    {
        $user = $this->bot->getUser();
        //if (Sktm::where('id_user',1)->exists()){
        if (Sktm::where('id_user',$user->getId())->exists()){
            $dataSktm = Sktm::where('id_user',$user->getId())->get();
            //$dataSktm = Sktm::where('id_user',1)->get();
            $message = '';
            foreach($dataSktm as $sktm){
                $message .= "Berikut Data Sktm Anda :" . PHP_EOL;
                $message .= "Nama  : " . $sktm->nama . PHP_EOL;
                $message .= "Tanggal Lahir   : " . $sktm->tmpt_tgl_lahir . PHP_EOL;
                $message .= "NIK      : " . $sktm->nik . PHP_EOL;
                $message .= "Jenis Kelamin  : " . $sktm->jk . PHP_EOL;
                $message .= "Nama Kepala Keluarga : " . $sktm->nama_kepala . PHP_EOL;
                $message .= "NIK Kepala Keluarga     : " . $sktm->nik_kepala . PHP_EOL;
                $message .= "Alamat    : " . $sktm->alamat_skr . PHP_EOL;
                $message .= "Surat ini Dipergunakan Untuk " . $sktm->keperluan . PHP_EOL;
                $message .= "Tanggal Dibuat :" . Carbon::parse($sktm->created_at)->isoFormat('dddd D MMMM Y') . PHP_EOL;
                $this->bot->reply($message);
                $this->say("Silahkan Klik Link Dibawah ini Untuk Mencetak Surat!!" . PHP_EOL ."https://www.skdurikulon.myhost.id/sktm/" . $sktm->id_sktm . "/" . $sktm->id_user2 . "/" . $sktm->nik);
            }
            $this->askMenuActionSktm();
        }else{
            $this->bot->reply('Maaf Data Tidak Ditemukan Silahkan Buat Terlebih Dahulu /buat_surat');
        }
    }
    public function askMenuActionSktm(){
        $question = Question::create('Berikut Menu Untuk Edit Data SKTM Anda!!')
        ->fallback('Maaf Perintah Tidak Ada')
        ->callbackId('ask_reason')
        ->addButtons([
            Button::create('Edit SKTM')->value('/edit_sktm'),
        ]);

        $this->ask($question, function (Answer $answer) {
            // Detect if button was clicked:
        });
    }
}
