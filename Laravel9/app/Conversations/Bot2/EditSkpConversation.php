<?php

namespace App\Conversations\Bot2;

use App\Models\Skp;
use Illuminate\Foundation\Inspiring;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Outgoing\Question as BotManQuestion;
use BotMan\BotMan\Messages\Incoming\Answer as BotManAnswer;
use Illuminate\Support\Facades\DB;


class EditSkpConversation extends Conversation
{
    public $para;
    public $id_user;

    public function run()
    {
        $this->viewDataSkp();
        // $this->cariLagi();
    }
    public function viewDataSkp()
    {
        $user = $this->bot->getUser();
        //if (Skp::where('id_user',1)->exists()){
        if (Skp::where('id_user',$user->getId())->exists()){
            $dataSkp = Skp::where('id_user',$user->getId())->get();
            //$dataSkp = Skp::where('id_user',1)->get();
            $message = '';
            foreach($dataSkp as $skp){
                $message .= "Berikut Data SK Penduduk Anda :" . PHP_EOL;
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
                $this->bot->reply($message);
            }
            $this->askMenuEditSkpBaru();
        }else{
            $this->bot->reply('Maaf Data Tidak Ditemukan Silahkan Buat Terlebih Dahulu /buat_surat');
        }
    }

    public function askMenuEditSkpBaru()
    {
        $question = Question::create('Silahkan Pilih Menu Di Bawah!!')
            ->fallback('Maaf Perintah Tidak Ada')
            ->callbackId('ask_reason')
            ->addButtons([
                Button::create('Edit Nama')->value('/edit_nama_skp'),
                Button::create('Edit NIK')->value('/edit_nik_skp'),
                Button::create('Edit No KK')->value('/edit_no_kk_skp'),
                Button::create('Edit Tanggal Lahir')->value('/edit_tgl_lahir_skp'),
                Button::create('Edit Jenis Kelamin')->value('/edit_jk_skp'),
                Button::create('Edit Pekerjaan')->value('/edit_pekerjaan_skp'),
                Button::create('Edit Status Pernikahan')->value('/edit_status_skp'),
                Button::create('Edit Agama')->value('/edit_agama_skp'),
                Button::create('Edit Pendidikan')->value('/edit_pendidikan_skp'),
                Button::create('Edit Alamat')->value('/edit_alamat_skp'),
            ]);

            return $this->ask($question, function (Answer $answer) {
                if ($answer->isInteractiveMessageReply()) {
                    switch ($answer->getValue()) {
                        case '/edit_nama_skp':
                            $this->askNamaSkpBaru();

                            break;
                        case '/edit_nik_skp':
                            $this->askNikSkpBaru();

                            break;
                        case '/edit_tgl_lahir_skp':
                            $this->askTglLahirSkpBaru();

                            break;
                        case '/edit_jk_skp':
                            $this->askJkSkpBaru();

                            break;
                        case '/edit_no_kk_skp':
                            $this->askNoKkSkpBaru();

                            break;
                        case '/edit_pekerjaan_skp':
                            $this->askPekerjaanSkpBaru();

                            break;
                        case '/edit_status_skp':
                            $this->askStatusSkpBaru();

                            break;
                        case '/edit_pendidikan_skp':
                            $this->askPendidikanSkpBaru();

                            break;
                        case '/edit_agama_skp':
                            $this->askAgamaSkpBaru();

                            break;
                        case '/edit_alamat_skp':
                            $this->askAlamatSkpBaru();

                            break;

                        default:
                            # code...
                           break;
                    }

                }
            });
    }
    public function askNamaSkpBaru(){
        $question = BotManQuestion::create("Masukkan Nama Baru Anda?");
        $this->ask($question, function(Answer $answer){
            $id_user = $this->bot->getUser()->getId();
            if( $answer->getText () != '' ){
                $this->para = $answer->getText();
                $this->say("Berhasil Mengganti Nama ".$this->para);
                $this->insertNamaSkpBaru();
                $this->askMenuEditSkpBaru();
            }
        });
    }
    private function insertNamaSkpBaru() {
        DB::table('skp')->where('id_user',$this->bot->getUser()->getId())->update([
        //DB::table('skp')->where('id_user',1)->update([
            'nama' => $this->para,
        ]);
        return true;
    }
    public function askNikSkpBaru(){
        $question = BotManQuestion::create("Masukkan NIK Baru Anda?");
        $this->ask($question, function(Answer $answer){
            $id_user = $this->bot->getUser()->getId();
            if( $answer->getText () != '' ){
                $this->para = $answer->getText();
                $this->say("Berhasil Mengganti NIK ".$this->para);
                $this->insertNikSkpBaru();
                $this->askMenuEditSkpBaru();
            }
        });
    }
    private function insertNikSkpBaru() {
        DB::table('skp')->where('id_user',$this->bot->getUser()->getId())->update([
        //DB::table('skp')->where('id_user',1)->update([
            'nik' => $this->para,
        ]);
        return true;
    }
    public function askTglLahirSkpBaru(){
        $question = BotManQuestion::create("Masukkan Tempat dan Tanggal Lahir Baru Anda?");
        $this->ask($question, function(Answer $answer){
            if( $answer->getText () != '' ){
                $this->para = $answer->getText();
                $this->say("Berhasil Mengganti Tempat dan Tanggal Lahir ".$this->para);
                $this->insertTglLahirSkpBaru();
                $this->askMenuEditSkpBaru();
                //$this->say("Silahkan Lihat Data Anda di /data_surat");
            }
        });
    }

    private function insertTglLahirSkpBaru() {
        DB::table('skp')->where('id_user',$this->bot->getUser()->getId())->update([
        //DB::table('skp')->where('id_user',1)->update([
            'tmpt_tgl_lahir' => $this->para,
        ]);
        return true;
    }

    public function askJkSkpBaru(){
        $question = BotManQuestion::create("Masukkan Jenis Kelamin Baru Anda?");
        $this->ask($question, function(Answer $answer){
            if( $answer->getText () != '' ){
                $this->para = $answer->getText();
                $this->say("Berhasil Mengganti Jenis Kelamin ".$this->para);
                $this->insertJkSkpBaru();
                $this->askMenuEditSkpBaru();
                //$this->say("Silahkan Lihat Data Anda di /data_surat");
            }
        });
    }

    private function insertJkSkpBaru() {
        DB::table('skp')->where('id_user',$this->bot->getUser()->getId())->update([
        //DB::table('skp')->where('id_user',1)->update([
            'jk' => $this->para,
        ]);
        return true;
    }

    public function askNoKkSkpBaru(){
        $question = BotManQuestion::create("Masukkan No KK yang Baru?");
        $this->ask($question, function(Answer $answer){
            if( $answer->getText () != '' ){
                $this->para = $answer->getText();
                $this->say("Berhasil Mengganti No KK ".$this->para);
                $this->insertNoKkSkpBaru();
                $this->askMenuEditSkpBaru();
                //$this->say("Silahkan Lihat Data Anda di /data_surat");
            }
        });
    }

    private function insertNoKkSkpBaru() {
        DB::table('skp')->where('id_user',$this->bot->getUser()->getId())->update([
        //DB::table('skp')->where('id_user',1)->update([
            'no_kk' => $this->para,
        ]);
        return true;
    }

    public function askPekerjaanSkpBaru(){
        $question = BotManQuestion::create("Masukkan Pekerjaan yang Baru?");
        $this->ask($question, function(Answer $answer){
            if( $answer->getText () != '' ){
                $this->para = $answer->getText();
                $this->say("Berhasil Mengganti Pekerjaan ".$this->para);
                $this->insertPekerjaanSkpBaru();
                $this->askMenuEditSkpBaru();
                //$this->say("Silahkan Lihat Data Anda di /data_surat");
            }
        });
    }

    private function insertPekerjaanSkpBaru() {
        DB::table('skp')->where('id_user',$this->bot->getUser()->getId())->update([
        //DB::table('skp')->where('id_user',1)->update([
            'pekerjaan' => $this->para,
        ]);
        return true;
    }

    public function askStatusSkpBaru(){
        $question = BotManQuestion::create("Masukkan Status Pernikahan yang Baru?");
        $this->ask($question, function(Answer $answer){
            if( $answer->getText () != '' ){
                $this->para = $answer->getText();
                $this->say("Berhasil Mengganti Status Pernikahan ".$this->para);
                $this->insertStatusSkpBaru();
                $this->askMenuEditSkpBaru();
                //$this->say("Silahkan Lihat Data Anda di /data_surat");
            }
        });
    }

    private function insertStatusSkpBaru() {
        DB::table('skp')->where('id_user',$this->bot->getUser()->getId())->update([
        //DB::table('skp')->where('id_user',1)->update([
            'status' => $this->para,
        ]);
        return true;
    }

    public function askAgamaSkpBaru(){
        $question = BotManQuestion::create("Masukkan Agama yang Baru?");
        $this->ask($question, function(Answer $answer){
            if( $answer->getText () != '' ){
                $this->para = $answer->getText();
                $this->say("Berhasil Mengganti Agama ".$this->para);
                $this->insertAgamaSkpBaru();
                $this->askMenuEditSkpBaru();
                //$this->say("Silahkan Lihat Data Anda di /data_surat");
            }
        });
    }

    private function insertAgamaSkpBaru() {
        DB::table('skp')->where('id_user',$this->bot->getUser()->getId())->update([
        //DB::table('skp')->where('id_user',1)->update([
            'agama' => $this->para,
        ]);
        return true;
    }

    public function askPendidikanSkpBaru(){
        $question = BotManQuestion::create("Masukkan Pendidikan Terakhir yang Baru?");
        $this->ask($question, function(Answer $answer){
            if( $answer->getText () != '' ){
                $this->para = $answer->getText();
                $this->say("Berhasil Mengganti Pendidikan Terakhir ".$this->para);
                $this->insertPendidikanSkpBaru();
                $this->askMenuEditSkpBaru();
                //$this->say("Silahkan Lihat Data Anda di /data_surat");
            }
        });
    }

    private function insertPendidikanSkpBaru() {
        DB::table('skp')->where('id_user',$this->bot->getUser()->getId())->update([
        //DB::table('skp')->where('id_user',1)->update([
            'pendidikan' => $this->para,
        ]);
        return true;
    }

    public function askAlamatSkpBaru(){
        $question = BotManQuestion::create("Masukkan Alamat Baru Anda?");
        $this->ask($question, function(Answer $answer){
            if( $answer->getText () != '' ){
                $this->para = $answer->getText();
                $this->say("Berhasil Mengganti Alamat ".$this->para);
                $this->insertAlamatSkpBaru();
                $this->askMenuEditSkpBaru();
                //$this->say("Silahkan Lihat Data Anda di /data_surat");
            }
        });
    }

    private function insertAlamatSkpBaru() {
        DB::table('skp')->where('id_user',$this->bot->getUser()->getId())->update([
        //DB::table('skp')->where('id_user',1)->update([
            'alamat_skr' => $this->para,
        ]);
        return true;
    }

}
