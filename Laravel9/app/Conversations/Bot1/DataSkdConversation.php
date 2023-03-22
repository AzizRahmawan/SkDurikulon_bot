<?php

namespace App\Conversations\Bot1;

use App\Models\Skd;
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

class DataSkdConversation extends Conversation
{

    public function run()
    {
        $this->viewDataSkd();
        // $this->cariLagi();
    }
    public function viewDataSkd()
    {
        $user = $this->bot->getUser();
        //if (Skd::where('id_user',1)->exists()){
        if (Skd::where('id_user2',$user->getId())->exists()){
            $dataSkd = Skd::where('id_user2',$user->getId())->get();
            //$dataSkd = Skd::where('id_user',1)->get();
            $buttons = [];
            foreach ($dataSkd as $skd) {
                $buttons[] = Button::create(Carbon::parse($skd->created_at)->isoFormat('dddd D MMMM Y'))->value($skd->id_skd);
            }
            $question = Question::create('Silahkan Pilih Data Surat Di Bawah!!')
            ->fallback('Maaf Perintah Tidak Ada')
            ->callbackId('ask_reason')
            ->addButtons($buttons);

            $this->ask($question, function (Answer $answer) {
                $skd = Skd::find($answer->getValue());
                if ($skd){
                    $message = "Berikut Data Surat Keterangan Domisili Milik Anda :" . PHP_EOL;
                    $message .= "Nama : " . $skd->nama . PHP_EOL;
                    $message .= "NIK : " . $skd->nik . PHP_EOL;
                    $message .= "No KK : " . $skd->no_kk . PHP_EOL;
                    $message .= "Tempat dan Tanggal Lahir : " . $skd->tmpt_tgl_lahir . PHP_EOL;
                    $message .= "Jenis Kelamin : " . $skd->jk . PHP_EOL;
                    $message .= "Pekerjaan : " . $skd->pekerjaan . PHP_EOL;
                    $message .= "Agama : " . $skd->agama . PHP_EOL;
                    $message .= "Status : " . $skd->status . PHP_EOL;
                    $message .= "Pendidikan : " . $skd->pendidikan . PHP_EOL;
                    $message .= "Alamat pada KTP : " . $skd->alamat_ktp . PHP_EOL;
                    //$message .= "Alamat Sekarang : " . $skd->alamat_skr . PHP_EOL;
                    $message .= "Tanggal Dibuat :" . Carbon::parse($skd->created_at)->isoFormat('dddd D MMMM Y') . PHP_EOL;
                    $this->say($message);
                    $this->say("Silahkan Klik Link Dibawah ini Untuk Mencetak Surat!!" . PHP_EOL ."https://skdurikulon.000webhostapp.com/skd/" . $skd->id_skd . "/" . $skd->id_user2 . "/" . $skd->nik);
                } else {
                    $this->say('Data tidak ditemukan, silakan pilih data yang tersedia.');
                }
            });
        }else{
            $this->bot->reply('Maaf Data Tidak Ditemukan Silahkan Buat Terlebih Dahulu /buat_surat');
        }
    }
}
