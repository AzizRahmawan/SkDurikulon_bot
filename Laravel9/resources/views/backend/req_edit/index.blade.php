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
    <li class="nav-item "><a class="nav-link " href="{{ route('penduduk.index') }}">
@endsection

@php
  $countReqEditPenduduk = DB::table('req_edit_penduduk')->where('status_acc', 'belum_disetujui')->count();
@endphp
@section('actreq_edit')
<li class="nav-item active">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages"
        aria-expanded="true" aria-controls="collapsePages">
        <i class="fas fa-fw fa-folder"></i>
        <span>Permintaan Edit</span>
    </a>
    <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" href="{{ route('req_edit_penduduk.index') }}">
                Edit Nama
                @if($countReqEditPenduduk > 0)
                    <span class="badge badge-danger">+{{ $countReqEditPenduduk }}</span>
                @endif
            </a>
        </div>
    </div>
</li>
@endsection

@section('content')

@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
  <strong>{{session('success')}}</strong>
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
@elseif (session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
  <strong>{{session('error')}}</strong>
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
@endif

<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Tenant Table -->
  <div class="card shadow mb-4">
      <div class="card-header py-3">
          <div class="row">
            <div class="col-sm-12 col-md-6 "><h6 class="m-0 font-weight-bold text-primary">Permintaan Edit Data Penduduk</h6></div>
            <div class="col-sm-12 col-md-6"><a href="{{route('req_edit_penduduk.create')}}" class="btn btn-primary float-right">Tambah</a></div>
          </div>
      </div>
      <div class="card-body">
          <div class="table-responsive">
              <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                      <tr>
                          <th>No</th>
                          <th>Nik</th>
                          <th>Tanggal</th>
                          <th>Cek</th>
                      </tr>
                  </thead>
                  <tfoot>
                      <tr>
                          <th>No</th>
                          <th>Nik</th>
                          <th>Tanggal</th>
                          <th>Cek</th>
                      </tr>
                  </tfoot>
                  <tbody>
                    <?php $no = 1?>
                    @foreach($req_edit_penduduk as $item)
                    <tr>
                      <td>{{$no++}}</td>
                      <td>{{$item->nik_lama}}</td>
                      <td>{{Carbon\Carbon::parse($item->created_at)->isoFormat('dddd D MMMM Y')}}</td>
                      <td>
                        <a href="{{route('req_edit_penduduk.edit',$item->nik_lama)}}" class="btn btn-primary"> Cek </a>
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
              </table>
          </div>
      </div>
  </div>

</div>
<!-- /.container-fluid -->

@endsection
