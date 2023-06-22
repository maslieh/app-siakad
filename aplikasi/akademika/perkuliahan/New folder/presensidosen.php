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
global $koneksi_db,$tahun_id, $kode_mk, $kelas;
$prodi = $_SESSION['prodi'];
$id = $_SESSION['kode_mk'];
$kelas=$_SESSION['kelas'];

//FilterSemester('presensi.dosen');
if ($_SESSION['Level']!="DOSEN"	) {

echo '<div class="col-md-6">';
	FilterMataKuliahDosen($prodi, $tahun_id, $user, $_GET['m']);
echo '</div><div class="col-md-6">';	
	FilterKelas($prodi, $tahun_id, $user, $_GET['m']);
echo '</div>';	

  $whr = array();
  $ord = '';
	$whr[] = "kode_prodi='$_SESSION[prodi]'";
	$whr[] = "tahun_id='$tahun_id'";
	$whr[] = "kelas='$kelas'";
	$whr[] = "id='$id'";
	if (!empty($whr)) $strwhr = "where " .implode(' and ', $whr);
 } else {
 	$strwhr = "where idd='$user'";
 } 

echo '
<table class="table table-striped table-bordered table-hover"  >
   	<thead>
     <tr>
       <th width="5%" rowspan="2" class="text-center">No</th>
       <th rowspan="2" class="text-center">NIP</th>
       <th rowspan="2" class="text-center">Nama</th>
       <th rowspan="2" class="text-center">Jabatan</th>
       <th colspan="4" align="center">Presensi ';
	   if ($_SESSION['Level'] !='DOSEN') {
	   echo '<a class="btn btn-primary" href="index.php?m='.$_GET['m'].'&op=Input&jenis=H"  ><i class="fa fa-plus"></i></a>';
	   }
	   echo '</th>
       <th width="140" rowspan="2" align="center">AKSI</th>
     </tr>
     <tr>
	 	<th align="center" width="50">Hadir</th>
	   <th align="center" width="50">Sakit</th>
	   <th align="center" width="50">Ijin</th>
	   <th align="center" width="50">Alpa</th>
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
				<td  align=center>'.$wm['nip'].'</td>
				<td  align=center>'.$wm['gelar_depan'].' '.$wm['nama_dosen'].', '.$wm['gelar_belakang'].'</td>
				<td  align=center>'.viewAplikasi('09', ''.$wm['jabatan_akademik'].'').'</td>
				<td  align=center>'.hitungpresensidosen($prodi, $id, $kelas, $tahun_id, 'H', $idd).'</td>
				<td  align=center>'.hitungpresensidosen($prodi, $id, $kelas, $tahun_id, 'S', $idd).'</td>
				<td  align=center>'.hitungpresensidosen($prodi, $id, $kelas, $tahun_id, 'I', $idd).'</td>
				<td  align=center>'.hitungpresensidosen($prodi, $id, $kelas, $tahun_id, 'A', $idd).'</td>
				<td >
					<a class="btn" href="index.php?m='.$_GET['m'].'&op=detail&idd='.$idd.'" >
					<i class="fa fa-folder"></i></a></a>';
					if ($_SESSION['Level'] !='DOSEN') {
					echo '<a class="btn" href="index.php?m='.$_GET['m'].'&op=edit&idd='.$idd.'" >
					<i class="fa fa-edit"></i></a></a>';
					}
					echo '<a class="btn" href="" clss="btn" onclick="bukajendela(\'cetak.php?m=presensidosen&idd='.$idd.'\'); return false"><i class="fa fa-print"/></i></a>
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
		</table></form>';

 	echo $obj->total;
	echo '<br/>';
	echo $obj->anchors;	
}

function Input() {

global $koneksi_db,$tahun_id;
$prodi = $_SESSION['prodi'];

  $whr = array();
  $ord = '';
  if (($_SESSION['reset_dosen'] != 'Reset') &&
  !empty($_SESSION['kolom_dosen']) && !empty($_SESSION['kunci_dosen'])) {
    $whr[] = "$_SESSION[kolom_dosen] like '%$_SESSION[kunci_dosen]%' ";
    $ord = "order by $_SESSION[kolom_dosen]";
  }
	$whr[] = "kode_prodi='$_SESSION[prodi]'";
	$whr[] = "tahun_id='$tahun_id'";
	$whr[] = "kelas='$_SESSION[kelas]'";
	$whr[] = "id='$_SESSION[kode_mk]'";

	if (!empty($whr)) $strwhr = "where " .implode(' and ', $whr);
  
$sekarang = date('Y-m-d');  
//$jenis = $_REQUEST['jenis'];

//if ($jenis=="H") { $jenisp = "HADIR"; } else { $jenisp = ''.viewAplikasi('59',''.$jenis.'').''; }

echo '
 <form action="" method="post"  class="cmxform" id="form_input" style="width:100%">
 <input type="hidden" name="m" value="'.$_GET['m'].'"/>
        <input type="hidden" name="op" value="saveInput"/>
<fieldset class="ui-widget ui-widget-content ui-corner-all" >
        <legend class="ui-widget ui-widget-header ui-corner-all">Tanggal dan Jam Presensi </legend>		
		<table >
		<tr> 
		<td   align="left" valign="top">Tanggal</td>
		<td><input name="tanggal"  type="text" class="tcal date required"  value="'.$sekarang.'" />
		</td>
		<td   align="left" valign="top">Jam/Waktu :<font color="red"> </font></td>
              <td  >	<select name="jam"  required   >'.opjam($prodi, ''.$wp['jam'].'').'</select>	</td>
                </tr>
	</table>

		
</fieldset>
		
<table width="100%" border="0" align="center" cellpadding="1" cellspacing="0" class="rapor">
   	<thead>
     <tr>
	   <th rowspan="2" width="5%" align="center">No</th>
	   <th rowspan="2" align="center">NIP</th>
       <th rowspan="2" align="center">Nama</th>
       <th rowspan="2" align="center">Jabatan</th>
	
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
	 
	$q = "select  * from view_jadwal $strwhr";
	$pilih = $koneksi_db->sql_query($q);
	$jumlah=$koneksi_db->sql_numrows($pilih);
			
	if ($jumlah > 0){
	$n=0;
		while($wr = $koneksi_db->sql_fetchassoc($pilih)){
		$n++;
		$id = $wr['idd'];
		$wm = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM m_dosen where `idd` = '$id' limit 1 " ));
		echo '<tr bgcolor="#f2f2f2">
				<td  align=center>'.$n.'<input type="hidden" name="idd['.$id.']" value="'.$id.'"/></td> 
				<td  align=left>'.$wm['nip'].'</a></td>
				<td  align=left>'.$wm['gelar_depan'].' '.$wm['nama_dosen'].', '.$wm['gelar_belakang'].'</td>
				<td  align=left>'.viewAplikasi('09', ''.$wm['jabatan_akademik'].'').'</td>
				
				<td  align=center><input type="radio" '.$ck.' name="jenis_presensi['.$id.']" checked="checked" value="H"></td>
				<td  align=center><input type="radio" '.$ck.' name="jenis_presensi['.$id.']" value="A"></td>
				<td  align=center><input type="radio" '.$ck.' name="jenis_presensi['.$id.']" value="I"></td>
				<td  align=center><input type="radio" '.$ck.' name="jenis_presensi['.$id.']" value="S"></td>
			</tr>'; 
		}
		echo '<thead><tr > 
				<th  colspan="8" align=right>
					<input type="submit" class="tombols ui-corner-all" value="Input"/>
					<input type="button" class=tombols ui-corner-all value="Kembali" onClick="window.location = \'index.php?m='.$_GET['m'].'\'"/>
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
global $koneksi_db, $tahun_id;

$hari = gethari($_REQUEST['tanggal']);
	if (is_array($_POST['idd'])) {
		foreach($_POST['idd'] as $key=>$val) {
		$presensi = $_POST['jenis_presensi'][$key];
		
		$jpresensi= $presensi;
			$wi = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM t_jadwal where `idd` = '$val' limit 1 " ));
			$whr[] = "kode_prodi='$wi[kode_prodi]'";
			$whr[] = "tahun_id='$wi[tahun_id]'";
			$whr[] = "kelas='$wi[kelas]'";
			$whr[] = "id='$wi[id]'";
			$ada=$koneksi_db->sql_numrows($koneksi_db->sql_query("SELECT * FROM t_dosen_presensi 
			where `idd` = '$val'
			and tanggal='".$_REQUEST['tanggal']."'
			and jam='".$_REQUEST['jam']."'
			and id='".$_SESSION['kode_mk']."'
			and kelas='".$_SESSION['kelas']."'
			and kode_prodi = '".$wi['kode_prodi']."'
			and tahun_id = '".$tahun_id."' 
			"));
			if ($ada < 1) {
				
			$s = "insert into t_dosen_presensi SET 
					kode_pt='".$wi['kode_pt']."',
					kode_fak='".$wi['kode_fak']."',
					kode_jenjang='".$wi['kode_jenjang']."',
					kode_prodi='".$_SESSION['prodi']."',
					tahun_id='".$tahun_id."',
					kelas='".$_SESSION['kelas']."',
					id='".$_SESSION['kode_mk']."',
					idd='".$val."',
					hari='".$hari."',
					tanggal='".$_REQUEST['tanggal']."',
					jam='".$_REQUEST['jam']."',
					jenis_presensi='".$jpresensi."',
					presensi='1'
					";
					
			  $koneksi_db->sql_query($s);
			  
			  }else{
				  
				  echo "<script>alert('Kehadiran sudah pernah di input !');</script>";
				  break;
			  }
		}
	}
	Daftar();
}
function detail() {
global $koneksi_db, $tahun_id, $kode_mk, $kelas;

$id = $_SESSION['kode_mk'];
$kelas=$_SESSION['kelas'];
$prodi = $_SESSION['prodi'];
$idd =$_REQUEST['idd'];
$w = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM m_dosen where idd='".$idd."' limit 1 " ));

$foto= ($w['foto'] =="" ) ? "images/no_avatar.gif": "images/avatar/".$w['foto']."";
echo '
		<table  border="0" cellspacing="1" cellpadding="1" class="datatable " >
		<thead>
		  <tr>
			<td width="149">NIP</td>
			<td width="436"><b>'.$w['nip'].'</b></td>
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
					
			$whr[] = "a.kode_prodi='$prodi'";
			$whr[] = "a.tahun_id='$tahun_id'";
			$whr[] = "a.kelas='$kelas'";
			$whr[] = "a.id='$id'";
			$whr[] = "a.idd='$idd'";
			if (!empty($whr)) $strwhr = "where " .implode(' and ', $whr);

			$q = "select  a.*, mulai, sampai from t_dosen_presensi a inner join m_jam b on a.jam=b.idj $strwhr order by tanggal";
			
			$pilih = $koneksi_db->sql_query($q);
			$jumlah=$koneksi_db->sql_numrows($pilih);
			
			if ($jumlah > 0){
			$no=0;
				while($w = $koneksi_db->sql_fetchassoc($pilih)){
		
				$no++;
				//$id = $w['iddm'];
				
					echo '<tr >
						<td  align=center>'.$no.'</td> 
						<td align="center">	'.$w['hari'].'</td>
						<td align="center">	'.converttgl($w['tanggal']).'</td>';
						echo'<td align="center">	'.$w['mulai'].' -- '.$w['sampai'].'</td>';
							$query  = $koneksi_db->sql_query ("SELECT * FROM r_kode where aplikasi = '59'  ");
							while( $r = $koneksi_db->sql_fetchrow ($query)) {
							 $ck = ($r[2] ==  $w['jenis_presensi'] ) ? '1' : '';
							echo '<td  align=center>'.$ck.'</td>';
							}
							
	
						echo '</tr>'; 
				}
				echo ' <thead> 	<tr >
					<th  colspan="4" class="text-center">Total Presensi</th>
					
					<th  class="text-center">'.hitungpresensidosen($prodi, $id, $kelas, $tahun_id, 'H', $idd).'</th> 
					<th  class="text-center">'.hitungpresensidosen($prodi, $id, $kelas, $tahun_id, 'S', $idd).'</th>
					<th  class="text-center">'.hitungpresensidosen($prodi, $id, $kelas, $tahun_id, 'I', $idd).'</th>
					<th  class="text-center">'.hitungpresensidosen($prodi, $id, $kelas, $tahun_id, 'A', $idd).'</th>
					</tr></thead>';
			} else {
				echo ' <thead> 	<tr ><th  colspan="8" align=center>Belum ada data</th>	</tr></thead>';
			}
		 
		 echo '</tbody>
			</table>';
			echo '<input type="button" class=tombols ui-corner-all value="Kembali" onClick="window.location = \'index.php?m='.$_GET['m'].'\'"/>';
}

function edit() {
global $koneksi_db, $tahun_id;
$prodi = $_SESSION['prodi'];
$idd =$_REQUEST['idd'];
$id = $_SESSION['kode_mk'];
$kelas=$_SESSION['kelas'];


$w = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM m_dosen where idd='".$idd."' limit 1 " ));
$foto= ($w['foto'] =="" ) ? "images/no_avatar.gif": "images/avatar/".$w['foto']."";
	
	
echo '
		<table  border="0" cellspacing="1" cellpadding="1" class="datatable " >
		<thead>
		  <tr>
			<td width="149">NIP</td>
			<td width="436"><b>'.$w['nip'].'</b></td>
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
		   <th align="center" width="60">Aksi</th>
		  </tr>
		 </thead>
		 <tbody>';
					
			$whr[] = "a.kode_prodi='$prodi'";
			$whr[] = "a.tahun_id='$tahun_id'";
			$whr[] = "a.kelas='$kelas'";
			$whr[] = "a.id='$id'";
			$whr[] = "a.idd='$idd'";
			if (!empty($whr)) $strwhr = "where " .implode(' and ', $whr);

			$q = "select  a.*, mulai, sampai from t_dosen_presensi a inner join m_jam b on a.jam=b.idj $strwhr  order by tanggal";
			$pilih = $koneksi_db->sql_query($q);
			$jumlah=$koneksi_db->sql_numrows($pilih);
			
			if ($jumlah > 0){
			$no=0;
				while($w = $koneksi_db->sql_fetchassoc($pilih)){
				$iddm = $w['iddm'];
				$no++;
				$id = $iddm;
					echo '<tr >
						<td  align=center>'.$no.'</td> 
						<td align="center">	'.$w['hari'].'</td>
						<td align="center">	'.converttgl($w['tanggal']).'</td>';
						echo'<td align="center">	'.$w['mulai'].' -- '.$w['sampai'].'</td>';
						
							$query  = $koneksi_db->sql_query ("SELECT * FROM r_kode where aplikasi = '59'  ");
							while( $r = $koneksi_db->sql_fetchrow ($query)) {
							 $ck = ($r[2] ==  $w['jenis_presensi'] ) ? 'checked' : '';
							echo '<td  align=center><input type="radio" '.$ck.' name="jenis_presensi['.$id.']" value="'.$r[2].'"></td>';
							}
					
					echo '<td><a href="index.php?m='.$_GET['m'].'&op=hapus&iddm='.$iddm.'" >HAPUS</a> </td>';
					
	
						echo '</tr>'; 
	
				}
				echo ' <thead> 	<tr ><th  colspan="7" align=right><input type="submit" name="simpan" class=tombols ui-corner-all value="Update"/>
				<input type="button" class=tombols ui-corner-all value="Kembali" onClick="window.location = \'index.php?m='.$_GET['m'].'\'"/>
				</th>	</tr></thead>';
			} else {
				echo ' <thead> 	<tr ><th  colspan="7" align=center>Belum ada data</th>	</tr></thead>';
			}
		 
		 echo '</tbody>
			</table></form>';
}

function Update() {
global $koneksi_db;
	if (is_array($_POST['jenis_presensi'])) {
		foreach($_POST['jenis_presensi'] as $key=>$val) {
		
			$update = $koneksi_db->sql_query("UPDATE `t_dosen_presensi` SET `jenis_presensi` = '$val'  WHERE `iddm` = '$key'");
		}
	}
	Daftar();	
}

function hapus() {
global $koneksi_db;
			$iddm = $_GET['iddm'] ;
			$koneksi_db->sql_query("delete from `t_dosen_presensi`   WHERE `iddm` = '$iddm'");

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
        <font style="font-size:18px; color:#999999">Presensi Dosen</font><br />
        	<a href="index.php">Home</a> &raquo; <a href="?m='.$_GET['m'].'">Presensi Dosen</a>  &raquo; '.$go.'  
    </div>';
echo '<div class="mainContentCell"><div class="content">';
$go();
echo '</div></div>';

?>
