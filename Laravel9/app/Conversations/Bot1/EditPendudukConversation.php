<?php

namespace App\Conversations\Bot1;

use App\Models\Penduduk;
use App\Models\EditPenduduk;
use App\Models\ReqEditPenduduk;
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

class EditPendudukConversation extends Conversation
{
    public $para;

    public function run()
    {
        $this->askNamaEditPenduduk();
        // $this->cariLagi();
    }
    public function askNamaEditPenduduk(){
        $question = BotManQuestion::create("Masukkan Nama Baru Anda?");
        $this->ask($question, function(Answer $answer){
            $id_user = $this->bot->getUser()->getId();
            if( $answer->getText () != '' ){
                DB::table('req_edit_enduduk')->where('id_user', 1)->where('status_acc','belum_disetujui')->update(['id_user' => null]);
                $this->para = $answer->getText();
                $this->say("Nama Anda ".$this->para);
                $this->insertNamaEditPenduduk();
                $this->askTglLahirEditPenduduk();
            }
        });
    }
    private function insertNamaEditPenduduk() {
        DB::table('EditPenduduk')->insert([
            'nama' => $this->para,
            'id_user' => 1,
            //'id_user' => $this->bot->getUser()->getId(),
            "created_at"=> Carbon::now(),
            "updated_at"=> Carbon::now(),
        ]);
        return true;
    }

    public function askTglLahirEditPenduduk(){
        $question = BotManQuestion::create("Masukkan Tempat Tanggal Lahir Anda?");
        $this->ask($question, function(Answer $answer){
            if( $answer->getText () != '' ){
                $this->para = $answer->getText();
                $this->say("Tempat Tanggal Lahir Anda ".$this->para);
                $this->insertTglLahirEditPenduduk();
                $this->askNoKkEditPenduduk();
                //$this->say("Silahkan Lihat Data Anda di /data_surat");
            }
        });
    }

    private function insertTglLahirEditPenduduk() {
        //DB::table('EditPenduduk')->where('id_user',$this->bot->getUser()->getId())->update([
        DB::table('EditPenduduk')->where('id_user',1)->update([
            'tmpt_tgl_lahir' => $this->para,
        ]);
        return true;
    }

    public function askNoKkEditPenduduk(){
        $question = BotManQuestion::create("Masukkan No KK Anda?");
        $this->ask($question, function(Answer $answer){
            if( $answer->getText () != '' ){
                $this->para = $answer->getText();
                $this->say("No KK Anda ".$this->para);
                $this->insertNoKkEditPenduduk();
                $this->askNikEditPenduduk();
                //$this->say("Silahkan Lihat Data Anda di /data_surat");
            }
        });
    }
    private function insertNoKkEditPenduduk() {
        //DB::table('EditPenduduk')->where('id_user',$this->bot->getUser()->getId())->update([
        DB::table('EditPenduduk')->where('id_user',1)->update([
            'no_kk' => $this->para,
        ]);
        return true;
    }

    public function askNikEditPenduduk(){
        $question = BotManQuestion::create("Masukkan NIK Anda?");
        $this->ask($question, function(Answer $answer){
            if( $answer->getText () != '' ){
                $this->para = $answer->getText();
                $this->say("Nik Anda ".$this->para);
                $this->insertNikEditPenduduk();
                $this->askJkEditPenduduk();
                //$this->say("Silahkan Lihat Data Anda di /data_surat");
            }
        });
    }
    private function insertNikEditPenduduk() {
        //DB::table('EditPenduduk')->where('id_user',$this->bot->getUser()->getId())->update([
        DB::table('EditPenduduk')->where('id_user',1)->update([
            'nik' => $this->para,
        ]);
        return true;
    }

    public function askJkEditPenduduk(){
        $question = BotManQuestion::create("Masukkan Jenis Kelamin Anda?");
        $this->ask($question, function(Answer $answer){
            if( $answer->getText () != '' ){
                $this->para = $answer->getText();
                $this->say("Jenis Kelamin Anda ".$this->para);
                $this->insertJkEditPenduduk();
                $this->askPekerjaanEditPenduduk();
                //$this->say("Silahkan Lihat Data Anda di /data_surat");
            }
        });
    }

    private function insertJkEditPenduduk() {
        //DB::table('EditPenduduk')->where('id_user',$this->bot->getUser()->getId())->update([
        DB::table('EditPenduduk')->where('id_user',1)->update([
            'jk' => $this->para,
        ]);
        return true;
    }

    public function askPekerjaanEditPenduduk(){
        $question = BotManQuestion::create("Masukkan Pekerjaan Anda?");
        $this->ask($question, function(Answer $answer){
            if( $answer->getText () != '' ){
                $this->para = $answer->getText();
                $this->say("Pekerjaan Anda ".$this->para);
                $this->insertPekerjaanEditPenduduk();
                $this->askStatusEditPenduduk();
                //$this->say("Silahkan Lihat Data Anda di /data_surat");
            }
        });
    }

    private function insertPekerjaanEditPenduduk() {
        //DB::table('EditPenduduk')->where('id_user',$this->bot->getUser()->getId())->update([
        DB::table('EditPenduduk')->where('id_user',1)->update([
            'pekerjaan' => $this->para,
        ]);
        return true;
    }

    public function askStatusEditPenduduk(){
        $question = BotManQuestion::create("Masukkan Status Pernikahan Anda Saat Ini?");
        $this->ask($question, function(Answer $answer){
            if( $answer->getText () != '' ){
                $this->para = $answer->getText();
                $this->say("Status Pernikahan Anda Saat Ini ".$this->para);
                $this->insertStatusEditPenduduk();
                $this->askAgamaEditPenduduk();
                //$this->say("Silahkan Lihat Data Anda di /data_surat");
            }
        });
    }

    private function insertStatusEditPenduduk() {
        //DB::table('EditPenduduk')->where('id_user',$this->bot->getUser()->getId())->update([
        DB::table('EditPenduduk')->where('id_user',1)->update([
            'status' => $this->para,
        ]);
        return true;
    }

    public function askAgamaEditPenduduk(){
        $question = BotManQuestion::create("Masukkan Agama Anda?");
        $this->ask($question, function(Answer $answer){
            if( $answer->getText () != '' ){
                $this->para = $answer->getText();
                $this->say("Agama Anda ".$this->para);
                $this->insertAgamaEditPenduduk();
                $this->askAlamatKtpEditPenduduk();
                //$this->say("Silahkan Lihat Data Anda di /data_surat");
            }
        });
    }

    private function insertAgamaEditPenduduk() {
        //DB::table('EditPenduduk')->where('id_user',$this->bot->getUser()->getId())->update([
        DB::table('EditPenduduk')->where('id_user',1)->update([
            'agama' => $this->para,
        ]);
        return true;
    }

    public function askAlamatKtpEditPenduduk(){
        $question = BotManQuestion::create("Masukkan Alamat Pada KTP Anda?");
        $this->ask($question, function(Answer $answer){
            if( $answer->getText () != '' ){
                $this->para = $answer->getText();
                $this->say("Alamat Anda ".$this->para);
                $this->insertAlamatKtpEditPenduduk();
                $this->askAlamatSkrEditPenduduk();
                //$this->say("Silahkan Lihat Data Anda di /data_surat");
            }
        });
    }

    private function insertAlamatKtpEditPenduduk() {
        //DB::table('EditPenduduk')->where('id_user',$this->bot->getUser()->getId())->update([
        DB::table('EditPenduduk')->where('id_user',1)->update([
            'alamat_ktp' => $this->para,
        ]);
        return true;
    }

    public function askAlamatSkrEditPenduduk(){
        $question = BotManQuestion::create("Masukkan Alamat Anda Sekarang?");
        $this->ask($question, function(Answer $answer){
            if( $answer->getText () != '' ){
                $this->para = $answer->getText();
                $this->say("Alamat Anda ".$this->para);
                $this->insertAlamatSkrEditPenduduk();
                $this->say("Surat Berhasil Dibuat!!");
                $this->cetakEditPenduduk();
            }
        });
    }

    private function insertAlamatSkrEditPenduduk() {
        //DB::table('EditPenduduk')->where('id_user',$this->bot->getUser()->getId())->update([
        DB::table('EditPenduduk')->where('id_user',1)->update([
            'alamat_skr' => $this->para,
        ]);
        return true;
    }

    private function cetakEditPenduduk()
    {
        //$user = $this->bot->getUser();
        //$dataEditPenduduk = EditPenduduk::where('id_user',$this->bot->getUser()->getId())->get();
        $dataEditPenduduk = ReqEditPenduduk::where('id_user',1)->get();
        $message = '';
        foreach($dataEditPenduduk as $EditPenduduk){
            $message .= "Berikut Data EditPenduduk Anda :" . PHP_EOL;
            $message .= "Nama  : " . $EditPenduduk->nama . PHP_EOL;
            $message .= "NIK      : " . $EditPenduduk->nik . PHP_EOL;
            $message .= "Tanggal Lahir   : " . $EditPenduduk->tmpt_tgl_lahir . PHP_EOL;
            $message .= "Jenis Kelamin  : " . $EditPenduduk->jk . PHP_EOL;
            $message .= "Pekerjaan     : " . $EditPenduduk->pekerjaan . PHP_EOL;
            $message .= "Agama : " . $EditPenduduk->agama . PHP_EOL;
            $message .= "Status     : " . $EditPenduduk->status . PHP_EOL;
            $message .= "No KK     : " . $EditPenduduk->no_kk . PHP_EOL;
            $message .= "Alamat      : " . $EditPenduduk->alamat_skr . PHP_EOL;
            //$message .= "Surat keterangan ini di berikan kepada orang tersebut untuk " . $EditPenduduk->keperluan . PHP_EOL;
            //$message .= "Silahkan Klik Link Dibawah ini Untuk Mencetak Surat!!" . PHP_EOL;
            //$message .= "665e-103-163-36-11.ap.ngrok.io/EditPenduduk/" . $EditPenduduk->id_user . "/" . $EditPenduduk->nik . PHP_EOL;
            //$message .= "127.0.0.1:8000/EditPenduduk/" . $EditPenduduk->id_user . "/" . $EditPenduduk->nik . PHP_EOL;
            $this->bot->reply($message);
            $this->say("Silahkan Klik Link Dibawah ini Untuk Mencetak Surat!!" . PHP_EOL ."127.0.0.1:8000/EditPenduduk/" . $EditPenduduk->id_user . "/" . $EditPenduduk->nik);
        }
    }

}
