<?php

if (!login_check()) {
		//alihkan user ke halaman logout
		logout ();
		session_destroy();
		//echo '<div class="message"><div class="success"><h3>Logout SUKSES</h3></div></div>';
		echo '<meta http-equiv="refresh" content="0; url=index.php" />';
		//exit(0);
}

global $development, $user;
?>
<div id="title" align="right">
        <font style="color:#FF9900; font-size:30px;"><strong>.:</strong></font>
        <font style="font-size:18px; color:#999999">Selamat Datang <?php echo ''.ucfirst($_SESSION['UserName']).'';?></font><br />
		<a href="index.php">Home</a> &raquo; <?php echo ''.ucfirst($_SESSION['UserName']).'';?>
</div>
		
<div class="clear"></div>

<div class="mainContentCell">

	<div class="content">
<!-----------------BATAS UTAMA-------------------------->

		<div class="newsItem">
			<fieldset class=\"ui-widget ui-widget-content ui-corner-all\" >
            <legend><input type="button"  class="tombols ui-corner-all" value='Sistem Informasi Akademik STEIN' onclick=\"#" ></legend>
			
			<?php $level = $koneksi_db->sql_fetchrow($koneksi_db->sql_query( "SELECT level FROM user where userid='$user' " ));
			
			?>
			<a href="./files/panduan/pedoman_penggunaan_krs_online_ganjil_2021-2022.pdf" type="button"  class="tombols ui-corner-all" target=_blank style="color: #FF0000; text-decoration: blink;" > KLIK UNTUK MELIHAT PANDUAN PENGISIAN KRS SEM. GANJIL Tahun Akademik 2021/2022  </a>
			
			
			?>
		
			
            &nbsp;<font color=\"red\"></font>
			  <p>
					Selamat Datang di Sistem Informasi Akademik (SIAKAD) <?php echo $perguruantinggi['nama_pt'];?>. 
					Sistem ini masih dalam taraf pengembangan, olehnya itu saran dan kritikan sangat diperlukan untuk perbaikan dimasa yang akan datang. 
					Semoga dengan kehadiran sistem ini menjadikan <?php echo $perguruantinggi['nama_pt'];?> akuntabel dalam pengelolaan akademik mahasiswa. 
					<br />
			<div class="title">Mengganti Password</div>
						Kepada seluruh user agar secara berkala mengganti Password  demi keamanan <br />
			<div class="title">Logout</div>
					Demi keamanan data di SIAKAD <?php echo $perguruantinggi['nama_pt'];?>, jangan lupa Logout sebelum meninggalkan komputer yang anda gunakan</p>
					<p>Terima Kasih<br/></p>
					
					<br/><br/>
					Divisi IT - AKPINDO
		</div>	
		
		<div class="clear"></div>			
<!-----------------BATAS UTAMA-------------------------->				
	</div>
</div>
