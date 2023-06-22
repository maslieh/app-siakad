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
			
		FilterMahasiswa('mahasiswa');

		global $koneksi_db, $user, $tahun_id;
		$prodi = $_SESSION['prodi'];

		  $whr = array();
		   $ord = 'group by t_mahasiswa_krs.idm';
		  if (($_SESSION['reset_mahasiswa'] != 'Reset') &&
		  !empty($_SESSION['kolom_mahasiswa']) && !empty($_SESSION['kunci_mahasiswa'])) {
			$whr[] = "$_SESSION[kolom_mahasiswa] like '%$_SESSION[kunci_mahasiswa]%' ";
			$ord = "group by t_mahasiswa_krs.idm order by $_SESSION[kolom_mahasiswa]";
			 }
			 
			 $whr[] = "t_mahasiswa_krs.tahun_id='$tahun_id'";
			 $whr[] = "m_mahasiswa.idm=t_mahasiswa_krs.idm";
			 $whr[] = "status_aktif='A'";
			 $whr[] = "verifi_pa='1'";
		if (!empty($_SESSION['prodi'])) $whr[] = "t_mahasiswa_krs.kode_prodi='$_SESSION[prodi]'";
		if (!empty($whr)) $strwhr = "where " .implode(' and ', $whr);
	
 

require('system/pagination_class.php');
$sql = "select * from m_mahasiswa inner join  t_mahasiswa_krs $strwhr ";
if(isset($_GET['starting'])){ //starting page
	$starting=$_GET['starting'];
}else{
	$starting=0;
}
$n = $starting;
$recpage = 10;//jumlah data yang ditampilkan per page(halaman)
$obj = new pagination_class($koneksi_db,$sql,$starting,$recpage);		
$result = $obj->result;
if($koneksi_db->sql_numrows($result)!=0){
	
	
		$i=0;
		while($wr = $koneksi_db->sql_fetchassoc($result)){
		$i++;
		$idm = $wr['idm'];
		
echo '<div class="panel panel-success">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapse'.$i.'">
        '.$i.'.   Nim / Nama :  '.$wr['NIM'].' -- '.$wr['nama_mahasiswa'].'
		</a>
      </h4>
    </div>';
			
		echo'
		  <div id="collapse'.$i.'" class="panel-collapse collapse">
        	<div class="panel-body">
<div class="table-responsive">
			<table   class="table table-striped table-bordered table-hover"  >
				<tr>
				<th width="5%" rowspan="2" align="center">No</th>
				<th rowspan="2" align="center">Kode MK</th>
				<th rowspan="2" align="center">Mata Kuliah</th>
				<th colspan="4" align="center">Presensi
				 </tr>
				<tr>
				 <th align="center" width="60">Hadir</th>
				   <th align="center" width="60">Sakit</th>
				   <th align="center" width="60">Ijin</th>
				   <th align="center" width="60">Alpa</th>
				  </tr>
			';
			
			$q = "select  a.id, kode_mk, nama_mk from t_mahasiswa_krs a inner join view_jadwal b on a.id=b.id 
					inner join t_mahasiswa_presensi c on a.idm=c.idm
					where a.kode_prodi='$prodi' and a.tahun_id='$tahun_id' and a.idm='$idm' group by a.id";
			$r = $koneksi_db->sql_query($q);
			$n=0;
			while ( $k = $koneksi_db->sql_fetchassoc($r))  {
				$n++;
				$jhadir = hitungpresensimahasiswa($prodi, $tahun_id, 'H', $k['id'], $idm);
				$jsakit = hitungpresensimahasiswa($prodi, $tahun_id, 'S', $k['id'], $idm);
				$jijin =  hitungpresensimahasiswa($prodi, $tahun_id, 'I', $k['id'], $idm);
				$jalpa = hitungpresensimahasiswa($prodi, $tahun_id, 'A', $k['id'], $idm);
				
				echo '<tr  >
				<td   >'.$n.'</td> 
				<td >'.$k['kode_mk'].'</td>
				<td >'.$k['nama_mk'].'</td>
				<td  >'.$jhadir.'</td>
				<td   >'.$jsakit.' </td>
				<td   >'.$jijin.' </td>
				<td   >'.$jalpa.' </td>
				</tr>
				';	
			}
			 echo '</table>
				</div>
				</div>
                <div class="panel-footer">
 					</div>
             </div>';
		echo '</div>';
		}
			echo $obj->total;
	echo '<br/>';
	echo $obj->anchors;	
		
	} else {
		 echo '<div class="alert alert-danger">Belum ada Data</div>
			 ';
	}

 
  
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
        <font style="font-size:18px; color:#999999">Rekap Presensi Mahasiswa Mahasiswa</font><br />
        	<a href="index.php">Home</a> &raquo; <a href="index.php?m='.$_GET['m'].'">Rekap Presensi Mahasiswa</a>  &raquo; '.$go.'  
    </div>';
echo '<div class="mainContentCell"><div class="content">';
	$go();
echo '</div></div>';
?>

 

