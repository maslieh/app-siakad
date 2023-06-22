<?php

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


?>
	<div id="title" align="right">
        <font style="color:#FF9900; font-size:30px;"><strong>.:</strong></font>
        <font style="font-size:18px; color:#999999">Ubah Password</font><br />
        	<a href="index.php">Home</a> &raquo; <a href="index.php?m=<?=$_GET['m'];?>">Ubah Password</a>  &raquo; Form 
    </div>

	<div class="mainContentCell">
		<div class="content">
<!-----------------BATAS UTAMA-------------------------->	
<?php




if (isset($_POST['submit']) ){
if (cekVersi()){
$password0 = $_POST['password0'];
$password1 = $_POST['password1'];
$password2 = $_POST['password2'];
$passwordx = md5($_POST['password1']);
$user = $_SESSION['UserID'];
$usernem=$_SESSION['UserName'];


	$w = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM user where userid='$user' limit 1 " ));

	if ( md5($password0 )!= $w['password'] ) $error .= "Password Lama Salah<br/>";
	if ( empty($password1) || empty($password2) ) $error .= "Form Password Masih Kosong<br/>";
	if ( $password1 != $password2 ) $error .= "Password Baru tidak sama<br/>";
	
	if ($error){
		echo '<div class="error">'.$error.'</div>';
	} else {
	$hasil = $koneksi_db->sql_query( "UPDATE user SET password='$passwordx' WHERE userid='$user'" );
	
	
    
	echo '<div class="sukses"><b>Password Berhasil diganti</b><br />
	Silahkan <a href="index.php?m=37" target="_top">Logout</a> kemudian Login kembali!</div>';
	}
	

}
}
?>		
		<form name="form_input" id="form_input"  method="post" action="" >
			<table>
			<tr><td  valign="top" >Masukkan Password Lama</td><td><input type="password" class="required" id="password" name="password0" size="15"></td></tr>
			<tr><td  valign="top" >Masukkan Password Baru</td><td><input type="password" class="required" id="password" name="password1" size="15"></td></tr>
			<tr><td  valign="top" >Ulangi Password Baru</td><td><input type="password" class="required" id="password" name="password2" size="15"></td></tr>
						<tr><td  valign="top" ></td><td><button class="button button-gray" type="submit" name="submit"><span class="accept"></span>UPDATE</button></td></tr>
		
			</table>
		
		</form>
<!-----------------BATAS UTAMA-------------------------->				
	</div>
</div>