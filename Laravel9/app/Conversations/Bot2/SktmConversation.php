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
use Illuminate\Support\Facades\Validator;

class SktmConversation extends Conversation
{
    public $para;
    public $id_user;

    public function run()
    {
        $this->askKonfirmasi();
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
                            $this->say('Silahkan Masukkan Identitas Anda untuk Membuat SKTM Baru!!');
                            $this->askNamaSktm();

                            break;
                        case '/sktm_tidak':
                            $this->say('Proses Buat SKTM Telah Dibatalkan!!. Silahkan Lihat Data Surat Anda dengan Perintah /data_surat');
                            //$this->askMenuStart();
                            break;
                        default:
                            # code...
                        break;
                    }

                }
            });
        } else {
            $this->askNamaSktm();
        }
    }

    public function askNamaSktm(){
        $question = BotManQuestion::create("Masukkan Nama Anda?");
        $this->ask($question, function(Answer $answer){
            $id_user = $this->bot->getUser()->getId();
            $validator = Validator::make(['answer' => $answer], [
                'answer' => 'required|max:100'
            ]);

            if ($validator->fails()) {
                $this->say('Input tidak valid. Silakan coba lagi.');
                $this->askNamaSktm();
                return;
            }else{
                if( $answer->getText () != '' ){
                    $this->para = $answer->getText();
                    $this->say("Nama Anda ".$this->para);
                    $this->insertNamaSktm();
                    $this->askNikSktm();
                }
            }
        });
    }
    private function insertNamaSktm() {
        DB::table('sktm')->insert([
            'nama' => $this->para,
            //'id_user' => 1,
            "created_at"=> Carbon::now(),
            "updated_at"=> Carbon::now(),
            'id_user' => $this->bot->getUser()->getId(),
        ]);
        return true;
    }
    public function askNikSktm(){
        $question = BotManQuestion::create("Masukkan NIK Anda?");
        $this->ask($question, function(Answer $answer){
            $validator = Validator::make(['answer' => $answer], [
                'answer' => 'required|integer|max:16'
            ]);

            if ($validator->fails()) {
                $this->say('Input tidak valid. Silakan coba lagi.');
                $this->askNikSktm();
                return;
            }else{
                if( $answer->getText () != '' ){
                    $this->para = $answer->getText();
                    $this->say("Nik Anda ".$this->para);
                    $this->insertNikSktm();
                    $this->askTglLahirSktm();
                    //$this->say("Silahkan Lihat Data Anda di /data_surat");
                }
            }
        });
    }
    private function insertNikSktm() {
        DB::table('sktm')->where('id_user',$this->bot->getUser()->getId())->update([
        //DB::table('sktm')->where('id_user',1)->update([
            'nik' => $this->para,
        ]);
        return true;
    }

    public function askTglLahirSktm(){
        $question = BotManQuestion::create("Masukkan Tempat dan Tanggal Lahir Anda?");
        $this->ask($question, function(Answer $answer){
            $validator = Validator::make(['answer' => $answer], [
                'answer' => 'required|max:100'
            ]);

            if ($validator->fails()) {
                $this->say('Input tidak valid. Silakan coba lagi.');
                $this->askTglLahirSktm();
                return;
            }else{
                if( $answer->getText () != '' ){
                    $this->para = $answer->getText();
                    $this->say("Tempat dan Tanggal Lahir Anda ".$this->para);
                    $this->insertTglLahirSktm();
                    $this->askJkSktm();
                    //$this->say("Silahkan Lihat Data Anda di /data_surat");
                }
            }
        });
    }

    private function insertTglLahirSktm() {
        DB::table('sktm')->where('id_user',$this->bot->getUser()->getId())->update([
        //DB::table('sktm')->where('id_user',1)->update([
            'tmpt_tgl_lahir' => $this->para,
        ]);
        return true;
    }

    public function askJkSktm(){
        $question = BotManQuestion::create("Masukkan Jenis Kelamin Anda?");
        $this->ask($question, function(Answer $answer){
            $validator = Validator::make(['answer' => $answer], [
                'answer' => 'required|max:100'
            ]);

            if ($validator->fails()) {
                $this->say('Input tidak valid. Silakan coba lagi.');
                $this->askJkSktm();
                return;
            }else{
                if( $answer->getText () != '' ){
                    $this->para = $answer->getText();
                    $this->say("Jenis Kelamin Anda ".$this->para);
                    $this->insertJkSktm();
                    $this->askNamaKkSktm();
                    //$this->say("Silahkan Lihat Data Anda di /data_surat");
                }
            }
        });
    }

    private function insertJkSktm() {
        DB::table('sktm')->where('id_user',$this->bot->getUser()->getId())->update([
        //DB::table('sktm')->where('id_user',1)->update([
            'jk' => $this->para,
        ]);
        return true;
    }

    public function askNamaKkSktm(){
        $question = BotManQuestion::create("Masukkan Nama Kepala Keluarga?");
        $this->ask($question, function(Answer $answer){
            $validator = Validator::make(['answer' => $answer], [
                'answer' => 'required|max:100'
            ]);

            if ($validator->fails()) {
                $this->say('Input tidak valid. Silakan coba lagi.');
                $this->askNamaKkSktm();
                return;
            }else{
                if( $answer->getText () != '' ){
                    $this->para = $answer->getText();
                    $this->say("Nama Kepala Keluarga Anda ".$this->para);
                    $this->insertNamaKkSktm();
                    $this->askNikKkSktm();
                    //$this->say("Silahkan Lihat Data Anda di /data_surat");
                }
            }
        });
    }

    private function insertNamaKkSktm() {
        DB::table('sktm')->where('id_user',$this->bot->getUser()->getId())->update([
        //DB::table('sktm')->where('id_user',1)->update([
            'nama_kepala' => $this->para,
        ]);
        return true;
    }

    public function askNikKkSktm(){
        $question = BotManQuestion::create("Masukkan NIK Kepala Keluarga?");
        $this->ask($question, function(Answer $answer){
            $validator = Validator::make(['answer' => $answer], [
                'answer' => 'required|integer|max:16'
            ]);

            if ($validator->fails()) {
                $this->say('Input tidak valid. Silakan coba lagi.');
                $this->askNikKkSktm();
                return;
            }else{
                if( $answer->getText () != '' ){
                    $this->para = $answer->getText();
                    $this->say("Nik Kepala Keluarga ".$this->para);
                    $this->insertNikKkSktm();
                    $this->askAlamatSktm();
                    //$this->say("Silahkan Lihat Data Anda di /data_surat");
                }
            }
        });
    }

    private function insertNikKkSktm() {
        DB::table('sktm')->where('id_user',$this->bot->getUser()->getId())->update([
        //DB::table('sktm')->where('id_user',1)->update([
            'nik_kepala' => $this->para,
        ]);
        return true;
    }

    public function askAlamatSktm(){
        $question = BotManQuestion::create("Masukkan Alamat Anda?");
        $this->ask($question, function(Answer $answer){
            $validator = Validator::make(['answer' => $answer], [
                'answer' => 'required|max:1000'
            ]);

            if ($validator->fails()) {
                $this->say('Input tidak valid. Silakan coba lagi.');
                $this->askAlamatSktm();
                return;
            }else{
                if( $answer->getText () != '' ){
                    $this->para = $answer->getText();
                    $this->say("Alamat Anda ".$this->para);
                    $this->insertAlamatSktm();
                    $this->say("Masukkan Keperluan Surat!!");
                    $this->askKeperluanSktm();
                    //$this->say("Silahkan Lihat Data Anda di /data_surat");
                }
            }
        });
    }

    private function insertAlamatSktm() {
        DB::table('sktm')->where('id_user',$this->bot->getUser()->getId())->update([
        //DB::table('sktm')->where('id_user',1)->update([
            'alamat_skr' => $this->para,
        ]);
        return true;
    }

    public function askKeperluanSktm(){
        $question = BotManQuestion::create("Surat keterangan ini dipergunakan untuk?");
        $this->ask($question, function(Answer $answer){
            $validator = Validator::make(['answer' => $answer], [
                'answer' => 'required|max:100'
            ]);

            if ($validator->fails()) {
                $this->say('Input tidak valid. Silakan coba lagi.');
                $this->askKeperluanSktm();
                return;
            }else{
                if( $answer->getText () != '' ){
                    $this->para = $answer->getText();
                    $this->say("Surat keterangan ini di berikan kepada orang tersebut untuk ".$this->para);
                    $this->insertKeperluanSktm();
                    $this->say("Surat Berhasil Dibuat!!");
                    $this->cetakSktm();
                }
            }
        });
    }

    private function insertkeperluanSktm() {
        DB::table('sktm')->where('id_user',$this->bot->getUser()->getId())->update([
        //DB::table('sktm')->where('id_user',1)->update([
            'keperluan' => $this->para,
        ]);
        return true;
    }

    private function cetakSktm()
    {
        //$user = $this->bot->getUser();
        $dataSktm = Sktm::where('id_user',$this->bot->getUser()->getId())->get();
        //$dataSktm = Sktm::where('id_user',1)->get();
        $message = '';
        foreach($dataSktm as $sktm){
            $message .= "Berikut Data SKTM Anda :" . PHP_EOL;
            $message .= "Nama  : " . $sktm->nama . PHP_EOL;
            $message .= "NIK      : " . $sktm->nik . PHP_EOL;
            $message .= "Tanggal Lahir   : " . $sktm->tmpt_tgl_lahir . PHP_EOL;
            $message .= "Jenis Kelamin  : " . $sktm->jk . PHP_EOL;
            $message .= "Nama Kepala Keluarga : " . $sktm->nama_kepala . PHP_EOL;
            $message .= "NIK Kepala Keluarga     : " . $sktm->nik_kepala . PHP_EOL;
            $message .= "Alamat      : " . $sktm->alamat_skr . PHP_EOL;
            $message .= "Tanggal Dibuat :" . Carbon::parse($sktm->created_at)->isoFormat('dddd D MMMM Y') . PHP_EOL;
            $message .= "Surat keterangan ini di berikan kepada orang tersebut untuk " . $sktm->keperluan . PHP_EOL;
            //$message .= "Silahkan Klik Link Dibawah ini Untuk Mencetak Surat!!" . PHP_EOL;
            //$message .= "665e-103-163-36-11.ap.ngrok.io/sktm/" . $sktm->id_user . "/" . $sktm->nik . PHP_EOL;
            //$message .= "127.0.0.1:8000/sktm/" . $sktm->id_user . "/" . $sktm->nik . PHP_EOL;
            $this->bot->reply($message);
            $this->say("Silahkan Klik Link Dibawah ini Untuk Mencetak Surat!!" . PHP_EOL ."http://localhost:8000/sktm/" . $sktm->id_sktm . "/" . $sktm->id_user2 . "/" . $sktm->nik);
        }
    }

}
