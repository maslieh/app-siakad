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
        <font style="font-size:18px; color:#999999">PDDIKTI</font><br />
        	<a href="index.php">PDDIKTI</a> &raquo; <a href="index.php?m=<?=$_GET['m'];?>">Config User</a>  &raquo; Form 
    </div>

	<div class="mainContentCell">
		<div class="content">
<!-----------------BATAS UTAMA-------------------------->	
    <section class="content">
<div class="row">
    <div class="col-lg-12">
        <div class="box box-solid box-primary">
                                   <div class="box-header">
                                    <h3 class="box-title">Edit Config Akun Feeder</h3>
                                    
                                </div>

                  <div class="box-body">
<div class="alert alert-info">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <strong>Untuk menghubungkan IMPORTER dengan NEO FEEDER, Silakan Masukan username, password Admin PT NEO FEEDER dibawah ini. Isi IP/Domain jika NEO FEEDER beda lokasi server dengan importer, biarkan url jika satu server/komputer</strong>
        </div>

                                     <div class="alert alert-danger pass_salah" style="display:none">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <strong><span class="isi_config_error"></span> </strong>
        </div>
                     <form id="update_config" method="post" class="form-horizontal" action="<?=base_admin();?>modul/config_akun_feeder/config_akun_feeder_action.php?act=up">
                      <div class="form-group">
                        <label for="Username Feeder" class="control-label col-lg-2">Username Feeder Dikti</label>
                        <div class="col-lg-10">
                          <input type="text" name="username" value="<?=dec_data($data_edit->username);?>" class="form-control" required> 
                        </div>
                      </div><!-- /.form-group -->
<div class="form-group">
                        <label for="Password Feeder" class="control-label col-lg-2">Password Feeder Dikti</label>
                        <div class="col-lg-10">
                          <input type="password" name="password" value="<?=dec_data($data_edit->password);?>" class="form-control" required> 
                        </div>
                      </div><!-- /.form-group -->
<div class="form-group">
                        <label for="Password Feeder" class="control-label col-lg-2">PORT</label>
                        <div class="col-lg-10">
                          <input type="port" name="port" value="<?=$data_edit->port;?>" class="form-control" required> 
                        </div>
                      </div><!-- /.form-group -->
<div class="form-group">

                        <label for="URL Feeder" class="control-label col-lg-2">URL Feeder</label>
                        <div class="col-lg-10">
                        <span style="color:#f00">Jika Feeder Dikti satu komputer dengan Importer isi localhost, jika beda komputer, isi dengan ip address atau alamat domain</span>
                          <input type="text" name="url" value="<?=$data_edit->url;?>" class="form-control" id="url" required> 
                        </div>
                      </div><!-- /.form-group -->

                      <input type="hidden" name="id" value="<?=$data_edit->id;?>">
                      <div class="form-group">
                        <label for="tags" class="control-label col-lg-2">&nbsp;</label>
                        <div class="col-lg-10">
                           <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
                    
                        </div>
                      </div><!-- /.form-group -->
                    </form>
                  
                  </div>
                  </div>
              </div>
</div>
                  
                </section><!-- /.content -->
<!-----------------BATAS UTAMA-------------------------->				
	</div>
</div>