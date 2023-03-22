<!DOCTYPE html>
<html>
<head>
	<title>Tutorial Membuat CRUD Pada Laravel - www.malasngoding.com</title>
</head>
<body>

	<h2>www.malasngoding.com</h2>
	<h3>Data Pegawai</h3>

	<a href="/pegawai/tambah"> + Tambah Pegawai Baru</a>

	<br/>
	<br/>

	<table border="1">
		<tr>
			<th>Nama</th>
			<th>Jabatan</th>
			<th>Umur</th>
			<th>Opsi</th>
		</tr>
		@foreach($surat as $s)
		<tr>
			<td>{{ $s->nama }}</td>
			<td>{{ $s->nik}}</td>
			<td>{{ $s->jenis_surat}}</td>
			<td>
				<a href="/surat/edit/{{ $s->nik }}">Edit</a>
				|
				<a href="/pegawai/hapus/{{ $s->nik}}">Hapus</a>
			</td>
		</tr>
		@endforeach
	</table>


</body>
</html>


