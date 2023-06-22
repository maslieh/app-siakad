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


function perguruantinggi() {
global $koneksi_db;
$qb = $koneksi_db->sql_query( "SELECT * FROM m_badan_hukum  limit 1 " );
$totalb = $koneksi_db->sql_numrows($qb);

if ($totalb < 1) { 
echo '<div class=error>Badan Hukum Masih Kosong, Klik <a href="?m=2">Add Badan Hukum</a></div>'; 
} else {
 
$wb = $koneksi_db->sql_fetchrow($qb);
  
$q = $koneksi_db->sql_query( "SELECT * FROM m_perguruan_tinggi where kode_badan_hukum ='$wb[0]' limit 1 " );
$total = $koneksi_db->sql_numrows($q);

  if ($total > 0) {
  	$md = 0;
    $id = $_REQUEST['id'];
	$w = $koneksi_db->sql_fetchassoc($q);
    $jdl = "Edit Perguruan Tinggi";
	$kode = '<input name="kode_pt_a"  disabled="disabled"  type="text" class="" id="" value="'.$w['kode_pt'].'" />
		<input name="kode_pt"  type="hidden" class=" form-control" required id="" value="'.$w['kode_pt'].'" />
	';

  } else {
  $md = 1;
    $w = array();
    $jdl = "Tambah Badan Hukum";
	$kode = '<input name="kode_pt"  type="text" class=" form-control" required id="" value="'.$w['kode_pt'].'" />';
  }

//$aktif = ($w['buka'] == 'Y') ? 'checked' : ''; 
	
echo '  
    <form action="" method="post"  class="cmxform" id="form_input" style="width:100%">
		<input type="hidden" name="id" value="'.$id.'"/>
        <input type="hidden" name="m" value="'.$_GET['m'].'"/>
        <input type="hidden" name="op" value="simpan_pt"/>
		<input type="hidden" name="md" value="'.$md.'"/>
       
	
            <table width="100%"  border="0" class="table full1">
			    <tr>
                    <td width="250" align="right" valign="top">Kode Badan Hukum<font color="red"> *</font></td>
                    <td  >
					'.$wb[0].'
					<input type="hidden" name="kode_badan_hukum" value="'.$wb[0].'"/>
					</td>
                </tr>

                <tr>
                    <td width="150" align="right" valign="top">Kode Perguruan Tinggi<font color="red"> *</font></td>
                    <td  >
					'.$kode.'
					</td>
                </tr>
                <tr>
                    <td align="right"valign="top">Nama Perguruan Tinggi<font color="red"> *</font></td>
                    <td>
					<input name="nama_pt"  type="text" class=" form-control" required id="" value="'.$w['nama_pt'].'" />
					</td>
                </tr>
                <tr>
                    <td align="right"valign="top">Singkatan Nama<font color="red"> *</font></td>
                    <td>
					<input name="singkatan"  type="text" class=" form-control" requiredid="" value="'.$w['singkatan'].'" />
					</td>
                </tr>
			
                <tr>
                    <td align="right" valign="top">Tanggal Berdiri<font color="red"> *</font></td>
                    <td>
					<input name="tgl_awal_berdiri"  type="text" class=" form-control date" required id="" value="'.$w['tgl_awal_berdiri'].'" />
					</td>
                </tr>
                <tr>
                    <td align="left" colspan="2" valign="top"><font color="#000066" size="+2">Akta Terakhir</font></td>
                </tr>
                <tr>
                    <td align="right"valign="top">Nomor<font color="red"> *</font></td>
                    <td>
					<input name="no_akta_sk_pendirian"  type="text" class=" form-control" required id="" value="'.$w['no_akta_sk_pendirian'].'" />
					</td>
                </tr>
                <tr>
                    <td align="right" valign="top">Tanggal<font color="red"> *</font></td>
                    <td>
					<input name="tanggal_akta"  type="text" class=" form-control date" required id="" value="'.$w['tanggal_akta'].'" />
					</td>
                </tr>
                <tr>
                    <td align="left" colspan="2" valign="top"><font color="#000066" size="+2">Pengesahan</font></td>
                </tr>			
                <tr>
                    <td align="right"valign="top">Nomor<font color="red"> *</font></td>
                    <td>
					<input name="no_sk_ban"  type="text" class=" form-control" required id="" value="'.$w['no_sk_ban'].'" />
					</td>
                </tr>
                <tr>
                    <td align="right" valign="top">Tanggal<font color="red"> *</font></td>
                    <td>
					<input name="tgl_sk_ban"  type="text" class=" form-control date" requiredid="" value="'.$w['tgl_sk_ban'].'" />
					</td>
                </tr>
                <tr>
                    <td width="150" align="right" valign="top">Akreditasi<font color="red"> *</font></td>
                    <td  >
					<select name="kode_akreditasi"  class=" form-control"  />'.opAplikasi('07',''.$w['kode_akreditasi'].'').'</select>
					</td>
                </tr>
				
                <tr>
                    <td align="left" colspan="2" valign="top"><font color="#000066" size="+2">Alamat</font></td>
                </tr>
                <tr>
                    <td align="right" valign="top">Alamat Utama<font color="red"></font></td>
                    <td>
					<textarea name="alamat1" cols=40 rows=1 class=" form-control" required>'.$w['alamat_1'].'</textarea>
					</td>
                </tr>
                <tr>
                    <td align="right" valign="top">Alamat Lain<font color="red"></font></td>
                    <td>
					<textarea name="alamat2" cols=40 rows=1 class=" form-control" required>'.$w['alamat_2'].'</textarea>
					</td>
                </tr>
				
                <tr>
                    <td width="150" align="right" valign="top">Kota/Kabupaten<font color="red"> *</font></td>
                    <td  >
					<select name="kode_kota"  class=" form-control" required  />'.opkota(''.$w['kode_kota'].'').'</select>
					</td>
                </tr>
                <tr>
                    <td width="150" align="right" valign="top">Propinsi<font color="red"> *</font></td>
                    <td  >
					<select name="kode_propinsi" class=" form-control" required  />'.oppropinsi(''.$w['kode_propinsi'].'').'</select>
					</td>
                </tr>
             	<tr>
                    <td align="right"valign="top">Pos<font color="red"> *</font></td>
                    <td>
					<input name="kode_pos"  type="text" class=" form-control" required id="" value="'.$w['kode_pos'].'" />
					</td>
                </tr>
             	<tr>
                    <td align="right"valign="top">Telepon<font color="red"> *</font></td>
                    <td>
					<input name="telepon"  type="text" class=" form-control" required id="" value="'.$w['telepon'].'" />
					</td>
                </tr>
             	<tr>
                    <td align="right"valign="top">Faximil<font color="red"> *</font></td>
                    <td>
					<input name="fax"  type="text" class=" form-control" required id="" value="'.$w['fax'].'" />
					</td>
                </tr>
             	<tr>
                    <td align="right"valign="top">Email<font color="red"> *</font></td>
                    <td>
					<input name="email"  type="text" class=" form-control" required id="" value="'.$w['email'].'" />
					</td>
                </tr>
             	<tr>
                    <td align="right"valign="top">Website<font color="red"> *</font></td>
                    <td>
					<input name="website"  type="text" class=" form-control" required id="" value="'.$w['website'].'" />
					</td>
                </tr>
				
				
                 <tr>
                    <td colspan="2">
                        <input type="submit" class="tombols ui-corner-all" value="Simpan"/> 
                        <input type="button" class="tombols ui-corner-all" value="Kembali" onClick="window.location = \'index.php\'"/></td>
                  </tr>
            </table>
    </form><br/>
 ';
 }
}


////simpan /
function simpan_pt() {
global $koneksi_db;
  $md = $_REQUEST['md']+0;
	$id = $_REQUEST['id'];  

	if (trim($_POST['kode_pt'])=="") {
		$pesan[] = "Form Tahun masih kosong, ulangi kembali";
	}
	else if (trim($_POST['nama_pt'])=="") {
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
			$s = "update m_perguruan_tinggi set 
					kode_badan_hukum='".$_REQUEST['kode_badan_hukum']."',
					nama_pt='".$_REQUEST['nama_pt']."',
					singkatan='".$_REQUEST['singkatan']."',
					tgl_awal_berdiri='".$_REQUEST['tgl_awal_berdiri']."',
					alamat_1='".$_REQUEST['alamat1']."',
					alamat_2='".$_REQUEST['alamat2']."',
					kode_kota='".$_REQUEST['kode_kota']."',
					kode_propinsi='".$_REQUEST['kode_propinsi']."',
					kode_pos='".$_REQUEST['kode_pos']."',
					telepon='".$_REQUEST['telepon']."',
					fax='".$_REQUEST['fax']."',
					email='".$_REQUEST['email']."',
					website='".$_REQUEST['website']."',
					no_akta_sk_pendirian='".$_REQUEST['no_akta_sk_pendirian']."',
					tanggal_akta='".$_REQUEST['tanggal_akta']."',
					kode_akreditasi='".$_REQUEST['kode_akreditasi']."',
					no_sk_ban='".$_REQUEST['no_sk_ban']."',
					tgl_sk_ban='".$_REQUEST['tgl_sk_ban']."'
					where kode_pt='".$_REQUEST['kode_pt']."'
					 ";
			$koneksi_db->sql_query($s);
			
			} else {
			  $s = "INSERT INTO m_perguruan_tinggi set 
			  		kode_pt='".$_REQUEST['kode_pt']."',
					kode_badan_hukum='".$_REQUEST['kode_badan_hukum']."',
					nama_pt='".$_REQUEST['nama_pt']."',
					singkatan='".$_REQUEST['singkatan']."',
					tgl_awal_berdiri='".$_REQUEST['tgl_awal_berdiri']."',
					alamat_1='".$_REQUEST['alamat1']."',
					alamat_2='".$_REQUEST['alamat2']."',
					kode_kota='".$_REQUEST['kode_kota']."',
					kode_propinsi='".$_REQUEST['kode_propinsi']."',
					kode_pos='".$_REQUEST['kode_pos']."',
					telepon='".$_REQUEST['telepon']."',
					fax='".$_REQUEST['fax']."',
					email='".$_REQUEST['email']."',
					website='".$_REQUEST['website']."',
					no_akta_sk_pendirian='".$_REQUEST['no_akta_sk_pendirian']."',
					tanggal_akta='".$_REQUEST['tanggal_akta']."',
					kode_akreditasi='".$_REQUEST['kode_akreditasi']."',
					no_sk_ban='".$_REQUEST['no_sk_ban']."',
					tgl_sk_ban='".$_REQUEST['tgl_sk_ban']."'
					";
			  $koneksi_db->sql_query($s);
			}
		}
		//echo $s;
  perguruantinggi();
}

////////////////////

$go = (empty($_REQUEST['op'])) ? 'perguruantinggi' : $_REQUEST['op'];

echo '<div id="title" align="right">
        <font style="color:#FF9900; font-size:30px;"><strong>.:</strong></font>
        <font style="font-size:18px; color:#999999">Identitas</font><br />
        	<a href="?m=home">Home</a> &raquo; <a href="?m='.$_GET['m'].'">Perguruan Tinggi</a>  &raquo; '.$go.'  
    </div>';
echo '<div class="mainContentCell"><div class="content">';
$go();
echo '</div></div>';
?>

 
