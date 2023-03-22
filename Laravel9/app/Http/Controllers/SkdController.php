<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SkdController extends Controller
{
    //
    public function view($id_skd,$id_user,$nik)
    {
        // mengambil data pegawai berdasarkan id yang dipilih
        $data_skd = DB::table('skd')->where('id_skd',$id_skd)->where('id_user2',$id_user)->where('nik',$nik)->get();
        // passing data pegawai yang didapat ke view edit.blade.php
        return view('skd',compact('data_skd'));

    }
}
