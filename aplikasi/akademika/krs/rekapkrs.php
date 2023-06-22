<?php 
if (!cek_pass_sama()) {
 
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


function RekapKRS() {
global $koneksi_db,$tahun_id;
	$prodi = $_SESSION['prodi'];
	//$tahun_id= $_SESSION['tahun_id'];

	$besar = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query("select max(semester) as besar from m_mata_kuliah where kode_prodi='$prodi'" ));
		$besarx = $besar['besar'];
		
		if ( empty($besar) ) {
		
        echo ' <div class="alert alert-danger">Mata Kuliah '.$prodi.' Kosong</div>
			 ';
		} else {
		
		for ($i=0; $i<$besarx; $i++) {
		$smtr = $i+1 ;
		
	echo '<div class="panel panel-success">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapse'.$i.'">
        SEMESTER <span class="badge pull-right">'.$smtr.'</span>
		</a>
      </h4>
    </div>';
		
		echo ' <div id="collapse'.$i.'" class="panel-collapse collapse">
        	<div class="panel-body">
<div class="table-responsive">
        	
                <table  class="table table-striped table-bordered"  >
                  <thead>
					<tr>
						<th rowspan="2" valign="middle">Kode</th>
						<th rowspan="2" valign="middle">Mata Kuliah</th>
						<th rowspan="2" valign="middle">Kelas</th>
						<th rowspan="2" valign="middle">Batas Kelas</th>
						<th rowspan="2" valign="middle">Jumlah Siswa yang sudah Ambil</th>
					</tr>
					</thead>
					<tbody>';
						$s = "select a.id, b.kode_mk, b.nama_mk, a.id, a.kelas, a.kapasitas from t_jadwal a inner join m_mata_kuliah b on a.id=b.id
								where a.kode_prodi='$prodi' and b.semester='$smtr' and tahun_id=$tahun_id order by b.nama_mk, a.kelas";
						$r = $koneksi_db->sql_query($s);
						while ($k = $koneksi_db->sql_fetchassoc($r)) {
                        echo 
						'<tr>	
                            <td>'.$k['kode_mk'].'</td>
                            <td>'.$k['nama_mk'].'</td>
                            <td>'.$k['kelas'].'</td>
                            <td>'.$k['kapasitas'].'</td>
    						';
    						$qn = "select  count(idkrs) as jumlah from t_mahasiswa_krs
    							where  kode_prodi='$prodi' and id='$k[id]' and tahun_id='$tahun_id' and kelas='$k[kelas]' and verifi_pa=1";
    							$pn = $koneksi_db->sql_query($qn);
    							$jn= $koneksi_db->sql_fetchassoc($pn);
							echo'
                            <td>'.$jn['jumlah'].'</td>
                            </tr>';
                        }
                echo '
                </tbody> 
                </table>
 
            </div></div></div></div>';
		}
		}
  
	
}

		
$go = (empty($_REQUEST['op'])) ? 'RekapKRS' : $_REQUEST['op'];
echo '<div id="title" align="right">
        <font style="color:#FF9900; font-size:30px;"><strong>.:</strong></font>
        <font style="font-size:18px; color:#999999">Jadwal Kuliah</font><br />
        	<a href="index.php">Home</a> &raquo; <a href="?m='.$_GET['m'].'">Jadwal</a>  &raquo; '.$go.'  
    </div>';

echo '<div class="mainContentCell"><div class="content">';
	echo'<div  class="pagination"><ul >';
	  for ($i = 0; $i < sizeof($arrSub); $i++) {
		$mn = explode('->', $arrSub[$i]);
		$c = ($mn[1] == $go)? 'class=current' : '';
		echo "<li><a $c href='index.php?m=".$_GET['m']."&op=$mn[1]'><span>$mn[0]</span></a></li>";
	  }
	echo	'</ul></div>';
	echo '<div class="clear"></div>';	

$go();
echo '</div></div>';
?>

 
<?php } ?>