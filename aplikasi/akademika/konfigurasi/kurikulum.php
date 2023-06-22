<?php
if(ereg(basename (__FILE__), $_SERVER['PHP_SELF']))
{
	header("HTTP/1.1 404 Not Found");
	exit;
}
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
DaftarKurikulum();
}

function Kurikulum() {
global $koneksi_db;
$prodi = $_SESSION['prodi'];
$id = $_REQUEST['id'];

if (!empty($id) && isset($id)) 
{ 
 $wp = $koneksi_db->sql_fetchrow($koneksi_db->sql_query( "SELECT * FROM m_kurikulum where id='$id' and kode_prodi='$prodi' limit 1 " ));
 	$sembunyi = 'style="display:block;"';
	$judul ="Edit Data Kurikulum";
	$pilihprodi = $wp['kode_prodi'];
} else {
	$sembunyi = 'style="display:none;"';
	$judul ="Tambah Data Kurikulum";
	$pilihprodi = $prodi;
}
echo"<input type=button  class=\"tombols ui-corner-all\" value='Tambah Kurikulum' onclick=\"return toggleView('form-hide')\" >";
echo'<div id="form-hide" '.$sembunyi.'>';
echo'<form action="" method="post"  class="cmxform" id="form_input" style="width:100%">
        <input type="hidden" name="m" value="'.$_GET['m'].'"/>
        <input type="hidden" name="op" value="simpanKurikulum"/>
		<input type="hidden" name="id" value="'.$id.'"/> 
        <fieldset class="ui-widget ui-widget-content ui-corner-all" >
            <legend class="ui-widget ui-widget-header ui-corner-all">'.$judul.'</legend>
            &nbsp;<font color="red"><br></font>
            <table width="600"  border="0" class="datatable full1">		
                <tr>
                    <td width="150" align="right" valign="top">Program Studi<font color="red"> *</font></td>
                    <td  >	<select name="kode_prodi"  class="required"   />'.opprodi(''.$pilihprodi.'').'</select>	</td>
                </tr>
				
                <tr>
                    <td width="150" align="right" valign="top">Kode Kurikulum<font color="red"> *</font></td>
                    <td  >	<input name="kode_kur"  type="text" class=" required" id="" value="'.$wp['kode_kur'].'" />	</td>
                </tr>
                <tr>
                    <td width="150" align="right" valign="top">Nama Kurikulum<font color="red"> *</font></td>
                    <td  >	<input name="nama_kurikulum"  type="text" class=" required full" id="" value="'.$wp['nama_kurikulum'].'" />	</td>
                </tr>				
                <tr>
                    <td align="right"valign="top">Kelompok Kurikulum<font color="red"> *</font></td>
                    <td>
					<select name="kode_kelompok"  class="required "  />'.opAplikasi('10',''.$wp['kode_kelompok'].'').'</select>
					</td>
                </tr>	
                <tr>
                    <td align="right"valign="top">Jenis Kurikulum<font color="red"> *</font></td>
                    <td>
					<select name="kode_jenis"  class="required "  />'.opAplikasi('11',''.$wp['kode_jenis'].'').'</select>
					</td>
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
	    <th  align="center">Kode</th>
	    <th  align="center">Nama Kurikulum</th>
		<th  align="center">Kelompok</th>
		<th  align="center">Jenis</th>
	   <th width="30%" align="center"></th>
     </tr>
	 </thead>
	 <tbody>';
	
		$qpp = $koneksi_db->sql_query( "SELECT * FROM `m_kurikulum` 
		where kode_prodi='$prodi' " );
		$jumlah=$koneksi_db->sql_numrows($qpp);
		if ($jumlah > 0){
			while($wf = $koneksi_db->sql_fetchrow($qpp)){
				$n++;
			  echo "<tr>
			  	<td align='center' $c>$n.</td>
				<td align='center' $c>$wf[kode_kur]</td>
				<td align='center' $c>$wf[nama_kurikulum]</td>
				<td align='center' $c>".viewAplikasi('10',''.$wf['kode_kelompok'].'')."</td>
				<td align='center' $c>".viewAplikasi('11',''.$wf['kode_jenis'].'')."</td>
				<td $c>
					<a href=# onclick=window.location.href='index.php?m=".$_GET['m']."&op=Kurikulum&id=$wf[id]'><img src='images/view.png'/></a>
					<a href=# onclick=window.location.href='index.php?m=".$_GET['m']."&op=Kurikulum&id=$wf[id]'><img src='images/update.png'/></a>
				
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
function simpanKurikulum() {
global $koneksi_db;
	$id = $_REQUEST['id'];  

	if (trim($_POST['kode_kur'])=="") {
		$pesan[] = "Form kode Kurikulum masih kosong, ulangi kembali";
	}

	else if (trim($_POST['nama_kurikulum'])=="") {
		$pesan[] = "Form Nama Kurikulum masih kosong, ulangi kembali";
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
	$wi = $koneksi_db->sql_fetchrow($koneksi_db->sql_query( "SELECT * FROM m_program_studi where kode_prodi='".$_REQUEST['kode_prodi']."' limit 1 " ));
		
			if ( !empty($id)) {
			$s = "update m_kurikulum set 
					kode_pt='".$wi['kode_pt']."',
					kode_fak='".$wi['kode_fak']."',
					kode_jenjang='".$wi['kode_jenjang']."',
					kode_prodi='".$_REQUEST['kode_prodi']."',
			
					kode_kur='".$_REQUEST['kode_kur']."',
					nama_kurikulum='".$_REQUEST['nama_kurikulum']."',
					kode_kelompok='".$_REQUEST['kode_kelompok']."',
					kode_jenis='".$_REQUEST['kode_jenis']."'
					where id='".$_REQUEST['id']."' ";
			$koneksi_db->sql_query($s);
			
			} else {
			  $s = "INSERT INTO m_kurikulum set 
			  		kode_pt='".$wi['kode_pt']."',
					kode_fak='".$wi['kode_fak']."',
					kode_jenjang='".$wi['kode_jenjang']."',
					kode_prodi='".$_REQUEST['kode_prodi']."',
					
					kode_kur='".$_REQUEST['kode_kur']."',
					nama_kurikulum='".$_REQUEST['nama_kurikulum']."',
					kode_kelompok='".$_REQUEST['kode_kelompok']."',
					kode_jenis='".$_REQUEST['kode_jenis']."'
					";
			  $koneksi_db->sql_query($s);
			}
		}
		echo "<meta http-equiv='refresh' content='2; url=index.php?m=".$_GET['m']."'>";
		//echo $s;
 Kurikulum();
}


function Jenis() {
global $koneksi_db;
$prodi = $_SESSION['prodi'];
$id = $_REQUEST['id'];

if (!empty($id) && isset($id)) 
{ 
 $wp = $koneksi_db->sql_fetchrow($koneksi_db->sql_query( "SELECT * FROM r_kode where id='$id' and aplikasi='11' limit 1 " ));
 	$sembunyi = 'style="display:block;"';
	$judul ="Edit Jenis Kurikulum";
	$pilihprodi = $wp['kode_prodi'];
} else {
	$sembunyi = 'style="display:none;"';
	$judul ="Tambah Jenis Kurikulum";
	$pilihprodi = $prodi;
}
echo"<input type=button  class=\"tombols ui-corner-all\" value='Tambah Jenis Kurikulum' onclick=\"return toggleView('form-hide')\" >";
echo'<div id="form-hide" '.$sembunyi.'>';
echo'<form action="" method="post"  class="cmxform" id="form_input" style="width:100%">
        <input type="hidden" name="m" value="'.$_GET['m'].'"/>
        <input type="hidden" name="op" value="simpanJenis"/>
		<input type="hidden" name="id" value="'.$id.'"/> 
        <fieldset class="ui-widget ui-widget-content ui-corner-all" >
            <legend class="ui-widget ui-widget-header ui-corner-all">'.$judul.'</legend>
            &nbsp;<font color="red"><br></font>
            <table width="600"  border="0" class="datatable full1">		
				
                <tr>
                    <td width="150" align="right" valign="top">Kode<font color="red"> *</font></td>
                    <td  >	<input name="kode"  type="text" class=" required" id="" value="'.$wp['kode'].'" />	</td>
                </tr>
                <tr>
                    <td width="150" align="right" valign="top">Nama Jenis Kurikulum<font color="red"> *</font></td>
                    <td  >	<input name="parameter"  type="text" class=" required full" id="" value="'.$wp['parameter'].'" />	</td>
                </tr>				             
                 <tr>
                    <td colspan="2">
                        <input type="submit" class="tombols ui-corner-all" value="Simpan"/> 
                        <input type="button" class="tombols ui-corner-all" value="Kembali" onClick="window.location = \'index.php?m='.$_GET['m'].'&op=Jenis\'"/></td>
                  </tr>					
            </table>
        </fieldset></form> ';
echo '</div>';

echo '<table class="table table-striped table-bordered table-hover"  >
   	<thead>
     <tr>
	   <th width="5%" align="center">No</th>
	    <th  align="center">Kode</th>
	    <th  align="center">Jenis Kurikulum</th>
	   <th width="30%" align="center"></th>
     </tr>
	 </thead>
	 <tbody>';
	
		$qpp = $koneksi_db->sql_query( "SELECT * FROM `r_kode` 
		where aplikasi='11' " );
		$jumlah=$koneksi_db->sql_numrows($qpp);
		if ($jumlah > 0){
			while($wf = $koneksi_db->sql_fetchrow($qpp)){
				$n++;
			  echo "<tr>
			  	<td align='center' $c>$n.</td>
				<td align='center' $c>$wf[kode]</td>
				<td  $c>$wf[parameter]</td>
				<td $c>
					<a href=# onclick=window.location.href='index.php?m=".$_GET['m']."&op=Jenis&id=$wf[id]'><img src='images/view.png'/></a>
					<a href=# onclick=window.location.href='index.php?m=".$_GET['m']."&op=Jenis&id=$wf[id]'><img src='images/update.png'/></a>
				</td>
				</tr>";
			}
		} else {
		echo '<tr > <th  colspan="4" align=center>Belum ada Data</th></tr>';
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
function simpanJenis() {
global $koneksi_db;
	$id = $_REQUEST['id'];  

	if (trim($_POST['kode'])=="") {
		$pesan[] = "Form kode Jenis Kurikulum masih kosong, ulangi kembali";
	}

	else if (trim($_POST['parameter'])=="") {
		$pesan[] = "Form Nama Jenis Kurikulum masih kosong, ulangi kembali";
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
		echo "<meta http-equiv='refresh' content='2; url=index.php?m=".$_GET['m']."&op=Jenis'>";
	} else {
		
			if ( !empty($id)) {
			$s = "update r_kode set 	
					kode='".strtoupper($_REQUEST['kode'])."',
					parameter='".strtoupper($_REQUEST['parameter'])."'
					where id='".$_REQUEST['id']."' ";
			$koneksi_db->sql_query($s);
			
			} else {
			  $s = "INSERT INTO r_kode set 
			  		aplikasi='11',
					keterangan='JENIS KURIKULUM',
			  		kode='".strtoupper($_REQUEST['kode'])."',
					parameter='".strtoupper($_REQUEST['parameter'])."'
					";
			  $koneksi_db->sql_query($s);
			}
		}
		echo "<meta http-equiv='refresh' content='2; url=index.php?m=".$_GET['m']."&op=Jenis'>";
		//echo $s;
 Jenis();
}

function Kelompok() {
global $koneksi_db;
$prodi = $_SESSION['prodi'];
$id = $_REQUEST['id'];

if (!empty($id) && isset($id)) 
{ 
 $wp = $koneksi_db->sql_fetchrow($koneksi_db->sql_query( "SELECT * FROM r_kode where id='$id' and aplikasi='10' limit 1 " ));
 	$sembunyi = 'style="display:block;"';
	$judul ="Edit Jenis Kurikulum";
	$pilihprodi = $wp['kode_prodi'];
} else {
	$sembunyi = 'style="display:none;"';
	$judul ="Tambah Kelompok Kurikulum";
	$pilihprodi = $prodi;
}
echo"<input type=button  class=\"tombols ui-corner-all\" value='Tambah Kelompok Kurikulum' onclick=\"return toggleView('form-hide')\" >";
echo'<div id="form-hide" '.$sembunyi.'>';
echo'<form action="" method="post"  class="cmxform" id="form_input" style="width:100%">
        <input type="hidden" name="m" value="'.$_GET['m'].'"/>
        <input type="hidden" name="op" value="simpanKelompok"/>
		<input type="hidden" name="id" value="'.$id.'"/> 
        <fieldset class="ui-widget ui-widget-content ui-corner-all" >
            <legend class="ui-widget ui-widget-header ui-corner-all">'.$judul.'</legend>
            &nbsp;<font color="red"><br></font>
            <table width="600"  border="0" class="datatable full1">		
				
                <tr>
                    <td width="150" align="right" valign="top">Kode<font color="red"> *</font></td>
                    <td  >	<input name="kode"  type="text" class=" required" id="" value="'.$wp['kode'].'" />	</td>
                </tr>
                <tr>
                    <td width="150" align="right" valign="top">Nama Kelompok Kurikulum<font color="red"> *</font></td>
                    <td  >	<input name="parameter"  type="text" class=" required full" id="" value="'.$wp['parameter'].'" />	</td>
                </tr>				             
                 <tr>
                    <td colspan="2">
                        <input type="submit" class="tombols ui-corner-all" value="Simpan"/> 
                        <input type="button" class="tombols ui-corner-all" value="Kembali" onClick="window.location = \'index.php?m='.$_GET['m'].'&op=Kelompok\'"/></td>
                  </tr>					
            </table>
        </fieldset></form> ';
echo '</div>';

echo '<table class="table table-striped table-bordered table-hover"  >
   	<thead>
     <tr>
	   <th width="5%" align="center">No</th>
	    <th  align="center">Kode</th>
	    <th  align="center">Kelompok Kurikulum</th>
	   <th width="30%" align="center"></th>
     </tr>
	 </thead>
	 <tbody>';
	
		$qpp = $koneksi_db->sql_query( "SELECT * FROM `r_kode` 
		where aplikasi='10' " );
		$jumlah=$koneksi_db->sql_numrows($qpp);
		if ($jumlah > 0){
			while($wf = $koneksi_db->sql_fetchrow($qpp)){
				$n++;
			  echo "<tr>
			  	<td align='center' $c>$n.</td>
				<td align='center' $c>$wf[kode]</td>
				<td  $c>$wf[parameter]</td>
				<td $c>
					<a href=# onclick=window.location.href='index.php?m=".$_GET['m']."&op=Kelompok&id=$wf[id]'><img src='images/view.png'/></a>
					<a href=# onclick=window.location.href='index.php?m=".$_GET['m']."&op=Kelompok&id=$wf[id]'><img src='images/update.png'/></a>
				</td>
				</tr>";
			}
		} else {
		echo '<tr > <th  colspan="4" align=center>Belum ada Data</th></tr>';
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
function simpanKelompok() {
global $koneksi_db;
	$id = $_REQUEST['id'];  

	if (trim($_POST['kode'])=="") {
		$pesan[] = "Form kode Kelompok Kurikulum masih kosong, ulangi kembali";
	}

	else if (trim($_POST['parameter'])=="") {
		$pesan[] = "Form Nama Kelompok Kurikulum masih kosong, ulangi kembali";
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
		echo "<meta http-equiv='refresh' content='2; url=index.php?m=".$_GET['m']."&op=Kelompok'>";
	} else {
		
			if ( !empty($id)) {
			$s = "update r_kode set 	
					kode='".strtoupper($_REQUEST['kode'])."',
					parameter='".strtoupper($_REQUEST['parameter'])."'
					where id='".$_REQUEST['id']."' ";
			$koneksi_db->sql_query($s);
			
			} else {
			  $s = "INSERT INTO r_kode set 
			  		aplikasi='10',
					keterangan='KELOMPOK MATAKULIAH',
			  		kode='".strtoupper($_REQUEST['kode'])."',
					parameter='".strtoupper($_REQUEST['parameter'])."'
					";
			  $koneksi_db->sql_query($s);
			}
		}
		echo "<meta http-equiv='refresh' content='2; url=index.php?m=".$_GET['m']."&op=Kelompok'>";
		//echo $s;
 Kelompok();
}


$arrSub = array('Kelompok Kurikulum->Kelompok',
		'Jenis Kurikulum->Jenis',
		'Data Kurikulum->Kurikulum'
		);
		
$go = (empty($_REQUEST['op'])) ? 'Kurikulum' : $_REQUEST['op'];
echo '<div id="title" align="right">
        <font style="color:#FF9900; font-size:30px;"><strong>.:</strong></font>
        <font style="font-size:18px; color:#999999">Master Kurikulum</font><br />
        	<a href="index.php">Home</a> &raquo; <a href="index.php?m='.$_GET['m'].'">Kurikulum</a>  &raquo; '.$go.'  
    </div>';
	

	
	
echo '<div class="mainContentCell"><div class="content">';
	echo'<div  class="pagination"><ul >';
	  for ($i = 0; $i < sizeof($arrSub); $i++) {
		$mn = explode('->', $arrSub[$i]);
		$c = ($mn[1] == $go)? 'class=current' : '';
		echo "<li><a $c href='index.php?m=".$_GET['m']."&op=$mn[1]'><span>$mn[0]</span></a></li>";
	  }
	echo	'</ul></div>';
	echo '<div class="clear"></div>';
$go();
echo '</div></div>';
?>
