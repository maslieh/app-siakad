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
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head profile="http://gmpg.org/xfn/11">
<head>
<TITLE><?php echo $judul_situs;?> :: </TITLE>
<META name=description content="<?php echo $DESCRIPTION;?>">
<META content="text/html; charset=utf-8" http-equiv=Content-Type>
<META name=revisit-after content="2 days">
<META name=robots content=all,index,follow>
<META name=MSSmartTagsPreventParsing content=TRUE>
<META content=en-us http-equiv=Content-Language>

<META name=Distribution content=Global>
<META name=Rating content=General>
<LINK rel="Shortcut Icon" type=image/x-icon href="images/favicon.ico">
<link href="<?php echo $DirTemplate;?>/tables.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $DirTemplate;?>/forms.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $DirTemplate;?>/cetak.css" rel="stylesheet" type="text/css" />
<LINK rel=image_src href="images/web-logo.gif">
<center>

<?php 

$tahun_id = $_SESSION['thakad'];

$badanhukum = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM m_badan_hukum limit 1 " ));
$perguruantinggi = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM m_perguruan_tinggi limit 1 " ));
$programstudi = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM m_program_studi where kode_prodi='$prodi' limit 1 " ));

	if (file_exists($DirAplikasi.'/print/'.$_GET['m'].'.php') && isset($_GET['m']) && !preg_match('/\.\./',$_GET['m'])) 
	{
	include $DirAplikasi.'/print/'.$_GET['m'].'.php';
	} else {
	include $DirAplikasi.'/print/error.php';
	}
	

?>
</center>
</BODY>
</HTML>

<?php

if ($_GET['m']){
echo "<script language=javascript>
function printWindow() {
bV = parseInt(navigator.appVersion);
if (bV >= 4) window.print();}
printWindow();
</script>";
}												 

?>

