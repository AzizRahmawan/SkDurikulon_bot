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
    <h1 class="h3 mb-2 text-gray-800">Buat SK Domisili</h1>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Buat SK Domisili</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('penduduk.store') }}" enctype="multipart/form-data">
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
                    <label for="pendidikan">Pendidikan</label>
                    <input type="text" class="form-control" name="pendidikan" required>
                </div>
                <div class="form-group">
                    <label for="alamat">Alamat Pada KTP</label>
                    <textarea name="alamat_ktp" rows="5" class="form-control" required></textarea>
                </div>
                <div class="form-group">
                    <label for="alamat">Alamat/Tempat Tinggal Sekarang</label>
                    <textarea name="alamat_skr" rows="5" class="form-control" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
</div>
<script>

</script>

@endsection
