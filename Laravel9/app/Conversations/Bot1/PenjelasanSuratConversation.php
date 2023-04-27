<?php

namespace App\Conversations\Bot1;

use Illuminate\Foundation\Inspiring;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Outgoing\Question as BotManQuestion;
use BotMan\BotMan\Messages\Incoming\Answer as BotManAnswer;
use Illuminate\Support\Facades\DB;


class PenjelasanSuratConversation extends Conversation
{
    public $para;
    public $id_user;

    public function askJenisSurat()
    {
        $question = Question::create('Silahkan Pilih Jenis Surat Di Bawah.')
            ->fallback('Maaf Perintah Tidak Ada')
            ->callbackId('ask_reason')
            ->addButtons([
                Button::create('SKTM')->value('/penjelasan_sktm'),
                Button::create('SK Penduduk!')->value('/penjelasan_skp'),
                Button::create('SK Domisili!')->value('/penjelasan_skd'),
            ]);

        $this->ask($question, function (Answer $answer) {
            // Detect if button was clicked:
                if ($answer->isInteractiveMessageReply()) {
                    switch ($answer->getValue()) {
                        case '/penjelasan_sktm':
                            $this->say('Surat Keterangan Tidak Mampu (SKTM) adalah surat yang diterbitkan oleh pihak Desa/Kelurahan untuk keluarga kurang mampu. SKTM ini berfungsi meringankan biaya bagi keluarga kurang mampu untuk seperi biaya pendidikan, biaya perawatan/pengobatan di rumah sakit dan keperluan lain yang memang membutuhkan SKTM.');
                            break;
                        case '/penjelasan_skp':
                            $this->say('Surat Keterangan Penduduk adalah surat yang diterbitkan oleh pihak Desa/Kelurahan yang menyatakan bahwa yang bersangkutan merupakan warga desa tersebut. Surat Keterangan Penduduk ini bisa digunakan sebagai ganti KTP ketika eKTP masih dalam proses.');
                            break;
                        case '/penjelasan_skd':
                            $this->say('Surat Keterangan Domisili adalah surat/bukti dokumentasi bagi pendatang dari luar daerah yang diterbitkan oleh pihak Desa/Kelurahan untuk menyatakan domisili/tempat tinggal seseorang.');
                            break;
                        default:

                        break;
                    }

                }
        });
    }
    /**
     * Start the conversation
     */
    public function run()
    {
        $this->askJenisSurat();
        // $this->cariLagi();
    }
}
