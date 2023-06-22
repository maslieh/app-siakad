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


function hapus() {
echo "<div class=error>Ma'af.. Data tidak dapat dihapus, hanya bisa diedit</div>";
Daftar();
}

function pilihmahasiswa($p) {
global $koneksi_db;
$prodi = $_SESSION['prodi'];
	$opsi .= "<option value=\"\" >..::Pilih Mahasiswa::..</option>";
	$query  = $koneksi_db->sql_query ("SELECT * FROM m_mahasiswa where kode_prodi='$prodi' ");
	while( $r = $koneksi_db->sql_fetchrow ($query)) {
	$cl = ($r['NIM'] == $p) ? "selected" : "";
	$opsi .= "<option value=\"$r[NIM]\" $cl>$r[6]</option>";
	}
	return $opsi;
}

function Daftar() {


FilterMahasiswa('mahasiswa');



global $koneksi_db, $prodi, $tahun_id;
$prodi = $_SESSION['prodi'];

  $whr = array();
  $ord = '';
  if (($_SESSION['reset_mahasiswa'] != 'Reset') &&
  !empty($_SESSION['kolom_mahasiswa']) && !empty($_SESSION['kunci_mahasiswa'])) {
    $whr[] = "$_SESSION[kolom_mahasiswa] like '%$_SESSION[kunci_mahasiswa]%' ";
    $ord = "order by $_SESSION[kolom_mahasiswa]";
	//$ord = ($_SESSION['kolom_mahasiswa'] =="" ) ? "NIM": $_SESSION['kolom_mahasiswa'];
	
  }
	$whr[] =  "t_mahasiswa_krs.kode_prodi='$prodi'";
	$whr[] = "t_mahasiswa_krs.tahun_id='$tahun_id'";
	$whr[] = "m_mahasiswa.status_aktif='A'";
	$whr[] = "(id=133 or id=636 or id=655 or id=678 or id=350 or id=387 or id=596 or id=825 or id=852 or id=1014 or id=1077 or id=1000)";
  	if (!empty($whr)) $strwhr = "where " .implode(' and ', $whr);
  	//$strwhr = "";
  
  
  require('system/pagination_class.php');
$sql = "select * from t_mahasiswa_krs  inner join m_mahasiswa  on t_mahasiswa_krs.idm=m_mahasiswa.idm $strwhr $ord";

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
	   <th width="5%" align="center">No</th>
	   <th align="center">NIM</th>
       <th align="center">Nama</th>
	   <th align="center">Status</th>
       <th align="center">Nilai OJT</th>
	   <th align="center">Edit</th>
     </tr>
	 </thead>
	 <tbody>';
	 
	 
		while($wr = $koneksi_db->sql_fetchassoc($result)){
		$n++;
		$id = $wr['idkrs'];
		echo '<tr  >
				<td   >'.$n.'</td> 
				<td r>'.$wr['NIM'].'</a></td>
				<td  >'.$wr['nama_mahasiswa'].'</td>
				<td  >'.viewAplikasi('05',''.$wr['status_aktif'].'').'</td>
				<td   >'.$wr['nilai'].'</td>
				<td   >
				
					<a class="btn" href="#" onclick="window.location.href=\'index.php?m='.$_GET['m'].'&op=edit&idkrs='.$id.'\';">
					<i class="fa fa-folder"></i></a>

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

function edit() {
global $koneksi_db, $prodi, $tahun_id;
	$idkrs = $_REQUEST['idkrs'];
	$w = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT nim, nama_mahasiswa, kode_mk, nama_mk, nilai, a.sks FROM t_mahasiswa_krs a 
															join m_mahasiswa b on a.idm=b.idm join m_mata_kuliah c on a.id=c.id 
														    where idkrs='".$idkrs."' limit 1 " ));
		echo'
		<form action="" method="post"  class="cmxform" id="form_input" name="form_input" style="width:100%">
				<input type="hidden" name="id" value="'.$id.'"/>
				<input type="hidden" name="m" value="'.$_GET['m'].'"/>
				<input type="hidden" name="op" value="Simpan"/>
				
			
			
		<table class="table table-striped table-bordered table-hover"  >
			<thead>
			 <tr>
			   <th width="5%" align="center">No.</th>
			   <th width="100" align="center">NIM</th>
			   <th width="300" align="center">Nama Mahasiswa</th>
			   <th width="50" align="center">Kode MK OJT</th>
			   <th width="30" align="center">SKS</th>
			   <th align="center" width="20">Nilai</th>
			 </tr>
			 </thead>
			 <tbody>';
		echo '<tr >
							<td  >'.$no.'</td> 
							<td valign="top"  >'.$w['nim'].'</td>
							<td valign="top ">'.$w['nama_mahasiswa'].'</td>
							<td valign="top">'.$w['kode_mk'].'</td>
							<td valign="top">'.$w['sks'].'</td>
							<td valign="top"  >
								<input name="nilai['.$idkrs.']"  type="text" class="" id="" value="'.$w['nilai'].'" />
							</td>
						</tr>'; 
					
					echo ' <thead> 	<tr ><th  colspan="5" align=right>
					<input type="submit" name="simpan" class=tombols ui-corner-all value="SIMPAN"/>
					<input type="button" class=tombols ui-corner-all value="Kembali" onClick="window.location = \'index.php?m='.$_GET['m'].'\'"/>
					</th>	</tr></thead>';
				
			 
			 echo '</tbody>
				</table></form>';

}

function simpan(){
global $koneksi_db, $prodi, $tahun_id, $kode_mk;
		
		
		
		if (is_array($_POST['nilai']) ) {
		foreach($_POST['nilai'] as $key=>$val) {
				$w = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query("SELECT sks FROM `t_mahasiswa_krs` where `idkrs` = '$key' " ));
				
				$nl = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT bobot FROM m_nilai
				where kode_prodi='$prodi' and nilai='$val'" ));
			}		
				$ip = $nl['bobot'] * $w['sks'];
				$update = $koneksi_db->sql_query("UPDATE `t_mahasiswa_krs` SET  `nilai`='$val', `bobot`='$nl[bobot]', `ip`='$ip'  WHERE `idkrs` = '$key'");
		}
		daftar();
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
        <font style="font-size:18px; color:#999999">Input Nilai OJT</font><br />
        	<a href="?m=home">Home</a> &raquo; <a href="?m='.$_GET['m'].'">Nilai OJT</a>  &raquo; '.$go.'  
    </div>';
echo '<div class="mainContentCell"><div class="content">';
$go();
echo '</div></div>';

?>

