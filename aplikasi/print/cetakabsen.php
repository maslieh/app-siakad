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

global $koneksi_db, $user, $prodi, $tahun_id;
$prodi = $_SESSION['prodi'];

$kode_mk = $_SESSION['kode_mk'];
$kelas = $_SESSION['kelas'];

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

//$tahun_id = $_SESSION['tahun_id'];
if ($_SESSION['Level'] != 'MAHASISWA') {
    if ($_SESSION['Level'] == 'DOSEN') {
        echo '<div hidden class="col-md-6">';
        FilterMataKuliahDosen($prodi, $tahun_id, $user, $_GET['m']);
        echo '</div><div hidden class="col-md-6">';
        FilterKelas($prodi, $tahun_id, $user, $_GET['m']);
        echo '</div>';
    } elseif ($_SESSION['Level'] == 'PA') {
        echo '<div hidden class="col-md-6">';
        FilterMataKuliahDosen($prodi, $tahun_id, $user, $_GET['m']);
        echo '</div><div class="col-md-6">';
        FilterKelas($prodi, $tahun_id, $user, $_GET['m']);
        echo '</div>';
    } else {
        echo '<div hidden class="col-md-6">';
        FilterMataKuliahDosen($prodi, $tahun_id, '', $_GET['m']);
        echo '</div><div hidden class="col-md-6">';
        FilterKelas($prodi, $tahun_id, '', $_GET['m']);
        echo 'CETAK </div>';
        echo "
	
	
	
	";
    }
    $whr = [];
    $ord =
        'group by t_mahasiswa_presensi.idm order by t_mahasiswa_presensi.idm';

    $whr[] = "t_mahasiswa_presensi.kode_prodi='$_SESSION[prodi]'";
    $whr[] = "t_mahasiswa_presensi.tahun_id='$tahun_id'";
    $whr[] = "kelas='$_SESSION[kelas]'";
    $whr[] = "id='$_SESSION[kode_mk]'";
    if (!empty($whr)) {
        $strwhr = 'where ' . implode(' and ', $whr);
    }
} else {
    $strwhr = "where idm='$dosen'";
}
echo '	
<div style="overflow-x:auto;" >
<table width=750 style="table {
  border-collapse: collapse;
  border-spacing: 0;
  width: 100%;
  
  border: 1px solid #ddd;
}" >
   	<thead>
     <tr>
       <th width="5%" rowspan="2" align="center" width=150>No</th>
       <th rowspan="2" align="center" width="70">NIM</th>
       <th rowspan="2" align="center" >Nama</th>
       <th rowspan="2" align="center" width="50">Angkatan</th>
       <th colspan="4" align="center">Presensi ';
if ($_SESSION['Level'] != 'MAHASISWA') {
    echo '<a class="btn" href="index.php?m=' .
        $_GET['m'] .
        '&op=Input" class="button-red"><i class="fa fa-plus"></i></a>';
}
echo '</th>
      
     </tr>
     <tr>
	 <th align="center" width="40">Hadir</th>
	   <th align="center" width="40">Sakit</th>
	   <th align="center" width="40">Ijin</th>
	   <th align="center" width="40">Alpa</th>
      </tr>
	 </thead>
	 <tbody>';

require 'system/pagination_class.php';

$sql = "select t_mahasiswa_presensi.idm, NIM, nama_mahasiswa, tahun_masuk, 
					sum(case when t_mahasiswa_presensi.idm=m_mahasiswa.idm and jenis_presensi='H' then presensi else 0 end) as hadir,
					sum(case when t_mahasiswa_presensi.idm=m_mahasiswa.idm and jenis_presensi='S' then presensi else 0 end) as sakit,
					sum(case when t_mahasiswa_presensi.idm=m_mahasiswa.idm and jenis_presensi='I' then presensi else 0 end) as ijin,
					sum(case when t_mahasiswa_presensi.idm=m_mahasiswa.idm and jenis_presensi='A' then presensi else 0 end) as alpa
					from t_mahasiswa_presensi left join m_mahasiswa  using(idm) $strwhr $ord ";

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
    while ($wr = $koneksi_db->sql_fetchassoc($result)) {
        $n++;
        $id = $wr['idm'];
        $idmatkul = $wr['id'];

        echo '<tr bgcolor="#f2f2f2">
				<td  align=center>' .
            $n .
            '</td> 
				<td  align=center>
					' .
            $wr['NIM'] .
            '
				</td>
				<td  align=left>' .
            $wr['nama_mahasiswa'] .
            '</td>
				<td  align=left>' .
            $wr['tahun_masuk'] .
            '</td>
				<td  align=center>' .
            $wr['hadir'] .
            '</td>
				<td  align=center>' .
            $wr['sakit'] .
            ' </td>
				<td  align=center>' .
            $wr['ijin'] .
            ' </td>
				<td  align=center>' .
            $wr['alpa'] .
            ' </td>
			
			</tr>';
    }
} else {
    echo '
		 <thead><tr > 
			<th  colspan="9" align=center>Belum ada Data</th>
			</tr>
		</thead>';
}

echo '</tbody>
		</table>
		</div>
	    <br/>
	        <br/>
	            <br/>';

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