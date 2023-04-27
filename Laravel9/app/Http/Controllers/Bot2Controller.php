<?php

namespace App\Http\Controllers;

use App\Conversations\Bot2\BuatSuratConversation as Bot2BuatSuratConversation;
use App\Conversations\Bot2\DataSkdConversation as Bot2DataSkdConversation;
use App\Conversations\Bot2\DataSkpConversation as Bot2DataSkpConversation;
use App\Conversations\Bot2\DataSktmConversation as Bot2DataSktmConversation;
use App\Conversations\Bot2\DataSuratConversation as Bot2DataSuratConversation;
use App\Conversations\Bot2\EditSkdConversation as Bot2EditSkdConversation;
use App\Conversations\Bot2\EditSkpConversation as Bot2EditSkpConversation;
use App\Conversations\Bot2\EditSktmConversation as Bot2EditSktmConversation;
use App\Conversations\Bot2\EditSuratConversation as Bot2EditSuratConversation;
use App\Conversations\Bot2\SkdConversation as Bot2SkdConversation;
use App\Conversations\Bot2\SkpConversation as Bot2SkpConversation;
use App\Conversations\Bot2\SktmConversation as Bot2SktmConversation;
use App\Conversations\Bot2\StartConversation as Bot2StartConversation;
use App\Models\imageModel;
use BotMan\BotMan\BotMan;
use App\Models\Skd;
use App\Models\Skp;
use App\Models\Sktm;
use BotMan\BotMan\Cache\LaravelCache;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;

class Bot2Controller extends Controller
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

        $botman->hears('/start|start|mulai|/menu', function (BotMan $bot) {
            $bot->startConversation(new Bot2StartConversation());
        })->stopsConversation();

        $botman->hears('/buat_surat', function (BotMan $bot) {
            $bot->startConversation(new Bot2BuatSuratConversation());
        })->stopsConversation();

        $botman->hears('/sktm', function (BotMan $bot) {
            $bot->startConversation(new Bot2SktmConversation);
        })->stopsConversation();
        $botman->hears('/skp', function (BotMan $bot) {
            $bot->startConversation(new Bot2SkpConversation);
        })->stopsConversation();
        $botman->hears('/skd', function (BotMan $bot) {
            $bot->startConversation(new Bot2SkdConversation);
        })->stopsConversation();

        $botman->hears('/data_surat', function (BotMan $bot) {
            $bot->startConversation(new Bot2DataSuratConversation());
        })->stopsConversation();
        $botman->hears('/data_sktm', function (BotMan $bot) {
            $bot->startConversation(new Bot2DataSktmConversation());
        })->stopsConversation();
        $botman->hears('/data_skp', function (BotMan $bot) {
            $bot->startConversation(new Bot2DataSkpConversation());
        })->stopsConversation();
        $botman->hears('/data_skd', function (BotMan $bot) {
            $bot->startConversation(new Bot2DataSkdConversation);
        })->stopsConversation();

        $botman->hears('/edit_surat', function (BotMan $bot) {
            $bot->startConversation(new Bot2EditSuratConversation);
        })->stopsConversation();
        $botman->hears('/edit_sktm', function (BotMan $bot) {
            $bot->startConversation(new Bot2EditSktmConversation());
        })->stopsConversation();
        $botman->hears('/edit_skp', function (BotMan $bot) {
            $bot->startConversation(new Bot2EditSkpConversation());
        })->stopsConversation();
        $botman->hears('/edit_skd', function (BotMan $bot) {
            $bot->startConversation(new Bot2EditSkdConversation());
        })->stopsConversation();

        $botman->hears('/hapus_sktm', function (BotMan $bot) {
            $bot->startConversation(new Bot2EditSktmConversation());
        })->stopsConversation();
        $botman->hears('/hapus_skp', function (BotMan $bot) {
            $bot->startConversation(new Bot2EditSkpConversation());
        })->stopsConversation();
        $botman->hears('/hapus_skd', function (BotMan $bot) {
            $bot->startConversation(new Bot2EditSkdConversation());
        })->stopsConversation();
        $botman->hears('/kirim_gambar', function (BotMan $bot) {
            // Mengirim pesan untuk meminta gambar
            $bot->reply("Silakan kirim gambar.");

            // Menerima gambar dari pengguna
            $bot->on('photo', function (BotMan $bot, $photo) {
                // Mendapatkan ID pengguna
                $user_id = $bot->getUser()->getId();

                // Mendapatkan URL untuk gambar
                $url = $photo[0]['file_id'];

                // Mendapatkan konten gambar
                $content = file_get_contents("https://api.telegram.org/bot5415228468:AAFqFWDKM7AXhfbZIc0gHq5DG3JUhZem_Ew/getFile?file_id=" . $url);
                $result = json_decode($content, true);
                $file_path = $result['result']['file_path'];
                $url = "https://api.telegram.org/file/bot5415228468:AAFqFWDKM7AXhfbZIc0gHq5DG3JUhZem_Ew/" . $file_path;
                $content = file_get_contents($url);

                // Konversi konten gambar menjadi data biner
                $binary = base64_encode($content);

                // Simpan data gambar ke database
                $image = new imageModel();
                $image->url = $url;
                $image->title = $binary;
                $image->save();

                // Mengirim konfirmasi ke pengguna
                $bot->reply('Gambar berhasil disimpan.');
            });
        });
        $botman->receivesImages(function($bot, $images) {

            foreach ($images as $image) {

                $url = $image->getUrl(); // The direct url
                $title = $image->getTitle(); // The title, if available
                $payload = $image->getPayload(); // The original payload
                $bot->reply($payload);
            }
        });

        $botman->fallback(function (BotMan $bot) {
            $message = $bot->getMessage()->getText();
            $bot->reply("Maaf, Perintah Ini '$message' Tidak Ada 😐");
        });

        $botman->listen();
    }
}
