@extends('backend.layouts.template')

@section('actdash')
    <li class="nav-item active"><a class="nav-link" href="{{route('dashboard')}}">
@endsection

@section('actsktm')
    <li class="nav-item"><a class="nav-link" href="{{ route('sktm.index') }}">
@endsection

@section('actskp')
    <li class="nav-item"><a class="nav-link " href="{{ route('skp.index') }}">
@endsection

@section('actskd')
    <li class="nav-item"><a class="nav-link " href="{{ route('skd.index') }}">
@endsection

@section('actpenduduk')
    <li class="nav-item"><a class="nav-link " href="{{ route('penduduk.index') }}">
@endsection

@php
  $countReqEdit_Nama = DB::table('edit_nama_penduduk')->where('status_acc', 'belum_disetujui')->count();
  $countReqEditNik = DB::table('edit_nik_penduduk')->where('status_acc', 'belum_disetujui')->count();
  $countReqEditKepala = DB::table('edit_kepala_penduduk')->where('status_acc', 'belum_disetujui')->count();
  $countReqEditNoKk = DB::table('edit_no_kk_penduduk')->where('status_acc', 'belum_disetujui')->count();
  $countReqEditTglLahir = DB::table('edit_tgl_lahir_penduduk')->where('status_acc', 'belum_disetujui')->count();
  $countReqEditPekerjaan = DB::table('edit_pekerjaan_penduduk')->where('status_acc', 'belum_disetujui')->count();
  $countReqEditStatus = DB::table('edit_status_penduduk')->where('status_acc', 'belum_disetujui')->count();
  $countReqEditPendidikan = DB::table('edit_pendidikan_penduduk')->where('status_acc', 'belum_disetujui')->count();
  $countReqEditAgama = DB::table('edit_agama_penduduk')->where('status_acc', 'belum_disetujui')->count();
  $countReqEditAlamat = DB::table('edit_alamat_penduduk')->where('status_acc', 'belum_disetujui')->count();
@endphp
@section('actreq_edit')
<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages"
        aria-expanded="true" aria-controls="collapsePages">
        <i class="fas fa-fw fa-folder"></i>
        <span>Permintaan Edit</span>
    </a>
    <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" href="{{ route('req_edit_nama.index') }}">
                Edit Nama
                @if($countReqEdit_Nama > 0)
                    <span class="badge badge-danger">+{{ $countReqEdit_Nama }}</span>
                @endif
            </a>
            <a class="collapse-item" href="{{ route('req_edit_nik.index') }}">
                Edit NIK
                @if($countReqEditNik > 0)
                    <span class="badge badge-danger">+{{ $countReqEditNik }}</span>
                @endif
            </a>
            <a class="collapse-item" href="{{ route('req_edit_kepala.index') }}">
                Edit Kepala
                @if($countReqEditKepala > 0)
                    <span class="badge badge-danger">+{{ $countReqEditKepala }}</span>
                @endif
            </a>
            <a class="collapse-item" href="{{ route('req_edit_no_kk.index') }}">
                Edit No KK
                @if($countReqEditNoKk > 0)
                    <span class="badge badge-danger">+{{ $countReqEditNoKk }}</span>
                @endif
            </a>
            <a class="collapse-item" href="{{ route('req_edit_tgl_lahir.index') }}">
                Edit Tgl Lahir
                @if($countReqEditTglLahir > 0)
                    <span class="badge badge-danger">+{{ $countReqEditTglLahir }}</span>
                @endif
            </a>
            <a class="collapse-item" href="{{ route('req_edit_pekerjaan.index') }}">
                Edit Pekerjaan
                @if($countReqEditPekerjaan > 0)
                    <span class="badge badge-danger">+{{ $countReqEditPekerjaan }}</span>
                @endif
            </a>
            <a class="collapse-item" href="{{ route('req_edit_status.index') }}">
                Edit Status
                @if($countReqEditStatus > 0)
                    <span class="badge badge-danger">+{{ $countReqEditStatus }}</span>
                @endif
            </a>
            <a class="collapse-item" href="{{ route('req_edit_agama.index') }}">
                Edit Agama
                @if($countReqEditAgama > 0)
                    <span class="badge badge-danger">+{{ $countReqEditAgama }}</span>
                @endif
            </a>
            <a class="collapse-item" href="{{ route('req_edit_pendidikan.index') }}">
                Edit Pendidikan
                @if($countReqEditPendidikan > 0)
                    <span class="badge badge-danger">+{{ $countReqEditPendidikan }}</span>
                @endif
            </a>
            <a class="collapse-item" href="{{ route('req_edit_alamat.index') }}">
                Edit Alamat
                @if($countReqEditAlamat > 0)
                    <span class="badge badge-danger">+{{ $countReqEditAlamat }}</span>
                @endif
            </a>
        </div>
    </div>
</li>
@endsection

@section('dashboard')
@if (session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
  <strong>{{session('error')}}</strong>
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
@endif
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    </div>

    <!-- Content Row -->
    <div class="row">

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                SKTM</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{$sktm}}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                SK Penduduk</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{$skp}}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">SK Domisili
                            </div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{$skd}}</div>
                                </div>
                                <div class="col">
                                    <!--<div class="progress progress-sm mr-2">
                                        <div class="progress-bar bg-info" role="progressbar"
                                            style="width: 50%" aria-valuenow="50" aria-valuemin="0"
                                            aria-valuemax="100"></div>
                                    </div>-->
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Requests Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Penduduk</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{$penduduk}}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

@endsection
