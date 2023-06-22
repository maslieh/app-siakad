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

$whr = [];
/*
  $ord = '';
  if (($_SESSION['reset_mahasiswa'] != 'Reset') &&
  !empty($_SESSION['kolom_mahasiswa']) && !empty($_SESSION['kunci_mahasiswa'])) {
    $whr[] = "$_SESSION[kolom_mahasiswa] like '%$_SESSION[kunci_mahasiswa]%' ";
    $ord = "order by $_SESSION[kolom_mahasiswa]";
  }
  */
//$whr[] = "status_aktif='A'";
$whr[] = "kode_prodi='$_SESSION[prodi]'";
$whr[] = "kelas='$_SESSION[kelas]'";
$whr[] = "id='$_SESSION[kode_mk]'";
if (!empty($whr)) {
    $strwhr = 'where ' . implode(' and ', $whr);
}

//echo   $strwhr;

$idd = $_REQUEST['idd'];
$id = $_REQUEST['id'];
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
//$d = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM m_dosen where idd='".$idd."' limit 1 " ));
/*$z = "select  k.*, m.*,n.* from t_dosen_pengajaran k 
			  left outer join m_dosen m on k.idd=m.idd inner join m_matakuliah n on k.id=n.id
		      where k.kode_prodi='$d[kode_prodi]' and k.id='$id'"; */
$sekarang = date('Y-m-d');
$foto =
    $perguruantinggi['logo'] == ''
        ? 'images/logo-depan.png'
        : 'images/' . $perguruantinggi['logo'] . '';
//$jenis = $_REQUEST['jenis'];

//if ($jenis=="H") { $jenisp = "HADIR"; } else { $jenisp = ''.viewAplikasi('59',''.$jenis.'').''; }
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
    <td align=center><h2><BR>DAFTAR HADIR PERKULIAHAN ' .
    strtoupper(NamaTahun($tahun_id, $prodi)) .
    '</h2></td>
  </tr>
</table>';

echo '	<br/>
<table width="700" border="1" cellspacing="1" cellpadding="1" class=no-style>
  <tr>
	<td></td> 
    <td width="120">PROGRAM STUDI </td>
    <td width="10">:</td>
    <td width="200"><strong>' .
    $pr['nama_prodi'] .
    '</strong></td>
    <td width="120">MATAKULIAH</td>
    <td>:</td>
    <td width="200"><strong>' .
    $mk['nama_mk'] .
    '</strong></td>
  </tr>
   <tr>
    <td></td>
    <td width="50">DOSEN</td>
    <td width="5">:</td>
    <td width="150"><strong>' .
    $wm['nama_dosen'] .
    '</strong></td>
    <td width="50">Kelas</td>
    <td width="5">:</td>
    <td width="100"><strong>' .
    $kelas .
    '</strong></td>
   
  </tr>
 
  

</table>';

echo '
<br>
		
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="0" class="rapor1">
   	<thead>
		<tr>
			<th width="10" rowspan="3">NO</th>
			<th width="100" rowspan="3">NIM </th>
			<th width="150" rowspan="3">NAMA </th>
			<th width="50" colspan="10">PERTEMUAN KE</th>
		</tr>
		<tr>
			<th>01</th>
			<th>02</th>
			<th>03</th>
			<th>04</th>
			<th>05</th>
			<th>06</th>
			<th>07</th>
			<th>08</th>
			<th>09</th>
			<th>10</th>
			
		</tr>
		<tr>
			<th>Tgl</th>
			<th>Tgl</th>
			<th>Tgl</th>
			<th>Tgl</th>
			<th>Tgl</th>
			<th>Tgl</th>
			<th>Tgl</th>
			<th>Tgl</th>
			<th>Tgl</th>
			<th>Tgl</th>
		</tr>
	 </thead>
	 <tbody>';

$q = "select  * from t_mahasiswa_krs $strwhr";
$pilih = $koneksi_db->sql_query($q);
$jumlah = $koneksi_db->sql_numrows($pilih);

if ($jumlah > 0) {
    $n = 0;
    while ($wr = $koneksi_db->sql_fetchassoc($pilih)) {
        $n++;
        $id = $wr['idkrs'];
        $idm = $wr['idm'];
        $wm = $koneksi_db->sql_fetchassoc(
            $koneksi_db->sql_query(
                "SELECT * FROM m_mahasiswa where `idm` = '$idm' limit 1 "
            )
        );
        echo '<tr bgcolor="">
				<td  align=center>' .
            $n .
            '<input type="hidden" name="idm[' .
            $id .
            ']" value="' .
            $id .
            '"/></td> 
				<td  align=center>' .
            $wm['NIM'] .
            '</a></td>
				<td  align=left>' .
            $wm['nama_mahasiswa'] .
            '</td>
				<td  align=left></td>
				<td  align=left></td>
				<td  align=left></td>
				<td  align=left></td>
				<td  align=left></td>
				<td  align=left></td>
				<td  align=left></td>
				<td  align=left></td>
				<td  align=left></td>
				<td  align=left></td>
			</tr>';
    }
    echo '<thead><tr > 
				
			</tr></thead>';
} else {
    echo '
		 <thead><tr > 
			<th  colspan="8" align=center>Belum ada Data</th>
			</tr>
		</thead>';
}
echo '</tbody>
		</table></form>';
?>
