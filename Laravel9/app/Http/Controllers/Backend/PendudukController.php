<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Penduduk;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Cache\LaravelCache;
use BotMan\BotMan\Drivers\DriverManager;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PendudukController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(){
        $penduduk = Penduduk::all();
        return view('backend.penduduk.index',compact('penduduk'));
    }
    public function create(){
        return view('backend.penduduk.create');
    }
    public function store(Request $request){
        try {
            $request->validate([
                'nik' => 'required|unique:penduduk,nik|size:16',
            ]);
            DB::table('penduduk')->insert([
                'nama' => $request->nama,
                'nik' => $request->nik,
                'tmpt_tgl_lahir' => $request->tgl_lahir,
                'jk' => $request->jk,
                'pekerjaan' => $request->pekerjaan,
                'status' => $request->status,
                'agama' => $request->agama,
                'no_kk' => $request->no_kk,
                'nama_kepala' => $request->nama_kk,
                'nik_kepala' => $request->nik_kk,
                'pendidikan' => $request->pendidikan,
                'alamat_ktp' => $request->alamat_ktp,
                //'alamat_skr' => $request->alamat_skr,
                //$request->kolom => $request->alamat,
                //'id_user' => '1',
                //'id_user' => $this->bot->getUser()->getId(),
                "created_at"=> Carbon::now(),
                "updated_at"=> Carbon::now(),
            ]);
            return redirect()->route('penduduk.index')->with('success','Data Penduduk Berhasil Dibuat');
        } catch (\Exception) {
            return redirect()->route('penduduk.create')->with('error','Data Gagal Dibuat (Data Tidak Valid / Sudah Ada)');
        }

    }
    public function edit($nik){
        $penduduk = penduduk::find($nik);
        return view('backend.penduduk.edit', compact('penduduk'));
    }
    public function update(Request $request, $nik){
        try{
            $request->validate([
                'nik' => 'required|size:16'
            ]);
            DB::table('penduduk')->where('nik',$nik)->update([
                'nama' => $request->nama,
                'nik' => $request->nik,
                'tmpt_tgl_lahir' => $request->tgl_lahir,
                'jk' => $request->jk,
                'pekerjaan' => $request->pekerjaan,
                'status' => $request->status,
                'agama' => $request->agama,
                'no_kk' => $request->no_kk,
                'nama_kepala' => $request->nama_kk,
                'nik_kepala' => $request->nik_kk,
                'pendidikan' => $request->pendidikan,
                'alamat_ktp' => $request->alamat_ktp,
                //'alamat_skr' => $request->alamat_skr,
                //$request->kolom => $request->alamat,
                //'id_user' => '1',
                //'id_user' => $this->bot->getUser()->getId(),
                "created_at"=> Carbon::now(),
                "updated_at"=> Carbon::now(),
            ]);
            return redirect()->route('penduduk.index')->with('success','Data Penduduk Berhasil Diubah');
        } catch (\Exception) {
            return redirect()->route('penduduk.index')->with('error','Data Gagal Diperbarui (Data Tidak Valid / Sudah Ada)');
        }
    }
    public function destroy($nik)
    {
        $penduduk= penduduk::find($nik);
        $penduduk->delete();
        return redirect()->route('penduduk.index')->with(['success'=>'Data Penduduk Berhasil Dihapus']);
    }

}
