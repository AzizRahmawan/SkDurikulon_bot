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

class CekDataPendudukConversation extends Conversation
{
    public $para;
    public $id_user;

    public function run()
    {
        $this->statusAcc();
        // $this->cariLagi();
    }
    public function askKonfirmasi(){
        //if(DB::table('req_edit_penduduk')->where('id_user', 1)->where('status_acc', 'disetujui')->exists() ){
        if(DB::table('req_edit_penduduk')->where('id_user', $this->bot->getUser()->getId())->where('status_acc', 'disetujui')->exists() ){
            $this->bot->reply('Permintaaan Edit Data Sebelumnya Telah Disetujui!!');
            DB::table('req_edit_penduduk')->where('id_user', $this->bot->getUser()->getId())->where('status_acc', 'disetujui')->delete();
            //DB::table('req_edit_penduduk')->where('id_user', 1)->where('status_acc', 'disetujui')->delete();
            $this->askNikDataPendudukBaru();
        //} elseif(DB::table('req_edit_penduduk')->where('id_user', 1)->where('status_acc', 'ditolak')->exists() ) {
        } elseif(DB::table('req_edit_penduduk')->where('id_user', $this->bot->getUser()->getId())->where('status_acc', 'ditolak')->exists() ){
            $this->bot->reply('Permintaaan Edit Data Sebelumnya Ditolak!!');
            //DB::table('req_edit_penduduk')->where('id_user', 1)->where('status_acc', 'ditolak')->delete();
            DB::table('req_edit_penduduk')->where('id_user', $this->bot->getUser()->getId())->where('status_acc', 'ditolak')->delete();
            $this->askNikDataPendudukBaru();
        } else {
            $this->askNikDataPendudukBaru();
        }
    }
    public function statusAcc(){
        //if(DB::table('edit_nama_penduduk')->where('id_user', $this->bot->getUser()->getId())->where('status_acc', 'disetujui')->exists() ){
        if(DB::table('edit_nama_penduduk')->where('id_user2', 1)->where('status_acc', 'disetujui')->exists() ){
            $this->bot->reply('Permintaaan Edit Data Sebelumnya Telah Disetujui!!');
            //DB::table('req_edit_penduduk')->where('id_user', $this->bot->getUser()->getId())->where('status_acc', 'disetujui')->delete();
            DB::table('edit_nama_penduduk')->where('id_user2', 1)->where('status_acc', 'disetujui')->delete();
            $this->askNikDataPendudukBaru();
        //} elseif(DB::table('req_edit_penduduk')->where('id_user', 1)->where('status_acc', 'ditolak')->exists() ) {
        } elseif(DB::table('edit_nama_penduduk')->where('id_user2', 1)->where('status_acc', 'ditolak')->exists() ){
            $this->bot->reply('Permintaaan Edit Data Sebelumnya Ditolak!!');
            DB::table('edit_nama_penduduk')->where('id_user2', 1)->where('status_acc', 'ditolak')->delete();
            //DB::table('edit_nama_penduduk')->where('id_user2', $this->bot->getUser()->getId())->where('status_acc', 'ditolak')->delete();
            $this->askNikDataPendudukBaru();
        } else {
            $this->askNikDataPendudukBaru();
        }
    }
    public function askNikDataPendudukBaru(){
        $question = BotManQuestion::create("Masukkan Nik Anda?");
        $this->ask($question, function(Answer $answer){
            $validator = Validator::make(['answer' => $answer], [
                'answer' => 'required|integer|max:16'
            ]);
            if ($validator->fails()) {
                $this->say('Input tidak valid. Silakan coba lagi.');
                $this->askNikDataPendudukBaru();
                return;
            }else{
                if(Penduduk::where('nik',$answer->getText ())->exists() ){
                    $this->para = $answer->getText();
                    $this->showNikDataPenduduk();
                    //$this->insertNikDataPenduduk();
                } else {
                    $this->bot->reply("Maaf NIK Anda Tidak Ada Silahkan Hubungi Admin!!");
                }
            }
        });
    }
    private function showNikDataPenduduk() {
        $dataPenduduk = Penduduk::where('nik',$this->para)->get();
        $message = '';
        foreach($dataPenduduk as $p){
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
            $message .= "Nama Kepala Keluarga : " . $p->nama_kepala . PHP_EOL;
            $message .= "NIK Kepala Keluarga     : " . $p->nik_kepala . PHP_EOL;
            $message .= "Alamat pada KTP     : " . $p->alamat_ktp . PHP_EOL;
            $message .= "Alamat Sekarang     : " . $p->alamat_skr . PHP_EOL;
        }
        $this->bot->reply($message);
    }

}
