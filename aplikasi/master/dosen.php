<?php

if (!cek_login ()){
header ("location:index.php");
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
/*
if ($_SESSION['Level']!="ADMIN"	) {
header ("location:index.php");
exit;
}*/


function hapus() {
echo "<div class=error>Ma'af.. Data tidak dapat dihapus, hanya bisa diedit</div>";
Daftar();
}




function Daftar() {
echo '<div class="row"><div class="col-md-4">';
echo"<input type=button  class=\"tombols ui-corner-all\" value='Tambah Dosen' onclick=\"window.location.href='index.php?m=".$_GET['m']."&op=add&md=1';\">";
 
echo '</div><div class="col-md-8">';
 FilterDosen('dosen');
 echo '</div></div>';
global $koneksi_db;
$prodi = $_SESSION['prodi'];
  $whr = array();
  $ord = '';
  if (($_SESSION['reset_dosen'] != 'Reset') &&
  !empty($_SESSION['kolom_dosen']) && !empty($_SESSION['kunci_dosen'])) {
    $whr[] = "$_SESSION[kolom_dosen] like '%$_SESSION[kunci_dosen]%' ";
    $ord = "order by $_SESSION[kolom_dosen]";
  }
  	if (!empty($_SESSION['prodi'])) $whr[] = "kode_prodi='$_SESSION[prodi]'";
	if (!empty($whr)) $strwhr = "where " .implode(' and ', $whr);  

require('system/pagination_class.php');
$sql = "select * from m_dosen $strwhr $ord";
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
echo '<div class="table-responsive">
<table class="table table-striped table-bordered table-hover" >
   	<thead>
     <tr>
	   <th width="5%" align="center">No</th>
	   <th width="10%" align="center">ID DOSEN</th>
	   <th align="center">NIP</th>
	   <th align="center">NIDN</th>
       <th align="center">Nama</th>
       <th align="center">Kontak</th>
	   <th align="center">Edit</th>
     </tr>
	 </thead>
	 <tbody>';
	 
 
		while($wr = $koneksi_db->sql_fetchassoc($result)){
		$n++;
		$idd = $wr['idd'];
		echo '<tr bgcolor="#f2f2f2">
				<td  >'.$n.'</td> 
				<td  >'.$wr['idd'].'</a></td>
				<td  >'.$wr['nip'].'</a></td>
				<td  >'.$wr['NIDN'].'</a></td>
				<td  align=left>'.$wr['gelar_depan'].' '.$wr['nama_dosen'].','.$wr['gelar_belakang'].'</td>
				<td  align=left>'.$wr['telephon'].'-'.$wr['hp'].'-'.$wr['email'].'</td>
				<td  >
					
					<a href="#" class="btn btn-primary" onclick="window.location.href=\'index.php?m='.$_GET['m'].'&op=edit&idd='.$idd.'\';"><i class="fa fa-edit"></i></a>
			
				</td>
			</tr>'; 
		}
		 echo '</tbody>
		</table></div>';
		
	echo $obj->total;
	echo '<br/>';
	echo $obj->anchors;	
	} else {
		 echo '<div class="alert alert-danger">Belum ada Data</th>
			</div>';
	}
	

 
////////////FormImport(); ?>&nbsp<?php 
	
	FormImport(); ?>&nbsp<?php 

}

function detail() {
//include 'admin/siswa_detail.php';
}

function add() {
global $koneksi_db;
  $md = $_REQUEST['md']+0;
  $idd = $_REQUEST['idd'];
  if (!empty($idd) && isset($idd)) {
	$w = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM m_dosen where idd='$idd' limit 1 " ));
    $jdl = "Biodata Dosen";
	$kode = '<input name="kode_"  disabled="disabled"  type="text" class="" id="" value="'.$w['idd'].'" />
		<input name="idd"  type="hidden" required  id="" value="'.$w['idd'].'" />
	';

  }
  else {
    $w = array();
	$autokode = kdauto('m_dosen','D');
    $jdl = "Tambah Data Dosen";
	$kode = '<input name="kode_"  disabled="disabled"  type="text" class="" id="" value="'.$autokode.'" />
		<input name="idd"  type="hidden" required  id="" value="'.$autokode.'" />
	';

  }


//$aktif = ($w['buka'] == 'Y') ? 'checked' : ''; 
	
echo '  
    <form action="" method="post"  class="cmxform" id="form_input" style="width:100%"enctype="multipart/form-data">
        <input type="hidden" name="m" value="dosen"/>
        <input type="hidden" name="op" value="simpanadd"/>
		<input type="hidden" name="md" value="'.$md.'"/>
		<input type="hidden" name="idd" value="'.$idd.'"/>
        <fieldset class="ui-widget ui-widget-content ui-corner-all" >
            <legend class="ui-widget ui-widget-header ui-corner-all">'.$jdl.' </legend>
            &nbsp;<font color="red"><br></font>
            <table width="600"  border="0" class="datatable full1">
                <tr>
					<td width="200" align="left" valign="top">ID Record<font color="red"> *</font></td>
                    <td width="400" >'.$kode.'</td>
                </tr>
				<tr>
                    <td align="left" valign="top">NIP<font color="red"> *</font></td>
                    <td><input name="nip"  type="text" required  value="'.$w['nip'].'" /></td>
                </tr>
                <tr>
                    <td align="left" valign="top">NIDN<font color="red"></font></td>
                    <td><input name="NIDN"  type="text" class="" value="'.$w['NIDN'].'" /></td>
                </tr>
                <tr>
                    <td align="left" valign="top">Nama Dosen<font color="red"> *</font></td>
                    <td><input name="nama_dosen"  type="text" required   value="'.$w['nama_dosen'].'" /></td>
                </tr>
                <tr>
                    <td align="left" valign="top">Gelar Depan<font color="red"> </font></td>
                    <td><input name="gelar_depan"  type="text" class=""  value="'.$w['gelar_depan'].'" /></td>
                </tr>
                <tr>
                    <td align="left" valign="top">Gelar Belakang<font color="red"> </font></td>
                    <td><input name="gelar_belakang"  type="text" class=""  value="'.$w['gelar_belakang'].'" /></td>
                </tr>				
                <tr>
                    <td align="left" valign="top">Tempat Lahir<font color="red"> *</font></td>
                    <td><input name="tempat_lahir"  type="text" required   value="'.$w['tempat_lahir'].'" /></td>
                </tr>
                <tr>
                    <td align="left" valign="top">Tanggal Lahir<font color="red"> *</font></td>
                    <td><input name="tanggal_lahir"  type="text" class="tcal date"  required value="'.$w['tanggal_lahir'].'" /></td>
                </tr>				
                <tr>
                    <td  align="left" valign="top">Jenis Kelamin<font color="red"> *</font></td>
                    <td  ><select name="jenis_kelamin"  required    />'.opAplikasi('08',''.$w['jenis_kelamin'].'').'</select></td>
                </tr>
                <tr>
                    <td  align="left" valign="top">Agama<font color="red"> *</font></td>
                    <td  ><select name="agama"  required    />'.opAplikasi('51',''.$w['agama'].'').'</select></td>
                </tr>
                <tr>
                    <td align="left" valign="top">No KTP<font color="red"> *</font></td>
                    <td><input name="ktp"  type="text" required   value="'.$w['ktp'].'" /></td>
                </tr>				
                <tr>
                    <td align="left" valign="top">No. Telepon<font color="red"> *</font></td>
                    <td><input name="telephon"  type="text" required   value="'.$w['telephon'].'" /></td>
                </tr>				
                <tr>
                    <td align="left" valign="top">No. HP<font color="red"> *</font></td>
                    <td><input name="hp"  type="text" required   value="'.$w['hp'].'" /></td>
                </tr>				
                <tr>
                    <td align="left" valign="top">Email<font color="red"> *</font></td>
                    <td><input name="email"  type="text" required   value="'.$w['email'].'" /></td>
                </tr>				
                <tr>
                    <td  align="left" valign="top">Program Studi<font color="red"> *</font></td>
                    <td  ><select name="kode_prodi"  required    />'.opprodi(''.$w['kode_prodi'].'').'</select></td>
                </tr>
				<tr>
				<td align="left" valign="top">Foto<font color="red"> *</font></td>
					<td><input name="gambar"  type="file"   /> <font color="red"><i>Ukuran Foto harus 4:3 tidak boleh lebih 2mb</i></font></td>
					
				</tr>
                 <tr>
                    <td colspan="2">
                        <input type="submit" class="tombols ui-corner-all" value="Simpan"/> 
                        <input type="button" class="tombols ui-corner-all" value="Kembali" onClick="window.location =\'index.php?m='.$_GET['m'].'\'"/></td>
                  </tr>
            </table>
        </fieldset>
    </form>
 ';

}


////simpan /
function simpanadd() {
global $koneksi_db;
  $md = $_REQUEST['md']+0;
	$idd = $_REQUEST['idd'];  

		$wi = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM m_program_studi where kode_prodi='".$_REQUEST['kode_prodi']."' limit 1 " ));

			if ($md == 0) {
				
					$rand = rand();
					$ekstensi =  array('png','jpg','jpeg','gif','JPG','PNG','JPEG');
					$nama_file = $_FILES['gambar']['name'];
					$ukuran = $_FILES['gambar']['size'];
					$ext = pathinfo($nama_file, PATHINFO_EXTENSION);
					
				if($nama_file != "") {
					
						if(!in_array($ext,$ekstensi) ) {
						echo '<script language="javascript">';
							echo 'alert("Upload gagal, ekstensi harus jpeg, jpg, png,tif,JPG,PNG)';
							echo '</script>';
					}else{
						if($ukuran < 2044070){		
							$xx = $rand.'_'.$nama_file;
							move_uploaded_file($_FILES['gambar']['tmp_name'], 'gambar/'.$rand.'_'.$nama_file);
								$_tahun = substr($tahun_id, 0, 4)+0;
								$s = "update m_dosen set 
								nip='".$_REQUEST['nip']."',
								NIDN='".$_REQUEST['NIDN']."',
								kode_pt='".$wi['kode_pt']."',
								kode_fak='".$wi['kode_fak']."',
								kode_prodi='".$_REQUEST['kode_prodi']."',
								kode_jenjang='".$wi['kode_jenjang']."',
								nama_dosen='".$_REQUEST['nama_dosen']."',
								gelar_depan='".$_REQUEST['gelar_depan']."',
								gelar_belakang='".$_REQUEST['gelar_belakang']."',
								agama='".$_REQUEST['agama']."',
								jenis_kelamin='".$_REQUEST['jenis_kelamin']."',
								tempat_lahir='".$_REQUEST['tempat_lahir']."',
								tanggal_lahir='".$_REQUEST['tanggal_lahir']."',
								ktp='".$_REQUEST['ktp']."',
								telephon='".$_REQUEST['telephon']."',
								foto='".$xx."',
								hp='".$_REQUEST['hp']."',
								email='".$_REQUEST['email']."'

								where idd='".$_REQUEST['idd']."' ";
						$r = $koneksi_db->sql_query($s);
							echo '<script language="javascript">';
							echo 'alert("Upload foto berhasil")';
							echo '</script>';
						}else{
							echo '<script language="javascript">';
							echo 'alert("Upload gagal ukuran file terlalu besar, ukuran tidak boleh lebih dari 2MB")';
							echo '</script>';
						}
					}
				
				}else{
						$s = "update m_dosen set 
								nip='".$_REQUEST['nip']."',
								NIDN='".$_REQUEST['NIDN']."',
								kode_pt='".$wi['kode_pt']."',
								kode_fak='".$wi['kode_fak']."',
								kode_prodi='".$_REQUEST['kode_prodi']."',
								kode_jenjang='".$wi['kode_jenjang']."',
								nama_dosen='".$_REQUEST['nama_dosen']."',
								gelar_depan='".$_REQUEST['gelar_depan']."',
								gelar_belakang='".$_REQUEST['gelar_belakang']."',
								agama='".$_REQUEST['agama']."',
								jenis_kelamin='".$_REQUEST['jenis_kelamin']."',
								tempat_lahir='".$_REQUEST['tempat_lahir']."',
								tanggal_lahir='".$_REQUEST['tanggal_lahir']."',
								ktp='".$_REQUEST['ktp']."',
								telephon='".$_REQUEST['telephon']."',
								hp='".$_REQUEST['hp']."',
								email='".$_REQUEST['email']."'

								where idd='".$_REQUEST['idd']."' ";
								$r = $koneksi_db->sql_query($s);
				}
			
				$si = "update user SET 
							userid='".$idd."',
							username='".$_REQUEST['nip']."',
							nama='".$_REQUEST['nama_dosen']."',
							email='".$_REQUEST['email']."',
							level='DOSEN' 
							where userid='".$_REQUEST['idd']."' 
							";
				$koneksi_db->sql_query($si);
			
			} else {
				$qd = $koneksi_db->sql_query( "SELECT * FROM m_dosen where nip='".$_REQUEST['nip']."' limit 1 " );
				$totald = $koneksi_db->sql_numrows($qd);
				$wd = $koneksi_db->sql_fetchassoc($qd);
				if ($totald > 0) { 
				echo '<div class=error>Kode NIP '.$_REQUEST['nip'].' sudah dipakai oleh '.$wd['nama_dosen'].'</div>'; 
				} else {
				$rand = rand();
				$ekstensi =  array('png','jpg','jpeg','gif','JPG','PNG','JPEG');
				$nama_file = $_FILES['gambar']['name'];
				$ukuran = $_FILES['gambar']['size'];
				$ext = pathinfo($nama_file, PATHINFO_EXTENSION);
				
				if($nama_file != "") {
					if(!in_array($ext,$ekstensi) ) {
					echo '<script language="javascript">';
						echo 'alert("Upload gagal, ekstensi harus jpeg, jpg, png,tif,JPG,PNG)';
						echo '</script>';
				}else{
					if($ukuran < 2044070){		
						$xx = $rand.'_'.$nama_file;
						move_uploaded_file($_FILES['gambar']['tmp_name'], 'gambar/'.$rand.'_'.$nama_file);
							$_tahun = substr($tahun_id, 0, 4)+0;
							$s = "insert into m_dosen SET 
							idd='".$_REQUEST['idd']."',
							nip='".$_REQUEST['nip']."',
							NIDN='".$_REQUEST['NIDN']."',
							kode_pt='".$wi['kode_pt']."',
							kode_fak='".$wi['kode_fak']."',
							kode_prodi='".$_REQUEST['kode_prodi']."',
							kode_jenjang='".$wi['kode_jenjang']."',
							nama_dosen='".$_REQUEST['nama_dosen']."',
							gelar_depan='".$_REQUEST['gelar_depan']."',
							gelar_belakang='".$_REQUEST['gelar_belakang']."',
							agama='".$_REQUEST['agama']."',
							jenis_kelamin='".$_REQUEST['jenis_kelamin']."',
							tempat_lahir='".$_REQUEST['tempat_lahir']."',
							foto='".$xx."',
							tanggal_lahir='".$_REQUEST['tanggal_lahir']."',
							ktp='".$_REQUEST['ktp']."',
							telephon='".$_REQUEST['telephon']."',
							hp='".$_REQUEST['hp']."',
							email='".$_REQUEST['email']."'
							";
							//echo $s;
							$koneksi_db->sql_query($s);
						echo '<script language="javascript">';
						echo 'alert("Upload foto berhasil")';
						echo '</script>';
					}else{
						echo '<script language="javascript">';
						echo 'alert("Upload gagal ukuran file terlalu besar, ukuran tidak boleh lebih dari 2MB")';
						echo '</script>';
					}
				}
				}	else {
												$s = "insert into m_dosen SET 
							idd='".$_REQUEST['idd']."',
							nip='".$_REQUEST['nip']."',
							NIDN='".$_REQUEST['NIDN']."',
							kode_pt='".$wi['kode_pt']."',
							kode_fak='".$wi['kode_fak']."',
							kode_prodi='".$_REQUEST['kode_prodi']."',
							kode_jenjang='".$wi['kode_jenjang']."',
							nama_dosen='".$_REQUEST['nama_dosen']."',
							gelar_depan='".$_REQUEST['gelar_depan']."',
							gelar_belakang='".$_REQUEST['gelar_belakang']."',
							agama='".$_REQUEST['agama']."',
							jenis_kelamin='".$_REQUEST['jenis_kelamin']."',
							tempat_lahir='".$_REQUEST['tempat_lahir']."',
							tanggal_lahir='".$_REQUEST['tanggal_lahir']."',
							ktp='".$_REQUEST['ktp']."',
							telephon='".$_REQUEST['telephon']."',
							hp='".$_REQUEST['hp']."',
							email='".$_REQUEST['email']."'
							";
							//echo $s;
							$koneksi_db->sql_query($s);

				}			
				
			
				 
				  ///buat user
				  $su = "insert into user SET 
						userid='".$_REQUEST['idd']."',
						username='".$_REQUEST['nip']."',
						password='".md5($_REQUEST['nip'])."',
						nama='".$_REQUEST['nama_dosen']."',
						email='".$_REQUEST['email']."',
						level='DOSEN'
						";
				  $koneksi_db->sql_query($su);				  
			  }
			}
		
	 echo "<div  class='error'>Proses Menyimpan Data...</div>";	
    	echo "<meta http-equiv='refresh' content='3; url=index.php?m=".$_GET['m']."&op=edit&idd=".$_REQUEST['idd']."'>";

  Daftar();
 
}

function edit() {
global $koneksi_db, $w;
$idd = $_REQUEST['idd'];  

if (empty($idd) || !isset($idd)) { echo "<meta http-equiv='refresh' content='3; url=index.php?m=".$_GET['m']."'>"; } 

$arrSub = array('Data Pribadi->pribadi',
		'Alamat->alamat',
		'Akademik->akademik',
		'Jabatan->jabatan',
		'Pendidikan->pendidikan',
		'Pengajaran->pengajaran',
		'Penelitian->penelitian'
		);
		//Tampilan Edit Dosen
$w = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM m_dosen where idd='".$idd."' limit 1 " ));
$foto= ($w['foto'] =="" ) ? "gambar/no_avatars.gif": "gambar/".$w['foto']."";

$sub = (empty($_REQUEST['sub'])) ? 'pribadi' : $_REQUEST['sub']; 
	echo '
		<table  border="0" cellspacing="1"class="datatable full" cellpadding="1">
		<thead>
		<tr>
			<td width="149">NIP</td>
			<td width="436"><b>'.$w['nip'].'</b></td>
			<td width="37" valign="top" rowspan="4"><img src="'.$foto.'" width="90" height="120"></td>
		  </tr>
		  <tr>
			<td width="149">NIDN</td>
			<td width="436"><b>'.$w['NIDN'].'</b></td>
		  </tr>
		  <tr>
			<td>NAMA</td>
			<td><b >'.$w['gelar_depan'].' '.$w['nama_dosen'].', '.$w['gelar_belakang'].'</b></td>
		  </tr>
		  <tr>
			<td>JABATAN</td>
			<td><b >'.viewAplikasi('02',''.$w['jabatan_akademik'].'').'</b ></td>
		  </tr>
		  <tr>
			<td>PANGKAT/GOLONGAN</td>
			<td><b >'.viewAplikasi('56',''.$w['pangkat_golongan'].'').'</b ></td>
		  </tr>
		  </thead>
		</table>
	  ';  		
	echo'<ul class="nav nav-tabs">';
	  for ($i = 0; $i < sizeof($arrSub); $i++) {
		$mn = explode('->', $arrSub[$i]);
		$c = ($mn[1] == $sub)? 'class="active"' : '';
		echo "<li $c><a  href='index.php?m=".$_GET['m']."&op=edit&sub=$mn[1]&idd=$idd'><span>$mn[0]</span></a></li>";
	  }
	echo	'</ul>';
	echo '<div class="tab-content"><div class="tab-pane fade active in" ><br/>';
	$sub();
	echo '</div></div>';
}

function pribadi() {

add();

}

function alamat() {
global $w;

echo '  
    <form action="" method="post"  class="cmxform" id="form_input" style="width:100%">
        <input type="hidden" name="m" value="dosen"/>
        <input type="hidden" name="op" value="edit"/>
		<input type="hidden" name="sub" value="alamatSav"/>
		<input type="hidden" name="idd" value="'.$w['idd'].'"/>
        <fieldset class="ui-widget ui-widget-content ui-corner-all" >
            <legend class="ui-widget ui-widget-header ui-corner-all">Edit Data Alamat '.$w['nama_dosen'].'</legend>
            &nbsp;<font color="red"><br></font>
            <table width="600"  border="0" class="datatable full1">
                <tr>
                    <td align="right" valign="top">Alamat<font color="red"></font></td>
                    <td>
					<textarea name="alamat" required   cols=40 rows=1>'.$w['alamat'].'</textarea>
					</td>
                </tr>
                <tr>
                    <td width="150" align="right" valign="top">Kota/Kabupaten<font color="red"> *</font></td>
                    <td  >
					<select name="kode_kota"  required    />'.opkota(''.$w['kode_kota'].'').'</select>
					</td>
                </tr>
                <tr>
                    <td width="150" align="right" valign="top">Propinsi<font color="red"> *</font></td>
                    <td  >
					<select name="kode_propinsi"  required    />'.oppropinsi(''.$w['kode_propinsi'].'').'</select>
					</td>
                </tr>
             	<tr>
                    <td align="right"valign="top">Pos<font color="red"> *</font></td>
                    <td>
					<input name="kode_pos"  type="text" required  id="" value="'.$w['kode_pos'].'" />
					</td>
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

function akademik() {
global $w;
echo '  
    <form action="" method="post"  class="cmxform" id="form_input" style="width:100%">
        <input type="hidden" name="m" value="dosen"/>
        <input type="hidden" name="op" value="edit"/>
		<input type="hidden" name="sub" value="akademikSav"/>
		<input type="hidden" name="idd" value="'.$w['idd'].'"/>
        <fieldset class="ui-widget ui-widget-content ui-corner-all" >
            <legend class="ui-widget ui-widget-header ui-corner-all">Edit Data Akademik '.$w['nama_dosen'].'</legend>
            &nbsp;<font color="red"><br></font>
            <table width="600"  border="0" class="datatable full1">
                <tr>
                    <td align="right" valign="top">Tanggal Mulai Kerja<font color="red"></font></td>
                    <td>
					<input name="mulai_masuk"  type="text" class="tcal date" required  id="" value="'.$w['mulai_masuk'].'" />
					</td>
                </tr>
                <tr>
                    <td width="150" align="right" valign="top">Semester Mulai Kerja<font color="red"> *</font></td>
                    <td  >
					<select name="mulai_semester"  required    />'.optapel(''.$w['mulai_semester'].'').'</select>
					</td>
                </tr>
                <tr>
                    <td width="150" align="right" valign="top">Status Ikatan Kerja<font color="red"> *</font></td>
                    <td  >
					<select name="kode_ikatan_kerja"  required    />'.opAplikasi('03',''.$w['kode_ikatan_kerja'].'').'</select>
					</td>
                </tr>
                <tr>
                    <td width="150" align="right" valign="top">Pendidikan Tertinggi<font color="red"> *</font></td>
                    <td  >
					<select name="kode_pendidikan"  required    />'.opAplikasi('01',''.$w['kode_pendidikan'].'').'</select>
					</td>
                </tr>
                <tr>
                    <td width="150" align="right" valign="top">Institusi Induk<font color="red"> *</font></td>
                    <td  >
					<select name="instansi_induk"  required    />'.opdaftarpt(''.$w['instansi_induk'].'').'</select>
					</td>
                </tr>
				
                <tr>
                    <td width="150" align="right" valign="top">Status<font color="red"> *</font></td>
                    <td  >
					<select name="status_aktif"  required    />'.opAplikasi('15',''.$w['status_aktif'].'').'</select>
					</td>
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

function jabatan() {
global $koneksi_db, $w;
echo '  
    <form action="" method="post"  class="cmxform" id="form_input" style="width:100%">
        <input type="hidden" name="m" value="dosen"/>
        <input type="hidden" name="op" value="edit"/>
		<input type="hidden" name="sub" value="jabatanSav"/>
		<input type="hidden" name="idd" value="'.$w['idd'].'"/>
        <fieldset class="ui-widget ui-widget-content ui-corner-all" >
            <legend class="ui-widget ui-widget-header ui-corner-all">Edit Data Jabatan '.$w['nama_dosen'].'</legend>
            &nbsp;<font color="red"><br></font>
            <table width="600"  border="0" class="datatable full1">
                <tr>
                    <td align="right" valign="top">Jabatan Akademik<font color="red"></font></td>
                    <td>
					<select name="jabatan_akademik"  required    />'.opAplikasi('02',''.$w['jabatan_akademik'].'').'</select>
					</td>
                </tr>
                <tr>
                    <td width="150" align="right" valign="top">Jabatan Fungsional<font color="red"> *</font></td>
                    <td  >
					<input name="jabatan_fungsional"  type="text" required  id="" value="'.$w['jabatan_fungsional'].'" />
					</td>
                </tr>
                <tr>
                    <td width="150" align="right" valign="top">Jabatan Struktural<font color="red"> *</font></td>
                    <td  >
					<input name="jabatan_struktural"  type="text" required  id="" value="'.$w['jabatan_struktural'].'" />
					</td>
                </tr>
                <tr>
                    <td width="150" align="right" valign="top">Pangkat/Golongan<font color="red"> *</font></td>
                    <td  >
					<select name="pangkat_golongan"  required    />'.opAplikasi('56',''.$w['pangkat_golongan'].'').'</select>
					</td>
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
function pendidikan() {
global $koneksi_db, $w;
$id = $_REQUEST['id'];
if (!empty($id) && isset($id)) 
{ 
 $wp = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM t_dosen_pendidikan where id='$id' limit 1 " ));
 	$sembunyi = 'style="display:block;"';
} else {
	$sembunyi = 'style="display:none;"';
}
echo"	<fieldset class=\"ui-widget ui-widget-content ui-corner-all\" >
            <legend><input type=button  class=\"tombols ui-corner-all\" value='Tambah Pendidikan' onclick=\"return toggleView('search_hide')\" ></legend>
            &nbsp;<font color=\"red\"><br></font>";
echo '<div id="form-hide" '.$sembunyi.'>';
echo '  
    <form action="" method="post"  class="cmxform" id="form_input" style="width:100%">
        <input type="hidden" name="m" value="dosen"/>
        <input type="hidden" name="op" value="edit"/>
		<input type="hidden" name="sub" value="pendidikanSav"/>
		<input type="hidden" name="idd" value="'.$w['idd'].'"/>
		<input type="hidden" name="id" value="'.$id.'"/>
        <fieldset class="ui-widget ui-widget-content ui-corner-all" >
            <legend class="ui-widget ui-widget-header ui-corner-all">Edit Data Pendidikan '.$w['nama_dosen'].'</legend>
            &nbsp;<font color="red"><br></font>
            <table width="600"  border="0" class="datatable full1">
                <tr>
                    <td align="right" valign="top">Jenjang Studi<font color="red"></font></td>
                    <td>
					<select name="jenjang_studi"  required    />'.opAplikasi('01',''.$wp['jenjang_studi'].'').'</select>
					</td>
                </tr>
                <tr>
                    <td align="right" valign="top">Bidang Keilmuan<font color="red"></font></td>
                    <td>
					<select name="kode_bidang"  required    />'.opAplikasi('42',''.$wp['kode_bidang'].'').'</select>
					</td>
                </tr>
				
                <tr>
                    <td width="150" align="right" valign="top">Gelar Akademik<font color="red"> *</font></td>
                    <td  >
					<input name="gelar"  type="text" required  id="" value="'.$wp['gelar'].'" />
					</td>
                </tr>
                <tr>
                    <td width="150" align="right" valign="top">Perguruan Tinggi<font color="red"> *</font></td>
                    <td  >
					<select name="kode_pt"  required    />'.opdaftarpt(''.$wp['kode_pt'].'').'</select>
				</td>
                </tr>
                <tr>
                    <td width="150" align="right" valign="top">Tanggal Ijazah<font color="red"> *</font></td>
                    <td  >
					<input name="tgl_ijazah"  type="text" class="tcal date" required  id="" value="'.$wp['tgl_ijazah'].'" />
					</td>
                </tr>
				
                <tr>
                    <td width="150" align="right" valign="top">Tahun<font color="red"> *</font></td>
                    <td  >
					<select name="tahun"  required    />'.optahun(''.$wp['tahun'].'').'</select>
					</td>
                </tr>
                <tr>
                    <td width="150" align="right" valign="top">SKS Lulus<font color="red"> *</font></td>
                    <td  >
					<input name="sks_lulus"  type="text" required  id="" value="'.$wp['sks_lulus'].'" />
					</td>
                </tr>
                <tr>
                    <td width="150" align="right" valign="top">IPK Akhir<font color="red"> *</font></td>
                    <td  >
					<input name="ipk_akhir"  type="text" required  id="" value="'.$wp['ipk_akhir'].'" />
					</td>
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
echo '</div>';

echo '<table class="table table-striped table-bordered table-hover"  >
   	<thead>
     <tr>
	   <th width="5%" align="center">No</th>
	   <th  align="center">Tahun</th>
	   <th  align="center">Tgl Ijazah</th>
	   <th  align="center">Gelar</th>
       <th  align="center">Jenjang</th>
       <th  align="center">Bidang</th>
	   <th width="30%" align="center"></th>
     </tr>
	 </thead>
	 <tbody>';
	
		$qpp = $koneksi_db->sql_query( "SELECT * FROM `t_dosen_pendidikan` where idd='$w[idd]' " );
		$jumlah=$koneksi_db->sql_numrows($qpp);
		if ($jumlah > 0){
			while($wf = $koneksi_db->sql_fetchassoc($qpp)){
				$n++;
			  echo "<tr>
			  	<td $c>$n</td>
				<td $c>$wf[tahun]</td>
				<td $c>$wf[tgl_ijazah]</td>
				<td $c>$wf[gelar]</td>
				<td $c>".viewAplikasi('01',''.$wf['jenjang_studi'].'')."</td>
				<td $c>".viewAplikasi('42',''.$wf['kode_bidang'].'')."</td>
				<td $c><a href='index.php?m=".$_GET['m']."&op=edit&sub=pendidikan&idd=$wf[idd]&id=$wf[id]'>Edit</a></td>
				</tr>";
				
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

function pengajaran() {
global $koneksi_db, $w, $tahun_id;
echo '<table class="table table-striped table-bordered table-hover"  >
   	<thead>
     <tr>
	   <th width="5%" align="center">No</th>
	   <th  align="center">Kode MK</th>
	   <th align="center">Nama MK</th>
       <th  align="center">Semester</th>
       <th align="center">SKS</th>
     </tr>
	 </thead>
	 <tbody>';
		/*$ss = "select  j.*, m.* from t_jadwal j
					left outer join m_mata_kuliah m on j.kode_mk=m.id
					where j.kode_prodi='$w[kode_prodi]' and tahun_id='$tahun_id' and j.idd='$w[idd]'
					order by j.idj asc";*/
		$ss = "select * from m_mata_kuliah
					where idd='$w[idd]'
					order by nama_mk asc";			
						
		$qp = $koneksi_db->sql_query($ss);
		$jumlah=$koneksi_db->sql_numrows($qp);
		if ($jumlah > 0){
			while($wf = $koneksi_db->sql_fetchassoc($qp)){
				$n++;
			  echo "<tr>
			  	<td $c>$n</td>
				<td $c>$wf[kode_mk]</td>
				<td $c>$wf[nama_mk]</td>
				<td $c>$wf[semester]</td>
				<td $c>$wf[sks_mk]</td>
				</tr>";
			}
		} else {
		echo '<tr > <th  colspan="5" align=center>Belum ada Data</th></tr>';
		}



echo '</tbody>
		</table>';

}

function penelitian() {

global $koneksi_db, $w;
$id = $_REQUEST['id'];
if (!empty($id) && isset($id)) 
{ 
 $wp = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM t_dosen_penelitian where id='$id' limit 1 " ));
 $sembunyi = 'style="display:block;"';
} else {
$sembunyi = 'style="display:none;"';
}
echo"	<fieldset class=\"ui-widget ui-widget-content ui-corner-all\" >
            <legend><input type=button  class=\"tombols ui-corner-all\" value='Tambah Penelitian' onclick=\"return toggleView('search_hide')\" ></legend>
            &nbsp;<font color=\"red\"><br></font>
";
echo '<div id="form-hide" '.$sembunyi.' >';
echo '  
    <form action="" method="post"  class="cmxform" id="form_input" style="width:100%">
        <input type="hidden" name="m" value="dosen"/>
        <input type="hidden" name="op" value="edit"/>
		<input type="hidden" name="sub" value="penelitianSav"/>
		<input type="hidden" name="idd" value="'.$w['idd'].'"/>
		<input type="hidden" name="id" value="'.$id.'"/>
        <fieldset class="ui-widget ui-widget-content ui-corner-all" >
            <legend class="ui-widget ui-widget-header ui-corner-all">Edit Data Penelitian '.$w['nama_dosen'].'</legend>
            &nbsp;<font color="red"><br></font>
            <table width="600"  border="0" class="datatable full1">
                <tr>
                    <td align="right" valign="top">Jenis<font color="red"></font></td>
                    <td><select name="kode_jenis"  required    />'.opAplikasi('24',''.$wp['kode_jenis'].'').'</select>	</td>
                </tr>
                <tr>
                    <td align="right" valign="top">Media Publikasi<font color="red"></font></td>
                    <td><select name="kode_media"  required    />'.opAplikasi('25',''.$wp['kode_media'].'').'</select>	</td>
                </tr>
				
                <tr>
                    <td width="150" align="right" valign="top">Status Author<font color="red"> *</font></td>
                    <td  >	<select name="kode_pengarang"  required    />'.opAplikasi('27',''.$wp['kode_pengarang'].'').'</select>	</td>
                </tr>
                <tr>
                    <td width="150" align="right" valign="top">Status Pelaksanaan<font color="red"> *</font></td>
                    <td  >	<select name="kode_kegiatan"  required    />'.opMK(''.$wp['kode_kegiatan'].'').'</select>	</td>
                </tr>
                <tr>
                    <td width="150" align="right" valign="top">Tanggal Terbit<font color="red"> *</font></td>
                    <td  >	<input name="tanggal_terbit"  type="text" class="tcal date" required  id="" value="'.$wp['tanggal_terbit'].'" />	</td>
                </tr>
                <tr>
                    <td width="150" align="right" valign="top">Judul<font color="red"> *</font></td>
                    <td  >	<input name="judul_penelitian"  type="text" required  id="" value="'.$wp['judul_penelitian'].'" />		</td>
                </tr>
                <tr>
                    <td align="right" valign="top">Catatan<font color="red"></font></td>
                    <td>	<textarea name="keterangan" cols=40 rows=1>'.$wp['keterangan'].'</textarea>	</td>
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
echo '</div>';

echo '<table class="table table-striped table-bordered table-hover"  >
   	<thead>
     <tr>
	   <th width="5%" align="center">No</th>
	   <th  width="10%" align="center">Tanggal</th>
	   <th  align="center">Judul</th>
	   <th width="30%" align="center"></th>
     </tr>
	 </thead>
	 <tbody>';
	 	//$qfak = $koneksi_db->sql_query( "SELECT * FROM m_fakultas where kode_pt='$wpt[0]' " );
		$qpp = $koneksi_db->sql_query( "SELECT * FROM `t_dosen_penelitian`  where idd='$w[idd]' " );
		$jumlah=$koneksi_db->sql_numrows($qpp);
		if ($jumlah > 0){
			while($wf = $koneksi_db->sql_fetchassoc($qp)){
				$n++;
			  echo "<tr>
			  	<td $c>$n</td>
				<td $c>$wf[tanggal_terbit]</td>
				<td $c>$wf[judul_penelitian]</td>
				<td $c><a href='index.php?m=".$_GET['m']."&op=edit&sub=penelitian&idd=$w[idd]&id=$wf[id]'>Edit</a></td></tr>";
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
////////////////////////
function alamatSav() {
global $koneksi_db, $w;
$idd = $_REQUEST['idd']; 
	$s = "update m_dosen set 
			alamat='".$_REQUEST['alamat']."',
			kode_kota='".$_REQUEST['kode_kota']."',
			kode_propinsi='".$_REQUEST['kode_propinsi']."',
			kode_pos='".$_REQUEST['kode_pos']."'
			where idd='".$idd."' ";
	$r = $koneksi_db->sql_query($s);
	echo "<meta http-equiv='refresh' content='3; url=index.php?m=".$_GET['m']."&op=edit&sub=alamat&idd=".$_REQUEST['idd']."'>";
}

function akademikSav() {
global $koneksi_db, $w;
$idd = $_REQUEST['idd']; 
	$s = "update m_dosen set 
			mulai_masuk='".$_REQUEST['mulai_masuk']."',
			mulai_semester='".$_REQUEST['mulai_semester']."',
			kode_ikatan_kerja='".$_REQUEST['kode_ikatan_kerja']."',
			kode_pendidikan='".$_REQUEST['kode_pendidikan']."',
			instansi_induk='".$_REQUEST['instansi_induk']."',
			status_aktif='".$_REQUEST['status_aktif']."'
			where idd='".$idd."' ";
	$r = $koneksi_db->sql_query($s);
	echo "<meta http-equiv='refresh' content='3; url=index.php?m=".$_GET['m']."&op=edit&sub=akademik&idd=".$_REQUEST['idd']."'>";

}

function jabatanSav() {
global $koneksi_db, $w;
$idd = $_REQUEST['idd']; 
	$s = "update m_dosen set 
			jabatan_akademik='".$_REQUEST['jabatan_akademik']."',
			jabatan_fungsional='".$_REQUEST['jabatan_fungsional']."',
			jabatan_struktural='".$_REQUEST['jabatan_struktural']."',
			pangkat_golongan='".$_REQUEST['pangkat_golongan']."'
			where idd='".$idd."' ";
	$r = $koneksi_db->sql_query($s);
	echo "<div  class='error'>Proses Menyimpan Data...</div>";	
	echo "<meta http-equiv='refresh' content='3; url=index.php?m=".$_GET['m']."&op=edit&sub=jabatan&idd=".$_REQUEST['idd']."'>";


}
function pendidikanSav() {

global $koneksi_db, $w;
$idd = $_REQUEST['idd']; 
$id = $_REQUEST['id']; 
	if (empty($id) || !isset($id))  {
		$s = "INSERT INTO t_dosen_pendidikan set 
				idd='".$idd."',
				jenjang_studi='".$_REQUEST['jenjang_studi']."',
				gelar='".$_REQUEST['gelar']."',
				kode_pt='".$_REQUEST['kode_pt']."',
				kode_bidang='".$_REQUEST['kode_bidang']."',
				tgl_ijazah='".$_REQUEST['tgl_ijazah']."',
				tahun='".$_REQUEST['tahun']."',
				sks_lulus='".$_REQUEST['sks_lulus']."',
				ipk_akhir='".$_REQUEST['ipk_akhir']."'
				";
		$r = $koneksi_db->sql_query($s);
	
	
	} else {
		$s = "update t_dosen_pendidikan set 
				jenjang_studi='".$_REQUEST['jenjang_studi']."',
				gelar='".$_REQUEST['gelar']."',
				kode_pt='".$_REQUEST['kode_pt']."',
				kode_bidang='".$_REQUEST['kode_bidang']."',
				tgl_ijazah='".$_REQUEST['tgl_ijazah']."',
				tahun='".$_REQUEST['tahun']."',
				sks_lulus='".$_REQUEST['sks_lulus']."',
				ipk_akhir='".$_REQUEST['ipk_akhir']."'
				where id='".$id."' ";
		$r = $koneksi_db->sql_query($s);
	}	
	echo "<div  class='error'>Proses Menyimpan Data...</div>";	
	echo "<meta http-equiv='refresh' content='3; url=index.php?m=".$_GET['m']."&op=edit&sub=pendidikan&idd=".$idd."'>";

}


function penelitianSav() {
global $koneksi_db, $w;
$idd = $_REQUEST['idd']; 
$id = $_REQUEST['id']; 
	if (empty($id) || !isset($id))  {
		$s = "INSERT INTO t_dosen_penelitian set 
				idd='".$idd."',
				kode_jenis='".$_REQUEST['kode_jenis']."',
				kode_media='".$_REQUEST['kode_media']."',
				kode_pengarang='".$_REQUEST['kode_pengarang']."',
				kode_kegiatan='".$_REQUEST['kode_kegiatan']."',
				judul_penelitian='".$_REQUEST['judul_penelitian']."',
				keterangan='".$_REQUEST['keterangan']."',
				tanggal_terbit='".$_REQUEST['tanggal_terbit']."'
				";
		$r = $koneksi_db->sql_query($s);
	
	} else {
		$s = "update t_dosen_penelitian set 
				kode_jenis='".$_REQUEST['kode_jenis']."',
				kode_media='".$_REQUEST['kode_media']."',
				kode_pengarang='".$_REQUEST['kode_pengarang']."',
				kode_kegiatan='".$_REQUEST['kode_kegiatan']."',
				judul_penelitian='".$_REQUEST['judul_penelitian']."',
				keterangan='".$_REQUEST['keterangan']."',
				tanggal_terbit='".$_REQUEST['tanggal_terbit']."'
				where id='".$id."' ";
		$r = $koneksi_db->sql_query($s);
	}	
	echo "<div  class='error'>Proses Menyimpan Data...</div>";	
	echo "<meta http-equiv='refresh' content='3; url=index.php?m=".$_GET['m']."&op=edit&sub=penelitian&idd=".$idd."'>";

}

function FormImport() {
	echo' <br />

<h4>Import Data Dosen</h4>
<div >
<a href="files/format_dosen.xls" class="btn btn-danger">Download Format</a>
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
          File yang diimport harus berekstensi .xls. Format isi file adalah sebagai berikut : <br />
[NIP], [NIDN], [nama_dosen], [gelar_depan], [gelar_belakang], [agama], [jenis_kelamin], [tempat_lahir], [tanggal_lahir], [telepon], [hp], [email]<br />
			Format tanggal adalah YYYY-MM-DD
        </div>
</div>		
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
			$idd = kdauto('m_dosen','D');
			  // membaca data nidn (kolom ke-1 dan seterusnya)
			  // kolom identitas dosen
			  $nip = 			$data->val($i, 1); 
			  $NIDN = 			$data->val($i, 2);
			  $nama_dosen = 	$data->val($i, 3);
			  $gelar_depan = 	$data->val($i, 4);
			  $gelar_belakang = $data->val($i, 5);
			  $agama = 			$data->val($i, 6);
			  $jenis_kelamin = 	$data->val($i, 7);
			  $tempat_lahir = 	$data->val($i, 8);
			  $tanggal_lahir = 	$data->val($i, 9);
			  $telepon = 		$data->val($i, 10);
			  $hp = 			$data->val($i, 11);
			  $email = 			$data->val($i, 12);
			  $kode_prd = 		$data->val($i, 13); 
			  $jenjang = 		$data->val($i, 14); 
			   
			  
			  		  		//insert data dosen
			$qada = "select * from m_dosen where  nip='$nip'";
			$qryu = $koneksi_db->sql_query($qada);
			if ($koneksi_db->sql_numrows($qryu) > 0 ) {
			$su = "update m_dosen SET 
					kode_pt='".$kodept."',
					kode_fak='".$kodefak."',
					kode_prodi='".$kode_prd."',
					kode_jenjang='".$jenjang."',
					nama_dosen='".$nama_dosen."',
					gelar_depan='".$gelar_depan."',
					gelar_belakang='".$gelar_belakang."',
					agama='".$agama."',
					jenis_kelamin='".$jenis_kelamin."',
					tempat_lahir='".$tempat_lahir."',
					tanggal_lahir='".$tanggal_lahir."',
					telephon='".$telepon."',
					hp='".$hp."',
					email='".$email."'
					WHERE nip = '".$nip."'
				";

			$hasil_update =  $koneksi_db->sql_query($su);
			
			} else {
			$si = "insert into m_dosen SET 
					idd='".$idd."',
					nip = '".$nip."',
					NIDN = '".$NIDN."',
					kode_pt='".$kodept."',
					kode_fak='".$kodefak."',
					kode_prodi='".$kode_prd."',
					kode_jenjang='".$jenjang."',
					nama_dosen='".$nama_dosen."',
					gelar_depan='".$gelar_depan."',
					gelar_belakang='".$gelar_belakang."',
					agama='".$agama."',
					jenis_kelamin='".$jenis_kelamin."',
					tempat_lahir='".$tempat_lahir."',
					tanggal_lahir='".$tanggal_lahir."',
					telephon='".$telepon."',
					hp='".$hp."',
					email='".$email."'
				";
			$hasil_import = $koneksi_db->sql_query($si);
			
			$su = "insert into user SET 
						userid='".$idd."',
						username='".$nip."',
						password='".md5($nip)."',
						nama='".$nama_dosen."',
						email='".$email."',
						level='DOSEN'
						";
			$koneksi_db->sql_query($su);				  
			$nama_hasil .= "<li>".$nama_dosen."</li>";		
			
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

$kolom_dosen = BuatSesi('kolom_dosen');
$kunci_dosen = BuatSesi('kunci_dosen');

if ($_REQUEST['reset_dosen'] == 'Reset') {
  $_SESSION['kolom_dosen'] = '';
  $_SESSION['kunci_dosen'] = '';
}

$go = (empty($_REQUEST['op'])) ? 'Daftar' : $_REQUEST['op']; 

echo '<div id="title" align="right">
        <font style="color:#FF9900; font-size:30px;"><strong>.:</strong></font>
        <font style="font-size:18px; color:#999999">Data Dosen</font><br />
        	<a href="?m=home">Home</a> &raquo; <a href="?m='.$_GET['m'].'">Dosen</a>  &raquo; '.$go.'  
    </div>';
echo '<div class="mainContentCell"><div class="content">';
$go();
echo '</div></div>';

?>
 
 