<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php global   $judul_situs;?> 
	<meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $judul_situs;?></title>
	<meta name="Description" content="" />
	<meta name="Keywords" content="" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="Distribution" content="Global" />
	<meta name="Author" content="Hari Pratomo, admin@klatenweb.com" />
	<meta name="Robots" content="index,follow" />
	
	<meta name="viewport" content="width=device-width, initial-scale=1.0">


	<!-- BOOTSTRAP STYLES-->
	<link href="assets/css/bootstrap2x2x.css" rel="stylesheet" />
	<!-- FONTAWESOME STYLES-->
	<link href="assets/css/font-awesome.css" rel="stylesheet" />
	<!-- CUSTOM STYLES-->
	<link href="assets/css/custom.css" rel="stylesheet" />
	<!-- GOOGLE FONTS-->

	<link href="assets/js/dataTables/dataTables.bootstrap.css" rel="stylesheet">
	
	<link rel="stylesheet" href="assets/css/bootstrap-datepicker.css">
	
 	<link rel="stylesheet" href="style/forms.css">
	<!-- JQUERY SCRIPTS -->

		<link href="https://fonts.googleapis.com/css?family=Questrial&display=swap" rel="stylesheet">
<style type="text/css">
body {
	font-family: 'Questrial', sans-serif;font-size:14px;word-break:normal;
}
td {
	padding-right:6px;
}



	#divLoading {
		width:50px;
		height:50px;
		position: absolute;
		top: 50%;
		left: 50%;
		margin-top:-25px;
		margin-left:-25px;
		z-index:9999999;
		}
</style>
</head>
<body >
    <div id="wrapper">
        <nav class="navbar navbar-default navbar-cls-top " role="navigation" style="background:#3a77ab;" >
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <span class="navbar-brand">SIAKADss</span> 
				
				
            </div>
			
  			<div style="color: white;padding: 15px 50px 5px 50px;float: right;font-size: 16px;height:60px;background:#3a77ab;"> 
				<?php Modul('prodi_aktif'); ?>	




			</div>
        </nav> 
           <!-- /. NAV TOP  -->
        <nav class="navbar-default navbar-side" role="navigation">
		<center>
		
<?php
  global $koneksi_db, $user;
 $hasil2m = $koneksi_db->sql_query( "SELECT * FROM `user` WHERE `userid` = '$user'" );
while ($pm = $koneksi_db->sql_fetchrow($hasil2m)) {
	$userm = $pm['username'];
	$namam = $pm['nama'];
	$fotom = $pm['foto'];
}

if(!$fotom)
{
	echo '<br/><img src="images/logo-user.png" style="
border-radius: 150px;padding:6px;
-webkit-border-radius: 150px;
-moz-border-radius: 150px;
background: url(URL) no-repeat;
box-shadow: 0 0 8px rgba(0, 0, 0, .8);
-webkit-box-shadow: 0 0 8px rgba(0, 0, 0, .8);
-moz-box-shadow: 0 0 8px rgba(0, 0, 0, .8);width:80px;float:left;margin-left:20px;"><br/>
		<div style="color: white;margin-top:-10px;margin-bottom:10px;">'.$namam.'<br/><a href="index.php?m=36">Rubah Password </a><br/><a href="index.php?m=37">Logout </a><br/> <br/></div>';
} else {
	
	echo '<br/><img   src="images/avatar/'.$fotom.'" style="
border-radius: 150px;
-webkit-border-radius: 150px;
-moz-border-radius: 150px;
background: url(URL) no-repeat;
box-shadow: 0 0 8px rgba(0, 0, 0, .8);
-webkit-box-shadow: 0 0 8px rgba(0, 0, 0, .8);
-moz-box-shadow: 0 0 8px rgba(0, 0, 0, .8);width:80px;float:left;margin-left:20px;"><br/>
		<div style="color: white;margin-top:-10px;margin-bottom:10px;">'.$namam.'<br/><a href="index.php?m=36">Rubah Password </a><br/><a href="index.php?m=37">Logout </a><br/> <br/></div>';
}


?></center><div class="sidebar-collapse"><?php Komponen('menu'); ?></div></nav><div id="page-wrapper"><div id="page-inner">