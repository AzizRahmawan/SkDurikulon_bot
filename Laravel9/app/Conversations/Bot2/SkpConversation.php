<?php

namespace App\Conversations\Bot2;

use App\Models\Penduduk;
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
use Illuminate\Support\Facades\Validator;

class SkpConversation extends Conversation
{
    public $para;


    public function run()
    {
        $this->askKonfirmasi();
        // $this->cariLagi();
    }
    public function askKonfirmasi(){
        //if(DB::table('skp')->where('id_user', 1)->exists() ){
        if(DB::table('skp')->where('id_user', $this->bot->getUser()->getId())->exists() ){
            $question = Question::create('Data SK Penduduk Sudah Ada, SK Penduduk yang Sebelumnya Akan Dihapus. Apakah Anda Ingin Buat Baru?')
            ->fallback('Maaf Perintah Tidak Ada')
            ->callbackId('ask_reason')
            ->addButtons([
                Button::create('Iya')->value('/skp_ya'),
                Button::create('Tidak')->value('/skp_tidak'),
            ]);
            return $this->ask($question, function (Answer $answer) {
                if ($answer->isInteractiveMessageReply()) {
                    switch ($answer->getValue()) {
                        case '/skp_ya':
                            //DB::table('sktm')->where('id_user', $this->bot->getUser()->getId())->update(['id_user' => null]);
                            DB::table('skp')->where('id_user', 1)->update(['id_user' => null]);
                            $this->say('Silahkan Masukkan Identitas Anda untuk Membuat SK Penduduk yang Baru!!');
                            $this->askNamaSkp();

                            break;
                        case '/skp_tidak':
                            $this->say('Proses Buat SK Penduduk Telah Dibatalkan!!');
                            //$this->askMenuStart();
                            break;
                        default:
                            # code...
                        break;
                    }

                }
            });
        } else {
            $this->askNamaSkp();
        }
    }
    public function askNamaSkp(){
        $question = BotManQuestion::create("Masukkan Nama Anda?");
        $this->ask($question, function(Answer $answer){
            $id_user = $this->bot->getUser()->getId();
            $validator = Validator::make(['answer' => $answer], [
                'answer' => 'required|max:100'
            ]);

            if ($validator->fails()) {
                $this->say('Input tidak valid. Silakan coba lagi.');
                $this->askNamaSkp();
                return;
            }else{
                if( $answer->getText () != '' ){
                    DB::table('skp')->where('id_user', 1)->update(['id_user' => null]);
                    $this->para = $answer->getText();
                    $this->say("Nama Anda ".$this->para);
                    $this->insertNamaSkp();
                    $this->askTglLahirSkp();
                }
            }
        });
    }
    private function insertNamaSkp() {
        DB::table('skp')->insert([
            'nama' => $this->para,
            //'id_user' => 1,
            'id_user' => $this->bot->getUser()->getId(),
            "created_at"=> Carbon::now(),
            "updated_at"=> Carbon::now(),
        ]);
        return true;
    }

    public function askTglLahirSkp(){
        $question = BotManQuestion::create("Masukkan Tempat Tanggal Lahir Anda?");
        $this->ask($question, function(Answer $answer){
            $validator = Validator::make(['answer' => $answer], [
                'answer' => 'required|max:100'
            ]);

            if ($validator->fails()) {
                $this->say('Input tidak valid. Silakan coba lagi.');
                $this->askTglLahirSkp();
                return;
            }else{
                if( $answer->getText () != '' ){
                    $this->para = $answer->getText();
                    $this->say("Tempat Tanggal Lahir Anda ".$this->para);
                    $this->insertTglLahirSkp();
                    $this->askNoKkSkp();
                    //$this->say("Silahkan Lihat Data Anda di /data_surat");
                }
            }
        });
    }

    private function insertTglLahirSkp() {
        DB::table('skp')->where('id_user',$this->bot->getUser()->getId())->update([
        //DB::table('skp')->where('id_user',1)->update([
            'tmpt_tgl_lahir' => $this->para,
        ]);
        return true;
    }

    public function askNoKkSkp(){
        $question = BotManQuestion::create("Masukkan No KK Anda?");
        $this->ask($question, function(Answer $answer){
            $validator = Validator::make(['answer' => $answer], [
                'answer' => 'required|Integer|max:16'
            ]);

            if ($validator->fails()) {
                $this->say('Input tidak valid. Silakan coba lagi.');
                $this->askNoKkSkp();
                return;
            }else{
                if( $answer->getText () != '' ){
                    $this->para = $answer->getText();
                    $this->say("No KK Anda ".$this->para);
                    $this->insertNoKkSkp();
                    $this->askNikSkp();
                    //$this->say("Silahkan Lihat Data Anda di /data_surat");
                }
            }
        });
    }
    private function insertNoKkSkp() {
        DB::table('skp')->where('id_user',$this->bot->getUser()->getId())->update([
        //DB::table('skp')->where('id_user',1)->update([
            'no_kk' => $this->para,
        ]);
        return true;
    }

    public function askNikSkp(){
        $question = BotManQuestion::create("Masukkan NIK Anda?");
        $this->ask($question, function(Answer $answer){
            $validator = Validator::make(['answer' => $answer], [
                'answer' => 'required|Integer|max:16'
            ]);

            if ($validator->fails()) {
                $this->say('Input tidak valid. Silakan coba lagi.');
                $this->askNikSkp();
                return;
            }else{
                if( $answer->getText () != '' ){
                    $this->para = $answer->getText();
                    $this->say("Nik Anda ".$this->para);
                    $this->insertNikSkp();
                    $this->askJkSkp();
                    //$this->say("Silahkan Lihat Data Anda di /data_surat");
                }
            }
        });
    }
    private function insertNikSkp() {
        DB::table('skp')->where('id_user',$this->bot->getUser()->getId())->update([
        //DB::table('skp')->where('id_user',1)->update([
            'nik' => $this->para,
        ]);
        return true;
    }

    public function askJkSkp(){
        $question = BotManQuestion::create("Masukkan Jenis Kelamin Anda?");
        $this->ask($question, function(Answer $answer){
            $validator = Validator::make(['answer' => $answer], [
                'answer' => 'required|max:10'
            ]);

            if ($validator->fails()) {
                $this->say('Input tidak valid. Silakan coba lagi.');
                $this->askJkSkp();
                return;
            }else{
                if( $answer->getText () != '' ){
                    $this->para = $answer->getText();
                    $this->say("Jenis Kelamin Anda ".$this->para);
                    $this->insertJkSkp();
                    $this->askPekerjaanSkp();
                    //$this->say("Silahkan Lihat Data Anda di /data_surat");
                }
            }
        });
    }

    private function insertJkSkp() {
        DB::table('skp')->where('id_user',$this->bot->getUser()->getId())->update([
        //DB::table('skp')->where('id_user',1)->update([
            'jk' => $this->para,
        ]);
        return true;
    }

    public function askPekerjaanSkp(){
        $question = BotManQuestion::create("Masukkan Pekerjaan Anda?");
        $this->ask($question, function(Answer $answer){
            $validator = Validator::make(['answer' => $answer], [
                'answer' => 'required|max:100'
            ]);

            if ($validator->fails()) {
                $this->say('Input tidak valid. Silakan coba lagi.');
                $this->askPekerjaanSkp();
                return;
            }else{
                if( $answer->getText () != '' ){
                    $this->para = $answer->getText();
                    $this->say("Pekerjaan Anda ".$this->para);
                    $this->insertPekerjaanSkp();
                    $this->askStatusSkp();
                    //$this->say("Silahkan Lihat Data Anda di /data_surat");
                }
            }
        });
    }

    private function insertPekerjaanSkp() {
        DB::table('skp')->where('id_user',$this->bot->getUser()->getId())->update([
        //DB::table('skp')->where('id_user',1)->update([
            'pekerjaan' => $this->para,
        ]);
        return true;
    }

    public function askStatusSkp(){
        $question = BotManQuestion::create("Masukkan Status Penikahan Anda Saat Ini?");
        $this->ask($question, function(Answer $answer){
            $validator = Validator::make(['answer' => $answer], [
                'answer' => 'required|max:20'
            ]);

            if ($validator->fails()) {
                $this->say('Input tidak valid. Silakan coba lagi.');
                $this->askStatusSkp();
                return;
            }else{

            }
            if( $answer->getText () != '' ){
                $this->para = $answer->getText();
                $this->say("Status Pernikahan Anda Saat Ini ".$this->para);
                $this->insertStatusSkp();
                $this->askAgamaSkp();
                //$this->say("Silahkan Lihat Data Anda di /data_surat");
            }
        });
    }

    private function insertStatusSkp() {
        DB::table('skp')->where('id_user',$this->bot->getUser()->getId())->update([
        //DB::table('skp')->where('id_user',1)->update([
            'status' => $this->para,
        ]);
        return true;
    }

    public function askAgamaSkp(){
        $question = BotManQuestion::create("Masukkan Agama Anda?");
        $this->ask($question, function(Answer $answer){
            $validator = Validator::make(['answer' => $answer], [
                'answer' => 'required|max:20'
            ]);

            if ($validator->fails()) {
                $this->say('Input tidak valid. Silakan coba lagi.');
                $this->askAgamaSkp();
                return;
            }else{
                if( $answer->getText () != '' ){
                    $this->para = $answer->getText();
                    $this->say("Agama Anda ".$this->para);
                    $this->insertAgamaSkp();
                    $this->askPendidikanSkp();
                    //$this->say("Silahkan Lihat Data Anda di /data_surat");
                }
            }
        });
    }

    private function insertAgamaSkp() {
        DB::table('skp')->where('id_user',$this->bot->getUser()->getId())->update([
        //DB::table('skp')->where('id_user',1)->update([
            'agama' => $this->para,
        ]);
        return true;
    }

    public function askPendidikanSkp(){
        $question = BotManQuestion::create("Masukkan Pendidikan Terakhir Anda?");
        $this->ask($question, function(Answer $answer){
            $validator = Validator::make(['answer' => $answer], [
                'answer' => 'required|max:50'
            ]);

            if ($validator->fails()) {
                $this->say('Input tidak valid. Silakan coba lagi.');
                $this->askPendidikanSkp();
                return;
            }else{
                if( $answer->getText () != '' ){
                    $this->para = $answer->getText();
                    $this->say("Pendidikan Terakhir Anda ".$this->para);
                    $this->insertPendidikanSkp();
                    $this->askAlamatSkrSkp();
                    //$this->say("Silahkan Lihat Data Anda di /data_surat");
                }
            }
        });
    }

    private function insertPendidikanSkp() {
        DB::table('skp')->where('id_user',$this->bot->getUser()->getId())->update([
        //DB::table('skp')->where('id_user',1)->update([
            'pendidikan' => $this->para,
        ]);
        return true;
    }

    public function askAlamatSkrSkp(){
        $question = BotManQuestion::create("Masukkan Alamat Anda?");
        $this->ask($question, function(Answer $answer){
            $validator = Validator::make(['answer' => $answer], [
                'answer' => 'required|max:1000'
            ]);

            if ($validator->fails()) {
                $this->say('Input tidak valid. Silakan coba lagi.');
                $this->askAlamatSkrSkp();
                return;
            }else{
                if( $answer->getText () != '' ){
                    $this->para = $answer->getText();
                    $this->say("Alamat Anda ".$this->para);
                    $this->insertAlamatSkrSkp();
                    $this->say("Surat Berhasil Dibuat!!");
                    $this->cetakSkp();
                }
            }
        });
    }

    private function insertAlamatSkrSkp() {
        DB::table('skp')->where('id_user',$this->bot->getUser()->getId())->update([
        //DB::table('skp')->where('id_user',1)->update([
            'alamat_skr' => $this->para,
        ]);
        return true;
    }

    private function cetakSkp()
    {
        $user = $this->bot->getUser();
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
            //$message .= "Surat keterangan ini di berikan kepada orang tersebut untuk " . $skp->keperluan . PHP_EOL;
            //$message .= "Silahkan Klik Link Dibawah ini Untuk Mencetak Surat!!" . PHP_EOL;
            //$message .= "665e-103-163-36-11.ap.ngrok.io/skp/" . $skp->id_user . "/" . $skp->nik . PHP_EOL;
            //$message .= "127.0.0.1:8000/skp/" . $skp->id_user . "/" . $skp->nik . PHP_EOL;
            $this->bot->reply($message);
            $this->say("Silahkan Klik Link Dibawah ini Untuk Mencetak Surat!!" . PHP_EOL ."https://www.skdurikulon.myhost.id/skp/" . $skp->id_skp . "/" . $skp->id_user2 . "/" . $skp->nik);
        }
    }

}
