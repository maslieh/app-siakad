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

global $koneksi_db, $user, $tahun_id;
$prodi = $_SESSION['prodi'];

  $whr = array();
  $ord = '';
  if (($_SESSION['reset_mahasiswa'] != 'Reset') &&
  !empty($_SESSION['kolom_mahasiswa']) && !empty($_SESSION['kunci_mahasiswa'])) {
    $whr[] = "$_SESSION[kolom_mahasiswa] like '%$_SESSION[kunci_mahasiswa]%' ";
    $ord = "order by $_SESSION[kolom_mahasiswa]";
	
  }
  	
	
	
	
	$pa = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT kode_prodi, nip FROM m_dosen where idd='".$user."' limit 1 " ));
	$usernya = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT level FROM user where userid='".$user."' limit 1 " ));
	
    
	
	if($usernya['level']=='ADMIN' || $usernya['level']=='PRODI' || $usernya['level']=='ADAK' ){
		$strwhr = "where t_mahasiswa_krs.kode_prodi='$prodi' and m_mahasiswa.status_aktif='A' and t_mahasiswa_krs.tahun_id='$tahun_id'";
		
	}
	else{
		$strwhr = "where t_mahasiswa_krs.kode_prodi='$prodi' and m_mahasiswa.status_aktif='A' and t_mahasiswa_krs.tahun_id='$tahun_id' and m_mahasiswa.pa='".$pa['nip']."'";
	}
	
	
  
echo '<table class="table table-striped table-bordered table-hover"  >
   	<thead>
     <tr>
	   <th width="5%" align="center">No</th>
	   <th align="center">NIM</th>
       <th align="center">Nama</th>
	   <th align="center">Status</th>
       <th align="center">Jml MK Diambil</th>
	   <th align="center">Jml MK Diverifikasi</th>
	   <th align="center">Jml SKS Diambil</th>
	   <th align="center">Verifikasi KRS</th>
     </tr>
	 </thead>
	 <tbody>';
 
	
	
require('system/pagination_class.php');
$sql = "SELECT t_mahasiswa_krs.idm, m_mahasiswa.nim, m_mahasiswa.nama_mahasiswa, m_mahasiswa.status_aktif, count(*) as isikrs, count(case when t_mahasiswa_krs.verifi_pa='1' then 1 else null end) as verifi, sum(sks) as sks FROM t_mahasiswa_krs  inner join m_mahasiswa  on t_mahasiswa_krs.idm=m_mahasiswa.idm ".$strwhr." group by t_mahasiswa_krs.idm 
order by m_mahasiswa.nim";

if(isset($_GET['starting'])){ //starting page
	$starting=$_GET['starting'];
}else{
	$starting=0;
}
$n = $starting;
$recpage = 1000;//jumlah data yang ditampilkan per page(halaman)
$obj = new pagination_class($koneksi_db,$sql,$starting,$recpage);		
$result = $obj->result;
if($koneksi_db->sql_numrows($result)!=0){
	
		while($wr = $koneksi_db->sql_fetchassoc($result)){
		$n++;
		$id = $wr['idm'];
		echo '<tr  >
				<td  >'.$n.'</td> 
				<td   >'.$wr['nim'].'</a></td>
				<td  align=left>'.$wr['nama_mahasiswa'].'</td>
				<td  >'.viewAplikasi('05',''.$wr['status_aktif'].'').'</td>
				<td   >'.$wr['isikrs'].'</td>
				<td   >'.$wr['verifi'].'</td>
				<td  r>'.$wr['sks'].'</td>
				<td  >
				
					<a href="#" class="btn" onclick="window.location.href=\'index.php?m='.$_GET['m'].'&op=verifikrs&idm='.$id.'\';"><i class="fa fa-edit"></i></a>

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

 	echo $obj->total;
	echo '<br/>';
	echo $obj->anchors;	

//FormImport(); 
  
}

function verifikrs_all() {
global $koneksi_db, $user, $prodi, $Mkelas,$Mstatus,$tahun_id;
$prodi = $_SESSION['prodi'];
	$idm = $_REQUEST['idm'];
	if (empty($idm) || !isset($idm)) { echo "<meta http-equiv='refresh' content='3; url=index.php?m=".$_GET['m']."'>"; } 
	$w = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM m_mahasiswa where idm='".$idm."' limit 1 " ));
	$foto= ($w['foto'] =="" ) ? "images/no_avatar.gif": "images/avatar/".$w['foto']."";
		echo'
		<table  border="0" cellspacing="1"class="datatable full" cellpadding="1">
		<thead>
		  <tr>
			<td width="149">NIM</td>
			<td width="436"><b>'.$w['NIM'].'</b></td>
			<td width="37" valign="top" rowspan="5"><img src="'.$fotonya.'" width="80" height="80"></td>
		  </tr>
		  <tr>
			<td>NAMA</td>
			<td><b >'.$w['nama_mahasiswa'].'</b></td>
		  </tr>
		  <tr>
			<td>KOSENTRASI</td>
			<td><b >'.viewkonsentrasi(''.$w['kode_konsentrasi'].'').'</b ></td>
		  </tr>
		  <tr>
			<td>PROGRAM STUDI </td>
			<td><b >'.viewprodi(''.$w['kode_prodi'].'').'</b ></td>
		  </tr>
		  </thead>
		</table>
	  ';
				
					echo '<br/>
					
					<span id=info></span>
					
					<form action="" method="post"  class="" id="form_input" name="form_input" style="width:100%">
					<input type="hidden" name="id" value="'.$id.'"/>
					<input type="hidden" name="m" value="'.$_GET['m'].'"/>
					<input type="hidden" name="op" value="aksi"/>
					<input type="hidden" name="idm" value="'.$idm.'"/>
						<table width="100%" border="0" align="center" cellpadding="1" cellspacing="0" class="datatable setengah2" >
						<thead>	
							<tr>
								
								<th width="100" rowspan="2" valign="middle">Kode</th>
								<th width="250" rowspan="2" valign="middle">Mata Kuliah</th>
								<th width="50" rowspan="2" valign="middle">Kelas</th>
								<th colspan="4" >SKS</td>
								<th colspan="2" >Status Verifikasi</td>
							</tr>
							<tr>
							  <th width="25" >MK</th>
								<th width="25" >T</th>
								<th width="25" >P</th>
								<th width="25" >L</th>
								<th width="25" >Terima</th>
								<th width="25" >Tolak</th>
							</tr>
						</thead>
						 <tbody>';
						 
						 $s = "select  * from view_jadwal  where kode_prodi='$prodi' and tahun_id='$tahun_id'";
						 $s_sks = $koneksi_db->sql_query($s);
						$jumlah=$koneksi_db->sql_numrows($s_sks);
						if ($jumlah > 0){
							$pengampu =array();
							
							while($k = $koneksi_db->sql_fetchassoc($s_sks)){
							$pengampu = explode("|", $k[idd]);
							
							foreach($pengampu as $kk=>$p ){
								$dosenx = viewdosen($p);
							}
							
							$q = "select  * from t_mahasiswa_krs where idm='$idm' and id='$k[id]'";
							if ( $koneksi_db->sql_numrows( $koneksi_db->sql_query( $q ))  < 1 ) {
							
								$dsnx = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM t_dosen_pengajaran where kelas='$kelas_siswa' and id='$k[id]' limit 1 " ));
								$n++;
								$kode[] = $k['id'];
								$kodemk[] = $k['kode_mk'];
								$nama[] = $k['nama_mk'];
								$dosen[] =$dosenx;
								$hari[] = $k['hari'];
								$sks[] = $k['sks_mk'];
								$skst[] = $k['sks_teori'];
								$sksp[] = $k['sks_praktek'];
								$sksl[] = $k['sks_lapangan'];
								
							}
							}
							
							//menampilkan matakuliah ke dalam tabel
							//for($i=0;$i<count($kode);$i++){
							$tkrs = mysql_query("select  a.*, b.* from t_mahasiswa_krs a 
												left outer join m_mata_kuliah b on a.id=b.id 
												where a.idm='$idm' and a.tahun_id='$tahun_id' and a.kode_prodi='$prodi'");
							while($data=mysql_fetch_array($tkrs)){
							$idkrs = $data['idkrs'];
							$Tot=$Tot+$data['sks_mk'];
							$id = $data['idkrs'];
							echo '<tr >
									
									
									<td id=k1'.$i.' valign="top" align="center">'.$data['kode_mk'].'</td>
									<td id=k2'.$i.' valign="top ">'.$data['nama_mk'].'</td>
									<td id=k3'.$i.' valign="top" >'.$data['kelas'].'</td>
									<td id=k4'.$i.' valign="top" align=center>'.$data['sks_mk'].'</td>
									<td id=k5'.$i.' valign="top" align=center>'.$data['sks_teori'].'</td>
									<td id=k6'.$i.' valign="top" align=center>'.$data['sks_praktek'].'</td>
									<td id=k7'.$i.' valign="top" align=center>'.$data['sks_lapangan'].'</td>
									<td align=center><input type="radio" name="verifikasi_pa['.$id.']" value="1" checked></td>
									<td align=center><input type="radio" name="verifikasi_pa['.$id.']" value="0"></td>
									
									</td>
								</tr>'; 
							}
							
							echo '<thead><tr > 
							<th  colspan="7" align=left>Jumlah SKS Yang Dipilih : '.number_format($Tot,0,',','.').' <span id=jsks></span> SKS</th>
							
							
							</tr>
							<tr>
							<th  colspan="9" align=right>
								<input type="button" class="tombols ui-corner-all" value="Kembali" onClick="window.location = \'index.php?m='.$_GET['m'].'\'"/></td>	
								<input type="submit" name="button" class=tombols ui-corner-all value="SIMPAN" />
									
							</th>
							</tr>';
				
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
					
					
					//include "system/get_krs.php"; 

}


function verifikrs() {
global $koneksi_db, $user, $prodi, $Mkelas,$Mstatus,$tahun_id;
$prodi = $_SESSION['prodi'];
	$idm = $_REQUEST['idm'];
	if (empty($idm) || !isset($idm)) { echo "<meta http-equiv='refresh' content='3; url=index.php?m=".$_GET['m']."'>"; } 
	$w = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM m_mahasiswa where idm='".$idm."' limit 1 " ));
	$foto= ($w['foto'] =="" ) ? "images/no_avatar.gif": "images/avatar/".$w['foto']."";
		echo'
		<table  border="0" cellspacing="1"class="datatable full" cellpadding="1">
		<thead>
		  <tr>
			<td width="149">NIM</td>
			<td width="436"><b>'.$w['NIM'].'</b></td>
		  </tr>
		  <tr>
			<td>NAMA</td>
			<td><b >'.$w['nama_mahasiswa'].'</b></td>
		  </tr>
		  <tr>
			<td>KOSENTRASI</td>
			<td><b >'.viewkonsentrasi(''.$w['kode_konsentrasi'].'').'</b ></td>
		  </tr>
		  <tr>
			<td>PROGRAM STUDI </td>
			<td><b >'.viewprodi(''.$w['kode_prodi'].'').'</b ></td>
		  </tr>
		  </thead>
		</table>
	  ';
			
					echo '<br/>
					
					<span id=info></span>
					
					<form action="" method="post"  class="" id="form_input" name="form_input" style="width:100%">
					<input type="hidden" name="id" value="'.$id.'"/>
					<input type="hidden" name="m" value="'.$_GET['m'].'"/>
					<input type="hidden" name="op" value="aksi"/>
					<input type="hidden" name="idm" value="'.$idm.'"/>
						<table width="100%" border="0" align="center" cellpadding="1" cellspacing="0" class="datatable setengah2" >
						<thead>	
							<tr>
								
								<th width="100" rowspan="2" valign="middle">Kode</th>
								<th width="250" rowspan="2" valign="middle">Mata Kuliah</th>
								<th width="50" rowspan="2" valign="middle">Kelas</th>
								<th colspan="4" >SKS</td>
								<th colspan="2" >Status Verifikasi</td>
							</tr>
							<tr>
							  <th width="25" >MK</th>
								<th width="25" >T</th>
								<th width="25" >P</th>
								<th width="25" >L</th>
								<th width="25" >Terima</th>
								<th width="25" >Tolak</th>
							</tr>
						</thead>
						 <tbody>';
						 
						 $s = "select  * from view_jadwal  where kode_prodi='$prodi' and tahun_id='$tahun_id'";
						 $s_sks = $koneksi_db->sql_query($s);
						$jumlah=$koneksi_db->sql_numrows($s_sks);
						if ($jumlah > 0){
							$pengampu =array();
							
							while($k = $koneksi_db->sql_fetchassoc($s_sks)){
							$pengampu = explode("|", $k[idd]);
							
							foreach($pengampu as $kk=>$p ){
								$dosenx = viewdosen($p);
							}
							
							$q = "select  * from t_mahasiswa_krs where idm='$idm' and id='$k[id]'";
							if ( $koneksi_db->sql_numrows( $koneksi_db->sql_query( $q ))  < 1 ) {
							
								$dsnx = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM t_dosen_pengajaran where kelas='$kelas_siswa' and id='$k[id]' limit 1 " ));
								$n++;
								$kode[] = $k['id'];
								$kodemk[] = $k['kode_mk'];
								$nama[] = $k['nama_mk'];
								$dosen[] =$dosenx;
								$hari[] = $k['hari'];
								$sks[] = $k['sks_mk'];
								$skst[] = $k['sks_teori'];
								$sksp[] = $k['sks_praktek'];
								$sksl[] = $k['sks_lapangan'];
								
							}
							}
							
							//menampilkan matakuliah ke dalam tabel
							//for($i=0;$i<count($kode);$i++){
							$tkrs = $koneksi_db->sql_query("select  a.*, b.* from t_mahasiswa_krs a 
												left outer join m_mata_kuliah b on a.id=b.id 
												where a.idm='$idm' and a.tahun_id='$tahun_id' and a.kode_prodi='$prodi'");
							while($data=$koneksi_db->sql_fetchassoc($tkrs)){
							$idkrs = $data['idkrs'];
							$Tot=$Tot+$data['sks_mk'];
							$id = $data['idkrs'];
							$cek = ($data['verifi_pa'] ==  0 ) ? 'checked' : '';
							echo '<tr >
									
									
									<td id=k1'.$i.' valign="top" align="center">'.$data['kode_mk'].'</td>
									<td id=k2'.$i.' valign="top ">'.$data['nama_mk'].'</td>
									<td id=k3'.$i.' valign="top" >'.$data['kelas'].'</td>
									<td id=k4'.$i.' valign="top" align=center>'.$data['sks_mk'].'</td>
									<td id=k5'.$i.' valign="top" align=center>'.$data['sks_teori'].'</td>
									<td id=k6'.$i.' valign="top" align=center>'.$data['sks_praktek'].'</td>
									<td id=k7'.$i.' valign="top" align=center>'.$data['sks_lapangan'].'</td>
									<td align=center><input type="radio" '.$cek.' name="verifikasi_pa['.$id.']" value="1" checked></td>
									<td align=center><input type="radio" '.$cek.' name="verifikasi_pa['.$id.']" value="0" ></td>
									
									</td>
								</tr>'; 
							}
							
							echo '<thead><tr > 
							<th  colspan="7" align=left>Jumlah SKS Yang Dipilih : '.number_format($Tot,0,',','.').' <span id=jsks></span> SKS</th>
							
							
							</tr>
							<tr>
							<th  colspan="9" align=right>								
								<input type="button" class="tombols ui-corner-all" value="Kembali" onClick="window.location = \'index.php?m='.$_GET['m'].'\'"/></td>
								<input type="submit" name="button" class=tombols ui-corner-all value="SIMPAN" />
							</th>
							</tr>';
				
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
					
					
					//include "system/get_krs.php"; 
			

}

function aksi(){
	if($_POST['button'] == 'SIMPAN')
	{
		update();
	}
	 elseif($_POST['button'] == 'Terima Semua')
	{
      verifikrs_all();
	}
	
}

function update() {
global $koneksi_db, $prodi, $tahun_id;

    
	if (is_array($_POST['verifikasi_pa'])) {
		foreach($_POST['verifikasi_pa'] as $key=>$val) {
			if ($val==0) {
				$update = $koneksi_db->sql_query("UPDATE `t_mahasiswa_krs` SET `verifi_pa` = '0' WHERE `idkrs` = '$key'");
			}
			else if ($val==1) {
				$update = $koneksi_db->sql_query("UPDATE `t_mahasiswa_krs` SET `verifi_pa` = '1' WHERE `idkrs` = '$key'");
		}
	}
	}
    
echo "<div  class='error'>Proses Menyimpan Data...</div>";		  
echo "<meta http-equiv='refresh' content='2; url=index.php?m=".$_GET['m']."'>";

Daftar();
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
        <font style="font-size:18px; color:#999999">Verifikasi KRS Mahasiswa</font><br />
        	<a href="?m=home">Home</a> &raquo; <a href="?m='.$_GET['m'].'">Verifikasi KRS Mahasiswa</a>  &raquo; '.$go.'  
    </div>';
echo '<div class="mainContentCell"><div class="content">';
$go();
echo '</div></div> ';


?>

