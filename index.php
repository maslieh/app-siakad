<?php
session_start();
#error_reporting(E_ALL);
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
if (file_exists($DirSystem."/kontrol.php")) include $DirSystem."/kontrol.php";	
if (file_exists($penampakan)) include $penampakan;			

?>
