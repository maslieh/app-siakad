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
global $koneksi_db;
$prodi = $_SESSION['prodi'];
$idpra = $_REQUEST['idpra'];

if (!empty($idpra) && isset($idpra)) 
{ 
 $wp = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM m_mata_kuliah_prasyarat where idpra='$idpra' and kode_prodi='$prodi' limit 1 " ));
 	$sembunyi = 'style="display:block;"';
	$judul ="Edit Data Mata Kuliah Prasyarat";
	$pilihprodi = $wp['kode_prodi'];
} else {
	$sembunyi = 'style="display:none;"';
	$judul ="Tambah Data Mata Kuliah Prasyarat";
	$pilihprodi = $prodi;
}
echo"<input type=button  class=\"tombols ui-corner-all\" value='Tambah Mata Kuliah Prasyarat' onclick=\"return toggleView('form-hide')\" >
";
echo'<div id="form-hide" '.$sembunyi.'>';
echo'<form action="" method="post"  class="cmxform" id="form_input" style="width:100%">
        <input type="hidden" name="m" value="'.$_GET['m'].'"/>
        <input type="hidden" name="op" value="simpan"/>
		<input type="hidden" name="idpra" value="'.$idpra.'"/> 
        <fieldset class="ui-widget ui-widget-content ui-corner-all" >
            <legend class="ui-widget ui-widget-header ui-corner-all">'.$judul.'</legend>
            &nbsp;<font color="red"><br></font>
            <table class="table table-striped table-bordered table-hover"  >			
                <tr>
                    <td  align="right" valign="top">Mata Kuliah<font color="red"> *</font></td>
                    <td>	<select name="kode_mk1"  style="width:300px" class="required"   >'.opmatakuliah($prodi, '', ''.$wp['id'].'').'</select></td>
                </tr>
                <tr>
                    <td width="150" align="right" valign="top">Mata Kuliah Prasyarat<font color="red"> *</font></td>
                    <td  >	<select name="kode_mk2"  style="width:300px" class="required"   >'.opmatakuliah($prodi, '', ''.$wp['id_mp'].'').'</select></td>
                </tr>
               														
                 <tr>
                    <td colspan="2">
                        <input type="submit" class="tombols ui-corner-all" value="Simpan"/> 
                        <input type="button" class="tombols ui-corner-all" value="Kembali" onClick="window.location = \'index.php?m='.$_GET['m'].'\'"/></td>
                  </tr>					
            </table>
        </fieldset></form> ';
echo '</div>';

echo '<table class="table table-striped table-bordered table-hover"  >
   	<thead>
     <tr>
	   <th width="5%" align="center">No</th>
	   <th  align="center">Mata Kuliah</th>
	   <th  align="center">Mata Kuliah Prasyarat</th>
	   <th width="30%" align="center"></th>
     </tr>
	 </thead>
	 <tbody>';
	
		$qpp = $koneksi_db->sql_query( "SELECT mp.idpra, mp.id, mp.id_mp,m.id,m.nama_mk, m.kode_mk FROM `m_mata_kuliah_prasyarat` mp inner join m_mata_kuliah m on mp.id=m.id order by nama_mk" );
		
		$jumlah=$koneksi_db->sql_numrows($qpp);
		if ($jumlah > 0){
			while($wf = $koneksi_db->sql_fetchassoc($qpp)){
			$n++;
					  echo "<tr>
						<td $c>$n</td>
						<td $c>$wf[kode_mk] - $wf[nama_mk]</td>
						";
				$qpp2 = $koneksi_db->sql_query("SELECT kode_mk, nama_mk from m_mata_kuliah where id=$wf[id_mp]" );			
				$jumlah2=$koneksi_db->sql_numrows($qpp2);
				if ($jumlah2 > 0){
				
				$q2 = $koneksi_db->sql_fetchassoc($qpp2);
					
				echo "		
						<td >$q2[kode_mk] - $q2[nama_mk]</td>
						<td >
						
							<a href=# class=btn onclick=window.location.href='index.php?m=".$_GET['m']."&op=Daftar&idpra=$wf[idpra]'>
							<i class='fa fa-edit'></i></a>
							<a href=# class=btn onclick=window.location.href='index.php?m=".$_GET['m']."&op=hapus&idpra=$wf[idpra]'>
							<i class='fa fa-trash-o'></i></a>
						</td>
						</tr>";
				}	
			}
		} else {
		echo '<tr > <th  colspan="6" align=center>Belum ada Data</th></tr>';
		}

	

echo '</tbody>
		</table>';
echo '
<script type="text/javascript"> 
function toggleView(){
	//$("#toggleView").click(function(){
		$("#form-hide").toggle();
		return false;
}
</script>
';		
 
}



////simpan /
function simpan() {
global $koneksi_db;
$prodi = $_SESSION['prodi'];
	$idpra = $_REQUEST['idpra'];  

	if (trim($_POST['kode_mk1'])=="") {
		$pesan[] = "Form Mata Kuliah masih kosong, ulangi kembali";
	}
	else if (trim($_POST['kode_mk2'])=="") {
		$pesan[] = "Form Mata Kuliah Prasyarat masih kosong, ulangi kembali";
	}
	else if (trim($_POST['kode_mk1'])==($_POST['kode_mk2'])) {
		$pesan[] = "Form Mata Kuliah tidak boleh sama dengan Mata Kuliah Prasyarat, ulangi kembali";
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
		echo "<meta http-equiv='refresh' content='2; url=index.php?m=".$_GET['m']."'>";
	} else {
	$wi = $koneksi_db->sql_fetchrow($koneksi_db->sql_query( "SELECT * FROM m_program_studi where kode_prodi='".$_SESSION['prodi']."' limit 1 " ));
		
			if ( !empty($idpra)) {
			$s = "update m_mata_kuliah_prasyarat set 
					id='".$_POST['kode_mk1']."',
					id_mp='".$_POST['kode_mk2']."'
					where idpra='".$_POST['idpra']."' ";
			$koneksi_db->sql_query($s);
			
			} else {
			  $s = "INSERT INTO m_mata_kuliah_prasyarat set 
					kode_pt='".$wi['kode_pt']."',
					kode_fak='".$wi['kode_fak']."',
					kode_prodi='".$prodi."',
					kode_jenjang='".$wi['kode_jenjang']."',
					id='".$_REQUEST['kode_mk1']."',
					id_mp='".$_REQUEST['kode_mk2']."' ";
			  $koneksi_db->sql_query($s);
			  
			}
			
			echo "<meta http-equiv='refresh' content='0; url=index.php?m=".$_GET['m']."'>";
		}
	//echo $s;
 //Daftar();
}


$go = (empty($_REQUEST['op'])) ? 'Daftar' : $_REQUEST['op'];
echo '<div id="title" align="right">
        <font style="color:#FF9900; font-size:30px;"><strong>.:</strong></font>
        <font style="font-size:18px; color:#999999">Master Mata Kuliah Prasyarat</font><br />
        	<a href="index.php">Home</a> &raquo; <a href="index.php?m='.$_GET['m'].'">Mata Kuliah Prasyarat</a>  &raquo; '.$go.'  
    </div>';
echo '<div class="mainContentCell"><div class="content">';
$go();
echo '</div></div>';
?>
