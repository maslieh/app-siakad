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
		'UserID->username',
		'Nama User->nama',
		'Email->email',
		'Level->level'
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

function Daftar() {
global $koneksi_db;
TampilkanFilter();

//echo"<input type=button class=button-blue value='Tambah User' onclick=\"window.location.href='?m=user&op=edit&md=1';\">";

  $whr = array();
  $ord = '';
  if (($_SESSION['reset_user'] != 'Reset') &&
  !empty($_SESSION['kolom_user']) && !empty($_SESSION['kunci_user'])) {
    $whr[] = "$_SESSION[kolom_user] like '%$_SESSION[kunci_user]%' ";
    $ord = "order by $_SESSION[kolom_user]";
  }
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
echo '
  <form action="" method="post"  class="cmxform" id="form_input" style="width:100%">
		<input type="hidden" name="id" value="'.$id.'"/>
         <input type="hidden" name="m" value="user"/>
        <input type="hidden" name="op" value="update"/>
		
<table class="table table-striped table-bordered table-hover"  >
   	<thead>
     <tr>
	   <th width="5%" align="center">No</th>
       <th align="center">User</th>
       <th align="center">Nama</th>
	   <th align="center">Email</th>
	   <th align="center">Level</th>
	   <th align="center">Aktif</th>
	   <th align="center">Reset</th>
	   <th  align="center"></th>
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
				   <td   >'.$wr['username'].'</a></td>
				   <td  align=left>'.$wr['nama'].'</td>
				   <td  align=left>'.$wr['email'].' </td>
				   <td  align=left>
				   <select name="level['.$id.']"  style="width:100%"  />'.oplevel(''.$wr['level'].'').'</select></td>
				   <td  align=left>
				   <select name="aktif['.$id.']"  style="width:100%"  />'.opYN(''.$wr['aktif'].'').'</select></td>
				   <td   >
				   	<a class="btn" href="#" onclick="window.location.href=\'index.php?m='.$_GET['m'].'&op=repassword&md=0&id='.$id.'\';">Reset</a>
					</td>
				   <td   > 
					<a class="btn" href="#" onclick="window.location.href=\'index.php?m='.$_GET['m'].'&op=edit&md=0&id='.$id.'\';">
					<i class="fa fa-edit"></i></a>
					<a class="btn" href="#" onclick="window.location.href=\'index.php?m='.$_GET['m'].'&op=hapus&id='.$id.'\';">
					<i class="fa fa-trash-o"></i></a>
				   </td>
				 </tr>'; 
		
		}
		
		 echo '</tbody>
		</table><input type="submit" class=tombols ui-corner-all value="Update"/></form>';
		
		
		
	echo $obj->total;
	echo '<br/>';
	echo $obj->anchors;	
	} else {
		 echo '<div class="alert alert-danger">Belum ada Data</th>
			</div>';
	}



}

function update() {
    global $koneksi_db;
	if (is_array($_POST['level'])) {
		foreach($_POST['level'] as $key=>$val) {
			$aktif = $_POST['aktif'][$key];
			$update = $koneksi_db->sql_query("UPDATE `user` SET `level` = '$val',`aktif` = '$aktif' WHERE `userid` = '$key'");
			
		}
	}
	Daftar();
}

function repassword() {
global $koneksi_db;
$id = $_REQUEST['id'];

$w = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM user where userid='$id' limit 1 " ));
$passwordx = md5($w['username']);
$usernem = $w['username'];
$update = $koneksi_db->sql_query("UPDATE `user` SET password='$passwordx' WHERE userid='$id' ");


echo '<div class=error>Pasword User <b>'.$w['username'].'</b> berhasil direset</div>'; 

	Daftar();
}


function edit() {
global $koneksi_db;
  $md = $_REQUEST['md']+0;
  if ($md == 0) {
    $id = $_REQUEST['id'];
	$w = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM user where userid='$id' limit 1 " ));
    $jdl = "Edit Data User";
	
		$pecah = substr($id, 0,1);
		if ($pecah =="D") {
		$status = "Bukan Mahasiswa";
		} else if ($pecah =="M") {
		$status = 'Mahasiswa';
		}


  }
  else {
    $w = array();
    $jdl = "Tambah User";
  }

//$aktif = ($w['buka'] == 'Y') ? 'checked' : ''; 
	
echo '  
    <form action="" method="post"  class="cmxform" id="form_input" style="width:100%">
		<input type="hidden" name="id" value="'.$id.'"/>
         <input type="hidden" name="m" value="user"/>
        <input type="hidden" name="op" value="simpan"/>
		<input type="hidden" name="md" value="'.$md.'"/>
       
		<fieldset class="ui-widget ui-widget-content ui-corner-all" >
            <legend class="ui-widget ui-widget-header ui-corner-all">'.$jdl.'</legend>
            &nbsp;<font color="red"><br></font>
            <table width="100%"  border="0" class="datatable full">
                <tr>
                    <td align="right" valign="top">User<font color="red"> *</font></td>
                    <td><input name="username"  readonly type="text" class="required " size="10" id="" value="'.$w['username'].'" />
					<input name="userid"  type="hidden" class="required " size="10" id="" value="'.$w['userid'].'" />
					</td>
                </tr>
                <tr>
                    <td align="right" valign="top">Nama<font color="red"> *</font></td>
                    <td><input name="nama"  type="text" class="required full" id="" value="'.$w['nama'].'" /></td>
                </tr>
                <tr>
                    <td align="right" valign="top">Email<font color="red"> *</font></td>
                    <td><input name="email"  type="text" class="required email full" id="" value="'.$w['email'].'" /></td>
                </tr>
                <tr>
                    <td align="right" valign="top">Level<font color="red"> *</font></td>
                   <td><select name="level"  class="required"   />'.oplevel(''.$w['level'].'').'</select></td>
                </tr>
                <tr>
                    <td align="right" valign="top">Aktif<font color="red"> *</font></td>
                    <td><select name="aktif"  class="required"   />'.opYN(''.$w['aktif'].'').'</select></td>
                </tr>
				
                 <tr>
                    <td colspan="2">
                        <input type="submit" class=tombols ui-corner-all value="Simpan"/> 
                        <input type="button" class=tombols ui-corner-all value="Kembali" onClick="window.location = \'index.php?m='.$_GET['m'].'\'"/></td>
                  </tr>
            </table>
        </fieldset>
    </form>
 ';

}


////simpan /
function simpan() {
global $koneksi_db;
  $md = $_REQUEST['md']+0;
  $userid = $_REQUEST['id'];  
$nama      = $_POST['username'];
$email     = $_POST['email'];

	if (trim($_POST['userid'])=="") {
		$pesan[] = "Form UserID masih kosong, ulangi kembali";
	}
	else if (trim($_POST['username'])=="") {
		$pesan[] = "Form UserName  masih kosong, ulangi kembali";
	}
	else if (trim($_POST['nama'])=="") {
		$pesan[] = "Form Nama masih kosong, ulangi kembali";
	}
	else if (trim($_POST['email'])=="") {
		$pesan[] = "Form Email  masih kosong, ulangi kembali";
	}
	else if (trim($_POST['level'])=="") {
		$pesan[] = "Form Level masih kosong, ulangi kembali";
	}
	
	if (!$nama || preg_match("/[^a-zA-Z0-9_-]/", $nama)) $pesan[] = "Error: Karakter Username tidak diizinkan kecuali a-z,A-Z,0-9,-, dan _<br />";

	if (strrpos($nama, " ") > 0) $pesan[] = "Username Tidak Boleh Menggunakan Spasi";
	
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
		echo "<meta http-equiv='refresh' content='1; url=index.php?m=".$_GET['m']."&op=edit&md=1'>";
	} else {
		
			if ($md == 0) {
			$s = "update user set 
					username='".$_REQUEST['username']."',
					nama='".$_REQUEST['nama']."',
					email='".$_REQUEST['email']."',
					level='".$_REQUEST['level']."',
					aktif='".$_REQUEST['aktif']."'

					where userid='".$_REQUEST['userid']."' ";
			$koneksi_db->sql_query($s);
			
			} else {
				$qf = $koneksi_db->sql_query( "SELECT * FROM user where username='".$_REQUEST['username']."'  limit 1 " );
				$totalf = $koneksi_db->sql_numrows($qf);
				if ($totalf > 0) { 
				echo '<div class=error>User '.$_REQUEST['username'].' sudah ada</div>'; 
				} else {
					
			  $s = "INSERT INTO m_asal.sekolah set 
			  		userid='".$_REQUEST['userid']."',
					username='".$_REQUEST['username']."',
					password='".md5($_REQUEST['username'])."',
					nama='".$_REQUEST['nama']."',
					email='".$_REQUEST['email']."',
					level='".$_REQUEST['level']."',
					aktif='".$_REQUEST['aktif']."'
					";
			  $koneksi_db->sql_query($s);
			  }
			}
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
        <font style="font-size:18px; color:#999999">Daftar User</font><br />
        	<a href="index.php">Home</a> &raquo; <a href="index.php?m='.$_GET['m'].'">User</a>  &raquo; '.$go.'  
    </div>';
echo '<div class="mainContentCell"><div class="content">';
$go();
echo '</div></div>';
?>
