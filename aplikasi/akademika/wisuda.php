<?php
if(ereg(basename (__FILE__), $_SERVER['PHP_SELF']))
{
	header("HTTP/1.1 404 Not Found");
	exit;
}
if (!login_check()) {
		//alihkan user ke halaman logout
		logout ();
		session_destroy();
		//echo '<div class="message"><div class="success"><h3>Logout SUKSES</h3></div></div>';
		echo '<meta http-equiv="refresh" content="0; url=index.php" />';
		//exit(0);
}
if (!cek_login ()){
header ("location:index.php");
exit;
}

$sub = $_GET['m'];
$submenu = $koneksi_db->sql_query( "SELECT * FROM modul_sub WHERE aktif='Y' AND cabang='$sub' ORDER BY urut" );
if ( $koneksi_db->sql_numrows( $submenu ) >0) {

	while ($wmenu = $koneksi_db->sql_fetchrow($submenu)) {
	if (strpos($wmenu['level_sub'], $_SESSION['Level'])) {
	
	
		echo '<fieldset class="" >
            <legend class="">
				<a href="index.php?m='.$wmenu[0].'" title="'.$wmenu[3].'" >&nbsp; '.$wmenu[3].' &nbsp;</a>
			</legend>
		</fieldset>';
	
	}
	}
}
?>
