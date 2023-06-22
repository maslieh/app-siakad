<?php
session_start();
#error_reporting(E_ALL);
 
#require('../system/config.php');
include("../system/mysqli.php");		
 

$kode_mk = (isset($_GET['kode_mk'])) ? $_GET['kode_mk'] : '' ;
$thatha=$_SESSION['thakad'];

$kelas = $koneksi_db->sql_query("SELECT * FROM view_jadwal where id='$kode_mk' and tahun_id='".$thatha."' GROUP BY kelas");
echo "<option></option>";
while($k = $koneksi_db->sql_fetchassoc($kelas)){
       echo '<option value="'.$k['kelas'].'">'.$k['kelas'].'</option>';
}
?>
