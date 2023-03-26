<?php

namespace App\Conversations\Bot1;

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
use Illuminate\Support\Facades\Validator;

class SktmBaruConversation extends Conversation
{
    public $para;
    public $id_user;

    public function run()
    {
        $this->listNik();
        // $this->cariLagi();
    }

    public function askKonfirmasi(){
        //if(DB::table('sktm')->where('id_user', 1)->exists() ){
        if(DB::table('sktm')->where('id_user', $this->bot->getUser()->getId())->exists() ){
            $question = Question::create('Data SKTM Sudah Ada, Sktm yang Sebelumnya Akan Dihapus. Apakah Anda Ingin Buat Baru?')
            ->fallback('Maaf Perintah Tidak Ada')
            ->callbackId('ask_reason')
            ->addButtons([
                Button::create('Iya')->value('/sktm_ya'),
                Button::create('Tidak')->value('/sktm_tidak'),
            ]);
            return $this->ask($question, function (Answer $answer) {
                if ($answer->isInteractiveMessageReply()) {
                    switch ($answer->getValue()) {
                        case '/sktm_ya':
                            DB::table('sktm')->where('id_user',$this->bot->getUser()->getId())->update(['id_user' => null]);
                            //DB::table('sktm')->where('id_user', 1)->update(['id_user' => null]);
                            $this->listNik();

                            break;
                        case '/sktm_tidak':
                            $this->say('Proses Buat SKTM Telah Dibatalkan!!. Silahkan Lihat Data Surat Anda dengan Perintah /lihat_data');
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
        $this->askNikSktmBaru();
    }
    public function askNikSktmBaru(){
        $question = BotManQuestion::create("Masukkan Nik Anda?");
        $this->ask($question, function(Answer $answer){
            $validator = Validator::make(['answer' => $answer], [
                'answer' => 'required|integer|max:16'
            ]);

            if ($validator->fails()) {
                $this->say('Input tidak valid. Silakan coba lagi.');
                $this->askNikSktmBaru();
                return;
            }else{
                if(Penduduk::where('nik',$answer->getText ())->exists() ){
                    DB::table('sktm')->where('id_user', $this->bot->getUser()->getId())->update(['id_user' => null]);
                    //DB::table('sktm')->where('id_user', 1)->update(['id_user' => null]);
                    $this->para = $answer->getText();
                    $this->insertNikSktmBaru();
                    $this->say('Masukkan Keperluan Surat Anda!');
                    $this->askKeperluanSktmBaru();
                } else {
                    $this->bot->reply("Maaf NIK Anda Tidak Ada Silahkan Hubungi Admin!!");
                }
            }
        });
    }
    private function insertNikSktmBaru() {
        $dataPenduduk = Penduduk::where('nik',$this->para)->get();
        $message = '';
        foreach($dataPenduduk as $p){
            DB::table('sktm')->insert([
                'nama' => $p->nama,
                'nik' => $p->nik,
                'tmpt_tgl_lahir' => $p->tmpt_tgl_lahir,
                'jk' => $p->jk,
                'nama_kepala' => $p->nama_kepala,
                'nik_kepala' => $p->nik_kepala,
                'alamat_ktp' => $p->alamat_ktp,
                //'id_user' => 1,
                'id_user' => $this->bot->getUser()->getId(),
                'id_user2' => $this->bot->getUser()->getId(),
                "created_at"=> Carbon::now(),
                "updated_at"=> Carbon::now(),
            ]);
            $message .= "Berikut Data Anda :" . PHP_EOL;
            $message .= "Nama  : " . $p->nama . PHP_EOL;
            $message .= "NIK   : " . $p->nik . PHP_EOL;
            $message .= "Tempat Tanggal Lahir : " . $p->tmpt_tgl_lahir . PHP_EOL;
            $message .= "Jenis Kelamin : " . $p->jk . PHP_EOL;
            $message .= "Agama : " . $p->agama . PHP_EOL;
            $message .= "Pekerjaan : " . $p->pekerjaan . PHP_EOL;
            $message .= "Status : " . $p->status . PHP_EOL;
            $message .= "Pendidikan Terakhir : " . $p->pendidikan . PHP_EOL;
            $message .= "No Kartu Keluarga : " . $p->no_kk . PHP_EOL;
            $message .= "Alamat      : " . $p->alamat_ktp . PHP_EOL;
        }
        $this->bot->reply($message);
    }

    public function askKeperluanSktmBaru(){
        $question = BotManQuestion::create("Surat keterangan ini dipergunakan untuk?");
        $this->ask($question, function(Answer $answer){
            $validator = Validator::make(['answer' => $answer], [
                'answer' => 'required|max:100'
            ]);

            if ($validator->fails()) {
                $this->say('Input tidak valid. Silakan coba lagi.');
                $this->askKeperluanSktmBaru();
                return;
            }else{
                if( $answer->getText () != '' ){
                    $this->para = $answer->getText();
                    $this->say("Surat keterangan ini di berikan kepada orang tersebut untuk ".$this->para);
                    $this->insertKeperluanSktmBaru();
                    $this->cetakSktmBaru();
                }
            }
        });
    }

    private function insertkeperluanSktmBaru() {
        DB::table('sktm')->where('id_user',$this->bot->getUser()->getId())->update([
        //DB::table('sktm')->where('id_user',1)->update([
            'keperluan' => $this->para,
        ]);
        return true;
    }

    private function cetakSktmBaru()
    {
        //$user = $this->bot->getUser();
        $dataSktm = Sktm::where('id_user',$this->bot->getUser()->getId())->get();
        //$dataSktm = Sktm::where('id_user',1)->get();
        $message = '';
        foreach($dataSktm as $sktm){
            $message .= "Berikut Data SKTM Anda :" . PHP_EOL;
            $message .= "Nama : " . $sktm->nama . PHP_EOL;
            $message .= "NIK  : " . $sktm->nik . PHP_EOL;
            $message .= "Tempat dan Tanggal Lahir : " . $sktm->tmpt_tgl_lahir . PHP_EOL;
            $message .= "Jenis Kelamin : " . $sktm->jk . PHP_EOL;
            $message .= "Nama Kepala Keluarga : " . $sktm->nama_kepala . PHP_EOL;
            $message .= "NIK Kepala Keluarga  : " . $sktm->nik_kepala . PHP_EOL;
            $message .= "Alamat : " . $sktm->alamat_ktp . PHP_EOL;
            $message .= "Surat keterangan ini di berikan kepada orang tersebut untuk " . $sktm->keperluan . PHP_EOL;
            $this->bot->reply($message);
            $this->say("Silahkan Klik Link Dibawah ini Untuk Mencetak Surat!!" . PHP_EOL ."https://574e-103-160-182-11.ap.ngrok.io/sktm/" . $sktm->id_sktm . "/" . $sktm->id_user2 . "/" . $sktm->nik);
        }
    }
}
