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


function Agama() {
global $koneksi_db;
$prodi = $_SESSION['prodi'];
$id = $_REQUEST['id'];

if (!empty($id) && isset($id)) 
{ 
 $wp = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM r_kode where id='$id' and aplikasi='51' limit 1 " ));
 	$sembunyi = 'style="display:block;"';
	$judul ="Edit Agama";
	$pilihprodi = $wp['kode_prodi'];
} else {
	$sembunyi = 'style="display:none;"';
	$judul ="Tambah Agama";
	$pilihprodi = $prodi;
}
echo"<input type=button  class=\"tombols ui-corner-all\" value='Tambah Agama' onclick=\"return toggleView('form-hide')\" >";
echo'<div id="form-hide" '.$sembunyi.'>';
echo'<form action="" method="post"  class="cmxform" id="form_input" style="width:100%">
        <input type="hidden" name="m" value="'.$_GET['m'].'"/>
        <input type="hidden" name="op" value="simpanAgama"/>
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
                    <td width="150" align="right" valign="top">Deskripsi<font color="red"> *</font></td>
                    <td  >	<input name="parameter"  type="text" class=" required full" id="" value="'.$wp['parameter'].'" />	</td>
                </tr>				             
                 <tr>
                    <td colspan="2">
                        <input type="submit" class="tombols ui-corner-all" value="Simpan"/> 
                        <input type="button" class="tombols ui-corner-all" value="Kembali" onClick="window.location = \'index.php?m='.$_GET['m'].'&op=Agama\'"/></td>
                  </tr>					
            </table>
        </fieldset></form> ';
echo '</div>';

echo '<table width="100%" border="0" align="center" cellpadding="1" cellspacing="0" class="table setengah">
   	<thead>
     <tr>
	   <th width="5%" align="center">No</th>
	    <th  align="center">Kode</th>
	    <th  align="center">Deskripsi</th>
	   <th align="center"></th>
     </tr>
	 </thead>
	 <tbody>';
	
		$qpp = $koneksi_db->sql_query( "SELECT * FROM `r_kode` 
		where aplikasi='51' " );
		$jumlah=$koneksi_db->sql_numrows($qpp);
		if ($jumlah > 0){
			while($wf = $koneksi_db->sql_fetchassoc($qpp)){
				$n++;
			  echo "<tr>
			  	<td align='center' $c>$n.</td>
				<td align='center' $c>$wf[kode]</td>
				<td  $c>$wf[parameter]</td>
				<td $c>
					<a href=# class='btn btn-primary' onclick=window.location.href='index.php?m=".$_GET['m']."&op=Agama&id=$wf[id]'>
					<i class='fa fa-folder'></i>
					</a>
					<a href=# class='btn btn-success' onclick=window.location.href='index.php?m=".$_GET['m']."&op=Agama&id=$wf[id]'>
					<i class='fa fa-edit'></i>
					</a>
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
function simpanAgama() {
global $koneksi_db;
	$id = $_REQUEST['id'];  

	if (trim($_POST['kode'])=="") {
		$pesan[] = "Form kode Agama masih kosong, ulangi kembali";
	}

	else if (trim($_POST['parameter'])=="") {
		$pesan[] = "Form Nama Agama  masih kosong, ulangi kembali";
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
		echo "<meta http-equiv='refresh' content='2; url=index.php?m=".$_GET['m']."&op=Agama'>";
	} else {
		
			if ( !empty($id)) {
			$s = "update r_kode set 	
					kode='".strtoupper($_REQUEST['kode'])."',
					parameter='".strtoupper($_REQUEST['parameter'])."'
					where id='".$_REQUEST['id']."' ";
			$koneksi_db->sql_query($s);
			
			} else {
			  $s = "INSERT INTO r_kode set 
			  		aplikasi='51',
					keterangan='AGAMA',
			  		kode='".strtoupper($_REQUEST['kode'])."',
					parameter='".strtoupper($_REQUEST['parameter'])."'
					";
			  $koneksi_db->sql_query($s);
			}
		}
		echo "<meta http-equiv='refresh' content='2; url=index.php?m=".$_GET['m']."&op=Agama'>";
		//echo $s;
 Agama();
}



//$arrSub = array('Agama->Agama');
		
$go = (empty($_REQUEST['op'])) ? 'Agama' : $_REQUEST['op'];
echo '<div id="title" align="right">
        <font style="color:#FF9900; font-size:30px;"><strong>.:</strong></font>
        <font style="font-size:18px; color:#999999">Agama</font><br />
        	<a href="index.php">Home</a> &raquo; <a href="?m='.$_GET['m'].'">Agama</a>  &raquo; '.$go.'  
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

<script type="text/javascript"> 
    $('.pelajaran-top').click(function(){
        //alert($(this).attr("ibu"));
        $("div[anak='"+$(this).attr("ibu")+"']").toggle('fast');
    });
</script>