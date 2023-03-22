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
use Illuminate\Support\Facades\DB;


class EditSktmConversation extends Conversation
{
    public $para;
    public $id_user;
    public function viewDataSktm()
    {
        $user = $this->bot->getUser();
        //if (Sktm::where('id_user',1)->exists()){
        if (Sktm::where('id_user',$user->getId())->exists()){
            $dataSktm = Sktm::where('id_user',$user->getId())->get();
            //$dataSktm = Sktm::where('id_user',1)->get();
            $message = '';
            foreach($dataSktm as $sktm){
                $message .= "Berikut Data SKTM Anda :" . PHP_EOL;
                $message .= "Nama  : " . $sktm->nama . PHP_EOL;
                $message .= "Tanggal Lahir   : " . $sktm->tmpt_tgl_lahir . PHP_EOL;
                $message .= "NIK      : " . $sktm->nik . PHP_EOL;
                $message .= "Jenis Kelamin  : " . $sktm->jk . PHP_EOL;
                $message .= "Nama Kepala Keluarga : " . $sktm->nama_kepala . PHP_EOL;
                $message .= "NIK Kepala Keluarga     : " . $sktm->nik_kepala . PHP_EOL;
                $message .= "Alamat    : " . $sktm->alamat_skr . PHP_EOL;
                $message .= "Surat ini Dipergunakan Untuk " . $sktm->keperluan . PHP_EOL;
                $this->bot->reply($message);
            }
            $this->askMenuEditSktmBaru();
        }else{
            $this->bot->reply('Maaf Data Tidak Ditemukan Silahkan Buat Terlebih Dahulu /buat_surat');
        }
    }

    public function askMenuEditSktmBaru()
    {
        $question = Question::create('Silahkan Pilih Menu Di Bawah!!')
            ->fallback('Maaf Perintah Tidak Ada')
            ->callbackId('ask_reason')
            ->addButtons([
                Button::create('Edit Nama')->value('/edit_nama_sktm'),
                Button::create('Edit NIK')->value('/edit_nik_sktm'),
                Button::create('Edit Tanggal Lahir')->value('/edit_tgl_lahir_sktm'),
                Button::create('Edit Jenis Kelamin')->value('/edit_jk_sktm'),
                Button::create('Edit Nama Kepala Keluarga')->value('/edit_nama_kepala_sktm'),
                Button::create('Edit Nik Kepala Keluarga')->value('/edit_nik_kepala_sktm'),
                Button::create('Edit Alamat')->value('/edit_alamat_skr_sktm'),
                Button::create('Edit Keperluan Surat')->value('/edit_keperluan_sktm'),
            ]);

            return $this->ask($question, function (Answer $answer) {
                if ($answer->isInteractiveMessageReply()) {
                    switch ($answer->getValue()) {
                        case '/edit_nama_sktm':
                            $this->askNamaSktmBaru();

                            break;
                        case '/edit_nik_sktm':
                            $this->askNikSktmBaru();

                            break;
                        case '/edit_tgl_lahir_sktm':
                            $this->askTglLahirSktmBaru();

                            break;
                        case '/edit_jk_sktm':
                            $this->askJkSktmBaru();

                            break;
                        case '/edit_nama_kepala_sktm':
                            $this->askNamaKepalaSktmBaru();

                            break;
                        case '/edit_nik_kepala_sktm':
                            $this->asktNikKepalaSktmBaru();

                            break;
                        case '/edit_alamat_skr_sktm':
                            $this->askAlamatSkrSktmBaru();

                            break;
                        case '/edit_keperluan_sktm':
                            $this->askKeperluanSktmBaru();

                            break;
                        default:
                            # code...
                           break;
                    }

                }
            });
    }
    public function askNamaSktmBaru(){
        $question = BotManQuestion::create("Masukkan Nama Baru Anda?");
        $this->ask($question, function(Answer $answer){
            $id_user = $this->bot->getUser()->getId();
            if( $answer->getText () != '' ){
                $this->para = $answer->getText();
                $this->say("Berhasil Mengganti Nama ".$this->para);
                $this->insertNamaSktmBaru();
                $this->askMenuEditSktmBaru();
            }
        });
    }
    private function insertNamaSktmBaru() {
        DB::table('sktm')->where('id_user',$this->bot->getUser()->getId())->update([
        //DB::table('sktm')->where('id_user',1)->update([
            'nama' => $this->para,
        ]);
        return true;
    }
    public function askNikSktmBaru(){
        $question = BotManQuestion::create("Masukkan NIK Baru Anda?");
        $this->ask($question, function(Answer $answer){
            $id_user = $this->bot->getUser()->getId();
            if( $answer->getText () != '' ){
                $this->para = $answer->getText();
                $this->say("Berhasil Mengganti NIK ".$this->para);
                $this->insertNikSktmBaru();
                $this->askMenuEditSktmBaru();
            }
        });
    }
    private function insertNikSktmBaru() {
        DB::table('sktm')->where('id_user',$this->bot->getUser()->getId())->update([
        //DB::table('sktm')->where('id_user',1)->update([
            'nik' => $this->para,
        ]);
        return true;
    }
    public function askTglLahirSktmBaru(){
        $question = BotManQuestion::create("Masukkan Tempat dan Tanggal Lahir Baru Anda?");
        $this->ask($question, function(Answer $answer){
            if( $answer->getText () != '' ){
                $this->para = $answer->getText();
                $this->say("Berhasil Mengganti Tempat dan Tanggal Lahir ".$this->para);
                $this->insertTglLahirSktmBaru();
                $this->askMenuEditSktmBaru();
                //$this->say("Silahkan Lihat Data Anda di /data_surat");
            }
        });
    }

    private function insertTglLahirSktmBaru() {
        DB::table('sktm')->where('id_user',$this->bot->getUser()->getId())->update([
        //DB::table('sktm')->where('id_user',1)->update([
            'tmpt_tgl_lahir' => $this->para,
        ]);
        return true;
    }

    public function askJkSktmBaru(){
        $question = BotManQuestion::create("Masukkan Jenis Kelamin Baru Anda?");
        $this->ask($question, function(Answer $answer){
            if( $answer->getText () != '' ){
                $this->para = $answer->getText();
                $this->say("Berhasil Mengganti Jenis Kelamin ".$this->para);
                $this->insertJkSktmBaru();
                $this->askMenuEditSktmBaru();
                //$this->say("Silahkan Lihat Data Anda di /data_surat");
            }
        });
    }

    private function insertJkSktmBaru() {
        DB::table('sktm')->where('id_user',$this->bot->getUser()->getId())->update([
        //DB::table('sktm')->where('id_user',1)->update([
            'jk' => $this->para,
        ]);
        return true;
    }

    public function askNamaKepalaSktmBaru(){
        $question = BotManQuestion::create("Masukkan Nama Keluarga yang Baru?");
        $this->ask($question, function(Answer $answer){
            if( $answer->getText () != '' ){
                $this->para = $answer->getText();
                $this->say("Berhasil Mengganti Nama Kepala Keluarga ".$this->para);
                $this->insertNamaKepalaSktmBaru();
                $this->askMenuEditSktmBaru();
                //$this->say("Silahkan Lihat Data Anda di /data_surat");
            }
        });
    }

    private function insertNamaKepalaSktmBaru() {
        DB::table('sktm')->where('id_user',$this->bot->getUser()->getId())->update([
        //DB::table('sktm')->where('id_user',1)->update([
            'nama_kepala' => $this->para,
        ]);
        return true;
    }

    public function asktNikKepalaSktmBaru(){
        $question = BotManQuestion::create("Masukkan NIK Kepala Keluarga yang Baru?");
        $this->ask($question, function(Answer $answer){
            if( $answer->getText () != '' ){
                $this->para = $answer->getText();
                $this->say("Berhasil Mengganti NIK Kepala Keluarga ".$this->para);
                $this->insertNikKepalaSktmBaru();
                $this->askMenuEditSktmBaru();
                //$this->say("Silahkan Lihat Data Anda di /data_surat");
            }
        });
    }

    private function insertNikKepalaSktmBaru() {
        DB::table('sktm')->where('id_user',$this->bot->getUser()->getId())->update([
        //DB::table('sktm')->where('id_user',1)->update([
            'nik_kepala' => $this->para,
        ]);
        return true;
    }


    public function askAlamatSkrSktmBaru(){
        $question = BotManQuestion::create("Masukkan Alamat Baru Anda?");
        $this->ask($question, function(Answer $answer){
            if( $answer->getText () != '' ){
                $this->para = $answer->getText();
                $this->say("Berhasil Mengganti Alamat ".$this->para);
                $this->insertAlamatSkrSktmBaru();
                $this->askMenuEditSktmBaru();
                //$this->say("Silahkan Lihat Data Anda di /data_surat");
            }
        });
    }

    private function insertAlamatSkrSktmBaru() {
        DB::table('sktm')->where('id_user',$this->bot->getUser()->getId())->update([
        //DB::table('sktm')->where('id_user',1)->update([
            'alamat_skr' => $this->para,
        ]);
        return true;
    }

    public function askKeperluanSktmBaru(){
        $question = BotManQuestion::create("Masukkan Keperluan Baru SKTM Anda. Surat Ini Dipergunakan Untuk?");
        $this->ask($question, function(Answer $answer){
            if( $answer->getText () != '' ){
                $this->para = $answer->getText();
                $this->say("Surat Ini Dipergunakan Untuk ".$this->para);
                $this->insertKeperluanSktmBaru();
                $this->askMenuEditSktmBaru();
                //$this->say("Silahkan Lihat Data Anda di /data_surat");
            }
        });
    }

    private function insertKeperluanSktmBaru() {
        DB::table('sktm')->where('id_user',$this->bot->getUser()->getId())->update([
        //DB::table('sktm')->where('id_user',1)->update([
            'keperluan' => $this->para,
        ]);
        return true;
    }


    /**
     * Start the conversation
     */
    public function run()
    {
        $this->viewDataSktm();
        // $this->cariLagi();
    }
}
