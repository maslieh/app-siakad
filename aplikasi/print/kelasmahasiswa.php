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

$logo =
    $perguruantinggi['logo'] == ''
        ? 'images/logo-depan.png'
        : 'images/' . $perguruantinggi['logo'] . '';
$tanggal = converttgl(date('Y-m-d'));
echo '
<table width="700" border="1" align=center cellspacing="1" cellpadding="1" class=no-style>
  <tr>
    <td width="16%" rowspan="4"><img src="' .
    $logo .
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
    <td align=center>
	
	<h2>DAFTAR MAHASISWA KELAS ' .
    viewkelas($_SESSION['kelas']) .
    '</h2>
	<u><h2>' .
    strtoupper(viewprodi('' . $_SESSION['prodi'] . '')) .
    '</h2></u></td>
  </tr>
</table>';

$whr[] = "status_aktif='A'";
if (!empty($_SESSION['prodi'])) {
    $whr[] = "kode_prodi='$_SESSION[prodi]'";
}
if (!empty($_SESSION['kelas'])) {
    $whr[] = "masuk_kelas='$_SESSION[kelas]'";
}
$whr[] = "semester='$_SESSION[semester]'";
if (!empty($whr)) {
    $strwhr = 'where ' . implode(' and ', $whr);
}

echo '<br/><table width="100%" border="0" align="center" cellpadding="1" cellspacing="0" class="rapor full">
   	<thead>
     <tr>
	   <th width="5%" align="center">No</th>
	   <th align="center">NIM</th>
       <th align="center">Nama</th>
       <th align="center">Angkatan</th>
	  
	   <th align="center" width="200">Tanda Tangan</th>
     </tr>
	 </thead>
	 <tbody>';

$q = "select  * from m_mahasiswa $strwhr";
$pilih = $koneksi_db->sql_query($q);
$jumlah = $koneksi_db->sql_numrows($pilih);
$n = 0;
if ($jumlah > 0) {
    while ($wr = $koneksi_db->sql_fetchassoc($pilih)) {
        $n++;
        $id = $wr['idm'];
        echo '<tr >
				<td  align=center>' .
            $n .
            '</td> 
				<td  align=center>' .
            $wr['NIM'] .
            '</a></td>
				<td  align=left>' .
            $wr['nama_mahasiswa'] .
            '</td>
				<td  align=left>' .
            $wr['tahun_masuk'] .
            '</td>
				<td  align=center>' .
            $n .
            '(....................)</td>
			</tr>';
    }
} else {
    echo '
		 <thead><tr > 
			<th  colspan="5" align=center>Belum ada Data</th>
			</tr>
		</thead>';
}
echo '</tbody>
		</table>';

echo '</td></tr></table>';
?>
