<?php

namespace App\Conversations\Bot1\Edit;

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

class EditKepalaConversation extends Conversation
{
    public $para;
    public $id_user;
    public $nik_lama;

    public function run()
    {
        //$this->askKonfirmasi();
        $this->askNikPenduduk();
    }
    private function askNikPenduduk(){
        $question = BotManQuestion::create("Silahkan Masukkan Nik Anda?");
        $this->ask($question, function(Answer $answer){
            if(Penduduk::where('nik',$answer->getText ())->exists() ){
                $this->para = $answer->getText();
                if (DB::table('edit_kepala_penduduk')->where('nik_lama',$answer->getText())->where('status_acc','belum_disetujui')->exists()){
                    $this->say('Permintaan Perubahan Data Pada Nik ini Belum Disetujui');
                //} elseif (DB::table('edit_kepala_penduduk')->where('id_user2', 1)->where('status_acc', 'disetujui')->where('nik_lama',$answer->getText())->exists()) {
                } elseif (DB::table('edit_kepala_penduduk')->where('id_user2', $this->bot->getUser()->getId())->where('status_acc', 'disetujui')->where('nik_lama',$answer->getText())->exists()) {
                    $this->bot->reply('Permintaaan Edit Data Sebelumnya Telah Disetujui!!');
                    DB::table('edit_kepala_penduduk')->where('id_user2', $this->bot->getUser()->getId())->where('status_acc', 'disetujui')->where('nik_lama',$answer->getText())->delete();
                    $this->insertNikKepala();
                    $this->askNamaKepalaEditPenduduk();
                }elseif (DB::table('edit_kepala_penduduk')->where('id_user2', $this->bot->getUser()->getId())->where('status_acc', 'ditolak')->where('nik_lama',$answer->getText())->exists()){
                    $this->bot->reply('Permintaaan Edit Data Sebelumnya Telah Ditolak!!');
                    DB::table('edit_kepala_penduduk')->where('id_user', $this->bot->getUser()->getId())->where('status_acc', 'ditolak')->where('nik_lama',$answer->getText())->delete();
                    $this->insertNikKepala();
                    $this->askNamaKepalaEditPenduduk();
                }
                else{
                    if (DB::table('edit_kepala_penduduk')->where('id_user', $this->bot->getUser()->getId())->where('status_acc', 'belum_disetujui')->exists()){
                        DB::table('edit_kepala_penduduk')->where('id_user', $this->bot->getUser()->getId())->where('status_acc', 'belum_disetujui')->update([
                            'id_user' => null,
                        ]);
                        $this->insertNikKepala();
                    } else {
                        $this->insertNikKepala();
                    }
                    $this->askNamaKepalaEditPenduduk();
                }
            } else {
                $this->bot->reply("Maaf NIK Anda Tidak Ada Silahkan Hubungi Admin!!");
            }
        });
    }
    private function insertNikKepala(){
        $dataPenduduk = Penduduk::where('nik',$this->para)->get();
        $message = '';
        $user = $this->bot->getUser();
        $fullName = $user->getFirstName() . ' ' . $user->getLastName();
        foreach($dataPenduduk as $p){
            DB::table('edit_kepala_penduduk')->insert([
                'nik_lama' => $p->nik,
                'status_acc' => 'belum_disetujui',
                'nama_user' => $fullName,
                'id_user' => $this->bot->getUser()->getId(),
                'id_user2' => $this->bot->getUser()->getId(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            $message .= "Berikut Data Anda :" . PHP_EOL;
            $message .= "Nama  : " . $p->nama . PHP_EOL;
            $message .= "NIK  : " . $p->nik . PHP_EOL;
            $message .= "Nama Kepala Keluarga : " . $p->nama_kepala . PHP_EOL;
            $message .= "NIK Kepala Keluarga : " . $p->nik_kepala . PHP_EOL;
        }
        $this->bot->reply($message);
    }
    private function askNamaKepalaEditPenduduk(){
        $question = BotManQuestion::create("Masukkan Nama Kepala Keluarga Baru Anda?");
        $this->ask($question, function(Answer $answer){
            $validator = Validator::make(['answer' => $answer], [
                'answer' => 'required|max:100'
            ]);

            if ($validator->fails()) {
                $this->say('Input tidak valid. Silakan coba lagi.');
                $this->askNamaKepalaEditPenduduk();
                return;
            } else {
            $id_user = $this->bot->getUser()->getId();
            if( $answer->getText () != '' ){
                DB::table('edit_kepala_penduduk')->where('id_user', $this->bot->getUser()->getId())->where('status_acc', 'belum_disetujui')->update([
                    'nama_kepala_baru' => $answer->getText(),
                ]);
                $this->say("Data Baru Anda : " . $answer->getText());
                $this->askNikKepalaEditPenduduk();
            }}
        });
    }
    private function askNikKepalaEditPenduduk(){
        $question = BotManQuestion::create("Masukkan NIK Kepala Keluarga Baru Anda?");
        $this->ask($question, function(Answer $answer){
            $validator = Validator::make(['answer' => $answer], [
                'answer' => 'required|max:16'
            ]);

            if ($validator->fails()) {
                $this->say('Input tidak valid. Silakan coba lagi.');
                $this->askNikKepalaEditPenduduk();
                return;
            } else {
            $id_user = $this->bot->getUser()->getId();
            if( $answer->getText () != '' ){
                DB::table('edit_kepala_penduduk')->where('id_user', $this->bot->getUser()->getId())->where('status_acc', 'belum_disetujui')->update([
                    'nik_kepala_baru' => $answer->getText(),
                ]);
                $this->say("Data Baru Anda : " . $answer->getText());
                $this->say('Pengajuan Perubahan Data Berhasil Dikirim, Silahkan Tunggu  Beberapa Hari Lalu Cek Pada /cek_nik');
            }}
        });
    }
}
