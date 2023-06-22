<?php
 
if (!login_check()) {
		//alihkan user ke halaman logout
		logout ();
		session_destroy();
		//echo '<div class="message"><div class="success"><h3>Logout SUKSES</h3></div></div>';
		echo '<meta http-equiv="refresh" content="0; url=index.php" />';
		//exit(0);
}
if (!cek_login ()){
header ("location:index.php");
exit;
}
if ($_SESSION['Level']!="ADMIN"	) {
header ("location:index.php");
exit;
}


global $koneksi_db, $tahun_id;
$prodi = $_SESSION['prodi'];
$semester = $_SESSION['semester'];
$kelas = $_SESSION['kelas'];
$idd =$_REQUEST['idd'];


if ( !empty($prodi)  ) {
$w = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM m_dosen where idd='".$idd."' limit 1 " ));
$foto= ($w['foto'] =="" ) ? "images/no_avatar.gif": "images/avatar/".$w['foto']."";
	
	

echo '<br/><br/><div class="mainContentCell"><div class="content">';
	
echo '
		<table  border="0" cellspacing="1" cellpadding="1" class="datatable " >
		<thead>
		  <tr>
			<td width="149">NIP</td>
			<td width="436"><b>'.$w['NIDN'].'</b></td>
			<td width="37" valign="top" rowspan="4"><img src="'.$foto.'" width="80" height="80"></td>
		  </tr>
		  <tr>
			<td>NAMA</td>
			<td><b >'.$w['gelar_depan'].' '.$w['nama_dosen'].', '.$w['gelar_belakang'].'</b></td>
		  </tr>
		  <tr>
			<td>JABATAN</td>
			<td><b >'.viewAplikasi('02',''.$w['jabatan_akademik'].'').'</b ></td>
		  </tr>
		  <tr>
			<td>PANGKAT/GOLONGAN</td>
			<td><b >'.viewAplikasi('56',''.$w['pangkat_golongan'].'').'</b ></td>
		  </tr>
		  </thead>
		</table><br/>';
			
	echo '		
	<table width="700" border="0" align="center" cellpadding="1" cellspacing="0" class="rapor">
		<thead>
		 <tr>
		   <th width="5%" rowspan="2" align="center">No</th>
		   <th rowspan="2" align="center">Hari</th>
		   <th rowspan="2" align="center">Tanggal</th>
		   <th rowspan="2" align="center">Jam</th>
		   <th colspan="4" align="center">Presensi</th>
		 </tr>
		 <tr>
		   
		   <th align="center" width="60">Alpa</th>
		   <th align="center" width="60">Ijin</th>
		   <th align="center" width="60">Sakit</th>
		   <th align="center" width="60">Hadir</th>
		  </tr>
		 </thead>
		 <tbody>';
					
			$whr[] = "kode_prodi='$prodi'";
			$whr[] = "tahun_id='$tahun_id'";
			//$whr[] = "semester='$semester'";
			$whr[] = "idd='$idd'";
			if (!empty($whr)) $strwhr = "where " .implode(' and ', $whr);

			$q = "select  * from t_dosen_presensi 	$strwhr order by tanggal";
			$pilih = $koneksi_db->sql_query($q);
			$jumlah=$koneksi_db->sql_numrows($pilih);
			
			if ($jumlah > 0){
			$no=0;
				while($w = $koneksi_db->sql_fetchassoc($pilih)){
		
				$no++;
				$id = $w[0];
					echo '<tr >
						<td  align=center>'.$no.'</td> 
						<td align="center">	'.$w['hari'].'</td>
						<td align="center">	'.converttgl($w['tanggal']).'</td>
						<td align="center">	'.viewjam('', ''.$w['jam'].'').'</td>';
						
						
							$query  = $koneksi_db->sql_query ("SELECT * FROM r_kode where aplikasi = '59'  ");
							while( $r = $koneksi_db->sql_fetchassoc ($query)) {
							 $ck = ($r[2] ==  $w['jenis_presensi'] ) ? '1' : '';
							echo '<td  align=center>'.$ck.'</td>';
							}
	
						echo '</tr>'; 
				}
				echo ' <thead> 	<tr >
					<th  colspan="4" align=center>Total Presensi</th>
					
					<th  align=center>'.hitungpresensidosen($prodi, $tahun_id, '', 'A', $idd).'</th>
					<th  align=center>'.hitungpresensidosen($prodi, $tahun_id, '', 'I', $idd).'</th>
					<th  align=center>'.hitungpresensidosen($prodi, $tahun_id, '', 'S', $idd).'</th>
					<th  align=center>'.hitungpresensidosen($prodi, $tahun_id, '', 'H', $idd).'</th>
					</tr></thead>';
			} else {
				echo ' <thead> 	<tr ><th  colspan="8" align=center>Belum ada data</th>	</tr></thead>';
			}
		 
		 echo '</tbody>
			</table>';
echo '</div></div>';
		
} else {

echo "<div  class='error'>Program studi, Semester belum dipilih</div>";	

echo '<input type="button" class=tombols ui-corner-all value="Kembali" onClick="window.location = \'index.php?m=presensi.dosen\'"/>';

}



?>