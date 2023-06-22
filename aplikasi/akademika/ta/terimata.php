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

//FilterSemester('transkrip');
//FilterKelas('yudisium.daftar');
//FilterPeriodeYudisium('yudisium.daftar');


global $koneksi_db, $tahun_id;
$prodi = $_SESSION['prodi'];
//$tahun_id = $_SESSION['tahun_id'];



	$whr = array();
	if (!empty($_SESSION['prodi'])) $whr[] = "kode_prodi='$_SESSION[prodi]'";
	$whr[] = "tahun_id='$tahun_id'";
	if (!empty($whr)) $strwhr = "where " .implode(' and ', $whr);
//echo $strwhr;

require('system/pagination_class.php');
$sql = "select * from t_mahasiswa_ta $strwhr $ord";
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

echo '
<table   class=" table full">
   	<thead>
     <tr>
	   <th width="5%" align="center">No.</th>
	   <th align="center">NIM</th>
       <th align="center">Nama</th>
       <th align="center">Angkatan</th>
	   <th align="center" width="60">Semester</th>
	   <th align="center" width="60">SKS</th>
	   <th align="center" width="60">IPK</th>
	   <th align="center" width="10">Aksi</th>
     </tr>
	 </thead>
	 <tbody>';
	 
 
		$n=0;
		while($wr = $koneksi_db->sql_fetchassoc($result)){
		
		$wi = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM m_mahasiswa where `idm` = '$wr[idm]' limit 1 " ));
		$n++;
		$id = $wr['idta'];
		$jumlah_sks = jumlah_sks($wi['kode_prodi'], '', '', $wi['idm'] );
		$jumlah_ip = jumlah_ip($wi['kode_prodi'], '', '', $wi['idm'] );
		if (!empty($jumlah_ip) && !empty($jumlah_sks)) { $kumulatif = round($jumlah_ip / $jumlah_sks,2); }
	
		echo '<tr bgcolor="#f2f2f2">
				<td  align=center>'.$n.'</td> 
				<td  align=left>'.$wi['NIM'].'</td>
				<td  align=left>'.$wi['nama_mahasiswa'].'</td>
				<td  align=left>'.$wi['tahun_masuk'].'</td>
				<td >'.$wi['semester'].'</td>
				<td  align=center>'.$jumlah_sks.'</td>
				<td  align=center>'.$kumulatif.'</td>
				<td ><a href="#" onclick="window.location.href=\'index.php?m='.$_GET['m'].'&op=edit&id='.$id.'\';"><img src="images/update.png"/></a></div>
			</tr>'; 
		}
		echo '</tbody>
		</table>';
		echo $obj->total;
	echo '<br/>';
	echo $obj->anchors;	
	
	} else {
		echo '<div class="alert alert-danger" >Belum ada Data</div>';
	}
	 
 
}

function Edit() {
global $koneksi_db, $tahun_id, $prodi;
$id = $_REQUEST['id'];
	$w = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM t_mahasiswa_ta  where idta='$id' limit 1 " ));

	$wm = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM m_mahasiswa  where idm='$w[idm]' limit 1 " ));
echo'	<br/>
<table width="700" border="1" cellspacing="1" cellpadding="1" class=no-style>
  <tr>
    <td width="150">NAMA</td>
    <td width="5">:</td>
    <td width="230"><strong>'.$wm['nama_mahasiswa'].'</strong></td>
    <td width="30">&nbsp;</td>
    <td width="150">JURUSAN</td>
    <td width="5">:</td>
    <td width="230"><strong>'.viewkonsentrasi(''.$wm['kode_konsentrasi'].'').'</strong></td>
  </tr>
  <tr>
    <td>NIM</td>
    <td>:</td>
    <td><strong>'.$wm['NIM'].'</strong></td>
    <td>&nbsp;</td>
    <td>PROGRAM STUDI </td>
    <td>:</td>
    <td><strong>'.viewprodi(''.$wm['kode_prodi'].'').'</strong></td>
  </tr>
  <tr>
    <td>ANGKATAN</td>
    <td>:</td>
    <td><strong>'.$wm['tahun_masuk'].'</strong></td>
    <td>&nbsp;</td>
    <td>SEMESTER</td>
    <td>:</td>
    <td><strong>'.strtoupper(viewsmtr(''.$wm['semester']).'').'</strong></td>
  </tr>
  <tr>
    <td>BATAS STUDI </td>
    <td>:</td>
    <td><strong>'.strtoupper(NamaTahun($wm['batas_studi'],$wm['kode_prodi'])).'</strong></td>
    <td>&nbsp;</td>
    <td>TAHUN AJARAN </td>
    <td>:</td>
    <td><strong>'.strtoupper(NamaTahun($tahun_id, $prodi)).'</strong></td>
  </tr>
</table><br/>';
			
echo '  
    <form action="" method="post"  class="cmxform" id="form_input" style="width:100%">
		<input type="hidden" name="id" value="'.$id.'"/>
         <input type="hidden" name="m" value="'.$_GET['m'].'"/>
        <input type="hidden" name="op" value="simpan"/>
		<input type="hidden" name="md" value="'.$md.'"/>
       
            <legend class="ui-widget ui-widget-header ui-corner-all">Pengajuan Judul Tugas Akhir</legend>
            &nbsp;<font color="red"><br></font>
            <table width="100%"  border="0" class="datatable full">
                <tr>
                    <td colspan=2 align="center" valign="top">
					<textarea name="judul_ta" rows=4 cols=100 wrap=virtual>'.$w['judul_ta'].'</textarea>
					</td>
                </tr>

                <tr>
                    <td align="right" valign="top">Tanggal Pengajuan<font color="red"> *</font></td>
                    <td><input name="tanggal_daftar"  type="text" class="tcal date required" id="" value="'.$w['tanggal_daftar'].'" /> </td>
				</tr>
                <tr>
                    <td align="right" valign="top">Tanggal Mulai<font color="red"> *</font></td>
                    <td><input name="tanggal_mulai"  type="text" class="tcal date required" id="" value="'.$w['tanggal_mulai'].'" /> </td>
				</tr>
                <tr>
                    <td align="right" valign="top">Tanggal Berakhir<font color="red"> *</font></td>
                    <td><input name="tanggal_akhir"  type="text" class="tcal date required" id="" value="'.$w['tanggal_akhir'].'" /> </td>
				</tr>
                <tr>
                    <td align="right" valign="top">Tanggal Sidang<font color="red"> *</font></td>
                    <td><input name="tanggal_ujian"  type="text" class="tcal date required" id="" value="'.$w['tanggal_ujian'].'" /> </td>
				</tr>
				<tr>
                    <td align="right"valign="top">Dosen Pembimbing 1<font color="red"> *</font></td>
                    <td><select name="pembimbing_1"  class="required "  />'.opdosen(''.$w['pembimbing_1'].'').'</select></td>
                </tr>				
				<tr>
                    <td align="right"valign="top">Dosen Pembimbing 2<font color="red"> *</font></td>
                    <td><select name="pembimbing_2"  class="required "  />'.opdosen(''.$w['pembimbing_2'].'').'</select></td>
                </tr>				

				<tr>
                    <td align="right"valign="top">Dosen Penguji 1<font color="red"> *</font></td>
                    <td><select name="penguji_1"  class="required "  />'.opdosen(''.$w['penguji_1'].'').'</select></td>
                </tr>				

				<tr>
                    <td align="right"valign="top">Dosen Penguji 2<font color="red"> *</font></td>
                    <td><select name="penguji_2"  class="required "  />'.opdosen(''.$w['penguji_2'].'').'</select></td>
                </tr>				
				
				<tr>
                    <td align="right"valign="top">Diterima<font color="red"> *</font></td>
                    <td><select name="terima"  class="required "   />'.opYN(''.$w['terima'].'').'</select></td>
                </tr>
				
                 <tr>
                    <td colspan="2">
                        <input type="submit" class=tombols ui-corner-all value="Simpan"/> 
                        <input type="button" class=tombols ui-corner-all value="Kembali" onClick="window.location = \'index.php?m='.$_GET['m'].'\'"/></td>
                  </tr>
            </table>
        </fieldset>
    </form>
 ';	
}

function simpan() {
global $koneksi_db, $user;
$id = $_REQUEST['id'];  
$w = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM m_mahasiswa where idm='$user' limit 1 " ));
$tanggal = date('Y-m-d');
	if (!empty($id) ) {
	$s = "update t_mahasiswa_ta set 
			judul_ta='".$_REQUEST['judul_ta']."',
			tanggal_daftar='".$_REQUEST['tanggal_daftar']."',
			tanggal_mulai='".$_REQUEST['tanggal_mulai']."',
			tanggal_akhir='".$_REQUEST['tanggal_akhir']."',
			tanggal_ujian='".$_REQUEST['tanggal_ujian']."',
			pembimbing_1='".$_REQUEST['pembimbing_1']."',
			pembimbing_2='".$_REQUEST['pembimbing_2']."',
			penguji_1='".$_REQUEST['penguji_1']."',
			penguji_2='".$_REQUEST['penguji_2']."',
			terima='".$_REQUEST['terima']."'
			where idta='".$_REQUEST['id']."' ";
	$koneksi_db->sql_query($s);
	
	} 
Daftar();
//echo $s;
}


$go = (empty($_REQUEST['op'])) ? 'Daftar' : $_REQUEST['op']; 

echo '<div id="title" align="right">
        <font style="color:#FF9900; font-size:30px;"><strong>.:</strong></font>
        <font style="font-size:18px; color:#999999">Tugas Akhir Mahasiswa</font><br />
        	<a href="index.php">Home</a> &raquo; <a href="index.php?m='.$_GET['m'].'">Daftar TA Mahasiswa</a>  &raquo; '.$go.'  
    </div>';
echo '<div class="mainContentCell"><div class="content">';
$go();
echo '</div></div>';


?>
