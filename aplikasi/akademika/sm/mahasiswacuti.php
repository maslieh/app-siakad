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

//FilterSemester($_GET['m']);
//FilterKelas($_GET['m']);
FilterMahasiswa($_GET['m']);


global $koneksi_db;
$prodi = $_SESSION['prodi'];
$tahun_id = $_SESSION['tahun_id'];

  $whr = array();
  $ord = '';
 
  if (($_SESSION['reset_mahasiswa'] != 'Reset') &&
  !empty($_SESSION['kolom_mahasiswa']) && !empty($_SESSION['kunci_mahasiswa'])) {
    $whr[] = "$_SESSION[kolom_mahasiswa] like '%$_SESSION[kunci_mahasiswa]%' ";
    $ord = "order by $_SESSION[kolom_mahasiswa]";
  }
  
  //$whr[] = "status_aktif='A'";
  	if (!empty($_SESSION['prodi'])) $whr[] = "t_mahasiswa_cuti.kode_prodi='$_SESSION[prodi]'";
	if (!empty($_SESSION['tahun_id'])) $whr[] = "tahun_id='$_SESSION[tahun_id]'";
	//if (!empty($_SESSION['kelas'])) $whr[] = "kelas='$_SESSION[kelas]'";
	//$whr[] = "semester='$_SESSION[semester]'";
	$whr[] = "status_cuti!='13'";
	if (!empty($whr)) $strwhr = "where " .implode(' and ', $whr);
  

	
require('system/pagination_class.php');
$sql = "select * from t_mahasiswa_cuti join m_mahasiswa on m_mahasiswa.idm=t_mahasiswa_cuti.idm $strwhr $ord";

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
	 
echo '
<form action="" method="post"  class="cmxform" id="form_input" style="width:100%">
         <input type="hidden" name="m" value="mahasiswa.cuti"/>
        <input type="hidden" name="op" value="Update"/>
<table class="table full"  >
   	<thead>
     <tr>
	   <th width="5%" align="center">No</th>
	   <th align="center">NIM</th>
       <th align="center">Nama</th>
       <th align="center">Angkatan</th>
		<th width="25%" align="center">Status Cuti</th>
     </tr>
	 </thead>
	 <tbody>';
 
		while($wr = $koneksi_db->sql_fetchassoc($result)){
		$wi = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM m_mahasiswa where `idm` = '$wr[idm]' limit 1 " ));
		$n++;
		//if ( ($wr['tanggal_akhir'] < $sekarang) && $wr['status_cuti'] != '11') { $ket = 'title="Batas Cuti Mahasiswa '.$wi['nama_mahasiswa'].' sudah melampaui Batas"'; }
		$id = $wr['idm'];
		echo '<tr '.$ket .'>
				<td >'.$n.'</td> 
				<td  >'.$wi['NIM'].'</a></td>
				<td >'.$wi['nama_mahasiswa'].'</td>
				<td >'.$wi['tahun_masuk'].'</td>
				<td  >
				<select name="status_cuti['.$id.']"  class="required"   >'.opAplikasi('70',$wr['status_cuti']).'</select></td>
			</tr>'; 
		}
		echo '</tbody>
		</table>';
		
	echo '<input type="submit" class=tombols ui-corner-all value="Update"/>
	</form>
				 ';	
			
	echo $obj->total;
	echo '<br/>';
	echo $obj->anchors;	
	} else {
		 echo '<div class="alert alert-danger">Belum ada Data</th>
			</div>';
	}
	 

 }

function Update() {
global $koneksi_db;
	if (is_array($_POST['status_cuti'])) {
		foreach($_POST['status_cuti'] as $key=>$val) {
			$update = $koneksi_db->sql_query("UPDATE `t_mahasiswa_cuti` SET `status_cuti` = '$val' WHERE `idm` = '$key'");
			if ($val == '13') { 
			$update = $koneksi_db->sql_query("UPDATE `m_mahasiswa` SET `status_aktif` = 'A' WHERE `idm` = '$key'");
			$update1 = mysql_query("UPDATE `t_mahasiswa_cuti` SET `status_cuti` = '$val' WHERE `idm` = '$key'");
			} else if ($val == '12') { 
			$update = $koneksi_db->sql_query("UPDATE `m_mahasiswa` SET `status_aktif` = 'C' WHERE `idm` = '$key'");
			} 
		}
	}
	
	Daftar();	
}

$semester = BuatSesi('semester');
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
        <font style="font-size:18px; color:#999999">Status Mahasiswa</font><br />
        	<a href="index.php">Home</a> &raquo; <a href="index.php?m='.$_GET['m'].'">Mahasiswa Cuti</a>  &raquo; '.$go.'  
    </div>';
echo '<div class="mainContentCell"><div class="content">';
$go();
echo '</div></div>';

?>
