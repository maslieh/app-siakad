<?php

if (cek_login ()){
$tombolx='';
global $koneksi_db, $tahun_id, $namaprodi;
if ($_SESSION['Level']=="ADMIN" || $_SESSION['Level']=="DOSEN" || $_SESSION['Level']=="ADKEU" ||  $_SESSION['Level']=="PA" || $_SESSION['Level']=="DIREKTUR" || $_SESSION['Level']=="ADAK" || $_SESSION['Level']=="ADMA" || $_SESSION['Level']=="ADKUL"	 ) {

	$m = (empty($_REQUEST['m'])) ? '' : $_REQUEST['m'];
	$prodi = BuatSesi('prodi');
	$prodi = $_SESSION['prodi'];
		
		
	$qp = "SELECT * FROM m_program_studi where kode_prodi='$prodi' limit 1 ";
	
	$pr = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( $qp ));
	$namaprodi = strtolower($pr['nama_prodi']);
	$jenjangprodi = $pr['kode_jenjang'];
		if (empty($prodi) || !isset($prodi)) { 	
			$tombolx .= 'Anda belum memilih Program Studi dan Tahun Akademik, Klik 	<a href="#" class="btn btn-danger " data-toggle="modal" data-target="#modal-transparent">PILIH</a>'; 
		} else {
			$tombolx = ' <a class="btn btn-danger " href="#" data-toggle="modal" data-target="#modal-transparent">UBAH</a> ';
		}
	} else if ($_SESSION['Level']=="MAHASISWA" ) {
		$userM=$_SESSION['UserName'];
		$m2 = (empty($_REQUEST['m'])) ? '' : $_REQUEST['m'];
		$row = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT kode_prodi FROM m_mahasiswa where NIM ='$userM' limit 1 " ));
		$prodi = $row['kode_prodi'];
		$_SESSION['prodi']= $prodi;
		$tahun_id = BuatSesi('thakad');
		$tahun_id = $_SESSION['thakad'];
			
		$qp = "SELECT * FROM m_program_studi where kode_prodi='$prodi' limit 1 ";
	
		$pr = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( $qp ));
		$namaprodi = strtolower($pr['nama_prodi']);
			if (empty($tahun_id) || !isset($tahun_id)) { 	
				$tombolx .= '<font style="background-color:Tomato;"><-Anda belum memilih Tahun Akademik-></font>, Klik 	<a href="#" class="btn btn-danger " data-toggle="modal" data-target="#modal-transparent2">PILIH</a>'; 
			} else {
				$tombolx = ' <a class="btn btn-danger " href="#" data-toggle="modal" data-target="#modal-transparent2">UBAH</a> ';
			}
		
	
	} else if ($_SESSION['Level']=="PRODI" ) {
	    
	$userM=$_SESSION['UserName'];
		$m2 = (empty($_REQUEST['m'])) ? '' : $_REQUEST['m'];
		$qp = "SELECT * FROM m_program_studi where kode_prodi='$userM' limit 1 ";
        $pr = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( $qp ));
        $prodi = $pr['kode_prodi'];
		$_SESSION['prodi']= $prodi;
		$tahun_id = BuatSesi('thakad');
		$tahun_id = $_SESSION['thakad'];
        	
		
		$namaprodi = strtolower($pr['nama_prodi']);
			if (empty($tahun_id) || !isset($tahun_id)) { 	
				$tombolx .= '<font style="background-color:Tomato;"><-Anda belum memilih Tahun Akademik-></font>, Klik 	<a href="#" class="btn btn-danger " data-toggle="modal" data-target="#modal-transparent2">PILIH</a>'; 
			} else {
				$tombolx = ' <a class="btn btn-danger " href="#" data-toggle="modal" data-target="#modal-transparent2">UBAH</a> ';
			}    
	    
	
		
	
	} 
	
}



if ($_SESSION['Level']=="MABA" ) {

}
else {
	
	echo '<font >'.ucwords(NamaTahun($_SESSION['thakad'],$pr['kode_prodi'])).',- '.ucwords($namaprodi).' '.$tombolx.'</font>';
	//echo $prodi;
}

?>

<!-- Modal transparent -->

<div class="modal  fade modal-transparent" id="modal-transparent" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
 <form class="form-horizontal" id="form1" method="post" 
	action="index.php?m=<?=$_GET['m'];?>" >
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" style="color:#FF0000" id="myModalLabel">Pilih Program Studi dan Tahun Akademik</h4>
      </div>
      <div class="modal-body">
			<div class="col-md-7">  
	   		<select  class="form-control" style="width:100%" name="prodi" ><?php echo''.opprodi(''.$_SESSION['prodi'].'').'';?></select>
			</div>
			<div class="col-md-5"> 
			<select  class="form-control" style="width:100%" name="thakad" ><?php echo''.optahunakademik(''.$_SESSION['thakad'].'').'';?></select>
			</div>
      </div>
      <div class="modal-footer">
       
		<button type="submit" class="btn btn-primary">Pilih</button>
      </div>
    </div>
  </div>
 </form>
</div>  

<div class="modal  fade modal-transparent" id="modal-transparent2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
 <form class="form-horizontal" id="form1" method="post" 
	action="index.php?m=<?=$_GET['m'];?>" >
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" style="color:#FF0000" id="myModalLabel">Pilih Tahun Akademik</h4>
      </div>
      <div class="modal-body">
			<div class="col-md-12"> 
			<select  class="form-control" style="width:100%" name="thakad" ><?php echo''.optahunakademik(''.$_SESSION['thakad'].'').'';?></select>
			</div>
      </div>
      <div class="modal-footer">
       
		<button type="submit" class="btn btn-primary">Pilih</button>
      </div>
    </div>
  </div>
 </form>
</div>
