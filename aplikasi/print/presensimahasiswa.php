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

global $koneksi_db, $tahun_id;
$prodi = $_SESSION['prodi'];
$kelas = $_SESSION['kelas'];
$idm = $_REQUEST['idm'];
$kode_mk = $_SESSION['kode_mk'];

if (!empty($prodi) && !empty($kelas)) {
    $w = $koneksi_db->sql_fetchassoc(
        $koneksi_db->sql_query(
            "SELECT * FROM m_mahasiswa where idm='" . $idm . "' limit 1 "
        )
    );
    $foto =
        $w['foto'] == ''
            ? 'images/logo-depan.png'
            : 'images/avatar/' . $w['foto'] . '';

    echo '<br/><br/><div class="mainContentCell"><div class="content">';

    echo '
		<table  border="0" cellspacing="1"class="datatable " cellpadding="1">
		<thead>
		  <tr>
			<td width="149">NIM</td>
			<td width="436"><b>' .
        $w['NIM'] .
        '</b></td>
			<td width="37" valign="top" rowspan="5"><img src="' .
        $foto .
        '" width="80" height="80"></td>
		  </tr>
		  <tr>
			<td>NAMA</td>
			<td><b >' .
        $w['nama_mahasiswa'] .
        '</b></td>
		  </tr>
		  <tr>
			<td>KONSENTRASI</td>
			<td><b >' .
        viewkonsentrasi('' . $w['kode_konsentrasi'] . '') .
        '</b ></td>
		  </tr>
		  <tr>
			<td>PROGRAM STUDI </td>
			<td><b >' .
        viewprodi('' . $w['kode_prodi'] . '') .
        '</b ></td>
		  </tr>
		  <tr>
			<td>BATAS STUDI </td>
			<td><b >' .
        strtoupper(NamaTahun($w['batas_studi'], $w['kode_prodi'])) .
        '</b ></td>
		  </tr>
		  </thead>
		</table> <br/>';

    echo '		
	<table width="700" border="0" align="center" cellpadding="1" cellspacing="0" class="rapor">
		<thead>
		 <tr>
		   <th width="5%" rowspan="2" align="center">No</th>
		   <th rowspan="2" align="center">Hari</th>
		   <th rowspan="2" align="center">Tanggal</th>
		   <th colspan="4" align="center">Presensi</th>
		 </tr>
		 <tr>
		 
		   <th align="center" width="60">Hadir</th>
		   <th align="center" width="60">Sakit</th>
		   <th align="center" width="60">Ijin</th>
		    <th align="center" width="60">Alpa</th>
		  </tr>
		 </thead>
		 <tbody>';

    $whr[] = "a.kode_prodi='$_SESSION[prodi]'";
    $whr[] = "a.tahun_id='$tahun_id'";
    $whr[] = "a.kelas='$_SESSION[kelas]'";
    $whr[] = "a.id='$_SESSION[kode_mk]'";
    $whr[] = "a.idm='$idm'";
    $whr[] = 'a.jam=b.idj';
    if (!empty($whr)) {
        $strwhr = 'where ' . implode(' and ', $whr);
    }

    $q = "select  a.*, mulai, sampai from t_mahasiswa_presensi a inner join m_jam b $strwhr order by tanggal";
    $pilih = $koneksi_db->sql_query($q);
    $jumlah = $koneksi_db->sql_numrows($pilih);

    if ($jumlah > 0) {
        $no = 0;
        while ($w = $koneksi_db->sql_fetchassoc($pilih)) {
            $no++;
            $id = $w[0];
            echo '<tr >
						<td  align=center>' .
                $no .
                '</td> 
						<td align="center">	' .
                $w['hari'] .
                '</td>
						<td align="center">	' .
                converttgl($w['tanggal']) .
                '</td>';

            $query = $koneksi_db->sql_query(
                "SELECT * FROM r_kode where aplikasi = '59'  "
            );
            while ($r = $koneksi_db->sql_fetchrow($query)) {
                $ck = $r[2] == $w['jenis_presensi'] ? '1' : '';
                echo '<td  align=center>' . $ck . '</td>';
            }

            echo '</tr>';
        }
        echo ' <thead> 	<tr >
					<th  colspan="3" align=center>Total Presensi</th>
					
					<th  align=center>' .
            hitungpresensimahasiswa($prodi, $tahun_id, 'H', $kode_mk, $idm) .
            '</th>
					<th  align=center>' .
            hitungpresensimahasiswa($prodi, $tahun_id, 'S', $kode_mk, $idm) .
            '</th>
					<th  align=center>' .
            hitungpresensimahasiswa($prodi, $tahun_id, 'I', $kode_mk, $idm) .
            '</th>
					<th  align=center>' .
            hitungpresensimahasiswa($prodi, $tahun_id, 'A', $kode_mk, $idm) .
            '</th>
					
					</tr></thead>';
    } else {
        echo ' <thead> 	<tr ><th  colspan="8" align=center>Belum ada data</th>	</tr></thead>';
    }

    echo '</tbody>
			</table>';
    echo '</div></div>';
} else {
    echo "<div  class='error'>Program studi, Kelas belum dipilih</div>";

    echo '<input type="button" class=tombols ui-corner-all value="Kembali" onClick="window.location = \'index.php?m=presensi.mahasiswa\'"/>';
}

?>
