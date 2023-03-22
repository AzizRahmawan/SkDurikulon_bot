<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SkpController extends Controller
{
    //
    public function view($id_skp,$id_user,$nik)
    {
        // mengambil data pegawai berdasarkan id yang dipilih
        $data_skp = DB::table('skp')->where('id_skp',$id_skp)->where('id_user2',$id_user)->where('nik',$nik)->get();
        // passing data pegawai yang didapat ke view edit.blade.php
        return view('skp',compact('data_skp'));

    }
}
