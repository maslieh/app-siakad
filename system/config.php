<?php

//konfigurasi db
error_reporting(0);
$mysql_user = 'root';
$mysql_password = '';
$mysql_database = 'app_siakadv1';
$mysql_host = 'localhost';

/*---------------------------------------------------------------------------------
 konfigurasi situs 
---------------------------------------------------------------------------------*/

$judul_situs = 'Sistem Informasi Akademik SSR';
$url_situs = 'https://' . $_SERVER['SERVER_NAME'] . '';
$slogan = 'Sistem Informasi Akademik SSR';
$DESCRIPTION = 'Sistem Informasi Akademik SSR';

$DirAplikasi = 'aplikasi';
$DirSystem = 'system';
$DirTemplate = 'style';

$text_head = 'Sistem Informasi Akademik SSR';

$arrBulan = [
    'Januari->01',
    'Pembruai->02',
    'Maret->03',
    'April->04',
    'Mei->05',
    'Juni->06',
    'Juli->07',
    'Agustus->08',
    'September->09',
    'Oktober->10',
    'Nopember->11',
    'Desember->12',
];

$arrtipefile = ['pdf', 'zip', 'swf', 'exe', 'doc', 'img', 'mpg'];

if (substr(phpversion(), 0, 3) >= 5.1) {
    date_default_timezone_set('Asia/Jakarta');
}

$development = "<a href='' target=_blank >Sistem Informasi Akademik SSR</a>";
$versidemo = '0'; // 1= Versi Demo . 0 = Versi Pro

?>
