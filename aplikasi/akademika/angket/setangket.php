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
							angket='N'
							where level='MAHASISWA'							
							";
						
						$koneksi_db->sql_query($so);
	Daftar();						
}

function Daftar() {
global $koneksi_db, $tahun_id;
TampilkanFilter();


  $whr = array();
  $ord = '';
  if (($_SESSION['reset_user'] != 'Reset') &&
  !empty($_SESSION['kolom_user']) && !empty($_SESSION['kunci_user'])) {
    $whr[] = "$_SESSION[kolom_user] like '%$_SESSION[kunci_user]%' ";
    
  }
  $strwhr = "where level='MAHASISWA' and user.userid=t_mahasiswa_krs.idm and t_mahasiswa_krs.tahun_id='$tahun_id' group by idm order by username";

require('system/pagination_class.php');
$sql = "select username, nama, angket from user left join t_mahasiswa_krs on user.userid=t_mahasiswa_krs.idm $strwhr";
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
	   <th width="15%" align="center">Status Isi Angket</th>
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
				<td  align=center>'.$n.'</td> 
				   <td  align=center>'.$wr['username'].'</a></td>
				   <td  align=left>'.$wr['nama'].'</td>';
				   if ($wr['angket'] == "Y"){
					echo '<td align=center>SUDAH</td>';
					}else{
					echo '<td  font color=red align=center><font color="red"> BELUM </font> </td>';
		}
				 echo'  
				   
				 </tr>'; 
		
		}
		
		 echo '
			
			</tbody>
		</table>';
		
 


	echo'</form>';
	echo $obj->total;
	echo '<br/>';
	echo $obj->anchors;	
	
		echo"<hr/>
	<a  class='btn'  href='index.php?m=".$_GET['m']."&op=setTidak'; onclick=\"return confirm('Apakah anda yakin akan SET SEMUA MAHASISWA AKTIF UNTUK ISI ANGKET ???')\">Set Semua Mahasiswa Untuk Mengisi Angket</a>";	
	} else {
		 echo '<div class="alert alert-danger">Belum ada Data</th>
			</div>';
	}
	

 




}

function update() {
	global $koneksi_db;
			foreach($_POST['aktif'] as $key=>$val) {
			$aktif = 'N';
			$update = mysql_query("UPDATE `user` SET `angket` = '$aktif' WHERE `userid` = '$key'");
			$mhs = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT nim FROM m_mahasiswa where idm='$key' limit 1 " ));
			
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
        <font style="font-size:18px; color:#999999">ANGKET MAHASISWA</font><br />
        	<a href="index.php">Home</a> &raquo; <a href="index.php?m='.$_GET['m'].'">Angket Mahasiswa</a>  &raquo; '.$go.'  
    </div>';
echo '<div class="mainContentCell"><div class="content">';
$go();
echo '</div></div>';
?>
