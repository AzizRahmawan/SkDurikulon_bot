<?php

namespace App\Http\Controllers\Backend\Req_Edit;

use App\Http\Controllers\Controller;
use App\Models\Penduduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EditStatusController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(){
        $req_edit_status= DB::table('edit_status_penduduk')->where('status_acc','belum_disetujui')->get();
        return view('backend.req_edit.status.index',compact('req_edit_status'));
    }
    public function edit($id){
        //$penduduk = DB::table('penduduk')->where('nik',$req_edit_penduduk->nik_lama)->first();
        $req_edit_status = DB::table('edit_status_penduduk')->where('id',$id)->first();
        $penduduk = Penduduk::find($req_edit_status->nik_lama);
        return view('backend.req_edit.status.edit', compact(['penduduk','req_edit_status']));
    }
    public function update(Request $request, $id){
        try{
            if ($request->has('disetujui')) {
                $request->validate([
                    'nik' => 'required',
                    'status_lama' => 'required|max:100',
                ]);
                // Tombol "Disetujui" diklik
                $penduduk = Penduduk::where('nik', $request->nik)->first();
                $penduduk->status = $request->status_lama;
                //$penduduk->nik = $request->nik_lama;
                $penduduk->save();
                DB::table('edit_status_penduduk')->where('id', $id)->update([
                    'status_acc' =>  $request->disetujui,
                ]);

            } elseif ($request->has('ditolak')) {
                // Tombol "Ditolak" diklik
                DB::table('edit_status_penduduk')->where('id', $id)->update([
                    'status_acc' =>  $request->ditolak,
                ]);
            }
            return redirect()->route('req_edit_status.index')->with('success','Data Penduduk Berhasil Diubah');
        } catch (\Exception) {
            return redirect()->route('req_edit_status.index')->with('error','Data Gagal Diperbarui, Data Tidak Lengkap Atau Tidak Valid');
        }
    }
}
