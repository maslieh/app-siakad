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
global $koneksi_db,$tahun_id, $kode_mk, $user, $kelas;
$prodi = $_SESSION['prodi'];
$id = $_SESSION['kode_mk'];
$kelas=$_SESSION['kelas'];


echo '<div class="col-md-6">';
if ($_SESSION['Level']=="DOSEN" 	) {

		FilterMataKuliahDosen($prodi, $tahun_id, $user, $_GET['m']);
		echo '</div><div class="col-md-6">';
		FilterKelas($prodi, $tahun_id, $user, $_GET['m']);
	
 } else if  ($_SESSION['Level']=="PA"	){
 
 	FilterMataKuliahDosen($prodi, $tahun_id, $user, $_GET['m']);
	echo '</div><div class="col-md-6">';
		FilterKelas($prodi, $tahun_id, $user, $_GET['m']);
	//$strwhr = "where idd='$user'";
 }else {
 
	 FilterMataKuliahDosen($prodi, $tahun_id, '', $_GET['m']);
	 echo '</div><div class="col-md-6">';
	FilterKelas($prodi, $tahun_id, '', $_GET['m']);
 } 

echo '</div>'; 
   $whr = array();
  $ord = '';
	$whr[] = "kode_prodi='$_SESSION[prodi]'";
	$whr[] = "tahun_id='$tahun_id'";
	$whr[] = "kelas='$kelas'";
	$whr[] = "id='$id'";
	if (!empty($whr)) $strwhr = "where " .implode(' and ', $whr);

echo '
<table class="table table-striped table-bordered table-hover"  >
   	<thead>
     <tr>
       <th width="5%" rowspan="2" class="text-center">No</th>
       <th rowspan="2" class="text-center">NIP</th>
       <th rowspan="2" class="text-center">Nama</th>
       <th rowspan="2" class="text-center">Jabatan</th>
       <th colspan="4" class="text-center">Presensi </th>
     </tr>
     <tr>
	 	<th align="center" width="60">Hadir</th>
	   <th align="center" width="60">Sakit</th>
	   <th align="center" width="60">Ijin</th>
	   <th align="center" width="60">Alpa</th>
      </tr>
	 </thead>
	 <tbody>';
	 
	 
	
	
require('system/pagination_class.php');
$sql = "select * from view_jadwal $strwhr ";
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
	
	
		while($wr = $koneksi_db->sql_fetchassoc($result)){
		$n++;
		$idd = $wr['idd'];
		$wm = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM m_dosen where `idd` = '$idd' limit 1 " ));
		echo '<tr bgcolor="#f2f2f2">
				<td  align=center>'.$n.'</td> 
				<td  align=left>'.$wm['nip'].'</td>
				<td  align=left>'.$wm['gelar_depan'].' '.$wm['nama_dosen'].', '.$wm['gelar_belakang'].'</td>
				<td  align=left>'.viewAplikasi('09', ''.$wm['jabatan_akademik'].'').'</td>
				<td  align=center>'.hitungpresensidosen($prodi, $id, $kelas, $tahun_id, 'H', $idd).'</td>
				<td  align=center>'.hitungpresensidosen($prodi, $id, $kelas, $tahun_id, 'S', $idd).'</td>
				<td  align=center>'.hitungpresensidosen($prodi, $id, $kelas, $tahun_id, 'I', $idd).'</td>
				<td  align=center>'.hitungpresensidosen($prodi, $id, $kelas, $tahun_id, 'A', $idd).'</td>

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
		</table> ';
	
	
 	echo $obj->total;
	echo '<br/>';
	echo $obj->anchors;	
			
	echo '
	</br>
	<font align="center" style="font-size:18px; color:#999999"> -- Berita Acara Perkuliahan dan Kehadiran Mahasiswa -- </font><br />
	
	</br>
	<table class="table table-striped table-bordered table-hover"  >
		<thead>
		 <tr>
		   <th width="5%" rowspan="2" class="text-center">No</th>
		   <th rowspan="2" class="text-center">Hari</th>
		   <th rowspan="2" class="text-center">Tanggal</th>
		   
		   <th colspan="2" class="text-center">Jumlah Mahasiswa</th>
		   <th rowspan="2" class="text-center">Pokok Bahasan</th>
		   <th width="60" rowspan="2" align="center">AKSI</th>
		 </tr>
		 <tr>
		   
		   <th align="center" width="60">Terdaftar</th>
		   <th align="center" width="60">Hadir</th>

		  </tr>
		 </thead>
		 <tbody>';
				
			$whr2[] = "a.kode_prodi='$prodi'";
			$whr2[] = "a.tahun_id='$tahun_id'";
			$whr2[] = "a.kelas='$kelas'";
			$whr2[] = "a.id='$id'";
			$whr2[] = "a.idd='$idd'";
			$whr2[] = "a.jenis_presensi='H'";
			$whr2[] = "a.ver='Y'";
			if (!empty($whr2)) $strwhr2 = "where " .implode(' and ', $whr2);

			$q = "select  a.*, mulai, sampai from t_dosen_presensi a inner join m_jam b on a.jam=b.idj $strwhr2 order by tanggal";
			
			$r = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "select  count(idm) as jum from t_mahasiswa_krs where
			kode_prodi='$prodi' and tahun_id='$tahun_id' and kelas='$kelas' and id='$id'
			"));
			
			
			
			$pilih = $koneksi_db->sql_query($q);
			$jumlah=$koneksi_db->sql_numrows($pilih);
			
			
			if ($jumlah > 0){
			$no=0;
				while($w = $koneksi_db->sql_fetchassoc($pilih)){
				
				$s = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "select  count(idm) as jum2 from t_mahasiswa_presensi where
				kode_prodi='$prodi' and tahun_id='$tahun_id' and kelas='$kelas' and id='$id' and tanggal='".$w['tanggal']."' and 
				jenis_presensi='H' and jam='".$w['jam']."'
				"));
				
				$no++;

					echo '<tr >
						<td  align=center>'.$no.'</td> 
						<td align="center">	'.$w['hari'].'</td>
						<td align="left">	'.converttgl($w['tanggal']).'</td>
						
						<td align="center">	'.$r['jum'].' </td>
						<td align="center">	'.$s['jum2'].' </td>
						<td align="left">	'.$w['bap'].'</td>
						<td><a href="index.php?m='.$_GET['m'].'&op=edit&iddm='.$w['iddm'].'" >UBAH BAP</a> </td>
						</tr>'; 
				}

			} else {
				echo ' <thead> 	<tr ><th  colspan="8" align=center>Belum ada data</th>	</tr></thead>';
			}
		 
		 echo '</tbody>
			</table>';	
			
		echo "<br/>
		<table>
		<td width=300>
		 <td>
		<div style='float: right ;height: 30px;'>
        <fieldset class='import' >
            <legend>Print BAP Dosen</legend>
			>>
            <input type=button  class=\"btn btn-primary\" value='Cetak BAP' onclick=\"bukajendela('cetak.php?m=cetakbap'); return false\" >
			<<
			</fieldset>
		</div>
		</td>
		</table>
		<br/>
		<br/>";	

}



function edit() {
global $koneksi_db, $tahun_id;
$iddm = $_REQUEST['iddm'];

$s = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "select  bap  from t_dosen_presensi where iddm=$iddm"));
	
	
echo '
	 <form action="" method="post"  class="cmxform" id="form_input" name="form_input" style="width:100%">
			<input type="hidden" name="m" value="'.$_GET['m'].'"/>
			<input type="hidden" name="op" value="SIMPAN"/>
			
		<fieldset class="ui-widget ui-widget-content ui-corner-all" >
				<legend class="ui-widget ui-widget-header ui-corner-all">Pokok Bahasan </legend>';
		echo '<textarea name="bap" cols="45" rows="3"  class="required" id="bap" style="width:100%">'.$s[bap].'</textarea>
			
	</fieldset>';
	
echo ' <thead> 	<tr ><th  colspan="7" align=right><input type="submit" name="Update" class="btn" value="SIMPAN"/>
	<input type="button" class="btn" value="Kembali" onClick="window.location = \'index.php?m='.$_GET['m'].'\'"/>
				</th>	</tr></thead></form>';
}

function SIMPAN() {
global $koneksi_db;
$iddm = $_REQUEST['iddm']; 
	
		
			$update = $koneksi_db->sql_query("UPDATE `t_dosen_presensi` SET `bap` = '".$_POST['bap']."'  WHERE `iddm` = '$iddm'");
		
	
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
        <font style="font-size:18px; color:#999999">Rekap BAP Dosen</font><br />
        	<a href="index.php">Home</a> &raquo; <a href="?m='.$_GET['m'].'">Rekap BAP Dosen</a>  &raquo; '.$go.'  
    </div>';
echo '<div class="mainContentCell"><div class="content">';
$go();
echo '</div></div>';

?>
