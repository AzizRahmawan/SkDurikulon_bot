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

class EditJkConversation extends Conversation
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
                if (DB::table('edit_jk_penduduk')->where('id_user2',1)->where('nik_lama',$answer->getText())->where('status_acc','belum_disetujui')->exists()){
                    $this->say('Permintaan Perubahan Data Pada Nik ini Belum Disetujui');
                } else{
                    if (DB::table('edit_jk_penduduk')->where('id_user',1)->where('status_acc','belum_disetujui')->exists()){
                        DB::table('edit_jk_penduduk')->where('id_user',1)->where('status_acc','belum_disetujui')->update([
                            'id_user' => null,
                        ]);
                        $dataPenduduk = Penduduk::where('nik',$this->para)->get();
                        $message = '';
                        foreach($dataPenduduk as $p){
                            DB::table('edit_jk_penduduk')->insert([
                                'nik_lama' => $p->nik,
                                'status_acc' => 'belum_disetujui',
                                'id_user' => 1,
                                'id_user2' => 1,
                                //'id_user' => $this->bot->getUser()->getId(),
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now(),
                            ]);
                            $message .= "Berikut Data Anda :" . PHP_EOL;
                            $message .= "Jenis Kelamin : " . $p->jk . PHP_EOL;
                        }
                    } else {
                        $dataPenduduk = Penduduk::where('nik',$this->para)->get();
                        $message = '';
                        foreach($dataPenduduk as $p){
                            DB::table('edit_jk_penduduk')->insert([
                                'nik_lama' => $p->nik,
                                'status_acc' => 'belum_disetujui',
                                'id_user' => 1,
                                'id_user2' => 1,
                                //'id_user' => $this->bot->getUser()->getId(),
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now(),
                            ]);
                            $message .= "Berikut Data Anda :" . PHP_EOL;
                            $message .= "Jenis Kelamin : " . $p->jk . PHP_EOL;
                        }
                    }
                    $this->bot->reply($message);
                    $this->askJkEditPenduduk();
                }
            } else {
                $this->bot->reply("Maaf NIK Anda Tidak Ada Silahkan Hubungi Admin!!");
            }
        });
    }
    private function askJkEditPenduduk(){
        $question = BotManQuestion::create("Masukkan Jenis Kelamin Anda?");
        $this->ask($question, function(Answer $answer){
            $id_user = $this->bot->getUser()->getId();
            if( $answer->getText () != '' ){
                //DB::table('edit_jk_penduduk')->where('id_user', $this->bot->getUser()->getId())->where('status_acc', 'belum_disetujui')->update([
                DB::table('edit_jk_penduduk')->where('id_user', 1)->where('status_acc', 'belum_disetujui')->update([
                    'jk_baru' => $answer->getText(),
                ]);
                $this->say("Data Baru Anda : " . $answer->getText());
                $this->say('Pengajuan Perubahan Data Berhasil Dikirim, Silahkan Tunggu  Beberapa Hari Lalu Cek Pada /cek_nik');
            }
        });
    }
}
