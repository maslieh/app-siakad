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
echo"<input type=button  class=\"tombols ui-corner-all\" value='Tambah Tahun Akademik' onclick=\"window.location.href='index.php?m=".$_GET['m']."&op=edit&md=1'\" >";



$sql = "select * from m_tahun";
$q = $koneksi_db->sql_query($sql);
$jumlah=$koneksi_db->sql_numrows($q);
if ($jumlah > 0){
echo '<table class="table table-striped table-bordered table-hover"  >
   	<thead>
     <tr>
	   <th width="15%" align="center">No</th>
	   <th width="25%" align="center">Kode</th>
       <th width="40%" align="center">Tahun</th>
	   <th width="20%" align="center">Aksi</th>
     </tr>
	 </thead>
	 <tbody>';
 
		while($wr = $koneksi_db->sql_fetchassoc($q)){
		$n++;
		$id = $wr['tahun_id'];
		
		if ($wr['buka']=='N') {
		$tombol = '<input class=button-red  type="submit"  name="BUKA" value="Tutup">';
		} else {
		$tombol = '<input class=button-blue  type="submit" disabled="disabled" name="TUTUP" value="Buka">';
		}
	
	
		echo '<tr >
				<td  >'.$n.'</td> 
				   <td  >'.$wr['tahun_id'].'</a></td>
				   <td  >'.$wr['nama_tahun'].'</td>
				   
					<td  >
					<a href="#" class="btn" onclick="window.location.href=\'index.php?m='.$_GET['m'].'&op=edit&md=0&id='.$id.'\';"><i class="fa fa-edit"></i></a>
					<a href="#" class="btn"  onclick="window.location.href=\'index.php?m='.$_GET['m'].'&op=hapus&id='.$id.'\';"><i class="fa fa-trash-o"></i></a>
					</td>
				 </tr>'; 
		
		}
		
		echo '</tbody>
		</table>';
	} else {
	
		echo '<div class="alert alert-danger">Belum ada Data</th>
			</div>';
	}
	 

 
}

function edit() {
global $koneksi_db;
  $md = $_REQUEST['md']+0;
  if ($md == 0) {
    $id = $_REQUEST['id'];
	$w = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM m_tahun where tahun_id='$id' limit 1 " ));
    $jdl = "Edit Data Tahun Akademik";

  }
  else {
    $w = array();
    $jdl = "Tambah Tahun Akademik";
  }

//$aktif = ($w['buka'] == 'Y') ? 'checked' : ''; 
	
echo '  
    <form action="" method="post"  class="cmxform" id="form_input" style="width:100%">
		<input type="hidden" name="id" value="'.$id.'"/>
         <input type="hidden" name="m" value="'.$_GET['m'].'"/>
        <input type="hidden" name="op" value="simpan"/>
		<input type="hidden" name="md" value="'.$md.'"/>
       
		<fieldset class="ui-widget ui-widget-content ui-corner-all" >
            <legend class="ui-widget ui-widget-header ui-corner-all">'.$jdl.'</legend>
            &nbsp;<font color="red"><br></font>
            <table width="100%"  border="0px" class="datatable full1">
                <tr>
                    <td  align="right" valign="top">Tahun<font color="red"> *</font></td>
                    <td  >
					<select name="tahun"  class="required number"   />'.optahun(''.$w['tahun'].'').'</select>
					</td>
                </tr>
                <tr>
                    <td align="right" valign="top">Semester<font color="red"> *</font></td>
                    <td>
					<select name="semester"  class="required number"   />'.opsemester(''.$w['semester'].'').'</select>
					</td>
                </tr>
                <tr>
                    <td align="right" valign="top">Nama Tahun<font color="red"> *</font></td>
                    <td>
					<input name="nama_tahun"  type="text" class="required" id="" value="'.$w['nama_tahun'].'" />
					</td>
                </tr>
				
                <tr>
                    <td align="right" valign="top">Batas KRS<font color="red"> *</font></td>
                    <td>
					<div class="col-md-6">
					<input name="krs_mulai"  type="text" class="tcal date required" id="" value="'.$w['krs_mulai'].'" />
					</div>
					<div class="col-md-6">
					<input name="krs_selesai"  type="text" class="tcal date required" id="" value="'.$w['krs_selesai'].'" />
					</div>
					</td>
                </tr>
               
                <tr>
                    <td align="right" valign="top">Perkuliahan<font color="red"> *</font></td>
                    <td>
					<div class="col-md-6">
					<input name="kuliah_mulai"  type="text" class="tcal date required" id="" value="'.$w['kuliah_mulai'].'" />
					</div><div class="col-md-6">
					<input name="kuliah_selesai"  type="text" class="tcal date required" id="" value="'.$w['kuliah_selesai'].'" />
					</div>
					</td>
                </tr>
                <tr>
                    <td align="right" valign="top">UTS<font color="red"> *</font></td>
                    <td>
					<div class="col-md-6">
					<input name="uts_mulai"  type="text" class="tcal date required" id="" value="'.$w['uts_mulai'].'" />
					</div><div class="col-md-6">
					<input name="uts_selesai"  type="text" class="tcal date required" id="" value="'.$w['uts_selesai'].'" />
					</div>
					</td>
                </tr>
                <tr>
                    <td align="right" valign="top">UAS<font color="red"> *</font></td>
                    <td><div class="col-md-6">
					<input name="uas_mulai"  type="text" class="tcal date required" id="" value="'.$w['uas_mulai'].'" />
					</div><div class="col-md-6">
					<input name="uas_selesai"  type="text" class="tcal date required" id="" value="'.$w['uas_selesai'].'" />
					</div></td>
                </tr>
				<tr>
                    <td align="right" valign="top">Batas Pengisian Nilai<font color="red"> *</font></td>
                    <td><div class="col-md-6">
					<input name="nilai_mulai"  type="text" class="tcal date required" id="" value="'.$w['nilai_mulai'].'" />
					</div><div class="col-md-6">
					<input name="nilai_selesai"  type="text" class="tcal date required" id="" value="'.$w['nilai_selesai'].'" />
					</div></td>
                </tr>
                
				
				
                 <tr>
                    <td colspan="2">
                        <input type="submit" class="tombols ui-corner-all" value="Simpan"/> 
                        <input type="button" class="tombols ui-corner-all" value="Kembali" onClick="window.location = \'index.php?m='.$_GET['m'].'\'"/></td>
                  </tr>
            </table>
        </fieldset>
    </form>
 ';

}

function detail() {


echo '<input type="button" class="tombols ui-corner-all" value="Kembali" onClick="window.location = \'index.php?m='.$_GET['m'].'\'"/></td>';
}
////simpan /
function simpan() {
global $koneksi_db;
  $md = $_REQUEST['md']+0;
	$id = $_REQUEST['id'];  

	if (trim($_POST['tahun'])=="") {
		$pesan[] = "Form Tahun masih kosong, ulangi kembali";
	}
	else if (trim($_POST['semester'])=="") {
		$pesan[] = "Form Semester masih kosong, ulangi kembali";
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
		echo "<meta http-equiv='refresh' content='1; url=index.php?m=".$_GET['m']."&op=edit&md=1'>";
	} else {
		
			if ($md == 0) {
			$s = "update m_tahun set 
					tahun='".$_REQUEST['tahun']."',
					semester='".$_REQUEST['semester']."',
					nama_tahun='".$_REQUEST['nama_tahun']."',
					krs_mulai='".$_REQUEST['krs_mulai']."',
					krs_selesai='".$_REQUEST['krs_selesai']."',
					kuliah_mulai='".$_REQUEST['kuliah_mulai']."',
					kuliah_selesai='".$_REQUEST['kuliah_selesai']."',
					uts_mulai='".$_REQUEST['uts_mulai']."',
					uts_selesai='".$_REQUEST['uts_selesai']."',
					uas_mulai='".$_REQUEST['uas_mulai']."',
					uas_selesai='".$_REQUEST['uas_selesai']."',
					nilai_mulai='".$_REQUEST['nilai_mulai']."',
					nilai_selesai='".$_REQUEST['nilai_selesai']."'
					where tahun_id='".$_REQUEST['id']."' ";
			$koneksi_db->sql_query($s);
			
			} else {
			  $idx = $_REQUEST['tahun']."".$_REQUEST['semester'];
			  $s = "INSERT INTO m_tahun set 
			  		tahun_id='".$idx."',
					tahun='".$_REQUEST['tahun']."',
					semester='".$_REQUEST['semester']."',
					nama_tahun='".$_REQUEST['nama_tahun']."',
					krs_mulai='".$_REQUEST['krs_mulai']."',
					krs_selesai='".$_REQUEST['krs_selesai']."',
					kuliah_mulai='".$_REQUEST['kuliah_mulai']."',
					kuliah_selesai='".$_REQUEST['kuliah_selesai']."',
					uts_mulai='".$_REQUEST['uts_mulai']."',
					uts_selesai='".$_REQUEST['uts_selesai']."',
					uas_mulai='".$_REQUEST['uas_mulai']."',
					uas_selesai='".$_REQUEST['uas_selesai']."',
					nilai_mulai='".$_REQUEST['nilai_mulai']."',
					nilai_selesai='".$_REQUEST['nilai_selesai']."'
					";
			  $koneksi_db->sql_query($s);
			}
		}
  Daftar();
}


function buka() {
global $koneksi_db;
	$sql = "UPDATE m_tahun SET 
			buka='Y'
			where tahun_id='".$_POST['id']."' ";
	mysql_query($sql);
	
	$sql2 = "UPDATE m_tahun SET 
			buka='N'
			where tahun_id!='".$_POST['id']."' ";
	mysql_query($sql2);	

Daftar();
}

$go = (empty($_REQUEST['op'])) ? 'Daftar' : $_REQUEST['op'];
echo '<div id="title" align="right">
        <font style="color:#FF9900; font-size:30px;"><strong>.:</strong></font>
        <font style="font-size:18px; color:#999999">Tahun Akademik</font><br />
        	<a href="index.php">Home</a> &raquo; <a href="?m='.$_GET['m'].'">Tahun</a>  &raquo; '.$go.'  
    </div>';
echo '<div class="mainContentCell"><div class="content">';
$go();
echo '</div></div>';
?>
