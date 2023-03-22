<?php

namespace App\Conversations\Bot2;

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

class SkdConversation extends Conversation
{
    public $para;

    public function run()
    {
        $this->askKonfirmasi();
        // $this->cariLagi();
    }
    public function askKonfirmasi(){
        //if(DB::table('skd')->where('id_user', 1)->exists() ){
        if(DB::table('skd')->where('id_user', $this->bot->getUser()->getId())->exists() ){
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
                            DB::table('skd')->where('id_user',$this->bot->getUser()->getId())->update(['id_user' => null]);
                            //DB::table('skd')->where('id_user', 1)->update(['id_user' => null]);
                            $this->say('Silahkan Masukkan Identitas Anda untuk Membuat SK Domisili Baru!!');
                            $this->askNamaSkd();

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
            $this->askNamaSkd();
        }
    }
    public function askNamaSkd(){
        $question = BotManQuestion::create("Masukkan Nama Anda?");
        $this->ask($question, function(Answer $answer){
            $id_user = $this->bot->getUser()->getId();
            $validator = Validator::make(['answer' => $answer], [
                'answer' => 'required|max:100'
            ]);

            if ($validator->fails()) {
                $this->say('Input tidak valid. Silakan coba lagi.');
                $this->askNamaSkd();
                return;
            }else{
                if( $answer->getText () != '' ){
                    DB::table('skd')->where('id_user', 1)->update(['id_user' => null]);
                    $this->para = $answer->getText();
                    $this->say("Nama Anda ".$this->para);
                    $this->insertNamaSkd();
                    $this->askTglLahirSkd();
                }
            }
        });
    }
    private function insertNamaSkd() {
        DB::table('skd')->insert([
            'nama' => $this->para,
            //'id_user' => 1,
            'id_user' => $this->bot->getUser()->getId(),
            "created_at"=> Carbon::now(),
            "updated_at"=> Carbon::now(),
        ]);
        return true;
    }

    public function askTglLahirSkd(){
        $question = BotManQuestion::create("Masukkan Tempat Tanggal Lahir Anda?");
        $this->ask($question, function(Answer $answer){
            $validator = Validator::make(['answer' => $answer], [
                'answer' => 'required|max:100'
            ]);

            if ($validator->fails()) {
                $this->say('Input tidak valid. Silakan coba lagi.');
                $this->askTglLahirSkd();
                return;
            }else{
                if( $answer->getText () != '' ){
                    $this->para = $answer->getText();
                    $this->say("Tempat Tanggal Lahir Anda ".$this->para);
                    $this->insertTglLahirSkd();
                    $this->askNoKkSkd();
                    //$this->say("Silahkan Lihat Data Anda di /data_surat");
                }
            }
        });
    }

    private function insertTglLahirSkd() {
        DB::table('skd')->where('id_user',$this->bot->getUser()->getId())->update([
        //DB::table('skd')->where('id_user',1)->update([
            'tmpt_tgl_lahir' => $this->para,
        ]);
        return true;
    }

    public function askNoKkSkd(){
        $question = BotManQuestion::create("Masukkan No KK Anda?");
        $this->ask($question, function(Answer $answer){
            $validator = Validator::make(['answer' => $answer], [
                'answer' => 'required|Integer|max:16'
            ]);

            if ($validator->fails()) {
                $this->say('Input tidak valid. Silakan coba lagi.');
                $this->askNoKkSkd();
                return;
            }else{
                if( $answer->getText () != '' ){
                    $this->para = $answer->getText();
                    $this->say("No KK Anda ".$this->para);
                    $this->insertNoKkSkd();
                    $this->askNikSkd();
                    //$this->say("Silahkan Lihat Data Anda di /data_surat");
                }
            }
        });
    }
    private function insertNoKkSkd() {
        DB::table('skd')->where('id_user',$this->bot->getUser()->getId())->update([
        //DB::table('skd')->where('id_user',1)->update([
            'no_kk' => $this->para,
        ]);
        return true;
    }

    public function askNikSkd(){
        $question = BotManQuestion::create("Masukkan NIK Anda?");
        $this->ask($question, function(Answer $answer){
            $validator = Validator::make(['answer' => $answer], [
                'answer' => 'required|Integer|max:16'
            ]);

            if ($validator->fails()) {
                $this->say('Input tidak valid. Silakan coba lagi.');
                $this->askNikSkd();
                return;
            }else{
                if( $answer->getText () != '' ){
                    $this->para = $answer->getText();
                    $this->say("Nik Anda ".$this->para);
                    $this->insertNikSkd();
                    $this->askJkSkd();
                    //$this->say("Silahkan Lihat Data Anda di /data_surat");
                }
            }
        });
    }
    private function insertNikSkd() {
        DB::table('skd')->where('id_user',$this->bot->getUser()->getId())->update([
        //DB::table('skd')->where('id_user',1)->update([
            'nik' => $this->para,
        ]);
        return true;
    }

    public function askJkSkd(){
        $question = BotManQuestion::create("Masukkan Jenis Kelamin Anda?");
        $this->ask($question, function(Answer $answer){
            $validator = Validator::make(['answer' => $answer], [
                'answer' => 'required|max:10'
            ]);

            if ($validator->fails()) {
                $this->say('Input tidak valid. Silakan coba lagi.');
                $this->askJkSkd();
                return;
            }else{
                if( $answer->getText () != '' ){
                    $this->para = $answer->getText();
                    $this->say("Jenis Kelamin Anda ".$this->para);
                    $this->insertJkSkd();
                    $this->askPekerjaanSkd();
                    //$this->say("Silahkan Lihat Data Anda di /data_surat");
                }
            }
        });
    }

    private function insertJkSkd() {
        DB::table('skd')->where('id_user',$this->bot->getUser()->getId())->update([
        //DB::table('skd')->where('id_user',1)->update([
            'jk' => $this->para,
        ]);
        return true;
    }

    public function askPekerjaanSkd(){
        $question = BotManQuestion::create("Masukkan Pekerjaan Anda?");
        $this->ask($question, function(Answer $answer){
            $validator = Validator::make(['answer' => $answer], [
                'answer' => 'required|max:100'
            ]);

            if ($validator->fails()) {
                $this->say('Input tidak valid. Silakan coba lagi.');
                $this->askPekerjaanSkd();
                return;
            }else{
                if( $answer->getText () != '' ){
                    $this->para = $answer->getText();
                    $this->say("Pekerjaan Anda ".$this->para);
                    $this->insertPekerjaanSkd();
                    $this->askStatusSkd();
                    //$this->say("Silahkan Lihat Data Anda di /data_surat");
                }
            }
        });
    }

    private function insertPekerjaanSkd() {
        DB::table('skd')->where('id_user',$this->bot->getUser()->getId())->update([
        //DB::table('skd')->where('id_user',1)->update([
            'pekerjaan' => $this->para,
        ]);
        return true;
    }

    public function askStatusSkd(){
        $question = BotManQuestion::create("Masukkan Status Pernikahan Anda Saat Ini?");
        $this->ask($question, function(Answer $answer){
            $validator = Validator::make(['answer' => $answer], [
                'answer' => 'required|max:20'
            ]);

            if ($validator->fails()) {
                $this->say('Input tidak valid. Silakan coba lagi.');
                $this->askStatusSkd();
                return;
            }else{
                if( $answer->getText () != '' ){
                    $this->para = $answer->getText();
                    $this->say("Status Pernikahan Anda Saat Ini ".$this->para);
                    $this->insertStatusSkd();
                    $this->askAgamaSkd();
                    //$this->say("Silahkan Lihat Data Anda di /data_surat");
                }
            }
        });
    }

    private function insertStatusSkd() {
        DB::table('skd')->where('id_user',$this->bot->getUser()->getId())->update([
        //DB::table('skd')->where('id_user',1)->update([
            'status' => $this->para,
        ]);
        return true;
    }

    public function askAgamaSkd(){
        $question = BotManQuestion::create("Masukkan Agama Anda?");
        $this->ask($question, function(Answer $answer){
            $validator = Validator::make(['answer' => $answer], [
                'answer' => 'required|max:20'
            ]);

            if ($validator->fails()) {
                $this->say('Input tidak valid. Silakan coba lagi.');
                $this->askAgamaSkd();
                return;
            }else{
                if( $answer->getText () != '' ){
                    $this->para = $answer->getText();
                    $this->say("Agama Anda ".$this->para);
                    $this->insertAgamaSkd();
                    $this->askAlamatKtpSkd();
                    //$this->say("Silahkan Lihat Data Anda di /data_surat");
                }
            }
        });
    }

    private function insertAgamaSkd() {
        DB::table('skd')->where('id_user',$this->bot->getUser()->getId())->update([
        //DB::table('skd')->where('id_user',1)->update([
            'agama' => $this->para,
        ]);
        return true;
    }

    public function askAlamatKtpSkd(){
        $question = BotManQuestion::create("Masukkan Alamat Pada KTP Anda?");
        $this->ask($question, function(Answer $answer){
            $validator = Validator::make(['answer' => $answer], [
                'answer' => 'required|max:255'
            ]);

            if ($validator->fails()) {
                $this->say('Input tidak valid. Silakan coba lagi.');
                $this->askAlamatKtpSkd();
                return;
            }else{
                if( $answer->getText () != '' ){
                    $this->para = $answer->getText();
                    $this->say("Alamat Anda ".$this->para);
                    $this->insertAlamatKtpSkd();
                    $this->askAlamatSkrSkd();
                    //$this->say("Silahkan Lihat Data Anda di /data_surat");
                }
            }
        });
    }

    private function insertAlamatKtpSkd() {
        DB::table('skd')->where('id_user',$this->bot->getUser()->getId())->update([
        //DB::table('skd')->where('id_user',1)->update([
            'alamat_ktp' => $this->para,
        ]);
        return true;
    }

    public function askAlamatSkrSkd(){
        $question = BotManQuestion::create("Masukkan Alamat Anda Sekarang?");
        $this->ask($question, function(Answer $answer){
            $validator = Validator::make(['answer' => $answer], [
                'answer' => 'required|max:255'
            ]);

            if ($validator->fails()) {
                $this->say('Input tidak valid. Silakan coba lagi.');
                $this->askAlamatSkrSkd();
                return;
            }else{
                if( $answer->getText () != '' ){
                    $this->para = $answer->getText();
                    $this->say("Alamat Anda ".$this->para);
                    $this->insertAlamatSkrSkd();
                    $this->say("Surat Berhasil Dibuat!!");
                    $this->cetakSkd();
                }
            }
        });
    }

    private function insertAlamatSkrSkd() {
        DB::table('skd')->where('id_user',$this->bot->getUser()->getId())->update([
        //DB::table('skd')->where('id_user',1)->update([
            'alamat_skr' => $this->para,
        ]);
        return true;
    }

    private function cetakSkd()
    {
        //$user = $this->bot->getUser();
        $dataSkd = Skd::where('id_user',$this->bot->getUser()->getId())->get();
        //$dataSkd = Skd::where('id_user',1)->get();
        $message = '';
        foreach($dataSkd as $skd){
            $message .= "Berikut Data Skd Anda :" . PHP_EOL;
            $message .= "Nama  : " . $skd->nama . PHP_EOL;
            $message .= "NIK      : " . $skd->nik . PHP_EOL;
            $message .= "Tanggal Lahir   : " . $skd->tmpt_tgl_lahir . PHP_EOL;
            $message .= "Jenis Kelamin  : " . $skd->jk . PHP_EOL;
            $message .= "Pekerjaan     : " . $skd->pekerjaan . PHP_EOL;
            $message .= "Agama : " . $skd->agama . PHP_EOL;
            $message .= "Status     : " . $skd->status . PHP_EOL;
            $message .= "No KK     : " . $skd->no_kk . PHP_EOL;
            $message .= "Alamat      : " . $skd->alamat_skr . PHP_EOL;
            $this->bot->reply($message);
            $this->say("Silahkan Klik Link Dibawah ini Untuk Mencetak Surat!!" . PHP_EOL ."http://localhost:8000/skd/" . $skd->id_skd . "/" . $skd->id_user2 . "/" . $skd->nik);
        }
    }

}
