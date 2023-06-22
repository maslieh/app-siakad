<?php


session_start();
$idm = $_SESSION['idm'];
$query  = $koneksi_db->sql_query ("SELECT nim, nama_mahasiswa, max(tahun_id) as tahun, a.kode_prodi FROM m_mahasiswa a inner join t_mahasiswa_krs b on b.idm=a.idm where a.idm='$idm'");

if( $r = $koneksi_db->sql_fetchassoc ($query)) {
$nim = $r['nim'];
$nama_mahasiswa = $r['nama_mahasiswa'];
$kode_prodi = $r['kode_prodi'];
}
$sekarang = date('Y');

$tahun_id = $r['tahun'];


?>



<div id="title" align="center">
	<font style="color:#FF9900; font-size:30px;"><strong>.:</strong></font>
	<font style="font-size:20px; color:#999999">Isian Angket Mahasiswa</font>
	<font style="color:#FF9900; font-size:30px;"><strong>:.</strong></font>&nbsp;&nbsp;&nbsp;<br />
	<font style="font-size:16px; color:#999999">Silahkan Isi Angket Akademik dan Dosen Sebelum Anda Melanjutkan ke SIAKAD</font>&nbsp;&nbsp;&nbsp;<br />
	<font style="font-size:16px; color:#999999">Semua Butir Harus diisi untuk dapat melanjutkan ke SIAKAD</font>&nbsp;&nbsp;&nbsp;<br /><br />
</div>	


		
<div class="mainContentCell">
	<div class="content" >

<?php
if (isset($_POST['Simpan'])){
	
	
	
		foreach($_POST['idangketakad'] as $key=>$val) {
		$j = $_POST['jawaban'][$key];
		$jj= $j;
		
		if (trim($jj)==""){
		$pesan = "Masih Ada Butir Angket Akademik Belum Diisi, Ulangi Kembali";
			
		}	
	}
	

	
	
		foreach($_POST['idangketdos'] as $key2=>$val2) {
		$j2 = $_POST['jawaban3'][$key2];
		$jj2= $j2;
		
		
		if (trim($jj2)==""){
		$pesan2 = "Masih Ada Butir Angket Dosen Belum Diisi, Ulangi Kembali";
			
		}	
	}
	
	//if ( $pesan != "" or $pesan2 != "" or $pesan3 != "" ) {
	if ( $pesan2 != "" ) {	
		echo "<div align='left'>";			
		
			echo "<h2><font color='#FF0000' align='left'>";
			echo "$pesan <br> $pesan3<br> $pesan2  ";
			echo "</font></h2>";
		
		}else{
		    
		    $kode_prodi = $r['kode_prodi'];
		    $sp ="insert into t_angket_akademik(kode_prodi, tahun_id, idangakad, nilai) VALUES ";
		    $sp2 = "insert into t_angket_dosen (kode_prodi, tahun_id, kelas, id, idd, idangdos, nilai) VALUES ";
		    foreach($_POST['idangketakad'] as $key3=>$val3) {
			$j3 = $_POST['jawaban'][$key3];
			$idp3 = $val3;

		    $sp .= "('".$kode_prodi."','".$tahun_id."','".$idp3."','".$j3."'), ";
					
		    }
		    $sp = rtrim($sp, ', ');
            $koneksi_db->sql_query($sp);
		    
		    //echo $sp;
		    
		    foreach($_POST['idangketdos'] as $key4=>$val4) {
			$j4 = $_POST['jawaban3'][$key4];
			$idp4 = $_POST['idangketdos2'][$val4];
			$id1 = $_POST['id'][$val4];
			$idd1 = $_POST['idd'][$val4];
			$kelas = $_POST['kelas'][$val4];

			$sp2 .= "('".$kode_prodi."', '".$tahun_id."', '".$kelas."', '".$id1."', '".$idd1."', '".$idp4."', '".$j4."'), ";

			}
			$sp2 = rtrim($sp2, ', ');
            $koneksi_db->sql_query($sp2);
		    
			//echo $sp2;
			
			
			$xx = "update user SET 
						angket ='Y'
						where userid = '".$idm."'
					";
				$koneksi_db->sql_query($xx);	
		    echo "<meta http-equiv='refresh' content='2; url=index.php'>";	

			
	}
	
}
?>
	
<form action="" method="post"  class="" id="form_input" style="width:100%">	
		<input type="hidden" name="m" value="pmb.go"/>
		<input type="hidden" name="op" value="Simpan"/>	
<?php 
	 
	
	
		echo
		'<div id="title" align="center">
		<font style="font-size:20px; color:#999999">Selamat Datang '.$nama_mahasiswa.' </br>
		(Silahkan mengisi angket terlebih dahulu, Kerahasiaan dan privasi anda dalam mengisi angket dijamin</font>
		</div>';
	
?>
			
			
			
				<div class="mainContentCell" style="background:#FFFFFF" >
					<div class="content" >
		
			</br>
			<div id="title" align="center">
            	<font style="color:#FF9900; font-size:30px;"><strong>.:</strong></font>
            	<font style="font-size:20px; color:#999999">ANGKET AKADEMIK</font>
            	<font style="color:#FF9900; font-size:30px;"><strong>:.</strong></font><br />
            	
        		<font style="font-size:20px; color:#999999">
        		(Silahkan mengisi sesuai dengan tingkat kepuasan yang berada pada kolom sebelah kanan)</br></br></font>
        		
            </div>	
			
			<?php
			echo '<table width="1000" border="0" align="center" cellpadding="3" cellspacing="2" class="rapor">
				<thead>
				 <tr>
				   <th rowspan="2" width="5%" align="center">No</th>
				   <th rowspan="2" width="45%" align="center">Pertanyaan</th>
				   <th colspan="4" width="50%" align="center">Tingkat Kepuasan</th>
				 </tr>
				 <tr>
					<th align="center" width="30">Sangat KurangBaik</th>
					<th align="center" width="30">Kurang Baik</th>
					<th align="center" width="30">Baik</th>
					<th align="center" width="30">Sangat Baik</th>
				 </tr>
				 </thead>';
				$q = "select  *  from m_angket_akademik "  ;
				$pilih = $koneksi_db->sql_query($q);
										
					$n=0;
					while($wm = $koneksi_db->sql_fetchassoc($pilih)){
					$n++;
					$idp=$wm['idangakad'];
							echo '<tr bgcolor="#f2f2f2">
									<td  align=center>'.$n.'<input type="hidden"  name="idangketakad['.$idp.']" value="'.$idp.'"/></td>
									<td  align=left>'.$wm['pertanyaan'].'</td>
										<td  align=center><input type="radio" '.$ck.'  class="required" name="jawaban['.$idp.']"  value="1"></td>
										<td  align=center><input type="radio" '.$ck.'  class="required" name="jawaban['.$idp.']"  value="2"></td>
										<td  align=center><input type="radio" '.$ck.'  class="required" name="jawaban['.$idp.']"  value="3"></td>
										<td  align=center><input type="radio" '.$ck.'  class="required" name="jawaban['.$idp.']"  value="4"></td>
						</tr>'; 
					}
					echo '</table>';
			?>
			
			</div>
				</div>
			
			
					
			
			

			    </br>
			<div id="title" align="center">
            	<font style="color:#FF9900; font-size:30px;"><strong>.:</strong></font>
            	<font style="font-size:20px; color:#999999">ANGKET PENGAJARAN</font>
            	<font style="color:#FF9900; font-size:30px;"><strong>:.</strong></font><br />
        		<font style="font-size:20px; color:#999999">
        		(Silahkan mengisi rentang 1 s.d. 5 pada kolom sebelah kanan, semakin besar nilai semakin besar tingkat kepuasan)</br></br></font>

            </div>
				<div class="mainContentCell" style="background:#FFFFFF">
					<div class="content">
					<?php
					$q1 = "select  a.id, kode_mk, nama_mk, idd, nama_dosen, a.kelas  from t_mahasiswa_krs a 
					        inner join view_jadwal b on b.id=a.id  
					        where  a.idm='$idm' and a.tahun_id='$tahun_id' and b.tahun_id='$tahun_id' group by a.id order by nama_mk"  ;
					    
					$pilih1 = $koneksi_db->sql_query($q1);
					$i=0;							
					while($wm1 = $koneksi_db->sql_fetchassoc($pilih1)){
					$i++;	
					$id = $wm1['id'];
					$idd = $wm1['idd'];					
					echo '
					    <div id="title" align="center">
						<div class="pelajaran-top" ibu="'.$i.'">
						<font style="font-size:20px; color:#999999">
						'.$i.'.   Mata Kuliah = '.$wm1['nama_mk'].'  |  Dosen =  '.$wm1['nama_dosen'].'  |  Kelas =  '.$wm1['kelas'].'
				        </font>
						</div>
						</div>
						
					
						<table width="800" border="0" align="center" cellpadding="3" cellspacing="2" class="rapor">
						
						 <tr>
						   <th rowspan="2" width="5%" align="center">No</th>
						   <th rowspan="2" width="45%" align="center">Pertanyaan</th>
						   <th colspan="5" width="50%" align="center">Skala</th>
						 </tr>

						 <tr>
							<th align="center" width="20">1</th>
							<th align="center" width="20">2</th>
							<th align="center" width="20">3</th>
							<th align="center" width="20">4</th>
							<th align="center" width="20">5</th>
							
						 </tr>
						 ';
						$q2 = "select  *  from m_angket_dosen "  ;
						$pilih2 = $koneksi_db->sql_query($q2);
												
							$n=0;
							while($wm3 = $koneksi_db->sql_fetchassoc($pilih2)){
							$n++;
							$idp2=$wm3['idangdos'].$id;
							$idp21=$wm3['idangdos'];
							echo '<tr bgcolor="#f2f2f2">
									<td  align=center>'.$n.'<input type="hidden" name="idangketdos['.$idp2.']" value="'.$idp2.'"/></td>		
									<td  align=left>'.$wm3['pertanyaan'].' <input type="hidden" name="idangketdos2['.$idp2.']" value="'.$idp21.'"/>
									<input type="hidden" name="id['.$idp2.']" value="'.$id.'"/>
									<input type="hidden" name="idd['.$idp2.']" value="'.$idd.'"/>
									<input type="hidden" name="kelas['.$idp2.']" value="'.$kelas.'"/>
									</td>
										<td  align=center><input type="radio" '.$ck.'  class="required" name="jawaban3['.$idp2.']"  value="1"></td>
										<td  align=center><input type="radio" '.$ck.'  class="required" name="jawaban3['.$idp2.']"  value="2"></td>
										<td  align=center><input type="radio" '.$ck.'  class="required" name="jawaban3['.$idp2.']"  value="3"></td>
										<td  align=center><input type="radio" '.$ck.'  class="required" name="jawaban3['.$idp2.']"  value="4"></td>
										<td  align=center><input type="radio" '.$ck.'  class="required" name="jawaban3['.$idp2.']"  value="5"></td>
										
								</tr>'; 
							}
							echo '</table>
							<div style="clear: both"></div>
							<br/>';
					
						}
					?>
					
					
					</div>
				</div>
			</div>
	
			<div id="title" align="center">			
			 <input  type="submit" name="Simpan" class="tombols ui-corner-all" value="SIMPAN"/> 
		    </div>
		    
		</form>
		</div>
		
		</div>
	</div>
