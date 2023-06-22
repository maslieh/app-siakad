<?php

if (!login_check()) {
		//alihkan user ke halaman logout
		logout ();
		session_destroy();
		//echo '<div class="message"><div class="success"><h3>Logout SUKSES</h3></div></div>';
		echo '<meta http-equiv="refresh" content="0; url=index.php" />';
		//exit(0);
}

function hapus() {
global $koneksi_db, $user;
$koneksi_db->sql_query("DELETE FROM t_mahasiswa_krs WHERE idkrs='$_GET[idkrs]'");
Daftar();
}

?>

<SCRIPT type=text/javascript>
 $(document).ready(function(){
	$("#loader").hide();
  $("#kode_mk").change(function(){
		$("#loader").fadeIn(500);
		 $("#kelas").fadeOut();
    var kode_mk = $("#kode_mk").val();
    $.ajax({
        url: "system/get_krs.php",
         data: "kode_mk=" + kode_mk,
        success: function(data){
            // jika data sukses diambil dari server, tampilkan di <select id=kelas>
            $("#kelas").html(data);
		        $("#loader").fadeOut(500);
		        $("#kelas").fadeIn(500);
        }
    });
  });
});
</script>

<?php

function Daftar() {
global $koneksi_db, $user, $prodi, $Mkelas,$Mstatus,$tahun_id;
//echo $tahun_id;
	if ($_SESSION['Level']!="MAHASISWA"	) {
		FilterMahasiswa($_GET['m']);
		$whr = array();
		$ord = '';
		if (($_SESSION['reset_mahasiswa'] == 'Reset') &&
		empty($_SESSION['kolom_mahasiswa']) && empty($_SESSION['kunci_mahasiswa'])) {
		echo "";
		} else {
		$whr[] = "$_SESSION[kolom_mahasiswa] like '%$_SESSION[kunci_mahasiswa]%' ";
		$ord = "order by $_SESSION[kolom_mahasiswa]";
		echo "";
		}
		$whr[] = "status_aktif='A'";
		if (!empty($whr)) $strwhr = "where " .implode(' and ', $whr);
		
		$ambilmhs= $koneksi_db->sql_query( "SELECT * FROM m_mahasiswa $strwhr  limit 1 " );

	} else {
		$ambilmhs = $koneksi_db->sql_query( "SELECT * FROM m_mahasiswa where idm='$user'  limit 1 " );
	}
		if ( $koneksi_db->sql_numrows( $ambilmhs ) > 0) {
		$wm = $koneksi_db->sql_fetchassoc( $ambilmhs );
		$status = $wm['status_aktif'];
		$idm = $wm['idm'];
		$fotonya = ($wm['foto'] =="" ) ? "gambar/no_avatars.gif": "gambar/".$wm['foto']."";
		echo'
		<table  border="0" cellspacing="1"class="datatable full" cellpadding="1">
		<thead>
		  <tr>
			<td width="149">NIM</td>
			<td width="436"><b>'.$wm['NIM'].'</b></td>
			<td width="37" valign="top" rowspan="5"><img src="'.$fotonya.'" width="90" height="120"></td>
		  </tr>
		  <tr>
			<td>NAMA</td>
			<td><b >'.$wm['nama_mahasiswa'].'</b></td>
		  </tr>
		  <tr>
			<td>KOSENTRASI</td>
			<td><b >'.viewkonsentrasi(''.$wm['kode_konsentrasi'].'').'</b ></td>
		  </tr>
		  <tr>
			<td>PROGRAM STUDI </td>
			<td><b >'.viewprodi(''.$wm['kode_prodi'].'').'</b ></td>
		  </tr>
		  </thead>
		</table>
	  ';
	  	if ($status !="A") {
			echo '<div class="error" style="width:70%">Maaf Anda tidak dapat Ambil KRS, Status Anda masih '.viewAplikasi('05', ''.$status.'').'</div>';
			
		} else {
			
			$level = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT level FROM user where userid='$user' " ));
			
			if ($level['level']!='ADMIN' && $level['level']!='ADAK' && $level['level']!='PRODI'){
			if(CekBatasKRS()){
					
		
							
					echo '<br/>
					
					<span id=info></span>
					
					<form action="" method="post"  class="" id="form_input" name="form_input" style="width:100%">
					<input type="hidden" name="id" value="'.$id.'"/>
					<input type="hidden" name="m" value="'.$_GET['m'].'"/>
					<input type="hidden" name="op" value="update"/>
					<input type="hidden" name="idm" value="'.$idm.'"/>
					<table class=box cellspacing=1 cellpadding=4  >
					<tr>
						<td width=100>Mata Kuliah</td>
						<td> 
							<select name="kode_mk"  id="kode_mk" class="required">
							<option>.:: Pilih Matakuliah ::.</option>';
								
								  // tampilkan nama-nama matakuliah yang ada di database
								  $sql = $koneksi_db->sql_query("SELECT id, kode_mk, nama_mk, kelas, tahun_id from  view_jadwal where tahun_id='$tahun_id' and kode_prodi='$prodi' GROUP BY kode_mk
ORDER BY nama_mk ASC");
								  while($p=$koneksi_db->sql_fetchassoc($sql)){
									 echo "<option value=$p[id]>| (SEMESTER: $p[semester])  |-|  Matakuliah: $p[kode_mk] - $p[nama_mk]</option> \n";
								  } 	
							echo'	
							</select>
						</td> 
					</tr>
					<tr>
						<td width=100>Kelas</td>
						<td>
							<select name="kelas"  id="kelas" class="required">
							 <option>.:: Pilih Kelas ::.</option>
							</select>
						</td>
					</tr>
					</table>
							<input type="submit" name="ambilkrs" class=tombols ui-corner-all value="Ambil KRS"/>
							<br/><BR>
						<table width="100%" border="0" align="center" cellpadding="1" cellspacing="0" class="datatable setengah2" >
						<thead>	
							<tr>
								
								<th width="100" rowspan="2" valign="middle">Kode</th>
								<th width="250" rowspan="2" valign="middle">Mata Kuliah</th>
								<th width="50" rowspan="2" valign="middle">Kelas</th>
								<th colspan="4" >SKS</td>
								<th rowspan="2" >Aksi</td>
							</tr>
							<tr>
							  <th width="25" >MK</th>
								<th width="25" >T</th>
								<th width="25" >P</th>
								<th width="25" >L</th>
							</tr>
						</thead>
						 <tbody>';
						 
						 $s = "select  * from view_jadwal  where kode_prodi='$prodi' and tahun_id='$tahun_id'";
						 $s_sks = $koneksi_db->sql_query($s);
						$jumlah=$koneksi_db->sql_numrows($s_sks);
						if ($jumlah > 0){
							$pengampu =array();
							
							while($k = $koneksi_db->sql_fetchassoc($s_sks)){
							$pengampu = explode("|", $k[idd]);
							
							foreach($pengampu as $kk=>$p ){
								$dosenx = viewdosen($p);
							}
							
							$q = "select  * from t_mahasiswa_krs where idm='$idm' and id='$k[id]'";
							if ( $koneksi_db->sql_numrows( $koneksi_db->sql_query( $q ))  < 1 ) {
							
								$dsnx = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM t_dosen_pengajaran where kelas='$kelas_siswa' and id='$k[id]' limit 1 " ));
								$n++;
								$kode[] = $k['id'];
								$kodemk[] = $k['kode_mk'];
								$nama[] = $k['nama_mk'];
								$dosen[] =$dosenx;
								$hari[] = $k['hari'];
								$sks[] = $k['sks_mk'];
								$skst[] = $k['sks_teori'];
								$sksp[] = $k['sks_praktek'];
								$sksl[] = $k['sks_lapangan'];
								
							}
							}
							
							//menampilkan matakuliah ke dalam tabel
							//for($i=0;$i<count($kode);$i++){
							$tkrs = $koneksi_db->sql_query("select  a.*, b.* from t_mahasiswa_krs a 
												left outer join m_mata_kuliah b on a.id=b.id 
												where a.idm='$idm' and a.tahun_id='$tahun_id' and a.kode_prodi='$prodi'");
							while($data=$koneksi_db->sql_fetchassoc($tkrs)){
							$idkrs = $data['idkrs'];
							$Tot=$Tot+$data['sks_mk'];
							
							echo '<tr >
									
									<td id=k1'.$i.' valign="top" align="center">'.$data['kode_mk'].'</td>
									<td id=k2'.$i.' valign="top ">'.$data['nama_mk'].'</td>
									<td id=k3'.$i.' valign="top" >'.$data['kelas'].'</td>
									<td id=k4'.$i.' valign="top" align=center>'.$data['sks_mk'].'</td>
									<td id=k5'.$i.' valign="top" align=center>'.$data['sks_teori'].'</td>
									<td id=k6'.$i.' valign="top" align=center>'.$data['sks_praktek'].'</td>
									<td id=k7'.$i.' valign="top" align=center>'.$data['sks_lapangan'].'</td>
									<td id=k8'.$i.' valign="top" align=center>
									<a href=index.php?m='.$_GET['m'].'&op=hapus&idkrs='.$idkrs.' 
									onClick=\'return confirm("Apakah Anda yakin ingin menghapus matakuliah ini?")\'><img src="images/delete.png"/></a>
									</td>
								</tr>'; 
							}
							
							echo '<thead><tr > <th  colspan="8" align=right>Jumlah SKS Yang Dipilih : '.number_format($Tot,0,',','.').' <span id=jsks></span> SKS<br/>
							<br/>
							<br/>
							NOTE* <br/>Untuk setiap Mahasiswa yang mengambil KRS dimohon untuk perhatikan Kode Kelas<br/>
								Jika ada Kelebihan SKS Silahkan Pilih SKS Diluar Semester Anda</th></tr><br/>
						    
						</th>	</tr>';
				
						} else {
							 echo '
							 <thead>
								<tr >
								<th  colspan="8" align=center>Belum ada Paket KRS</th>
								</tr>
							</thead>';
						}
						 echo '</tbody>
							</table></form>s';

			
			}
			}
			else {
		
							
					echo '<br/>
					
					<span id=info></span>
					
					<form action="" method="post"  class="" id="form_input" name="form_input" style="width:100%">
					<input type="hidden" name="id" value="'.$id.'"/>
					<input type="hidden" name="m" value="'.$_GET['m'].'"/>
					<input type="hidden" name="op" value="update"/>
					<input type="hidden" name="idm" value="'.$idm.'"/>
					<table class=box cellspacing=1 cellpadding=4  >
					<tr>
						<td width=100>Mata Kuliah</td>
						<td> 
							<select name="kode_mk"  id="kode_mk" class="required">
							<option>.:: Pilih Matakuliah ::.</option>';
								
								  // tampilkan nama-nama matakuliah yang ada di database
								  $sql = $koneksi_db->sql_query("SELECT id, kode_mk,semester, nama_mk, kelas from  view_jadwal where tahun_id='$tahun_id' and kode_prodi='$prodi' GROUP BY kode_mk
ORDER BY semester ASC ");
								  while($p=$koneksi_db->sql_fetchassoc($sql)){
									 echo "<option value=$p[id]> ( SEMESTER : $p[semester] )  - <b> Matakuliah : $p[kode_mk]- $p[nama_mk] </b></option> \n";
								  } 	
							echo'	
							</select>
						</td> 
					</tr>
					<tr>
						<td width=100>Kelas</td>
						<td>
							<select name="kelas"  id="kelas" class="required">
							 <option>.:: Pilih Kelas ::.</option>
							</select>
						</td>
					</tr>
					</table>
							<input type="submit" name="ambilkrs" class=tombols ui-corner-all value="Ambil KRS"/>
							<br/><BR>
						<table width="100%" border="0" align="center" cellpadding="1" cellspacing="0" class="datatable setengah2" >
						<thead>	
							<tr>
								
								<th width="100" rowspan="2" valign="middle">Kode</th>
								<th width="250" rowspan="2" valign="middle">Mata Kuliah</th>
								<th width="50" rowspan="2" valign="middle">Kelas</th>
								<th colspan="4" >SKS</td>
								<th rowspan="2" align=center>Aksi</td>
							</tr>
							<tr>
							  <th width="25" >MK</th>
								<th width="25" >T</th>
								<th width="25" >P</th>
								<th width="25" >L</th>
							</tr>
						</thead>
						 <tbody>';
						 
						 $s = "select  * from view_jadwal  where kode_prodi='$prodi' and tahun_id='$tahun_id'";
						 $s_sks = $koneksi_db->sql_query($s);
						$jumlah=$koneksi_db->sql_numrows($s_sks);
						if ($jumlah > 0){
							$pengampu =array();
							
							while($k = $koneksi_db->sql_fetchassoc($s_sks)){
							$pengampu = explode("|", $k[idd]);
							
							foreach($pengampu as $kk=>$p ){
								$dosenx = viewdosen($p);
							}
							
							$q = "select  * from t_mahasiswa_krs where idm='$idm' and id='$k[id]'";
							if ( $koneksi_db->sql_numrows( $koneksi_db->sql_query( $q ))  < 1 ) {
							
								$dsnx = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM t_dosen_pengajaran where kelas='$kelas_siswa' and id='$k[id]' limit 1 " ));
								$n++;
								$kode[] = $k['id'];
								$kodemk[] = $k['kode_mk'];
								$nama[] = $k['nama_mk'];
								$dosen[] =$dosenx;
								$hari[] = $k['hari'];
								$sks[] = $k['sks_mk'];
								$skst[] = $k['sks_teori'];
								$sksp[] = $k['sks_praktek'];
								$sksl[] = $k['sks_lapangan'];
								
							}
							}
							
							//menampilkan matakuliah ke dalam tabel

							$tkrs = $koneksi_db->sql_query("select  a.*, b.* from t_mahasiswa_krs a 
												left outer join m_mata_kuliah b on a.id=b.id 
												where a.idm='$idm' and a.tahun_id='$tahun_id' and a.kode_prodi='$prodi'");
							while($data=$koneksi_db->sql_fetchassoc($tkrs)){
							$idkrs = $data['idkrs'];
							$Tot=$Tot+$data['sks_mk'];
							
							echo '<tr >
									
									<td id=k1'.$i.' valign="top" align="center">'.$data['kode_mk'].'</td>
									<td id=k2'.$i.' valign="top ">'.$data['nama_mk'].'</td>
									<td id=k3'.$i.' valign="top" >'.$data['kelas'].'</td>
									<td id=k4'.$i.' valign="top" align=center>'.$data['sks_mk'].'</td>
									<td id=k5'.$i.' valign="top" align=center>'.$data['sks_teori'].'</td>
									<td id=k6'.$i.' valign="top" align=center>'.$data['sks_praktek'].'</td>
									<td id=k7'.$i.' valign="top" align=center>'.$data['sks_lapangan'].'</td>
									<td id=k8'.$i.' valign="top" align=center>
									<a href=index.php?m='.$_GET['m'].'&op=hapus&idkrs='.$idkrs.' 
									onClick=\'return confirm("Apakah Anda yakin ingin menghapus matakuliah ini?")\'><img src="images/delete.png"/></a>
									</td>
								</tr>'; 
							}
							
							echo '<thead><tr > <th  colspan="8" align=right>Jumlah SKS Yang Dipilih : '.number_format($Tot,0,',','.').' <span id=jsks></span> SKS  <br/>
							<br/>
							<br/>
							NOTE* <br/>Untuk setiap Mahasiswa yang mengambil KRS dimohon untuk perhatikan Kode Kelas<br/>
								Jika ada Kelebihan SKS Silahkan Pilih SKS Diluar Semester Anda</th></tr>';
				
						} else {
							 echo '
							 <thead>
								<tr >
								<th  colspan="8" align=center>Belum ada Paket KRS</th>
								</tr>
							</thead>';
						}
						 echo '</tbody>
							</table></form>';
					
					
			}
		}
		
	}
	if($_SESSION['Level']!="MAHASISWA"	){
		FormImport(); ?>&nbsp<?php 
	}
	
}

function FormImport() {
	echo' <br />
    <h3>Import Data KRS</h3>
	
<div >
<a href="files/format_krs.xls" class="btn btn-danger">Download Format</a>
</div>
<div class="col-md-4">
	
<form action="" method="post" id="form_input" enctype="multipart/form-data">
<input type="hidden" name="m" value="mahasiswa" />
<input type="hidden" name="op" value="Import"/>
<div class="form-group">
	<input type="file" name="fileimport" required  class="form-control">
	<button type="submit" class="btn btn-default">Proses Import Data</button>
</div>
				
</form>
</div>
<div class="col-md-8">	      
<div class="alert alert-success">				
File yang diimport harus berekstensi .xls.<br/> 
Fitur ini digunakan untuk import KRS massal semua jurusan dan mahasiswa
<br/>
 
 </div></div>';
}


function Import() {
require("system/excel_reader2.php");
global $koneksi_db, $tahun_id;
$prodi = $_SESSION['prodi'];
$_tahun = substr($tahun_id, 0, 4)+0;
        //echo $prodi, "TEST";
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
			//$idm = kdauto('m_mahasiswa','M');
			
			  // membaca data nidn (kolom ke-1 dan seterusnya)
			  // kolom identitas mahasiswa
			  $kode_prd	= 			$data->val($i, 1); 
			  $jenjang 	= 			$data->val($i, 2);
			  $id_mhs 	= 			$data->val($i, 3);
			  $kelas 	= 			$data->val($i, 4);
			  $id_mk 	= 			$data->val($i, 5);
			  $sks 		= 			$data->val($i, 6);
			  $hari	 	= 			$data->val($i, 7);
			  $sts_krs	= 			$data->val($i, 8);
			  $sts_ambil= 			$data->val($i, 9);
			  $vr_pa	= 			$data->val($i, 10);
			  
			
			  
			  		  		//insert data 
			$qada = "select * from t_mahasiswa_krs where id='$id_mk' and idm='$id_mhs' ";
			$qryu = $koneksi_db->sql_query($qada);
			if ($koneksi_db->sql_numrows($qryu) > 0 ) {
				/*
				$su = "update m_mahasiswa SET 
						kode_pt='".$kodept."',
						kode_fak='".$kodefak."',
						kode_jurusan='".$kodejurusan."',
						kode_prodi='".$prodi."',
						kode_jenjang='".$kodejen."',
						nama_mahasiswa='".$nama_mahasiswa."',
						agama='".$agama."',
						jenis_kelamin='".$jenis_kelamin."',
						tempat_lahir='".$tempat_lahir."',
						tanggal_lahir='".$tanggal_lahir."',
						telepon='".$telepon."',
						hp='".$hp."',
						email='".$email."',
						status_masuk='".$status_masuk."'
						WHERE NIM = '".$NIM."'
					";
				$hasil_update =  $koneksi_db->sql_query($su);
				*/
				$sukses_update++;
				$nama_update .= "<li>".$kelas."</li>";
			} else {
			
					$si = "insert into t_mahasiswa_krs SET 
						kode_pt='".$kodept."',
						kode_fak='".$kodefak."',
						kode_prodi='".$kode_prd."',
						kode_jenjang='".$jenjang."',
						idm='".$id_mhs."',
						tahun_id='".$tahun_id."',
						kelas='".$kelas."',
						sks='".$sks."',
						hari='".$hari."',
						status_krs='".$sts_krs."',
						status_ambil='".$sts_ambil."',
						verifi_pa='".$vr_pa."',
						id='".$id_mk."'
					";
					
					
				$hasil_import = $koneksi_db->sql_query($si);
				$nama_hasil .= "<li>".$kelas."</li>";			
			}
		  
			  if ($hasil_import) { $sukses_import++; } 
			  else   { $gagal++;  } 
		}
	
echo  "<fieldset class=cari>
	<legend> Proses import data </legend>
<table width=400 border=0>
	<tr><td align=right>Mata Kuliah Berhasil Di Import</td><td width=100> ".$sukses_import."</td></tr>
	<tr><td align=right></td><td width=100><ul> ".$nama_hasil."</ul></td></tr>
	<tr><td align=right>Jumlah data yang gagal diimport </td><td>: ".$gagal."</td></tr>
</table>
</fieldset>	<br/>
<input id=kembali type=button class=button-red class=ui-button tombols ui-corner-all value=Kembali ke daftar Dosen style=\"font-size: 11px;height: inherit;\"  onclick=\"window.location='index.php?m=".$_GET['m']."'\">
	";
}

function update() {
global $koneksi_db, $prodi, $tahun_id, $user;
$sukses_import = 0;
$sudah_import = 0;

	if (isset($_REQUEST['ambilkrs'])) {
		$kelas = $_POST['kelas'];
		$kdmk = $_POST['kode_mk'];
		$idm = $_POST['idm'];
		
			
			if (substr($tahun_id, 4) == 1){
			$tahun_id_sebelumnya = (substr($tahun_id,0,4)-1).'2';
			}else{
			$tahun_id_sebelumnya = (substr($tahun_id,0,4)).'1';	
			}
			
			$xx = $koneksi_db->sql_numrows($koneksi_db->sql_query( "SELECT * FROM `t_mahasiswa_krs` 
								where kode_prodi='$prodi' 
								and tahun_id='$tahun_id_sebelumnya' 
								and idm='$idm' and nilai != ''
 
								" ));
			
			
			if (substr($tahun_id_sebelumnya, 4) == 1){
			$tahun_id_sebelumnya1 = (substr($tahun_id_sebelumnya,0,4)-1).'2';
			}else{
			$tahun_id_sebelumnya1 = (substr($tahun_id_sebelumnya,0,4)).'1';	
			}
			
			$xx1 = $koneksi_db->sql_numrows($koneksi_db->sql_query( "SELECT * FROM `t_mahasiswa_krs` 
								where kode_prodi='$prodi' 
								and tahun_id='$tahun_id_sebelumnya1' 
								and idm='$idm' and nilai != ''
								" ));

			if (substr($tahun_id_sebelumnya1, 4) == 1){
			$tahun_id_sebelumnya2 = (substr($tahun_id_sebelumnya1,0,4)-1).'2';
			}else{
			$tahun_id_sebelumnya2 = (substr($tahun_id_sebelumnya1,0,4)).'1';	
			}
			
			$xx2 = $koneksi_db->sql_numrows($koneksi_db->sql_query( "SELECT * FROM `t_mahasiswa_krs` 
								where kode_prodi='$prodi' 
								and tahun_id='$tahun_id_sebelumnya2' 
								and idm='$idm' and nilai != ''
								" ));
								
			if($xx>0){
						  //batas sks
						  
			$jumlah_sks_semua = jumlah_sks($prodi, $tahun_id_sebelumnya,  $idm );
			$jumlah_ip_semua = jumlah_ip($prodi, $tahun_id_sebelumnya,  $idm );
			if (!empty($jumlah_ip_semua) && !empty($jumlah_sks_semua)) { $kumulatif_semua = round($jumlah_ip_semua / $jumlah_sks_semua,2); 
			} 
			}else if($xx1>0){
						  //batas sks
			$jumlah_sks_semua = jumlah_sks($prodi, $tahun_id_sebelumnya1,  $idm );
			$jumlah_ip_semua = jumlah_ip($prodi, $tahun_id_sebelumnya1,  $idm );
			if (!empty($jumlah_ip_semua) && !empty($jumlah_sks_semua)) { $kumulatif_semua = round($jumlah_ip_semua / $jumlah_sks_semua,2); 
			} 
			}else if($xx2>0){
						  //batas sks
			$jumlah_sks_semua = jumlah_sks($prodi, $tahun_id_sebelumnya2,  $idm );
			$jumlah_ip_semua = jumlah_ip($prodi, $tahun_id_sebelumnya2,  $idm );
			if (!empty($jumlah_ip_semua) && !empty($jumlah_sks_semua)) { $kumulatif_semua = round($jumlah_ip_semua / $jumlah_sks_semua,2); 
			} 
			}else $kumulatif_semua = 3;
			 
			
			$wsks = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM m_krs
								where kode_prodi='$prodi' and `ipk_min` <= '$kumulatif_semua' and  `ipk_max` >= '$kumulatif_semua' limit 1" ));	
			$boleh = $wsks['jml_sks'];			
			  
			  $z = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT sum(sks) as t FROM t_mahasiswa_krs where idm='$idm' and kode_prodi='$prodi' and tahun_id='$tahun_id'" ));
			  $sks_siswa =$z['t'];
			  
			  $mk = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query("SELECT * FROM `view_jadwal` where `id` = '$kdmk' and kelas='$kelas' and tahun_id=$tahun_id" ));
			  $krs1 = $mk['sks_mk'];
			  $jml_krs = $krs1 + $sks_siswa;
			  $qm = $koneksi_db->sql_query( "SELECT * FROM `m_mahasiswa` 
			  	where kode_prodi='$mk[kode_prodi]'
				and idm='$idm'  
				and status_aktif='A'
				" );
				$jumlah=$koneksi_db->sql_numrows($qm);
				
				if ($jumlah > 0){
					while($wm = $koneksi_db->sql_fetchassoc($qm)){
						
						 $ada = $koneksi_db->sql_numrows($koneksi_db->sql_query( "SELECT * FROM `t_mahasiswa_krs` 
								where kode_prodi='$prodi' 
								and tahun_id='$tahun_id' 
								and idm='$idm' 
								and id='".$_REQUEST['kode_mk']."'
								" ));
								
								
						$jam = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query("SELECT a.hari, b.jam  FROM t_jadwal a join m_jam b  on a.jamke=b.idj  
								where a.kode_prodi='$prodi' 
								and a.tahun_id='$tahun_id' 
								and a.id='".$_REQUEST['kode_mk']."'
								and a.kelas='".$_REQUEST['kelas']."'
								"));
								
								
						$bentrok = $koneksi_db->sql_numrows($koneksi_db->sql_query( "SELECT 
								a.kode_pt, a.kode_fak, a.kode_jenjang, a.kode_prodi, a.idm, a.id, a.kelas, 
								a.tahun_id, a.hari, b.jamke, b.mulai
								FROM t_mahasiswa_krs a
								JOIN view_jadwal b
								WHERE b.id = a.id
								AND b.kelas = a.kelas
								AND b.tahun_id = a.tahun_id
								and a.kode_prodi='$prodi' 
								and a.tahun_id='$tahun_id' 
								and a.idm='$idm' 
								and a.hari='".$jam['hari']."'
								and b.jamke='".$jam['jam']."'
								" ));
								
						$bentrok2 = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT 
						a.kode_pt, a.kode_fak, a.kode_jenjang, a.kode_prodi, a.idm, a.id, a.kelas, a.tahun_id, a.hari, b.jamke, b.mulai
								FROM t_mahasiswa_krs a
								JOIN view_jadwal b
								WHERE b.id = a.id
								AND b.kelas = a.kelas
								AND b.tahun_id = a.tahun_id
								and a.kode_prodi='$prodi' 
								and a.tahun_id='$tahun_id' 
								and a.idm='$idm' 
								and a.hari='".$jam['hari']."'
								and b.jamke='".$jam['jam']."'
								" ));
								
								
						$bentrokdet = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( " select 
								kode_mk,  nama_mk 
								FROM m_mata_kuliah 
								where id='".$bentrok2['id']."'
								" ));
						
						$adapra = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT id_mp FROM `m_mata_kuliah_prasyarat` 
								where kode_prodi='$prodi' 
								and id='".$_REQUEST['kode_mk']."'
								" ));
								
						$namapra = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT kode_mk, nama_mk FROM `m_mata_kuliah` 
								where kode_prodi='$prodi' 
								and id='".$adapra['id_mp']."'
								" ));

						$nilaipra = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query("SELECT min(nilai) as nilai FROM t_mahasiswa_krs  
								where kode_prodi='$prodi' 
								and idm='$idm'
								and id='".$adapra['id_mp']."'
								"));
								
						$penuh = $koneksi_db->sql_numrows($koneksi_db->sql_query( "SELECT * FROM `t_mahasiswa_krs` 
								where kode_prodi='$prodi' 
								and tahun_id='$tahun_id' 
								and id='".$_REQUEST['kode_mk']."'
								and kelas='".$_REQUEST['kelas']."'
								" ));
								
						$kapasitas = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT kapasitas FROM `t_jadwal` 
								where kode_prodi='$prodi' 
								and tahun_id='$tahun_id' 
								and id='".$_REQUEST['kode_mk']."'
								and kelas='".$_REQUEST['kelas']."'
								" ));
								

							
							if ($ada > 0){
								echo "<script>alert('Matakuliah ini sudah anda pilih !')</script>";
								
							}else if ($bentrok >0 ){
								echo   "<script>alert('Hari dan Jam Kuliah Kuliah Bentrok ! dengan (" ,$bentrokdet['kode_mk'],'--',$bentrokdet['nama_mk'],")')</script>";
							
							} else if ($jml_krs > $boleh) {
								echo "<script>alert('Batas Pengambilan SKS Anda hanya $boleh SKS, Karena IP Anda  $kumulatif_semua !')</script>";
								
							} else if ($adapra['id_mp'] != null && ($nilaipra['nilai']=='E' || $nilaipra['nilai']== null)) {
								echo "<script>alert('Anda Belum Mengambil atau Belum Lulus MK Prasyarat (" ,$namapra['kode_mk'],'--',$namapra['nama_mk'], ")')</script>"  ;
							}else if ($penuh == $kapasitas['kapasitas']) {
								echo "<script>alert('Kelas Penuh ! ')</script>";
							}else {	
							
							$so = "insert into t_mahasiswa_krs SET 
								kode_pt='".$mk['kode_pt']."',
								kode_fak='".$mk['kode_fak']."',
								kode_jenjang='".$mk['kode_jenjang']."',
								kode_prodi='".$mk['kode_prodi']."',
								tahun_id='".$tahun_id."',
								kelas='".$kelas."',								
								idm='".$wm['idm']."',
								id='".$kdmk."',
								sks='".$mk['sks_mk']."',
								hari='".$mk['hari']."'
								
								";
								
								$koneksi_db->sql_query($so);
							}
					}
				}
				
		
	}
Daftar();
}

$kelas = BuatSesi('kelas');
$kode_mk = BuatSesi('kode_mk');
$kolom_mahasiswa = BuatSesi('kolom_mahasiswa');
$kunci_mahasiswa = BuatSesi('kunci_mahasiswa');

if ($_REQUEST['reset_mahasiswa'] == 'Reset') {
  $_SESSION['kolom_mahasiswa'] = '';
  $_SESSION['kunci_mahasiswa'] = '';
}

$go = (empty($_REQUEST['op'])) ? 'Daftar' : $_REQUEST['op']; 

echo '<div id="title" align="right">
        <font style="color:#FF9900; font-size:30px;"><strong>.:</strong></font>
        <font style="font-size:18px; color:#999999">Input KRS Mahasiswa</font><br />
        	<a href="index.php">Home</a> &raquo; <a href="index.php?m='.$_GET['m'].'">KRS Mahasiswa</a>  &raquo; '.$go.'  
    </div>';
echo '<div class="mainContentCell"><div class="content">';

$go();
echo '</div></div>';

?>