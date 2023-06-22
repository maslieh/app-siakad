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

function Daftar() {

global $koneksi_db, $tahun_id,$prodi;
$prodi = $_SESSION['prodi'];
$kode_mk = $_SESSION['kode_mk'];
$kelas = $_SESSION['kelas'];
$idm = $_SESSION['idm'];



			
echo '<div class="row"><div class="col-md-4">';
		FilterMataKuliahDosen($prodi, $tahun_id, '', $_GET['m']); 
echo '</div><div class="col-md-4">';		
		FilterKelas($prodi, $tahun_id, '', $_GET['m']); 
echo '</div><div class="col-md-4">';		
		FilterMhsKuliah($prodi, $tahun_id, $_GET['m']); 
echo '</div></div>';		
	
	echo '  
				<form action="" method="post"  class="cmxform" id="form_input" style="width:100%">
					<input type="hidden" name="op" value="simpan"/>
					<input type="hidden" name="idm" value="'.$idm.'"/>
					<input type="hidden" name="kode_mk" value="'.$kode_mk.'"/>
					<input type="hidden" name="kelas" value="'.$kelas.'"/>
				   </br>
					<fieldset class="ui-widget ui-widget-content ui-corner-all" >
						<legend class="ui-widget ui-widget-header ui-corner-all">Pendataan Komponen Nilai </legend>
					   
					   
					   <table  border="0" class="datatable full1">
						
						<tr>
							<td  >Jenis Nilai</td>
							<td> 
							<select  class="required" name="jenis_nilai" >'.opJenisNilai2($jenis_nilai).'</select>
						</td>
						 
							<td   >Nilai<font color="red"> *</font></td>
							<td  ><input name="nilai"  type="text" class="required"   /></td>
						</tr>
		
					   
						
					</table> 
					</fieldset>
					
					<br/>
							
							<input type="submit" name="simpan" class=tombols ui-corner-all value="Simpan"/>
							
				</form>
			 ';

}
	
function Simpan() {
global $koneksi_db, $prodi, $tahun_id;
$idm= $_REQUEST['idm'];
$kode_mk= $_REQUEST['kode_mk'];
		
			
				$lihatkrs = $koneksi_db->sql_query("SELECT * FROM t_mahasiswa_krs where idm='$idm' and tahun_id= '$tahun_id' and id='$kode_mk'" );
				$krs = $koneksi_db->sql_fetchassoc($lihatkrs);
				
				
				
				$so = "insert into t_mahasiswa_nilai SET 
						kode_pt='".$krs['kode_pt']."',
						kode_fak='".$krs['kode_fak']."',
						kode_jenjang='".$krs['kode_jenjang']."',
						kode_konsentrasi='".$krs['kode_konsentrasi']."',
						kode_prodi='$prodi',
						tahun_id='$tahun_id',
						idm ='$idm', 
						jenis_nilai ='$_REQUEST[jenis_nilai]', 
						kelas = '$_REQUEST[kelas]', 
						id = '$_REQUEST[kode_mk]', 
						nilai ='$_REQUEST[nilai]', 
						nilai_ke = 1
						";
				$koneksi_db->sql_query($so);
				
				
				$hadir1 = hadir($krs['kode_prodi'], $krs['tahun_id'], $krs['id'],  $krs['idm'] );
				$tugas1 = tugas($krs['kode_prodi'], $krs['tahun_id'], $krs['id'],  $krs['idm'] );
				$uts1 = uts($krs['kode_prodi'], $krs['tahun_id'], $krs['id'],  $krs['idm'] );	
				$uas1 = uas($krs['kode_prodi'], $krs['tahun_id'], $krs['id'],  $krs['idm'] );	
				
				$rata = ($hadir1*0.1)+($tugas1*0.2)+($uts1*0.3)+($uas1*0.4);	
				
				$nilaiakhir = $rata ;
				
				$nl = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM m_nilai
				where kode_prodi='$krs[kode_prodi]' and `nilai_min` <= '$nilaiakhir' and  `nilai_max` >= '$nilaiakhir' limit 1" ));
				
				$ip = $nl['bobot'] * $krs['sks'];
				$update = $koneksi_db->sql_query("UPDATE `t_mahasiswa_krs` SET `jumlah_nilai`='$nilaiakhir', `nilai`='$nl[nilai]', `bobot`='$nl[bobot]', `ip`='$ip', `lulus`='$nl[lulus]' WHERE `idkrs` = '$krs[idkrs]'");
					
			
		
		echo "<div  class='error'>Proses Menyimpan Data...</div>";		  
		echo "<meta http-equiv='refresh' content='1; url=index.php?m=".$_GET['m']."'>";
	

}

$kode_mk = BuatSesi('kode_mk');
$kelas = BuatSesi('kelas');
$idm = BuatSesi('idm');


$go = (empty($_REQUEST['op'])) ? 'Daftar' : $_REQUEST['op']; 

echo '<div id="title" align="right">
        <font style="color:#FF9900; font-size:30px;"><strong>.:</strong></font>
        <font style="font-size:18px; color:#999999">Nilai Mahasiswa Mahasiswa</font><br />
        	<a href="index.php">Home</a> &raquo; <a href="index.php?m='.$_GET['m'].'">Nilai Mahasiswa</a>  &raquo; '.$go.'  
    </div>';
echo '<div class="mainContentCell"><div class="content">';
$go();
echo '</div></div>';

?>