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

if ($_SESSION['Level']!="ADMIN"	) {
header ("location:index.php");
exit;
}

function hapus() {
echo "<div class=error>Ma'af.. Data tidak dapat dihapus, hanya bisa diedit</div>";
Daftar();
}




function Daftar() {

FilterUser('user');

 echo"
 <fieldset class=\"ui-widget ui-widget-content ui-corner-all\" >
            <legend><input type=button  class=\"tombols ui-corner-all\" value='Tambah User' onclick=\"window.location.href='index.php?m=".$_GET['m']."&op=add&md=1';\"></legend>
            &nbsp;<font color=\"red\"><br></font>
 
 ";
global $koneksi_db;
$prodi = $_SESSION['prodi'];
  $whr = array();
  $ord = '';
  if (($_SESSION['reset_user'] != 'Reset') &&
  !empty($_SESSION['kolom_user']) && !empty($_SESSION['kunci_user'])) {
    $whr[] = "$_SESSION[kolom_user] like '%$_SESSION[kunci_user]%' ";
    $ord = "order by $_SESSION[kolom_user]";
  }
  	//if (!empty($_SESSION['prodi'])) $whr[] = "kode_prodi='$_SESSION[prodi]'"; filter berdasarkan prodi
	if (!empty($whr)) $strwhr = "where " .implode(' and ', $whr);  

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
echo '<table class="table table-striped table-bordered table-hover"  >
   	<thead>
     <tr>
	   <th width="5%" align="center">No</th>
	   <th align="center">Username</th>
	   <th align="center">Nama Lengkap</th>
       <th align="center">Email</th>
       <th align="center">Level</th>
	    <th align="center">Aktif</th>
	   <th align="center">Edit</th>
     </tr>
	 </thead>
	 <tbody>';
	 
 
		while($wr = $koneksi_db->sql_fetchassoc($result)){
		$n++;
		$userid = $wr['userid'];
		echo '<tr  >
				<td   >'.$n.'</td> 
				<td   >'.$wr['username'].'</a></td>
				<td  >'.$wr['nama'].'</a></td>
				<td  align=left>'.$wr['email'].'</td>
				<td  align=left>'.$wr['level'].'</td>
				<td  align=left>'.$wr['aktif'].'</td>
				<td   >
					
					<a class="btn" href="#" onclick="window.location.href=\'index.php?m='.$_GET['m'].'&op=edit&userid='.$userid.'\';">
					<i class="fa fa-edit"></i></a>
			
				</td>
			</tr>'; 
		}
	echo '</tbody>
		</table> ';

	echo $obj->total;
	echo '<br/>';
	echo $obj->anchors;	
	} else {
		 echo '<div class="alert alert-danger">Belum ada Data</th>
			</div>';
	}



}

function add() {
global $koneksi_db;
  $md = $_REQUEST['md']+0;
  $userid = $_REQUEST['userid'];
  if (!empty($userid) && isset($userid)) {
	$w = $koneksi_db->sql_fetchrow($koneksi_db->sql_query( "SELECT * FROM user where userid='$userid' limit 1 " ));
    $jdl = "Edit Data User";
	$kode = '<input name="kode_"  disabled="disabled"  type="text" class="" id="" value="'.$w['userid'].'" />
		<input name="userid"  type="hidden" class="required" id="" value="'.$w['userid'].'" />
	';

  } else {
    $w = array();
    $jdl = "Tambah Data User";
	

  }


//$aktif = ($w['buka'] == 'Y') ? 'checked' : ''; 
	
echo '  
    <form action="" method="post"  class="cmxform" id="form_input" style="width:100%">
        <input type="hidden" name="m" value="user"/>
        <input type="hidden" name="op" value="simpanadd"/>
		<input type="hidden" name="md" value="'.$md.'"/>
		<input type="hidden" name="userid" value="'.$userid.'"/>
        <fieldset class="ui-widget ui-widget-content ui-corner-all" >
            <legend class="ui-widget ui-widget-header ui-corner-all">'.$jdl.' '.$w['username'].'</legend>
            &nbsp;<font color="red"><br></font>
            <table    border="0" class="datatable ful1">
                <tr>
                    <td align="left" valign="top">UserID<font color="red"> *</font></td>
                    <td><input name="userid"  type="text" required value="'.$w['userid'].'" /></td>
                </tr>
				<tr>
                    <td align="left" valign="top">Username<font color="red"> *</font></td>
                    <td><input name="username"  type="text" required value="'.$w['username'].'" /></td>
                </tr>
                <tr>
                    <td align="left" valign="top">Password<font color="red">*</font></td>
                    <td><input name="password"  type="password" size=50 required value="'.$w['password'].'" /></td>
                </tr>
                <tr>
                    <td align="left" valign="top">Nama Lengkap<font color="red"> *</font></td>
                    <td><input name="nama"  type="text" required  value="'.$w['nama'].'" /></td>
                </tr>
    
                <tr>
                    <td  align="left" valign="top">Email<font color="red"> *</font></td>
                    <td><input name="email"  type="text" required  value="'.$w['email'].'" /></td>
                </tr>
                <tr>
                    <td  align="left" valign="top">Level<font color="red"> *</font></td>
                    <td  >
                    <select class="required" name="level" required>
            					<option value="">-Pilih-</option>
            					<option value="ADMIN">ADMIN</option>
                                <option value="ADAK">ADAK</option>
                                <option value="ADKEU">ADKEU</option>
                                <option value="PRODI">PRODI</option>
                                <option value="WALI">WALI</option>
                                <option value="ADKUL">ADKUL</option>
                              </select> 
                    </td>
                </tr>
				<tr>
                    <td  align="left" valign="top">Aktif<font color="red"> *</font></td>
                    <td  ><select name="aktif"  required   />'.opYN(''.$w[6].'').'</select></td>
                </tr>
				
                 <tr>
                    <td colspan="2">
                        <input type="submit" class="tombols ui-corner-all" value="Simpan"/> 
                        <input type="button" class="tombols ui-corner-all" value="Kembali" onClick="window.location =\'index.php?m='.$_GET['m'].'\'"/></td>
                  </tr>
            </table>
        </fieldset>
    </form>
 ';

}

function Edit() {

global $koneksi_db;
$userid = $_REQUEST['userid'];
if (!empty($userid) && isset($userid)) 
{ 
 $wp = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM user where userid='$userid' limit 1 " ));
	$judul ="Edit User";
} else {
	$judul ="Tambah User";
}

echo'<form action="" method="post"  class="cmxform" id="form_input" style="width:100%">
        <input type="hidden" name="m" value="user"/>
        <input type="hidden" name="op" value="simpanadd"/>
		<input type="hidden" name="userid" value="'.$userid.'"/> 
        <fieldset class="ui-widget ui-widget-content ui-corner-all" >
            <legend class="ui-widget ui-widget-header ui-corner-all">'.$judul.'</legend>
            <table width="600"  border="0" class="datatable">	
			    <tr>  
                   <td align="left" valign="top">UserID<font color="red"> *</font></td>
                    <td  ><input type=text class="required" readonly name="userid" value="'.$wp['userid'].'" size=30 maxlength=150>	</td>
                </tr>
                <tr>
                    <td align="left" valign="top">Username<font color="red"> *</font></td>
                    <td  ><input type=text class="required" name="username" value="'.$wp['username'].'" size=30 maxlength=150>	</td>
                </tr>
				<tr>
                  <td align="left" valign="top">Password<font color="red"> *</font></td>
                    <td  ><input type=password class="" name="password" value="" size=50 maxlength=150><font color="blue" align="right">Note *)</font>	</td>
                </tr>
				<tr>
                    <td align="left" valign="top">Nama Lengkap<font color="red"> *</font></td>
                    <td  ><input type=text class="required" name="nama" value="'.$wp['nama'].'" size=30 maxlength=150>	</td>
                </tr>
				<tr>
                    <td align="left" valign="top">Email<font color="red"> *</font></td>
                    <td  ><input type=text class="required" name="email" value="'.$wp['email'].'" size=30 maxlength=150>	</td>
                </tr>
				<tr>
                    <td  align="left" valign="top">Level<font color="red"> *</font></td>
                    <td  ><select name="level"  class="required"   />'.oplevel(''.$wp['level'].'').'</select></td>
                </tr>
				<tr>
                    <td  align="left" valign="top">Aktif<font color="red"> *</font></td>
                    <td  ><select name="aktif"  class="required"   />'.opYN(''.$wp['aktif'].'').'</select></td>
                </tr>
					<tr>
                    <td  colspan="2" align="left" valign="top"><font color="blue">*) Jika password tidak diubah, dikosongkan saja</font></td>
                   
                </tr>
                 <tr>
                    <td colspan="2">
                        <input type="submit" class="tombols ui-corner-all" value="Simpan"/> 
                        <input type="button" class="tombols ui-corner-all" value="Kembali" onClick="window.location = \'index.php?m='.$_GET['m'].'\'"/></td>
                  </tr>					
            </table>
        </fieldset></form> ';

}


////simpan /
function simpanadd() {
global $koneksi_db;
  $md = $_REQUEST['md']+0;
	$userid = $_REQUEST['userid'];  

	if (! count($pesan)==0 ) {
		echo "<div align='left'>";			
		echo "&nbsp; <b> Kesalahan Input : </b><br>";
		foreach ($pesan as $indeks=>$pesan_tampil) {
			$urut_pesan++;
			echo "<font color='#FF0000' align='left'>";
			echo "&nbsp; &nbsp;";
			echo "$urut_pesan . $pesan_tampil <br>";
			echo "</font>";
		}
		echo "</div><br>";
		echo "<meta http-equiv='refresh' content='2; url=index.php?m=".$_GET['m']."&op=add&md=1'>";
	} else {
		$wi = $koneksi_db->sql_fetchrow($koneksi_db->sql_query( "SELECT * FROM m_program_studi where kode_prodi='".$_REQUEST['kode_prodi']."' limit 1 " ));

			if ($md == 0) {
				if (empty($_REQUEST['password'])) {
					$s = "update user set 
					userid='".$_REQUEST['userid']."',
					username='".$_REQUEST['username']."',
					nama='".$_REQUEST['nama']."',
					email='".$_REQUEST['email']."',
					level='".$_REQUEST['level']."',
					aktif='".$_REQUEST['aktif']."'

					where userid='".$_REQUEST['userid']."' ";
					$r = $koneksi_db->sql_query($s);
				} else {
					$pass = md5($_REQUEST['password']);
					$s = "update user set 
					userid='".$_REQUEST['userid']."',
					username='".$_REQUEST['username']."',
					password='".$pass."',
					nama='".$_REQUEST['nama']."',
					email='".$_REQUEST['email']."',
					level='".$_REQUEST['level']."',
					aktif='".$_REQUEST['aktif']."'

					where userid='".$_REQUEST['userid']."' ";
					$r = $koneksi_db->sql_query($s);
					}
			
			} else {
				$qd = $koneksi_db->sql_query( "SELECT * FROM user where userid='".$_REQUEST['userid']."' limit 1 " );
				$totald = $koneksi_db->sql_numrows($qd);
				$wd = $koneksi_db->sql_fetchassoc($qd);
				if ($totald > 0) { 
				echo '<div class=error>Kode UserID '.$_REQUEST['userid'].' sudah dipakai oleh '.$wd['username'].'</div>'; 
				} else {

				  $s = "insert into user SET 
						userid='".$_REQUEST['userid']."',
						username='".$_REQUEST['username']."',
						password='".md5($_REQUEST['password'])."',
						nama='".$_REQUEST['nama']."',
						email='".$_REQUEST['email']."',
						level='".$_REQUEST['level']."',
						aktif='".$_REQUEST['aktif']."'";
				  $koneksi_db->sql_query($s);
				  
			  }
			}
		}
	  echo "<div  class='error'>Proses Menyimpan Data...</div>";	
	echo "<meta http-equiv='refresh' content='3; url=?m=".$_GET['m']."'>";

  //Daftar();
 
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
        <font style="font-size:18px; color:#999999">Daftar User</font><br />
        	<a href="?m=home">Home</a> &raquo; <a href="?m='.$_GET['m'].'">User</a>  &raquo; '.$go.'  
    </div>';
echo '<div class="mainContentCell"><div class="content">';
$go();
echo '</div></div>';
?>
