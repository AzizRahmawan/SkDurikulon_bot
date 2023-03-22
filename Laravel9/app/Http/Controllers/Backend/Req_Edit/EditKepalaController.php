<?php

namespace App\Http\Controllers\Backend\Req_Edit;

use App\Http\Controllers\Controller;
use App\Models\Penduduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EditKepalaController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(){
        $req_edit_kepala= DB::table('edit_kepala_penduduk')->where('status_acc','belum_disetujui')->get();
        return view('backend.req_edit.kepala.index',compact('req_edit_kepala'));
    }
    public function edit($id){
        //$penduduk = DB::table('penduduk')->where('nik',$req_edit_penduduk->nik_lama)->first();
        $req_edit_kepala = DB::table('edit_kepala_penduduk')->where('id',$id)->first();
        $penduduk = Penduduk::find($req_edit_kepala->nik_lama);
        return view('backend.req_edit.kepala.edit', compact(['penduduk','req_edit_kepala']));
    }
    public function update(Request $request, $id){
        try{
            if ($request->has('disetujui')) {
                $request->validate([
                    'nik' => 'required',
                    'nama_kepala_lama' => 'required|max:100',
                    'nik_kepala_lama' => 'required|max:100',
                ]);
                // Tombol "Disetujui" diklik
                $penduduk = Penduduk::where('nik', $request->nik)->first();
                $penduduk->nama_kepala = $request->nama_kepala_lama;
                $penduduk->nik_kepala = $request->nik_kepala_lama;
                //$penduduk->nik = $request->nik_lama;
                $penduduk->save();
                DB::table('edit_kepala_penduduk')->where('id', $id)->update([
                    'status_acc' =>  $request->disetujui,
                ]);

            } elseif ($request->has('ditolak')) {
                // Tombol "Ditolak" diklik
                DB::table('edit_kepala_penduduk')->where('id', $id)->update([
                    'status_acc' =>  $request->ditolak,
                ]);
            }
            return redirect()->route('req_edit_kepala.index')->with('success','Data Penduduk Berhasil Diubah');
        } catch (\Exception) {
            return redirect()->route('req_edit_kepala.index')->with('error','Data Gagal Diperbarui, Data Tidak Lengkap Atau Tidak Valid');
        }
    }
}
