<?php

namespace App\Http\Controllers;

use App\Conversations\Bot1\BuatSuratConversation as Bot1BuatSuratConversation;
use App\Conversations\Bot1\CekDataPendudukConversation as Bot1CekDataPendudukConversation;
use App\Conversations\Bot1\DataSkpConversation as Bot1DataSkpConversation;
use App\Conversations\Bot1\DataSkdConversation as Bot1DataSkdConversation;
use App\Conversations\Bot1\SkdBaruConversation as Bot1SkdBaruConversation;
use App\Conversations\Bot1\SkpBaruConversation as Bot1SkpBaruConversation;
use App\Conversations\Bot1\SktmBaruConversation as Bot1SktmBaruConversation;
use App\Conversations\Bot1\StartConversation as Bot1StartConversation;
use App\Conversations\Bot1\DataSktmConversation as Bot1DataSktmConversation;
use App\Conversations\Bot1\DataSuratConversation as Bot1DataSuratConversation;
use App\Conversations\Bot1\Edit\EditDataConversation as Bot1EditDataConversation;
use App\Conversations\Bot1\Edit\EditNamaConversation as Bot1EditNamaConversation;
use App\Conversations\Bot1\Edit\EditNikConversation as Bot1EditNikConversation;
use App\Conversations\Bot1\Edit\EditAgamaConversation;
use App\Conversations\Bot1\Edit\EditAlamatConversation;
use App\Conversations\Bot1\Edit\EditKepalaConversation;
use App\Conversations\Bot1\Edit\EditNoKkConversation;
use App\Conversations\Bot1\Edit\EditPekerjaanConversation;
use App\Conversations\Bot1\Edit\EditPendidikanConversation;
use App\Conversations\Bot1\Edit\EditStatusConversation;
use App\Conversations\Bot1\Edit\EditTglLahirConversation;
use App\Models\imageModel;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Cache\LaravelCache;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class Bot1Controller extends Controller
{

    //
    public function handle()
    {
        // Load the driver(s) you want to use
        DriverManager::loadDriver(\BotMan\Drivers\Telegram\TelegramDriver::class);

        $config = [
            // Your driver-specific configuration
            "telegram" => [
               "token" => "5995297911:AAF_9Ot_ZUwTc3KGX-CLz0w2SUj4qjUH5TU"

            ]
        ];
        $botman = BotManFactory::create($config, new LaravelCache());

        $botman->hears('/start|start|mulai|/menu', function (BotMan $bot) {
            $bot->startConversation(new Bot1StartConversation());
        })->stopsConversation();

        $botman->hears('/buat_surat', function (BotMan $bot) {
            $bot->startConversation(new Bot1BuatSuratConversation());
        })->stopsConversation();

        $botman->hears('/sktm', function (BotMan $bot) {
            $user = $bot->getUser();
            $bot->startConversation(new Bot1SktmBaruConversation());
        })->stopsConversation();
        $botman->hears('/skp', function (BotMan $bot) {
            $bot->startConversation(new Bot1SkpBaruConversation());
        })->stopsConversation();
        $botman->hears('/skd', function (BotMan $bot) {
            $bot->startConversation(new Bot1SkdBaruConversation());
        })->stopsConversation();

        $botman->hears('/cek_nik', function (BotMan $bot) {
            $bot->startConversation(new Bot1CekDataPendudukConversation);
        })->stopsConversation();

        $botman->hears('/data_surat', function (BotMan $bot) {
            $bot->startConversation(new Bot1DataSuratConversation);
        })->stopsConversation();

        $botman->hears('/data_sktm', function (BotMan $bot) {
            $bot->startConversation(new Bot1DataSktmConversation);
        })->stopsConversation();

        $botman->hears('/data_skp', function (BotMan $bot) {
            $bot->startConversation(new Bot1DataSkpConversation);
        })->stopsConversation();

        $botman->hears('/data_skd', function (BotMan $bot) {
            $bot->startConversation(new Bot1DataSkdConversation);
        })->stopsConversation();

        $botman->hears('/edit_data', function (BotMan $bot) {
            $bot->startConversation(new Bot1EditDataConversation);
        })->stopsConversation();

        $botman->hears('/edit_nama', function (BotMan $bot) {
            $bot->startConversation(new Bot1EditNamaConversation);
        })->stopsConversation();

        $botman->hears('/edit_nik', function (BotMan $bot) {
            $bot->startConversation(new Bot1EditNikConversation);
        })->stopsConversation();

        $botman->hears('/edit_kepala', function (BotMan $bot) {
            $bot->startConversation(new EditKepalaConversation);
        })->stopsConversation();

        $botman->hears('/edit_no_kk', function (BotMan $bot) {
            $bot->startConversation(new EditNoKkConversation);
        })->stopsConversation();

        $botman->hears('/edit_tgl_lahir', function (BotMan $bot) {
            $bot->startConversation(new EditTglLahirConversation);
        })->stopsConversation();

        $botman->hears('/edit_pekerjaan', function (BotMan $bot) {
            $bot->startConversation(new EditPekerjaanConversation);
        })->stopsConversation();

        $botman->hears('/edit_status', function (BotMan $bot) {
            $bot->startConversation(new EditStatusConversation);
        })->stopsConversation();

        $botman->hears('/edit_agama', function (BotMan $bot) {
            $bot->startConversation(new EditAgamaConversation);
        })->stopsConversation();

        $botman->hears('/edit_pendidikan', function (BotMan $bot) {
            $bot->startConversation(new EditPendidikanConversation);
        })->stopsConversation();

        $botman->hears('/edit_alamat', function (BotMan $bot) {
            $bot->startConversation(new EditAlamatConversation);
        })->stopsConversation();

        $botman->receivesImages(function($bot, $images) {
            foreach ($images as $image) {

                $url = $image->getUrl(); // The direct url
                $title = $image->getTitle(); // The title, if available
                $payload = $image->getPayload(); // The original payload
            }
            $bot->reply('Judul Gambar' .$title);
        });

        $botman->listen();

        $botman->fallback(function (BotMan $bot) {
            $message = $bot->getMessage()->getText();
            $bot->reply("Maaf, Perintah Ini '$message' Tidak Ada 😐");
        });

        $botman->listen();
    }
}
?>
