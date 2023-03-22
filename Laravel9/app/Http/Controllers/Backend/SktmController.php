<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Penduduk;
use App\Models\Sktm;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Cache\LaravelCache;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\Drivers\Telegram\TelegramDriver;

class SktmController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(){
        $sktm = Sktm::all();
        return view('backend.sktm.index',compact('sktm'));
    }
    public function create(){
        $penduduk = Penduduk::all();
        return view('backend.sktm.cariNik', compact('penduduk'));
    }

    public function cariNik(Request $request){
        $cari = $request->nik;

        $penduduk = DB::table('penduduk')->where('nik',$cari)->get();

        return view('backend.sktm.create', compact('penduduk'));
    }
    public function store(Request $request){
        DB::table('sktm')->insert([
            'nama' => $request->nama,
            'nik' => $request->nik,
            'tmpt_tgl_lahir' => $request->tgl_lahir,
            'jk' => $request->jk,
            'nama_kepala' => $request->nama_kk,
            'nik_kepala' => $request->nik_kk,
            'alamat_skr' => $request->alamat,
            //$request->kolom => $request->alamat,
            'keperluan' => $request->keperluan,
            'id_user' => '1',
            //'id_user' => $this->bot->getUser()->getId(),
            "created_at"=> Carbon::now(),
            "updated_at"=> Carbon::now(),
        ]);
        return redirect()->route('sktm.index')->with('success','Sktm Berhasil Dibuat');
    }
    public function edit($id_sktm){
        $sktm = Sktm::find($id_sktm);
        return view('backend.sktm.edit', compact('sktm'));
    }
    public function update(Request $request, $id_sktm){
        $sktm = Sktm::find($id_sktm);
        $sktm->nama = $request->nama;
        $sktm->nik = $request->nik;
        $sktm->tmpt_tgl_lahir = $request->tgl_lahir;
        $sktm->jk = $request->jk;
        $sktm->nama_kepala = $request->nama_kk;
        $sktm->nik_kepala = $request->nik_kk;
        $sktm->alamat_skr = $request->alamat;
        $sktm->keperluan = $request->keperluan;
        $sktm->save();

        return redirect()->route('sktm.index')->with('success','SKTM Berhasil Diubah');
        }
    public function destroy($id_sktm)
    {
        $sktm= Sktm::find($id_sktm);
        $sktm->delete();
        return redirect()->route('sktm.index')->with(['success'=>'SKTM Berhasil Dihapus']);
    }
    public function view($id_sktm, $nik)
    {
        $data_sktm = DB::table('sktm')->where('id_sktm',$id_sktm)->where('nik',$nik)->get();
        return view('surat',compact('data_sktm'));
    }

}
