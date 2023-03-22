@extends('backend.layouts.template')

@section('actdash')
    <li class="nav-item"><a class="nav-link" href="{{route('dashboard')}}">
@endsection

@section('actsktm')
    <li class="nav-item"><a class="nav-link" href="{{ route('sktm.index') }}">
@endsection

@section('actskp')
    <li class="nav-item active"><a class="nav-link " href="{{ route('skp.index') }}">
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

@section('content')

<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Buat SK Penduduk</h1>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Buat SK Penduduk</h6>
        </div>
        <div class="card-body">
            <form action="/dashboard/skp/create/nik" method="GET">
                <div class="form-group">
                    <label for="exampleDataList" class="form-label">Cari Data Penduduk (Optional)</label>
                    <div class="row">
                        <div class="col-md mb-2">
                            <input type="text" name="nik" class="form-control" placeholder="Masukkan NIK">
                        </div>
                        <div class="col-md">
                            <button type="submit" class="btn btn-primary">Cari</button>
                        </div>
                    </div>
                </div>
            </form>
            <form method="POST" action="{{ route('skp.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="nama">NAMA</label>
                    <input type="Text" class="form-control" name="nama" required>
                </div>
                <div class="form-group">
                    <label for="nik">NIK</label>
                    <input type="Text" class="form-control" name="nik" required>
                </div>
                <div class="form-group">
                    <label for="no_kk">No KK</label>
                    <input type="text" class="form-control" name="no_kk" required>
                </div>
                <div class="form-group">
                    <label for="tgl_lahir">Tempat dan Tanggal Lahir</label>
                    <input type="text" class="form-control" name="tgl_lahir" required>
                </div>
                <div class="form-group">
                    <label for="jk">Jenis Kelamin</label>
                    <input type="text" class="form-control" name="jk" required>
                </div>
                <div class="form-group">
                    <label for="pekerjaan">Pekerjaan</label>
                    <input type="text" class="form-control" name="pekerjaan" required>
                </div>
                <div class="form-group">
                    <label for="status">Status Pernikahan</label>
                    <input type="text" class="form-control" name="status" required>
                </div>
                <div class="form-group">
                    <label for="agama">Agama</label>
                    <input type="text" class="form-control" name="agama" required>
                </div>
                <div class="form-group">
                    <label for="pendidikan">Pendidikan Terakhir</label>
                    <input type="text" class="form-control" name="pendidikan" required>
                </div>
                <div class="form-group">
                    <label for="alamat">Alamat</label>
                    <textarea name="alamat" rows="5" class="form-control" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
</div>
<script>

</script>

@endsection
