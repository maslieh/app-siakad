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
	
	
$tahun_id = $_SESSION['thakad'];

$badanhukum = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM m_badan_hukum limit 1 " ));
$perguruantinggi = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM m_perguruan_tinggi limit 1 " ));
$programstudi = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM m_program_studi where kode_prodi='$prodi' limit 1 " ));

if (file_exists($DirAplikasi.'/dikti/'.$_GET['m'].'.php') && isset($_GET['m']) && !preg_match('/\.\./',$_GET['m'])) 
{
include $DirAplikasi.'/dikti/'.$_GET['m'].'.php';
} else {
echo "<div  class='alert alert-danger'>Modul tidak tersedia</div>";
}
?>