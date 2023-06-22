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

function pilihmahasiswa($p) {
global $koneksi_db;
$prodi = $_SESSION['prodi'];
	$opsi .= "<option value=\"\" >..::Pilih Mahasiswa::..</option>";
	$query  = $koneksi_db->sql_query ("SELECT * FROM m_mahasiswa where kode_prodi='$prodi' ");
	while( $r = $koneksi_db->sql_fetchassoc ($query)) {
	$cl = ($r['NIM'] == $p) ? "selected" : "";
	$opsi .= "<option value=\"$r[NIM]\" $cl>$r[6]</option>";
	}
	return $opsi;
}

function Daftar() {

//FilterSemester('transkrip');
//FilterKelas('yudisium.daftar');
//FilterPeriodeYudisium('yudisium.daftar');

echo ' <a class="btn" href="index.php?m='.$_GET['m'].'&op=input" class="tombols ui-corner-all">Tambah Nilai Mahasiswa</a><br/> <br/>';
		

global $koneksi_db, $tahun_id;
$prodi = $_SESSION['prodi'];


  $whr = array();
  $ord = 'order by m_mahasiswa.NIM';
	$whr[] =  "t_mahasiswa_ta.kode_prodi='$prodi'";
	$whr[] = "t_mahasiswa_ta.tahun_id='$tahun_id'";
	if (!empty($whr)) $strwhr = "where " .implode(' and ', $whr);


require('system/pagination_class.php');
$sql = "select * from t_mahasiswa_ta inner join m_mahasiswa on t_mahasiswa_ta.idm=m_mahasiswa.idm $strwhr $ord";

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
 <form action="" method="post"  class="cmxform" id="form_input" name="form_input" style="width:100%">
	<input type="hidden" name="m" value="'.$_GET['m'].'"/>
	<input type="hidden" name="op" value="Simpan"/>
			
<table class="table table-striped table-bordered table-hover"  >
   	<thead>
     <tr>
	   <th width="5%" align="center">No</th>
	   <th align="center">NIM</th>
       <th align="center">Nama</th>
       <th align="center">Judul Tugas Akhir</th>
	   <th align="center" width="60">Nilai</th>
	   <th align="center">Edit</th>
     </tr>
	 </thead>
	 <tbody>';
	 
		while($wr = $koneksi_db->sql_fetchassoc($result)){
		$n++;
		$id = $wr['idta'];
		echo '<tr  >
				<td  >'.$n.'</td> 
				<td  align=left>'.$wr['NIM'].'</td>
				<td  align=left>'.$wr['nama_mahasiswa'].'</td>
				<td  align=left>'.$wr['judul_ta'].'</td>
				<td  align=left>'.$wr['nilai'].'</td>
				<td>
					<a class="btn" href="#" onclick="window.location.href=\'index.php?m='.$_GET['m'].'&op=edit&idta='.$id.'\';"><i class="fa fa-edit"></i></a>

				</td>
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

function Input() {
global $koneksi_db, $user;
///// opsi mahasiswa dan bukan mahasiswa

		FilterMahasiswa('mahasiswa');
	  $whr = array();
	  $ord = '';
	  if (($_SESSION['reset_mahasiswa'] != 'Reset') &&
	  !empty($_SESSION['kolom_mahasiswa']) && !empty($_SESSION['kunci_mahasiswa'])) {
		$whr[] = "$_SESSION[kolom_mahasiswa] like '%$_SESSION[kunci_mahasiswa]%' ";
		$ord = "order by $_SESSION[kolom_mahasiswa]";
	  }
		$whr[] = "status_aktif='A'";
		if (!empty($whr)) $strwhr = "where " .implode(' and ', $whr);
		
		$ambilmhs= $koneksi_db->sql_query( "SELECT * FROM m_mahasiswa $strwhr  limit 1 " );


	
	if ( $koneksi_db->sql_numrows( $ambilmhs ) > 0) {
	
		$wm = $koneksi_db->sql_fetchassoc( $ambilmhs );
		$status = $wm['status_aktif'];
		$idm = $wm['idm'];
		$fotonya = ($wm['foto'] =="" ) ? "gambar/no_avatars.gif": "gambar/".$wm['foto']."";
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
			<td>JURUSAN</td>
			<td><b >'.viewkonsentrasi(''.$wm['kode_konsentrasi'].'').'</b ></td>
		  </tr>
		  <tr>
			<td>PROGRAM STUDI </td>
			<td><b >'.viewprodi(''.$wm['kode_prodi'].'').'</b ></td>
		  </tr>
		  </thead>
		</table>
	  ';
	  	if ($status !="A") {
			echo '<div class="error" style="width:70%">Maaf Anda tidak dapat mengajukan Cuti, Status Anda masih '.viewAplikasi('05', ''.$status.'').'</div>';
			
		} else {
			$qta = $koneksi_db->sql_query("SELECT * FROM t_mahasiswa_ta where idm='$idm' limit 1 ");
			$w = $koneksi_db->sql_fetchassoc( $qta );
			echo '  
				<form action="" method="post"  class="cmxform" id="form_input" style="width:100%">
					<input type="hidden" name="id" value="'.$w['idta'].'"/>
					<input type="hidden" name="idm" value="'.$idm.'"/>
					 <input type="hidden" name="m" value="'.$_GET['m'].'"/>
					<input type="hidden" name="op" value="simpan"/>
					<input type="hidden" name="md" value="'.$md.'"/>
				   </br>
					<fieldset class="ui-widget ui-widget-content ui-corner-all" >
						<legend class="ui-widget ui-widget-header ui-corner-all">Pendataan Karya Tulis Akhir </legend>
					   
					   
					   <table   border="0" class="datatable full1">
						<tr>
							<td align="left" valign="top">Judul KTA<font color="red"> *</font></td>
							<td><textarea name="judul_ta" rows=5 cols=50 class=full wrap=virtual></textarea></td>
						</tr>
						<tr>
							<td align="left" valign="top">Tanggal Ujian<font color="red"> *</font></td>
							<td><input name="tanggal_ujian"  type="text" class="tcal date required"   /></td>
						</tr>
						<tr>
							<td align="left" valign="top">Tanggal Yudisium<font color="red"> *</font></td>
							<td><input name="tanggal_yudisium"  type="text" class="tcal date required"   /></td>
						</tr>
						<tr>
							<td  align="left" valign="top">Nilai<font color="red"> *</font></td>
							<td  ><input name="nilai"  type="text" class="required"   /></td>
						</tr>
		
					   
						
					</table> 
					</fieldset>
					
					<br/>
							
							<input type="submit" name="simpan" class=tombols ui-corner-all value="Simpan"/>
							<input type="button" class=tombols ui-corner-all value="Kembali" onClick="window.location = \'index.php?m='.$_GET['m'].'\'"/>
				</form>
			 ';
			
		}
	}
}

function Edit() {
global $koneksi_db, $user;
///// opsi mahasiswa dan bukan mahasiswa

	$idta = $_REQUEST['idta'];
	
			$qta = $koneksi_db->sql_query("SELECT * FROM t_mahasiswa_ta where idta='$idta' limit 1 ");
			$w = $koneksi_db->sql_fetchassoc( $qta );
			echo '  
				<form action="" method="post"  class="cmxform" id="form_input" style="width:100%">
					<input type="hidden" name="id" value="'.$w['idta'].'"/>
					<input type="hidden" name="idm" value="'.$w['idm'].'"/>
					 <input type="hidden" name="m" value="'.$_GET['m'].'"/>
					<input type="hidden" name="op" value="simpan"/>
					<input type="hidden" name="md" value="'.$md.'"/>
				   </br>
					<fieldset class="ui-widget ui-widget-content ui-corner-all" >
						<legend class="ui-widget ui-widget-header ui-corner-all">Pendataan SKRIPSI </legend>
					   
					   
					   <table   border="0" class="datatable full1">
						<tr>
							<td align="left" valign="top">Judul SKRIPSI<font color="red"> *</font></td>
							<td><textarea name="judul_ta" rows=5 cols=50 class=full wrap=virtual>'.$w['judul_ta'].'</textarea></td>
						</tr>
						<tr>
							<td align="left" valign="top">Tanggal Ujian<font color="red"> *</font></td>
							<td><input name="tanggal_ujian"  type="text" class="tcal date required"  value="'.$w['tanggal_ujian'].'" /></td>
						</tr>
						<tr>
							<td align="left" valign="top">Tanggal Yudisium<font color="red"> *</font></td>
							<td><input name="tanggal_yudisium"  type="text" class="tcal date required"  value="'.$w['tanggal_yudisium'].'" /></td>
						</tr>
						<tr>
							<td  align="left" valign="top">Nilai<font color="red"> *</font></td>
							<td  ><input name="nilai"  type="text" class="required"  value="'.$w['nilai'].'" /></td>
						</tr>
		
					   
						
					</table> 
					</fieldset>
					
					<br/>
							
							<input type="submit" name="simpan" class=tombols ui-corner-all value="Simpan"/>
							<input type="button" class=tombols ui-corner-all value="Kembali" onClick="window.location = \'index.php?m='.$_GET['m'].'\'"/>
				</form>
			 ';
			
		
	}

	
function Simpan() {
global $koneksi_db, $prodi, $tahun_id;
$idm= $_REQUEST['idm'];
		
			
				$lihat = $koneksi_db->sql_query("SELECT * FROM t_mahasiswa_ta where idm='$idm' and tahun_id='$tahun_id' " );
				$ada = $koneksi_db->sql_numrows($lihat);
				$krs = $koneksi_db->sql_fetchassoc($lihat);
				
				$lihatkta = $koneksi_db->sql_query("SELECT * FROM t_mahasiswa_krs where idm='$idm' and tahun_id= '$tahun_id' and (id=49 or id=136 or id=250 or id=344 or id=388 or id=411 or id=447 or id=1101 or id=1095)" );
				$adakta = $koneksi_db->sql_numrows($lihatkta);
				$krskta = $koneksi_db->sql_fetchassoc($lihatkta);
				
				
			if 	($adakta > 0){
				if ($ada > 0){
					$update = $koneksi_db->sql_query("UPDATE `t_mahasiswa_ta` 	SET 
						idm ='$idm', 
						judul_ta ='$_REQUEST[judul_ta]', 
						tanggal_ujian ='$_REQUEST[tanggal_ujian]', 
						tanggal_yudisium ='$_REQUEST[tanggal_yudisium]', 
						nilai ='$_REQUEST[nilai]' 
						where  idta = '$krs[idta]' ");
					
					$nl = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT bobot FROM m_nilai
						where kode_prodi='$prodi' and nilai='$_REQUEST[nilai]'" ));
							
					$ip = $nl['bobot'] * $krskta['sks'];	
					
					$update2 = $koneksi_db->sql_query("UPDATE `t_mahasiswa_krs` SET validasi=1, `nilai`='$_REQUEST[nilai]', `bobot`='$nl[bobot]', `ip`='$ip'  WHERE `idkrs` = '$krskta[idkrs]'");
		
				} else {
				$so = "insert into t_mahasiswa_ta SET 
						kode_pt='".$krskta['kode_pt']."',
						kode_fak='".$krskta['kode_fak']."',
						kode_jenjang='".$krskta['kode_jenjang']."',
						kode_konsentrasi='".$krskta['kode_konsentrasi']."',
						kode_prodi='".$krskta['kode_prodi']."',
						tahun_id='".$tahun_id."',
						idm ='$idm', 
						judul_ta ='$_REQUEST[judul_ta]', 
						tanggal_ujian ='$_REQUEST[tanggal_ujian]', 
						tanggal_yudisium ='$_REQUEST[tanggal_yudisium]', 
						nilai ='$_REQUEST[nilai]' 
						";
				$koneksi_db->sql_query($so);
				
				$nl = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT bobot FROM m_nilai
						where kode_prodi='$prodi' and nilai='$_REQUEST[nilai]'" ));
							
					$ip = $nl['bobot'] * $krskta['sks'];	
					
					$update2 = $koneksi_db->sql_query("UPDATE t_mahasiswa_krs SET  validasi=1, nilai='$_REQUEST[nilai]', bobot='$nl[bobot]', ip='$ip'  WHERE idkrs = '$krskta[idkrs]'");
		
				
				}
			}else {
				echo 'BELUM AMBIL KRS SKRIPSI';
			}
			
			
		
		echo "<div  class='error'>Proses Menyimpan Data...</div>";		  
	echo "<meta http-equiv='refresh' content='1; url=index.php?m=".$_GET['m']."'>";
	

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
        <font style="font-size:18px; color:#999999">SKRIPSI Mahasiswa</font><br />
        	<a href="index.php">Home</a> &raquo; <a href="?m='.$_GET['m'].'">Nilai SKRIPSI</a>  &raquo; '.$go.'  
    </div>';
echo '<div class="mainContentCell"><div class="content">';
$go();
echo '</div></div>';


?>
