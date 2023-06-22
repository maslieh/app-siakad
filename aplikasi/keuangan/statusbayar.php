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
if ($_SESSION['Level']!="ADMIN" && $_SESSION['Level']!="ADKEU") {
header ("location:index.php");
exit;
}

function hapus() {
echo "<div class=error>Ma'af.. Data tidak dapat dihapus, hanya bisa diedit</div>";
Daftar();
}

function TampilkanFilter() {
  // Tampilkan formulir
  echo "<p><table class=box cellspacing=1 cellpadding=4 width=100%>
  <form action='' method=POST>
  <input type=hidden name='m' value='asal.pt'>
  <tr>
	  <td class=inp1>Cari Berdasarkan </td>
	  <td class=inp1>
		<select id=\"kolom_user\" name=\"kolom_user\">";
		
		$arr = array(
		'Semua Data->',
		'NIM->username',
		'Nama Mahasiswa->nama'
		);
		
	  for ($i=0; $i<sizeof($arr); $i++) {
		$r = Explode('->', $arr[$i]);
		$cl = ($r[1] == $_SESSION[kolom_user]) ? "selected" : "";
		echo "<option value=\"$r[1]\" $cl>$r[0]</option>";
	  }
		echo "</select>
	  </td>
	  <td><input type=text name='kunci_user' value='$_SESSION[kunci_user]' size=20 maxlength=20></td>
	  <td class=inp1>
	  	<input type=submit name='cari' value='Cari'>
		<input type=submit name='reset_user' value='Reset'>
	 </td>
  </tr>
  </table></form><br/>";
}

function setTidak () {
global $koneksi_db;
	
	$so = "UPDATE user SET 	
							aktif='N'
							where level='MAHASISWA'							
							";
						
						$koneksi_db->sql_query($so);
	Daftar();						
}

function Daftar() {
global $koneksi_db;
TampilkanFilter();


  $whr = array();
  $ord = '';
  if (($_SESSION['reset_user'] != 'Reset') &&
  !empty($_SESSION['kolom_user']) && !empty($_SESSION['kunci_user'])) {
    $whr[] = "$_SESSION[kolom_user] like '%$_SESSION[kunci_user]%' ";
    $ord = "order by $_SESSION[kolom_user]";
  }
	if (!empty($whr)) $strwhr = "where level='MAHASISWA' and " .implode(' and ', $whr);  
	
	if (empty($whr)) $strwhr = "where level='MAHASISWA'";
require('system/pagination_class.php');
$sql = "select * from user $strwhr $ord";
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
  <form action="" method="post"  class="cmxform" id="form_input" style="width:100%">
		<input type="hidden" name="id" value="'.$id.'"/>
         <input type="hidden" name="m" value="user"/>
        <input type="hidden" name="op" value="update"/>
		
<table class="table table-striped table-bordered table-hover"  >
   	<thead>
     <tr>
	   <th width="5%" align="center">No</th>
       <th align="center">NIM</th>
       <th align="center">Nama</th>
	   <th width="15%" align="center">Status Bayar</th>
     </tr>
	 </thead>
	 <tbody>';
	 
 
		while($wr = $koneksi_db->sql_fetchassoc($result)){
		$n++;
		$id = $wr['userid'];
		$pecah = substr($id, 0,1);
		if ($pecah =="D") {
		$status = "Dosen";
		} else if ($pecah =="M") {
		$status = 'Mahasiswa';
		}

	
		echo '<tr  >
				<td   >'.$n.'</td> 
				   <td  >'.$wr['username'].'</a></td>
				   <td  align=left>'.$wr['nama'].'</td>
				   <td  align=center><select name="aktif['.$id.']"  class="required"   />'.opYN(''.$wr['aktif'].'').'</select></td>
				   
				 </tr>'; 
		
		}
			 echo ' </tbody>
		</table> ';
			 echo ' 
				<input type="submit" class=tombols ui-corner-all value="Update"/> </th>
			 
	 </form>';
		
	echo $obj->total;
	echo '<br/>';
	echo $obj->anchors;	
	} else {
		 echo '<div class="alert alert-danger">Belum ada Data</th>
			</div>';
	}
 
echo'</br> </br>';
echo"
<a  <input type=button  class=\"btn btn-primary\"  href='index.php?m=".$_GET['m']."&op=setTidak'; onclick=\"return confirm('Apakah anda yakin akan MERUBAH SEMUA STATUS MAHASISWA MENJADI TIDAK ???')\">Set Status TIDAK Semua Mahasiswa </a>";	


 
}

function update()  {
global $koneksi_db;


			foreach($_POST['aktif'] as $key=>$val) {
			$aktif = $_POST['aktif'][$key];
			
			$update =  $koneksi_db->sql_query("UPDATE `user` SET `aktif` = '$aktif' WHERE `userid` = '$key'");
			}
	Daftar();
}




$kolom_user = BuatSesi('kolom_user');
$kunci_user = BuatSesi('kunci_user');

if ($_REQUEST['reset_user'] == 'Reset') {
  $_SESSION['kolom_user'] = '';
  $_SESSION['kunci_user'] = '';
  
}


$go = (empty($_REQUEST['op'])) ? 'Daftar' : $_REQUEST['op'];
echo '<div id="title" align="right">
        <font style="color:#FF9900; font-size:30px;"><strong>.:</strong></font>
        <font style="font-size:18px; color:#999999">STATUS PEMBAYARAN</font><br />
        	<a href="index.php">Home</a> &raquo; <a href="index.php?m='.$_GET['m'].'">Status Pembayaran User Mahasiswa</a>  &raquo; '.$go.'  
    </div>';
echo '<div class="mainContentCell"><div class="content">';
$go();
echo '</div></div>';
?>
