
<?php 
global $koneksi_db;
$ikon = array(
'fa-home fa-lg', // home
'fa-briefcase  fa-lg', // master
'fa-file-text-o  fa-lg', // pmb
'fa-font fa-lg', // akademika
'fa-users fa-lg', // hrd
'fa-shopping-cart fa-lg', // keuangan
'fa-user fa-lg', // alumni
'fa-bar-chart-o fa-lg', // grafik
'fa-external-link fa-lg', // dikti
'fa-book fa-lg', // perpus
'fa-stack-overflow fa-lg', // inventory
'fa-envelope-o fa-lg', // ARSIP SURAT
'fa-gears fa-lg', // tool
'fa-lock fa-lg', // akun
'fa-envelope-o fa-lg', // esurat
'fa-save fa-lg' // excel
);

$menux = (isset($_GET['m'])) ? $_GET['m'] : '' ;
$idkepala = '';
$idcabang = '';
if ($menux!='') {
	$ambil=  $koneksi_db->sql_fetchrow($koneksi_db->sql_query("SELECT id_kepala, cabang FROM modul_sub where id_sub='".$menux."' limit 1" ));
	$idkepala = $ambil[0];
	$idcabang = $ambil[1];
} 
?>
<ul class="nav" id="main-menu" >
	<li>
	<a <?php if ($menux=='') echo 'class="active-menu"';?> href="index.php">
		<i class="fa fa-laptop fa-lg"></i>DASHBOARD</a> 
	</li>
<?php
$qkepala =  "SELECT * FROM modul_kepala where aktif='Y' order by id_kepala asc" ;
$ambilkepala =  $koneksi_db->sql_query($qkepala );
if ($koneksi_db->sql_numrows($ambilkepala) > 0) {
$ik = 0;
	while ($rowkepala = $koneksi_db->sql_fetchrow($ambilkepala)) {
		$icon=$rowkepala['icon'];
	$ik ++;
		if (strpos($rowkepala[3], $_SESSION['Level'])) {
		$parent= $rowkepala[0];
		?>
		<li <?php if ($parent==$idkepala) echo 'class="active"';?> >
			<a href="#" class="parent <?php if ($parent==$idkepala) echo 'active-menu';?>" >
			<i class="fa <?=$rowkepala[5];?>"></i>
			<?= $rowkepala[1]?> 
			<span style="display:inline-block;float:right" class="fa fa-sort-down"/></a>
			
				<ul class="nav nav-second-level">
			<?php
			$qsubhasil =  "SELECT * FROM modul_sub WHERE aktif='Y' AND id_kepala='$parent' and cabang='0' ORDER BY urut" ;
			$subhasil = $koneksi_db->sql_query( $qsubhasil);
			if ( $koneksi_db->sql_numrows( $subhasil ) > 0 ) {
 				while ($subdata = $koneksi_db->sql_fetchrow($subhasil)) {
					if (strpos($subdata[5], $_SESSION['Level'])) {
						$parentsub= $subdata[0];
						$qsubsubhasil =  "SELECT * FROM modul_sub WHERE aktif='Y' and id_kepala='$parent' AND cabang='$parentsub' ORDER BY urut" ;
						$subsubhasil = $koneksi_db->sql_query( $qsubsubhasil );
						if ( $koneksi_db->sql_numrows( $subsubhasil ) > 0) {
							?>
							<li <?php if ($parentsub==$idcabang) echo 'class="active"';?>>
									<a  
										href="index.php?m=<?= $subdata[0]?>" 
										title="<?= $subdata[3]?>" 
										class="parent <?php if ($parentsub==$idcabang) echo 'active-menu';?>">
										<span ><?= $subdata[3]?><span style="display:inline-block;float:right" class="fa fa-sort-down"/></span>
									</a>
								<ul class="nav nav-third-level">
									<?php
									while ($subsubdata = $koneksi_db->sql_fetchrow($subsubhasil)) {
										if (strpos($subsubdata[5], $_SESSION['Level'])) {
											?>
											<li >
												<a <?php if ($menux==$subsubdata[0]) echo 'class="active-menu"';?> 
													href="index.php?m=<?= $subsubdata[0]?>" 
													title="<?= $subsubdata[3]?>">
													<span><?= $subsubdata[3]?></span>
												</a>
											</li>
											<?php
										}
									}
									?>
								</ul>
							</li>
						<?php	
						} else {
							?>
							<li >
								<a <?php if ($menux==$subdata[0]) echo 'class="active-menu"';?> 
									href="index.php?m=<?= $subdata[0]?>" 
									title="<?= $subdata[3]?>">
									<span><?= $subdata[3]?></span>
								</a>
							</li>
							<?php
						} // end if subsub
					} // end level sub
				} // end whil sub
			} // end if sub
				?>			
				</ul>
		</li>	
		<?php
		} // end level top
	} //end while top
} // end top
?>	

</ul>