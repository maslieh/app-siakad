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


global $koneksi_db, $user;
$prodi = $_SESSION['prodi'];

if ($_SESSION['Level']!="MAHASISWA"	) {

//FilterSemester($_GET['m']);
//FilterKelas($_GET['m']);
FilterMahasiswa($_GET['m']);


  $whr = array();
  $ord = '';
  if (($_SESSION['reset_mahasiswa'] != 'Reset') &&
  !empty($_SESSION['kolom_mahasiswa']) && !empty($_SESSION['kunci_mahasiswa'])) {
    $whr[] = "$_SESSION[kolom_mahasiswa] like '%$_SESSION[kunci_mahasiswa]%' ";
    $ord = "order by $_SESSION[kolom_mahasiswa]";
  }
  $whr[] = "status_aktif='A'";
  	if (!empty($_SESSION['prodi'])) $whr[] = "kode_prodi='$_SESSION[prodi]'";
	//if (!empty($_SESSION['kelas'])) $whr[] = "idk='$_SESSION[kelas]'";
	//if (!empty($_SESSION['semester'])) $whr[] = "semester='$_SESSION[semester]'";
	if (!empty($whr)) $strwhr = "where " .implode(' and ', $whr);
} else {
$strwhr = "where idm='$user'";
}
  require('system/pagination_class.php');
$sql = "select * from m_mahasiswa  $strwhr $ord";
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
echo '<div class="table-responsive">
<table class="table table-striped table-bordered table-hover"  >
   	<thead>
     <tr>
	   <th width="5%" align="center">No</th>
	   <th align="center">NIM</th>
       <th align="center">Nama</th>
       <th align="center">Angkatan</th>
	   <th align="center" width="60"></th>
     </tr>
	 </thead>
	 <tbody>';
	 
	 
		while($wr = $koneksi_db->sql_fetchassoc($result)){
		$n++;
		$id = $wr['idm'];
		echo '<tr  >
				<td  >'.$n.'</td> 
				<td  >'.$wr['NIM'].'</td>
				<td  >'.$wr['nama_mahasiswa'].'</td>
				<td   >'.$wr['tahun_masuk'].'</td>
				<td >
					<a  class="btn" href="index.php?m='.$_GET['m'].'&op=detail&idm='.$id.'" ><i class="fa fa-folder"></i></a>
					
					</td>
			</tr>'; 
		}
		
		 echo '</tbody>
		</table></div>';
		
		echo $obj->total;
	echo '<br/>';
	echo $obj->anchors;
	} else {
		echo '<div class="alert alert-danger" >Belum ada Data</div>';
	}
	
 
}

function detail() {


global $koneksi_db, $tahun_id;
$prodi = $_SESSION['prodi'];

    $idm = $_REQUEST['idm'];
	if (empty($idm) || !isset($idm)) { echo "<meta http-equiv='refresh' content='3; url=index.php?m=khs'>"; } 
	$w = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM m_mahasiswa where idm='".$idm."' limit 1 " ));
	$qq = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM t_mahasiswa_krs where idm='".$idm."'" ));
	$foto= ($perguruantinggi['logo'] =="" ) ? "images/no_avatar.gif": "images/".$perguruantinggi['logo']."";

$tanggal = converttgl(date('Y-m-d'));

echo '<div class="table-responsive">
<table class="table table-striped table-bordered table-hover"  >
  <tr>
    <td width="150">NAMA</td>
    <td width="5">:</td>
    <td width="230"><strong>'.$w['nama_mahasiswa'].'</strong></td>
    <td width="30">&nbsp;</td>
 <td>TAHUN AJARAN </td>
    <td>:</td>
    <td><strong>'.strtoupper(NamaTahun($tahun_id, $prodi)).'</strong></td>
  </tr>
  <tr>
    <td>NIM</td>
    <td>:</td>
    <td><strong>'.$w['NIM'].'</strong></td>
    <td>&nbsp;</td>
    <td>PROGRAM STUDI </td>
    <td>:</td>
    <td><strong>'.viewprodi(''.$w['kode_prodi'].'').'</strong></td>
  </tr>
  <tr>
    <td>ANGKATAN</td>
    <td>:</td>
    <td><strong>'.$w['tahun_masuk'].'</strong></td>
    <td>&nbsp;</td>
    
  </tr>

</table></div>';		
		
echo '<br/><div class="table-responsive">
<table class="table table-striped table-bordered table-hover"  >
  <tr>
    <th width="10" class="text-center">NO</th>
    <th width="50" class="text-center">KODE MK </th>
    <th width="410" class="text-center">MATA KULIAH </th>
    <th width="50" class="text-center">KELAS</th>
    <th width="50" class="text-center">KEHADIRAN</th>
    <th width="50" class="text-center">TUGAS</th>
    <th width="50" class="text-center">UTS</th>
    <th width="50" class="text-center">UAS</th>
  </tr>
  <tr>
    <th class="text-center">(a)</th>
    <th class="text-center">(b)</th>
    <th class="text-center">(c)</th>
    <th class="text-center">(d)</th>
    <th class="text-center">(e)</th>
    <th class="text-center">(f)</th>
    <th class="text-center">(g)</th>
    <th class="text-center">(h)</th>
  </tr>';
		$q = "select  k.*, m.* from t_mahasiswa_krs k 
					left outer join m_mata_kuliah m on k.id=m.id 
					where k.kode_prodi='$w[kode_prodi]' and k.idm='$w[idm]' and k.tahun_id='$tahun_id' and k.verifi_pa='1'";
															
			$pilih = $koneksi_db->sql_query($q);
			$jumlah=$koneksi_db->sql_numrows($pilih);
							
			$no=0;
			if ($jumlah > 0){
				while($k = $koneksi_db->sql_fetchassoc($pilih)){
				
				$wtugas = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT nilai FROM t_mahasiswa_nilai where id='".$k['id']."' and idm='".$k['idm']."' and jenis_nilai='TUGAS' and tahun_id='$tahun_id'" ));
				$wuts = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT nilai FROM t_mahasiswa_nilai where id='".$k['id']."' and  idm='".$k['idm']."' and jenis_nilai='UTS' and tahun_id='$tahun_id'" ));
				$wuas = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT nilai FROM t_mahasiswa_nilai where id='".$k['id']."' and  idm='".$k['idm']."' and jenis_nilai='UAS' and tahun_id='$tahun_id'" ));
				$whadir = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT nilai FROM t_mahasiswa_nilai where id='".$k['id']."' and  idm='".$k['idm']."' and jenis_nilai='HADIR' and tahun_id='$tahun_id'" ));	
			
				$no++;
				
				$validasi = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT validasi FROM t_mahasiswa_krs where kode_prodi='".$prodi."'
				and tahun_id='".$tahun_id."' and id='".$k['id']."' and idm='".$k['idm']."' " ));
				if ($validasi['validasi']==1){
				echo '<tr >
					<td  >'.$no.'</td> 
					<td valign="top"  >'.$k['kode_mk'].'</td>
					<td valign="top">'.$k['nama_mk'].'</td>
					<td valign="top">'.$k['kelas'].'</td>
					<td valign="top"  >'.$whadir['nilai'].'</td>
					<td valign="top"  >'.$wtugas['nilai'].'</td>
					<td valign="top"  >'.$wuts['nilai'].'</td>
					<td valign="top" >'.$wuas['nilai'].'</td>
				</tr>'; 
				}else{
					echo '<tr >
					<td  align=center>'.$no.'</td> 
					<td valign="top"  >'.$k['kode_mk'].'</td>
					<td valign="top">'.$k['nama_mk'].'</td>
					<td valign="top">'.$k['kelas'].'</td>
					<td valign="top"  >999</td>
					<td valign="top" >999</td>
					<td valign="top"  >999</td>
					<td valign="top"  >999</td>
				</tr>';
				}
				}  
			} else {
			
		echo '<tr><td colspan=8><div class="alert alert-danger">Belum ambil KRS</div></td></tr>';	
			}

echo '
<table width="100%" border="1" cellspacing="1" cellpadding="1" class=no-style>
</table></div>
<br/>
<font color="RED"> keterangan : 999 ---> Belum ada Nilai atau belum di validasi </font> ';

echo '</td></tr>';
echo'<br/><input type="button" class=tombols ui-corner-all value="Kembali" onClick="window.location = \'index.php?m='.$_GET['m'].'\'"/>';	
}


$semester = BuatSesi('semester');
$kelas = BuatSesi('kelas');
$kolom_mahasiswa = BuatSesi('kolom_mahasiswa');
$kunci_mahasiswa = BuatSesi('kunci_mahasiswa');

if ($_REQUEST['reset_mahasiswa'] == 'Reset') {
  $_SESSION['kolom_mahasiswa'] = '';
  $_SESSION['kunci_mahasiswa'] = '';
}

$go = (empty($_REQUEST['op'])) ? 'Daftar' : $_REQUEST['op']; 
echo '<div id="title" align="right">
        <font style="color:#FF9900; font-size:30px;"><strong>.:</strong></font>
        <font style="font-size:18px; color:#999999">Detail Nilai</font><br />
        	<a href="index.php">Home</a> &raquo; <a href="index.php?m='.$_GET['m'].'">Detail Nilai</a>  &raquo; '.$go.'  
    </div>';
echo '<div class="mainContentCell"><div class="content">';




$go();

echo '</div></div>';