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

function hapus() {
echo "<div class=error>Ma'af.. Data tidak dapat dihapus, hanya bisa diedit</div>";
Daftar();
}


function Daftar() {
global $koneksi_db;
$no=0;
 
$result = $koneksi_db->sql_query("SHOW COLUMNS FROM `user` WHERE Field ='level' ");
$col2 = $koneksi_db->sql_fetchassoc($result);
preg_match("/^enum\(\'(.*)\'\)$/", $col2['Type'], $matches);
$enum = explode("','", $matches[1]);

 

$ambilkepala =  $koneksi_db->sql_query("SELECT * FROM modul_kepala where aktif='Y' order by id_kepala asc" );
	if ($koneksi_db->sql_numrows($ambilkepala) > 0) 
	{
		while ($rowkepala = $koneksi_db->sql_fetchrow($ambilkepala)) {
		$no++;
		$parent= $rowkepala[0];
		$ambiljml = $koneksi_db->sql_query( "SELECT * FROM modul_sub WHERE id_kepala=$parent ORDER BY id_sub" );
		$jmlmodul = $koneksi_db->sql_numrows( $ambiljml );
		
		?>

<div class="panel panel-primary">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapse<?= $rowkepala[0]?>">
        <?= $no?>. <?= strtoupper($rowkepala[1])?>
		<span class="badge pull-right"><?= $jmlmodul?> Modul</span>
		</a>
      </h4>
    </div>
	
		 <div id="collapse<?= $rowkepala[0]?>" class="panel-collapse collapse">
        	<div class="panel-body">
 
				<table  class="table table-striped table-bordered table-hover" >
					<tr>
					<form action='' method=POST>
						<input type=hidden name='m' value='<?= $_GET['m']?>'>
						<input type="hidden" name="op" value="updateatas"/>
					<th colspan="6">
						
						<?php
							foreach ($enum as $k=>$v){
							if (strpos($rowkepala[3], $v)) { 
								echo '<input name="level['.$rowkepala[0].'][]" type="checkbox" value="'.$v.'" checked="checked" />'.$v.'';
							} else {
								echo '<input name="level['.$rowkepala[0].'][]" type="checkbox" value="'.$v.'" />'.$v.'';
							}								
							}
						?>
						
						
					</th>
					<th>
					<select name="aktif[<?= $rowkepala[0]?>]"  class="required"   /><?= opYN($rowkepala[4]);?></select>
					<input type="submit" class=tombols ui-corner-all value="Update Top"/>
					</th>
					</form>
					</tr>
					<tr>
						<th colspan="2" valign="middle">NO</th>
						<th valign="middle">ID</th>
						<th valign="middle">Nama Modul</th>
						<th >Skrip</th>
						<th >Hak Akses</th>
						<th >Aktif</th>
					</tr>
				<?php
				    $subhasil = $koneksi_db->sql_query( "SELECT * FROM modul_sub WHERE id_kepala=$parent and cabang='0' ORDER BY urut" );
        			if ( $koneksi_db->sql_numrows( $subhasil ) >0) {
						$noa=0;
 						while ($subdata = $koneksi_db->sql_fetchrow($subhasil)) {
							$noa ++;
							$parentsub= $subdata[0];
							$subsubhasil = $koneksi_db->sql_query( "SELECT * FROM modul_sub WHERE id_kepala=$parent AND cabang=$parentsub ORDER BY urut" );
							?>
							<form action='' method=POST>
							<input type="hidden" name="m" value="<?= $_GET['m']?>"/>
							<input type="hidden" name="op" value="update"/>
							<tr>
								<td colspan="2" ><?= $noa?></td>
								<td ><?= $subdata[0]?></td>
								<td valign="middle"><?= $subdata[3]?></td>
								<td ><?= $rowkepala[2]?>/<?= $subdata[4]?></td>
								<td >
								<?php
								foreach ($enum as $k=>$v){
									if (strpos($subdata[5], $v)) { 
										echo '<input name="level['.$subdata[0].'][]" type="checkbox" value="'.$v.'" checked="checked" />'.$v.'';
									} else {
										echo '<input name="level['.$subdata[0].'][]" type="checkbox" value="'.$v.'" />'.$v.'';
									}
								}
								?>
								</td>
								<td ><select name="aktif[<?= $subdata[0]?>]"  class="required"   /><?= opYN($subdata[6]);?></select></td>
								</tr>
							<?php
							if ( $koneksi_db->sql_numrows( $subsubhasil ) >0) {
								$nob =0;
								while ($subsubdata = $koneksi_db->sql_fetchrow($subsubhasil)) {
								$nob++;
							?>
								<tr>
								<td ></td><td ><?= $nob?></td>
								<td ><?= $subsubdata[0]?></td>
								<td valign="middle"><?= $subsubdata[3]?></td>
								<td ><?= $rowkepala[2]?>/<?= $subdata[4]?>/<?= $subsubdata[4]?></td>
								<td >
								<?php
									foreach ($enum as $k=>$v){
									if (strpos($subsubdata[5], $v)) { 
										echo '<input name="level['.$subsubdata[0].'][]" type="checkbox" value="'.$v.'" checked="checked" />'.$v.'';
									} else {
										echo '<input name="level['.$subsubdata[0].'][]" type="checkbox" value="'.$v.'" />'.$v.'';
									}								}
								?>
								</td>
								<td ><select name="aktif[<?= $subsubdata[0]?>]"  class="required"   /><?= opYN($subsubdata[6]);?></select></td>
								</tr>
								<?php
								}
							?>
								</ul>
							</li>
							
							<?php	
							} 
							
						?>
						<tr><td colspan="6" align="right"></td><td><input type="submit" class=tombols ui-corner-all value="Update Sub"/> </td></tr>
						</form>
						<?php	
						} // end while subsub
					}
				?>	
						
				</table>
			
		</div>	
		</div>
		</div>
		
		
		<?php
		}
	}

}

function updateatas() {
    global $koneksi_db;
	if (is_array($_POST['aktif'])) {
		foreach($_POST['aktif'] as $key=>$val) {
			$aktif = $_POST['aktif'][$key];
			$level = $_POST['level'][$key];
			$levelx = implode('.',$level);
			$update = "UPDATE `modul_kepala` SET `level_kepala` = '.$levelx.', `aktif` = '$aktif' WHERE `id_kepala` = '$key'";
			$simpan = $koneksi_db->sql_query($update );
			//echo $update;
		}
	}
	
    Daftar();
}

function update() {
     global $koneksi_db;
	if (is_array($_POST['aktif'])) {
		foreach($_POST['aktif'] as $key=>$val) {
			$aktif = $_POST['aktif'][$key];
			$level = $_POST['level'][$key];
			$levelx = implode('.',$level);
			$update = "UPDATE `modul_sub` SET `level_sub` = '.$levelx.', `aktif` = '$aktif' WHERE `id_sub` = '$key'";
			$simpan = $koneksi_db->sql_query($update );
			//echo $update.'<br/>';
		}
	}
	
	Daftar();
}





$go = (empty($_REQUEST['op'])) ? 'Daftar' : $_REQUEST['op'];
echo '<div id="title" align="right">
        <font style="color:#FF9900; font-size:30px;"><strong>.:</strong></font>
        <font style="font-size:18px; color:#999999">Daftar Modul</font><br />
        	<a href="?m=home">Home</a> &raquo; <a href="?m='.$_GET['m'].'">Pengaturan Modul</a>  &raquo; '.$go.'  
    </div>';
echo '<div class="mainContentCell"><div class="content">';
$go();
echo '</div></div>';
?>
 