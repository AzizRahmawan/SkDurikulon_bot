<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Penduduk;
use App\Models\Skd;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SkdController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(){
        $skd = Skd::all();
        return view('backend.skd.index',compact('skd'));
    }
    public function create(){
        $penduduk = Penduduk::all();
        return view('backend.skd.cariNik', compact('penduduk'));
    }

    public function cariNik(Request $request){
        $cari = $request->nik;

        $penduduk = DB::table('penduduk')->where('nik',$cari)->get();

        return view('backend.skd.create', compact('penduduk'));
    }
    public function store(Request $request){
        DB::table('skd')->insert([
            'nama' => $request->nama,
            'nik' => $request->nik,
            'tmpt_tgl_lahir' => $request->tgl_lahir,
            'jk' => $request->jk,
            'pekerjaan' => $request->pekerjaan,
            'status' => $request->status,
            'agama' => $request->agama,
            'no_kk' => $request->no_kk,
            'alamat_skr' => $request->alamat_skr,
            'alamat_ktp' => $request->alamat_ktp,
            //$request->kolom => $request->alamat,
            'id_user' => '1',
            //'id_user' => $this->bot->getUser()->getId(),
            "created_at"=> Carbon::now(),
            "updated_at"=> Carbon::now(),
        ]);
        return redirect()->route('skd.index')->with('success','SK Domisili Berhasil Dibuat');
    }
    public function edit($id_skd){
        $skd = Skd::find($id_skd);
        return view('backend.skd.edit', compact('skd'));
    }
    public function update(Request $request, $id_skd){
        $skd = Skd::find($id_skd);
        $skd->nama = $request->nama;
        $skd->nik = $request->nik;
        $skd->no_kk = $request->no_kk;
        $skd->tmpt_tgl_lahir = $request->tgl_lahir;
        $skd->jk = $request->jk;
        $skd->pekerjaan = $request->pekerjaan;
        $skd->status = $request->status;
        $skd->agama = $request->agama;
        $skd->alamat_skr = $request->alamat_skr;
        $skd->alamat_ktp = $request->alamat_ktp;
        //$request->kolom => $request->alamat,
        $skd->save();
        return redirect()->route('skd.index')->with('success','SK Domisili Berhasil Diubah');
    }
    public function destroy($id_skd)
    {
        $skd= Skd::find($id_skd);
        $skd->delete();
        return redirect()->route('skd.index')->with(['success'=>'SK Domisili Berhasil Dihapus']);
    }
    public function view($id_skd,$nik)
    {
        $data_skd = DB::table('skd')->where('id_skd',$id_skd)->where('nik',$nik)->get();
        return view('skd',compact('data_skd'));
    }

}
