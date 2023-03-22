<?php

namespace App\Http\Controllers;

use App\Models\Penduduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DropdownController extends Controller
{
    //
    public function index()
	{
    		// mengambil data dari table pegawai
		$pegawai = DB::table('penduduk')->paginate(10);

    		// mengirim data pegawai ke view index
		return view('dropdownindex',['pegawai' => $pegawai]);

	}

	public function cari(Request $request)
	{
		// menangkap data pencarian
		$cari = $request->cari;

    		// mengambil data dari table pegawai sesuai pencarian data
		$pegawai = DB::table('penduduk')
		->where('nik','like',"%".$cari."%")
		->paginate();

    		// mengirim data pegawai ke view index
		return view('dropdownindex',['pegawai' => $pegawai]);

	}
}
