<?php
namespace App\Http\Controllers;

use App\Conversations\DataSkpConversation;
use App\Conversations\DataSktmConversation;
use App\Conversations\DataSuratConversation;
use App\Conversations\EditSktmConversation;
use App\Conversations\EditSuratConversation;
use App\Conversations\Example2Conversation;
use BotMan\BotMan\BotMan;
use App\Conversations\ExampleConversation;
use App\Conversations\HapusSkpConversation;
use App\Conversations\HapusSktmConversation;
use App\Conversations\HapusSuratConversation;
use App\Conversations\SkdBaruConversation;
use App\Conversations\SkdConversation;
use App\Conversations\SkpBaruConversation;
use App\Conversations\SkpConversation;
use App\Conversations\SktmBaruConversation;
use App\Conversations\SktmConversation;
use App\Conversations\StartConversation;
use App\Conversations\SuratConversation;
use App\Models\imageModel;
use App\Models\Skd;
use App\Models\Skp;
use App\Models\Sktm;
use App\Models\Test;
use BotMan\BotMan\Cache\LaravelCache;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use Illuminate\Support\Facades\DB;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use BotMan\BotMan\Storages\Storage;
use Illuminate\Support\Facades\Storage as FacadesStorage;
use BotMan\BotMan\Messages\Attachments\Image;
use Telegram\Bot\Api;

class BotManController extends Controller
{
    /**
     * Place your BotMan logic here.
     */
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

        $botman->hears('/gambar', function (BotMan $bot) {
            $bot->startConversation(new Example2Conversation);
        })->stopsConversation();

        $botman->hears('/start|start|mulai|/menu', function (BotMan $bot) {
            $bot->startConversation(new StartConversation());
        })->stopsConversation();

        $botman->hears('/buat_surat', function (BotMan $bot) {
            $bot->startConversation(new SuratConversation());
        })->stopsConversation();

        $botman->hears('/edit_surat', function (BotMan $bot) {
            $bot->startConversation(new EditSuratConversation());
        })->stopsConversation();

        $botman->hears('/edit_sktm', function (BotMan $bot) {
            $bot->startConversation(new EditSktmConversation());
        })->stopsConversation();

        $botman->hears('/sktm', function (BotMan $bot) {
            $user = $bot->getUser();
            //if (Sktm::where('id_user',$user->getId())->exists()){
            if (Sktm::where('id_user',2)->exists()){
                $bot->reply('Maaf Anda Sudah Terdaftar!!');
                $bot->reply("Silahkan Lihat Data Anda di /data_surat");
            }else{
                $bot->startConversation(new SktmConversation());
            }
        })->stopsConversation();
        $botman->hears('/skp', function (BotMan $bot) {
            if (Skp::where('id_user',2)->exists()){
                $bot->reply('Maaf Anda Sudah Terdaftar!!');
                $bot->reply("Silahkan Lihat Data Anda di /data_surat");
            }else{
                $bot->startConversation(new SkpConversation());
            }
        })->stopsConversation();
        $botman->hears('/skd', function (BotMan $bot) {
            $user = $bot->getUser();
            //if (Sktm::where('id_user',$user->getId())->exists()){
            if (Sktm::where('id_user',2)->exists()){
                $bot->reply('Maaf Anda Sudah Terdaftar!!');
                $bot->reply("Silahkan Lihat Data Anda di /data_surat");
            }else{
                $bot->startConversation(new SkdConversation());
            }
        })->stopsConversation();

        $botman->hears('/data_surat', function (BotMan $bot) {
            $bot->startConversation(new DataSuratConversation());
        })->stopsConversation();
        $botman->hears('/data_sktm', function (BotMan $bot) {
            $bot->startConversation(new DataSktmConversation());
        })->stopsConversation();
        $botman->hears('/data_skp', function (BotMan $bot) {
            $bot->startConversation(new DataSkpConversation());
        })->stopsConversation();
        $botman->hears('/hapus_surat', function (BotMan $bot) {
            $bot->startConversation(new HapusSuratConversation());
        })->stopsConversation();
        $botman->hears('/hapus_sktm', function (BotMan $bot) {
            $bot->startConversation(new HapusSktmConversation());
        })->stopsConversation();
        $botman->hears('/hapus_skp', function (BotMan $bot) {
            $bot->startConversation(new HapusSkpConversation());
        })->stopsConversation();

        $botman->fallback(function (BotMan $bot) {
            $message = $bot->getMessage()->getText();
            $bot->reply("Maaf, Perintah Ini '$message' Tidak Ada 😐");
        });

        $botman->listen();
    }
    public function sendMessageToTelegram($message)
    {
        DriverManager::loadDriver(TelegramDriver::class);

        $config = [
            // Your driver-specific configuration
            "telegram" => [
               "token" => "5415228468:AAFqFWDKM7AXhfbZIc0gHq5DG3JUhZem_Ew"
            ]
        ];

        // Create a new BotMan instance
        $botman = BotManFactory::create($config);

        // Check the database for changes to the "acc" column
        $sentMessage = false;
        while (!$sentMessage) {
            if (DB::table('test')->where('id_user',1315569563)->where('acc', '=', 'disetujui')->exists()) {
                $botman->say('The "acc" column has been updated to "disetujui"!', 1315569563);
                $sentMessage = true;
            }
            sleep(5); // Sleep for 5 seconds before checking again
        }

        // Start the conversation
        $botman->listen();
    }

}
