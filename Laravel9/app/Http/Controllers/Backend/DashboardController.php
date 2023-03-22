<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Penduduk;
use App\Models\Skd;
use App\Models\Skp;
use App\Models\Sktm;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(){
        $sktm = Sktm::count();
        $skp = Skp::count();
        $skd = Skd::count();
        $penduduk = Penduduk::count();

        return view('backend.index',compact('sktm','skp','skd','penduduk'));
    }
}
