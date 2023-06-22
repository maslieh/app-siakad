<?php 
if (!cek_pass_sama()) {
 
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


function Jadwal() {
global $koneksi_db, $tahun_id, $prodi;

 	
	if ($_SESSION['Level']!="DOSEN"	) {		
	echo"<input type=button class=button-red value='Tambah Jadwal' onclick=\"window.location.href='index.php?m=".$_GET['m']."&op=editJadwal&md=1';\">";
	}elseif ($_SESSION['Level']!="MAHASISWA"	) {		
	echo"<input type=button class=button-red value='Tambah Jadwal' onclick=\"window.location.href='index.php?m=".$_GET['m']."&op=editJadwal&md=1';\">";
	}
/////////////////
	$besar = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query("select max(hari) as besar from t_jadwal where kode_prodi='$prodi' and tahun_id='$tahun_id'" ));
		$besarx = $besar['besar'];
		
		if ( empty($besar) ) {
		
        echo '<div class="alert alert-danger">Mata Kuliah '.$prodi.' Kosong</div>';
		} else {
		//echo $besarx;
		for ($i=0; $i<$besarx; $i++) {
		$hri = $i+1 ;
    	$T = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query("select COUNT(id) as total from t_jadwal where kode_prodi='$prodi' and hari='$hri' and tahun_id='$tahun_id'" ));
	
			
echo '<div class="panel panel-success">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapse'.$i.'">
        HARI '.Terbilanghari($hri).' <span class="badge pull-right">'.$T['total'].' Matakuliah</span>
		</a>
      </h4>
    </div>';
		
		echo ' <div id="collapse'.$i.'" class="panel-collapse collapse">
        	<div class="panel-body">
<div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" >
					<tr>
						<th width="30" rowspan="2" valign="middle"align="center">Semester</th>
						<th width="30" rowspan="2" valign="middle"align="center">Waktu</th>
						<th width="40" rowspan="2" valign="middle"align="center">Ruang</th>
						<th width="20" rowspan="2" valign="middle"align="center">Kode / ID</th>
						<th width="120" rowspan="2" valign="middle"align="center">Mata Kuliah</th>
						<th width="10" rowspan="2" valign="middle"align="center">Kelas</th>
						<th width="10" rowspan="2" valign="middle" align="center">Kapasitas</th>
						<th width="10" rowspan="2" valign="middle" align="center">SKS</th>
						<th width="130" rowspan="2" valign="middle" align="center" >Dosen</th>
						<th width="10" rowspan="2" >&nbsp;</th>
					</tr>
					<tr>
						
					</tr>';
						$s = "select  * from view_jadwal
								where kode_prodi='$prodi' and hari='$hri' and tahun_id='$tahun_id'";
						$r = $koneksi_db->sql_query($s);
						$st = "select sum(sks_mk) as ttl from view_jadwal  where kode_prodi='$prodi' and hari='$hri' and tahun_id='$tahun_id'";
						$s_sks = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query($st));		
						while ($k = $koneksi_db->sql_fetchassoc($r)) {
						$id = $k['idj'];
						$tsks = $tsks + $k['sks_mk'];
						
						$pengampu =array();
						$pengampu = explode("|", $k[idd]);
						
                        echo '<tr>
								<td valign="top" align="center">'.$k['semester'].'</td>
								<td valign="top" align="center">'.$k['mulai'].'-'.$k['sampai'].'</td>
								<td valign="top" align="center">'.$k['nama_ruang'].'</td>
                                <td valign="top" align="center">'.$k['kode_mk'].' / '.$k['id'].'</td>
                                <td valign="top ">'.$k['nama_mk'].'</td>
								<td valign="top ">'.$k['kelas'].'</td>
								<td valign="top ">'.$k['kapasitas'].'</td>
								<td valign="top ">'.$k['sks_mk'].'</td>
								<td valign="top ">'.$k['nama_dosen'].','.$k['gelar_belakang'].'</td>
                                ';
						if ($_SESSION['Level']!="DOSEN"	) {
						echo '
								
								<td valign="top" ><a class="btn btn-primary"  href="index.php?m='.$_GET['m'].'&op=editJadwal&md=0&idj='.$id.'" >Edit</a></td>
                            </tr>';
							}
						}
                
			
		 echo '</table>
				</div>
				</div>
                <div class="panel-footer">
						Total SKS <span class="badge pull-right">'.$s_sks['ttl'].' SKS</span>                   
					</div>
             </div>';
		echo '</div>';
			
			
		}
		}	
////////////
	FormImport(); ?>&nbsp<?php 
}
function FormImport() {
	echo' <br/><br /><br />

<h4>Import Data Dosen</h4>
<div >
<a href="files/format_jadwal.xls" class="btn btn-danger">Download Format</a>
</div>
<div class="col-md-4">			
<form action="" class="form-horizontal" method="post" id="form_input" enctype="multipart/form-data">
	<input type="hidden" name="m" value="dosen" />
	<input type="hidden" name="op" value="Import"/>
<div class="form-group">
	<input type="file" name="fileimport" required  class="form-control">
	<button type="submit" class="btn btn-default">Proses Import Data</button>
</div>                   
   
</form>
</div>
<div class="col-md-8">	      
		<div class="alert alert-success">
          File yang diimport harus berekstensi .xls. <br/>
		 -- <b><font color=red>Untuk ID_MATA_KULIAH Bisa di lihat di pengaturan Matakuliah</font></b><br/>
		 -- <b><font color=red>Untuk ID_JAM Bisa di lihat di pengaturan jam kuliah</font></b><br/>
		 -- <b><font color=red>Untuk ID_RUANG Bisa di lihat di pengaturan Ruang</font></b><br/>
		 -- <b><font color=red>Untuk ID_DOSEN Bisa di lihat di pengaturan Dosen</font></b><br/>
		 -- <b><font color=red>Untuk Tahun ajar  Bisa di lihat di pengaturan Kurikulum</font></b><br/>
        </div>
</div>		<br/><br/><br/><br/><br/>
     ';
}


function Import() {
require("system/excel_reader2.php");
global $koneksi_db;
$prodi = $_SESSION['prodi'];
		$w = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM m_program_studi where kode_prodi='".$prodi."' limit 1 " ));
		$kodept = $w['kode_pt'];
		$kodefak = $w['kode_fak'];
		$kodejen = $w['kode_jenjang'];
		$tahun_id = $w['tahun_id'];
		
		//$filex_namex = strip(strtolower($_FILES['filesiswa']['tmp_name']));

		// membaca file excel yang diupload
		$data = new Spreadsheet_Excel_Reader($_FILES['fileimport']['tmp_name']);
		 
		// membaca jumlah baris dari data excel
		$baris = $data->rowcount($sheet_index=0);
		 
		// nilai awal counter untuk jumlah data yang sukses dan yang gagal diimport
		$sukses_import = 0;
		$sukses_update = 0;
		$gagal = 0;
		 
			// import data excel mulai baris ke-2
		for ($i=2; $i<=$baris; $i++)
		{
			//$idd = kdauto('m_dosen','D');
			  // membaca data nidn (kolom ke-1 dan seterusnya)
			  // kolom identitas dosen
			  $dosen = 			$data->val($i, 1); 
			  $ruang = 			$data->val($i, 2);
			  $kelass = 		$data->val($i, 3);
			  $id_mk =  		$data->val($i, 4);
			  $hari = 			$data->val($i, 5);
			  $idjam = 			$data->val($i, 6);
			  $kapasitas = 		$data->val($i, 7);
			  $kode_prdi = 		$data->val($i, 8);
			  $jenjang = 		$data->val($i, 9);
			  $tahun_id = 		$data->val($i, 10);
			  
			   
			  
			  		  		//insert data dosen
			$qada = "select * from t_jadwal where  idd='$dosen' AND kelas='$kelas'";
			$qryu = $koneksi_db->sql_query($qada);
			if ($koneksi_db->sql_numrows($qryu) > 0 ) {
			$su = "update t_jadwal SET 
					kode_pt='".$kodept."',
					kode_fak='".$kodefak."',
					kode_prodi='".$kode_prdi."',
					kode_jenjang='".$jenjang."',
					tahun_id='".$tahun_id."',
					idd='".$dosen."',
					idr='".$ruang."',
					kelas='".$kelass."',
					id='".$id_mk."',
					hari='".$hari."',
					jamke='".$idjam."',
					kapasitas='".$kapasitas."'
					WHERE idd = '".$dosen." AND kelas='".$kelas."'
				";

			$hasil_update =  $koneksi_db->sql_query($su);
			} else {
			$si = "insert into t_jadwal SET 
					kode_pt='".$kodept."',
					kode_fak='".$kodefak."',
					kode_prodi='".$kode_prdi."',
					kode_jenjang='".$jenjang."',
					idd='".$dosen."',
					tahun_id='".$tahun_id."',
					idr='".$ruang."',
					kelas='".$kelass."',
					id='".$id_mk."',
					hari='".$hari."',
					jamke='".$idjam."',
					kapasitas='".$kapasitas."'
				";
			$hasil_import = $koneksi_db->sql_query($si);
			
							  
			$nama_hasil .= "<li>".$kelass."</li>";		
			
			}
		  
			  if ($hasil_import) { $sukses_import++; } 
			  else if ($hasil_update) { $sukses_update++; } 
			  else   { $gagal++;  } 
		}

echo  "<fieldset class=cari>
	<legend> Proses import data </legend>
<table width=400 border=0>
	<tr><td align=right>Jumlah data yang sukses diimport </td><td width=100>: ".$sukses_import."</td></tr>
	<tr><td align=right>Jumlah data yang sudah ada </td><td>: ".$sukses_update."</td></tr>
	
	<tr><td align=right></td><td width=100><ul> ".$nama_hasil."</ul></td></tr>
	<tr><td align=right>Jumlah data yang gagal diimport </td><td>: ".$gagal."</td></tr>
</table>
</fieldset>	<br/>
<input id=kembali type=button class=button-red class=ui-button tombols ui-corner-all value=Kembali ke daftar Dosen style=\"font-size: 11px;height: inherit;\"  onclick=\"window.location='index.php?m=".$_GET['m']."'\">
	";
}


function editJadwal() {
global $koneksi_db, $prodi;

$idj = $_REQUEST['idj'];


if (!empty($idj) && isset($idj)) 
{ 
 $wp = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM t_jadwal where idj='$idj' limit 1 " ));
	$judul ="Edit Jadwal Perkuliahan";
	$semester = $wp['semester'];
	$prodi = $wp['kode_prodi'];
} else {
	$judul ="Tambah Jadwal Perkuliahan";
	$semester = $_REQUEST['s'];
	$prodi = $_SESSION['prodi'];
}



echo'<form action="" method="post"  class="cmxform" id="form_input" style="width:50%">
        <input type="hidden" name="m" value="'.$_GET['m'].'"/>
        <input type="hidden" name="op" value="simpan"/>
		<input type="hidden" name="idj" value="'.$idj.'"/> 
		<input type="hidden" name="semester" value="'.$semester.'"/> 
        <h4>'.$judul.'</h4>
            <table   border="0" class="table full2">	
                <tr>
                    <td  width="30%"  align="right" valign="top">Hari Kuliah<font color="red"> *</font></td>
                    <td  >	
					<select name="hari"  class="required number"   >'.opAplikasi('54',''.$wp['hari'].'').'</select>	
					</td>
                </tr>
                <tr>
                    <td  align="right" valign="top">Jam/Waktu<font color="red"> *</font></td>
                    <td  >	<select name="jamke"  class="required number"   >'.opjam($prodi, ''.$wp['jamke'].'').'</select>	</td>
                </tr>
                <tr>
                    <td   align="right" valign="top">Mata Kuliah<font color="red"> *</font></td>
                    <td  ><select name="kode_mk"  class="required"   >'.opmatakuliah($prodi, '', ''.$wp['id'].'').'</select></td>
                </tr>
				<tr>
                    <td  align="right" valign="top">Kelas<font color="red"> *</font></td>
                    <td  ><input name="kelas"  type="text" class=" required" id="" value="'.$wp['kelas'].'" /></td>
                </tr>
				<tr>
                    <td  align="right" valign="top">Kapasitas<font color="red"> *</font></td>
                    <td  ><input name="kapasitas"  type="text" class=" required" id="" value="'.$wp['kapasitas'].'" /></td>
                </tr>
				<tr>
                    <td  align="right" valign="top">Ruang<font color="red"> *</font></td>
                    <td  >	<select name="ruang"  class="required"   >'.opruang($prodi, ''.$wp['idr'].'', '').'</select></td>
                </tr>
				<tr>
					<td  align="right" valign="top">Dosen Pengampu<font color="red"> *</font></td>
					<td >	<select name="idd"  class="required"   />'.opdos(''.$wp['idd'].'').'</select></td>
				</tr>
				</table>
                
				';
						if ($_SESSION['Level']!="MAHASISWA" || $_SESSION['Level']!="DOSEN") {
                        echo '<input type="submit" class="tombols ui-corner-all" value="Simpan"/> ';
						}
                      echo '  <input type="button" class="tombols ui-corner-all" value="Kembali" onClick="window.location = \'index.php?m='.$_GET['m'].'\'"/>
					  		
            
      </form> ';


}



////simpan /
function simpan() {
global $koneksi_db, $tahun_id;
$prodi = $_SESSION['prodi'];
//$prodi = $_SESSION['prodi'];

	$idj = $_REQUEST['idj'];  

	if (trim($_POST['jamke'])=="") {
		$pesan[] = "Form Jammasih kosong, ulangi kembali";
	}
	else if (trim($_POST['kode_mk'])=="") {
		$pesan[] = "Form Mata Kuliah masih kosong, ulangi kembali";
	}
	else if (trim($_POST['kelas'])=="") {
		$pesan[] = "Form Kelas masih kosong, ulangi kembali";
	}
	else if (trim($_POST['ruang'])=="") {
		$pesan[] = "Form Ruang masih kosong, ulangi kembali";
	}
	else if (trim($_POST['hari'])=="") {
		$pesan[] = "Form Hari masih kosong, ulangi kembali";
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
	$wi = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM m_program_studi where kode_prodi='".$_SESSION['prodi']."' limit 1 " ));
		
			if ( !empty($idj)) {
			$s = "update t_jadwal set 
					idr='".$_REQUEST['ruang']."',
					kelas='".$_REQUEST['kelas']."',
					kapasitas='".$_REQUEST['kapasitas']."',
					id='".$_REQUEST['kode_mk']."',
					idd='".$_REQUEST['idd']."',
					hari='".$_REQUEST['hari']."',
					jamke='".$_REQUEST['jamke']."'
					where idj='".$_REQUEST['idj']."' ";
			 $koneksi_db->sql_query($s);
			echo "<div  class='error'>Proses Menyimpan Jadwal</div>";		  
			echo "<meta http-equiv='refresh' content='2; url=index.php?m=".$_GET['m']."'>";
			} else {
			
			/* $qada = $koneksi_db->sql_query("select * from t_jadwal 
								where kode_prodi='$prodi' 
								and tahun_id='$tahun_id' 
								and idd='".$_REQUEST['idd']."'
								and idr='".$_REQUEST['ruang']."'
								and kelas='".$_REQUEST['kelas']."'
								and id='".$_REQUEST['kode_mk']."'
								and hari='".$_REQUEST['hari']."'
								and jamke='".$_REQUEST['jamke']."'
								"); */
			$qada = $koneksi_db->sql_query("select * from t_jadwal 
								where kode_prodi='$prodi' 
								and tahun_id='$tahun_id' 
								and hari='".$_REQUEST['hari']."'
								and kelas='".$_REQUEST['kelas']."'
								and jamke='".$_REQUEST['jamke']."'
								");
								
			$qada2 = $koneksi_db->sql_query("select * from t_jadwal 
								where kode_prodi='$prodi' 
								and tahun_id='$tahun_id' 
								and hari='".$_REQUEST['hari']."'
								and idr='".$_REQUEST['ruang']."'
								and jamke='".$_REQUEST['jamke']."'
								");
								
			$qada3 = $koneksi_db->sql_query("select * from t_jadwal 
								where kode_prodi='$prodi' 
								and tahun_id='$tahun_id' 
								and hari='".$_REQUEST['hari']."'
								and idd='".$_REQUEST['idd']."'
								and jamke='".$_REQUEST['jamke']."'
								");					
								
			$jumlah=$koneksi_db->sql_numrows($qada);
			
			$jumlah2=$koneksi_db->sql_numrows($qada2);
			
			$jumlah3=$koneksi_db->sql_numrows($qada3);
			
				if ($jumlah > 0){
				echo "<div  class='error'>Jadwal Bentrok</div>";
				echo "<meta http-equiv='refresh' content='2; url=index.php?m=".$_GET['m']."'>";
				} 
				else if ($jumlah2 > 0){
				echo "<div  class='error'>Ruang Bentrok</div>";
				echo "<meta http-equiv='refresh' content='2; url=index.php?m=".$_GET['m']."'>";
				}
				else if ($jumlah3 > 0){
				echo "<div  class='error'>Dosen Bentrok</div>";
				echo "<meta http-equiv='refresh' content='2; url=index.php?m=".$_GET['m']."'>";
				}else {
				  $s = "INSERT INTO t_jadwal set 
						kode_pt='".$wi['kode_pt']."',
						kode_fak='".$wi['kode_fak']."',
						kode_jenjang='".$wi['kode_jenjang']."',
						kode_prodi='".$prodi."',
						tahun_id='".$tahun_id."',
						idd='".$_REQUEST['idd']."',
						idr='".$_REQUEST['ruang']."',
						kelas='".$_REQUEST['kelas']."',
						kapasitas='".$_REQUEST['kapasitas']."',
						id='".$_REQUEST['kode_mk']."',
						hari='".$_REQUEST['hari']."',
						jamke='".$_REQUEST['jamke']."'
						";
				  $koneksi_db->sql_query($s);
				  	//echo "<div  class='error'>Proses Menyimpan Jadwal</div>";		  
					//echo "<meta http-equiv='refresh' content='2; url=index.php?m=".$_GET['m']."'>";
				  }
				  $qp = $koneksi_db->sql_query( "SELECT * FROM t_dosen_pengajaran where 
					idr='".$_REQUEST['ruang']."'
					and kelas='".$_REQUEST['kelas']."'
					and id='".$_REQUEST['kode_mk']."'
					and tahun_id='".$tahun_id."' 
					limit 1 " );
					$totalp = $koneksi_db->sql_numrows($qp);
					if ($totalp > 0) {
					$k = $koneksi_db->sql_fetchassoc($qp);
					$sp = "update t_dosen_pengajaran set 
					idd='".$_REQUEST['idd']."'
					where id='".$k['kode_mk']."'  ";
					} else {
					$sp = "INSERT INTO t_dosen_pengajaran set 
					kode_pt='".$wi['kode_pt']."',
					kode_fak='".$wi['kode_fak']."',
					kode_jenjang='".$wi['kode_jenjang']."',
					kode_prodi='".$prodi."',
					tahun_id='".$tahun_id."',
					idd='".$_REQUEST['idd']."',
					idr='".$_REQUEST['ruang']."',
					kelas='".$_REQUEST['kelas']."',
					id='".$_REQUEST['kode_mk']."',
					hari='".$_REQUEST['hari']."'
					";
					}
					$koneksi_db->sql_query($sp);
			}
		}
	//echo $s;
 Jadwal();
}

		
$go = (empty($_REQUEST['op'])) ? 'Jadwal' : $_REQUEST['op'];
echo '<div id="title" align="right">
        <font style="color:#FF9900; font-size:30px;"><strong>.:</strong></font>
        <font style="font-size:18px; color:#999999">Jadwal Kuliah</font><br />
        	<a href="index.php">Home</a> &raquo; <a href="?m='.$_GET['m'].'">Jadwal</a>  &raquo; '.$go.'  
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

 
<?php } ?>