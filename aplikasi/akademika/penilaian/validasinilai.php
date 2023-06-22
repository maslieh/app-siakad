<?php
 
if (!cek_login ()){
header ("location:index.php");
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



function Daftar() {
global $koneksi_db,$tahun_id;
$prodi = $_SESSION['prodi'];

	echo"<input type=button class=button-red value='LAKUKAN VALIDASI' onclick=\"window.location.href='index.php?m=".$_GET['m']."&op=validasi&md=1';\">
	<br/>";
	echo '
	</br>
	<font align="center" style="font-size:18px; color:#999999"> -- Daftar Validasi Nilai -- </font><br />
	
	</br>';
$whr2[] = "a.kode_prodi='$prodi'";
$whr2[] = "a.tahun_id='$tahun_id'";
if (!empty($whr2)) $strwhr2 = "where " .implode(' and ', $whr2);
require('system/pagination_class.php');
$sql = "select  validasi, kode_mk, nama_mk, a.kelas from t_mahasiswa_krs a inner join view_jadwal b on a.id=b.id $strwhr2  group by a.id, a.kelas  order by nama_mk, a.kelas  ";
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
	
echo '<table class="table table-striped table-bordered table-hover"  >
		<thead>
		 <tr>
		   <th width="5%" rowspan="2" align="center">No</th>
		   <th rowspan="2" align="center">Kode Mata Kuliah</th>
		   <th rowspan="2" align="center">Mata Kuliah</th>
		   <th rowspan="2" align="center">Kelas</th>
		   <th colspan="2" align="center">Status Validasi</th>	   
		 </tr>
		 <tr>
		 </thead>
		 <tbody>';
				

/*
			$q = "select  validasi, kode_mk, nama_mk, a.kelas from t_mahasiswa_krs a inner join view_jadwal b on a.id=b.id $strwhr2  group by a.id, a.kelas  order by nama_mk, a.kelas ";
			
			
			$pilih = $koneksi_db->sql_query($q);
			$jumlah=$koneksi_db->sql_numrows($pilih);
			
			
			if ($jumlah > 0){
*/			
			$no=0;
				while($w = $koneksi_db->sql_fetchassoc($result)){
				
			
				$no++;

					echo '<tr >
						<td   >'.$no.'</td> 
						<td  >	'.$w['kode_mk'].'</td>
						<td>	'.$w['nama_mk'].'</td>
						<td  >	'.$w['kelas'].' </td>';
						if ($w['validasi'] == 1){
							echo'<td  > SUDAH DIVALIDASI </td>';
						}else{
							echo'<td  > <font color="red"> BELUM DIVALIDASI </font> </td>';
						}
						echo'
						</tr>'; 
				}

echo '</tbody>
			</table>';	
echo $obj->total;
	echo '<br/>';
	echo $obj->anchors;
			} else {
				 echo '<div class="alert alert-danger" >Belum ada Data</div>';
			}
		 
		 

}



function validasi() {
global $koneksi_db,$tahun_id, $kode_mk, $user, $kelas;


echo '<div class="col-md-6">';
	FilterMataKuliahDosen($prodi, $tahun_id, '', $_GET['m']);
	echo '</div><div class="col-md-6">';
	FilterKelas($prodi, $tahun_id, '', $_GET['m']); 
	echo '</div>';
	echo ' <font color = "red">Jangan memilih kelas (pilih = .:kelas:.) untuk melakukan validasi seluruh kelas</font>';
 
 	
echo '
	
	<form action="" method="post"  class="cmxform" id="form_input" name="form_input" style="width:100%">
	<br/>
			<input type="hidden" name="m" value="'.$_GET['m'].'"/>
			<input type="hidden" name="op" value="SIMPAN"/> 
	<thead> 	<tr ><th  colspan="7" align=right><input type="submit" name="Update" class=tombols ui-corner-all value="SIMPAN"/>
	<input type="button" class=tombols ui-corner-all value="Kembali" onClick="window.location = \'index.php?m='.$_GET['m'].'\'"/>
				</th>	</tr></thead></form>';
}

function SIMPAN() {
global $koneksi_db,$tahun_id;
$prodi = $_SESSION['prodi'];
$id = $_SESSION['kode_mk'];
$kelas=$_SESSION['kelas'];
 
 
	$whr = array();
	$whr[] = "kode_prodi='$_SESSION[prodi]'";
	$whr[] = "tahun_id='$tahun_id'";
	$whr[] = "kelas='$kelas'";
	$whr[] = "id='$id'";
	if (!empty($whr)) $strwhr = "where " .implode(' and ', $whr);
	
	$whr3 = array();
	$whr3[] = "kode_prodi='$_SESSION[prodi]'";
	$whr3[] = "tahun_id='$tahun_id'";
	$whr3[] = "id='$id'";
	if (!empty($whr3)) $strwhr3 = "where " .implode(' and ', $whr3);
	
	if ($kelas == 0){
	$update = $koneksi_db->sql_query("UPDATE `t_mahasiswa_krs` SET `validasi` = 1  $strwhr3");	
	}else{	
	$update = $koneksi_db->sql_query("UPDATE `t_mahasiswa_krs` SET `validasi` = 1  $strwhr");
	}
	Daftar();
}




$kelas = BuatSesi('kelas');
$kode_mk = BuatSesi('kode_mk');
$kolom_dosen = BuatSesi('kolom_dosen');
$kunci_dosen = BuatSesi('kunci_dosen');

if ($_REQUEST['reset_dosen'] == 'Reset') {
  $_SESSION['kolom_dosen'] = '';
  $_SESSION['kunci_dosen'] = '';
}


$go = (empty($_REQUEST['op'])) ? 'Daftar' : $_REQUEST['op']; 

echo '<div id="title" align="right">
        <font style="color:#FF9900; font-size:30px;"><strong>.:</strong></font>
        <font style="font-size:18px; color:#999999">Validasi Nilai</font><br />
        	<a href="index.php">Home</a> &raquo; <a href="?m='.$_GET['m'].'">Validasi Nilai</a>  &raquo; '.$go.'  
    </div>';
echo '<div class="mainContentCell"><div class="content">';
$go();
echo '</div></div>';

?>
