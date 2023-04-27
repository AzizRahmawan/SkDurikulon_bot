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
use App\Conversations\Bot1\PenjelasanSuratConversation;
use App\Conversations\Bot1\TentangConversation;
use App\Conversations\Bot1\TutorialConversation;
use App\Models\imageModel;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Cache\LaravelCache;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\BotMan\Messages\Attachments\Video;
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
               "token" => "5415228468:AAFqFWDKM7AXhfbZIc0gHq5DG3JUhZem_Ew"

            ]
        ];
        $botman = BotManFactory::create($config, new LaravelCache());

        $botman->hears('/start|mulai|/menu', function (BotMan $bot) {
            $bot->startConversation(new Bot1StartConversation());
        })->stopsConversation();

        $botman->hears('/buat_surat', function (BotMan $bot) {
            $bot->startConversation(new Bot1BuatSuratConversation());
        })->stopsConversation();

        $botman->hears('/penjelasan_surat', function (BotMan $bot) {
            $bot->startConversation(new PenjelasanSuratConversation);
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

        $botman->hears('/tutorial', function (BotMan $bot){
            $bot->startConversation(new TutorialConversation);
        })->stopsConversation();

        $botman->hears('/tentang', function (BotMan $bot){
            $bot->startConversation(new TentangConversation);
        })->stopsConversation();

        $botman->fallback(function (BotMan $bot) {
            $message = $bot->getMessage()->getText();
            $bot->reply("Maaf, Perintah Ini '$message' Tidak Ada 😐");
        });

        $botman->listen();
    }
}
?>
