<?php 
session_start();
error_reporting(0);
@header("Content-type: text/html; charset=utf-8;");
define('VALID_CONTENT', true);

if (!file_exists("system/config.php")) {
header("Location: install.php");
exit;
} else {
include "system/config.php";
}

if (file_exists($DirSystem."/mysqli.php")) include $DirSystem."/mysqli.php";		  
if (file_exists($DirSystem."/fungsi.php")) include $DirSystem."/fungsi.php";
if (file_exists($DirSystem."/fungsi_kode.php")) include $DirSystem."/fungsi_kode.php";
if (file_exists($DirSystem."/fungsi_filter.php")) include $DirSystem."/fungsi_filter.php";
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
	echo "<div  class='alert alert-danger'>Modul tidak tersedia</div>";
}	

?>