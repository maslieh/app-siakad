<?php
if(ereg(basename (__FILE__), $_SERVER['PHP_SELF']))
{
	header("HTTP/1.1 404 Not Found");
	exit;
}
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
global $koneksi_db;
$prodi = $_SESSION['prodi'];

FilterMahasiswa($_GET['m']);

  $whr = array();
  $ord = '';
  if (($_SESSION['reset_mahasiswa'] != 'Reset') &&
  !empty($_SESSION['kolom_mahasiswa']) && !empty($_SESSION['kunci_mahasiswa'])) {
    $whr[] = "$_SESSION[kolom_mahasiswa] like '%$_SESSION[kunci_mahasiswa]%' ";
    $ord = "order by $_SESSION[kolom_mahasiswa]";
	//$ord = ($_SESSION['kolom_mahasiswa'] =="" ) ? "NIM": $_SESSION['kolom_mahasiswa'];
	
  }
  	
  	if (!empty($_SESSION['prodi'])) $whr[] = "kode_prodi='$_SESSION[prodi]'";
	$whr[] = "status_masuk='P'";
	if (!empty($whr)) $strwhr = "where " .implode(' and ', $whr);
  
echo '<table class="table table-striped table-bordered table-hover"  >
   	<thead>
     <tr>
	   <th width="5%" align="center">No</th>
	   <th align="center">NIM</th>
       <th align="center">Nama</th>
       <th align="center">SKS Diakui</th>
	   <th align="center">Status</th>
	   <th align="center"></th>
     </tr>
	 </thead>
	 <tbody>';
	 
	include "system/pag/pag.php"; 
	$hal = new JinPagination;
	// setup paginasi
	$hal->setOption("tabel", "m_mahasiswa"); // nama tabel database
	$hal->setOption("where", "$strwhr"); // where kondisi, kosongkan jika tidak memakai WHERE
	$hal->setOption("limit", "10"); // LIMIT tampilan per halaman
	$hal->setOption("order", "$ord"); // urutan, kosongkan jika tidak memakai urutan
	$hal->setOption("page", $_REQUEST["hal"]); // setup untuk ambil variable angka halaman (berguna jika menggunakan SEO url, ubah sesuai dgn kebutuhan)
	$hal->setOption("web_url_page", "index.php?m=".$_GET['m']."&hal="); // setup alamat url (berguna jika menggunakan SEO url, ubah sesuai dgn kebutuhan)
	// optional setup
	$hal->setOption("adjacents", "3"); // tampil berapa angka ke kanan dan ke kiri nya, jika kita diposisi tengah halaman
	$hal->setOption("txt_prev", "&laquo; sebelumnya"); // mengubah text "prev" menjadi "sebelumnya"
	$hal->setOption("txt_next", "berikutnya &raquo;"); // mengubah text "next" menjadi "berikutnya"
	// generate hasil pagination
	$hal_array = $hal->build();
	// setup penomoran
	$no = $hal_array["start"] + 1;
	$jumlah=$koneksi_db->sql_numrows($hal_array["hasil"]);
	if ($jumlah > 0){
		while($wr = $koneksi_db->sql_fetchrow($hal_array["hasil"])){
		$n++;
		$id = $wr['idm'];
		echo '<tr bgcolor="#f2f2f2">
				<td  align=center>'.$n.'</td> 
				<td  align=center>'.$wr['NIM'].'</a></td>
				<td  align=left>'.$wr['nama_mahasiswa'].'</td>
				<td  align=center>'.$wr['sks_diakui'].'</td>
				<td  align=center>'.viewAplikasi('05',''.$wr['status_aktif'].'').'</td>
				<td  align=center>
					<a href="#" onclick="window.location.href=\'index.php?m='.$_GET['m'].'&op=input&idm='.$id.'\';"><img src="images/update.png"/></a>
				</td>
			</tr>'; 
		}
	} else {
		 echo '<tr > 
			<th  colspan="6" align=center>Belum ada Data</th>
			</tr>';
	}
	 echo '</tbody>
		</table>';

echo "Total: " . $hal_array["total"]; // tampilkan total data
echo $hal_array["pagination"]; // tampilkan pagination dibawah


}


function Input() {
	//echo '<div class="error" style="width:70%">BATAS PENGAMBILAN KRS BELUM DIMULAI ATAU SUDAH DITUTUP</div>';
//} else {

global $koneksi_db;

$prodi = $_SESSION['prodi'];
$krs_semester = $_SESSION['krs_semester'];
    $idm = $_REQUEST['idm'];
	if (empty($idm) || !isset($idm)) { echo "<meta http-equiv='refresh' content='3; url=index.php?m=".$_GET['m']."'>"; } 
	$w = $koneksi_db->sql_fetchrow($koneksi_db->sql_query( "SELECT * FROM m_mahasiswa where idm='".$idm."' limit 1 " ));
	$foto= ($w['foto'] =="" ) ? "images/no_avatar.gif": "images/avatar/".$w['foto']."";
echo '
		<table  border="0" cellspacing="0" class="datatable " cellpadding="0">
		<thead>
		  <tr>
			<td width="149">NIM</td>
			<td width="436"><b>'.$w['NIM'].'</b></td>
			<td width="37" valign="top" rowspan="5"><img src="'.$foto.'" width="80" height="80"></td>
		  </tr>
		  <tr>
			<td>NAMA</td>
			<td><b >'.$w['nama_mahasiswa'].'</b></td>
		  </tr>
		  <tr>
			<td>JURUSAN</td>
			<td><b >'.viewkonsentrasi(''.$w['kode_konsentrasi'].'').'</b ></td>
		  </tr>
		  <tr>
			<td>PROGRAM STUDI </td>
			<td><b >'.viewprodi(''.$w['kode_prodi'].'').'</b ></td>
		  </tr>
		  <tr>
			<td>SKS YG DIAKUI </td>
			<td><b >'.$w['sks_diakui'].'</b ></td>
		  </tr>
		  </thead>
		</table> <br/>';
		
FilterPaketDiakui($_GET['m']);

	if (!empty($krs_semester)) {

	echo '<br/>
	 <form action="" method="post"  class="" id="form_input" name="form_input" style="width:100%">
			<input type="hidden" name="idm" value="'.$idm.'"/>
			<input type="hidden" name="m" value="'.$_GET['m'].'"/>
			<input type="hidden" name="op" value="update"/>
	<table width="100%" border="0" align="center" cellpadding="1" cellspacing="0" class="datatable full" >
		<thead>	
			<tr>
				<th width="30" rowspan="2" valign="middle">
					<a  href="javascript:checkall(\'form_input\', \'ambil[]\'); hitungtotal()">ALL</a></th>
				<th width="30" rowspan="2" valign="middle">Kode</th>
				<th width="200" rowspan="2" valign="middle">Mata Kuliah</th>
				<th width="50" rowspan="2" valign="middle">SMTR</th>
				<th width="200" rowspan="2" valign="middle">Dosen</th>
				<th width="50">SKS</td>
				<th width="50">Jenis MK</td>
				<th >Nilai</td>
			</tr>
		</thead>
		 <tbody>';
		 /*
		 $s = "select  m.*, d.* from m_mata_kuliah m 
								left outer join m_dosen d on m.idd=d.idd
								where m.kode_prodi='$prodi' and m.semester='$krs_semester'";
		*/
		$s = "select  * from m_mata_kuliah  where kode_prodi='$prodi' and semester='$krs_semester'";						
		$s_sks = $koneksi_db->sql_query($s);
		$jumlah=$koneksi_db->sql_numrows($s_sks);
		if ($jumlah > 0){
			while($k = $koneksi_db->sql_fetchrow($s_sks)){
			$q = "select  * from t_mahasiswa_krs where idm='$idm' and kode_mk='$k[id]' ";
			if ( $koneksi_db->sql_numrows( $koneksi_db->sql_query( $q ))  < 1 ) {
				$dsnx = $koneksi_db->sql_fetchrow($koneksi_db->sql_query( "SELECT * FROM t_dosen_pengajaran where kelas='$kelas' and kode_mk='$k[id]'  limit 1 " ));
				$n++;
				$kode[] = $k['id'];
				$kodemk[] = $k['kode_mk'];
				$nama[] = $k['nama_mk'];
				$semester[] = $k['semester'];
				//$dosen[] =$k['gelar_depan'].' '.$k['nama_dosen'].', '.$k['gelar_belakang'];
				$dosen[] =viewdosen($dsnx['idd']);
				$sks[] = $k['sks_mk'];
				$skst[] = $k['sks_teori'];
				$sksp[] = $k['sks_praktek'];
				$sksl[] = $k['sks_lapangan'];
				$jenis[] = viewAplikasi('28',''.$k['kode_jenis'].'');
				$nilai[] = opnilai($prodi, $k[nilai]);
			}
			}
			//menampilkan matakuliah ke dalam tabel
			for($i=0;$i<count($kode);$i++){
			echo '<tr >
					<td id=k1'.$i.' align=center><input type="checkbox" onclick=hitungtotal() name="ambil[]" value="'.$kode[$i].'" id=ambil'.$i.' ></td> 
					<td id=k2'.$i.' valign="top" align="center">'.$kodemk[$i].'</td>
                    <td id=k3'.$i.' valign="top ">'.$nama[$i].'</td>
					<td id=k4'.$i.' valign="top" align="center">'.$semester[$i].'</td>
					<td id=k5'.$i.' valign="top" >'.$dosen[$i].'</td>
					<td id=k6'.$i.' valign="top" align=center>'.$sks[$i].'</td>
					<td id=k7'.$i.' valign="top" align=center>'.$jenis[$i].'</td>
					<td id=k8'.$i.' valign="top" align=center><select  name="nilai[]" >'.$nilai[$i].'</select></td>
				</tr>'; 
			}
			echo '<thead><tr > <th  colspan="8" align=right>Jumlah SKS Yang Dipilih : 	<span id=jsks></span> SKS</th>	</tr>';
			echo '
			<tr > 
				<th  colspan="8" align=left>
				<input type="hidden" name="idm" value="'.$idm.'"/>
				<input type="submit" class=tombols ui-corner-all value="Proses"/>
				</th>
				</tr></thead>';

		} else {
			 echo '
			 <thead>
			 	<tr > 
				<th  colspan="8" align=center>Belum ada Paket KRS</th>
				</tr>
			</thead>';
		}
		 echo '</tbody>
			</table></form>';
	}
	
	include "system/fungsi_krsmassal.php"; 

}

function update() {
global $koneksi_db, $prodi, $tahun_id;
$sukses_import = 0;
$sudah_import = 0;
		
	if (is_array($_POST['ambil'])) {
		$idm = $_POST['idm'];
		$w = $koneksi_db->sql_fetchrow($koneksi_db->sql_query( "SELECT * FROM m_mahasiswa where idm='".$idm."' limit 1 " ));
		foreach($_POST['ambil'] as $key=>$val) {
		$nilai = $_POST['nilai'][$key];
		if (!empty($nilai)) {
		
			$mk = $koneksi_db->sql_fetchrow($koneksi_db->sql_query("SELECT * FROM `m_mata_kuliah` where `id` = '$val' " ));
			$nl = $koneksi_db->sql_fetchrow($koneksi_db->sql_query( "SELECT * FROM m_nilai
					where kode_prodi='$w[kode_prodi]' and `nilai` = '$nilai' limit 1" ));
			$bobot = $nl['bobot'];
			$ip = $nl['bobot'] * $mk['sks_mk'];
			$lulus = $nl['lulus'];
			  //////////////////////
			 
			 $ada = $koneksi_db->sql_numrows($koneksi_db->sql_query( "SELECT * FROM `t_mahasiswa_krs` 
					where kode_prodi='$mk[kode_prodi]' 
					and kode_konsentrasi='$mk[kode_konsentrasi]' 
					and idm='$idm' 
					and kode_mk='$val'
					" ));
				if ($ada < 1){
					$so = "insert into t_mahasiswa_krs SET 
					kode_pt='".$mk['kode_pt']."',
					kode_fak='".$mk['kode_fak']."',
					kode_jenjang='".$mk['kode_jenjang']."',
					kode_konsentrasi='".$mk['kode_konsentrasi']."',
					kode_prodi='".$mk['kode_prodi']."',
					tahun_id='".$tahun_id."',
					semester='".$mk['semester']."',	
					kelas='".$w['masuk_kelas']."',				
					idm='".$idm."',
					kode_mk='".$mk['id']."',
					sks='".$mk['sks_mk']."',
					nilai='".$nilai."',
					bobot = '".$bobot."',
					ip = '".$ip."',
					lulus ='".$lulus."'
					";
					$koneksi_db->sql_query($so);
					$sukses_import++;
					$ket_sukses .= "<tr><td>".$mk['kode_mk']."</td><td>".$mk['nama_mk']."</td><td width=100>SKS : ".$mk['sks_mk']."</td>
					<td colspan=3>Nilai : ".$nilai." - Bobot Nilai : ".$bobot." - IP : ".$ip." - Lulus : ".$lulus."</td></tr>
					";
					
				} else {
					$sudah_import++;
					$ket_ada .= "<tr><td>".$mk['nama_mk']."</td><td width=100>".$w['NIM']."</td><td>".$w['nama_mahasiswa']."</td></tr>";
				}
			}
				///////////////////////
		}
	}
//echo "<div  class='sukses'>Proses Menyimpan Data...</div>";		  
//echo "<meta http-equiv='refresh' content='3; url=index.php?m=krs.terima'>";
echo "<div id=fram>
<table width=100% border=0>
	<tr><td align=left><h1>Jumlah Mata Kuliah sukses diambil</h1></td><td align=left><h1> ".$sukses_import."</h1></td></tr>
	<tr><td colspan=2 align=left><table class=rapor width=100%> ".$ket_sukses."</table></td></tr>
	<tr><td align=left><h1>Jumlah Mata Kuliah yang sudah ada </h1></td><td align=left><h1> ".$sudah_import."</h1></td></tr>
	<tr><td colspan=2 align=left><table class=rapor width=100%> ".$ket_ada."</table></td></tr>
</table></div><br/>";

echo '<input type="button" class=tombols ui-corner-all value="Kembali" onClick="window.location = \'index.php?m='.$_GET['m'].'\'"/>';

}

$krs_semester = BuatSesi('krs_semester');
$kolom_mahasiswa = BuatSesi('kolom_mahasiswa');
$kunci_mahasiswa = BuatSesi('kunci_mahasiswa');

if ($_REQUEST['reset_mahasiswa'] == 'Reset') {
  $_SESSION['kolom_mahasiswa'] = '';
  $_SESSION['kunci_mahasiswa'] = '';
}

$go = (empty($_REQUEST['op'])) ? 'Daftar' : $_REQUEST['op']; 

echo '<div id="title" align="right">
        <font style="color:#FF9900; font-size:30px;"><strong>.:</strong></font>
        <font style="font-size:18px; color:#999999">Input Makul Mahasiswa Pindahan</font><br />
        	<a href="?index.php">Home</a> &raquo; <a href="index.php?m='.$_GET['m'].'">Makul Mahasiswa Pindahan</a>  &raquo; '.$go.'  
    </div>';
echo '<div class="mainContentCell"><div class="content">';
$go();
echo '</div></div>';

?>

