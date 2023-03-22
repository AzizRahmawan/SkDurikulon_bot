<html>
<head>
  <script language="JavaScript">
    function Cetakan()
    {
     document.all.Button.style.visibility="hidden";
     window.print();
     alert("Jangan di tekan tombol OK sebelum dokumen selesai tercetak!");
     document.all.Button.style.visibility="visible";
    }
  </script>
<title>SURAT KETERANGAN DOMISILI</title>
</head>
<body>
@foreach($data_skd as $skd)
<table width="80%" border="0" align="center" cellspacing="0" cellpadding="2">
	<tr>
    <td rowspan="5" width="15%" align="center"><img src="{{ asset('image/logo durikulon.jpg') }}" width="100px"/></td>
    <td align="center" style="font-size:18px"><strong>PEMERINTAH KABUPATEN LAMONGAN</strong></td>
  </tr>
	<tr>
	  <td align="center" style="font-size:18px"><strong>KECAMATAN LAREN</strong></td>
  </tr>
	<tr>
	  <td align="center" style="font-size:18px"><strong>DESA DURIKULON</strong></td>
  </tr>
	<tr>
	  <td align="center"><i>Jalan Tangkis Bengawan Solo Rt 01 Rw 03 Desa Durikulon 62262</i></td>
  </tr>
</table>
<table width="80%" border="0" align="center" cellspacing="0" cellpadding="2">
	<tr>
      <td colspan="2"><hr /></td>
  </tr>
    <tr>
      <td colspan="2"><div align="center"><strong><u>SURAT KETERANGAN DOMISILI</u></strong></div></td>
    </tr>
    <tr>
      <td colspan="2"><div align="center">No: 470 / {{$skd->id_skd}} / 413.308.05 / 2023</div></td>
    </tr>
    <tr>
      <td colspan="2"><input type="button" name="Button" value="Cetak" ONCLICK=Cetakan()></td>
    </tr>
</table>
<table width="80%" border="0" align="center" cellspacing="0" cellpadding="2">
    <tr>
      <td colspan="4" style="text-align: justify">Yang tangan di bawah ini Penjabat Kepala Desa Durikulon Kecamatan Laren Kabupaten Lamongan menerangkan: </td>
    </tr>
    <tr style="vertical-align: top;">
      <td width="10%">&nbsp;</td>
      <td width="30%">N a m a</td>
      <td>: </td>
      <td>{{ $skd->nama }} </td>
    </tr>
    <tr style="vertical-align: top;">
      <td>&nbsp;</td>
      <td>Tempat/Tanggal Lahir</td>
      <td>: </td>
      <td>{{ $skd->tmpt_tgl_lahir }}</td>
    </tr>
    <tr style="vertical-align: top;">
      <td>&nbsp;</td>
      <td>NIK</td>
      <td>:</td>
      <td>{{ $skd->nik }}</td>
    </tr>
    <tr style="vertical-align: top;">
      <td>&nbsp;</td>
      <td>Jenis Kelamin</td>
      <td>: </td>
      <td>{{ $skd->jk }}</td>
    </tr>
    <tr style="vertical-align: top;">
        <td>&nbsp;</td>
        <td>Pekerjaan</td>
        <td>: </td>
        <td>{{ $skd->pekerjaan }}</td>
    </tr>
    <tr style="vertical-align: top;">
        <td>&nbsp;</td>
        <td>Status</td>
        <td>: </td>
        <td>{{ $skd->status }}</td>
    </tr>
    <tr style="vertical-align: top;">
        <td>&nbsp;</td>
        <td>Agama</td>
        <td>: </td>
        <td>{{ $skd->agama }}</td>
    </tr>
    <tr style="vertical-align: top;">
      <td>&nbsp;</td>
      <td>Alamat</td>
      <td>: </td>
      <td style="text-align: justify">{{ $skd->alamat_ktp }}</td>
    </tr>
    <tr style="vertical-align: top;">
      <td>&nbsp;</td>
      <td>Keterangan</td>
      <td>:</td>
      <td style="text-align: justify">Bahwa Orang  tersebut  di  atas pada saat ini <strong>Berdomisili /Bertempat Tinggal</strong> di {{ $skd->alamat_skr }}.</td>
    </tr>
    <tr>
      <td colspan="4" style="text-align: justify">Demikian surat Keterangan ini di buat dengan sebenar- benarnya. Supaya di gunakan sebagaimana mestinya.</td>
    </tr>
    <tr>
      <td colspan="4">&nbsp;</td>
    </tr>
</table>
<table width="80%" border="0" align="center" cellspacing="0" cellpadding="2">
    <tr>
      <td width="54%">&nbsp;</td>
      <td width="46%">Durikulon, {{ Carbon\Carbon::parse($skd->created_at)->isoFormat('dddd D MMMM Y') }}</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>Kepala Desa Durikulon</td>
    </tr>

    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><u><strong>ALI</strong></u></td>
    </tr>
</table>
@endforeach
</body>
</html>



