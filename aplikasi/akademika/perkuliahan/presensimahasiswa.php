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
global $koneksi_db, $user,$prodi,$tahun_id;
$prodi = $_SESSION['prodi'];
//$tahun_id = $_SESSION['tahun_id'];
if ($_SESSION['Level']!="MAHASISWA"	) {
	if ($_SESSION['Level'] =='DOSEN') {
	echo '<div class="col-md-6">';
		FilterMataKuliahDosen($prodi, $tahun_id, $user, $_GET['m']);
		echo '</div><div class="col-md-6">';	
		FilterKelas($prodi, $tahun_id, $user, $_GET['m']);
		echo '</div>';
		} 
		else if ($_SESSION['Level'] =='PA') {
		echo '<div class="col-md-6">';
		FilterMataKuliahDosen($prodi, $tahun_id, $user, $_GET['m']);
		echo '</div><div class="col-md-6">';	
		FilterKelas($prodi, $tahun_id, $user,  $_GET['m']);
		echo '</div>';
		}else {
		echo '<div class="col-md-6">';
		FilterMataKuliahDosen($prodi, $tahun_id, '', $_GET['m']);
		echo '</div><div class="col-md-6">';	
		FilterKelas($prodi, $tahun_id, '', $_GET['m']);
	echo ' </div>';
	echo"
	
	
	
	";

	}
	$whr = array();
	$ord = 'group by t_mahasiswa_presensi.idm order by t_mahasiswa_presensi.idm';

  	$whr[] = "t_mahasiswa_presensi.kode_prodi='$_SESSION[prodi]'";
	$whr[] = "t_mahasiswa_presensi.tahun_id='$tahun_id'";
	$whr[] = "kelas='$_SESSION[kelas]'";
	$whr[] = "id='$_SESSION[kode_mk]'";
	if (!empty($whr)) $strwhr = "where " .implode(' and ', $whr);
} else {
 	$strwhr = "where idm='$dosen'";
}  
echo '<table class="table table-striped table-bordered table-hover"  >
   	<thead>
     <tr>
       <th width="5%" rowspan="2" align="center">No</th>
       <th rowspan="2" align="center" width="70">NIM</th>
       <th rowspan="2" align="center" >Nama</th>
       <th rowspan="2" align="center" width="50">Angkatan</th>
       <th colspan="4" align="center">Presensi '; 
	   	if ($_SESSION['Level'] !='MAHASISWA') {
	   	echo '<a class="btn" href="index.php?m='.$_GET['m'].'&op=Input" class="button-red"><i class="fa fa-plus"></i></a>';
		}
		echo '</th>
       <th rowspan="2" align="center" width="130">AKSI</th>
     </tr>
     <tr>
	 <th align="center" width="40">Hadir</th>
	   <th align="center" width="40">Sakit</th>
	   <th align="center" width="40">Ijin</th>
	   <th align="center" width="40">Alpa</th>
      </tr>
	 </thead>
	 <tbody>';
	 
 
	
require('system/pagination_class.php');


$sql = "select t_mahasiswa_presensi.idm, NIM, nama_mahasiswa, tahun_masuk, 
					sum(case when t_mahasiswa_presensi.idm=m_mahasiswa.idm and jenis_presensi='H' then presensi else 0 end) as hadir,
					sum(case when t_mahasiswa_presensi.idm=m_mahasiswa.idm and jenis_presensi='S' then presensi else 0 end) as sakit,
					sum(case when t_mahasiswa_presensi.idm=m_mahasiswa.idm and jenis_presensi='I' then presensi else 0 end) as ijin,
					sum(case when t_mahasiswa_presensi.idm=m_mahasiswa.idm and jenis_presensi='A' then presensi else 0 end) as alpa
					from t_mahasiswa_presensi left join m_mahasiswa  using(idm) $strwhr $ord ";

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
			$id = $wr['idm'];
			$idmatkul=$wr['id'];
		
		echo '<tr bgcolor="#f2f2f2">
				<td  align=center>'.$n.'</td> 
				<td  align=center>
					'.$wr['NIM'].'
				</td>
				<td  align=left>'.$wr['nama_mahasiswa'].'</td>
				<td  align=left>'.$wr['tahun_masuk'].'</td>
				<td  align=center>'.$wr['hadir'].'</td>
				<td  align=center>'.$wr['sakit'].' </td>
				<td  align=center>'.$wr['ijin'].' </td>
				<td  align=center>'.$wr['alpa'].' </td>
				<td >
					<a class="btn" href="index.php?m='.$_GET['m'].'&op=detail&idm='.$id.'" >
					<i class="fa fa-folder"></i></a>';
					if ($_SESSION['Level']!='MAHASISWA') {
					echo '<a class="btn" href="index.php?m='.$_GET['m'].'&op=edit&idm='.$id.'" >
					<i class="fa fa-edit"></i></a>';
					}
					echo '<a class="btn" href="" onclick="bukajendela(\'cetak.php?m=presensimahasiswa&idm='.$id.'\'); return false">
					<i class="fa fa-print"></i></a>
					</td>
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
		</table>';
	echo '<a class="btn" href="" onclick="bukajendela(\'cetak.php?m=cetakabsen\'); return false">
					<i class="fa fa-print"> </i> PRINT</a>&nbsp;';
						echo '<a class="btn" href="" onclick="bukajendela(\'cetak.php?m=cetakmanual\'); return false">
					<i class="fa fa-print"> </i> PRINT manual</a><br/>';
  	echo $obj->total;
	echo '<br/>';
	echo $obj->anchors;	
}

function input(){
	global $koneksi_db, $user;
	$level = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT level FROM user where userid='$user' " ));
	
	if ($level['level']!='ADMIN'){

		if (CekBatasKuliah()){
			GoInput();
		}
		}else{
			GoInput();
		}	
}

function edit(){
	global $koneksi_db, $user;
	$level = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT level FROM user where userid='$user' " ));
	
	if ($level['level']!='ADMIN'){

		if (CekBatasKuliah()){
			goedit();
		}
		}else{
			goedit();
		}	
}

function GoInput() {
global $koneksi_db,$tahun_id,$user;
$prodi = $_SESSION['prodi'];

  
  $whr = array();

  	$whr[] = "a.kode_prodi='$_SESSION[prodi]'";
	$whr[] = "a.tahun_id='$tahun_id'";
	$whr[] = "kelas='$_SESSION[kelas]'";
	$whr[] = "id='$_SESSION[kode_mk]'";
	
	if (!empty($whr)) $strwhr = "where " .implode(' and ', $whr);
 

$sekarang = date('Y-m-d');  

$kode_mk = $_SESSION['kode_mk'];
$kelas =  $_SESSION['kelas'];

echo '
 <form action="" method="post"  class="cmxform" id="form_input" style="width:100%">
		
        <input type="hidden" name="m" value="'.$_GET['m'].'"/>
        <input type="hidden" name="op" value="saveInput"/>
		
 
		 <table ><tr><td width="40%">Tanggal Presensi </td><td>
		';
			$tgl_absen = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM t_dosen_presensi where `kode_prodi` = '$prodi' AND `tahun_id` = '$tahun_id' AND id='$kode_mk' AND kelas='$kelas'  limit 1 " ));
			 $tgl1=date('Y-m-d');
		if ($_SESSION['Level']=='DOSEN') {
//	if($tgl_absen['tanggal']==$tgl1){
	//	  	echo '<select name="tanggal" disabled  required >'.tgl_ngajar($prodi, $tahun_id, $user, $kode_mk, $kelas, $_GET['m']).'</select>';
	//	}else {
		    	echo '<select name="tanggal"  required >'.tgl_ngajar($prodi, $tahun_id, $user, $kode_mk, $kelas, $_GET['m']).'</select>';
//	}
	
		
		} 
		else if ($_SESSION['Level']=='PA') {
		echo '<select name="tanggal"  required >'.tgl_ngajar($prodi, $tahun_id, $user, $kode_mk, $kelas, $_GET['m']).'</select>';
		}else {
		echo  '<select name="tanggal"  required >'.tgl_ngajar_admin($prodi, $tahun_id, $kode_mk, $kelas, $_GET['m']).'</select>';
		}
		echo '</td></tr>
		<tr><td>Pokok Bahasan dan Materi Perkuliahan </td><td>';
		echo '<textarea name="bap" cols="45" rows="3"  required id="bap" style="width:100%"></textarea></td></tr>';
	
echo '		
</table>
		
<table width="100%" border="0" align="center" cellpadding="1" cellspacing="0" class="table table-striped table-bordered table-hover">
   	<thead>
     <tr>
	   <th rowspan="2" width="5%" align="center">No</th>
	   <th rowspan="2" align="center">NIM</th>
       <th rowspan="2" align="center">Nama</th>
       <th rowspan="2" align="center">Angkatan</th>
	   <th rowspan="2" align="center">Batas Studi</th>
	   <th colspan="4" align="center">Presensi</th>
     </tr>
	 <tr>
	 	<th align="center" width="40">Hadir</th>
	   <th align="center" width="40">Alpa</th>
	   <th align="center" width="40">Ijin</th>
	   <th align="center" width="40">Sakit</th>
	 </tr>
	 </thead>
	 <tbody>';
	 
	$q = "select  * from t_mahasiswa_krs a inner join m_mahasiswa b on a.idm=b.idm $strwhr and verifi_pa='1' order by nim"  ;
	$pilih = $koneksi_db->sql_query($q);
	$jumlah=$koneksi_db->sql_numrows($pilih);
			
	if ($jumlah > 0){
		$n=0;
		while($wr = $koneksi_db->sql_fetchassoc($pilih)){
		$n++;
		$id = $wr['idkrs'];
		$idm = $wr['idm'];
		$wm = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM m_mahasiswa where `idm` = '$idm' limit 1 " ));
		echo '<tr bgcolor="#f2f2f2">
				<td  align=center>'.$n.'<input type="hidden" name="idm['.$id.']" value="'.$id.'"/></td> 
				<td  align=center>'.$wm['NIM'].'</a></td>
				<td  align=left>'.$wm['nama_mahasiswa'].'</td>
				<td  align=left>'.$wm['tahun_masuk'].'</td>
				<td  align=left>'.NamaTahun($wm['batas_studi'],$wm['kode_prodi']).'</td>
					<td  align=center><input type="radio" '.$ck.' name="jenis_presensi['.$id.']" checked="checked" value="H"></td>
					<td  align=center><input type="radio" '.$ck.' name="jenis_presensi['.$id.']" value="A"></td>
					<td  align=center><input type="radio" '.$ck.' name="jenis_presensi['.$id.']" value="I"></td>
					<td  align=center><input type="radio" '.$ck.' name="jenis_presensi['.$id.']" value="S"></td>
			</tr>'; 
		}
		echo '<thead><tr > 
				<th  colspan="8" align=right>
					<input type="submit" class="tombols ui-corner-all" value="SIMPAN"/>
					<input type="button" class=tombols ui-corner-all value="KEMBALI" onClick="window.location = \'index.php?m='.$_GET['m'].'\'"/>
				</th>
			</tr></thead>';
	} else {
		 echo '
		 <thead><tr > 
			<th  colspan="8" align=center>Belum ada Data</th>
			</tr>
		</thead>';
	}
	 echo '</tbody>
		</table></form>';
}

function saveInput() {
global $koneksi_db, $tahun_id, $prodi ;
$prodi = $_SESSION['prodi'];
$hari = gethari(substr($_REQUEST['tanggal'],0,10));
$tgl = substr($_REQUEST['tanggal'],0,10);
$jam = substr($_REQUEST['tanggal'],10);
	if (is_array($_POST['idm'])) {
		foreach($_POST['idm'] as $key=>$val) {
		$presensi = $_POST['jenis_presensi'][$key];
		//$jpresensi= ($presensi =="" ) ? "H": $presensi;
		$jpresensi= $presensi;

			
				
			$wi = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM t_mahasiswa_krs where idkrs='$key' limit 1 " ));
			$whr[] = "kode_prodi='$wi[kode_prodi]'";
			$whr[] = "tahun_id='$wi[tahun_id]'";
			$whr[] = "kelas='$wi[kelas]'";
			$whr[] = "id='$wi[id]'";
			$whr[] = "tanggal='$tgl'";
			$whr[] = "jam='$jam'";
			$whr[] = "idm='$wi[idm]'";
			if (!empty($whr)) $strwhr = "where " .implode(' and ', $whr);
				
			$ada=$koneksi_db->sql_numrows($koneksi_db->sql_query("SELECT * FROM t_mahasiswa_presensi $strwhr"));
			
					
			if ($ada < 1) {
			
			$s = "insert into t_mahasiswa_presensi SET 
					kode_pt='".$wi['kode_pt']."',
					kode_fak='".$wi['kode_fak']."',
					kode_jenjang='".$wi['kode_jenjang']."',
					kode_konsentrasi='".$wi['kode_konsentrasi']."',
					kode_prodi='".$wi['kode_prodi']."',
					tahun_id='".$tahun_id."',
					kelas='".$wi['kelas']."',
					id='".$wi['id']."',
					idm='".$wi['idm']."',
					hari='".$hari."',
					tanggal='$tgl',
					jam='$jam',
					jenis_presensi='".$jpresensi."',
					presensi='1'";
			  $koneksi_db->sql_query($s);
			  
			  
			  $presensi_dosen = total_presensi_dosen($wi['kode_prodi'], $tahun_id, $wi['kelas'], $wi['id'], 'H',  $wi['idd']);
				$presensi_mahasiswa = total_presensi_mahasiswa($wi['kode_prodi'], $tahun_id, $wi['kelas'], $wi['id'], 'H',  $wi['idm']);
				$nilaikehadiran = ($presensi_mahasiswa / $presensi_dosen) * 100;
				
				
				$nilai = nilai_ke($prodi, $tahun_id, $wi['id'], $wi['kelas'], 'HADIR', $wi['idm']);
				
				
				if ( $nilai == "") {
			    $so = "insert into t_mahasiswa_nilai SET 
							kode_pt='".$wi['kode_pt']."',
							kode_fak='".$wi['kode_fak']."',
							kode_jenjang='".$wi['kode_jenjang']."',
							kode_prodi='".$wi['kode_prodi']."',
							tahun_id='".$wi['tahun_id']."',
							kelas='".$wi['kelas']."',				
							idm='".$wi['idm']."',
							id='".$wi['id']."',
							jenis_nilai='HADIR',
							nilai_ke = 0,
							nilai='".$nilaikehadiran."'
							";
				
				
						}	else {
				 $so = "UPDATE t_mahasiswa_nilai SET 	
							nilai='".$nilaikehadiran."'
							where kode_pt='".$wi['kode_pt']."'
							and kode_fak='".$wi['kode_fak']."'
							and kode_jenjang='".$wi['kode_jenjang']."'
							and kode_prodi='".$wi['kode_prodi']."'
							and tahun_id='".$tahun_id."'
							and kelas='".$wi['kelas']."'				
							and idm='".$wi['idm']."'
							and id='".$wi['id']."'
							and jenis_nilai='HADIR'
							";
							
							
						}
						
					
						$koneksi_db->sql_query($so); 

							$ss = "UPDATE t_dosen_presensi SET 	
							bap='".$_REQUEST['bap']."',ver='Y'
							where kode_pt='".$wi['kode_pt']."'
							and kode_fak='".$wi['kode_fak']."'
							and kode_prodi='".$wi['kode_prodi']."'
							and tahun_id='".$tahun_id."'
							and kelas='".$wi['kelas']."'
							and id='".$wi['id']."'
							and tanggal='$tgl'
							and jam='$jam'
							";
						$koneksi_db->sql_query($ss);
						
			}else{
				
				echo "<script>alert('Kehadiran sudah pernah di input !');</script>";
				break;
			}
								
		}
		}		
	
	
	Daftar();
	}



function goedit() {

global $koneksi_db, $tahun_id;
$prodi = $_SESSION['prodi'];
$kelas = $_SESSION['kelas'];
$idm =$_REQUEST['idm'];


$w = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM m_mahasiswa where idm='".$idm."' limit 1 " ));
$foto= ($w['foto'] =="" ) ? "images/no_avatar.gif": "images/avatar/".$w['foto']."";
	
echo '
		<table  border="0" cellspacing="1"class="datatable " cellpadding="1">
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
			<td>KONSENTRASI</td>
			<td><b >'.viewkonsentrasi(''.$w['kode_konsentrasi'].'').'</b ></td>
		  </tr>
		  <tr>
			<td>PROGRAM STUDI </td>
			<td><b >'.viewprodi(''.$w['kode_prodi'].'').'</b ></td>
		  </tr>
		  <tr>
			<td>BATAS STUDI </td>
			<td><b >'.strtoupper(NamaTahun($w['batas_studi'],$w['kode_prodi'])).'</b ></td>
		  </tr>
		  </thead>
		</table> <br/>';
			
	echo '
	 <form action="" method="post"  class="cmxform" id="form_input" name="form_input" style="width:100%">
			<input type="hidden" name="m" value="'.$_GET['m'].'"/>
			<input type="hidden" name="op" value="Update"/>
			
	<table width="100%" border="0" align="center" cellpadding="1" cellspacing="0" class="rapor">
		<thead>
		 <tr>
		   <th width="5%" rowspan="2" class="text-center">No</th>
		   <th rowspan="2" class="text-center">Hari</th>
		   <th rowspan="2" class="text-center">Tanggal</th>
		   <th rowspan="2" class="text-center">Jam</th>
		   <th colspan="4" class="text-center">Presensi</th>
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
			if (!empty($whr)) $strwhr = "where " .implode(' and ', $whr);

			$q = "select  a.*, mulai, sampai from t_mahasiswa_presensi a inner join m_jam b on a.jam=b.idj 	$strwhr order by tanggal";
			$pilih = $koneksi_db->sql_query($q);
			$jumlah=$koneksi_db->sql_numrows($pilih);
			
			if ($jumlah > 0){
			$no=0;
				while($w = $koneksi_db->sql_fetchassoc($pilih)){
		
				$no++;
				$id = $w['idpm'];
					echo '<tr >
						<td  align=center>'.$no.'</td> 
						<td align="center">	'.$w['hari'].'</td>
						<td align="center">	'.converttgl($w['tanggal']).'</td>';
						echo '<td align="center">	'.$w['mulai'].' -- '.$w['sampai'].'</td>';
							$query  = $koneksi_db->sql_query ("SELECT * FROM r_kode where aplikasi = '59'  ");
							while( $r = $koneksi_db->sql_fetchrow ($query)) {
							 $ck = ($r[2] ==  $w['jenis_presensi'] ) ? 'checked' : '';
							echo '<td  align=center><input type="radio" '.$ck.' name="jenis_presensi['.$id.']" value="'.$r[2].'"></td>';
							}
	
						echo '</tr>'; 
	
				}
				echo ' <thead> 	<tr ><th  colspan="8" align=right><input type="submit" name="simpan" class=tombols ui-corner-all value="Update"/>
				<input type="button" class=tombols ui-corner-all value="Kembali" onClick="window.location = \'index.php?m='.$_GET['m'].'\'"/>
				</th>	</tr></thead>';
			} else {
				echo ' <thead> 	<tr ><th  colspan="8" align=center>Belum ada data</th>	</tr></thead>';
			}
		 
		 echo '</tbody>
			</table></form>';

}

function detail() {

global $koneksi_db, $tahun_id;
$prodi = $_SESSION['prodi'];
$kelas = $_SESSION['kelas'];
$idm =$_REQUEST['idm'];
$kode_mk = $_SESSION['kode_mk'];

$w = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM m_mahasiswa where idm='".$idm."' limit 1 " ));
$foto= ($w['foto'] =="" ) ? "images/no_avatar.gif": "images/avatar/".$w['foto']."";
	

echo '
		<table  border="0" cellspacing="1"class="datatable " cellpadding="1">
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
			<td>KONSENTRASI</td>
			<td><b >'.viewkonsentrasi(''.$w['kode_konsentrasi'].'').'</b ></td>
		  </tr>
		  <tr>
			<td>PROGRAM STUDI </td>
			<td><b >'.viewprodi(''.$w['kode_prodi'].'').'</b ></td>
		  </tr>
		  <tr>
			<td>BATAS STUDI </td>
			<td><b >'.strtoupper(NamaTahun($w['batas_studi'],$w['kode_prodi'])).'</b ></td>
		  </tr>
		  </thead>
		</table> <br/>';
			
	echo '		
	<table width="700" border="1" align="center" cellpadding="1" cellspacing="0" class="rapor">
		<thead>
		 <tr>
		   <th width="5%" rowspan="2" class="text-center">No</th>
		   <th rowspan="2" class="text-center">Hari</th>
		   <th rowspan="2" class="text-center">Tanggal</th>
		   <th rowspan="2" class="text-center">Jam</th>
		   <th colspan="4" class="text-center">Presensi</th>
		 </tr>
		 <tr>
		 
		   <th class="text-center" width="60">Hadir</th>
		   <th class="text-center" width="60">Sakit</th>
		   <th class="text-center" width="60">Ijin</th>
		    <th class="text-center" width="60">Alpa</th>
		  </tr>
		 </thead>
		 <tbody>';
					
		  	$whr[] = "a.kode_prodi='$_SESSION[prodi]'";
			$whr[] = "a.tahun_id='$tahun_id'";
			$whr[] = "a.kelas='$_SESSION[kelas]'";
			$whr[] = "a.id='$_SESSION[kode_mk]'";
			$whr[] = "a.idm='$idm'";
			$whr[] = "a.jam=b.idj";
			if (!empty($whr)) $strwhr = "where " .implode(' and ', $whr);

			$q = "select  a.*, mulai, sampai from t_mahasiswa_presensi a inner join m_jam b $strwhr order by tanggal";
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
						<td align="center">	'.converttgl($w['tanggal']).'</td>';
						echo '<td align="center">	'.$w['mulai'].' -- '.$w['sampai'].'</td>';
						
							$query  = $koneksi_db->sql_query ("SELECT * FROM r_kode where aplikasi = '59'  ");
							while( $r = $koneksi_db->sql_fetchrow ($query)) {
							 $ck = ($r[2] ==  $w['jenis_presensi'] ) ? '1' : '';
							echo '<td  align=center>'.$ck.'</td>';
							}
	
						echo '</tr>'; 
				}
				echo ' <thead> 	<tr >
					<th  colspan="4" class="text-center">Total Presensi</th>
					
					<th  class="text-center">'.hitungpresensimahasiswa($prodi, $tahun_id, 'H', $kode_mk, $idm).'</th>
					<th  class="text-center">'.hitungpresensimahasiswa($prodi, $tahun_id, 'S', $kode_mk, $idm).'</th>
					<th  class="text-center">'.hitungpresensimahasiswa($prodi, $tahun_id, 'I', $kode_mk, $idm).'</th>
					<th  class="text-center">'.hitungpresensimahasiswa($prodi, $tahun_id, 'A', $kode_mk, $idm).'</th>
					
					</tr></thead>';
			} else {
				echo ' <thead> 	<tr ><th  colspan="8" align=center>Belum ada data</th>	</tr></thead>';
			}
		 
		 echo '</tbody>
			</table>';
		echo '<input type="button" class=tombols ui-corner-all value="Kembali" onClick="window.location = \'index.php?m='.$_GET['m'].'\'"/>';
}

function Update() {
global $koneksi_db, $tahun_id;
	if (is_array($_POST['jenis_presensi'])) {
		foreach($_POST['jenis_presensi'] as $key=>$val) {
			$update = $koneksi_db->sql_query("UPDATE `t_mahasiswa_presensi` SET `jenis_presensi` = '$val' WHERE `idpm` = '$key'");
		
	$wi = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM t_mahasiswa_presensi where idpm='$key' limit 1 " ));
	$presensi_dosen = total_presensi_dosen($wi['kode_prodi'], $tahun_id, $wi['kelas'], $wi['id'], 'H',  $wi['idd']);
	$presensi_mahasiswa = total_presensi_mahasiswa($wi['kode_prodi'], $tahun_id, $wi['kelas'], $wi['id'], 'H',  $wi['idm']);
	$nilaikehadiran = ($presensi_mahasiswa / $presensi_dosen) * 100;
	
	
	$so = "UPDATE t_mahasiswa_nilai SET 	
							nilai='".$nilaikehadiran."'
							where kode_pt='".$wi['kode_pt']."'
							and kode_fak='".$wi['kode_fak']."'
							and kode_jenjang='".$wi['kode_jenjang']."'
							and kode_prodi='".$wi['kode_prodi']."'
							and tahun_id='".$tahun_id."'
							and kelas='".$wi['kelas']."'				
							and idm='".$wi['idm']."'
							and id='".$wi['id']."'
							and jenis_nilai='HADIR'
							";

						$koneksi_db->sql_query($so); 	

						}
	}

	Daftar();	
}


$kelas = BuatSesi('kelas');
$kode_mk = BuatSesi('kode_mk');
$kolom_mahasiswa = BuatSesi('kolom_mahasiswa');
$kunci_mahasiswa = BuatSesi('kunci_mahasiswa');

if ($_REQUEST['reset_mahasiswa'] == 'Reset') {
  $_SESSION['kolom_mahasiswa'] = '';
  $_SESSION['kunci_mahasiswa'] = '';
}


$go = (empty($_REQUEST['op'])) ? 'Daftar' : $_REQUEST['op']; 

echo '<div id="title" align="right">
        <font style="color:#FF9900; font-size:30px;"><strong>.:</strong></font>
        <font style="font-size:18px; color:#999999">Presensi Mahasiswa</font><br />
        	<a href="index.php">Home</a> &raquo; <a href="index.php?m='.$_GET['m'].'">Presensi Mahasiswa</a>  &raquo; '.$go.'  
    </div>';
echo '<div class="mainContentCell"><div class="content">';
$go();
echo '</div></div>';

?>
