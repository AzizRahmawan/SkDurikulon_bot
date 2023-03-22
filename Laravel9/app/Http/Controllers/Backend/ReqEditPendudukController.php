<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Penduduk;
use App\Models\ReqEditPenduduk;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReqEditPendudukController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(){
        $req_edit_penduduk = DB::table('req_edit_penduduk')->where('status_acc','belum_disetujui')->get();
        return view('backend.req_edit_penduduk.index',compact('req_edit_penduduk'));
    }
    public function create(){
        return view('backend.req_edit_penduduk.create');
    }
    public function store(Request $request){
        try {
            $request->validate([
                'nik' => 'required|unique:penduduk,nik',
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
                'pendidikan' => $request->pendidikan,
                'alamat_skr' => $request->alamat_skr,
                'alamat_ktp' => $request->alamat_ktp,
                //$request->kolom => $request->alamat,
                //'id_user' => '1',
                //'id_user' => $this->bot->getUser()->getId(),
                "created_at"=> Carbon::now(),
                "updated_at"=> Carbon::now(),
            ]);
            return redirect()->route('penduduk.index')->with('success','Data Penduduk Berhasil Dibuat');
        } catch (\Exception) {
            return redirect()->route('penduduk.create')->with('error','Data Gagal Disimpan');
        }

    }
    public function edit($nik){
        $penduduk = Penduduk::find($nik);
        //$penduduk = DB::table('penduduk')->where('nik',$req_edit_penduduk->nik_lama)->first();
        $req_edit_penduduk = DB::table('req_edit_penduduk')->where('nik_lama',$nik)->where('status_acc','belum_disetujui')->first();
        return view('backend.req_edit_penduduk.edit', compact(['penduduk','req_edit_penduduk']));
    }
    public function update(Request $request, $nik){
        try{
            if ($request->has('disetujui')) {
                $request->validate([
                    'nik' => 'required',
                    'nama' => 'required',
                    'tgl_lahir' => 'required',
                    'no_kk' => 'required',
                    'jk' => 'required',
                    'pekerjaan' => 'required',
                    'status' => 'required',
                    'agama' => 'required',
                    'pendidikan' => 'required',
                    'nik_kk' => 'required',
                    'nama_kk' => 'required',
                    'alamat_skr' => 'required',
                    'alamat_ktp' => 'required',
                ]);
                // Tombol "Disetujui" diklik
                $penduduk = Penduduk::where('nik', $nik)->first();
                $penduduk->nama = $request->nama;
                $penduduk->nik = $request->nik;
                $penduduk->no_kk = $request->no_kk;
                $penduduk->tmpt_tgl_lahir = $request->tgl_lahir;
                $penduduk->jk = $request->jk;
                $penduduk->pekerjaan = $request->pekerjaan;
                $penduduk->status = $request->status;
                $penduduk->agama = $request->agama;
                $penduduk->nik_kepala = $request->nik_kk;
                $penduduk->nama_kepala = $request->nama_kk;
                $penduduk->pendidikan = $request->pendidikan;
                $penduduk->alamat_skr = $request->alamat_skr;
                $penduduk->alamat_ktp = $request->alamat_ktp;
                $penduduk->save();
                $req_edit_penduduk = ReqEditPenduduk::where('nik_lama', $nik)->where('status_acc','belum_disetujui')->first();
                $req_edit_penduduk->status_acc = 'disetujui';
                $req_edit_penduduk->save();

            } elseif ($request->has('ditolak')) {
                // Tombol "Ditolak" diklik
                $penduduk = ReqEditPenduduk::where('nik_lama', $nik)->where('status_acc','belum_disetujui')->first();
                $penduduk->status_acc = 'ditolak';
                $penduduk->save();
            }
            return redirect()->route('req_edit_penduduk.index')->with('success','Data Penduduk Berhasil Diubah');
        } catch (\Exception) {
            return redirect()->route('req_edit_penduduk.index')->with('error','Data Gagal Diperbarui, Data Tidak Lengkap Atau Tidak Valid');
        }
    }
    public function destroy($nik)
    {
        $penduduk= penduduk::find($nik);
        $penduduk->delete();
        return redirect()->route('penduduk.index')->with(['success'=>'Data Penduduk Berhasil Dihapus']);
    }

}
