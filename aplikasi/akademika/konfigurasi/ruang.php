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
global $koneksi_db;
$prodi = $_SESSION['prodi'];
$idr = $_REQUEST['idr'];

if (!empty($idr) && isset($idr)) 
{ 
 $wp = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM m_ruang where idr='$idr' and kode_prodi='$prodi' limit 1 " ));
 	$sembunyi = 'style="display:block;"';
	$judul ="Edit Data Ruang";
	$pilihprodi = $wp['kode_prodi'];
} else {
	$sembunyi = 'style="display:none;"';
	$judul ="Tambah Data Ruang";
	$pilihprodi = $prodi;
}
echo"<input type=button  class=\"tombols ui-corner-all\" value='Tambah Ruang' onclick=\"return toggleView('form-hide')\" >";
echo'<div id="form-hide" '.$sembunyi.'>';
echo'<form action="" method="post"  class="cmxform" id="form_input" style="width:100%">
        <input type="hidden" name="m" value="'.$_GET['m'].'"/>
        <input type="hidden" name="op" value="simpan"/>
		<input type="hidden" name="idr" value="'.$idr.'"/> 
        <fieldset class="ui-widget ui-widget-content ui-corner-all" >
            <legend class="ui-widget ui-widget-header ui-corner-all">'.$judul.'</legend>
            &nbsp;<font color="red"><br></font>
            <table width="600"  border="0" class="datatable full1">		
                <tr>
                    <td  align="right" valign="top">Program Studi<font color="red"> *</font></td>
                    <td  >	<select name="kode_prodi"  style="width:300px" class="required"   />'.opprodi(''.$pilihprodi.'').'</select>	</td>
                </tr>
				<tr>
                    <td width="150" align="right" valign="top">Kode Ruang<font color="red"> *</font></td>
                    <td  >	<input name="kode_ruang"  type="text" class=" required" id="" value="'.$wp['kode_ruang'].'" />	</td>
                </tr>
                <tr>
                    <td width="150" align="right" valign="top">Nama Ruang<font color="red"> *</font></td>
                    <td  >	<input name="nama_ruang"  type="text" class=" required" id="" value="'.$wp['nama_ruang'].'" />	</td>
                </tr>
				<tr>
                    <td width="150" align="right" valign="top">Lantai<font color="red"> *</font></td>
                    <td  >	<input name="lantai"  type="text" class=" required" id="" value="'.$wp['lantai'].'" />	</td>
                </tr>
                <tr>
                    <td width="150" align="right" valign="top">Kapasitas<font color="red"> *</font></td>
                    <td  >	<input name="kapasitas"  type="text" class=" required number" id="" value="'.$wp['kapasitas'].'" />	</td>
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
		<th  align="center">ID RUANG</th>
		<th  align="center">Kode Ruang</th>
		<th  align="center">Nama Ruang</th>
		<th  align="center">Lantai</th>
		<th  align="center">Kapasitas</th>

	   <th width="30%" align="center"></th>
     </tr>
	 </thead>
	 <tbody>';
	
		$qpp = $koneksi_db->sql_query( "SELECT k.*, d.* FROM `m_ruang` k
		left join m_dosen d on k.penasehat=d.idd
		where k.kode_prodi='$prodi' " );
		$jumlah=$koneksi_db->sql_numrows($qpp);
		if ($jumlah > 0){
			while($wf = $koneksi_db->sql_fetchassoc($qpp)){
				$n++;
			  echo "<tr>
			  	<td >$n.</td>
				<td >$wf[idr]</td>
				<td >$wf[kode_ruang]</td>
				<td >$wf[nama_ruang]</td>
				<td >$wf[lantai]</td>
				<td >$wf[kapasitas]</td>
		
			
				<td  >
					<a href=# class=btn onclick=window.location.href='index.php?m=".$_GET['m']."&op=Daftar&idr=$wf[idr]'><i class='fa fa-folder'></i></a>
					<a href=# class=btn onclick=window.location.href='index.php?m=".$_GET['m']."&op=Daftar&idr=$wf[idr]'><i class='fa fa-edit'></i></a>
				
				</td>
				</tr>";
				
			}
		} else {
		echo '<tr > <th  colspan="7" align=center>Belum ada Data</th></tr>';
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
	$idr = $_REQUEST['idr'];  

	if (trim($_POST['kode_ruang'])=="") {
		$pesan[] = "Form Kode Ruang masih kosong, ulangi kembali";
	}
	
	else if (trim($_POST['nama_ruang'])=="") {
		$pesan[] = "Form Nama Ruang masih kosong, ulangi kembali";
	}
	
	else if (trim($_POST['kode_prodi'])=="") {
		$pesan[] = "Form Prodi masih kosong, ulangi kembali";
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
	$wi = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM m_program_studi where kode_prodi='".$_REQUEST['kode_prodi']."' limit 1 " ));
		
			if ( !empty($idr)) {
			$s = "update m_ruang set 
					kode_pt='".$wi['kode_pt']."',
					kode_fak='".$wi['kode_fak']."',
					kode_jenjang='".$wi['kode_jenjang']."',
					kode_konsentrasi='".$wi['kode_konsentrasi']."',
					kode_prodi='".$_REQUEST['kode_prodi']."',
					nama_ruang='".$_REQUEST['nama_ruang']."',
					kode_ruang='".$_REQUEST['kode_ruang']."',
					lantai='".$_REQUEST['lantai']."',
					kapasitas='".$_REQUEST['kapasitas']."',
					penasehat='".$_REQUEST['penasehat']."'
					where idr='".$_REQUEST['idr']."' ";
			$koneksi_db->sql_query($s);
			
			} else {
			  $s = "INSERT INTO m_ruang set 
			  		kode_pt='".$wi['kode_pt']."',
					kode_fak='".$wi['kode_fak']."',
					kode_jenjang='".$wi['kode_jenjang']."',
					kode_konsentrasi='".$wi['kode_konsentrasi']."',
					kode_prodi='".$_REQUEST['kode_prodi']."',
					nama_ruang='".$_REQUEST['nama_ruang']."',
					kode_ruang='".$_REQUEST['kode_ruang']."',
					lantai='".$_REQUEST['lantai']."',
					kapasitas='".$_REQUEST['kapasitas']."',
					penasehat='".$_REQUEST['penasehat']."'
					";
			  $koneksi_db->sql_query($s);
			}
		}
		echo "<meta http-equiv='refresh' content='2; url=index.php?m=".$_GET['m']."'>";
		//echo $s;
 Daftar();
}


$go = (empty($_REQUEST['op'])) ? 'Daftar' : $_REQUEST['op'];
echo '<div id="title" align="right">
        <font style="color:#FF9900; font-size:30px;"><strong>.:</strong></font>
        <font style="font-size:18px; color:#999999">Master Ruang</font><br />
        	<a href="index.php">Home</a> &raquo; <a href="index.php?m='.$_GET['m'].'">Ruang</a>  &raquo; '.$go.'  
    </div>';
echo '<div class="mainContentCell"><div class="content">';
$go();
echo '</div></div>';
?>
