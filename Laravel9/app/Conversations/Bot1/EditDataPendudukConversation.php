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

class EditDataPendudukConversation extends Conversation
{
    public $para;
    public $id_user;

    public function run()
    {
        //$this->askKonfirmasi();
        $this->askNikDataPendudukBaru();
    }
    public function askKonfirmasi(){
        //if(DB::table('req_edit_penduduk')->where('id_user', 1)->where('status_acc', 'belum_disetujui')->exists() ){
        if(DB::table('req_edit_penduduk')->where('id_user', $this->bot->getUser()->getId())->exists() ){
            //DB::table('req_edit_penduduk')->where('id_user', $this->bot->getUser()->getId())->update(['id_user' => null]);
            $question = Question::create('Pemintaan Edit Data Telah Ada, Permintaan Edit Data yang Lama Akan Dihapus. Apakah Anda Ingin Lanjut?')
            ->fallback('Maaf Perintah Tidak Ada')
            ->callbackId('ask_reason')
            ->addButtons([
                Button::create('Iya')->value('/edit_penduduk_ya'),
                Button::create('Tidak')->value('/edit_penduduk_tidak'),
            ]);
            return $this->ask($question, function (Answer $answer) {
                if ($answer->isInteractiveMessageReply()) {
                    switch ($answer->getValue()) {
                        case '/edit_penduduk_ya':
                            DB::table('req_edit_penduduk')->where('id_user', $this->bot->getUser()->getId())->delete();
                            //DB::table('req_edit_penduduk')->where('id_user', 1)->delete();
                            $this->say('Permintaan Edit Data yang Sebelumnya Telah Dihapus!!!');
                            $this->askNikDataPendudukBaru();

                            break;
                        case '/edit_penduduk_tidak':
                            $this->say('Proses Edit Telah Dibatalkan!!');
                            //$this->askMenuStart();
                            break;
                        default:
                            # code...
                        break;
                    }

                }
            });
        //} elseif (DB::table('req_edit_penduduk')->where('id_user', 1)->where('status_acc', 'ditolak')->exists()){
        } elseif(DB::table('req_edit_penduduk')->where('id_user', $this->bot->getUser()->getId())->where('status_acc', 'ditolak')->exists()){
            $this->bot->reply('Permintaaan Edit Data Sebelumnya Ditolak. Silahkan Membuat Pengajuan Edit Data yang Baru!!');
            //DB::table('req_edit_penduduk')->where('id_user', 1)->where('status_acc', 'ditolak')->delete();
            DB::table('req_edit_penduduk')->where('id_user', $this->bot->getUser()->getId())->where('status_acc', 'ditolak')->delete();
        }
        else {
            $this->askNikDataPendudukBaru();
        }
    }
    public function askNikDataPendudukBaru(){
        $question = BotManQuestion::create("Silahkan Masukkan Nik Anda?");
        $this->ask($question, function(Answer $answer){
            if (DB::table('req_edit_penduduk')->where('nik_lama',$answer->getText())->where('status_acc','belum_disetujui')->exists()){
                $this->bot->reply("Maaf Perubahan Data NIK ini Belum Disetujui Oleh Admin Silahkan Menunggu!!");
            } else {
                if(Penduduk::where('nik',$answer->getText ())->exists() ){
                    $this->para = $answer->getText();
                    $this->insertNikDataPenduduk();
                    //$this->askNamaEditPenduduk();
                } else {
                    $this->bot->reply("Maaf NIK Anda Tidak Ada Silahkan Hubungi Admin!!");
                }
            }
        });
    }
    private function insertNikDataPenduduk() {
        $dataPenduduk = Penduduk::where('nik',$this->para)->get();
        $message = '';
        foreach($dataPenduduk as $p){
            DB::table('edit_nama_penduduk')->insert([
                'nik_lama' => $p->nik,
                'status_acc' => 'belum_disetujui',
                //'id_user' => 1,
                'id_user' => $this->bot->getUser()->getId(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            DB::table('edit_nik_penduduk')->insert([
                'nik_lama' => $p->nik,
                'status_acc' => 'belum_disetujui',
                //'id_user' => 1,
                'id_user' => $this->bot->getUser()->getId(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
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
    private function askNamaEditPenduduk(){
        $question = BotManQuestion::create("Masukkan Nama Baru Anda?");
        $this->ask($question, function(Answer $answer){
            $id_user = $this->bot->getUser()->getId();
            if( $answer->getText () != '' ){
                //DB::table('req_edit_enduduk')->where('id_user', $this->bot->getUser()->getId())->where('status_acc','belum_disetujui')->update();
                //DB::table('req_edit_enduduk')->where('id_user', 1)->where('status_acc','belum_disetujui')->update();
                $this->para = $answer->getText();
                $this->say("Nama Baru Anda ".$this->para);
                $this->insertNamaEditPenduduk();
                $this->askNikEditPenduduk();
            }
        });
    }
    private function insertNamaEditPenduduk() {
        DB::table('req_edit_penduduk')->where('id_user', $this->bot->getUser()->getId())->where('status_acc','belum_disetujui')->update([
        //DB::table('req_edit_penduduk')->where('id_user', 1)->where('status_acc','belum_disetujui')->update([
            'nama' => $this->para,
        ]);
        return true;
    }
    private function askNikEditPenduduk(){
        $question = BotManQuestion::create("Masukkan NIK Baru Anda?");
        $this->ask($question, function(Answer $answer){
            if( $answer->getText () != '' ){
                $this->para = $answer->getText();
                $this->say("Nik Baru Anda ".$this->para);
                $this->insertNikEditPenduduk();
                $this->askTglLahirEditPenduduk();
                //$this->say("Silahkan Lihat Data Anda di /data_surat");
            }
        });
    }
    private function insertNikEditPenduduk() {
        DB::table('req_edit_penduduk')->where('id_user', $this->bot->getUser()->getId())->where('status_acc','belum_disetujui')->update([
        //DB::table('req_edit_penduduk')->where('id_user', 1)->where('status_acc','belum_disetujui')->update([
            'nik_baru' => $this->para,
        ]);
        return true;
    }
    private function askTglLahirEditPenduduk(){
        $question = BotManQuestion::create("Masukkan Tempat Tanggal Lahir Baru Anda?");
        $this->ask($question, function(Answer $answer){
            if( $answer->getText () != '' ){
                $this->para = $answer->getText();
                $this->say("Tempat Tanggal Lahir Baru Anda ".$this->para);
                $this->insertTglLahirEditPenduduk();
                $this->askNoKkEditPenduduk();
                //$this->say("Silahkan Lihat Data Anda di /data_surat");
            }
        });
    }
    private function insertTglLahirEditPenduduk() {
        DB::table('req_edit_penduduk')->where('id_user', $this->bot->getUser()->getId())->where('status_acc','belum_disetujui')->update([
        //DB::table('req_edit_penduduk')->where('id_user', 1)->where('status_acc','belum_disetujui')->update([
            'tmpt_tgl_lahir' => $this->para,
        ]);
        return true;
    }
    private function askNoKkEditPenduduk(){
        $question = BotManQuestion::create("Masukkan No KK Baru Anda?");
        $this->ask($question, function(Answer $answer){
            if( $answer->getText () != '' ){
                $this->para = $answer->getText();
                $this->say("No KK Baru Anda ".$this->para);
                $this->insertNoKkEditPenduduk();
                $this->askNamaKepalaEditPenduduk();
                //$this->say("Silahkan Lihat Data Anda di /data_surat");
            }
        });
    }
    private function insertNoKkEditPenduduk() {
        DB::table('req_edit_penduduk')->where('id_user', $this->bot->getUser()->getId())->where('status_acc','belum_disetujui')->update([
        //DB::table('req_edit_penduduk')->where('id_user', 1)->where('status_acc','belum_disetujui')->update([
            'no_kk' => $this->para,
        ]);
        return true;
    }
    private function askNamaKepalaEditPenduduk(){
        $question = BotManQuestion::create("Masukkan Nama Kepala Keluarga Baru Anda?");
        $this->ask($question, function(Answer $answer){
            if( $answer->getText () != '' ){
                $this->para = $answer->getText();
                $this->say("Nama Kepala Keluarga Baru Anda ".$this->para);
                $this->insertNamaKepalaEditPenduduk();
                $this->askNikKepalaEditPenduduk();
                //$this->say("Silahkan Lihat Data Anda di /data_surat");
            }
        });
    }
    private function insertNamaKepalaEditPenduduk() {
        DB::table('req_edit_penduduk')->where('id_user', $this->bot->getUser()->getId())->where('status_acc','belum_disetujui')->update([
        //DB::table('req_edit_penduduk')->where('id_user', 1)->where('status_acc','belum_disetujui')->update([
            'nama_kepala' => $this->para,
        ]);
        return true;
    }
    private function askNikKepalaEditPenduduk(){
        $question = BotManQuestion::create("Masukkan NIK Kepala Keluarga Baru Anda?");
        $this->ask($question, function(Answer $answer){
            if( $answer->getText () != '' ){
                $this->para = $answer->getText();
                $this->say("NIK Kepala Keluarga Baru Anda ".$this->para);
                $this->insertNikKepalaEditPenduduk();
                $this->askJkEditPenduduk();
                //$this->say("Silahkan Lihat Data Anda di /data_surat");
            }
        });
    }
    private function insertNikKepalaEditPenduduk() {
        DB::table('req_edit_penduduk')->where('id_user', $this->bot->getUser()->getId())->where('status_acc','belum_disetujui')->update([
        //DB::table('req_edit_penduduk')->where('id_user', 1)->where('status_acc','belum_disetujui')->update([
            'nik_kepala' => $this->para,
        ]);
        return true;
    }
    private function askJkEditPenduduk(){
        $question = BotManQuestion::create("Masukkan Jenis Kelamin Baru Anda?");
        $this->ask($question, function(Answer $answer){
            if( $answer->getText () != '' ){
                $this->para = $answer->getText();
                $this->say("Jenis Kelamin Baru Anda ".$this->para);
                $this->insertJkEditPenduduk();
                $this->askPekerjaanEditPenduduk();
                //$this->say("Silahkan Lihat Data Anda di /data_surat");
            }
        });
    }
    private function insertJkEditPenduduk() {
        DB::table('req_edit_penduduk')->where('id_user', $this->bot->getUser()->getId())->where('status_acc','belum_disetujui')->update([
        //DB::table('req_edit_penduduk')->where('id_user', 1)->where('status_acc','belum_disetujui')->update([
            'jk' => $this->para,
        ]);
        return true;
    }
    public function askPekerjaanEditPenduduk(){
        $question = BotManQuestion::create("Masukkan Pekerjaan Baru Anda?");
        $this->ask($question, function(Answer $answer){
            if( $answer->getText () != '' ){
                $this->para = $answer->getText();
                $this->say("Pekerjaan Baru Anda ".$this->para);
                $this->insertPekerjaanEditPenduduk();
                $this->askStatusEditPenduduk();
                //$this->say("Silahkan Lihat Data Anda di /data_surat");
            }
        });
    }
    private function insertPekerjaanEditPenduduk() {
        DB::table('req_edit_penduduk')->where('id_user', $this->bot->getUser()->getId())->where('status_acc','belum_disetujui')->update([
        //DB::table('req_edit_penduduk')->where('id_user', 1)->where('status_acc','belum_disetujui')->update([
            'pekerjaan' => $this->para,
        ]);
        return true;
    }
    public function askStatusEditPenduduk(){
        $question = BotManQuestion::create("Masukkan Status Pernikahan Baru Anda Saat Ini?");
        $this->ask($question, function(Answer $answer){
            if( $answer->getText () != '' ){
                $this->para = $answer->getText();
                $this->say("Status Pernikahan Baru Anda Saat Ini ".$this->para);
                $this->insertStatusEditPenduduk();
                $this->askAgamaEditPenduduk();
                //$this->say("Silahkan Lihat Data Anda di /data_surat");
            }
        });
    }
    private function insertStatusEditPenduduk() {
        DB::table('req_edit_penduduk')->where('id_user', $this->bot->getUser()->getId())->where('status_acc','belum_disetujui')->update([
        //DB::table('req_edit_penduduk')->where('id_user', 1)->where('status_acc','belum_disetujui')->update([
            'status' => $this->para,
        ]);
        return true;
    }
    public function askAgamaEditPenduduk(){
        $question = BotManQuestion::create("Masukkan Agama Baru Anda?");
        $this->ask($question, function(Answer $answer){
            if( $answer->getText () != '' ){
                $this->para = $answer->getText();
                $this->say("Agama Baru Anda ".$this->para);
                $this->insertAgamaEditPenduduk();
                $this->askPendidikanEditPenduduk();
                //$this->say("Silahkan Lihat Data Anda di /data_surat");
            }
        });
    }
    private function insertAgamaEditPenduduk() {
        DB::table('req_edit_penduduk')->where('id_user', $this->bot->getUser()->getId())->where('status_acc','belum_disetujui')->update([
        //DB::table('req_edit_penduduk')->where('id_user', 1)->where('status_acc','belum_disetujui')->update([
            'agama' => $this->para,
        ]);
        return true;
    }
    public function askPendidikanEditPenduduk(){
        $question = BotManQuestion::create("Masukkan Pendidikan Terakhir Anda yang Baru?");
        $this->ask($question, function(Answer $answer){
            if( $answer->getText () != '' ){
                $this->para = $answer->getText();
                $this->say("Pendidikan Terakhir Anda yang Baru ".$this->para);
                $this->insertPendidikanEditPenduduk();
                $this->askAlamatKtpEditPenduduk();
                //$this->say("Silahkan Lihat Data Anda di /data_surat");
            }
        });
    }
    private function insertPendidikanEditPenduduk() {
        DB::table('req_edit_penduduk')->where('id_user', $this->bot->getUser()->getId())->where('status_acc','belum_disetujui')->update([
        //DB::table('req_edit_penduduk')->where('id_user', 1)->where('status_acc','belum_disetujui')->update([
            'pendidikan' => $this->para,
        ]);
        return true;
    }
    public function askAlamatKtpEditPenduduk(){
        $question = BotManQuestion::create("Masukkan Alamat Pada KTP Anda yang Baru?");
        $this->ask($question, function(Answer $answer){
            if( $answer->getText () != '' ){
                $this->para = $answer->getText();
                $this->say("Alamat KTP Baru Anda ".$this->para);
                $this->insertAlamatKtpEditPenduduk();
                $this->askAlamatSkrEditPenduduk();
                //$this->say("Silahkan Lihat Data Anda di /data_surat");
            }
        });
    }
    private function insertAlamatKtpEditPenduduk() {
        DB::table('req_edit_penduduk')->where('id_user', $this->bot->getUser()->getId())->where('status_acc','belum_disetujui')->update([
        //DB::table('req_edit_penduduk')->where('id_user', 1)->where('status_acc','belum_disetujui')->update([
            'alamat_ktp' => $this->para,
        ]);
        return true;
    }
    public function askAlamatSkrEditPenduduk(){
        $question = BotManQuestion::create("Masukkan Alamat Baru Anda Saat Ini?");
        $this->ask($question, function(Answer $answer){
            if( $answer->getText () != '' ){
                $this->para = $answer->getText();
                $this->say("Alamat Baru Anda ".$this->para);
                $this->insertAlamatSkrEditPenduduk();
                $this->say("Permintaan Edit Data Berhasil Diajukan!!");
                $this->cetakEditPenduduk();
            }
        });
    }
    private function insertAlamatSkrEditPenduduk() {
        DB::table('req_edit_penduduk')->where('id_user', $this->bot->getUser()->getId())->where('status_acc','belum_disetujui')->update([
        //DB::table('req_edit_penduduk')->where('id_user', 1)->where('status_acc','belum_disetujui')->update([
            'alamat_skr' => $this->para,
        ]);
        return true;
    }
    private function cetakEditPenduduk()
    {
        $dataEditPenduduk = DB::table('req_edit_penduduk')->where('id_user', $this->bot->getUser()->getId())->where('status_acc','belum_disetujui')->get();
        //$dataEditPenduduk = DB::table('req_edit_penduduk')->where('id_user', 1)->where('status_acc','belum_disetujui')->get();
        $message = '';
        foreach($dataEditPenduduk as $EditPenduduk){
            $message .= "Berikut Detail Permintaan Edit Data Penduduk Anda :" . PHP_EOL;
            $message .= "Nama  : " . $EditPenduduk->nama . PHP_EOL;
            $message .= "NIK      : " . $EditPenduduk->nik_baru . PHP_EOL;
            $message .= "No KK     : " . $EditPenduduk->no_kk . PHP_EOL;
            $message .= "Nama Kepala Keluarga     : " . $EditPenduduk->nama_kepala . PHP_EOL;
            $message .= "NIK Kepala Keluarga     : " . $EditPenduduk->nik_kepala . PHP_EOL;
            $message .= "Tanggal Lahir   : " . $EditPenduduk->tmpt_tgl_lahir . PHP_EOL;
            $message .= "Jenis Kelamin  : " . $EditPenduduk->jk . PHP_EOL;
            $message .= "Pekerjaan     : " . $EditPenduduk->pekerjaan . PHP_EOL;
            $message .= "Status     : " . $EditPenduduk->status . PHP_EOL;
            $message .= "Agama : " . $EditPenduduk->agama . PHP_EOL;
            $message .= "Pendidikan     : " . $EditPenduduk->pendidikan . PHP_EOL;
            $message .= "Alamat KTP      : " . $EditPenduduk->alamat_ktp . PHP_EOL;
            $message .= "Alamat Saat Ini     : " . $EditPenduduk->alamat_skr . PHP_EOL;
            $this->bot->reply($message);
        }
        $this->say("Silahkan Tunggu atau Cek Data dengan Perintah /cek_data");
    }

}
