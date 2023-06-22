<?php

if (!login_check()) {
    //alihkan user ke halaman logout
    logout();
    session_destroy();
    //echo '<div class="message"><div class="success"><h3>Logout SUKSES</h3></div></div>';
    echo '<meta http-equiv="refresh" content="0; url=index.php" />';
    //exit(0);
}
if (!cek_login()) {
    header('location:index.php');
    exit();
}

echo '<table width="700"  border="0" cellspacing="1" cellpadding="1" ><tr><td align=left>';

global $koneksi_db, $tahun_id, $programstudi, $perguruantinggi;
$wj = $koneksi_db->sql_fetchassoc(
    $koneksi_db->sql_query(
        "SELECT * FROM m_program_studi where kode_prodi='" .
            $programstudi['kode_prodi'] .
            "' limit 1 "
    )
);
$tanggal = converttgl(date('Y-m-d'));
$DariNIM = $_REQUEST['dariNIM'];
$SampaiNIM = $_REQUEST['sampaiNIM'];

$whr[] = "'$DariNIM' <= NIM";
$whr[] = "NIM <= '$SampaiNIM'";
$whr[] = "semester_masuk='$tahun_id'";
if (!empty($_SESSION['prodi'])) {
    $whr[] = "kode_prodi='$_SESSION[prodi]'";
}
if (!empty($whr)) {
    $strwhr = 'where ' . implode(' and ', $whr);
}

$idm = $_REQUEST['idm'];
if (empty($idm) || !isset($idm)) {
    echo "<meta http-equiv='refresh' content='3; url=index.php?m=kstcetak'>";
}
$w = $koneksi_db->sql_fetchassoc(
    $koneksi_db->sql_query(
        "SELECT * FROM m_mahasiswa where idm='" . $idm . "' limit 1 "
    )
);
$foto =
    $perguruantinggi['logo'] == ''
        ? 'images/logo-depan.png'
        : 'images/' . $perguruantinggi['logo'] . '';

$tanggal = converttgl(date('Y-m-d'));

echo '
<table width="700" border="1" align=center cellspacing="1" cellpadding="1" class=no-style>
  <tr>
    <td width="16%" rowspan="4"><img src="' .
    $foto .
    '" width="80" height="80"></td>
    <td valign=top align=center><h1>' .
    $perguruantinggi['nama_pt'] .
    '</h1></td>
  </tr>
  <tr>
    <td width="84%" align=center>
	' .
    $perguruantinggi['alamat_1'] .
    ' 
	' .
    viewkota($perguruantinggi['kode_kota']) .
    ' 
	' .
    viewpropinsi($perguruantinggi['kode_propinsi']) .
    ' 
	</td>
  </tr>
  <tr>
    <td align=center>Telp. ' .
    $perguruantinggi['telepon'] .
    ' 
	Email ' .
    $perguruantinggi['email'] .
    '  
	Website ' .
    $perguruantinggi['website'] .
    ' </td>
  </tr>
  <tr>
    <td align=center><h2>KARTU STUDI TETAP</h2></td>
  </tr>
</table>';

echo '	<br/>
<table width="700" border="1" cellspacing="1" cellpadding="1" class=no-style>
  <tr>
    <td width="150">NAMA</td>
    <td width="5">:</td>
    <td width="230"><strong>' .
    $w['nama_mahasiswa'] .
    '</strong></td>
    <td width="30">&nbsp;</td>
 <td>TAHUN AJARAN </td>
    <td>:</td>
    <td><strong>' .
    strtoupper(NamaTahun($tahun_id, $prodi)) .
    '</strong></td>
  </tr>
  <tr>
    <td>NIM</td>
    <td>:</td>
    <td><strong>' .
    $w['NIM'] .
    '</strong></td>
    <td>&nbsp;</td>
    <td>PROGRAM STUDI </td>
    <td>:</td>
    <td><strong>' .
    viewprodi('' . $w['kode_prodi'] . '') .
    '</strong></td>
  </tr>
  <tr>
    <td>SEMESTER</td>
    <td>:</td>
    <td><strong>' .
    $tahun_id .
    '</strong></td>
    <td>&nbsp;</td>
  </tr>

</table>';

echo '<br/>
<table width="730" border="1" cellspacing="1" cellpadding="1" class=rapor1>
  <tr>
    <th width="10">NO</th>
    <th width="100">KODE MK </th>
    <th width="500" >MATA KULIAH </th>
	<th width="50">DOSEN</th>
	<th colspan=2 >SKS</th>
  </tr>';

$q = "select  k.*, m.*, n.* from 
		                    t_mahasiswa_krs k 
							left  join m_mata_kuliah m on k.id=m.id 
							inner join r_kode n on k.hari=n.kode 
							where k.kode_prodi='$w[kode_prodi]' and k.idm='$w[idm]' and k.tahun_id='$tahun_id' and k.verifi_pa='1'
							order by m.nama_mk";

$pilih = $koneksi_db->sql_query($q);
$jumlah = $koneksi_db->sql_numrows($pilih);
$jumlah_sks = jumlah_sks(
    $w['kode_prodi'],
    $tahun_id,
    $w['semester'],
    $w['idm']
);

$no = 0;
if ($jumlah > 0) {
    while ($k = $koneksi_db->sql_fetchassoc($pilih)) {
        $totsks += $k['sks'];
        $idk = $k['id'];
        $kl = $k['kelas'];
        $idmm = $w['idm'];
        //	 $count1= mysqli_num_rows(mysqli_query($config, "SELECT * FROM view_jadwal where id='$idk'"));
        // $count1 = $koneksi_db->sql_numrows($koneksi_db->sql_query( "SELECT * FROM view_jadwal where id='$idk'" ));
        $www = $koneksi_db->sql_fetchassoc(
            $koneksi_db->sql_query(
                "SELECT * FROM view_jadwal q join   t_mahasiswa_krs e where q.id='$idk'AND e.id='$idk' AND q.idm='$idmm' "
            )
        );

        $no++;
        echo '<tr >
					<td  align=center>' .
            $no .
            '</td> 
					<td valign="top" align="center">' .
            $k['kode_mk'] .
            '</td>
					<td valign="top ">' .
            $k['nama_mk'] .
            '</td>';

        echo '
					<td valign="top align=center">' .
            $www['nama_dosen'] .
            '</td>
					<td valign="top "align=center>' .
            $k['sks'] .
            '</td>';
        if ($n % 2 == 0) {
            echo '</tr><tr>';
        }
    }
    echo '<tr><td colspan=9></td></tr>';
    echo '
              <tr>
                <th  colspan=5 width="470" align=right>TOTAL</th>
                <td width="50">' .
        $totsks .
        '</td>
                <td colspan=3 width="50">SKS</td>
              </tr>';
} else {
    echo '<tr><td colspan=7> Belum ambil KRS</td></tr>';
    // echo '<tr><td colspan=9></td></tr>';
    echo '
      <tr>
        <th  colspan=5 width="470" align=right>TOTAL</th>
        <td width="50">' .
        $totsks .
        '</td>
      </tr>';
}
/// perulangan
echo '</table>';
//
echo '
<table width="730" border=1 cellspacing="1" cellpadding="1" class=no-style >
  <tr>
    <td align=left valign=left width="50%" width=100 height=70><br>
	<p align=center>Nama Mahasiswa <br/>
  </p>
	</td>
    <td align=center valign=midle  height=70>
	' .
    viewkota($perguruantinggi['kode_kota']) .
    ', ' .
    $tanggal .
    '<br/>
	Sekretariat 
	' .
    $perguruantinggi['nama_pt'] .
    '
	</td>
 
  <tr align=center valign=bottom height=80>
    <td><b>
    ' .
    $w['nama_mahasiswa'] .
    '</b><br>' .
    $w['NIM'] .
    '
	</td>
    <td align=center>
	' .
    viewlektorkepala() .
    '
	</td>
  </tr>
   <tr>
    <td colspan=2 align=left valign=midle height=70><br>
	<b><u>Catatan : </u></b> <br/>
	*) Kartu Studi Tetap (KST), harus dibawa saat Mid Test dan Final Test. <br/>
	*) Final Test wajib mengenakan kemeja putih, bawahan hitam dan bersepatu. <br/>
	*) Terlambat lebih dari 40 menit tidak diperkenankan mengikuti Final Test. <br/>
	*) Pelanggaran atau susulan Final Test dikenakan sanksi potongan nilai Final Test 15%.
	</td>
  </tr>
</table>';

echo '</td></tr></table>';
?>
