@extends('backend.layouts.template')

@section('actdash')
    <li class="nav-item"><a class="nav-link" href="{{route('dashboard')}}">
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
    <li class="nav-item active"><a class="nav-link " href="{{ route('penduduk.index') }}">
@endsection

@section('content')

@if (session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
  <strong>{{session('error')}}</strong>
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
@endif

<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Permintaan Edit Data Penduduk</h1>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Permintaan Edit Data Penduduk</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('req_edit_penduduk.update',$req_edit_penduduk->nik_lama) }}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="row">
                    <div class="col-md">
                        <h5><strong> DATA LAMA</strong></h5>
                        <div class="form-group">
                            <label for="nama">NAMA</label>
                            <input type="Text" class="form-control" name="nama_lama" required value="{{ isset($penduduk) ? $penduduk->nama : '' }}" readonly>
                        </div>

                        <div class="form-group">
                            <label for="nik">NIK</label>
                            <input type="Text" class="form-control" name="nik_lama" required value="{{ isset($penduduk) ? $penduduk->nik : '' }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="no_kk">No KK</label>
                            <input type="text" class="form-control" name="no_kk_lama" required value="{{ isset($penduduk) ? $penduduk->no_kk : '' }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="nama_kk">Nama Kepala Keluarga</label>
                            <input type="text" class="form-control" name="nama_kk" required value="{{ isset($penduduk) ? $penduduk->nama_kepala : '' }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="nik_kk">NIK Kepala Keluarga</label>
                            <input type="text" class="form-control" name="nik_kk" required value="{{ isset($penduduk) ? $penduduk->nik_kepala : '' }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="tgl_lahir">Tempat dan Tanggal Lahir</label>
                            <input type="text" class="form-control" name="tgl_lahir_lama" required value="{{ isset($penduduk) ? $penduduk->tmpt_tgl_lahir : '' }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="jk">Jenis Kelamin</label>
                            <input type="text" class="form-control" name="jk_lama" required value="{{ isset($penduduk) ? $penduduk->jk : '' }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="pekerjaan">Pekerjaan</label>
                            <input type="text" class="form-control" name="pekerjaan_lama" required value="{{ isset($penduduk) ? $penduduk->pekerjaan: '' }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="status">Status Pernikahan</label>
                            <input type="text" class="form-control" name="status_lama" required value="{{ isset($penduduk) ? $penduduk->status: '' }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="agama">Agama</label>
                            <input type="text" class="form-control" name="agama_lama" required value="{{ isset($penduduk) ? $penduduk->agama: '' }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="pendidikan">Pendidikan</label>
                            <input type="text" class="form-control" name="pendidikan_lama" required value="{{ isset($penduduk) ? $penduduk->pendidikan: '' }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="alamat">Alamat Pada KTP</label>
                            <textarea name="alamat_ktp_lama" rows="5" class="form-control" required readonly>{{ isset($penduduk) ? $penduduk->alamat_ktp : '' }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="alamat">Alamat/Tempat Tinggal Sekarang</label>
                            <textarea name="alamat_skr_lama" rows="5" class="form-control" required readonly>{{ isset($penduduk) ? $penduduk->alamat_skr : '' }}</textarea>
                        </div>
                    </div>
                    <div class="col-md">
                        <h5><strong>DATA BARU</strong></h5>
                        <div class="form-group">
                            <label for="nama">NAMA</label>
                            <input type="Text" class="form-control" name="nama" required value="{{ isset($req_edit_penduduk) ? $req_edit_penduduk->nama : '' }}" readonly>
                        </div>

                        <div class="form-group">
                            <label for="nik">NIK</label>
                            <input type="Text" class="form-control" name="nik" required value="{{ isset($req_edit_penduduk) ? $req_edit_penduduk->nik_baru : '' }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="no_kk">No KK</label>
                            <input type="text" class="form-control" name="no_kk" required value="{{ isset($req_edit_penduduk) ? $req_edit_penduduk->no_kk : '' }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="nama_kk">Nama Kepala Keluarga</label>
                            <input type="text" class="form-control" name="nama_kk" required value="{{ isset($req_edit_penduduk) ? $req_edit_penduduk->nama_kepala : '' }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="nik_kk">NIK Kepala Keluarga</label>
                            <input type="text" class="form-control" name="nik_kk" required value="{{ isset($req_edit_penduduk) ? $req_edit_penduduk->nik_kepala : '' }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="tgl_lahir">Tempat dan Tanggal Lahir</label>
                            <input type="text" class="form-control" name="tgl_lahir" required value="{{ isset($req_edit_penduduk) ? $req_edit_penduduk->tmpt_tgl_lahir : '' }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="jk">Jenis Kelamin</label>
                            <input type="text" class="form-control" name="jk" required value="{{ isset($req_edit_penduduk) ? $req_edit_penduduk->jk : '' }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="pekerjaan">Pekerjaan</label>
                            <input type="text" class="form-control" name="pekerjaan" required value="{{ isset($req_edit_penduduk) ? $req_edit_penduduk->pekerjaan: '' }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="status">Status Pernikahan</label>
                            <input type="text" class="form-control" name="status" required value="{{ isset($req_edit_penduduk) ? $req_edit_penduduk->status: '' }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="agama">Agama</label>
                            <input type="text" class="form-control" name="agama" required value="{{ isset($req_edit_penduduk) ? $req_edit_penduduk->agama: '' }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="pendidikan">Pendidikan</label>
                            <input type="text" class="form-control" name="pendidikan" required value="{{ isset($req_edit_penduduk) ? $req_edit_penduduk->pendidikan: '' }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="alamat">Alamat Pada KTP</label>
                            <textarea name="alamat_ktp" rows="5" class="form-control" required readonly>{{ isset($req_edit_penduduk) ? $req_edit_penduduk->alamat_ktp : '' }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="alamat">Alamat/Tempat Tinggal Sekarang</label>
                            <textarea name="alamat_skr" rows="5" class="form-control" required readonly>{{ isset($req_edit_penduduk) ? $req_edit_penduduk->alamat_skr : '' }}</textarea>
                        </div>
                        <input type="hidden" name="status_acc" value="disetujui">
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary" name="disetujui" value="disetujui">Disetujui</button>
                    <button type="submit" class="btn btn-danger" name="ditolak" value="ditolak">Ditolak</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
