<?php

namespace App\Conversations\Bot1\Edit;

use App\Models\Penduduk;
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
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class EditDataConversation extends Conversation
{
    public $para;
    public $id_user;
    public $nik_lama;
    public $table = '';

    public function run()
    {
        //$this->askKonfirmasi();
        $this->askNikPenduduk();
    }
    private function askNikPenduduk(){
        $question = BotManQuestion::create("Silahkan Masukkan Nik Anda.");
        $this->ask($question, function(Answer $answer){
            $validator = Validator::make(['answer' => $answer], [
                'answer' => 'required|max:16'
            ]);
            if ($validator->fails()) {
                $this->say('Input tidak valid. Silakan coba lagi.');
                $this->askNikPenduduk();
                return;
            } else {
            if(Penduduk::where('nik',$answer->getText ())->exists() ){
                $this->para = $answer->getText();
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
                }
                $this->bot->reply($message);
                $this->askMenuEdit();
            } else {
                $this->bot->reply("Maaf NIK Anda Tidak Ada Silahkan Hubungi Admin.");
            }}
        });
    }
    private function askMenuEdit()
    {
        $question = Question::create('Silahkan Pilih Menu Di Bawah!!')
            ->fallback('Maaf Perintah Tidak Ada')
            ->callbackId('ask_reason')
            ->addButtons([
                Button::create('Edit Nama')->value('/edit_nama'),
                Button::create('Edit NIK')->value('/edit_nik'),
                Button::create('Edit Kepala Keluarga')->value('/edit_kepala'),
                Button::create('Edit No KK')->value('/edit_no_kk'),
                Button::create('Edit Tempat Tgl Lahir')->value('/edit_tgl_lahir'),
                Button::create('Edit Pekerjaan')->value('/edit_pekerjaan'),
                Button::create('Edit Status Pernikahan')->value('/edit_status'),
                Button::create('Edit Agama')->value('/edit_agama'),
                Button::create('Edit Pendidikan')->value('/edit_pendidikan'),
                Button::create('Edit Alamat')->value('/edit_alamat'),
            ]);

            return $this->ask($question, function (Answer $answer) {
                if ($answer->isInteractiveMessageReply()) {
                    switch ($answer->getValue()) {
                        case '/edit_nama':
                            $table = 'nama';
                            $this->insertNikEdit($table);
                            break;
                        case '/edit_nik':
                            $table = 'nik';
                            $this->insertNikEdit($table);
                            break;
                        case '/edit_kepala':
                            $table = 'kepala';
                            $this->insertNikEdit($table);
                            break;
                        case '/edit_no_kk':
                            $table = 'no_kk';
                            $this->insertNikEdit($table);
                            break;
                        case '/edit_tgl_lahir':
                            $table = 'tgl_lahir';
                            $this->insertNikEdit($table);
                            break;
                        case '/edit_pekerjaan':
                            $table = 'pekerjaan';
                            $this->insertNikEdit($table);
                            break;
                        case '/edit_status':
                            $table = 'status';
                            $this->insertNikEdit($table);
                            break;
                        case '/edit_agama':
                            $table = 'agama';
                            $this->insertNikEdit($table);
                            break;
                        case '/edit_pendidikan':
                            $table = 'pendidikan';
                            $this->insertNikEdit($table);
                            break;
                        case '/edit_alamat':
                            $table = 'alamat';
                            $this->insertNikEdit($table);
                            break;
                        default:

                        break;
                    }

                }
            });
    }
    private function insertNikEdit($table){
        //if (DB::table('edit_'.$table.'_penduduk')->where('nik_lama',$this->para)->where('status_acc','belum_disetujui')->exists()){
        //    $this->say('Permintaan Perubahan Data Pada Nik ini Belum Disetujui');
        //} else
        if (DB::table('edit_'.$table.'_penduduk')->where('id_user', $this->bot->getUser()->getId())->where('status_acc', 'belum_disetujui')->exists()){
            DB::table('edit_'.$table.'_penduduk')->where('id_user', $this->bot->getUser()->getId())->where('status_acc', 'belum_disetujui')->update([
                'id_user' => null,
            ]);
            $this->insertNikPenduduk($table);
            $this->askMenuQ($table);
        } else {
            $this->insertNikPenduduk($table);
            $this->askMenuQ($table);
        }
    }
    private function askMenuQ($table){
        switch ($table) {
            case 'nama':
                $this->askNamaEditPenduduk();
                break;
            case 'nik':
                $this->askNikEditPenduduk();
                break;
            case 'kepala':
                $this->askNamaKepalaEditPenduduk();
                break;
            case 'no_kk':
                $this->askNoKkEditPenduduk();
                break;
            case 'tgl_lahir':
                $this->askTglLahirEditPenduduk();
                break;
            case 'pekerjaan':
                $this->askPekerjaanEditPenduduk();
                break;
            case 'status':
                $this->askStatusEditPenduduk();
                break;
            case 'agama':
                $this->askAgamaEditPenduduk();
                break;
            case 'pendidikan':
                $this->askPendidikanEditPenduduk();
                break;
            case 'alamat':
                $this->askAlamatEditPenduduk();
                break;
            default:

            break;
        }
    }
    private function insertNikPenduduk($table){
        $dataPenduduk = Penduduk::where('nik',$this->para)->get();
        $user = $this->bot->getUser();
        $fullName = $user->getFirstName() . ' ' . $user->getLastName();
        foreach($dataPenduduk as $p){
            DB::table('edit_'.$table.'_penduduk')->insert([
                'nik_lama' => $p->nik,
                'status_acc' => 'belum_disetujui',
                'nama_user' => $fullName,
                //'id_user' => 1,
                'id_user' => $this->bot->getUser()->getId(),
                'id_user2' => $this->bot->getUser()->getId(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
    private function askNamaEditPenduduk(){
        $question = BotManQuestion::create("Masukkan Nama Baru Anda.");
        $this->ask($question, function(Answer $answer){
            $validator = Validator::make(['answer' => $answer], [
                'answer' => 'required|max:100'
            ]);
            if ($validator->fails()) {
                $this->say('Input tidak valid. Silakan coba lagi.');
                $this->askNamaEditPenduduk();
                return;
            } else {
            if( $answer->getText () != '' ){
                DB::table('edit_nama_penduduk')->where('id_user', $this->bot->getUser()->getId())->where('status_acc', 'belum_disetujui')->update([
                    'nama_baru' => $answer->getText(),
                ]);
                $this->say("Data Baru Anda : " . $answer->getText());
                $this->say('Pengajuan Perubahan Data Berhasil Dikirim, Silahkan Tunggu  Beberapa Hari Lalu Cek Pada /cek_nik');
            }}
        });
    }
    private function askNikEditPenduduk(){
        $question = BotManQuestion::create("Masukkan NIK Baru Anda.");
        $this->ask($question, function(Answer $answer){
            $validator = Validator::make(['answer' => $answer], [
                'answer' => 'required|max:16'
            ]);
            if ($validator->fails()) {
                $this->say('Input tidak valid. Silakan coba lagi.');
                $this->askNikEditPenduduk();
                return;
            } else {
            if( $answer->getText () != '' ){
                DB::table('edit_nik_penduduk')->where('id_user', $this->bot->getUser()->getId())->where('status_acc', 'belum_disetujui')->update([
                    'nik_baru' => $answer->getText(),
                ]);
                $this->say("Data Baru Anda : " . $answer->getText());
                $this->say('Pengajuan Perubahan Data Berhasil Dikirim, Silahkan Tunggu  Beberapa Hari Lalu Cek Pada /cek_nik');
            }}
        });
    }
    private function askNamaKepalaEditPenduduk(){
        $question = BotManQuestion::create("Masukkan Nama Kepala Keluarga Baru Anda.");
        $this->ask($question, function(Answer $answer){
            $validator = Validator::make(['answer' => $answer], [
                'answer' => 'required|max:100'
            ]);

            if ($validator->fails()) {
                $this->say('Input tidak valid. Silakan coba lagi.');
                $this->askNamaKepalaEditPenduduk();
                return;
            } else {
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
        $question = BotManQuestion::create("Masukkan NIK Kepala Keluarga Baru Anda.");
        $this->ask($question, function(Answer $answer){
            $validator = Validator::make(['answer' => $answer], [
                'answer' => 'required|max:16'
            ]);

            if ($validator->fails()) {
                $this->say('Input tidak valid. Silakan coba lagi.');
                $this->askNikKepalaEditPenduduk();
                return;
            } else {
            if( $answer->getText () != '' ){
                DB::table('edit_kepala_penduduk')->where('id_user', $this->bot->getUser()->getId())->where('status_acc', 'belum_disetujui')->update([
                    'nik_kepala_baru' => $answer->getText(),
                ]);
                $this->say("Data Baru Anda : " . $answer->getText());
                $this->say('Pengajuan Perubahan Data Berhasil Dikirim, Silahkan Tunggu  Beberapa Hari Lalu Cek Pada /cek_nik');
            }}
        });
    }
    private function askNoKkEditPenduduk(){
        $question = BotManQuestion::create("Masukkan NO KK Baru Anda.");
        $this->ask($question, function(Answer $answer){
            $validator = Validator::make(['answer' => $answer], [
                'answer' => 'required|max:16'
            ]);

            if ($validator->fails()) {
                $this->say('Input tidak valid. Silakan coba lagi.');
                $this->askNoKkEditPenduduk();
                return;
            } else {
            if( $answer->getText () != '' ){
                DB::table('edit_no_kk_penduduk')->where('id_user', $this->bot->getUser()->getId())->where('status_acc', 'belum_disetujui')->update([
                    'no_kk_baru' => $answer->getText(),
                ]);
                $this->say("Data Baru Anda : " . $answer->getText());
                $this->say('Pengajuan Perubahan Data Berhasil Dikirim, Silahkan Tunggu  Beberapa Hari Lalu Cek Pada /cek_nik');
            }}
        });
    }
    private function askTglLahirEditPenduduk(){
        $question = BotManQuestion::create("Masukkan Tempat dan Tanggal Lahir Baru Anda.");
        $this->ask($question, function(Answer $answer){
            $validator = Validator::make(['answer' => $answer], [
                'answer' => 'required|max:100'
            ]);

            if ($validator->fails()) {
                $this->say('Input tidak valid. Silakan coba lagi.');
                $this->askTglLahirEditPenduduk();
                return;
            } else {

                if( $answer->getText () != '' ){
                    DB::table('edit_tgl_lahir_penduduk')->where('id_user', $this->bot->getUser()->getId())->where('status_acc', 'belum_disetujui')->update([
                        'tgl_lahir_baru' => $answer->getText(),
                    ]);
                    $this->say("Data Baru Anda : " . $answer->getText());
                    $this->say('Pengajuan Perubahan Data Berhasil Dikirim, Silahkan Tunggu  Beberapa Hari Lalu Cek Pada /cek_nik');
                }
            }
        });
    }
    private function askPekerjaanEditPenduduk(){
        $question = BotManQuestion::create("Masukkan Pekerjaan Baru Anda.");
        $this->ask($question, function(Answer $answer){
            $validator = Validator::make(['answer' => $answer], [
                'answer' => 'required|max:100'
            ]);

            if ($validator->fails()) {
                $this->say('Input tidak valid. Silakan coba lagi.');
                $this->askPekerjaanEditPenduduk();
                return;
            } else {
            if( $answer->getText () != '' ){
                DB::table('edit_pekerjaan_penduduk')->where('id_user', $this->bot->getUser()->getId())->where('status_acc', 'belum_disetujui')->update([
                    'pekerjaan_baru' => $answer->getText(),
                ]);
                $this->say("Data Baru Anda : " . $answer->getText());
                $this->say('Pengajuan Perubahan Data Berhasil Dikirim, Silahkan Tunggu  Beberapa Hari Lalu Cek Pada /cek_nik');
            }}
        });
    }
    private function askStatusEditPenduduk(){
        $question = BotManQuestion::create("Masukkan Status Pernikahan Anda yang Baru.");
        $this->ask($question, function(Answer $answer){
            $validator = Validator::make(['answer' => $answer], [
                'answer' => 'required|max:100'
            ]);

            if ($validator->fails()) {
                $this->say('Input tidak valid. Silakan coba lagi.');
                $this->askStatusEditPenduduk();
                return;
            } else {

                if( $answer->getText () != '' ){
                    DB::table('edit_status_penduduk')->where('id_user', $this->bot->getUser()->getId())->where('status_acc', 'belum_disetujui')->update([
                        'status_baru' => $answer->getText(),
                    ]);
                    $this->say("Data Baru Anda : " . $answer->getText());
                    $this->say('Pengajuan Perubahan Data Berhasil Dikirim, Silahkan Tunggu  Beberapa Hari Lalu Cek Pada /cek_nik');
                }
            }
        });
    }
    private function askAgamaEditPenduduk(){
        $question = BotManQuestion::create("Masukkan Agama Baru Anda.");
        $this->ask($question, function(Answer $answer){
            $validator = Validator::make(['answer' => $answer], [
                'answer' => 'required|max:100'
            ]);

            if ($validator->fails()) {
                $this->say('Input tidak valid. Silakan coba lagi.');
                $this->askAgamaEditPenduduk();
                return;
            } else {
            if( $answer->getText () != '' ){
                DB::table('edit_agama_penduduk')->where('id_user', $this->bot->getUser()->getId())->where('status_acc', 'belum_disetujui')->update([
                //DB::table('edit_agama_penduduk')->where('id_user', 1)->where('status_acc', 'belum_disetujui')->update([
                    'agama_baru' => $answer->getText(),
                ]);
                $this->say("Data Baru Anda : " . $answer->getText());
                $this->say('Pengajuan Perubahan Data Berhasil Dikirim, Silahkan Tunggu Beberapa Hari Lalu Cek Perubahan Pada /cek_nik');
            }}
        });
    }
    private function askPendidikanEditPenduduk(){
        $question = BotManQuestion::create("Masukkan Pendidikan Terakhir Anda yang Terbaru.");
        $this->ask($question, function(Answer $answer){
            $validator = Validator::make(['answer' => $answer], [
                'answer' => 'required|max:100'
            ]);

            if ($validator->fails()) {
                $this->say('Input tidak valid. Silakan coba lagi.');
                $this->askPendidikanEditPenduduk();
                return;
            } else {
            if( $answer->getText () != '' ){
                DB::table('edit_pendidikan_penduduk')->where('id_user', $this->bot->getUser()->getId())->where('status_acc', 'belum_disetujui')->update([
                    'pendidikan_baru' => $answer->getText(),
                ]);
                $this->say("Data Baru Anda : " . $answer->getText());
                $this->say('Pengajuan Perubahan Data Berhasil Dikirim, Silahkan Tunggu  Beberapa Hari Lalu Cek Pada /cek_nik');
            }}

        });
    }
    private function askAlamatEditPenduduk(){
        $question = BotManQuestion::create("Masukkan Alamat Baru Anda.");
        $this->ask($question, function(Answer $answer){
            $validator = Validator::make(['answer' => $answer], [
                'answer' => 'required|max:1000'
            ]);

            if ($validator->fails()) {
                $this->say('Input tidak valid. Silakan coba lagi.');
                $this->askAlamatEditPenduduk();
                return;
            } else {
            if( $answer->getText () != '' ){
                DB::table('edit_alamat_penduduk')->where('id_user', $this->bot->getUser()->getId())->where('status_acc', 'belum_disetujui')->update([
                //DB::table('edit_alamat_penduduk')->where('id_user', 1)->where('status_acc', 'belum_disetujui')->update([
                    'alamat_baru' => $answer->getText(),
                ]);
                $this->say("Data Baru Anda : " . $answer->getText());
                $this->say('Pengajuan Perubahan Data Berhasil Dikirim, Silahkan Tunggu  Beberapa Hari Lalu Cek Pada /cek_nik');
            }}
        });
    }
}
