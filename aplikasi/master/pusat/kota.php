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

 

function Kota() {
//SekolahFilter();
global $koneksi_db;
$prodi = $_SESSION['prodi'];
$id = $_REQUEST['id'];

echo"<input type=button  class=\"tombols ui-corner-all\" value='Tambah Kota' onclick=\"window.location.href='index.php?m=".$_GET['m']."&op=AddKota';\">";
/*
  $whr = array();
  $ord = '';
  if (($_SESSION['reset_sekolah'] != 'Reset') &&
  !empty($_SESSION['kolom_sekolah']) && !empty($_SESSION['kunci_sekolah'])) {
    $whr[] = "$_SESSION[kolom_sekolah] like '%$_SESSION[kunci_sekolah]%' ";
    $ord = "order by $_SESSION[kolom_sekolah]";
  }
	if (!empty($whr)) $strwhr = "where " .implode(' and ', $whr);  
*/
echo '<div class="table-responsive">
<table class="table table-striped table-bordered table-hover" id="dataTables-example">
   	<thead>
     <tr>
	   <th width="5%" align="center">No</th>
	   <th align="center">Kode</th>
       <th align="center">Nama Kota</th>
	   <th align="center">Aksi</th>
     </tr>
	 </thead>
	 <tbody>';
	 
	 
	$sql = "select * from r_kota ";
	$q = $koneksi_db->sql_query( $sql );
	$jumlah=$koneksi_db->sql_numrows($q);
	if ($jumlah >= 1){
		while($wr = $koneksi_db->sql_fetchrow($hal_array["hasil"])){
		$n++;
		$id = $wr[0];
			
		echo '<tr bgcolor="#f2f2f2">
				<td  align=center>'.$n.'</td> 
				   <td  align=center>'.$wr[1].'</a></td>
				   <td  align=left>'.$wr[2].'</td>
					<td  align=center>
					<a href="#" class="btn btn-primary" onclick="window.location.href=\'index.php?m='.$_GET['m'].'&op=AddKota&id='.$id.'\';"><i class="fa fa-edit"></i></a>
					</td>
				 </tr>'; 
		
		}
	} else {
		 echo '<tr > 
			<th  colspan="5" align=center>Belum ada Data</th>
			</tr>';
	}
	 echo ' 
			
			</tbody>
		</table></div>';

 
}



function AddKota() {
global $koneksi_db;
$prodi = $_SESSION['prodi'];
$id = $_REQUEST['id'];

if (!empty($id) && isset($id)) 
{ 
 $wp = $koneksi_db->sql_fetchrow($koneksi_db->sql_query( "SELECT * FROM r_kota where id='$id' limit 1 " ));
 	$sembunyi = 'style="display:block;"';
	$judul ="Edit Kota";
	$pilihprodi = $wp['kode_prodi'];
} else {
	$sembunyi = 'style="display:none;"';
	$judul ="Tambah Kota";
	$pilihprodi = $prodi;
}

//$aktif = ($w['buka'] == 'Y') ? 'checked' : ''; 
	
echo '  
    <form action="" method="post"  class="cmxform" id="form_input" style="width:100%">
		<input type="hidden" name="id" value="'.$id.'"/>
         <input type="hidden" name="m" value="'.$_GET['m'].'"/>
        <input type="hidden" name="op" value="simpankota"/>
		<input type="hidden" name="md" value="'.$md.'"/>
       
            <table width="100%"  border="0" class="datatable full2">
                <tr>
                    <td align="left" valign="top">Kode<font color="red"> *</font></td>
                    <td>
					<input name="kode_kota"  type="text" class="required"  size="5" id="" value="'.$wp[1].'" />
					</td>
                </tr>
				
                <tr>
                    <td align="left" valign="top">Nama Kota<font color="red"> *</font></td>
                    <td>
					<input name="nama_kota"  type="text" size="40" class="full required" id="" value="'.$wp[2].'" />	
				
                 <tr>
                    <td colspan="2">
                        <input type="submit" class="tombols ui-corner-all" value="Simpan"/> 
                        <input type="button" class="tombols ui-corner-all" value="Kembali" onClick="window.location = \'index.php?m='.$_GET['m'].'\'"/></td>
                  </tr>
            </table>
    </form>
 ';

}


////simpan /
function simpankota() {
global $koneksi_db;
	$id = $_REQUEST['id'];  

	if (trim($_POST['kode_kota'])=="") {
		$pesan[] = "Form kode Kota masih kosong, ulangi kembali";
	}

	else if (trim($_POST['nama_kota'])=="") {
		$pesan[] = "Form Nama Kota  masih kosong, ulangi kembali";
	}
	
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
		echo "<meta http-equiv='refresh' content='2; url=index.php?m=".$_GET['m']."&op=Kota'>";
	} else {
		
			if ( !empty($id)) {
			$s = "update r_kota set 	
					kode_kota='".strtoupper($_REQUEST['kode_kota'])."',
					nama_kota='".strtoupper($_REQUEST['nama_kota'])."'
					where id='".$_REQUEST['id']."' ";
			$koneksi_db->sql_query($s);
			
			} else {
			  $s = "INSERT INTO r_kota set 
			  		kode_kota='".strtoupper($_REQUEST['kode_kota'])."',
					nama_kota='".strtoupper($_REQUEST['nama_kota'])."'
					";
			  $koneksi_db->sql_query($s);
			}
		}
		echo "<meta http-equiv='refresh' content='2; url=index.php?m=".$_GET['m']."&op=Kota'>";
		//echo $s;
 Kota();
}

/*
$kolom_sekolah = BuatSesi('kolom_sekolah');
$kunci_sekolah = BuatSesi('kunci_sekolah');

if ($_REQUEST['reset_sekolah'] == 'Reset') {
  $_SESSION['kolom_sekolah'] = '';
  $_SESSION['kunci_sekolah'] = '';
}
*/

$go = (empty($_REQUEST['op'])) ? 'Kota' : $_REQUEST['op'];

echo '<div id="title" align="right">
        <font style="color:#FF9900; font-size:30px;"><strong>.:</strong></font>
        <font style="font-size:18px; color:#999999">Konfigurasi Kota</font><br />
        	<a href="index.php">Home</a> &raquo; <a href="index.php?m='.$_GET['m'].'">Kota</a>  &raquo; '.$go.'  
    </div>';



echo '<div  class="panes" id="panel1" style="display: block;">
<div class="mainContentCell"><div class="content">	';
$go();
echo '</div></div></div>';




?>

<script src="assets/js/dataTables/jquery.dataTables.js"></script>
<script src="assets/js/dataTables/dataTables.bootstrap.js"></script>
<script>
$(document).ready(function () {
			$('#dataTables-example').dataTable();
});
</script>
