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
$id = $_REQUEST['id'];

if (!empty($id) && isset($id)) 
{ 
 $wp = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM m_krs where id='$id' and kode_prodi='$prodi' limit 1 " ));
 	$sembunyi = 'style="display:block;"';
	$judul ="Edit Data Batas Jumlah SKS";
	$pilihprodi = $wp['kode_prodi'];
} else {
	$sembunyi = 'style="display:none;"';
	$judul ="Tambah Data Batas Jumlah SKS";
	$pilihprodi = $prodi;
}
echo"<input type=button  class=\"tombols ui-corner-all\" value='Tambah Batas SKS' onclick=\"return toggleView('form-hide')\" >";
echo'<div id="form-hide" '.$sembunyi.'>';
echo'<form action="" method="post"  class="cmxform" id="form_input" style="width:100%">
        <input type="hidden" name="m" value="'.$_GET['m'].'"/>
        <input type="hidden" name="op" value="simpan"/>
		<input type="hidden" name="id" value="'.$id.'"/> 
        <fieldset class="ui-widget ui-widget-content ui-corner-all" >
            <legend class="ui-widget ui-widget-header ui-corner-all">'.$judul.'</legend>
            &nbsp;<font color="red"><br></font>
      <table width="600"  class="datatable full1">		
                <tr>
                    <td  align="right" valign="top">Program Studi<font color="red"> *</font></td>
                    <td  >	<select name="kode_prodi"  style="width:300px" class="required full"   />'.opprodi(''.$pilihprodi.'').'</select>	</td>
                </tr>

                <tr>
                    <td width="150" align="right" valign="top">IPK Minimal<font color="red"> *</font></td>
                    <td  >	<input name="ipk_min"  type="text" class="required number" id="" value="'.$wp['ipk_min'].'" />	</td>
                </tr>
                <tr>
                    <td width="150" align="right" valign="top">IPK Maksimal<font color="red"> *</font></td>
                    <td  >	<input name="ipk_max"  type="text" class="required number" id="" value="'.$wp['ipk_max'].'" />	</td>
                </tr>
                <tr>
                    <td width="150" align="right" valign="top">Batas SKS<font color="red"> *</font></td>
                    <td  >	<input name="jml_sks"  type="text" class="required number" id="" value="'.$wp['jml_sks'].'" />	</td>
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
	   <th  align="center">IPK Min</th>
	   <th  align="center">IPK Max</th>
	   <th  align="center">Jml SKS</th>
	   <th width="30%" align="center"></th>
     </tr>
	 </thead>
	 <tbody>';
	
		$qpp = $koneksi_db->sql_query( "SELECT * FROM `m_krs` where kode_prodi='$prodi' " );
		$jumlah=$koneksi_db->sql_numrows($qpp);
		if ($jumlah > 0){
			while($wf = $koneksi_db->sql_fetchassoc($qpp)){
				$n++;
			  echo "<tr>
			  	<td $c>$n</td>
				<td $c>$wf[ipk_min]</td>
				<td $c>$wf[ipk_max]</td>
				<td $c>$wf[jml_sks]</td>
				<td $c>
					<a href=# class=btn onclick=window.location.href='index.php?m=".$_GET['m']."&op=Daftar&id=$wf[id]'><i class='fa fa-folder'></i></a>
					<a href=# class=btn onclick=window.location.href='index.php?m=".$_GET['m']."&op=Daftar&id=$wf[id]'><i class='fa fa-edit'></i></a>
					<a href=# class=btn onclick=window.location.href='index.php?m=".$_GET['m']."&op=hapus&id=$wf[id]'><i class='fa fa-trash-o'></i></a>
				</td>
				</tr>";
				
			}
		} else {
		echo '<tr > <th  colspan="5" align=center>Belum ada Data</th></tr>';
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
	$id = $_REQUEST['id'];  

	if (trim($_POST['jml_sks'])=="") {
		$pesan[] = "Form nilai masih kosong, ulangi kembali";
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
		
			if ( !empty($id)) {
			$s = "update m_krs set 
					jml_sks='".$_REQUEST['jml_sks']."',
					ipk_min='".$_REQUEST['ipk_min']."',
					ipk_max='".$_REQUEST['ipk_max']."'
					where id='".$_REQUEST['id']."' ";
			$koneksi_db->sql_query($s);
			
			} else {
			  $s = "INSERT INTO m_krs set 
					kode_pt='".$wi['kode_pt']."',
					kode_fak='".$wi['kode_fak']."',
					kode_jenjang='".$wi['kode_jenjang']."',
					kode_konsentrasi='".$wi['kode_konsentrasi']."',
					kode_prodi='".$_REQUEST['kode_prodi']."',
					ipk_min='".$_REQUEST['ipk_min']."',
					ipk_max='".$_REQUEST['ipk_max']."',
					jml_sks='".$_REQUEST['jml_sks']."'
					";
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
        <font style="font-size:18px; color:#999999">Batas Pengambilan SKS</font><br />
        	<a href="index.php">Home</a> &raquo; <a href="?m='.$_GET['m'].'">Batas Pengambilan SKS</a>  &raquo; '.$go.'  
    </div>';
echo '<div class="mainContentCell"><div class="content">';
$go();
echo '</div></div>';
?>
