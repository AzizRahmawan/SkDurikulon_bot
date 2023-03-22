<?php

namespace App\Conversations\Bot1;

use App\Models\Penduduk;
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
use Illuminate\Support\Facades\Validator;

class SkdBaruConversation extends Conversation
{
    public $para;

    public function run()
    {
        $this->listNik();
        // $this->cariLagi();
    }

    public function askKonfirmasi(){
        if(DB::table('skd')->where('id_user', 1)->exists() ){
        //if(DB::table('skd')->where('id_user', $this->bot->getUser()->getId())->exists() ){
            $question = Question::create('Data SK Domisili Sudah Ada, SK Domisili yang Sebelumnya Akan Dihapus. Apakah Anda Ingin Buat Baru?')
            ->fallback('Maaf Perintah Tidak Ada')
            ->callbackId('ask_reason')
            ->addButtons([
                Button::create('Iya')->value('/skd_ya'),
                Button::create('Tidak')->value('/skd_tidak'),
            ]);
            return $this->ask($question, function (Answer $answer) {
                if ($answer->isInteractiveMessageReply()) {
                    switch ($answer->getValue()) {
                        case '/skd_ya':
                            //DB::table('skd')->where('id_user',$this->bot->getUser()->getId())->update(['id_user' => null]);
                            DB::table('skd')->where('id_user', 1)->update(['id_user' => null]);
                            $this->listNik();

                            break;
                        case '/skd_tidak':
                            $this->say('Proses Buat SK DOmisili Telah Dibatalkan!!');
                            //$this->askMenuStart();
                            break;
                        default:
                            # code...
                        break;
                    }

                }
            });
        } else {
            $this->listNik();
        }
    }

    public function listNik(){
        $nik_penduduk = Penduduk::all();
        $message = '';
        $this->bot->reply('Silahkan Masukkan Salah Satu Nik Dibawah Ini!!!');
        foreach ($nik_penduduk as $nik_p){
            $message .= "NIK   : " . $nik_p->nik . PHP_EOL;
        }
        $this->bot->reply($message);
        $this->askNikSkdBaru();
    }
    public function askNikSkdBaru(){
        $question = BotManQuestion::create("Masukkan Nik Anda?");
        $this->ask($question, function(Answer $answer){
            $validator = Validator::make(['answer' => $answer], [
                'answer' => 'required|integer|max:16'
            ]);

            if ($validator->fails()) {
                $this->say('Input tidak valid. Silakan coba lagi.');
                $this->askNikSkdBaru();
                return;
            } else {
                if(Penduduk::where('nik',$answer->getText ())->exists() ){
                    //DB::table('skd')->where('id_user', $this->bot->getUser()->getId())->update(['id_user' => null]);
                    DB::table('skd')->where('id_user', 1)->update(['id_user' => null]);
                    $this->para = $answer->getText();
                    $this->insertNikSkdBaru();
                    $this->bot->reply("Masukkan Keterangan Alamat Sekarang!!");
                    $this->askKeteranganSkdBaru();
                } else {
                    $this->bot->reply("Maaf NIK Anda Tidak Ada Silahkan Hubungi Admin!!");
                }
            }
        });
    }
    private function insertNikSkdBaru() {
        $dataPenduduk = Penduduk::where('nik',$this->para)->get();
        $message = '';
        foreach($dataPenduduk as $p){
            DB::table('skd')->insert([
                'nama' => $p->nama,
                'nik' => $p->nik,
                'tmpt_tgl_lahir' => $p->tmpt_tgl_lahir,
                'jk' => $p->jk,
                'agama' => $p->agama,
                'pekerjaan' => $p->pekerjaan,
                'status' => $p->status,
                'no_kk' => $p->no_kk,
                //'alamat_skr' => $p->alamat_skr,
                'alamat_ktp' => $p->alamat_ktp,
                'id_user' => '1',
                'id_user2' => '1',
                //'id_user' => $this->bot->getUser()->getId(),
                //'id_user2' => $this->bot->getUser()->getId(),
                "created_at"=> Carbon::now(),
                "updated_at"=> Carbon::now(),
            ]);
            $message .= "Berikut Data Surat Keterangan Domisili Milik Anda :" . PHP_EOL;
            $message .= "Nama : " . $p->nama . PHP_EOL;
            $message .= "NIK : " . $p->nik . PHP_EOL;
            $message .= "No KK : " . $p->no_kk . PHP_EOL;
            $message .= "Tempat dan Tanggal Lahir : " . $p->tmpt_tgl_lahir . PHP_EOL;
            $message .= "Jenis Kelamin : " . $p->jk . PHP_EOL;
            $message .= "Pekerjaan : " . $p->pekerjaan . PHP_EOL;
            $message .= "Agama : " . $p->agama . PHP_EOL;
            $message .= "Status : " . $p->status . PHP_EOL;
            $message .= "Alamat pada KTP : " . $p->alamat_ktp . PHP_EOL;
            //$message .= "Alamat Sekarang : " . $skd->alamat_skr . PHP_EOL;
            $this->bot->reply($message);
        }
    }

    private function askKeteranganSkdBaru(){
        $question = BotManQuestion::create("Keterangan : Orang tersebut di atas pada saat ini benar–benar Berdomisili / Bertempat Tinggal di?");
        $this->ask($question, function(Answer $answer){
            $validator = Validator::make(['answer' => $answer], [
                'answer' => 'required|max:1000'
            ]);

            if ($validator->fails()) {
                $this->say('Input tidak valid. Silakan coba lagi.');
                $this->askKeteranganSkdBaru();
                return;
            }else{
                if( $answer->getText () != '' ){
                    $this->para = $answer->getText();
                    $this->say("Keterangan : Orang tersebut di atas pada saat ini benar–benar Berdomisili / Bertempat Tinggal di ".$this->para);
                    $this->insertKeteranganSkdBaru();
                    $this->say("Surat Berhasil Dibuat!!");
                    $this->cetakSkdBaru();
                }
            }
        });
    }
    private function insertKeteranganSkdBaru() {
        //DB::table('skd')->where('id_user',$this->bot->getUser()->getId())->update([
        DB::table('skd')->where('id_user',1)->update([
            'alamat_skr' => $this->para,
        ]);
        return true;
    }

    private function cetakSkdBaru()
    {
        //$user = $this->bot->getUser();
        //$dataSkd = Skd::where('id_user',$this->bot->getUser()->getId())->get();
        $dataSkd = Skd::where('id_user',1)->get();
        $message = '';
        foreach($dataSkd as $skd){
            $message .= "Berikut Data Surat Keterangan Domisili Milik Anda :" . PHP_EOL;
            $message .= "Nama : " . $skd->nama . PHP_EOL;
            $message .= "NIK : " . $skd->nik . PHP_EOL;
            $message .= "No KK : " . $skd->no_kk . PHP_EOL;
            $message .= "Tempat dan Tanggal Lahir : " . $skd->tmpt_tgl_lahir . PHP_EOL;
            $message .= "Jenis Kelamin : " . $skd->jk . PHP_EOL;
            $message .= "Pekerjaan : " . $skd->pekerjaan . PHP_EOL;
            $message .= "Agama : " . $skd->agama . PHP_EOL;
            $message .= "Status : " . $skd->status . PHP_EOL;
            $message .= "Alamat pada KTP : " . $skd->alamat_ktp . PHP_EOL;
            $message .= "Keterangan : Orang tersebut di atas pada saat ini benar–benar Berdomisili / Bertempat Tinggal di " . $skd->alamat_skr . PHP_EOL;
            //$message .= "Alamat Sekarang : " . $skd->alamat_skr . PHP_EOL;
            $this->bot->reply($message);
            $this->say("Silahkan Klik Link Dibawah ini Untuk Mencetak Surat!!" . PHP_EOL ."https://skdurikulon.000webhostapp.com/skd/" . $skd->id_skd . "/" . $skd->id_user2 . "/" . $skd->nik);
        }
    }

}
