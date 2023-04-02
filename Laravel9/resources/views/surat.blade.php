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
<title>SURAT KETERANGAN TIDAK MAMPU</title>
</head>
<body>
@foreach($data_sktm as $sktm)
<table width="80%" border="0" align="center" cellspacing="0" cellpadding="2">
	<tr>
    <td rowspan="5" width="15%" align="center"><img src="{{ asset('Image/logo durikulon.jpg') }}" width="100px"/></td>
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
      <td colspan="2"><div align="center"><strong><u>SURAT KETERANGAN TIDAK MAMPU</u></strong></div></td>
    </tr>
    <tr>
      <td colspan="2"><div align="center">No: 465 / {{$sktm->id_sktm}} / 413.308.05 / 2023</div></td>
    </tr>
    <tr>
    </tr>
    <tr>
      <td colspan="2"><input type="button" name="Button" value="Cetak" ONCLICK=Cetakan()></td>
    </tr>
</table>
<table width="80%" border="0" align="center" cellspacing="0" cellpadding="2">
    <tr>
      <td colspan="4" style="text-align: justify">Yang bertanda tangan di bawah ini Kepala Desa Durikulon Kecamatan Laren Kabupaten Lamongan menerangkan: </td>
    </tr>
    <tr style="vertical-align: top;">
      <td width="10%">&nbsp;</td>
      <td width="30%">N a m a</td>
      <td>:</td>
      <td> {{ $sktm->nama }} </td>
    </tr>
    <tr style="vertical-align: top;">
        <td>&nbsp;</td>
        <td>NIK</td>
        <td>:</td>
        <td> {{ $sktm->nik }}</td>
    </tr>
    <tr style="vertical-align: top;">
      <td>&nbsp;</td>
      <td>Tempat/Tanggal Lahir</td>
      <td>:</td>
      <td> {{ $sktm->tmpt_tgl_lahir }}</td>
    </tr>
    <tr style="vertical-align: top;">
      <td>&nbsp;</td>
      <td>Jenis Kelamin</td>
      <td>:</td>
      <td>{{ $sktm->jk }}</td>
    </tr>
    <tr style="vertical-align: top;">
      <td>&nbsp;</td>
      <td>NIK Kepala Keluarga</td>
      <td>:</td>
      <td> {{ $sktm->nik_kepala }}</td>
    </tr>
    <tr style="vertical-align: top;">
      <td>&nbsp;</td>
      <td>Nama Kepala Keluarga</td>
      <td>:</td>
      <td> {{ $sktm->nama_kepala }}</td>
    </tr>
    <tr style="vertical-align: top;">
      <td>&nbsp;</td>
      <td>Alamat pada KTP</td>
      <td>:</td>
      <td> {{ $sktm->alamat_ktp }} </td>
    </tr>
    <tr>
      <td colspan="4" style="text-align: justify">Orang tersebut di atas adalah penduduk Desa Durikulon dari  Keluarga  Tidak Mampu. Surat  keterangan  ini  di  berikan kepada orang tersebut diatas untuk {{ $sktm->keperluan }}.</td>
    </tr>
    <!--<tr>
      <td colspan="3">Adalah benar-benar Mahasiswa Politeknik Negeri Jember yang masih Aktif Kuliah pada Tahun Akademik 2022/2023 Semester
        Ganjil      .</td>
    </tr>
    <tr>
      <td colspan="3">Surat Keterangan ini dipergunakan untuk .</td>
    </tr>-->
    <tr>
      <td colspan="4">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="4" style="text-align: justify">Demikian surat keterangan ini dibuat untuk dipergunakan sebagaimana mestinya.</td>
    </tr>
    <tr>
      <td colspan="4">&nbsp;</td>
    </tr>
</table>
<table width="80%" border="0" align="center" cellspacing="0" cellpadding="2">
    <tr>
      <td width="54%">&nbsp;</td>
      <td width="46%">Durikulon, {{ Carbon\Carbon::parse($sktm->created_at)->isoFormat('dddd D MMMM Y') }} </td>
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



