<?php

namespace App\Conversations\Bot1;

use App\Models\Penduduk;
use App\Models\Skp;
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

class SkpBaruConversation extends Conversation
{
    public $para;

    public function run()
    {
        $this->listNik();
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
                            DB::table('sktm')->where('id_user', $this->bot->getUser()->getId())->update(['id_user' => null]);
                            //DB::table('skp')->where('id_user', 1)->update(['id_user' => null]);
                            $this->say('Silahkan Masukkan Identitas Anda untuk Membuat SK Penduduk yang Baru!!');
                            $this->listNik();

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
            $this->listNik();
        }
    }
    public function listNik(){
        $nik_penduduk = Penduduk::all();
        $message = '';
        $this->bot->reply('Silahkan Masukkan Salah Satu Nik Dibawah Ini!!!');
        foreach ($nik_penduduk as $nik_p){
            $message .= "NIK   : " . $nik_p->nik . PHP_EOL;
        }
        $this->bot->reply($message);
        $this->askNikSkpBaru();
    }
    public function askNikSkpBaru(){
        $question = BotManQuestion::create("Masukkan Nik Anda?");
        $this->ask($question, function(Answer $answer){
            $validator = Validator::make(['answer' => $answer], [
                'answer' => 'required|integer|max:16'
            ]);

            if ($validator->fails()) {
                $this->say('Input tidak valid. Silakan coba lagi.');
                $this->askNikSkpBaru();
                return;
            } else {
                if(Penduduk::where('nik',$answer->getText ())->exists() ){
                    DB::table('skp')->where('id_user', $this->bot->getUser()->getId())->update(['id_user' => null]);
                    //DB::table('skp')->where('id_user', 1)->update(['id_user' => null]);
                    $this->para = $answer->getText();
                    $this->insertNikSkpBaru();
                    $this->bot->reply("Surat Berhasil Dibuat!!");
                    $this->cetakSkpBaru();
                } else {
                    $this->bot->reply("Maaf NIK Anda Tidak Ada Silahkan Hubungi Admin!!");
                }
            }
        });
    }
    private function insertNikSkpBaru() {
        $dataPenduduk = Penduduk::where('nik',$this->para)->get();
        $message = '';
        foreach($dataPenduduk as $p){
            DB::table('skp')->insert([
                'nama' => $p->nama,
                'nik' => $p->nik,
                'tmpt_tgl_lahir' => $p->tmpt_tgl_lahir,
                'jk' => $p->jk,
                'agama' => $p->agama,
                'pekerjaan' => $p->pekerjaan,
                'status' => $p->status,
                'pendidikan' => $p->pendidikan,
                'no_kk' => $p->no_kk,
                'alamat_skr' => $p->alamat_skr,
                //'id_user' => '1',
                'id_user' => $this->bot->getUser()->getId(),
                'id_user2' => $this->bot->getUser()->getId(),
                "created_at"=> Carbon::now(),
                "updated_at"=> Carbon::now(),
            ]);
        }
    }
    private function cetakSkpBaru()
    {
        //$user = $this->bot->getUser();
        $dataSkp = Skp::where('id_user',$this->bot->getUser()->getId())->get();
        //$dataSkp = Skp::where('id_user',1)->get();
        $message = '';
        foreach($dataSkp as $skp){
            $message .= "Berikut Data Surat Keterangan Penduduk Milik Anda :" . PHP_EOL;
            $message .= "Nama  : " . $skp->nama . PHP_EOL;
            $message .= "NIK   : " . $skp->nik . PHP_EOL;
            $message .= "No KK : " . $skp->no_kk . PHP_EOL;
            $message .= "Tempat dan Tanggal Lahir : " . $skp->tmpt_tgl_lahir . PHP_EOL;
            $message .= "Jenis Kelamin : " . $skp->jk . PHP_EOL;
            $message .= "Pekerjaan     : " . $skp->pekerjaan . PHP_EOL;
            $message .= "Agama  : " . $skp->agama . PHP_EOL;
            $message .= "Status : " . $skp->status . PHP_EOL;
            $message .= "Pendidikan: " . $skp->pendidikan . PHP_EOL;
            $message .= "Alamat : " . $skp->alamat_skr . PHP_EOL;
            $this->bot->reply($message);
            $this->say("Silahkan Klik Link Dibawah ini Untuk Mencetak Surat!!" . PHP_EOL ."https://skdurikulon.000webhostapp.com/skp/" . $skp->id_skp . "/" . $skp->id_user2 . "/" . $skp->nik);
        }
    }

}
