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
	while( $r = $koneksi_db->sql_fetchrow ($query)) {
	$cl = ($r['NIM'] == $p) ? "selected" : "";
	$opsi .= "<option value=\"$r[NIM]\" $cl>$r[6]</option>";
	}
	return $opsi;
}

function Daftar() {
?>
<SCRIPT type=text/javascript>
///////////// pilih kelas dan yang sukses ////////////////////	
function submitPilihMHS(v,m,f){
        if (v == true)
            //window.location="index.php?m="+f.m+'&dariNIM='+f.dariNIM+'&sampaiNIM='+f.sampaiNIM;
			bukajendela("cetak.php?m="+f.m+'&dariNIM='+f.dariNIM+'&sampaiNIM='+f.sampaiNIM);
        else
            return true;
}
    
function pilihMHS(action){
var pesan = 'Pilih Mahasiswa : <br /> <input type="hidden" id="m" name="m" value="'+action+'">Dari NIM : <input type=text class="required" name="dariNIM" id="dariNIM"> Sampai NIM : <input type=text class="required" name="sampaiNIM" id="sampaiNIM">';
	 $.prompt(pesan,{
	   callback: submitPilihMHS,
			buttons: {
				Ok: true,
				Batal : false
			}
	  });
}
</SCRIPT>

<?php


global $koneksi_db, $user;
$prodi = $_SESSION['prodi'];

if ($_SESSION['Level']!="MAHASISWA"	) {

//FilterSemester($_GET['m']);
//FilterKelas($_GET['m']);
FilterMahasiswa($_GET['m']);
#echo"<input type=button  class=\"tombols ui-corner-all\" value='Cetak KST' onclick=\"pilihMHS('kstcetak'); return false\" >";


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

echo '<div class="table-responsive">
<table class="table table-striped table-bordered table-hover"  >
   	<thead>
     <tr>
	   <th width="5%" align="center">No</th>
	   <th align="center">NIM</th>
       <th align="center">Nama</th>
       <th align="center">Angkatan</th>
	   <th align="center" width="30"></th>
     </tr>
	 </thead>
	 <tbody>';
	 
	 
	
	
require('system/pagination_class.php');
$sql = "select * from m_mahasiswa $strwhr ";
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
		echo '<tr  >
				<td  >'.$n.'</td> 
				<td  >'.$wr['NIM'].'</td>
				<td   >'.$wr['nama_mahasiswa'].'</td>
				<td   >'.$wr['tahun_masuk'].'</td>
				<td >
					<a href="" class="btn" onclick="bukajendela(\'cetak.php?m=kstcetak&idm='.$id.'\'); return false">
					<i class="fa fa-print"></i></a>
					</td>
			</tr>'; 
		}
	} else {
		 echo '
		 <thead><tr > 
			<th  colspan="8" align=center>Belum ada Data</th>
			</tr>
		</thead>';
	}
	 echo '</tbody>
		</table></div>';

 	echo $obj->total;
	echo '<br/>';
	echo $obj->anchors;	


}
/*	
function detail() {

echo '<table width="700"  border="0" cellspacing="1" cellpadding="1" ><tr><td align=left>';

global $koneksi_db, $tahun_id, $programstudi;
    $idm = $_REQUEST['idm'];
	if (empty($idm) || !isset($idm)) { echo "<meta http-equiv='refresh' content='3; url=index.php?m=khs'>"; } 
	$w = $koneksi_db->sql_fetchrow($koneksi_db->sql_query( "SELECT * FROM m_mahasiswa where idm='".$idm."' limit 1 " ));
	$qq = $koneksi_db->sql_fetchrow($koneksi_db->sql_query( "SELECT * FROM t_mahasiswa_krs where idm='".$idm."'" ));
	$foto= ($perguruantinggi['logo'] =="" ) ? "images/no_avatar.gif": "images/".$perguruantinggi['logo']."";

$tanggal = converttgl(date('Y-m-d'));


echo'<table width="100%" border="1" cellspacing="1" cellpadding="1" class=no-style>
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
    <td>SEMESTER</td>
    <td>:</td>
    <td><strong>'.strtoupper(viewsmtr(''.$qq['semester']).'').'</strong></td>
  </tr>

</table>';		
		
echo '<br/>
<table width="100%" border="1" cellspacing="1" cellpadding="1" class=rapor>
  <tr>
    <th width="10">NO</th>
    <th width="50">KODE MK </th>
    <th width="410" >MATA KULIAH </th>
    <th width="50">SKS</th>
    <th width="50">NILAI</th>
    <th width="50">HURUF</th>
    <th width="80">d x e </th>
  </tr>
  <tr>
    <th>(a)</th>
    <th>(b)</th>
    <th>(c)</th>
    <th>(d)</th>
    <th>(e)</th>
    <th>(f)</th>
    <th>(g)</th>
  </tr>';
		$q = "select  k.*, m.* from t_mahasiswa_krs k 
					left outer join m_mata_kuliah m on k.id=m.id
					where k.kode_prodi='$qq[kode_prodi]' and k.semester='$qq[semester]' and k.idm='$qq[idm]'";
															
			$pilih = $koneksi_db->sql_query($q);
			$jumlah=$koneksi_db->sql_numrows($pilih);
			
			$jumlah_mk = jumlah_mk($w['kode_prodi'], $tahun_id, $w['semester'], $w['idm'] );
			$jumlah_sks = jumlah_sks($w['kode_prodi'], $tahun_id, $w['semester'], $w['idm'] );
			$jumlah_ip = jumlah_ip($w['kode_prodi'], $tahun_id, $w['semester'], $w['idm'] );
			if (!empty($jumlah_ip) && !empty($jumlah_sks)) { $kumulatif = round($jumlah_ip / $jumlah_sks,2); }
			
			$jumlah_sks_semua = jumlah_sks($w['kode_prodi'], '', '', $w['idm'] );
			$jumlah_ip_semua = jumlah_ip($w['kode_prodi'], '', '', $w['idm'] );
			if (!empty($jumlah_ip_semua) && !empty($jumlah_sks_semua)) { $kumulatif_semua = round($jumlah_ip_semua / $jumlah_sks_semua,2); }

				$pr = $koneksi_db->sql_fetchrow($koneksi_db->sql_query( "SELECT * FROM m_predikat
				where `bobot_min` <= '$kumulatif_semua' and  `bobot_max` >= '$kumulatif_semua' limit 1" ));
				
			$no=0;
			if ($jumlah > 0){
				while($k = $koneksi_db->sql_fetchrow($pilih)){
				$no++;
				echo '<tr >
					<td  align=center>'.$no.'</td> 
					<td valign="top" align="center">'.$k['kode_mk'].'</td>
					<td valign="top ">'.$k['nama_mk'].'</td>
					<td valign="top align=center">'.$k['sks'].'</td>
					<td valign="top align=center">'.$k['bobot'].'</td>
					<td valign="top align=center">'.$k['nilai'].'</td>
					<td valign="top align=center">'.$k['ip'].'</td>
				</tr>'; 
				}  
			} else {
			
		echo '<tr><td colspan=7> Belum ambil KRS</td></tr>';	
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
  <tr>
    <th colspan=3>KUMULATIF SEMESTER </th>
    <td width="50"></td>
    <td width="50">&nbsp;</td>
    <td width="50">&nbsp;</td>
    <td width="80"></td>
  </tr>
  <tr>
    <th colspan=3>KUMULATIF SEMESTER '.strtoupper(viewsmtr(''.$qq['semester']).'').'</th>
    <td>'.$jumlah_sks_semua.'</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>';
echo '<tr><td colspan=7></td></tr>';
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
    <th colspan=3>PREDIKAT</th>
    <td colspan=4>'.$pr['predikat'].'</td>
  </tr>
</table>';

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

echo '</td></tr></table>';
echo'<br/><input type="button" class=tombols ui-corner-all value="Kembali" onClick="window.location = \'index.php?m='.$_GET['m'].'\'"/>';	
}
*/


$kolom_mahasiswa = BuatSesi('kolom_mahasiswa');
$kunci_mahasiswa = BuatSesi('kunci_mahasiswa');

if ($_REQUEST['reset_mahasiswa'] == 'Reset') {
  $_SESSION['kolom_mahasiswa'] = '';
  $_SESSION['kunci_mahasiswa'] = '';
}

$go = (empty($_REQUEST['op'])) ? 'Daftar' : $_REQUEST['op']; 
echo '<div id="title" align="right">
        <font style="color:#FF9900; font-size:30px;"><strong>.:</strong></font>
        <font style="font-size:18px; color:#999999">Kartu Studi Tetap</font><br />
        	<a href="index.php">Home</a> &raquo; <a href="index.php?m='.$_GET['m'].'">KST</a>  &raquo; '.$go.'  
    </div>';
echo '<div class="mainContentCell"><div class="content">';




$go();

echo '</div></div>';


?>
