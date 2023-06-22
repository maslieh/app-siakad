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
}


function DaftarFak() {

global $koneksi_db;
	$qpt = $koneksi_db->sql_query( "SELECT * FROM m_perguruan_tinggi limit 1 " );
	$totalpt = $koneksi_db->sql_numrows($qpt);
	if ($totalpt < 1) { 
	echo '<div class=error>Identitas Perguruan Tinggi Masih Kosong, Klik <a href="index.php?m=2">Add Perguruan Tinggi</a></div>'; 
	} else {
	
	$wpt = $koneksi_db->sql_fetchrow($qpt);
	echo"<input type=button class=button value='Tambah Fakultas' onclick=\"window.location.href='index.php?m=".$_GET['m']."&op=edit_fak&md=1';\"><br/>";
	echo '<table   border="0" class="table table-striped table-bordered table-hover">
		<thead>
		 <tr>
		   <th width="5%" align="center">No</th>
		   <th align="center">KodePT</th>
		   <th align="center">Kode</th>
		   <th align="center">Fakultas</th>
		   <th align="center"></th>
		 </tr>
		 </thead>
		 <tbody>';
	$qfak = $koneksi_db->sql_query( "SELECT * FROM m_fakultas where kode_pt='$wpt[0]' " );
		$jumlah=$koneksi_db->sql_numrows($qfak);
		if ($jumlah >= 1){
			while($wf = $koneksi_db->sql_fetchrow($qfak)){
				$n++;
			  echo '<tr><td $c>'.$n.'</td>
				<td $c>'.$wf[1].'</td>
				<td $c><a href="index.php?m='.$_GET['m'].'&fid='.$wf[0].'&op=edit_fak&md=0">'.$wf[0].'</a></td>
				<td $c><a href="index.php?m='.$_GET['m'].'&fid='.$wf[0].'">'.$wf[2].'</a></td>
				<td $c>
					<a href="#" class="btn btn-primary" onclick="window.location.href=\'index.php?m='.$_GET['m'].'&op=edit_fak&fid='.$wf[0].'\';">
					<i class="fa fa-folder"></i></a>
					<a href="#" class="btn btn-primary"onclick="window.location.href=\'index.php?m='.$_GET['m'].'&op=edit_fak&md=0&fid='.$wf[0].'\';">
					<i class="fa fa-edit"></i></a>
					<a href="#" class="btn btn-primary" onclick="window.location.href=\'index.php?m='.$_GET['m'].'&op=hapus&fid='.$wf[0].'\';">
					<i class="fa fa-trash-o"></i></a>
				</td></tr>';
			}
		} else {
		echo '<tr > <th  colspan="4" align=center>Belum ada Data</th></tr>';
		}
		 echo '	</tbody></table>';	
	}
}

function DaftarProdi() {
global $koneksi_db;
$fid = $_SESSION['fid'];
if (!empty($fid)) {
	$qf = $koneksi_db->sql_query( "SELECT * FROM m_fakultas limit 1 " );
	$totalf = $koneksi_db->sql_numrows($qf);
	if ($totalf < 1) { 
	echo '<div class=error>Data Fakultas Masih Kosong, Klik <a href="index.php?m='.$_GET['m'].'&op=edit_fak&md=1">Add Fakultas</a></div>'; 
	} else {
	
	//$wf = $koneksi_db->sql_fetchrow($qf);
	echo"<input type=button class=button value='Tambah Program Studi' onclick=\"window.location.href='index.php?m=".$_GET['m']."&op=edit_prodi&md=1';\"><br/>";
	echo '<table width="100%" border="0" align="center" cellpadding="1" cellspacing="0" class="table full">
		<thead>
		 <tr>
		   <th width="5%" align="center">No</th>
		   <th align="center">Kode</th>
		   <th align="center">Program Studi</th>
		   <th align="center">Jenjang</th>
		   <th align="center"></th>
		 </tr>
		 </thead>
		 <tbody>';
	$qk = $koneksi_db->sql_query( "SELECT * FROM m_program_studi where kode_fak='$fid'" );
		$jumlah=$koneksi_db->sql_numrows($qk);
		if ($jumlah > 0){
			while($wp = $koneksi_db->sql_fetchrow($qk)){
				$nn++;
			  echo "<tr>
			  <td >$nn</td>
				<td align='center'><a href='index.php?m=".$_GET['m']."&kid=$wp[0]&op=edit_prodi&md=0'>$wp[0]</a></td>
				<td align='left'><a href='index.php?m=".$_GET['m']."&pid=$wp[0]'>$wp[5]</td>
				<td align='center'>".viewAplikasi('04',''.$wp[3].'')."</td>
				<td align='center'>";
				echo '
					<a href="#" class="btn btn-primary" onclick="window.location.href=\'index.php?m='.$_GET['m'].'&op=edit_prodi&pid='.$wp[0].'\';">
					<i class="fa fa-folder"></i></a>
					<a href="#" class="btn btn-primary"  onclick="window.location.href=\'index.php?m='.$_GET['m'].'&op=edit_prodi&md=0&pid='.$wp[0].'\';">
					<i class="fa fa-edit"></i></a>
					<a href="#" class="btn btn-primary"  onclick="window.location.href=\'index.php?m='.$_GET['m'].'&op=hapus&pid='.$wp[0].'\';">
					<i class="fa fa-trash-o"></i></a>';
				echo "</td></tr>";
			}
		} else {
		echo '<tr > <th  colspan="5" align=center>Belum ada Data</th></tr>';
		}
		 echo '	</tbody></table>';	
	}
}
}

function DaftarKonsentrasi() {
global $koneksi_db;
$fid = $_SESSION['fid'];
$pid = $_SESSION['pid'];

if (!empty($fid)&& !empty($pid)) {
	$qf = $koneksi_db->sql_query( "SELECT * FROM m_program_studi limit 1 " );
	$totalf = $koneksi_db->sql_numrows($qf);
	if ($totalf < 1) { 
	echo '<div class=error>Data Program Studi Masih Kosong, Klik <a href="index.php?m='.$_GET['m'].'&op=edit_prodi&md=1">Add Program Studi</a></div>'; 
	} else {
	
	//$wf = $koneksi_db->sql_fetchrow($qf);
	echo"<input type=button class=button value='Tambah Konsentrasi' onclick=\"window.location.href='index.php?m=".$_GET['m']."&op=edit_kons&md=1';\"><br/>";
	echo '<table width="100%" border="0" align="center" cellpadding="1" cellspacing="0" class="table full">
		<thead>
		 <tr>
		   <th width="5%" align="center">No</th>
		   <th align="center">Kode</th>
		   <th align="center">Nama Konsentrasi</th>
		   <th align="center"></th>
		 </tr>
		 </thead>
		 <tbody>';
	$qk = $koneksi_db->sql_query( "SELECT * FROM m_konsentrasi where kode_fak='$fid' and kode_prodi='$pid'" );
		$jumlah=$koneksi_db->sql_numrows($qk);
		if ($jumlah > 0){
			while($wk = $koneksi_db->sql_fetchrow($qk)){
				$nn++;
			  echo "<tr>
			  <td >$nn</td>
				<td align='center'><a href='index.php?m=".$_GET['m']."&kid=$wk[0]&op=edit_kons&md=0'>$wk[0]</a></td>
				<td align='left'>$wk[4]</a></td>
				<td align='center'>";
				echo'<a href="#" class="btn btn-primary" onclick="window.location.href=\'index.php?m='.$_GET['m'].'&op=edit_kons&kid='.$wk[0].'\';"><i class="fa fa-folder"></i></a>
					<a href="#" class="btn btn-primary" onclick="window.location.href=\'index.php?m='.$_GET['m'].'&op=edit_kons&md=0&kid='.$wk[0].'\';"><i class="fa fa-edit"></i></a>
					<a href="#" class="btn btn-primary" onclick="window.location.href=\'index.php?m='.$_GET['m'].'&op=hapus&kid='.$wk[0].'\';"><i class="fa fa-trash-o"></i></a>';
				echo "</td></tr>";
			}
		} else {
		echo '<tr > <th  colspan="5" align=center>Belum ada Data</th></tr>';
		}
		 echo '	</tbody></table>';	
	}
}
}


function edit_fak() {
global $koneksi_db;
  $md = $_REQUEST['md']+0;
  if ($md == 0) {
    $id = $_REQUEST['fid'];
	$w = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM m_fakultas where kode_fakultas='$id' limit 1 " ));
    $jdl = "Edit Data Fakultas";
	$kode = '<input name="kode_fak"  disabled="disabled"  type="text" class="" id="" value="'.$w['kode_fakultas'].'" />
		<input name="kode_fakultas"  type="hidden" class="required" id="" value="'.$w['kode_fakultas'].'" />
	';

  }
  else {
    $w = array();
    $jdl = "Tambah Fakultas";
	$kode = '<input name="kode_fakultas"  type="text" class="required" id="" value="'.$w['kode_fakultas'].'" />
	';

  }

//$aktif = ($w['buka'] == 'Y') ? 'checked' : ''; 
	
echo '  
    <form action="" method="post"  class="cmxform" id="form_input" style="width:100%">
		<input type="hidden" name="id" value="'.$id.'"/>
         <input type="hidden" name="m" value="fakultas"/>
        <input type="hidden" name="op" value="simpan_fak"/>
		<input type="hidden" name="md" value="'.$md.'"/>
       
		<fieldset class="ui-widget ui-widget-content ui-corner-all" >
            <legend class="ui-widget ui-widget-header ui-corner-all">'.$jdl.'</legend>
            &nbsp;<font color="red"><br></font>
            <table width="100%"  border="0" class="datatable full1">
                <tr>
                    <td width="150" align="right" valign="top">Perguruan Tinggi<font color="red"> *</font></td>
                    <td  >
					<select name="kode_pt"  class=" form-control" required   />'.oppt(''.$w['kode_pt'].'').'</select>
					</td>
                </tr>
                <tr>
                    <td align="right" valign="top">Kode Fakultas<font color="red"> *</font></td>
                    <td>
					'.$kode.'
					</td>
                </tr>

                <tr>
                    <td align="right" valign="top">Nama Fakultas<font color="red"> *</font></td>
                    <td>
					<input name="nama_fakultas"  type="text" class=" form-control" required id="" value="'.$w['nama_fakultas'].'" />
					</td>
                </tr>
				
                <tr>
                    <td width="150" align="right" valign="top">Nama Pejabat<font color="red"> *</font></td>
                    <td  >
					<select name="pejabat"  class=" form-control " required  />'.opdosen(''.$w['pejabat'].'').'</select>
					</td>
                </tr>

                <tr>
                    <td width="150" align="right" valign="top">Jabatan<font color="red"> *</font></td>
                    <td  >
					<select name="jabatan"  class="form-control" required    />'.opAplikasi('09',''.$w['jabatan'].'').'</select>
					</td>
                </tr>

                 <tr>
                    <td colspan="2">
                        <input type="submit" class="tombols ui-corner-all" value="Simpan"/> 
                        <input type="button" class="tombols ui-corner-all" value="Kembali" onClick="window.location = \'index.php?m='.$_GET['m'].'\'"/></td>
                  </tr>
            </table>
        </fieldset>
    </form>
 ';

}


////simpan /
function simpan_fak() {
global $koneksi_db;
  $md = $_REQUEST['md']+0;
	$id = $_REQUEST['id'];  

	if (trim($_POST['kode_pt'])=="") {
		$pesan[] = "Form Perguruan Tinggi masih kosong, ulangi kembali";
	}
	else if (trim($_POST['kode_fakultas'])=="") {
		$pesan[] = "Form kode Fakultas masih kosong, ulangi kembali";
	}
	else if (trim($_POST['nama_fakultas'])=="") {
		$pesan[] = "Form Nama Fakultas masih kosong, ulangi kembali";
	}
	
	if (! count($pesan)==0 ) {
		echo "<div align='left'>";			
		echo "&nbsp; <b> Kesalahan Input : </b><br>";
		foreach ($pesan as $indeks=>$pesan_tampil) {
			$urut_pesan++;
			echo "<font color='#FF0000' align='left'>";
			echo "&nbsp; &nbsp;";
			echo "$urut_pesan . $pesan_tampil <br>";
			echo "</font>";
		}
		echo "</div><br>";
		echo "<meta http-equiv='refresh' content='3; url=index.php?m=fakultas&op=edit_fak&md=1'>";
	} else {
		
			if ($md == 0) {
			$s = "update m_fakultas set 
					kode_pt='".$_REQUEST['kode_pt']."',
					nama_fakultas='".$_REQUEST['nama_fakultas']."',
					pejabat='".$_REQUEST['pejabat']."',
					jabatan='".$_REQUEST['jabatan']."'
					where kode_fakultas='".$_REQUEST['kode_fakultas']."' ";
			$koneksi_db->sql_query($s);
			
			} else {
				$qf = $koneksi_db->sql_query( "SELECT * FROM m_fakultas where kode_fakultas='".$_REQUEST['kode_fakultas']."' limit 1 " );
				$totalf = $koneksi_db->sql_numrows($qf);
				if ($totalf > 0) { 
				echo '<div class=error>Kode Fakultas '.$_REQUEST['kode_fakultas'].' sudah dipakai</div>'; 
				} else {
	
				  $s = "INSERT INTO m_fakultas set 
						kode_fakultas='".$_REQUEST['kode_fakultas']."',
						kode_pt='".$_REQUEST['kode_pt']."',
						nama_fakultas='".$_REQUEST['nama_fakultas']."',
						pejabat='".$_REQUEST['pejabat']."',
						jabatan='".$_REQUEST['jabatan']."'
						";
				  $koneksi_db->sql_query($s);
				}
			}
		}
  DaftarKonsentrasi();
}

function edit_prodi() {
global $koneksi_db;
  $md = $_REQUEST['md']+0;
  if ($md == 0) {
    $id = $_REQUEST['pid'];
	$w = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM m_program_studi where kode_prodi='$id' limit 1 " ));
    $jdl = "Edit Data Program Studi";
	$kode = '<input name="kode_prodix"  disabled="disabled"  type="text" class="" id="" value="'.$w['kode_prodi'].'" />
		<input name="kode_prodi"  type="hidden" class="required" id="" value="'.$w['kode_prodi'].'" />
	';

  }
  else {
    $w = array();
    $jdl = "Tambah Program Studi";
	$kode = '<input name="kode_prodi"  type="text" class="required" id="" value="'.$w['kode_fakultas'].'" />
	';

  }

//$aktif = ($w['buka'] == 'Y') ? 'checked' : ''; 
	
echo '  
    <form action="" method="post"  class="cmxform" id="form_input" style="width:100%">
		<input type="hidden" name="id" value="'.$id.'"/>
         <input type="hidden" name="m" value="fakultas"/>
        <input type="hidden" name="op" value="simpan_prodi"/>
		<input type="hidden" name="md" value="'.$md.'"/>
       
		<fieldset class="ui-widget ui-widget-content ui-corner-all" >
            <legend class="ui-widget ui-widget-header ui-corner-all">'.$jdl.'</legend>
            &nbsp;<font color="red"><br></font>
            <table width="100%"  border="0" class="datatable full1">
                <tr>
                    <td width="150" align="right" valign="top">Fakultas<font color="red"> *</font></td>
                    <td  >
					<select name="kode_fak"  class="form-control " required    />'.opfak(''.$w['kode_fak'].'').'</select>
					</td>
                </tr>
                <tr>
                    <td align="right" valign="top">Kode Program Studi<font color="red"> *</font></td>
                    <td>
					'.$kode.'
					</td>
                </tr>

                <tr>
                    <td align="right" valign="top">Nama Program Studi<font color="red"> *</font></td>
                    <td>
					<input name="nama_prodi"  type="text" class="form-control " required id="" value="'.$w['nama_prodi'].'" />
					</td>
                </tr>
                <tr>
                    <td width="150" align="right" valign="top">Jenjang<font color="red"> *</font></td>
                    <td  >
					<select name="kode_jenjang"  class="form-control " required    />'.opAplikasi('04',''.$w['kode_jenjang'].'').'</select>
					</td>
                </tr>
                <tr>
                    <td align="right" valign="top">Gelar Akademik<font color="red"> *</font></td>
                    <td>
					<input name="gelar"  type="text" class=" form-control" required id="" value="'.$w['gelar'].'" /><i>singkatan</i><br/>
					<input name="gelar_panjang"  type="text" class="form-control " required full id="" value="'.$w['gelar_panjang'].'" />
					</td>
                </tr>	
                <tr>
                    <td align="right" valign="top">SKS Lulus<font color="red"> *</font></td>
                    <td>
					<input name="sks_lulus"  type="text" class="form-control number" required  id="" value="'.$w['sks_lulus'].'" />
					</td>
                </tr>
                <tr>
                    <td align="right" valign="top">Status Prodi<font color="red"> *</font></td>
                    <td>
					<select name="status_prodi"  class="form-control" required   />'.opAplikasi('14',''.$w['status_prodi'].'').'</select>
					</td>
                </tr>
				
                <tr>
                    <td align="right" valign="top">Tanggal Berdiri<font color="red"> *</font></td>
                    <td>
					<input name="tgl_awal_berdiri"  type="text" class="form-control tcal date"  required id="" value="'.$w['tgl_awal_berdiri'].'" />
					</td>
                </tr>
                <tr>
                    <td align="right" valign="top">Semester Awal<font color="red"> *</font></td>
                    <td>
					<select name="semester_awal"  class="form-control" required number   />'.optapel(''.$w['semester_awal'].'').'</select>
					</td>
                </tr>
                <tr>
                    <td align="right" valign="top">Mulai Semester<font color="red"> *</font></td>
                    <td>
					<select name="mulai_semester"  class="form-control" required number   />'.optapel(''.$w['mulai_semester'].'').'</select>
					</td>
                </tr>
                <tr>
                    <td width="150" align="right" valign="top">Frekuensi Kurikulum<font color="red"> *</font></td>
                    <td  >
					<select name="frekuensi_kurikulum"  class="form-control" required   />'.opAplikasi('29',''.$w['frekuensi_kurikulum'].'').'</select>
					</td>
                </tr>
                <tr>
                    <td width="150" align="right" valign="top">Pelaksanaan Kurikulum<font color="red"> *</font></td>
                    <td  >
					<select name="pelaksanaan_kurikulum"  class="form-control" required   />'.opAplikasi('30',''.$w['pelaksanaan_kurikulum'].'').'</select>
					</td>
                </tr>
	
	            <tr>
                    <td align="left" colspan="2" valign="top"><font color="#000066" size="+2">Sesi dan Batas Studi</font></td>
                </tr>
                <tr>
                    <td align="right"valign="top">Nama Sesi<font color="red"> *</font></td>
                    <td><input name="nama_sesi"  type="text" class="form-control" required id="" value="'.$w['nama_sesi'].'" /> <i>semester/cawu</i>	</td>
                </tr>
				<tr>
                    <td align="right"valign="top">Jumlah Sesi/Tahun<font color="red"> *</font></td>
                    <td><input name="jumlah_sesi"  type="text" class="form-control" required id="" value="'.$w['jumlah_sesi'].'" />	</td>
                </tr>
				<tr>
                    <td align="right"valign="top">Batas Sesi<font color="red"> *</font></td>
                    <td><input name="batas_sesi"  type="text" class="form-control" required id="" value="'.$w['batas_sesi'].'" />	</td>
                </tr>
			
                <tr>
                    <td align="left" colspan="2" valign="top"><font color="#000066" size="+2">Pejabat</font></td>
                </tr>
				
                <tr>
                    <td width="150" align="right" valign="top">Ketua Prodi<font color="red"> *</font></td>
                    <td  >
					<select name="ketua_prodi"  class="form-control"  required  />'.opdosen(''.$w['ketua_prodi'].'').'</select>
					</td>
                </tr>
                <tr>
                    <td align="right"valign="top">Nomor HP Ketua<font color="red"> *</font></td>
                    <td>
					<input name="hp_ketua"  type="text" class="form-control" required  id="" value="'.$w['hp_ketua'].'" />
					</td>
                </tr>
                <tr>
                    <td width="150" align="right" valign="top">Operator<font color="red"> *</font></td>
                    <td  >
					<select name="nama_operator"  class="form-control"  required  />'.opdosen(''.$w['nama_operator'].'').'</select>
					</td>
                </tr>
                <tr>
                    <td align="right"valign="top">Telepon Operator<font color="red"> *</font></td>
                    <td>
					<input name="telepon_operator"  type="text" class="form-control" required number id="" value="'.$w['telepon_operator'].'" />
					</td>
                </tr>

				
                <tr>
                    <td align="left" colspan="2" valign="top"><font color="#000066" size="+2">Surat Keputusan</font></td>
                </tr>
                <tr>
                    <td align="right"valign="top">Nomor SK Dikst<font color="red"> *</font></td>
                    <td>
					<input name="no_sk_dikti"  type="text" class="form-control required full" id="" value="'.$w['no_sk_dikti'].'" />
					</td>
                </tr>
                <tr>
                    <td align="right" valign="top">Tanggal Sk Dikti<font color="red"> *</font></td>
                    <td>
					<input name="tgl_sk_dikti"  type="text" class="form-control tcal date required" id="" value="'.$w['tgl_sk_dikti'].'" />
					</td>
                </tr>
                <tr>
                    <td align="right" valign="top">Tanggal Berakhir Sk Dikti<font color="red"> *</font></td>
                    <td>
					<input name="tgl_akhir_sk_dikti"  type="text" class="form-control tcal date required" id="" value="'.$w['tgl_akhir_sk_dikti'].'" />
					</td>
                </tr>
                <tr>
                    <td align="right"valign="top">Nomor SK BAN<font color="red"> *</font></td>
                    <td>
					<input name="no_sk_ban"  type="text" class="form-control required full" id="" value="'.$w['no_sk_ban'].'" />
					</td>
                </tr>
                <tr>
                    <td align="right" valign="top">Tanggal SK BAN<font color="red"> *</font></td>
                    <td>
					<input name="tgl_sk_ban"  type="text" class="form-control tcal date required" id="" value="'.$w['tgl_sk_ban'].'" />
					</td>
                </tr>
                <tr>
                    <td align="right" valign="top">Tanggal Berakhir SK BAN<font color="red"> *</font></td>
                    <td>
					<input name="tgl_akhir_sk_ban"  type="text" class="form-control tcal date required" id="" value="'.$w['tgl_akhir_sk_ban'].'" />
					</td>
                </tr>				
                <tr>
                    <td width="150" align="right" valign="top">Akreditasi<font color="red"> *</font></td>
                    <td  >
					<select name="kode_akreditasi"  class="form-control required"   />'.opAplikasi('07',''.$w['kode_akreditasi'].'').'</select>
					</td>
                </tr>


				
                <tr>
                    <td align="left" colspan="2" valign="top"><font color="#000066" size="+2">Alamat</font></td>
                </tr>
                <tr>
                    <td align="right" valign="top">Alamat<font color="red"></font></td>
                    <td>
					<textarea name="alamat" class="form-control required"  cols=40 rows=1>'.$w['alamat'].'</textarea>
					</td>
                </tr>
                <tr>
                    <td width="150" align="right" valign="top">Kota/Kabupaten<font color="red"> *</font></td>
                    <td  >
					<select name="kode_kota"  class="form-control required"   />'.opkota(''.$w['kode_kota'].'').'</select>
					</td>
                </tr>
                <tr>
                    <td width="150" align="right" valign="top">Propinsi<font color="red"> *</font></td>
                    <td  >
					<select name="kode_propinsi"  class="form-control required"   />'.oppropinsi(''.$w['kode_propinsi'].'').'</select>
					</td>
                </tr>
             	<tr>
                    <td align="right"valign="top">Pos<font color="red"> *</font></td>
                    <td>
					<input name="kode_pos"  type="text" class="form-control required number" id="" value="'.$w['kode_pos'].'" />
					</td>
                </tr>
             	<tr>
                    <td align="right"valign="top">Telepon<font color="red"> *</font></td>
                    <td>
					<input name="telepon"  type="text" class="form-control required number" id="" value="'.$w['telepon'].'" />
					</td>
                </tr>
             	<tr>
                    <td align="right"valign="top">Faximil<font color="red"> *</font></td>
                    <td>
					<input name="fax"  type="text" class="form-control required number" id="" value="'.$w['fax'].'" />
					</td>
                </tr>
             	<tr>
                    <td align="right"valign="top">Email<font color="red"> *</font></td>
                    <td>
					<input name="email"  type="text" class="form-control required full email" id="" value="'.$w['email'].'" />
					</td>
                </tr>
             	<tr>
                    <td align="right"valign="top">Website<font color="red"> *</font></td>
                    <td>
					<input name="website"  type="text" class= "form-control required full url"  id="" value="'.$w['website'].'" />
					</td>
                </tr>

                 <tr>
                    <td colspan="2">
                        <input type="submit" class="tombols ui-corner-all" value="Simpan"/> 
                        <input type="button" class="tombols ui-corner-all" value="Kembali" onClick="window.location = \'index.php?m='.$_GET['m'].'\'"/></td>
                  </tr>
            </table>
        </fieldset>
    </form>
 ';

}


////simpan /
function simpan_prodi() {
global $koneksi_db;
  $md = $_REQUEST['md']+0;
	$id = $_REQUEST['id'];  


	if (trim($_POST['kode_fak'])=="") {
		$pesan[] = "Form Kode Fakultas masih kosong, ulangi kembali";
	}
	else if (trim($_POST['kode_prodi'])=="") {
		$pesan[] = "Form Kode Prodi  masih kosong, ulangi kembali";
	}
	else if (trim($_POST['nama_prodi'])=="") {
		$pesan[] = "Form Nama Prodi masih kosong, ulangi kembali";
	}
	
	if (! count($pesan)==0 ) {
		echo "<div align='left'>";			
		echo "&nbsp; <b> Kesalahan Input : </b><br>";
		foreach ($pesan as $indeks=>$pesan_tampil) {
			$urut_pesan++;
			echo "<font color='#FF0000' align='left'>";
			echo "&nbsp; &nbsp;";
			echo "$urut_pesan . $pesan_tampil <br>";
			echo "</font>";
		}
		echo "</div><br>";
		echo "<meta http-equiv='refresh' content='3; url=index.php?m=40'>";
	} else {
		$w = $koneksi_db->sql_fetchrow($koneksi_db->sql_query( "SELECT * FROM m_fakultas where kode_fakultas='".$_REQUEST['kode_fak']."' limit 1 " ));
		$kodept = $w[1];
		


			if ($md == 0) {
			$s = "update m_program_studi set 
						kode_pt='".$kodept."',
						kode_fak='".$_REQUEST['kode_fak']."',
						kode_jenjang='".$_REQUEST['kode_jenjang']."',
						nama_prodi='".$_REQUEST['nama_prodi']."',
						alamat='".$_REQUEST['alamat']."',
						kode_kota='".$_REQUEST['kode_kota']."',
						kode_propinsi='".$_REQUEST['kode_propinsi']."',
						kode_pos='".$_REQUEST['kode_pos']."',
						telepon='".$_REQUEST['telepon']."',
						fax='".$_REQUEST['fax']."',
						email='".$_REQUEST['email']."',
						website='".$_REQUEST['website']."',
						sks_lulus='".$_REQUEST['sks_lulus']."',
						status_prodi='".$_REQUEST['status_prodi']."',
						tgl_awal_berdiri='".$_REQUEST['tgl_awal_berdiri']."',
						semester_awal='".$_REQUEST['semester_awal']."',
						mulai_semester='".$_REQUEST['mulai_semester']."',
						no_sk_dikti='".$_REQUEST['no_sk_dikti']."',
						tgl_sk_dikti='".$_REQUEST['tgl_sk_dikti']."',
						tgl_akhir_sk_dikti='".$_REQUEST['tgl_akhir_sk_dikti']."',
						kode_akreditasi='".$_REQUEST['kode_akreditasi']."',
						frekuensi_kurikulum='".$_REQUEST['frekuensi_kurikulum']."',
						pelaksanaan_kurikulum='".$_REQUEST['pelaksanaan_kurikulum']."',
						ketua_prodi='".$_REQUEST['ketua_prodi']."',
						hp_ketua='".$_REQUEST['hp_ketua']."',
						nama_operator='".$_REQUEST['nama_operator']."',
						telepon_operator='".$_REQUEST['telepon_operator']."',
						nama_sesi='".$_REQUEST['nama_sesi']."',
						jumlah_sesi='".$_REQUEST['jumlah_sesi']."',
						batas_sesi='".$_REQUEST['batas_sesi']."',
						gelar='".$_REQUEST['gelar']."',
						gelar_panjang='".$_REQUEST['gelar_panjang']."'
					where kode_prodi='".$_REQUEST['kode_prodi']."' ";
			$koneksi_db->sql_query($s);
			
			} else {
				$qf = $koneksi_db->sql_query( "SELECT * FROM m_program_studi where kode_prodi='".$_REQUEST['kode_prodi']."' limit 1 " );
				$totalf = $koneksi_db->sql_numrows($qf);
				if ($totalf > 0) { 
				echo '<div class=error>Kode Prodi '.$_REQUEST['kode_prodi'].' sudah dipakai</div>'; 
				} else {
	
				  $s = "INSERT INTO m_program_studi set 
						kode_prodi='".$_REQUEST['kode_prodi']."',
						kode_pt='".$kodept."',
						kode_fak='".$_REQUEST['kode_fak']."',
						kode_jenjang='".$_REQUEST['kode_jenjang']."',
						nama_prodi='".$_REQUEST['nama_prodi']."',
						alamat='".$_REQUEST['alamat']."',
						kode_kota='".$_REQUEST['kode_kota']."',
						kode_propinsi='".$_REQUEST['kode_propinsi']."',
						kode_pos='".$_REQUEST['kode_pos']."',
						telepon='".$_REQUEST['telepon']."',
						fax='".$_REQUEST['fax']."',
						email='".$_REQUEST['email']."',
						website='".$_REQUEST['website']."',
						sks_lulus='".$_REQUEST['sks_lulus']."',
						status_prodi='".$_REQUEST['status_prodi']."',
						tgl_awal_berdiri='".$_REQUEST['tgl_awal_berdiri']."',
						semester_awal='".$_REQUEST['semester_awal']."',
						mulai_semester='".$_REQUEST['mulai_semester']."',
						no_sk_dikti='".$_REQUEST['no_sk_dikti']."',
						tgl_sk_dikti='".$_REQUEST['tgl_sk_dikti']."',
						tgl_akhir_sk_dikti='".$_REQUEST['tgl_akhir_sk_dikti']."',
						no_sk_ban='".$_REQUEST['no_sk_ban']."',
						tgl_sk_ban='".$_REQUEST['tgl_sk_ban']."',
						tgl_akhir_sk_ban='".$_REQUEST['tgl_akhir_sk_ban']."',
						kode_akreditasi='".$_REQUEST['kode_akreditasi']."',
						frekuensi_kurikulum='".$_REQUEST['frekuensi_kurikulum']."',
						pelaksanaan_kurikulum='".$_REQUEST['pelaksanaan_kurikulum']."',
						ketua_prodi='".$_REQUEST['ketua_prodi']."',
						hp_ketua='".$_REQUEST['hp_ketua']."',
						nama_operator='".$_REQUEST['nama_operator']."',
						telepon_operator='".$_REQUEST['telepon_operator']."',
						nama_sesi='".$_REQUEST['nama_sesi']."',
						jumlah_sesi='".$_REQUEST['jumlah_sesi']."',
						batas_sesi='".$_REQUEST['batas_sesi']."',
						gelar='".$_REQUEST['gelar']."',
						gelar_panjang='".$_REQUEST['gelar_panjang']."'
						";
				  $koneksi_db->sql_query($s);
				}
			}
		}
			echo "<meta http-equiv='refresh' content='3; url=index.php?m=40'>";
  DaftarKonsentrasi();
  //echo $s;
}

function edit_kons() {
global $koneksi_db;
  $md = $_REQUEST['md']+0;
  if ($md == 0) {
    $id = $_REQUEST['kid'];
	$w = $koneksi_db->sql_fetchassoc($koneksi_db->sql_query( "SELECT * FROM m_konsentrasi where kode_konsentrasi='$id' limit 1 " ));
    $jdl = "Edit Data Konsentrasi";
	$kode = '<input name="kode_kons"  disabled="disabled"  type="text" class="" id="" value="'.$w['kode_konsentrasi'].'" />
		<input name="kode_konsentrasi"  type="hidden" class="required" id="" value="'.$w['kode_konsentrasi'].'" />
	';

  }
  else {
    $w = array();
    $jdl = "Tambah Konsentrasi";
	$kode = '<input name="kode_konsentrasi"  type="text" class="required" id="" value="'.$w['kode_konsentrasi'].'" />
	';

  }

//$aktif = ($w['buka'] == 'Y') ? 'checked' : ''; 
	
echo '  
    <form action="" method="post"  class="cmxform" id="form_input" style="width:100%">
		<input type="hidden" name="id" value="'.$id.'"/>
         <input type="hidden" name="m" value="fakultas"/>
        <input type="hidden" name="op" value="simpan_kons"/>
		<input type="hidden" name="md" value="'.$md.'"/>
       
		<fieldset class="ui-widget ui-widget-content ui-corner-all" >
            <legend class="ui-widget ui-widget-header ui-corner-all">'.$jdl.'</legend>
            &nbsp;<font color="red"><br></font>
            <table width="100%"  border="0" class="datatable full1">
                <tr>
                    <td width="150" align="right" valign="top">Program Studi<font color="red"> *</font></td>
                    <td  >
					<select name="kode_prodi"  class="form-control required"   />'.opprodi(''.$w['kode_prodi'].'').'</select>
					</td>
                </tr>
                <tr>
                    <td align="right" valign="top">Kode Konsentrasi<font color="red"> *</font></td>
                    <td>
					'.$kode.'
					</td>
                </tr>

                <tr>
                    <td align="right" valign="top">Nama Konsentrasi<font color="red"> *</font></td>
                    <td>
					<input name="nama_konsentrasi"  type="text" class="form-control required full" id="" value="'.$w['nama_konsentrasi'].'" />
					</td>
                </tr>
                 <tr>
                    <td colspan="2">
                        <input type="submit" class="tombols ui-corner-all" value="Simpan"/> 
                        <input type="button" class="tombols ui-corner-all" value="Kembali" onClick="window.location = \'index.php?m='.$_GET['m'].'\'"/></td>
                  </tr>
            </table>
        </fieldset>
    </form>
 ';

}


////simpan /
function simpan_kons() {
global $koneksi_db;
  $md = $_REQUEST['md']+0;
	$id = $_REQUEST['id'];  

	if (trim($_POST['kode_prodi'])=="") {
		$pesan[] = "Form Kode Program Studi masih kosong, ulangi kembali";
	}
	else if (trim($_POST['kode_konsentrasi'])=="") {
		$pesan[] = "Form Kode Konsentrasi masih kosong, ulangi kembali";
	}
	else if (trim($_POST['nama_konsentrasi'])=="") {
		$pesan[] = "Form Nama Konsentrasi masih kosong, ulangi kembali";
	}
	
	if (! count($pesan)==0 ) {
		echo "<div align='left'>";			
		echo "&nbsp; <b> Kesalahan Input : </b><br>";
		foreach ($pesan as $indeks=>$pesan_tampil) {
			$urut_pesan++;
			echo "<font color='#FF0000' align='left'>";
			echo "&nbsp; &nbsp;";
			echo "$urut_pesan . $pesan_tampil <br>";
			echo "</font>";
		}
		echo "</div><br>";
		echo "<meta http-equiv='refresh' content='3; url=index.php?m=fakultas&op=edit_kons&md=1'>";
	} else {
		$w = $koneksi_db->sql_fetchrow($koneksi_db->sql_query( "SELECT * FROM m_program_studi where kode_prodi='".$_REQUEST['kode_prodi']."' limit 1" ));
		$kodept = $w['kode_pt'];
		$kodefak = $w['kode_fak'];
		
			if ($md == 0) {
			$s = "update m_konsentrasi set 
					kode_pt='".$kodept."',
					kode_fak='".$kodefak."',
					kode_prodi='".$_REQUEST['kode_prodi']."',
					nama_konsentrasi='".$_REQUEST['nama_konsentrasi']."'
					where kode_konsentrasi='".$_REQUEST['kode_konsentrasi']."' ";
			$koneksi_db->sql_query($s);
			
			} else {
				$qf = $koneksi_db->sql_query( "SELECT * FROM m_konsentrasi where kode_konsentrasi='".$_REQUEST['kode_konsentrasi']."' limit 1 " );
				$totalf = $koneksi_db->sql_numrows($qf);
				if ($totalf > 0) { 
				echo '<div class=error>Kode Konsentrasi '.$_REQUEST['kode_konsentrasi'].' sudah dipakai</div>'; 
				} else {

				  $s = "INSERT INTO m_konsentrasi set 
						kode_konsentrasi='".$_REQUEST['kode_konsentrasi']."',
						kode_pt='".$kodept."',
						kode_fak='".$kodefak."',
						kode_prodi='".$_REQUEST['kode_prodi']."',
						nama_konsentrasi='".$_REQUEST['nama_konsentrasi']."' ";
				  $koneksi_db->sql_query($s);
				}
			}
		}
  DaftarKonsentrasi();
}

///////////
$fid = BuatSesi('fid');
$pid = BuatSesi('pid');
// *** Default ***

$go = (empty($_REQUEST['op'])) ? 'DaftarKonsentrasi' : $_REQUEST['op']; 
//$ki = DaftarFak();
//$ka = $go();
  
echo '<div id="title" align="right">
        <font style="color:#FF9900; font-size:30px;"><strong>.:</strong></font>
        <font style="font-size:18px; color:#999999">Fakultas, Program Studi dan Konsentrasi</font><br />
        	<a href="?m=home">Home</a> &raquo; <a href="index.php?m='.$_GET['m'].'">Fakultas</a>  &raquo; '.$go.'  
    </div>';
	  
echo '<div class="mainContentCell"><div class="content">';
echo '<table width=100%><tr><td width=50%>';
DaftarFak();
echo '</td><td width=5></td><td>';
DaftarProdi();
echo '</td><tr></table>';
echo '<br/>';
$go();
echo '</div></div>';
?>
 