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

global $koneksi_db, $tahun_id, $user, $prodi;
$prodi = $_SESSION['prodi'];
$kode_mk = $_SESSION['kode_mk'];
$kelas = $_SESSION['kelas'];
$ord = 'order by idm';

$jTugas = '';

$sekarang = date('Y-m-d');
$foto =
    $perguruantinggi['logo'] == ''
        ? 'images/logo-depan.png'
        : 'images/' . $perguruantinggi['logo'] . '';

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
    <td align=center><h2><BR>BERITA ACARA PERKULIAHAN SEMESTER' .
    strtoupper(NamaTahun($tahun_id, $prodi)) .
    '</h2></td>
  </tr>
</table>';

$pr = $koneksi_db->sql_fetchassoc(
    $koneksi_db->sql_query(
        "SELECT nama_prodi FROM m_program_studi where `kode_prodi` = '$prodi' limit 1 "
    )
);
$mk = $koneksi_db->sql_fetchassoc(
    $koneksi_db->sql_query(
        "SELECT nama_mk FROM m_mata_kuliah where `id` = '$kode_mk' limit 1 "
    )
);
$wm = $koneksi_db->sql_fetchassoc(
    $koneksi_db->sql_query(
        "SELECT nama_dosen, idd FROM  view_jadwal  where kode_prodi='$prodi' and tahun_id='$tahun_id' and kelas='$kelas' and id='$kode_mk'  limit 1 "
    )
);
$idd = $wm['idd'];
echo " 	</br>
		<table width=750 >
		<tr>
		<td align=left width=150>
		Program Studi </td>
		<td align=left> : </td>";
echo '	<td align=left>' . $pr[nama_prodi] . ' </td>';
echo "	</tr>
		<tr>
		<td align=left width=150>
		Mata Kuliah </td>
		<td align=left> : </td>";
echo '	<td align=left>' . $mk[nama_mk] . ' </td>';
echo "	</tr>
		<tr>
		<td align=left width=150>
		Kelas </td>
		<td align=left> : </td>";
echo '	<td align=left>';
echo $kelas;
echo '</td>';
echo "	</tr>
		<tr>
		<td align=left width=150>
		Dosen </td>
		<td align=left> : </td> ";
echo '	<td align=left>' . $wm[nama_dosen] . ' </td>';
echo '	</tr> </table>';

echo '
		
	</br>
	<table class="table table-striped table-bordered table-hover"  >
		<thead>
		 <tr>
		   <th width="5%" rowspan="2" align="center">No</th>
		   <th rowspan="2" align="center">Hari</th>
		   <th rowspan="2" align="center">Tanggal</th>
		   <th rowspan="2" align="center">Jam</th>
		   <th colspan="2" align="center">Jumlah Mahasiswa</th>
		   <th rowspan="2" align="center">Pokok Bahasan</th>
		 </tr>
		 <tr>
		   
		   <th align="center" width="60">Terdaftar</th>
		   <th align="center" width="60">Hadir</th>

		  </tr>
		 </thead>
		 <tbody>';

$whr2[] = "a.kode_prodi='$prodi'";
$whr2[] = "a.tahun_id='$tahun_id'";
$whr2[] = "a.kelas='$kelas'";
$whr2[] = "a.id='$kode_mk'";
$whr2[] = "a.idd='$idd'";
$whr2[] = "a.jenis_presensi='H'";
if (!empty($whr2)) {
    $strwhr2 = 'where ' . implode(' and ', $whr2);
}

$q = "select  a.*, mulai, sampai from t_dosen_presensi a inner join m_jam b on a.jam=b.idj $strwhr2 order by tanggal";

$r = $koneksi_db->sql_fetchassoc(
    $koneksi_db->sql_query("select  count(idm) as jum from t_mahasiswa_krs where
			kode_prodi='$prodi' and tahun_id='$tahun_id' and kelas='$kelas' and id='$kode_mk'
			")
);

$pilih = $koneksi_db->sql_query($q);
$jumlah = $koneksi_db->sql_numrows($pilih);

if ($jumlah > 0) {
    $no = 0;
    while ($w = $koneksi_db->sql_fetchassoc($pilih)) {
        $s = $koneksi_db->sql_fetchassoc(
            $koneksi_db->sql_query(
                "select  count(idm) as jum2 from t_mahasiswa_presensi where
				kode_prodi='$prodi' and tahun_id='$tahun_id' and kelas='$kelas' and id='$kode_mk' and tanggal='" .
                    $w['tanggal'] .
                    "' and 
				jenis_presensi='H' and jam='" .
                    $w['jam'] .
                    "'
				"
            )
        );

        $no++;

        echo '<tr >
						<td  align=center>' .
            $no .
            '</td> 
						<td align="center">	' .
            $w['hari'] .
            '</td>
						<td align="center">	' .
            converttgl($w['tanggal']) .
            '</td>
						<td align="center">	' .
            $w['mulai'] .
            ' -- ' .
            $w['sampai'] .
            '</td>
						<td align="center">	' .
            $r['jum'] .
            ' </td>
						<td align="center">	' .
            $s['jum2'] .
            ' </td>
						<td align="center">	' .
            $w['bap'] .
            '</td>
						</tr>';
    }
} else {
    echo ' <thead> 	<tr ><th  colspan="8" align=center>Belum ada data</th>	</tr></thead>';
}

echo '</tbody>
			</table>';

$sekarang = date('d-m-Y');
echo "<table width=750 >
			<td align=left>
			Jakarta, $sekarang
			</br>
			</br>
			</br>
			</br>
			</br>";

echo $wm['nama_dosen'];
echo '</td></table>';

?>