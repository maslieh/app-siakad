<div id="menu">
    <ul class="menu">
		<li><a href="index.php"><span style="color: #ffffff;">Home</span></a></li>

<?php
	$ambilkepala =  $koneksi_db->sql_query("SELECT * FROM modul_kepala where aktif='Y' order by id_kepala asc" );
	if ($koneksi_db->sql_numrows($ambilkepala) > 0) 
	{
		while ($rowkepala = $koneksi_db->sql_fetchrow($ambilkepala)) {
		if (strpos($rowkepala['level_kepala'], $_SESSION['Level'])) {
		$parent= $rowkepala[0];
		
		?>
		<li><a href="#" class="parent"><span style="color: #ffffff;"><?= $rowkepala[1]?></span></a>
			<div>
				<ul>
				<?php
				    $subhasil = $koneksi_db->sql_query( "SELECT * FROM modul_sub WHERE aktif='Y' AND id_kepala=$parent and cabang='0' ORDER BY urut" );
        			if ( $koneksi_db->sql_numrows( $subhasil ) >0) {
 						while ($subdata = $koneksi_db->sql_fetchrow($subhasil)) {
						if (strpos($subdata['level_sub'], $_SESSION['Level'])) {
							$parentsub= $subdata[0];
							$subsubhasil = $koneksi_db->sql_query( "SELECT * FROM modul_sub WHERE aktif='Y' and id_kepala=$parent AND cabang=$parentsub ORDER BY urut" );
							if ( $koneksi_db->sql_numrows( $subsubhasil ) >0) {
							?>
							<li><a href="index.php?m=<?= $subdata[0]?>" title="<?= $subdata[3]?>t" class="parent"><span><?= $subdata[3]?></span></a>
								<div><ul>
							<?php
								while ($subsubdata = $koneksi_db->sql_fetchrow($subsubhasil)) {
								if (strpos($subsubdata['level_sub'], $_SESSION['Level'])) {
								?>
								<li><a href="index.php?m=<?= $subsubdata[0]?>" title="<?= $subsubdata[3]?>"><span><?= $subsubdata[3]?></span></a></li>
								<?php
								}
								}
							?>
								</ul>
								</div>
							</li>
							
							<?php	
							} else {
							?>
							<li><a href="index.php?m=<?= $subdata[0]?>" title="Data Kuliah"><span><?= $subdata[3]?></span></a></li>
							<?php
							}
						}
						}
					}
				?>			
				</ul>
			</div>
		</li>	
		<?php
		}
		}
	}


?>	
		
	</ul>
</div>
