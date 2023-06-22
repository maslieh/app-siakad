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


global $koneksi_db, $user, $tahun_id, $prodi;
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
$sql = "select * from m_mahasiswa $strwhr $ord";
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
	   <th align="center"  ></th>
     </tr>
	 </thead>
	 <tbody>';
	 
	 
		while($wr = $koneksi_db->sql_fetchassoc($result)){
		$n++;
		$id = $wr['idm'];
		echo '<tr  >
				<td  align=center>'.$n.'</td> 
				<td  align=center>'.$wr['NIM'].'</td>
				<td  align=left>'.$wr['nama_mahasiswa'].'</td>
				<td  align=left>'.$wr['tahun_masuk'].'</td>
				<td >
					<a class="btn" href="index.php?m='.$_GET['m'].'&op=detail&idm='.$id.'" ><i class="fa fa-folder"></i></a>
					<a href="" class="btn" onclick="bukajendela(\'cetak.php?m=khscetak&idm='.$id.'\'); return false"><i class="fa fa-print"></i></a>
					</td>
			</tr>'; 
		}
		
		echo '</tbody>
		</table></div>';
		
	echo $obj->total;
	echo '<br/>';
	echo $obj->anchors;	
	} else {
		 echo '<div class="alert alert-danger">Belum ada Data</th>
			</div>';
	}
	 
 


}

function detail() {
global $koneksi_db, $tahun_id;
$prodi = $_SESSION['prodi'];
    $idm = $_REQUEST['idm'];
	if (empty($idm) || !isset($idm)) { echo "<meta http-equiv='refresh' content='3; url=index.php?m=khs'>"; } 
	$w = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM m_mahasiswa where idm='".$idm."' limit 1 " ));
	//$qq = $koneksi_db->sql_fetchrow($koneksi_db->sql_query( "SELECT * FROM t_mahasiswa_krs where idm='".$idm."'" ));
	$foto= ($perguruantinggi['logo'] =="" ) ? "images/no_avatar.gif": "images/".$perguruantinggi['logo']."";

$tanggal = converttgl(date('Y-m-d'));


echo'
<div class="table-responsive">
<table width="100%"   cellspacing="1" cellpadding="1" class="table table-striped table-bordered table-hover">
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
<table width="100%" border="1" cellspacing="1" cellpadding="1" class=table>
  <tr>
    <th width="10" valign="center" class="text-center">NO</th>
    <th width="50" class="text-center">KODE MK </th>
    <th width="410" class="text-center">MATA KULIAH </th>
    <th width="50" class="text-center">SKS</th>
    <th width="50" class="text-center">Nilai Huruf</th>
    <th width="50" class="text-center">Bobot</th>
    <th width="80" class="text-center">SKS * B </th>
  </tr>';
		$q = "select  k.*, m.* from t_mahasiswa_krs k 
					left outer join m_mata_kuliah m on k.id=m.id
					where k.kode_prodi='$w[kode_prodi]' and k.idm='$w[idm]' and k.tahun_id='$tahun_id'";
															
			$pilih = $koneksi_db->sql_query($q);
			$jumlah=$koneksi_db->sql_numrows($pilih);
			
			$jumlah_mk = jumlah_mk($w['kode_prodi'], $tahun_id, $w['idm'] );
			$jumlah_sks = jumlah_sks($w['kode_prodi'], $tahun_id, $w['idm'] );
			$jumlah_ip = jumlah_ip($w['kode_prodi'], $tahun_id,  $w['idm'] );
			if (!empty($jumlah_ip) && !empty($jumlah_sks)) { $kumulatif = round($jumlah_ip / $jumlah_sks,2); }
			
			//beban studi yad
			
			
			if (substr($tahun_id, 4) == 1){
			$tahun_id_sebelumnya = (substr($tahun_id,0,4)-1).'2';
			}else{
			$tahun_id_sebelumnya = (substr($tahun_id,0,4)).'1';	
			}
			
			if ($jumlah_sks!=null){
				
			
			
			$wsks = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM m_krs
								where kode_prodi='".$w['kode_prodi']."' and `ipk_min` <= '$kumulatif' and  `ipk_max` >= '$kumulatif' limit 1" ));	
							
								
			$boleh = $wsks['jml_sks'];
			}else{
				$jumlah_mk2 = jumlah_mk($w['kode_prodi'], $tahun_id_sebelumnya, $w['idm'] );
				$jumlah_sks2 = jumlah_sks($w['kode_prodi'], $tahun_id_sebelumnya, $w['idm'] );
				$jumlah_ip2 = jumlah_ip($w['kode_prodi'], $tahun_id_sebelumnya,  $w['idm'] );
				if (!empty($jumlah_ip2) && !empty($jumlah_sks2)) { $kumulatif2 = round($jumlah_ip2 / $jumlah_sks2,2); }
				
				$wsks2 = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM m_krs
								where kode_prodi='".$w['kode_prodi']."' and `ipk_min` <= '$kumulatif2' and  `ipk_max` >= '$kumulatif2' limit 1" ));	
							
								
				$boleh = $wsks2['jml_sks'];
			}
			
			$jumlah_sks_semua = jumlah_sks($w['kode_prodi'], $_tahun_id,  $w['idm'] );
			$jumlah_ip_semua = jumlah_ip($w['kode_prodi'], $_tahun_id,  $w['idm'] );
			if (!empty($jumlah_ip_semua) && !empty($jumlah_sks_semua)) { $kumulatif_semua = round($jumlah_ip_semua / $jumlah_sks_semua,2); }

				$pr = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM m_predikat
				where `bobot_min` <= '$kumulatif_semua' and  `bobot_max` >= '$kumulatif_semua' limit 1" ));
				
			$no=0;
			if ($jumlah > 0){
				while($k = $koneksi_db->sql_fetchassoc($pilih)){
				$no++;
				$validasi = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT validasi FROM t_mahasiswa_krs where kode_prodi='".$w['kode_prodi']."'
				and tahun_id='".$tahun_id."' and id='".$k['id']."' and idm='".$k['idm']."' " ));
				
				if ($validasi['validasi']==1){
				echo '<tr >
					<td  align=center>'.$no.'</td> 
					<td>'.$k['kode_mk'].'</td>
					<td valign="top ">'.$k['nama_mk'].'</td>
					<td valign="top align=center">'.$k['sks'].'</td>	
					<td valign="top align=center">'.$k['nilai'].'</td>				
					<td valign="top align=center">'.$k['bobot'].'</td>
					<td valign="top align=center">'.$k['ip'].'</td>
				</tr>'; 
				}else{
				echo '<tr >
					<td  align=center>'.$no.'</td> 
					<td >'.$k['kode_mk'].'</td>
					<td>'.$k['nama_mk'].'</td>
					<td class="text-center">'.$k['sks'].'</td>	
					<td class="text-center">--</td>				
					<td class="text-center">--</td>
					<td class="text-center">--</td>
				</tr>';  	
				}
				}  
			} else {
			
		echo '<tr><td colspan=6> Belum ambil KRS</td></tr>';	
			}
  /// perulangan

echo '<tr><td colspan=7></td></tr>';
echo'
  <tr>
    <th  colspan=3 width="470">JUMLAH</th>
    <td width="50">'.$jumlah_sks.'</td>
    <td width="50">&nbsp;</td>
    <td width="50">&nbsp;</td>
    <td width="80">'.$jumlah_ip.'</td>
  </tr>
  ';
echo '<tr><td colspan=7></td></tr>';

if ($validasi['validasi']==1){
echo'
  <tr>
    <th colspan=3 >INDEKS PRESTASI SEMESTER '.strtoupper(viewsmtr(''.$qq['semester']).'').'</th>
    <td colspan=4>'.$kumulatif.'</td>
  </tr>
  <tr>
    <th colspan=3>INDEKS PRESTASI KUMULATIF </th>
    <td colspan=4>'.$kumulatif_semua.'</td>
  </tr>
  <tr>
    <th colspan=3>Beban SKS Semester Yang Akan Datang</th>
    <td colspan=4>'.$boleh.'</td>
  </tr>
</table>';
}else{
echo'
  <tr>
    <th colspan=3 >INDEKS PRESTASI SEMESTER '.strtoupper(viewsmtr(''.$qq['semester']).'').'</th>
    <td colspan=4>--</td>
  </tr>
  <tr>
    <th colspan=3>INDEKS PRESTASI KUMULATIF </th>
    <td colspan=4>--</td>
  </tr>
  <tr>
    <th colspan=3>Beban SKS Semester Yang Akan Datang</th>
    <td colspan=4>--</td>
  </tr>
</table></div>';	
}

echo '</td></tr>
<br/>
<font color="RED"> keterangan : -- = Belum ada Nilai atau belum di validasi </font> ';

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
        <font style="font-size:18px; color:#999999">Kartu Hasil Studi</font><br />
        	<a href="index.php">Home</a> &raquo; <a href="index.php?m='.$_GET['m'].'">KHS</a>  &raquo; '.$go.'  
    </div>';
echo '<div class="mainContentCell"><div class="content">';




$go();

echo '</div></div>';


?>