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


function badanhukum() {
global $koneksi_db;
$q = $koneksi_db->sql_query( "SELECT * FROM m_badan_hukum  limit 1 " );
$total = $koneksi_db->sql_numrows($q);

  if ($total > 0) {
  	$md = 0;
    $id = $_REQUEST['id'];
	$w = $koneksi_db->sql_fetchassoc($q);
    $jdl = "Edit Badan Hukum";
	$kode = '<input name="kode_badan_hukum_a"  disabled="disabled"  type="text" class="" id="" value="'.$w['kode_badan_hukum'].'" />
		<input name="kode_badan_hukum"  type="hidden" class="form-control" required id="" value="'.$w['kode_badan_hukum'].'" />
	';

  } else {
  $md = 1;
    $w = array();
    $jdl = "Tambah Badan Hukum";
	$kode = '<input name="kode_badan_hukum"  type="text" class="form-control" required id="" value="'.$w['kode_badan_hukum'].'" />';
  }

//$aktif = ($w['buka'] == 'Y') ? 'checked' : ''; 
	
echo '  
    <form action="" method="post"  class="cmxform" id="form_input" style="width:100%">
		<input type="hidden" name="id" value="'.$id.'"/>
        <input type="hidden" name="m" value="'.$_GET['m'].'"/>
        <input type="hidden" name="op" value="simpan_badan"/>
		<input type="hidden" name="md" value="'.$md.'"/>
       
		
            <table width="100%" class="table full1" >
                <tr>
                    <td width="250" align="right" valign="top">Kode Badan Hukum<font color="red"> *</font></td>
                    <td  >
					'.$kode.'
					</td>
                </tr>
                <tr>
                    <td align="right"valign="top">Nama Badan Hukum<font color="red"> *</font></td>
                    <td>
					<input name="nama_badan_hukum"  class="form-control" required type="text" id="autocomplete" value="'.$w['nama_badan_hukum'].'" />
					</td>
                </tr>
			
                <tr>
                    <td align="right" valign="top">Tanggal Berdiri<font color="red"> *</font></td>
                    <td>
					<input name="tanggal_awal_berdiri"  type="text" class="form-control date  " required   id="" value="'.$w['tanggal_awal_berdiri'].'" />
					</td>
                </tr>
                <tr>
                    <td align="left" colspan="2" valign="top"><font color="#000066" size="+2">Akta Terakhir</font></td>
                </tr>
                <tr>
                    <td align="right"valign="top">Nomor<font color="red"> *</font></td>
                    <td>
					<input name="nomor_akta_terakhir"  type="text" class="form-control" required id="" value="'.$w['nomor_akta_terakhir'].'" />
					</td>
                </tr>
                <tr>
                    <td align="right" valign="top">Tanggal<font color="red"> *</font></td>
                    <td>
					<input name="tanggal_akta_terakhir"  type="text" class="form-control date" required  id="" value="'.$w['tanggal_akta_terakhir'].'" />
					</td>
                </tr>
                <tr>
                    <td align="left" colspan="2" valign="top"><font color="#000066" size="+2">Pengesahan</font></td>
                </tr>			
                <tr>
                    <td align="right"valign="top">Nomor<font color="red"> *</font></td>
                    <td>
					<input name="nomor_pengesahan"  type="text" class="form-control" required id="" value="'.$w['nomor_pengesahan'].'" />
					</td>
                </tr>
                <tr>
                    <td align="right" valign="top">Tanggal<font color="red"> *</font></td>
                    <td>
					<input name="tanggal_pengesahan"  type="text" class="form-control date" required   id="" value="'.$w['tanggal_pengesahan'].'" />
					</td>
                </tr>
                <tr>
                    <td align="left" colspan="2" valign="top"><font color="#000066" size="+2">Alamat</font></td>
                </tr>
                <tr>
                    <td align="right" valign="top">Jalan<font color="red"></font></td>
                    <td>
					<input name="alamat"  type="text" class="form-control" required id="" value="'.$w['alamat'].'" />
					</td>
                </tr>
                <tr>
                    <td width="150" align="right" valign="top">Kota/Kabupaten<font color="red"> *</font></td>
                    <td  >
					<select name="kode_kota"  class="form-control" required  />'.opkota(''.$w['kode_kota'].'').'</select>
					</td>
                </tr>
                <tr>
                    <td width="150" align="right" valign="top">Propinsi<font color="red"> *</font></td>
                    <td  >
					<select name="kode_propinsi"  class="form-control" required  />'.oppropinsi(''.$w['kode_propinsi'].'').'</select>
					</td>
                </tr>
             	<tr>
                    <td align="right"valign="top">Pos<font color="red"> *</font></td>
                    <td>
					<input name="kode_pos"  type="text" class="form-control" required id="" value="'.$w['kode_pos'].'" />
					</td>
                </tr>
             	<tr>
                    <td align="right"valign="top">Telepon<font color="red"> *</font></td>
                    <td>
					<input name="telepon"  type="text" class="form-control" required id="" value="'.$w['telepon'].'" />
					</td>
                </tr>
             	<tr>
                    <td align="right"valign="top">Faximil<font color="red"> *</font></td>
                    <td>
					<input name="fax"  type="text" class="form-control" required id="" value="'.$w['fax'].'" />
					</td>
                </tr>
             	<tr>
                    <td align="right"valign="top">Email<font color="red"> *</font></td>
                    <td>
					<input name="email"  type="text" class="form-control" required id="" value="'.$w['email'].'" />
					</td>
                </tr>
             	<tr>
                    <td align="right"valign="top">Website<font color="red"> *</font></td>
                    <td>
					<input name="website"  type="text" class="form-control" required  id="" value="'.$w['website'].'" />
					</td>
                </tr>
				
                 <tr>
                    <td colspan="2">
                        <input type="submit" class=tombols ui-corner-all value="Simpan"/> 
                        <input type="button" class=tombols ui-corner-all value="Kembali" onClick="window.location = \'index.php\'"/></td>
                  </tr>
            </table>
    </form><br/>
 ';
 
}


////simpan /
function simpan_badan() {
global $koneksi_db;
  $md = $_REQUEST['md']+0;
	$id = $_REQUEST['id'];  

	if (trim($_POST['kode_badan_hukum'])=="") {
		$pesan[] = "Form Tahun masih kosong, ulangi kembali";
	}
	else if (trim($_POST['nama_badan_hukum'])=="") {
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
		echo "<meta http-equiv='refresh' content='1; url=index.php?m=".$_GET['m']."&md=1'>";
	} else {
		
			if ($md == 0) {
			$s = "update m_badan_hukum set 
					nama_badan_hukum='".$_REQUEST['nama_badan_hukum']."',
					tanggal_awal_berdiri='".$_REQUEST['tanggal_awal_berdiri']."',
					alamat='".$_REQUEST['alamat']."',
					kode_kota='".$_REQUEST['kode_kota']."',
					kode_propinsi='".$_REQUEST['kode_propinsi']."',
					kode_pos='".$_REQUEST['kode_pos']."',
					telepon='".$_REQUEST['telepon']."',
					fax='".$_REQUEST['fax']."',
					email='".$_REQUEST['email']."',
					website='".$_REQUEST['website']."',
					nomor_akta_terakhir='".$_REQUEST['nomor_akta_terakhir']."',
					tanggal_akta_terakhir='".$_REQUEST['tanggal_akta_terakhir']."',
					nomor_pengesahan='".$_REQUEST['nomor_pengesahan']."',
					tanggal_pengesahan='".$_REQUEST['tanggal_pengesahan']."'
					where kode_badan_hukum='".$_REQUEST['kode_badan_hukum']."'
					 ";
			$koneksi_db->sql_query($s);
			
			} else {
			  $idx = $_REQUEST['tahun']."".$_REQUEST['semester'];
			  $s = "INSERT INTO m_badan_hukum set 
					kode_badan_hukum='".$_REQUEST['kode_badan_hukum']."',
					nama_badan_hukum='".$_REQUEST['nama_badan_hukum']."',
					tanggal_awal_berdiri='".$_REQUEST['tanggal_awal_berdiri']."',
					alamat='".$_REQUEST['alamat']."',
					kode_kota='".$_REQUEST['kode_kota']."',
					kode_propinsi='".$_REQUEST['kode_propinsi']."',
					kode_pos='".$_REQUEST['kode_pos']."',
					telepon='".$_REQUEST['telepon']."',
					fax='".$_REQUEST['fax']."',
					email='".$_REQUEST['email']."',
					website='".$_REQUEST['website']."',
					nomor_akta_terakhir='".$_REQUEST['nomor_akta_terakhir']."',
					tanggal_akta_terakhir='".$_REQUEST['tanggal_akta_terakhir']."',
					nomor_pengesahan='".$_REQUEST['nomor_pengesahan']."',
					tanggal_pengesahan='".$_REQUEST['tanggal_pengesahan']."'
					";
			  $koneksi_db->sql_query($s);
			}
		}
		//echo $s;
  badanhukum();
}



////////////////////



$go = (empty($_REQUEST['op'])) ? 'badanhukum' : $_REQUEST['op'];

echo '<div id="title" align="right">
        <font style="color:#FF9900; font-size:30px;"><strong>.:</strong></font>
        <font style="font-size:18px; color:#999999">Identitas</font><br />
        	<a href="?m=home">Home</a> &raquo; <a href="?m='.$_GET['m'].'">Badan Hukum</a>  &raquo; '.$go.'  
    </div>';
echo '<div class="mainContentCell"><div class="content">';
$go();
echo '</div></div>';


?>


