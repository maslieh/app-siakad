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
include "system/inc/config.php";
require_once "system/inc/fungsi.php";

?>
	<div id="title" align="right">
        <font style="color:#FF9900; font-size:30px;"><strong>.:</strong></font>
        <font style="font-size:18px; color:#999999">Ubah Password</font><br />
        	<a href="index.php">Home</a> &raquo; <a href="index.php?m=<?=$_GET['m'];?>">Ubah Password</a>  &raquo; Form 
    </div>

	<div class="mainContentCell">
		<div class="content">
<!-----------------BATAS UTAMA-------------------------->	
<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-header">
					  <h3 class="box-title">Config Akun Feeder</h3>
					</div><!-- /.box-header -->
					<div class="box-body table-responsive">
					<form action="" method="post"  class="cmxform" id="form_input" style="width:100%">
					<table class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>Username Feeder</th>
								<th>URL Feeder</th>
								<th>PORT</th>
								<th>Kode PT</th>
								<th>Status</th>
							<th>Action</th>
							</tr>
						</thead>
					<tbody>
					<?php 
						  $isi=$db->fetch_custom_single("select config_user.username,config_user.password,config_user.url,config_user.port,config_user.kode_pt,config_user.live,config_user.id from config_user ");
						  //dump($isi);
						  $i=1;
						  $token = check_token();
						 //dump($token);

							if ($token['status']=='1') {
								$status = '<button type="input" class="btn btn-success btn-xs">Connected</button>';
							} else {
							  $status = '<button type="input" class="btn btn-danger btn-xs">'.$token['error'].'error</button>';
							}
							?>
							<tr id="line_<?=$isi->id;?>">
							<td><?=dec_data($isi->username);?></td>
							<td><?=$isi->url;?></td>
							<td><?=$isi->port;?></td>
							<td><?=$isi->kode_pt;?></td>
							<td> <?=$status;?> </td>
							<td>
						   <button class="button button-gray" type="submit" name="submit"><span class="accept"></span>UPDATE</button>
							</td>
							</tr>
							<?php
							$i++;
						?>
					</tbody>
					</table>
					</form>
					</div><!-- /.box-body -->
				</div><!-- /.box -->
			</div>
		</div>
	</section><!-- /.content -->

<!-----------------BATAS UTAMA-------------------------->				
	</div>
</div>