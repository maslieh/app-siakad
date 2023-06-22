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


function hapus() {
echo "<div class=error>Ma'af.. Data tidak dapat dihapus, hanya bisa diedit</div>";
Daftar();
}


function Daftar() {
	global $koneksi_db, $user;
	$prodi = $_SESSION['prodi'];

		DaftarADMIN();
		

}




function DaftarADMIN() {
global $koneksi_db, $user;
if ($_SESSION['Level']!="MAHASISWA"	) {
//FilterSemester('transkrip');
	//FilterKelas($_GET['m']);
	FilterMahasiswa($_GET['m']);
	
	
	global $koneksi_db;
	$prodi = $_SESSION['prodi'];
	
	$whr = array();
	$ord = '';
	if (($_SESSION['reset_mahasiswa'] != 'Reset') &&
	!empty($_SESSION['kolom_mahasiswa']) && !empty($_SESSION['kunci_mahasiswa'])) {
	$whr[] = "$_SESSION[kolom_mahasiswa] like '%$_SESSION[kunci_mahasiswa]%' ";
	$ord = "order by $_SESSION[kolom_mahasiswa]";
	}
	$whr[] = "status_aktif='A'"; // L = LULUS
	if (!empty($_SESSION['prodi'])) $whr[] = "kode_prodi='$_SESSION[prodi]'";
	if (!empty($whr)) $strwhr = "where " .implode(' and ', $whr);
 } else {
$strwhr = "where idm='$user'";
} 


require('system/pagination_class.php');
$sql = "select * from m_mahasiswa  $strwhr $ord";
if(isset($_GET['starting'])){ //starting page
	$starting=$_GET['starting'];
}else{
	$starting=0;
}
$n = $starting;
$recpage = 20;//jumlah data yang ditampilkan per page(halaman)
$obj = new pagination_class($koneksi_db,$sql,$starting,$recpage);		
$result = $obj->result;
if($koneksi_db->sql_numrows($result)!=0){	
echo '<div class="table-responsive">
<table class="table table-striped table-bordered table-hover"  >
   	<thead>
     <tr>
	   <th width="5%" align="center">No</th>
	   <th align="center">NIM</th>
       <th align="center">Nama</th>
       <th align="center">Angkatan</th>
	   <th align="center" width="60">Aksi</th>
     </tr>
	 </thead>
	 <tbody>';
	 
	 
		while($wr = $koneksi_db->sql_fetchassoc($result)){
		$n++;
		$id = $wr['idm'];
		echo '<tr  >
				<td >'.$n.'</td> 
				<td  >'.$wr['NIM'].'</td>
				<td  >'.$wr['nama_mahasiswa'].'</td>
				<td  >'.$wr['tahun_masuk'].'</td>
				<td >
					<a class="btn" href="index.php?m='.$_GET['m'].'&op=detail&idm='.$id.'"><i class="fa fa-edit"></i></a>
					</td>
			</tr>'; 
		}
		echo '</tbody>
		</table></div>';
			echo $obj->total;
	echo '<br/>';
	echo $obj->anchors;
	} else {
		echo '<div class="alert alert-danger" >Belum ada Data</div>';
	}
	 


}

function detail() {

echo '<table width="100%"  border="0" cellspacing="1" cellpadding="1" ><tr><td align=left>';

global $koneksi_db, $programstudi;
    $idm = $_REQUEST['idm'];

	$w = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM m_mahasiswa where idm='".$idm."' and aktif='1' limit 1 " ));
	$qq = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM t_mahasiswa_krs where idm='".$idm."'" ));

	
				
$tanggal = converttgl(date('Y-m-d'));

echo'
		<table  border="0" cellspacing="1" class="datatable " cellpadding="1">
		<thead>
		  <tr>
			<td width="149">NIM</td>
			<td width="436"><b>'.$w['NIM'].'</b></td>
		  </tr>
		  <tr>
			<td>NAMA MAHASISWA</td>
			<td><b >'.$w['nama_mahasiswa'].'</b></td>
		  </tr>

		  <tr>
			<td>PROGRAM STUDI </td>
			<td><b ><strong>'.strtoupper(viewAplikasi('04', ''.$w['kode_jenjang'].'')).' '.strtoupper(viewprodi(''.$w['kode_prodi'].'')).'</strong></b ></td>
		  </tr>
		  <tr>
			<td>KONSENTRASI</td>
			<td><b >'.viewkonsentrasi(''.$w['kode_konsentrasi'].'').'</b ></td>
		  </tr>

		  </thead>
		</table> </br>';
		

echo '<br/><a class="btn" href="index.php?m='.$_GET['m'].'&op=add&idm='.$idm.'" class="button-red">Tambah Nilai Akhir Mahasiswa</a><br/>';

echo '</br> </br>
<table class="table table-striped table-bordered table-hover"  >
   	<thead>
     <tr>
	   <th width="5%" align="center">No</th>
	   <th align="center">Kode MK</th>
       <th align="center">Mata Kuliah</th>
       <th align="center">SKS</th>
	   <th align="center">Nilai Huruf</th>
	   <th align="center"   >Aksi</th>
     </tr>
	 </thead>
	 <tbody>';

						
					$qkrs = "select  idkrs, validasi, kode_mk, nama_mk, k.sks, 
					        k.nilai, k.sks, k.bobot from t_mahasiswa_krs k 
							left outer join m_mata_kuliah m on k.id=m.id
							where k.idm='$qq[idm]'  and k.verifi_pa='1' 
							ORDER BY m.nama_mk";
				
					
					$pkrs = $koneksi_db->sql_query($qkrs);
					$jkrs= $koneksi_db->sql_numrows($pkrs);
					$no=0;
					if ($jkrs > 0){
					// perulanagn makul
						while($mk = $koneksi_db->sql_fetchassoc($pkrs)){
						$no++;
						$idkrs=$mk['idkrs'];
						echo'
							<td   > <font  '; if ($mk['validasi']==0) echo'color="red"'; echo '>  '.$no.' </font></td>  
							<td  >  <font  '; if ($mk['validasi']==0) echo'color="red"'; echo '> '.$mk['kode_mk'].'</font></td>
							<td>  <font  '; if ($mk['validasi']==0) echo'color="red"'; echo '>'.$mk['nama_mk'].'</font></td><td  align="center" > <font  '; if ($mk['validasi']==0) echo'color="red"'; echo '> '.$mk['sks'].'</font></td>
									<td  >  <font  '; if ($mk['validasi']==0) echo'color="red"'; echo '>'.$mk['nilai'].'</font></td>
									<td   >
									<a class="btn"  href="index.php?m='.$_GET['m'].'&op=ubah&idkrs='.$idkrs.'&idm='.$idm.'">
									<i class="fa fa-edit"></i></a>
									 
									<a class="btn"  href="index.php?m='.$_GET['m'].'&op=unvalid&idkrs='.$idkrs.'&idm='.$idm.'">
									<i class="fa fa-trash-o"></i></a>
									 
									<a class="btn"  href="index.php?m='.$_GET['m'].'&op=valid&idkrs='.$idkrs.'&idm='.$idm.'">
									<i class="fa fa-check"></i></a>
									</td>
									</tr>';
							
							
									
											
							
							
						}
						
					}
					
				$total = $M + $total - $M;	
					


echo '
</table>';



echo '</td></tr></table> </br>';
echo '<input type="button" class=tombols ui-corner-all value="Kembali" onClick="window.location = \'index.php?m='.$_GET['m'].'\'"/>';
}

function add(){
global $koneksi_db, $prodi;
$idm = $_REQUEST['idm'];
$prodi = $_SESSION['prodi'];
$kode_mk = $_SESSION['kode_mk'];

	if (empty($idm) || !isset($idm)) { echo "<meta http-equiv='refresh' content='3; url=index.php?m=transkrip'>"; } 
	$w = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM m_mahasiswa where idm='".$idm."' and aktif='1' limit 1 " ));
	
echo'
		<table  border="0" cellspacing="1" class="datatable " cellpadding="1">
		<thead>
		  <tr>
			<td width="149">NIM</td>
			<td width="436"><b>'.$w['NIM'].'</b></td>
		  </tr>
		  <tr>
			<td>NAMA MAHASISWA</td>
			<td><b >'.$w['nama_mahasiswa'].'</b></td>
		  </tr>

		  <tr>
			<td>PROGRAM STUDI </td>
			<td><b ><strong>'.strtoupper(viewAplikasi('04', ''.$w['kode_jenjang'].'')).' '.strtoupper(viewprodi(''.$w['kode_prodi'].'')).'</strong></b ></td>
		  </tr>
		  <tr>
			<td>KONSENTRASI</td>
			<td><b >'.viewkonsentrasi(''.$w['kode_konsentrasi'].'').'</b ></td>
		  </tr>

		  </thead>
		</table> </br>';
	
FilterMataKuliah($prodi, '', $_GET['m']);
$idm = $_REQUEST['idm'];
echo '
	<form action="" method="post"  class="cmxform" id="form_input" name="form_input" style="width:100%">
				<input type="hidden" name="id" value="'.$idm.'"/>
				<input type="hidden" name="m" value="'.$_GET['m'].'"/>
				<input type="hidden" name="op" value="Simpan"/>
	</br>
	<table class=box cellspacing=1 cellpadding=4  >
  <tr>
	  <td width=100>Nilai Huruf</td>
	  <td> 
		<input name="nilai"  type="text" class="" id="" value="" />
	</td>

	</tr>
	</table>
	</br>
	<input type="submit" name="simpan" class=tombols ui-corner-all value="SIMPAN"/>
	<input type="button" class=tombols ui-corner-all value="Kembali" onClick="window.location = \'index.php?m='.$_GET['m'].'&op=detail&idm='.$idm.'\'"/>
	</form>
';
}

function ubah(){
global $koneksi_db, $prodi;
$idm = $_REQUEST['idm'];
$prodi = $_SESSION['prodi'];
$kode_mk = $_SESSION['kode_mk'];
$idkrs = $_REQUEST['idkrs'];

	if (empty($idm) || !isset($idm)) { echo "<meta http-equiv='refresh' content='3; url=index.php?m=transkrip'>"; } 
	$w = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM m_mahasiswa where idm='".$idm."' and aktif='1' limit 1 " ));
	
echo'
		<table  border="0" cellspacing="1" class="datatable " cellpadding="1">
		<thead>
		  <tr>
			<td width="149">NIM</td>
			<td width="436"><b>'.$w['NIM'].'</b></td>
		  </tr>
		  <tr>
			<td>NAMA MAHASISWA</td>
			<td><b >'.$w['nama_mahasiswa'].'</b></td>
		  </tr>

		  <tr>
			<td>PROGRAM STUDI </td>
			<td><b ><strong>'.strtoupper(viewAplikasi('04', ''.$w['kode_jenjang'].'')).' '.strtoupper(viewprodi(''.$w['kode_prodi'].'')).'</strong></b ></td>
		  </tr>
		  <tr>
			<td>KONSENTRASI</td>
			<td><b >'.viewkonsentrasi(''.$w['kode_konsentrasi'].'').'</b ></td>
		  </tr>

		  </thead>
		</table> </br>';
	
$xyz = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT a.*, b.* FROM t_mahasiswa_krs a inner join m_mata_kuliah b on a.id=b.id where idkrs='".$idkrs."' limit 1 " ));

echo '
	<form action="" method="post"  class="cmxform" id="form_input" name="form_input" style="width:100%">
				<input type="hidden" name="id" value="'.$idm.'"/>
				<input type="hidden" name="m" value="'.$_GET['m'].'"/>
				<input type="hidden" name="kode_mk" value="'.$xyz['id'].'"/>
				<input type="hidden" name="op" value="Update"/>
	</br>
	<table class=box cellspacing=1 cellpadding=4  >
  <tr>
	  <td width=150>Mata Kuliah</td>
	  <td> 
		<input name="idmatkul"  type="text" class="" id=""  value="'.$xyz['kode_mk'].'--'.$xyz['nama_mk'].'" readonly />
	</td>

	</tr>
  
  <tr>
	  <td width=100>Nilai Huruf</td>
	  <td> 
		<input name="nilai"  type="text" class="" id="" value="'.$xyz['nilai'].'" />
	</td>

	</tr>
	</table>
	</br>
	<input type="submit" name="Update" class=tombols ui-corner-all value="SIMPAN"/>
	<input type="button" class=tombols ui-corner-all value="Kembali" onClick="window.location = \'index.php?m='.$_GET['m'].'&op=detail&idm='.$idm.'\'"/>
	</form>
';
}

function unvalid(){
global $koneksi_db, $prodi;
$idm = $_REQUEST['idm'];
$idkrs = $_REQUEST['idkrs'];

	$update = $koneksi_db->sql_query("UPDATE `t_mahasiswa_krs` SET  validasi=0  WHERE `idkrs` = '$idkrs'");
	
	echo "<meta http-equiv='refresh' content='2; url=index.php?m=".$_GET['m']."&op=detail&idm=".$idm."'>";
	
}

function valid(){
global $koneksi_db, $prodi;
$idm = $_REQUEST['idm'];
$idkrs = $_REQUEST['idkrs'];

	$update = $koneksi_db->sql_query("UPDATE `t_mahasiswa_krs` SET  validasi=1  WHERE `idkrs` = '$idkrs'");
	
	echo "<meta http-equiv='refresh' content='2; url=index.php?m=".$_GET['m']."&op=detail&idm=".$idm."'>";
	
}

function Update(){
global $koneksi_db, $prodi;
$kode_mk = $_REQUEST['kode_mk'];
$idm = $_REQUEST['idm'];
$idkrs = $_GET['idkrs'];

$nilai = $_POST['nilai'];

$mk = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query("SELECT * FROM m_mata_kuliah where id = '$kode_mk' and kode_prodi='$prodi'" ));

			  
$nl = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM m_nilai
				where kode_prodi='$prodi' and nilai='$nilai' limit 1" ));
	
				$ip = $mk['sks_mk'] * $nl['bobot'];
				

$update = $koneksi_db->sql_query("UPDATE `t_mahasiswa_krs` SET  `nilai`='$nilai', `bobot`='$nl[bobot]', `ip`='$ip'  WHERE `idkrs` = '$idkrs'");

echo "<meta http-equiv='refresh' content='2; url=index.php?m=".$_GET['m']."&op=detail&idm=".$idm."'>";
}

function Simpan(){
global $koneksi_db, $prodi,  $kode_mk;
$idm = $_REQUEST['idm'];

if (!empty($_POST['nilai'])) {
	$nilai = $_POST['nilai'];

$mk = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query("SELECT * FROM `m_mata_kuliah` where `id` = '$kode_mk' and kode_prodi='$prodi'" ));

			  
$nl = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM m_nilai
				where kode_prodi='$prodi' and nilai='$nilai' limit 1" ));
	
				$ip = $mk['sks_mk'] * $nl['bobot'];
				


$so = "insert into t_mahasiswa_krs SET 
								kode_pt='".$mk['kode_pt']."',
								kode_fak='".$mk['kode_fak']."',
								kode_jenjang='".$mk['kode_jenjang']."',
								kode_prodi='".$mk['kode_prodi']."',						
								idm='".$idm."',
								id='".$kode_mk."',
								sks='".$mk['sks_mk']."',	
								nilai='".$nilai."',
								bobot='".$nl['bobot']."',
								ip='".$ip."',
								lulus='".$nl['lulus']."',
								verifi_pa=1,
								validasi=1
								";
								$koneksi_db->sql_query($so);

}

echo "<meta http-equiv='refresh' content='2; url=index.php?m=".$_GET['m']."&op=detail&idm=".$idm."'>";
}


$semester = BuatSesi('semester');
$kelas = BuatSesi('kelas');
$kolom_mahasiswa = BuatSesi('kolom_mahasiswa');
$kunci_mahasiswa = BuatSesi('kunci_mahasiswa');

if ($_REQUEST['reset_mahasiswa'] == 'Reset') {
  $_SESSION['kolom_mahasiswa'] = '';
  $_SESSION['kunci_mahasiswa'] = '';
}

$kode_mk = BuatSesi('kode_mk');

$go = (empty($_REQUEST['op'])) ? 'Daftar' : $_REQUEST['op']; 

echo '<div id="title" align="right">
        <font style="color:#FF9900; font-size:30px;"><strong>.:</strong></font>
        <font style="font-size:18px; color:#999999">Pendataan Nilai Akhir</font><br />
        	<a href="index.php">Home</a> &raquo; <a href="index.php?m='.$_GET['m'].'">Nilai Akhir</a>  &raquo; '.$go.'  
    </div>';
echo '<div class="mainContentCell"><div class="content">';
$go();
echo '</div></div>';


?>