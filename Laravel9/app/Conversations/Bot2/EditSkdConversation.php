<?php

namespace App\Conversations\Bot2;

use App\Models\Skd;
use Illuminate\Foundation\Inspiring;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Outgoing\Question as BotManQuestion;
use BotMan\BotMan\Messages\Incoming\Answer as BotManAnswer;
use Illuminate\Support\Facades\DB;


class EditSkdConversation extends Conversation
{
    public $para;
    public $id_user;
    public function viewDataSkd()
    {
        $user = $this->bot->getUser();
        //if (Skd::where('id_user',1)->exists()){
        if (Skd::where('id_user',$user->getId())->exists()){
            $dataSkd = Skd::where('id_user',$user->getId())->get();
            //$dataSkd = Skd::where('id_user',1)->get();
            $message = '';
            foreach($dataSkd as $skd){
                $message .= "Berikut Data SK Domisili Anda :" . PHP_EOL;
                $message .= "Nama  : " . $skd->nama . PHP_EOL;
                $message .= "Tanggal Lahir   : " . $skd->tmpt_tgl_lahir . PHP_EOL;
                $message .= "No KK : " . $skd->no_kk . PHP_EOL;
                $message .= "NIK      : " . $skd->nik . PHP_EOL;
                $message .= "Jenis Kelamin  : " . $skd->jk . PHP_EOL;
                $message .= "Pekerjaan     : " . $skd->pekerjaan . PHP_EOL;
                $message .= "Status    : " . $skd->status . PHP_EOL;
                $message .= "Agama    : " . $skd->agama . PHP_EOL;
                $message .= "Alamat di KTP     : " . $skd->alamat_ktp . PHP_EOL;
                $message .= "Alamat Sekarang     : " . $skd->alamat_skr . PHP_EOL;
                $this->bot->reply($message);
            }
            $this->askMenuEditSkdBaru();
        }else{
            $this->bot->reply('Maaf Data Tidak Ditemukan Silahkan Buat Terlebih Dahulu /buat_surat');
        }
    }

    public function askMenuEditSkdBaru()
    {
        $question = Question::create('Silahkan Pilih Menu Di Bawah!!')
            ->fallback('Maaf Perintah Tidak Ada')
            ->callbackId('ask_reason')
            ->addButtons([
                Button::create('Edit Nama')->value('/edit_nama_skd'),
                Button::create('Edit NIK')->value('/edit_nik_skd'),
                Button::create('Edit No KK')->value('/edit_no_kk_skd'),
                Button::create('Edit Tanggal Lahir')->value('/edit_tgl_lahir_skd'),
                Button::create('Edit Jenis Kelamin')->value('/edit_jk_skd'),
                Button::create('Edit Pekerjaan')->value('/edit_pekerjaan_skd'),
                Button::create('Edit Status Pernikahan')->value('/edit_status_skd'),
                Button::create('Edit Agama')->value('/edit_agama_skd'),
                Button::create('Edit Alamat Di KTP')->value('/edit_alamat_ktp_skd'),
                Button::create('Edit Alamat Sekarang')->value('/edit_alamat_skr_skd'),
            ]);

            return $this->ask($question, function (Answer $answer) {
                if ($answer->isInteractiveMessageReply()) {
                    switch ($answer->getValue()) {
                        case '/edit_nama_skd':
                            $this->askNamaSkdBaru();

                            break;
                        case '/edit_nik_skd':
                            $this->askNikSkdBaru();

                            break;
                        case '/edit_tgl_lahir_skd':
                            $this->askTglLahirSkdBaru();

                            break;
                        case '/edit_jk_skd':
                            $this->askJkSkdBaru();

                            break;
                        case '/edit_no_kk_skd':
                            $this->askNoKkSkdBaru();

                            break;
                        case '/edit_pekerjaan_skd':
                            $this->askPekerjaanSkdBaru();

                            break;
                        case '/edit_status_skd':
                            $this->askStatusSkdBaru();

                            break;
                        case '/edit_agama_skd':
                            $this->askAgamaSkdBaru();

                            break;
                        case '/edit_alamat_ktp_skd':
                            $this->askAlamatKtpSkdBaru();

                            break;
                        case '/edit_alamat_skr_skd':
                            $this->askAlamatSkrSkdBaru();

                            break;

                        default:
                            # code...
                           break;
                    }

                }
            });
    }
    public function askNamaSkdBaru(){
        $question = BotManQuestion::create("Masukkan Nama Baru Anda?");
        $this->ask($question, function(Answer $answer){
            $id_user = $this->bot->getUser()->getId();
            if( $answer->getText () != '' ){
                $this->para = $answer->getText();
                $this->say("Berhasil Mengganti Nama ".$this->para);
                $this->insertNamaSkdBaru();
                $this->askMenuEditSkdBaru();
            }
        });
    }
    private function insertNamaSkdBaru() {
        DB::table('skd')->where('id_user',$this->bot->getUser()->getId())->update([
        //DB::table('skd')->where('id_user',1)->update([
            'nama' => $this->para,
        ]);
        return true;
    }
    public function askNikSkdBaru(){
        $question = BotManQuestion::create("Masukkan NIK Baru Anda?");
        $this->ask($question, function(Answer $answer){
            $id_user = $this->bot->getUser()->getId();
            if( $answer->getText () != '' ){
                $this->para = $answer->getText();
                $this->say("Berhasil Mengganti NIK ".$this->para);
                $this->insertNikSkdBaru();
                $this->askMenuEditSkdBaru();
            }
        });
    }
    private function insertNikSkdBaru() {
        DB::table('skd')->where('id_user',$this->bot->getUser()->getId())->update([
        //DB::table('skd')->where('id_user',1)->update([
            'nik' => $this->para,
        ]);
        return true;
    }
    public function askTglLahirSkdBaru(){
        $question = BotManQuestion::create("Masukkan Tempat dan Tanggal Lahir Baru Anda?");
        $this->ask($question, function(Answer $answer){
            if( $answer->getText () != '' ){
                $this->para = $answer->getText();
                $this->say("Berhasil Mengganti Tempat dan Tanggal Lahir ".$this->para);
                $this->insertTglLahirSkdBaru();
                $this->askMenuEditSkdBaru();
                //$this->say("Silahkan Lihat Data Anda di /data_surat");
            }
        });
    }

    private function insertTglLahirSkdBaru() {
        DB::table('skd')->where('id_user',$this->bot->getUser()->getId())->update([
        //DB::table('skd')->where('id_user',1)->update([
            'tmpt_tgl_lahir' => $this->para,
        ]);
        return true;
    }

    public function askJkSkdBaru(){
        $question = BotManQuestion::create("Masukkan Jenis Kelamin Baru Anda?");
        $this->ask($question, function(Answer $answer){
            if( $answer->getText () != '' ){
                $this->para = $answer->getText();
                $this->say("Berhasil Mengganti Jenis Kelamin ".$this->para);
                $this->insertJkSkdBaru();
                $this->askMenuEditSkdBaru();
                //$this->say("Silahkan Lihat Data Anda di /data_surat");
            }
        });
    }

    private function insertJkSkdBaru() {
        DB::table('skd')->where('id_user',$this->bot->getUser()->getId())->update([
        //DB::table('skd')->where('id_user',1)->update([
            'jk' => $this->para,
        ]);
        return true;
    }

    public function askNoKkSkdBaru(){
        $question = BotManQuestion::create("Masukkan No KK yang Baru?");
        $this->ask($question, function(Answer $answer){
            if( $answer->getText () != '' ){
                $this->para = $answer->getText();
                $this->say("Berhasil Mengganti No KK ".$this->para);
                $this->insertNoKkSkdBaru();
                $this->askMenuEditSkdBaru();
                //$this->say("Silahkan Lihat Data Anda di /data_surat");
            }
        });
    }

    private function insertNoKkSkdBaru() {
        DB::table('skd')->where('id_user',$this->bot->getUser()->getId())->update([
        //DB::table('skd')->where('id_user',1)->update([
            'no_kk' => $this->para,
        ]);
        return true;
    }

    public function askdekerjaanSkdBaru(){
        $question = BotManQuestion::create("Masukkan Pekerjaan yang Baru?");
        $this->ask($question, function(Answer $answer){
            if( $answer->getText () != '' ){
                $this->para = $answer->getText();
                $this->say("Berhasil Mengganti Pekerjaan ".$this->para);
                $this->insertPekerjaanSkdBaru();
                $this->askMenuEditSkdBaru();
                //$this->say("Silahkan Lihat Data Anda di /data_surat");
            }
        });
    }

    private function insertPekerjaanSkdBaru() {
        DB::table('skd')->where('id_user',$this->bot->getUser()->getId())->update([
        //DB::table('skd')->where('id_user',1)->update([
            'pekerjaan' => $this->para,
        ]);
        return true;
    }

    public function askStatusSkdBaru(){
        $question = BotManQuestion::create("Masukkan Status Pernikahan yang Baru?");
        $this->ask($question, function(Answer $answer){
            if( $answer->getText () != '' ){
                $this->para = $answer->getText();
                $this->say("Berhasil Mengganti Status Pernikahan ".$this->para);
                $this->insertStatusSkdBaru();
                $this->askMenuEditSkdBaru();
                //$this->say("Silahkan Lihat Data Anda di /data_surat");
            }
        });
    }

    private function insertStatusSkdBaru() {
        DB::table('skd')->where('id_user',$this->bot->getUser()->getId())->update([
        //DB::table('skd')->where('id_user',1)->update([
            'status' => $this->para,
        ]);
        return true;
    }

    public function askAgamaSkdBaru(){
        $question = BotManQuestion::create("Masukkan Agama yang Baru?");
        $this->ask($question, function(Answer $answer){
            if( $answer->getText () != '' ){
                $this->para = $answer->getText();
                $this->say("Berhasil Mengganti Agama ".$this->para);
                $this->insertAgamaSkdBaru();
                $this->askMenuEditSkdBaru();
                //$this->say("Silahkan Lihat Data Anda di /data_surat");
            }
        });
    }

    private function insertAgamaSkdBaru() {
        DB::table('skd')->where('id_user',$this->bot->getUser()->getId())->update([
        //DB::table('skd')->where('id_user',1)->update([
            'agama' => $this->para,
        ]);
        return true;
    }

    public function askAlamatKtpSkdBaru(){
        $question = BotManQuestion::create("Masukkan Alamat KTP Baru Anda?");
        $this->ask($question, function(Answer $answer){
            if( $answer->getText () != '' ){
                $this->para = $answer->getText();
                $this->say("Berhasil Mengganti Alamat KTP ".$this->para);
                $this->insertAlamatKtpSkdBaru();
                $this->askMenuEditSkdBaru();
                //$this->say("Silahkan Lihat Data Anda di /data_surat");
            }
        });
    }

    private function insertAlamatKtpSkdBaru() {
        DB::table('skd')->where('id_user',$this->bot->getUser()->getId())->update([
        //DB::table('skd')->where('id_user',1)->update([
            'alamat_ktp' => $this->para,
        ]);
        return true;
    }

    public function askAlamatSkrSkdBaru(){
        $question = BotManQuestion::create("Masukkan Alamat Baru Sekarang Anda?");
        $this->ask($question, function(Answer $answer){
            if( $answer->getText () != '' ){
                $this->para = $answer->getText();
                $this->say("Berhasil Mengganti Alamat Sekarang ".$this->para);
                $this->insertAlamatSkrSkdBaru();
                $this->askMenuEditSkdBaru();
                //$this->say("Silahkan Lihat Data Anda di /data_surat");
            }
        });
    }

    private function insertAlamatSkrSkdBaru() {
        DB::table('skd')->where('id_user',$this->bot->getUser()->getId())->update([
        //DB::table('skd')->where('id_user',1)->update([
            'alamat_skr' => $this->para,
        ]);
        return true;
    }


    /**
     * Start the conversation
     */
    public function run()
    {
        $this->viewDataSkd();
        // $this->cariLagi();
    }
}
