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
	global $koneksi_db, $user, $tahun_id;
	$prodi = $_SESSION['prodi'];

	$pa = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT kode_prodi, nip FROM m_dosen where idd='".$user."' limit 1 " ));
	$usernyasiapa = substr($user,0,1);
	
	if($usernyasiapa=='A'){
		DaftarADMIN();
		
	}
	else{
		DaftarMHSPA();
		
	}
}




function DaftarMHSPA() {
global $koneksi_db, $user;
if ($_SESSION['Level']!="MAHASISWA"	) {
//FilterSemester('transkrip');
	//FilterKelas($_GET['m']);
	FilterMahasiswa($_GET['m']);
	
	
	global $koneksi_db;
	$prodi = $_SESSION['prodi'];
	
	$pa = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT nip FROM m_dosen where idd='".$user."' limit 1 " ));
	
	$whr = array();
	$ord = '';
	if (($_SESSION['reset_mahasiswa'] != 'Reset') &&
	!empty($_SESSION['kolom_mahasiswa']) && !empty($_SESSION['kunci_mahasiswa'])) {
	$whr[] = "$_SESSION[kolom_mahasiswa] like '%$_SESSION[kunci_mahasiswa]%' ";
	$ord = "order by $_SESSION[kolom_mahasiswa]";
	}
	$whr[] = "status_aktif='A'"; // L = LULUS
	if (!empty($_SESSION['prodi'])) $whr[] = "kode_prodi='$_SESSION[prodi]'";
	if (!empty($user[nip])) $whr[] = "PA='$pa[nip]'";
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

echo '
<div class="table-responsive">
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
				<td  >'.$n.'</td> 
				<td   >'.$wr['NIM'].'</td>
				<td  align=left>'.$wr['nama_mahasiswa'].'</td>
				<td  align=left>'.$wr['tahun_masuk'].'</td>
				<td >
					<a class="btn" href="index.php?m='.$_GET['m'].'&op=detail&idm='.$id.'"><i class="fa fa-folder"></i></a>
					<a class="btn" href="" onclick="bukajendela(\'cetak.php?m=transkrip&idm='.$id.'\'); return false"><i class="fa fa-print"></i></a>
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

function DaftarADMIN() {
global $koneksi_db, $user;
if ($_SESSION['Level']!="MAHASISWA"	) {
//FilterSemester('transkrip');
	//FilterKelas($_GET['m']);
	FilterMahasiswa($_GET['m']);
	
	
	global $koneksi_db;
	$prodi = $_SESSION['prodi'];
	
	$whr = array();
	$ord = '';
	if (($_SESSION['reset_mahasiswa'] != 'Reset') &&
	!empty($_SESSION['kolom_mahasiswa']) && !empty($_SESSION['kunci_mahasiswa'])) {
	$whr[] = "$_SESSION[kolom_mahasiswa] like '%$_SESSION[kunci_mahasiswa]%' ";
	$ord = "order by $_SESSION[kolom_mahasiswa]";
	}
	$whr[] = "status_aktif='A'"; // L = LULUS
	if (!empty($_SESSION['prodi'])) $whr[] = "kode_prodi='$_SESSION[prodi]'";
	//if (!empty($_SESSION['kelas'])) $whr[] = "masuk_kelas='$_SESSION[kelas]'";
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
echo '
<div class="table-responsive">
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
				<td  >'.$n.'</td> 
				<td   >'.$wr['NIM'].'</td>
				<td   >'.$wr['nama_mahasiswa'].'</td>
				<td >'.$wr['tahun_masuk'].'</td>
				<td >
					<a class="btn" href="index.php?m='.$_GET['m'].'&op=detail&idm='.$id.'"><i class="fa fa-folder"></i></a>
					<a class="btn"  href="" onclick="bukajendela(\'cetak.php?m=transkrip&idm='.$id.'\'); return false"><i class="fa fa-print"></i></a>
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

global $koneksi_db, $tahun_id, $programstudi;
    $idm = $_REQUEST['idm'];
	if (empty($idm) || !isset($idm)) { echo "<meta http-equiv='refresh' content='3; url=index.php?m=transkrip'>"; } 
	$w = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM m_mahasiswa where idm='".$idm."' and aktif='1' limit 1 " ));
	$qq = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM t_mahasiswa_krs where idm='".$idm."'" ));
	$ta = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM t_mahasiswa_ta where idm='".$idm."' limit 1 " ));
	
	$logo= ($perguruantinggi['logo'] =="" ) ? "images/no_avatar.gif": "images/".$perguruantinggi['logo']."";
	$foto= ($w['foto'] =="" ) ? "gambar/no_avatars.gif": "gambar/".$w['foto']."";
	
	
	$jumlah_mk = jumlah_mk($w['kode_prodi'], '', $w['idm'] );
	$jumlah_sks = jumlah_sks($w['kode_prodi'],  '', $w['idm'] );
	$jumlah_ip = jumlah_ip($w['kode_prodi'], '', $w['idm'] );
	if (!empty($jumlah_ip) && !empty($jumlah_sks)) { $kumulatif = round($jumlah_ip / $jumlah_sks,2); }

$pr = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM m_predikat
				where `bobot_min` <= '$kumulatif' and  `bobot_max` >= '$kumulatif' limit 1" ));
				
$tanggal = converttgl(date('Y-m-d'));

echo'
		<table  border="0" cellspacing="1" class="datatable " cellpadding="1">
		<thead>
		  <tr>
			<td width="149">NIM</td>
			<td width="436"><b>'.$w['NIM'].'</b></td>
			<td width="37" valign="top" rowspan="5"><img src="'.$foto.'" width="90" height="120"></td>
		  </tr>
		  <tr>
			<td>NAMA MAHASISWA</td>
			<td><b >'.$w['nama_mahasiswa'].'</b></td>
		  </tr>
		  <tr>
			<td>Tempat, Tanggal Lahir </td>
			<td><strong>'.strtoupper($w['tempat_lahir']).','.strtoupper(converttgl($w['tanggal_lahir'])).'</strong></td>
		  </tr>
		  <tr>
			<td>PROGRAM STUDI </td>
			<td><b ><strong>'.strtoupper(viewAplikasi('04', ''.$w['kode_jenjang'].'')).' '.strtoupper(viewprodi(''.$w['kode_prodi'].'')).'</strong></b ></td>
		  </tr>
		  <tr>
			<td>KONSENTRASI</td>
			<td><b >'.viewkonsentrasi(''.$w['kode_konsentrasi'].'').'</b ></td>
		  </tr>
		  <tr>
			<td>Tanggal Kelulusan </td>
			<td><strong>'.strtoupper(converttgl($w['tanggal_lulus'])).'</strong></td>
		  </tr>
		  </thead>
		</table>';
		
		
echo '
<br/>
<div class="table-responsive">
<table width="100%" border="1" cellspacing="1" cellpadding="1" class=table>
  <tr>
    <th width="10" rowspan="2" class="text-center">NO</th>
    <th width="150" rowspan="2" class="text-center">KODE MK </th>
    <th width="310" rowspan="2" class="text-center">MATA KULIAH </th>
    <th width="50" rowspan="2" class="text-center">SKS</th>
    <th colspan="3" class="text-center">PRESTASI</th>
  </tr>
  <tr>
    <th width="50" class="text-center">AM</th>
    <th width="50" class="text-center">HM</th>
    <th width="80" class="text-center">M </th>
  </tr>
  <tr>
    <td align=center>(a)</td>
    <td align=center>(b)</td>
    <td align=center>(c)</td>
    <td align=center>(d)</td>
    <td align=center>(e)</td>
    <td align=center>(f)</td>
    <td align=center>(g)</td>
  </tr>';

						
					$qkrs = "select  k.*, m.*, min(k.nilai) from t_mahasiswa_krs k 
							left outer join m_mata_kuliah m on k.id=m.id
							where k.idm='$qq[idm]' and k.validasi='1' and k.verifi_pa='1' 
							GROUP BY k.idm, k.id 
							ORDER BY m.nama_mk";
				
					
					$pkrs = $koneksi_db->sql_query($qkrs);
					$jkrs= $koneksi_db->sql_numrows($pkrs);
					$no=0;
					if ($jkrs > 0){
					// perulanagn makul
						while($mk = $koneksi_db->sql_fetchassoc($pkrs)){
						$no++;
						
						echo '<tr >
							<td  align=center>'.$no.'</td> 
							<td  align="center">'.$mk['kode_mk'].'</td>
							<td >'.$mk['nama_mk'].'</td>';
							
							$qn = "select  sks, max(bobot) as bobot, min(nilai) as nilai from t_mahasiswa_krs
							where kode_prodi='$w[kode_prodi]' and id='$mk[id]' and idm='$w[idm]' and validasi='1' and verifi_pa='1'";
							$pn = $koneksi_db->sql_query($qn);
							$jn= $koneksi_db->sql_numrows($pn);
						/// perulanagn nilai makul
							if ($jn > 0){
								$wn = $koneksi_db->sql_fetchassoc($pn);
								$wn2 = $koneksi_db->sql_fetchassoc($pn);
								$M = round($wn['bobot'] * $wn['sks'],2);
								echo '<td  align="center">'.$wn['sks'].'</td>
								<td  align="center">'.$wn['bobot'].'</td>
									<td  align="center">'.$wn['nilai'].'</td>
									<td  align="center">'.$M.'</td>
									</tr>';
							
							} else {
								echo '
								<td  align="center">-</td>
								<td  align="center">-</td>
									<td  align="center">-</td>
									<td  align="center">-</td>
									</tr>'; 
							}
							
							
						}
						
					}
					
				$total = $M + $total - $M;	
					
				//}  
				
				//$total = count($total);
			//} else {
			
		//echo '<tr><td colspan=7> Belum Lulus</td></tr>';	
			//}
  /// perulangan

echo '<tr><td colspan=7></td></tr>';
echo'
  <tr>
    <td  colspan=3 width="470">JUMLAH</td>
    <td width="50" align=center>'.$jumlah_sks.'</td>
    <td width="50">&nbsp;</td>
    <td width="50">&nbsp;</td>
    <td width="80" align=center><b>'.$jumlah_ip.'</b></td>
  </tr> ';
echo '<tr><td colspan=7></td></tr>';
echo'
  <tr>
    <td colspan=3 >Jumlah Kredit Kumulatif</td>
    <td colspan=4><b>: '.$jumlah_sks.' SKS</b></td>
  </tr>
  <tr>
    <td colspan=3>Indeks Prestasi Kumulatif</td>
    <td colspan=4>: '.$kumulatif.'</td>
  </tr>
  <tr>
    <td colspan=3>PREDIKAT</td>
    <td colspan=4>: '.$pr['predikat'].'</td>
  </tr>
  <tr>
    <td colspan=3 valign=top>Judul Tugas Akhir </td>
    <td colspan=5>: '.strtoupper($ta['judul_ta']).'</td>
  </tr>
</table>';
/*
echo '
<table width="100%" border="1" cellspacing="1" cellpadding="1" class=no-style>
  <tr>
    <td width="50%" align=center valign=midle height=150>
	Ketua Program Studi<br/>
	'.viewprodi($prodi).'
	</td>
    <td align=center valign=midle height=150>
	'.viewkota($perguruantinggi['kode_kota']).', '.$tanggal.'<br/>
	Kepala Sub Bagian Administrasi Akademik<br/>
	'.$perguruantinggi['nama_pt'].'
	</td>
  </tr>
  <tr align=center valign=bottom>
    <td>
	'.viewdosen($programstudi['ketua_prodi']).'
	</td>
    <td align=center>
	'.viewlektorkepala().'
	</td>
  </tr>
</table>';
*/
echo '<table>
<tr><td colspan=6><u><b>Keterangan :</b></u></td></tr>
<tr><td colspan=3 width=200><u><b>Predikat IPK :</b></u></td><td colspan=3><u><b>Prestasi :</b></u></td></tr>
<tr><td>3.50-4.00</td><td>=</td><td>Terpuji</td><td>AM</td><td>:</td><td>Angka Mutu</td></tr>
<tr><td>3.25-3.49</td><td>=</td><td>Sangat Memuaskan</td><td>HM</td><td>:</td><td>Huruf Mutu</td></tr>
<tr><td>3.00-3.24</td><td>=</td><td>Memuaskan</td><td>M</td><td>:</td><td>Mutu</td></tr>
<tr><td>2.75-2.99</td><td>=</td><td>Cukup</td><td></td><td></td><td></td></tr>
<tr><td>2.50-2.74</td><td>=</td><td>Sedang</td><td></td><td></td><td></td></tr>

</table></div>
';

echo '</td></tr>';
echo '<input type="button" class=tombols ui-corner-all value="Kembali" onClick="window.location = \'index.php?m='.$_GET['m'].'\'"/>';
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
        <font style="font-size:18px; color:#999999">Transkrip Nilai</font><br />
        	<a href="index.php">Home</a> &raquo; <a href="index.php?m='.$_GET['m'].'">Transkrip Nilai</a>  &raquo; '.$go.'  
    </div>';
echo '<div class="mainContentCell"><div class="content">';
$go();
echo '</div></div>';

?>