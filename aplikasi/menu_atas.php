<div id="menu">
    <ul class="menu">
		<li><a href="index.php"><span style="color: #ffffff;">Home</span></a></li>

<?php
$ambilkepala =  $koneksi_db->sql_query("SELECT * FROM modul_kepala where aktif='Y' order by id_kepala asc" );
if ($koneksi_db->sql_numrows($ambilkepala) > 0) {
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
						
						
						if ( $koneksi_db->sql_numrows( $subsubhasil ) > 0) {
							if ($subdata[1]!='7') {
							?>
							<li><a href="index.php?m=<?= $subdata[0]?>" title="Data Kuliah" class="parent"><span><?= $subdata[3]?></span></a>
							<?php
							} else{
							?>
							<li><a href="index.php?module=<?= $subdata[4]?>" title="<?= $subdata[3]?>" class="parent"><span><?= $subdata[3]?></span></a>
							<?php
							}
							
							?>
								<div>
								<ul>
							<?php
							while ($subsubdata = $koneksi_db->sql_fetchrow($subsubhasil)) {
								if (strpos($subsubdata['level_sub'], $_SESSION['Level'])) {
									if ($subsubdata['id_kepala']!='7') {
									?>
									<li><a href="index.php?m=<?= $subsubdata[0]?>" title="<?= $subsubdata[3]?>"><span><?= $subsubdata[3]?></span></a></li>
									<?php
									} else {
									?>
									<li><a href="index.php?module=<?= $subsubdata[4]?>" title="<?= $subsubdata[3]?>"><span><?= $subsubdata[3]?></span></a></li>
									<?php
									}
								}
							}
							?>
								</ul>
								</div>
							</li>
								
						<?php	
						} else {
							if ($subdata[1]!='7') {
							?>
							<li><a href="index.php?m=<?= $subdata[0]?>" title="Data Kuliah"><span><?= $subdata[3]?></span></a></li>
							<?php
							} else{
							?>
							<li><a href="index.php?module=<?= $subdata[4]?>" title="<?= $subdata[3]?>"><span><?= $subdata[3]?></span></a></li>
							<?php
							}
						} // end if subsub
					} // end level sub
				} // end whil sub
			} // end if sub
				?>			
				</ul>
			</div>
		</li>	
		<?php
		} // end level top
	} //end while top
} // end top


?>	
		
	</ul>
</div>
