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



function pilihmahasiswa($p) {
global $koneksi_db;
	$opsi .= "<option value=\"\" >..::Pilih Mahasiswa::..</option>";
	$query  = $koneksi_db->sql_query ("SELECT * FROM m_mahasiswa");
	while( $r = $koneksi_db->sql_fetchassoc($query)) {
	$cl = ($r['NIM'] == $p) ? "selected" : "";
	$opsi .= "<option value=\"$r[NIM]\" $cl>$r[NIM]</option>";
	}
	return $opsi;
}

function TampilkanPARentangNIM() {

global $koneksi_db;
$prodi = $_SESSION['prodi'];
$tahun_id = $_SESSION['tahun_id'];

echo "<p><font size=+1>&raquo; Tentukan PA untuk rentang NIM</font></p>";
  echo "<blockquote>
  <table cellspacing=1 cellpadding=4 width=100% class=datatable full>
  <form name='data' action='' method=POST>
  <input type='hidden' name='m' value='".$_GET['m']."'/>
        <input type='hidden' name='op' value='RentangNIM'/>
  <tr><td class=ul colspan=2>Untuk melakukan penentuan pembimbing akademik dari rentang NIM tertentu.</td></tr>
  <tr><td class=inp>Rentang NIM</td>
    <td class=ul>
	<div class='col-md-6'>
	<select  name=\"DariNIM\" >".pilihmahasiswa($_SESSION[DariNIM])."</select>
   </div><div class='col-md-6'>
	<select  name=\"SampaiNIM\" >".pilihmahasiswa($_SESSION[SampaiNIM])."</select>
    </div>
	</td></tr>
  <tr><td class=inp>Pembimbing Akademik</td>
    <td class=ul>
	<div class='col-md-12'>
	<select  name=\"DosenID\" >".opdosenPA($_SESSION[DosenID])."</select>
    </div>
	</td></tr>
  <tr><td class=ul colspan=2><input type=submit class='tombols ui-corner-all' name='Simpan' value='Proses'>
    </td></tr>
  </form></table>
  </blockquote>";
}

function RentangNIM() {
global $koneksi_db;
$prodi = $_SESSION['prodi'];
$tahun_id = $_SESSION['tahun_id'];

  $DariNIM = $_REQUEST['DariNIM'];
  $SampaiNIM = $_REQUEST['SampaiNIM'];
  $DosenID = $_REQUEST['DosenID'];
  
  if (empty($DariNIM) || empty($SampaiNIM)) {
 	 echo "<div  class='error'>Rentang NIM Harus Diisi</div>";	
	echo "<meta http-equiv='refresh' content='1; url=index.php?m=".$_GET['m']."'>";
  } else {
	$dsn = $koneksi_db->sql_query( "SELECT * FROM m_dosen where nip ='$DosenID' " );
	$jumd=$koneksi_db->sql_numrows($dsn);
		$DSN = $koneksi_db->sql_fetchassoc($dsn);
		$dosene = $DSN['nama_dosen'];
		if (empty($jumd)) {
		 echo "<div  class='error'>Dosen dengan NIP: <font size=+1>$DosenID</font> tidak ditemukan.</div>";	
		 echo "<meta http-equiv='refresh' content='1; url=index.php?m=".$_GET['m']."'>";
		} else { RentangNIMKonf($DariNIM, $SampaiNIM, $DosenID, $dosene);
		} 
	}
}

function RentangNIMKonf($DariNIM, $SampaiNIM, $DosenID, $dosene) {
global $koneksi_db;

$prodi = $_SESSION['prodi'];
$tahun_id = $_SESSION['tahun_id'];

  $s = "select * from m_mahasiswa
    where '$DariNIM' <= NIM and NIM <= '$SampaiNIM'
    order by NIM";

	$r = $koneksi_db->sql_query( $s );
	$jml=$koneksi_db->sql_numrows($r);
  
  $a = '<table class="table table-striped table-bordered table-hover"  >
    <th width="5%" align="center">No</th>
	   <th align="center">NIM</th>
       <th align="center">Nama</th>
       <th align="center">Angkatan</th>
	   
	   <th align="center" width="10%">Status</th>
    <th class=ttl>Pembimbing Akademik</th>
    </tr>';
	
  while ($w = $koneksi_db->sql_fetchassoc($r) ) {
    $n++;
    $_nim .= "&_NIM[]=$w[NIM]";
    $a .= '<tr><td class=inp>'.$n.'</td>
    <td class=ul>'.$w[NIM].'</td>
	<td  align=left>'.$w['nama_mahasiswa'].'</td>
	<td  align=left>'.$w['tahun_masuk'].'</td>

	<td  align=center>
		'.viewAplikasi('05',''.$w['status_aktif'].'').'
	</td>
    <td class=ul>'.viewdosenPA($w['pa']).'</td>
    </tr>';
  }
  $a .= "</table></p>";
  
 echo  "<table cellspacing=1 cellpadding=1 width=100%>
  <tr><td >Konfirmasi Set Penasehat Akademik</td></tr>
  <tr><td >Di bawah ini adalah daftar mahasiswa dalam rentang yang Anda tentukan.<br />
    Terdapat: <font size=+1>$jml</font> mahasiswa.<br />
    Apakah Anda akan mengubah Penasehat Akademik mereka menjadi: <b>$dosene</b> ($DosenID)?
    <hr size=1 color=silver>
    <input type=button  class='tombols ui-corner-all' name='Ubah' value='Ubah PA'  onClick=\"location='index.php?m=".$_GET['m']."&op=RentangNIMSav&DariNIM=$DariNIM&SampaiNIM=$SampaiNIM&DosenID=$DosenID$_nim'\">
      <input type=button   class='tombols ui-corner-all' name='Batal' value='Batalkan' onClick=\"location='index.php?m=".$_GET['m']."'\"></td></tr>
  </table><br/>";
  
  echo $a;
}

function RentangNIMSav() {
global $koneksi_db;

$prodi = $_SESSION['prodi'];
$tahun_id = $_SESSION['tahun_id'];

  $_NIM = array();
  $_NIM = $_REQUEST['_NIM'];
  for ($i = 0; $i < sizeof($_NIM); $i++) {
    $isi = $_NIM[$i];
    $_NIM[$i] = "'$isi'";
  }
  $__npm = implode(',', $_NIM);
  $DosenID = $_REQUEST['DosenID'];
  $s = "update m_mahasiswa set pa='$DosenID'
    where NIM in ($__npm)";
  $r = $koneksi_db->sql_query($s);
   echo  "<table cellspacing=1 cellpadding=1 width=100%>
  <tr><td >Pengubahan PA Berhasil</td></tr>
  <tr><td >Di bawah ini adalah daftar mahasiswa dalam rentang yang Anda tentukan.<br />
    Berikut adalah mahasiswa yang berhasil diubah PA-nya
    $__npm <hr size=1 color=silver>
      <input type=button name='Batal' class='tombols ui-corner-all' value='Kembali' onClick=\"location='index.php?m=".$_GET['m']."'\"></td></tr>
  </table>";
  
}

function TampilkanUbahPA() {
global $koneksi_db;
global $koneksi_db;
 
 
}

function UbahPAKonf() {
global $koneksi_db;
  $Tahun = $_REQUEST['thn'];
  $DariDosen = $_REQUEST['DariDosen'];
  $MenjadiDosen = $_REQUEST['MenjadiDosen'];
   $whr = array();
   //$whr[] = "kode_prodi='$_SESSION[prodi]'";
   $whr[] = "pa='$_SESSION[DariDosen]'";
   $whr[] = "tahun_masuk='$_SESSION[thn]'";
   
  if (!empty($whr)) $strwhr = "where " .implode(' and ', $whr);
  
   echo  "<table cellspacing=1 cellpadding=1 width=100%>
  <tr><td >Konfirmasi Ubah Penasehat Akademik</td></tr>
  <tr><td >
  		Anda akan mengubah Pembimbing Akademik mahasiswa dari <br /><font size=+1>".viewdosen($DariDosen)."</font><br />
        menjadi <br /><font size=+1>".viewdosen($MenjadiDosen)."</font>?<br />
  </td></tr>
  </table>";
  
  echo '
 <form action="" method="post"  class="cmxform" id="form_input" style="width:100%">
		<input type="hidden" name="id" value="'.$id.'"/>
         <input type="hidden" name="m" value="mahasiswa.kelas"/>
        <input type="hidden" name="op" value="update"/>
<table class="table table-striped table-bordered table-hover"  >
   	<thead>
     <tr>
	   <th width="5%" align="center"><a href="javascript:checkall(\'form_input\', \'terima[]\');">ALL</a></th>
	   <th align="center">NIM</th>
       <th align="center">Nama</th>
       <th align="center">Angkatan</th>

	   <th align="center" width="10%">Status</th>
     </tr>
	 </thead>
	 <tbody>';
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
 
	 
		while($wr = $koneksi_db->sql_fetchassoc($result)){
		$n++;
		$id = $wr['idm'];
		echo '<tr bgcolor="#f2f2f2">
				<td  align=center><input type="checkbox" name="terima[]" value="'.$id.'"></td> 
				<td  align=center>'.$wr['NIM'].'</a></td>
				<td  align=left>'.$wr['nama_mahasiswa'].'</td>
				<td  align=left>'.$wr['tahun_masuk'].'</td>
				
				<td  align=center>'.viewAplikasi('05',''.$wr['status_aktif'].'').'</td>
			</tr>'; 
		}
		echo  '</tbody>
		</table>
		<input type="submit" class=tombols ui-corner-all value="Update"/>
		
		</form>';
	echo $obj->total;
	echo '<br/>';
	echo $obj->anchors;	
	} else {
		 echo '<div class="alert alert-danger" >Belum ada Data</div>';
		 echo ' 
		<input type=button name="Batal" value="Kembali" onClick="location=\'index.php?m='.$_GET['m'].'\'">
		';
	}
	 

 	


}

function update() {
global $koneksi_db, $prodi, $tahun_id;
 $MenjadiDosen = $_SESSION['MenjadiDosen'];
	if (is_array($_POST['terima'])) {
		foreach($_POST['terima'] as $key=>$val) {
		$mhsw .= $val.', ';;
			$update = $koneksi_db->sql_query("UPDATE `m_mahasiswa` SET `pa` = '$MenjadiDosen'  WHERE `idm` = '$val'");
		}
	}
   echo  "<table cellspacing=1 cellpadding=1 width=100%>
  <tr><td >Pengubahan PA Berhasil</td></tr>
  <tr><td >Di bawah ini adalah daftar mahasiswa dalam rentang yang Anda tentukan.<br />
    Berikut adalah mahasiswa yang berhasil diubah PA-nya
    $mhsw <hr size=1 color=silver>
      <input type=button name='Batal' value='Kembali' onClick=\"location='index.php?m=".$_GET['m']."'\"></td></tr>
  </table>";
}
  
function PAAwal() {
  TampilkanPARentangNIM();
  TampilkanUbahPA();
}


$DosenID = BuatSesi('DosenID');
$DariNIM = BuatSesi('DariNIM');
$SampaiNIM = BuatSesi('SampaiNIM');
$DariDosen = BuatSesi('DariDosen');
$MenjadiDosen = BuatSesi('MenjadiDosen');
$thn = BuatSesi('thn');

$go = (empty($_REQUEST['op'])) ? 'PAAwal' : $_REQUEST['op']; 

echo '<div id="title" align="right">
        <font style="color:#FF9900; font-size:30px;"><strong>.:</strong></font>
        <font style="font-size:18px; color:#999999">SetPembimbing Akademik</font><br />
        	<a href="index.php">Home</a> &raquo; <a href="?m='.$_GET['m'].'">Settting Pembimbing Akademik</a>  &raquo; '.$go.'  
    </div>';
echo '<div class="mainContentCell"><div class="content">';
$go();
echo '</div></div>';

?>
