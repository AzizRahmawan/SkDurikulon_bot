<?php

namespace App\Http\Controllers\Backend\Req_Edit;

use App\Http\Controllers\Controller;
use App\Models\Penduduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EditAgamaController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(){
        $req_edit_agama= DB::table('edit_agama_penduduk')->where('status_acc','belum_disetujui')->get();
        return view('backend.req_edit.agama.index',compact('req_edit_agama'));
    }
    public function edit($id){
        //$penduduk = DB::table('penduduk')->where('nik',$req_edit_penduduk->nik_lama)->first();
        $req_edit_agama = DB::table('edit_agama_penduduk')->where('id',$id)->first();
        $penduduk = Penduduk::find($req_edit_agama->nik_lama);
        return view('backend.req_edit.agama.edit', compact(['penduduk','req_edit_agama']));
    }
    public function update(Request $request, $id){
        try{
            if ($request->has('disetujui')) {
                $request->validate([
                    'nik' => 'required',
                    'agama_lama' => 'required|max:100',
                ]);
                // Tombol "Disetujui" diklik
                $penduduk = Penduduk::where('nik', $request->nik)->first();
                $penduduk->agama = $request->agama_lama;
                //$penduduk->nik = $request->nik_lama;
                $penduduk->save();
                DB::table('edit_agama_penduduk')->where('id', $id)->update([
                    'status_acc' =>  $request->disetujui,
                ]);

            } elseif ($request->has('ditolak')) {
                // Tombol "Ditolak" diklik
                DB::table('edit_agama_penduduk')->where('id', $id)->update([
                    'status_acc' =>  $request->ditolak,
                ]);
            }
            return redirect()->route('req_edit_agama.index')->with('success','Data Penduduk Berhasil Diubah');
        } catch (\Exception) {
            return redirect()->route('req_edit_agama.index')->with('error','Data Gagal Diperbarui, Data Tidak Lengkap Atau Tidak Valid');
        }
    }
}

