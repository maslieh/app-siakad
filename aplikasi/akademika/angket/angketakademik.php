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


function Angket() {
global $koneksi_db;
$id = $_REQUEST['id'];

if (!empty($id) && isset($id)) 
{ 
 $wp = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM m_angket_akademik where idangakad='$id' limit 1 " ));
 	$sembunyi = 'style="display:block;"';
	$judul ="Edit Angket Pendukung PBM";
} else {
	$sembunyi = 'style="display:none;"';
	$judul ="Tambah Angket Pendukung PBM";
}

echo"<input type=button  class=\"tombols ui-corner-all\" value='Tambah Angket Pendukung PBM' onclick=\"return toggleView('form-hide')\" > <br/>";
echo'<div id="form-hide" '.$sembunyi.'>';
echo'<form action="" method="post"  class="cmxform" id="form_input" style="width:100%">
        <input type="hidden" name="m" value="'.$_GET['m'].'"/>
        <input type="hidden" name="op" value="simpanAngket"/>
		<input type="hidden" name="id" value="'.$id.'"/> 
        <fieldset class="ui-widget ui-widget-content ui-corner-all" >
            <legend class="ui-widget ui-widget-header ui-corner-all">'.$judul.'</legend>
            &nbsp;<font color="red"><br></font>
            <br/><table width="600"  border="0" class="datatable full1">		
				
				<tr>
                    <td  align="right" valign="top">Bagian<font color="red"> *</font></td>
					<td  ><select name="bagian" style="width:100%" class="required"   /> '.opAplikasi('72',''.$wp['bagian'].'').' </select></td>
                </tr>
                <tr>
                    <td  align="right" valign="top">Pertanyaan<font color="red"> *</font></td>
                    <td  >	<textarea name="pertanyaan"  cols=40 rows=1 class=" required full" id="" >'.$wp['pertanyaan'].' </textarea>	</td>
					
                </tr>
	
                 <tr>
                    <td colspan="2">
                        <input type="submit" class="tombols ui-corner-all" value="Simpan"/> 
                        <input type="button" class="tombols ui-corner-all" value="Kembali" onClick="window.location = \'index.php?m='.$_GET['m'].'&op=Angket\'"/></td>
                  </tr>					
            </table>
        </fieldset></form> ';
echo '</div>';

echo '<br/><table width="100%" border="0" align="center" cellpadding="1" cellspacing="0" class="datatable Full">
   	<thead>
     <tr>
	   <th width="5%" align="center">No</th>
	    <th  width 300% align="center">Pertanyaan</th>
		<th  width 300% align="center">Bagian</th>
	   <th align="center">Ubah</th>
     </tr>
	 </thead>
	 <tbody>';
	
		$qpp = $koneksi_db->sql_query( "SELECT * FROM `m_angket_akademik`" );
		$jumlah=$koneksi_db->sql_numrows($qpp);
		if ($jumlah > 0){
			while($wf = $koneksi_db->sql_fetchassoc($qpp)){
				$n++;
			  echo "<tr>
			  	<td  $c>$n.</td>
				<td  $c>$wf[pertanyaan]</td>
				<td  $c>$wf[bagian]</td>
				<td $c>
					<a class='btn' href=# onclick=window.location.href='index.php?m=".$_GET['m']."&op=Angket&id=$wf[idangakad]'>
					<i class='fa fa-edit'></i></a>
				</td>
				</tr>";
			}
			echo '</tbody>
		</table>';
		
		} else {
		echo 'Belum ada Data';
		}

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
function simpanAngket() {
global $koneksi_db;
	$id = $_REQUEST['id'];  


	if (trim($_POST['pertanyaan'])=="") {
		$pesan[] = "Form Pertanyaan Angket  masih kosong, ulangi kembali";
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
		echo "<meta http-equiv='refresh' content='2; url=index.php?m=".$_GET['m']."&op=Angket'>";
	} else {
		
			if ( !empty($id)) {
			$s = "update m_angket_akademik 	set
					pertanyaan='".$_REQUEST['pertanyaan']."',
					bagian='".$_REQUEST['bagian']."'
					where idangakad='".$_REQUEST['id']."' ";
			$koneksi_db->sql_query($s);
			
			} else {
			  $s = "INSERT INTO m_angket_akademik set 
			  		pertanyaan='".$_REQUEST['pertanyaan']."',
					bagian='".$_REQUEST['bagian']."'
					";
			  $koneksi_db->sql_query($s);
			}
		}
		echo "<meta http-equiv='refresh' content='2; url=index.php?m=".$_GET['m']."&op=Angket'>";
		//echo $s;
 Angket();
}



		
$go = (empty($_REQUEST['op'])) ? 'Angket' : $_REQUEST['op'];
echo '<div id="title" align="right">
        <font style="color:#FF9900; font-size:30px;"><strong>.:</strong></font>
        <font style="font-size:18px; color:#999999">Angket Akademik dan Keuangan</font><br />
        	<a href="index.php">Home</a> &raquo; <a href="?m='.$_GET['m'].'">Angket Pendukung PBM</a>  &raquo; '.$go.'  
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