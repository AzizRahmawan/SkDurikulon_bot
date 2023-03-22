<?php

namespace App\Conversations\Bot1;

use App\Models\Skp;
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
        if (Skp::where('id_user2',$user->getId())->exists()){
            $dataSkp = Skp::where('id_user2',$user->getId())->get();
            //$dataSkp = Skp::where('id_user',1)->get();
            $buttons = [];
            foreach ($dataSkp as $skp) {
                $buttons[] = Button::create(Carbon::parse($skp->created_at)->isoFormat('dddd D MMMM Y'))->value($skp->id_skp);
            }
            $question = Question::create('Silahkan Pilih Data Surat Di Bawah!!')
            ->fallback('Maaf Perintah Tidak Ada')
            ->callbackId('ask_reason')
            ->addButtons($buttons);

            $this->ask($question, function (Answer $answer) {
                $skp = Skp::find($answer->getValue());
                if ($skp){
                    $message = "Berikut Data Surat Keterangan Penduduk Milik Anda :" . PHP_EOL;
                    $message .= "Nama : " . $skp->nama . PHP_EOL;
                    $message .= "NIK : " . $skp->nik . PHP_EOL;
                    $message .= "No KK : " . $skp->no_kk . PHP_EOL;
                    $message .= "Tempat dan Tanggal Lahir : " . $skp->tmpt_tgl_lahir . PHP_EOL;
                    $message .= "Jenis Kelamin : " . $skp->jk . PHP_EOL;
                    $message .= "Pekerjaan : " . $skp->pekerjaan . PHP_EOL;
                    $message .= "Agama : " . $skp->agama . PHP_EOL;
                    $message .= "Status : " . $skp->status . PHP_EOL;
                    $message .= "Pendidikan: " . $skp->pendidikan . PHP_EOL;
                    $message .= "Alamat : " . $skp->alamat_skr . PHP_EOL;
                    $message .= "Tanggal Dibuat :" . Carbon::parse($skp->created_at)->isoFormat('dddd D MMMM Y') . PHP_EOL;
                    $this->say($message);
                    $this->say("Silahkan Klik Link Dibawah ini Untuk Mencetak Surat!!" . PHP_EOL ."https://skdurikulon.000webhostapp.com/skp/" . $skp->id_skp . "/" . $skp->id_user2 . "/" . $skp->nik);
                } else {
                    $this->say('Data tidak ditemukan, silakan pilih data yang tersedia.');
                }
            });
        }else{
            $this->bot->reply('Maaf Data Tidak Ditemukan Silahkan Buat Terlebih Dahulu /buat_surat');
        }
    }
}
