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

//FilterSemester($_GET['m']);
//FilterKelas($_GET['m']);
#FilterMahasiswa($_GET['m']);
echo '<div class="row"><div class="col-md-4 pull-right">';
FilterAngkatan($_GET['m']);

echo '</div></div>';

global $koneksi_db, $jenjangprodi, $tahun_id,  $badanhukum, $perguruantinggi, $programstudi;
$prodi = $_SESSION['prodi'];
$tahun_id = $_SESSION['tahun_id'];
$tahun = date('Y');

$niijazah = $badanhukum['kode_badan_hukum'].'/'.$perguruantinggi['kode_pt'].'/'.$programstudi['kode_prodi'].'/'.$tahun.'/'; 
/*
$smtr = round($_SESSION['semester'] / 2,0);
echo  $smtr;
*/
  $whr = array();
  $ord = '';
  if (($_SESSION['reset_mahasiswa'] != 'Reset') &&
  !empty($_SESSION['kolom_mahasiswa']) && !empty($_SESSION['kunci_mahasiswa'])) {
    //$whr[] = "$_SESSION[kolom_mahasiswa] like '%$_SESSION[kunci_mahasiswa]%' ";
    $ord = "order by $_SESSION[kolom_mahasiswa]";
  }
  	$whr[] = "status_aktif='A'";
  	$whr[] = "kode_prodi='$_SESSION[prodi]'";
	$whr[] = "tahun_masuk='$_SESSION[angkatan]'";
	//if (!empty($_SESSION['kelas'])) $whr[] = "masuk_kelas='$_SESSION[kelas]'";
	//$whr[] = "semester='$_SESSION[semester]'";
	if (!empty($whr)) $strwhr = "where " .implode(' and ', $whr);
  
  
 	$sql = "select * from m_mahasiswa $strwhr";
	//echo $sql;
	$q = $koneksi_db->sql_query( $sql );
	
	$jumlah=$koneksi_db->sql_numrows($q);
	
	//echo $jumlah;
	if ($jumlah > 0){
	  
echo '
<form action="" method="post"  class="cmxform" id="form_input" style="width:100%">
         <input type="hidden" name="m" value="'.$_GET['m'].'"/>
        <input type="hidden" name="op" value="Update"/>
		
<table width="100%" border="0" align="center" cellpadding="1" cellspacing="0" class="datatable full1"  >
<tr>
	<td >No. Ijazah</td>
	<td><input name="no_ijazah"  type="text" class="full required"  value="'.$niijazah.'" /></td>
	<td >Tgl. Ijazah</td>
	<td><input name="tanggal_lulus"  type="text" class="tcal date required"  value="" /></td></tr>
<tr>
	<td >No. SK Yudisium</td>
	<td><input name="no_sk_yudisium"  type="text" class="full required"  value="'.$niijazah.'" /></td>
	<td >Tgl. SK Yudisium</td>
	<td><input name="tgl_sk_yudisium"  type="text" class="tcal date required"  value="" /></td></tr>
</table>
<br />

<table class="table full" id="dataTables-example">
   	<thead>
     <tr>
	   <th width="5%" align="center"><a href="javascript:checkall(\'form_input\', \'lulus[]\');">ALL</a></th>
	   <th align="center">NIM</th>
       <th align="center">Nama</th>
       <th align="center">Angkatan</th>

	   <th align="center">SKS</th>
	   <th align="center">IPK</th>
     </tr>
	 </thead>
	 <tbody>';
	 	$prd = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT sks_lulus FROM m_program_studi where kode_prodi='$_SESSION[prodi]' limit 1 " ));
		$sks_lulus = $prd['sks_lulus'];
	 
		while($wr = $koneksi_db->sql_fetchassoc($q)){
		$n++;
		$id = $wr['idm'];
		
		 
		$jumlah_mk = jumlah_mk($wr['kode_prodi'], '', '', $wr['idm'] );
		$jumlah_sks = jumlah_sks($wr['kode_prodi'], '', '', $wr['idm'] );
		$jumlah_ip = jumlah_ip($wr['kode_prodi'], '', '', $wr['idm'] );
		if (!empty($jumlah_ip) && !empty($jumlah_sks)) { $kumulatif = round($jumlah_ip / $jumlah_sks,2); }
		 
		
		if ($jumlah_sks >= $sks_lulus   ) { $tombol=""; } else { $tombol = 	'disabled="disabled"'; }
		
		echo '<tr  >
				<td  ><input type="checkbox" name="lulus[]" value="'.$id.'" '.$tombol.'></td> 
				<td  >'.$wr['NIM'].'</a></td>
				<td  >'.$wr['nama_mahasiswa'].'</td>
				<td  >'.$wr['tahun_masuk'].'</td>
				
				<td  >'.$jumlah_sks.'</td>
				<td >'.$kumulatif.'</td>
			</tr>'; 
		}
		echo '</tbody>
		</table>';
	echo '<input type="submit" class=tombols ui-corner-all value="Proses"/>
				</form>';	
			
	} else {
		 echo '
		 <div class="alert alert-danger" >Belum ada Data</div>';
	}
	 

 }

function Update() {
global $koneksi_db, $jenjangprodi, $prodi;

$tanggal_lulus = $_POST['tanggal_lulus'];	
$tglsk = $_POST['tgl_sk_yudisium'];	
	if (is_array($_POST['lulus'])) {
		foreach($_POST['lulus'] as $key=>$val) {
		$noijazah = $_POST['no_ijazah'].''.$val;
		$nosk= $_POST['no_sk_yudisium'].''.$val;
			$update = $koneksi_db->sql_query("UPDATE `m_mahasiswa` SET 
			`tanggal_lulus` = '$tanggal_lulus', status_aktif ='L', yudisium='Y', no_sk_yudisium='$nosk', tgl_sk_yudisium='$tglsk', no_ijazah='$noijazah'
			 WHERE `idm` = '$val'");
		}
	}
	Daftar();	
}

//$semester = BuatSesi('semester');
//$kelas = BuatSesi('kelas');
$kolom_mahasiswa = BuatSesi('kolom_mahasiswa');
$kunci_mahasiswa = BuatSesi('kunci_mahasiswa');

if ($_REQUEST['reset_mahasiswa'] == 'Reset') {
  $_SESSION['kolom_mahasiswa'] = '';
  $_SESSION['kunci_mahasiswa'] = '';
}


$go = (empty($_REQUEST['op'])) ? 'Daftar' : $_REQUEST['op']; 

echo '<div id="title" align="right">
        <font style="color:#FF9900; font-size:30px;"><strong>.:</strong></font>
        <font style="font-size:18px; color:#999999">Lulus Akademik</font><br />
        	<a href="index.php">Home</a> &raquo; <a href="index.php?m='.$_GET['m'].'">Proses Lulus Akademik</a>  &raquo; '.$go.'  
    </div>';
echo '<div class="mainContentCell"><div class="content">';
$go();
echo '</div></div>';

?>
