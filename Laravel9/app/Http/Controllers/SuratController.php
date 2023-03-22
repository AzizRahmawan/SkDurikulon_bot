<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SuratController extends Controller
{
    //
    public function index(){
        $surat = DB::table('sktm')->get();

    	// mengirim data pegawai ke view index
    	return view('index',['surat' => $surat]);
    }
    public function view($id_sktm,$id_user, $nik)
    {

        // mengambil data pegawai berdasarkan id yang dipilih
        $data_sktm = DB::table('sktm')->where('id_sktm',$id_sktm)->where('id_user2',$id_user)->where('nik',$nik)->get();
        // passing data pegawai yang didapat ke view edit.blade.php
        return view('surat',compact('data_sktm'));

    }
}
