<!DOCTYPE html>
<html>
<head>
	<title>Tutorial Membuat Pencarian Pada Laravel - www.malasngoding.com</title>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
</head>
<body>

	<style type="text/css">
		.pagination li{
			float: left;
			list-style-type: none;
			margin:5px;
		}
	</style>

	<h2><a href="https://www.malasngoding.com">www.malasngoding.com</a></h2>
	<h3>Data Pegawai</h3>


	<p>Cari Data Pegawai :</p>
	<form action="/pegawai/cari" method="GET">
		<input type="text" name="cari" placeholder="Cari Pegawai .." value="{{ old('cari') }}">
		<input type="submit" value="CARI">
	</form>

	<br/>

	<table border="1">
		<tr>
			<th>Nama</th>
			<th>Jabatan</th>
			<th>Umur</th>
			<th>Alamat</th>
		</tr>
		@foreach($pegawai as $p)
		<tr>
			<td>{{ $p->nama }}</td>
			<td>{{ $p->nik }}</td>
			<td>{{ $p->tmpt_tgl_lahir }}</td>
			<td>{{ $p->alamat_skr }}</td>
		</tr>
        <form method="POST" action="{{ route('sktm.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="nama">Nama</label>
                <input type="Text" class="form-control" name="nama" value="{{ $p->nama }}" required>
            </div>
            <div class="form-group">
                <label for="nik">NIK</label>
                <input type="Text" class="form-control" name="nik" value="{{ $p->nik }}" required>
            </div>
            <div class="form-group">
                <label for="tgl_lahir">Tanggal Lahir</label>
                <input type="text" class="form-control" name="tgl_lahir" value="{{ $p->tmpt_tgl_lahir }}" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
		@endforeach
	</table>


	<br/>
	Halaman : {{ $pegawai->currentPage() }} <br/>
	Jumlah Data : {{ $pegawai->total() }} <br/>
	Data Per Halaman : {{ $pegawai->perPage() }} <br/>


	{{ $pegawai->links() }}

    <div class="container">
        <div class="row">
            <form class="col-md-4">
                <label>Select</label>
                <select class="form-control select2">
                   <option>Select</option>
                   <option>Car</option>
                   <option>Bike</option>
                   <option>Scooter</option>
                   <option>Cycle</option>
                   <option>Horse</option>
                </select>
            </form>
         </div>
    </div>
    <script>
        $('.select2').select2();
    </script>

</body>
</html>
