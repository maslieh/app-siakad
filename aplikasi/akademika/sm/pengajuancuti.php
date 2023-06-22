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



function Daftar() {
global $koneksi_db, $user;
	///// opsi mahasiswa dan bukan mahasiswa
	if ($_SESSION['Level']!="MAHASISWA"	) {
	  FilterMahasiswa($_GET['m']);
	  $whr = array();
	  $ord = '';
	  if (($_SESSION['reset_mahasiswa'] == 'Reset') &&
	  empty($_SESSION['kolom_mahasiswa']) && empty($_SESSION['kunci_mahasiswa'])) {
	  echo "";
	  } else {
		$whr[] = "$_SESSION[kolom_mahasiswa] like '%$_SESSION[kunci_mahasiswa]%' ";
		$ord = "order by $_SESSION[kolom_mahasiswa]";
	  }
		$whr[] = "status_aktif='A'";
		if (!empty($whr)) $strwhr = "where " .implode(' and ', $whr);
		
		$ambilmhs= $koneksi_db->sql_query( "SELECT * FROM m_mahasiswa $strwhr  limit 1 " );

	} else {
		$ambilmhs = $koneksi_db->sql_query( "SELECT * FROM m_mahasiswa where idm='$user'  limit 1 " );
	}
	
	if ( $koneksi_db->sql_numrows( $ambilmhs ) > 0) {
		$wm = $koneksi_db->sql_fetchassoc( $ambilmhs );
		$status = $wm['status_aktif'];
		$idm = $wm['idm'];
		$fotonya = ($wm['foto'] =="" ) ? "gambar/no_avatar.gif": "gambar/".$wm['foto']."";
		echo '
		<table  border="0" cellspacing="1"class="datatable full" cellpadding="1">
		<thead>
		  <tr>
			<td width="149">NIM</td>
			<td width="436"><b>'.$wm['NIM'].'</b></td>
			<td width="37" valign="top" rowspan="5"><img src="'.$fotonya.'" width="80" height="80"></td>
		  </tr>
		  <tr>
			<td>NAMA</td>
			<td><b >'.$wm['nama_mahasiswa'].'</b></td>
		  </tr>
		  <tr>
			<td>KONSENTRASI</td>
			<td><b >'.viewkonsentrasi(''.$wm['kode_konsentrasi'].'').'</b ></td>
		  </tr>
		  <tr>
			<td>PROGRAM STUDI </td>
			<td><b >'.viewprodi(''.$wm['kode_prodi'].'').'</b ></td>
		  </tr>
		  </thead>
		</table>
	  ';
	  ///// end opsi mahasiswa dan bukan mahasiswa
		$w = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM t_mahasiswa_cuti where idm='$idm' limit 1 " ));
		$statuscuti=$w['status_cuti'];
		if ($status !="A") {
			echo '<div class="alert alert-danger>Maaf Anda tidak dapat mengajukan Cuti, Status Anda masih '.viewAplikasi('05', ''.$status.'').'</div>';
			
		} else if ($statuscuti ==11){
			echo '<h2><font color=blue>Proses Pengajuan Cuti sudah terkirim, tinggal menunggu konfirmasi dari BAAK</font></h2>';
		} else {
		
		echo '  
			<form action="" method="post"  class="cmxform" id="form_input" style="width:100%">
				<input type="hidden" name="id" value="'.$w['idcm'].'"/>
				<input type="hidden" name="idm" value="'.$wm['idm'].'"/>
				<input type="hidden" name="m" value="'.$_GET['m'].'"/>
				<input type="hidden" name="op" value="simpan"/>
				<input type="hidden" name="md" value="'.$md.'"/>
			   
				<fieldset class="ui-widget ui-widget-content ui-corner-all" >
					<legend class="ui-widget ui-widget-header ui-corner-all">Pengajuan Cuti</legend>
					<table width="600"  border="0">
						<tr>
							<h3>Anda Yakin Ingin Mengajukan Cuti ??</h3>
						</tr>
						<tr>
						<br>
						</tr>
						
						<tr><td colspan=2>
							<input type="submit" class=tombols ui-corner-all value="Simpan"/> 
							<input type="button" class=tombols ui-corner-all value="Kembali" onClick="window.location = \'index.php?m='.$_GET['m'].'\'"/>
						</td></tr>
						</table>
				</fieldset>
			</form>
		 ';
		 }
		
		
	}
}


function simpan() {
global $koneksi_db, $user, $tahun_id;
$id = $_REQUEST['id'];  
$idm = $_REQUEST['idm']; 
$w = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM m_mahasiswa where idm='$idm' limit 1 " ));

	if (!empty($id) ) {
	$s = "update t_mahasiswa_cuti set 
			tanggal_mulai='".$_REQUEST['tanggal_mulai']."',
			tanggal_akhir='".$_REQUEST['tanggal_akhir']."'
			where idcm='".$_REQUEST['id']."' ";
	$koneksi_db->sql_query($s);
	
	} else {
	  $s = "INSERT INTO t_mahasiswa_cuti set 
			kode_pt='".$w['kode_pt']."',
			kode_fak='".$w['kode_fak']."',
			kode_jenjang='".$w['kode_jenjang']."',
			kode_konsentrasi='".$w['kode_konsentrasi']."',
			kode_prodi='".$w['kode_prodi']."',
			tahun_id='".$tahun_id."',
			idm='".$_REQUEST['idm']."',
			status_cuti='11'
			";
			
	  $koneksi_db->sql_query($s);
	 echo '<div class="sukses"><b>Proses Pengajuan Cuti</b></div><br />';
	echo "<meta http-equiv='refresh' content='3; url=index.php?m=".$_GET['m']."'>";
	}
//Menunggu();
//echo $s;
}
$kolom_mahasiswa = BuatSesi('kolom_mahasiswa');
$kunci_mahasiswa = BuatSesi('kunci_mahasiswa');

if ($_REQUEST['reset_mahasiswa'] == 'Reset') {
  $_SESSION['kolom_mahasiswa'] = '';
  $_SESSION['kunci_mahasiswa'] = '';
}

$go = (empty($_REQUEST['op'])) ? 'Daftar' : $_REQUEST['op']; 

echo '<div id="title" align="right">
        <font style="color:#FF9900; font-size:30px;"><strong>.:</strong></font>
        <font style="font-size:18px; color:#999999">Pengajuan Cuti Mahasiswa</font><br />
        	<a href="index.php">Home</a> &raquo; <a href="index.php?m='.$_GET['m'].'">Pengajuan Cuti</a>  &raquo; '.$go.'  
    </div>';
echo '<div class="mainContentCell"><div class="content">';
	$go();
echo '</div></div>';

?>
