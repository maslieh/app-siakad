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
    <td align=center><h2><BR>DAFTAR NILAI MAHASISWA ' .
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

echo "<div style=overflow:auto;padding: 5px; >
		</br>
		<table width=750 class=rapor >
			  <tr>
				<TH rowspan='3' valign=middle align=center width=5> No.</TH>
				<TH rowspan='3' valign=middle align=center width=40>NIM</TH>
				<TH rowspan='3' valign=middle align=center width=150>Nama Mahasiswa</TH>
				<TH rowspan='3' valign=middle align=center width=10>L/P</TH>
				<TH colspan='6' valign=middle align=center>KOMPONEN NILAI</TH>
			  </tr>
			  <tr>
				<TH rowspan='2' width=20 valign=middle>Kehadiran</TH>
				<TH rowspan='2' width=20 valign=middle>Tugas</TH>
				<TH rowspan='2' width=20 valign=middle>UTS</TH>
				<TH rowspan='2' width=20 valign=middle>UAS</TH>
				<TH rowspan='2' width=20 align=center style='background-color:#eee;' valign=middle>Rata</TH>
				<TH rowspan='2' width=20 align=center style=background-color:#FDFECB; valign=middle>Nilai</TH>
			  </tr>
			  <tr>
			  	$headerUL
			  	$headerTG
			  </tr>	
			  <tbody>		
			";

$whr[] = "kode_prodi='$prodi'";
$whr[] = "tahun_id='$tahun_id'";
$whr[] = "kelas='$kelas'";
$whr[] = "id='$kode_mk'";
$whr[] = "verifi_pa='1'";

if (!empty($whr)) {
    $strwhr = 'where ' . implode(' and ', $whr);
}

require 'system/pagination_class.php';
$sql = "select * from t_mahasiswa_krs  $strwhr";
if (isset($_GET['starting'])) {
    //starting page
    $starting = $_GET['starting'];
} else {
    $starting = 0;
}
$n = $starting;
$recpage = 100; //jumlah data yang ditampilkan per page(halaman)
$obj = new pagination_class($koneksi_db, $sql, $starting, $recpage);
$result = $obj->result;
if ($koneksi_db->sql_numrows($result) != 0) {
    while ($k = $koneksi_db->sql_fetchassoc($result)) {
        $no++;

        $w = $koneksi_db->sql_fetchassoc(
            $koneksi_db->sql_query(
                "SELECT NIM, nama_mahasiswa, jenis_kelamin  FROM m_mahasiswa where idm='" .
                    $k['idm'] .
                    "' "
            )
        );
        $wtugas = $koneksi_db->sql_fetchassoc(
            $koneksi_db->sql_query(
                "SELECT nilai FROM t_mahasiswa_nilai where id='" .
                    $k['id'] .
                    "' and idm='" .
                    $k['idm'] .
                    "' and jenis_nilai='TUGAS' and tahun_id='" .
                    $tahun_id .
                    "'"
            )
        );
        $wuts = $koneksi_db->sql_fetchassoc(
            $koneksi_db->sql_query(
                "SELECT nilai FROM t_mahasiswa_nilai where id='" .
                    $k['id'] .
                    "' and  idm='" .
                    $k['idm'] .
                    "' and jenis_nilai='UTS' and tahun_id='" .
                    $tahun_id .
                    "'"
            )
        );
        $wuas = $koneksi_db->sql_fetchassoc(
            $koneksi_db->sql_query(
                "SELECT nilai FROM t_mahasiswa_nilai where id='" .
                    $k['id'] .
                    "' and  idm='" .
                    $k['idm'] .
                    "' and jenis_nilai='UAS' and tahun_id='" .
                    $tahun_id .
                    "'"
            )
        );
        $whadir = $koneksi_db->sql_fetchassoc(
            $koneksi_db->sql_query(
                "SELECT nilai FROM t_mahasiswa_nilai where id='" .
                    $k['id'] .
                    "' and  idm='" .
                    $k['idm'] .
                    "' and jenis_nilai='HADIR' and tahun_id='" .
                    $tahun_id .
                    "'"
            )
        );
        echo "<tr>
				<td >$no</td>
				<td >$w[NIM]</td>
				<td >$w[nama_mahasiswa]</td>
				<td >$w[jenis_kelamin]</td>";

        //////////////////////////
        echo '<td align=center>' . $whadir['nilai'] . '</td>'; // nilai kehadiran
        echo '<td align=center>' . $wtugas['nilai'] . '</td>'; ///nilai Tugas
        echo '<td align=center>' . $wuts['nilai'] . '</td>'; ///nilai UTS
        echo '<td align=center>' . $wuas['nilai'] . '</td>'; // nilai UAS
        echo '<td align=center>' . $k['jumlah_nilai'] . '</td>';
        echo '<td align=center>' . $k['nilai'] . '</td>';
        echo '</tr>';
    }
} else {
    echo ' <tr >
			  		<TH  colspan="4" align=center></TH>	
			  		<TH  colspan="$colMP" align=center>Belum ada data</TH>	
			  		</tr>';
}

echo '</tbody>
			</table><br/>
			</div>';
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