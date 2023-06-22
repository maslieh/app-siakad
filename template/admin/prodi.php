<SCRIPT type=text/javascript>
///////////// pilih kelas dan yang sukses ////////////////////	
function submitPilihKelas(v,m,f){
        if (v == true)
            window.location="index.php?m="+f.m+'&prodi='+f.kelas;
        else
            return true;
}
    
function pilihKelas(action){
var pesan = 'Pilih Program Studi : <br /> <input type="hidden" id="m" name="m" value="'+action+'"><select name="kelas" id="kelas"><?php echo''.opprodi(''.$_SESSION['prodi'].'').'';?></select>';
	 $.prompt(pesan,{
	   callback: submitPilihKelas,
			buttons: {
				Ok: true,
				Batal : false
			}
	  });
}
</SCRIPT>

<?php

if (cek_login ()){

	if ($_SESSION['Level']=="ADMIN" || $_SESSION['Level']=="PERPUSTAKAAN" || $_SESSION['Level']=="ADAK" || $_SESSION['Level']=="ADMA" || $_SESSION['Level']=="DOSEN" || $_SESSION['Level']=="WALI" || $_SESSION['Level']=="INVENTORI" || $_SESSION['Level']=="KEUANGAN"	) {
		$m = (empty($_REQUEST['m'])) ? '' : $_REQUEST['m'];
		$prodi = BuatSesi('prodi');
		$prodi = $_SESSION['prodi'];
		if (empty($prodi) || !isset($prodi)) { 	
			$tombolx .= '<div class=error>Anda belum memilih Program Studi, Klik <a href="#" onclick="pilihKelas(\''.$m.'\')">UBAH</a> untuk mengaktifkan</div>'; 
		} else {
			$tombolx = ' | <a href="#" onclick="pilihKelas(\''.$m.'\')">UBAH</a> ';
		}
	} else ($_SESSION['Level']=="MAHASISWA" ) {
		$prodi = $row['kode_prodi'];
		$_SESSION['prodi']= $prodi;
		
	} 
	$qp = "SELECT * FROM m_program_studi where kode_prodi='$prodi' limit 1 ";
	
	$pr = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( $qp ));
	$namaprodi = strtolower($pr['nama_prodi']);
	$jenjangprodi = $pr['kode_jenjang'];
}

echo '<div class="topseller">
		<h2 >'.ucwords(NamaTahun($tahun_id,$pr['kode_prodi'])).' | Program Studi '.ucwords($namaprodi).' '.$tombolx.' </h2>
	  </div>';
?>