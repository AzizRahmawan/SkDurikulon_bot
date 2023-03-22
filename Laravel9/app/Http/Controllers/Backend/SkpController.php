<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Penduduk;
use App\Models\Skp;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SkpController extends Controller
{
    //
    public function __construct()
    {
        //$this->middleware(['auth','super_admin']);
        $this->middleware('auth');
    }
    public function index(){
        $skp = Skp::all();
        return view('backend.skp.index',compact('skp'));
    }
    public function create(){
        $penduduk = Penduduk::all();
        return view('backend.skp.cariNik', compact('penduduk'));
    }

    public function cariNik(Request $request){
        $cari = $request->nik;

        $penduduk = DB::table('penduduk')->where('nik',$cari)->get();

        return view('backend.skp.create', compact('penduduk'));
    }
    public function store(Request $request){
        DB::table('skp')->insert([
            'nama' => $request->nama,
            'nik' => $request->nik,
            'tmpt_tgl_lahir' => $request->tgl_lahir,
            'jk' => $request->jk,
            'pekerjaan' => $request->pekerjaan,
            'status' => $request->status,
            'agama' => $request->agama,
            'pendidikan' => $request->pendidikan,
            'no_kk' => $request->no_kk,
            'alamat_skr' => $request->alamat,
            //$request->kolom => $request->alamat,
            'id_user' => '1',
            //'id_user' => $this->bot->getUser()->getId(),
            "created_at"=> Carbon::now(),
            "updated_at"=> Carbon::now(),
        ]);
        return redirect()->route('skp.index')->with('success','Data Berhasil Dibuat');
    }
    public function edit($id_skp){
        $skp = Skp::find($id_skp);
        return view('backend.skp.edit', compact('skp'));
    }
    public function update(Request $request, $id_skp){
        $skp = Skp::find($id_skp);
        $skp->nama = $request->nama;
        $skp->nik = $request->nik;
        $skp->no_kk = $request->no_kk;
        $skp->tmpt_tgl_lahir = $request->tgl_lahir;
        $skp->jk = $request->jk;
        $skp->pekerjaan = $request->pekerjaan;
        $skp->status = $request->status;
        $skp->agama = $request->agama;
        $skp->pendidikan = $request->pendidikan;
        $skp->alamat_skr = $request->alamat;
        //$request->kolom => $request->alamat,
        $skp->save();
        return redirect()->route('skp.index')->with('success','SK Penduduk Berhasil Diubah');
    }
    public function destroy($id_skp)
    {
        $skp= Skp::find($id_skp);
        $skp->delete();
        return redirect()->route('skp.index')->with(['success'=>'SK Penduduk Berhasil Dihapus']);
    }
    public function view($id_skp,$nik)
    {
        $data_skp = DB::table('skp')->where('id_skp',$id_skp)->where('nik',$nik)->get();
        return view('skp',compact('data_skp'));
    }

}
