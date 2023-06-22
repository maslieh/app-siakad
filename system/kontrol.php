<?php
Komponen('header');
global $koneksi_db, $prodi, $thakad, $tahun_id;
$prodi = BuatSesi('prodi');
 
$thakad = BuatSesi('thakad');
$angkatan = BuatSesi('angkatan');

$tahun_id = $_SESSION['thakad'];

$badanhukum = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM m_badan_hukum limit 1 " ));
$perguruantinggi = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM m_perguruan_tinggi limit 1 " ));
$programstudi = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM m_program_studi where kode_prodi='$prodi' limit 1 " ));

if (cek_login ()){
	$user = $_SESSION['UserID'];
	$t_bd = $koneksi_db->sql_numrows($koneksi_db->sql_query( "SELECT * FROM m_badan_hukum  limit 1 " ));
	$t_pt = $koneksi_db->sql_numrows($koneksi_db->sql_query( "SELECT * FROM m_perguruan_tinggi  limit 1 " ));
	$t_pf = $koneksi_db->sql_numrows($koneksi_db->sql_query( "SELECT * FROM m_fakultas  limit 1 " ));
	$t_pd = $koneksi_db->sql_numrows($koneksi_db->sql_query( "SELECT * FROM m_program_studi  limit 1 " ));
	$t_pk = $koneksi_db->sql_numrows($koneksi_db->sql_query( "SELECT * FROM m_konsentrasi  limit 1 " ));
	
  	if($_SESSION['Level']=="ADMIN"){ 
	$main = 'admin';
	//////////////////
		if ($t_bd < 1) { $tidakada .= '<div class="alert alert-danger">Anda belum memasukan data Badan Hukum, Klik <a href="?m=identitas&op=badanhukum">Tambah Badan Hukum</a></div>'; }
		if ($t_pt < 1) { $tidakada .= '<div class="alert alert-danger">Anda belum memasukan data Perguruan Tinggi, Klik <a href="?m=identitas&op=perguruantinggi">Tambah Perguruan Tinggi</a></div>'; 	}
		if ($t_pf < 1) { $tidakada .= '<div class="alert alert-danger">Anda belum memasukan data Fakultas, Klik <a href="?m=fakultas&op=edit_fak&md=1">Tambah Fakultas</a></div>'; }
		if ($t_pd < 1) { $tidakada .= '<div class="alert alert-danger">Anda belum memasukan data Program Studi, Klik <a href="?m=fakultas&op=edit_prodi&md=1">Tambah Program Studi</a></div>'; }
		if ($t_pk < 1) { $tidakada .= '<div class="alert alert-danger">Anda belum memasukan data Konsentrasi, Klik <a href="?m=fakultas&op=edit_kons&md=1">Tambah Konsentrasi</a></div>'; }
	///////////////////
	}
  	else if ($_SESSION['Level']=="DIREKTUR"){ 
	$main = 'direktur'; 
	//////////////////
		if ($t_bd < 1) { $tidakada .= '<div class="alert alert-danger">Data Badan Hukum Masih Kosong</div>'; }
		if ($t_pt < 1) { $tidakada .= '<div class="alert alert-danger">Data Perguruan Tinggi Masih Kosong</div>'; 	}
		if ($t_pf < 1) { $tidakada .= '<div class="alert alert-danger">Data Fakultas Masih Kosong</div>'; }
		if ($t_pd < 1) { $tidakada .= '<div class="alert alert-danger">Data Program Studi Masih Kosong</div>'; }
	///////////////////
	}
  	else if ($_SESSION['Level']=="ADAK" || $_SESSION['Level']=="ADMA"){ 
	$main = 'operator'; 
	//////////////////
		if ($t_bd < 1) { $tidakada .= '<div class="alert alert-danger">Data Badan Hukum Masih Kosong</div>'; }
		if ($t_pt < 1) { $tidakada .= '<div class="alert alert-danger">Data Perguruan Tinggi Masih Kosong</div>'; 	}
		if ($t_pf < 1) { $tidakada .= '<div class="alert alert-danger">Data Fakultas Masih Kosong</div>'; }
		if ($t_pd < 1) { $tidakada .= '<div class="alert alert-danger">Data Program Studi Masih Kosong</div>'; }
	///////////////////
	}
	else if ($_SESSION['Level']=="PUSTAKA"){ 
	$main = 'pustaka'; 
	//////////////////
		if ($t_bd < 1) { $tidakada .= '<div class="alert alert-danger">Data Badan Hukum Masih Kosong</div>'; }
		if ($t_pt < 1) { $tidakada .= '<div class="alert alert-danger">Data Perguruan Tinggi Masih Kosong</div>'; 	}
		if ($t_pf < 1) { $tidakada .= '<div class="alert alert-danger">Data Fakultas Masih Kosong</div>'; }
		if ($t_pd < 1) { $tidakada .= '<div class="alert alert-danger">Data Program Studi Masih Kosong</div>'; }
	///////////////////
	}		
  	else if ($_SESSION['Level']=="DOSEN"){ 
	$main = 'dosen'; 
	//////////////////
		if ($t_bd < 1) { $tidakada .= '<div class="alert alert-danger">Data Badan Hukum Masih Kosong</div>'; }
		if ($t_pt < 1) { $tidakada .= '<div class="alert alert-danger">Data Perguruan Tinggi Masih Kosong</div>'; 	}
		if ($t_pf < 1) { $tidakada .= '<div class="alert alert-danger">Data Fakultas Masih Kosong</div>'; }
		if ($t_pd < 1) { $tidakada .= '<div class="alert alert-danger">Data Program Studi Masih Kosong</div>'; }
	///////////////////
	}
	else if ($_SESSION['Level']=="WALI"){ 
	$main = 'wali'; 
	//////////////////
		if ($t_bd < 1) { $tidakada .= '<div class="alert alert-danger">Data Badan Hukum Masih Kosong</div>'; }
		if ($t_pt < 1) { $tidakada .= '<div class="alert alert-danger">Data Perguruan Tinggi Masih Kosong</div>'; 	}
		if ($t_pf < 1) { $tidakada .= '<div class="alert alert-danger">Data Fakultas Masih Kosong</div>'; }
		if ($t_pd < 1) { $tidakada .= '<div class="alert alert-danger">Data Program Studi Masih Kosong</div>'; }
	///////////////////
	}
	else if ($_SESSION['Level']=="MAHASISWA"){ 
	$main = 'mahasiswa'; 
	//////////////////
		if ($t_bd < 1) { $tidakada .= '<div class="alert alert-danger">Data Badan Hukum Masih Kosong</div>'; }
		if ($t_pt < 1) { $tidakada .= '<div class="alert alert-danger">Data Perguruan Tinggi Masih Kosong</div>'; 	}
		if ($t_pf < 1) { $tidakada .= '<div class="alert alert-danger">Data Fakultas Masih Kosong</div>'; }
		if ($t_pd < 1) { $tidakada .= '<div class="alert alert-danger">Data Program Studi Masih Kosong</div>'; }
	///////////////////
	}
	////////////////////
	$pecah = substr($user, 0,1);
	if ($_SESSION['Level']=="ADMIN") {
	$row = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM user where userid ='$user' limit 1 " ));
	$nama = $row['nama'];
	} else if ($pecah =="D") {
	$row = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM m_dosen where idd ='$user' limit 1 " ));
	$nama = $row['nama_dosen'];
	$prodi = $row['kode_prodi'];
	} else if ($pecah =="M") {
	$row = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM m_mahasiswa where idm ='$user' limit 1 " ));
	$nama = $row['nama_mahasiswa'];
	$Mkelas = $row['masuk_kelas'];
	$Msemester = $row['semester'];
	$Mstatus = $row['status_aktif'];
	}
	
	$avatar = ($row['foto'] =="" ) ? "images/no_avatar.gif": "images/avatar/".$row['foto']."";

	 
	
	///////////////// main modul
	$AKSI = !isset($_GET['p']) ? 'index' : $_GET['p'];

	if ( isset($_GET['m']) && !empty($_GET['m']) && !preg_match('/\.\./',$_GET['m']  )) {

			$ambil = "SELECT modul_sub.*, modul_kepala.* FROM modul_sub
			left join modul_kepala on modul_sub.id_kepala=modul_kepala.id_kepala
			where modul_sub.id_sub=".$_GET['m']." and modul_sub.aktif ='Y' limit 1 ";
			#echo $ambil;
			$qambil = $koneksi_db->sql_query( $ambil );
	 
			if ($koneksi_db->sql_numrows($qambil) > 0) {
			// modul ada
				$rowmodul = $koneksi_db->sql_fetchassoc($qambil);
				if (strpos($rowmodul['level_sub'], $_SESSION['Level'])) { 
				// akses diperbolehkan
						if ($rowmodul['cabang']!='0') {
							$rowcabang = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM modul_sub where id_sub ='".$rowmodul['cabang']."' limit 1 " ));
							$modulnya = $DirAplikasi."/".$rowmodul['skrip_kepala']."/".$rowcabang['skrip_sub']."/".$rowmodul['skrip_sub'].".php";
						} else {
							$modulnya = $DirAplikasi."/".$rowmodul['skrip_kepala']."/".$rowmodul['skrip_sub'].".php";
						}
						if (file_exists($modulnya)) {
						
							include $modulnya;
						} else {
							echo "<div  class='alert alert-danger'>Modul <b>".$rowmodul['nama_sub']."</b> tidak ada </div> ";
 						}
					//echo $modulnya;
				//$aplikasiDir = $DirAplikasi."/".$pmodul['skrip_kepala']."/".$pmodul['skrip_sub'];
				} else {
				// tidak ada akses
				echo "<div  class='alert alert-danger'>Anda tidak diperkenankan mengakses modul ini <b>(".$rowmodul['nama_sub'].")</b></div>";
				 
 				}
			} else {
			// modul tidak ada
			echo "<div  class='alert alert-danger'>Modul tidak tersedia</div>";
			 
 			}
	
	} else {
	include $DirAplikasi."/home/".$AKSI.".php";
	}	
	
	
} else {
	$P = !isset($_GET['p']) ? 'index' : $_GET['p'];
 	if ( isset($_GET['m']) && !empty($_GET['m']) && !preg_match('/\.\./',$_GET['m']  )) 
	{
	$moduldepan = $DirAplikasi.'/front/'.$_GET['m'].'.php';
	} else {
	$moduldepan = $DirAplikasi.'/front/home.php';
	}
	
	if (file_exists($moduldepan)) {
		include $moduldepan;
	} else {
		//echo "<div  class='alert alert-danger'>Modul  tidak ada '$moduldepan</div>";
		echo "<meta http-equiv='refresh' content='1; url=index.php'>";
		//include $DirAplikasi."/modul/home.php";
	}
}
 
 
Komponen('footer');
 
function Komponen($komponen) {
	$MAIN = (cek_login ()) ? 'admin' : 'front';
	include 'template/'.$MAIN.'/'.$komponen.'.php';
}

function Modul($modul) {
global $DirAplikasi;
	include $DirAplikasi.'/modul/'.$modul.'.php';
}

?>