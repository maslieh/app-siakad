<?php
function FilterBerita($module) {
  echo "<table class=box cellspacing=1 cellpadding=4 width=100%>
  <form action='' method=POST>
  <input type=hidden name='m' value='$module'>
	  <td  >Cari Berdasarkan </td>
	  <td >
		<select  name=\"kolom_berita\">";
		$arr = array(
		'Judul->judul',
		'Pengirim->user',
		'Tanggal->tgl',
		'Konten->konten'
		);
		
	  for ($i=0; $i<sizeof($arr); $i++) {
		$r = Explode('->', $arr[$i]);
		$cl = ($r[1] == $_SESSION[kolom_berita]) ? "selected" : "";
		echo "<option value=\"$r[1]\" $cl>$r[0]</option>";
	  }
		echo "</select>
	  </td>
	  <td><input type=text name='kunci_berita' value='$_SESSION[kunci_berita]' size=20 maxlength=20></td>
	  <td >
	  	<input type=submit name='cari' value='Cari'>
		<input type=submit name='reset_berita' value='Reset'>
	 </td>
  </tr>
  <tr><td class=inp1>FILTER :</td><td colspan=2><select name='topik' onChange='this.form.submit()'>".optopik($_SESSION['topik'])."</select></td></tr>
  </form></table></p>";
}

function FilterPustaka($module) {
  echo "<table class=box cellspacing=1 cellpadding=4 width=100%>
  <form action='' method=POST>
  <input type=hidden name='m' value='$module'>
	  <td >Cari Berdasarkan </td>
	  <td >
		<select  name=\"kolom_pustaka\">";
		$arr = array(
		'Judul->judul',
		'Keyword->keyword',
		'Tahun Terbit->tahun',
		'Abstraksi->abstraksi',
		'Fisik->keteranganfisik',
		'Keterangan->keterangan'
		);
		
	  for ($i=0; $i<sizeof($arr); $i++) {
		$r = Explode('->', $arr[$i]);
		$cl = ($r[1] == $_SESSION[kolom_pustaka]) ? "selected" : "";
		echo "<option value=\"$r[1]\" $cl>$r[0]</option>";
	  }
		echo "</select>
	  </td>
	  <td><input type=text name='kunci_pustaka' value='$_SESSION[kunci_pustaka]' size=20 maxlength=20></td>
	  <td >
	  	<input type=submit name='cari' value='Cari'>
		<input type=submit name='reset_pustaka' value='Reset'>
	 </td>
  </tr>
  <tr><td class=inp1>FILTER :</td><td colspan=2><select name='katalog' class=' btnfrm'  onChange='this.form.submit()'>".opkatalog($_SESSION['katalog'])."</select></td></tr>
  </form></table></p>";
}

function FilterPenulis($module) {
  // Tampilkan formulir
  echo "<table class=box cellspacing=1 cellpadding=4 >
  <form action='' method=POST>
  <input type=hidden name='m' value='$module'>
  <tr>
	  <td  >Cari Berdasarkan </td>
	  <td >
		<select  name=\"kolom_penulis\">";
		$arr = array(
		'Semua Data->',
		'Kode->kode',
		'Nama Penulis->nama',
		'Kontak->kontak',
		'Biografi->biografi'
		);
		
	  for ($i=0; $i<sizeof($arr); $i++) {
		$r = Explode('->', $arr[$i]);
		$cl = ($r[1] == $_SESSION[kolom_mahasiswa]) ? "selected" : "";
		echo "<option value=\"$r[1]\" $cl>$r[0]</option>";
	  }
		echo "</select>
	  </td>
	  <td><input type=text name='kunci_penulis' value='$_SESSION[kunci_penulis]' size=20 maxlength=20></td>
	  <td >
	  	<input type=submit name='cari' value='Cari'>
		<input type=submit name='reset_penulis' value='Reset'>
	 </td>
  </tr>
  </table></form><br/>";
  
}

function FilterPenerbit($module) {
  // Tampilkan formulir
  echo "<table class=box cellspacing=1 cellpadding=4 >
  <form action='' method=POST>
  <input type=hidden name='m' value='$module'>
  <tr>
	  <td >Cari Berdasarkan </td>
	  <td >
		<select  name=\"kolom_penerbit\">";
		$arr = array(
		'Semua Data->',
		'Kode->kode',
		'Nama Penerbit->nama',
		'Alamat->alamat',
		'Telepon->telpon',
		'Email->email',
		'Website->website',
		'Kontak->kontak'
		);
		
	  for ($i=0; $i<sizeof($arr); $i++) {
		$r = Explode('->', $arr[$i]);
		$cl = ($r[1] == $_SESSION[kolom_mahasiswa]) ? "selected" : "";
		echo "<option value=\"$r[1]\" $cl>$r[0]</option>";
	  }
		echo "</select>
	  </td>
	  <td><input type=text name='kunci_penerbit' value='$_SESSION[kunci_penerbit]' size=20 maxlength=20></td>
	  <td >
	  	<input type=submit name='cari' value='Cari'>
		<input type=submit name='reset_penerbit' value='Reset'>
	 </td>
  </tr>
  </table></form><br/>";
  
}
function FilterMahasiswa($module) {
  // Tampilkan formulir
  echo "<table class=box cellspacing=1 cellpadding=4 >
  <form action='' method=POST>
  <input type=hidden name='m' value='$module'>
  <tr>
	  <td >Cari Berdasarkan </td>
	  <td >
		<select  name=\"kolom_mahasiswa\">";
		$arr = array(
		'Semua Data->',
		'NIM->NIM',
		'Nama Mahasiswa->nama_mahasiswa',
		'Angkatan->tahun_masuk',
		'NIS Asal->nis_asal',
		'KTP->ktp',
		'Telephone->telephon',
		'HP->hp',
		'Alamat->alamat'
		);
		
	  for ($i=0; $i<sizeof($arr); $i++) {
		$r = Explode('->', $arr[$i]);
		$cl = ($r[1] == $_SESSION[kolom_mahasiswa]) ? "selected" : "";
		echo "<option value=\"$r[1]\" $cl>$r[0]</option>";
	  }
		echo "</select>
	  </td>
	  <td><input type=text name='kunci_mahasiswa' value='$_SESSION[kunci_mahasiswa]' size=20 maxlength=20></td>
	  <td >
	  	<input type=submit name='cari' value='Cari'>
		<input type=submit name='reset_mahasiswa' value='Reset'>
	 </td>
  </tr>
  </table></form><br/>";
  
}

function FilterDosen($module) {
  // Tampilkan formulir
  echo "<table class=box cellspacing=1 cellpadding=4 width=100%>
  <form action='' method=POST>
  <input type=hidden name='m' value='$module'>
  <tr>
	  <td >Cari Berdasarkan </td>
	  <td >
		<select  name=\"kolom_dosen\">";
		
		$arr = array(
		'Semua Data->',
		'NIP->NIDN',
		'Nama Dosen->nama_dosen',
		'Gelar Depan->gelar_depan',
		'Gelar Belakang->gelar_belakang',
		'KTP->ktp',
		'Telephone->telephon',
		'HP->hp',
		'Alamat->alamat'
		);
		
	  for ($i=0; $i<sizeof($arr); $i++) {
		$r = Explode('->', $arr[$i]);
		$cl = ($r[1] == $_SESSION[kolom_dosen]) ? "selected" : "";
		echo "<option value=\"$r[1]\" $cl>$r[0]</option>";
	  }
		echo "</select>
	  </td>
	  <td><input type=text name='kunci_dosen' value='$_SESSION[kunci_dosen]' size=20 maxlength=20></td>
	  <td >
	  	<input type=submit name='cari' value='Cari'>
		<input type=submit name='reset_dosen' value='Reset'>
	 </td>
  </tr>
  </table></form><br/>";
 
}

function FilterUser($module) {
  // Tampilkan formulir
  echo "<table class=box cellspacing=1 cellpadding=4 width=100%>
  <form action='' method=POST>
  <input type=hidden name='m' value='$module'>
  <tr>
	  <td  >Cari Berdasarkan </td>
	  <td >
		<select  name=\"kolom_user\">";
		
		$arr = array(
		'Semua Data->',
		'Username->username',
		'Nama Lengkap->nama',
		'Level->level'
		);
		
	  for ($i=0; $i<sizeof($arr); $i++) {
		$r = Explode('->', $arr[$i]);
		$cl = ($r[1] == $_SESSION[kolom_user]) ? "selected" : "";
		echo "<option value=\"$r[1]\" $cl>$r[0]</option>";
	  }
		echo "</select>
	  </td>
	  <td><input type=text name='kunci_user' value='$_SESSION[kunci_user]' size=20 maxlength=20></td>
	  <td >
	  	<input type=submit name='cari' value='Cari'>
		<input type=submit name='reset_user' value='Reset'>
	 </td>
  </tr>
  </table></form><br/>";
 
}


function FilterPaketKRS($module) {
global $prodi;
$krs_semester = $_SESSION['krs_semester'];
 echo "<table class=box cellspacing=1 cellpadding=4  >
  <form action='' method=POST>
  <input type=hidden name='m' value='$module'>
  <tr>
	  <td  >Paket KRS</td>
	  <td >
		<select  name=\"krs_semester\" onChange='this.form.submit()'>".oppaketkrs($prodi, $krs_semester)."</select>
	</td>
	<td >
	  	<input type=submit name='go' value='Go'>
	 </td>
	</tr>
	</table>
	</form>";
	  
}

function FilterPaketDiakui($module) {
global $prodi;
$krs_semester = $_SESSION['krs_semester'];
 echo "<table class=box cellspacing=1 cellpadding=4  >
  <form action='' method=POST>
  <input type=hidden name='m' value='$module'>
  <tr>
	  <td  >Matakuliah Diakui di</td>
	  <td >
		<select  name=\"krs_semester\" onChange='this.form.submit()'>".opsmtrmk($krs_semester)."</select>
	</td>
	<td >
	  	<input type=submit name='go' value='Go'>
	 </td>
	</tr>
	</table>
	</form>";
	  
}

function FilterPeriodeYudisium($module) {
global $prodi;
$periode_yudisium = $_SESSION['periode_yudisium'];
 echo "<table class=box cellspacing=1 cellpadding=4  >
  <form action='' method=POST>
  <input type=hidden name='m' value='$module'>
  <tr>
	  <td  >Periode Wisuda</td>
	  <td >
		<select  name=\"periode_yudisium\" onChange='this.form.submit()'>".opperiodeyudisium($periode_yudisium)."</select>
	</td>
	<td >
	  	<input type=submit name='go' value='Go'>
	 </td>
	</tr>
	</table>
	</form>";
	  
}

function FilterMataKuliah($prodi, $tahun_id, $module) {
$kode_mk = $_SESSION['kode_mk'];
 echo "<table class=box cellspacing=1 cellpadding=4  >
  <form action='' method=POST>
  <input type=hidden name='m' value='$module'>
  <tr>
	  <td  >Mata Kuliah</td>
	  <td  > 
		<select  name=\"kode_mk\" onChange='this.form.submit()'>".opmatakuliah($prodi, $tahun_id, $kode_mk)."</select>
	</td>
	<td  >
	  	<input type=submit name='go' value='Go'>
	 </td>
	</tr>
	</table>
	</form>";
}

function FilterMataKuliahDosen($prodi,$tahun_id, $user,$module) {
$kode_mk = $_SESSION['kode_mk'];
 echo "<table class=box cellspacing=1 cellpadding=4  >
  <form action='' method=POST>
  <input type=hidden name='m' value='$module'>
  <tr>
	  <td  >Mata Kuliah Dosen</td>
	  <td  > 
		<select  name=\"kode_mk\" onChange='this.form.submit()'>".opmatakuliahdosen($prodi, $tahun_id, $user, $kode_mk)."</select>
	</td>
	<td  >
	  	<input type=submit name='go' value='Go'>
	 </td>
	</tr>
	</table>
	</form>";
}


function FilterTahunGrafik($module) {
$tahungrafik = $_SESSION['tahungrafik'];
 echo "<table class=box cellspacing=1 cellpadding=4  >
  <form action='' method=POST>
  <input type=hidden name='m' value='$module'>
  <tr>
	  <td  >Tahun Akademik</td>
	  <td >
		<select  name=\"tahungrafik\" onChange='this.form.submit()'>".optapel(''.$tahungrafik.'')."</select>
	</td>
	<td >
	  	<input type=submit name='go' value='Go'>
	 </td>
	</tr>
	</table>
	</form>";
}

function FilterSemester($module) {
global $prodi;
$semester = $_SESSION['semester'];
 echo "<table class=box cellspacing=1 cellpadding=4  >
  <form action='' method=POST>
  <input type=hidden name='m' value='$module'>
  <tr>
	  <td  >Semester</td>
	  <td >
		<select  name=\"semester\" onChange='this.form.submit()'>".opsmtr(''.$semester.'')."</select>
	</td>
	<td >
	  	<input type=submit name='go' value='Go'>
	 </td>
	</tr>
	</table>
	</form>";
}

function FilterSemesterMK($module) {
global $prodi;
$semester = $_SESSION['semester'];
 echo "<table class=box cellspacing=1 cellpadding=4  >
  <form action='' method=POST>
  <input type=hidden name='m' value='$module'>
  <tr>
	  <td  >Semester</td>
	  <td >
		<select  name=\"semester\" onChange='this.form.submit()'>".opsmtrmk(''.$semester.'')."</select>
	</td>
	<td >
	  	<input type=submit value='Go'>
	 </td>
	</tr>
	</table>
	</form>";
}

function FilterRuang($module) {
global $prodi;
$kode_ruang = $_SESSION['kode_ruang'];
 echo "<table class=box cellspacing=1 cellpadding=4  >
  <form action='' method=POST>
  <input type=hidden name='m' value='$module'>
  <tr>
	  <td  >Ruang</td>
	  <td  > 
		<select  name=\"ruang\" onChange='this.form.submit()'>".opruang($prodi, $kode_ruang, '' )."</select>
	</td>
	<td  >
	  	<input type=submit name='go' value='Go'>
	 </td>
	</tr>
	</table>
	</form>";
	  
}

function FilterKelas($prodi, $tahun_id, $user, $module) {
$kelas = $_SESSION['kelas'];
 echo "<table class=box cellspacing=1 cellpadding=4  >
  <form action='' method=POST>
  <input type=hidden name='m' value='$module'>
  <tr>
	  <td >Kelas</td>
	  <td  > 
		<select  name=\"kelas\" onChange='this.form.submit()'>".opkelas($prodi, $tahun_id, $user, $kode_mk, $kelas)."</select>
	</td>
	<td  >
	  	<input type=submit name='go' value='Go'>
	 </td>
	</tr>
	</table>
	</form>";
	  
}


function FilterMhsKuliah($prodi, $tahun_id, $module) {
$idm = $_SESSION['idm'];
 echo "<table class=box cellspacing=1 cellpadding=4  >
  <form action='' method=POST>
  <input type=hidden name='m' value='$module'>
  <tr>
	  <td  >Nama Mahasiswa</td>
	  <td  > 
		<select  name=\"idm\" onChange='this.form.submit()'>".opmhskuliah($prodi, $tahun_id, $kode_mk, $kelas, $idm)."</select>
	</td>
	<td  >
	  	<input type=submit name='go' value='Go'>
	 </td>
	</tr>
	</table>
	</form>";
	  
}

function FilterKelasDosen($module) {
global $prodi, $user;
$kelas = $_SESSION['kelas'];
 echo "<table class=box cellspacing=1 cellpadding=4  >
  <form action='' method=POST>
  <input type=hidden name='m' value='$module'>
  <tr>
	  <td  >Kelas</td>
	  <td  > 
		<select  name=\"kelas\" onChange='this.form.submit()'>".opkelasdosen($prodi, $kelas, $user)."</select>
	</td>
	<td  >
	  	<input type=submit name='go' value='Go'>
	 </td>
	</tr>
	</table>
	</form>";
	  
}

function FilterPeriodePMB($module) {
global $prodi, $pmb_id;
$pmb_id = $_SESSION['pmb_id'];
 echo "<table class=box cellspacing=1 cellpadding=4 width=400 >
  <form action='' method=POST>
  <input type=hidden name='m' value='$module'>
  <tr>
	  <td  >Periode PMB</td>
	  <td >
		<select  name=\"pmb_id\" onChange='this.form.submit()'>".oppmb($pmb_id, $prodi)."</select>
	</td>
	<td >
	  	<input type=submit name='go' value='Go'>
	 </td>
	</tr>
	</table>
	</form>";
	  
}


function FilterMahasiswaPMB($module) {
  // Tampilkan formulir
  echo "<table class=box cellspacing=1 cellpadding=4 width=100%>
  <form action='' method=POST>
  <input type=hidden name='m' value='$module'>
  <tr>
	  <td >Cari Berdasarkan </td>
	  <td >
		<select  name=\"kolom_mahasiswa\">";
		
		$arr = array(
		'Semua Data->',
		'ID PMB->idmpmb',
		'Nama Mahasiswa->nama_mahasiswa',
		'Angkatan->tahun_masuk',
		'NIS Asal->nis_asal',
		'KTP->ktp',
		'Telephone->telephon',
		'HP->hp',
		'Alamat->alamat'
		);
		
	  for ($i=0; $i<sizeof($arr); $i++) {
		$r = Explode('->', $arr[$i]);
		$cl = ($r[1] == $_SESSION[kolom_mahasiswa]) ? "selected" : "";
		echo "<option value=\"$r[1]\" $cl>$r[0]</option>";
	  }
		echo "</select>
	  </td>
	  <td><input type=text name='kunci_mahasiswa' value='$_SESSION[kunci_mahasiswa]' size=20 maxlength=20></td>
	  <td >
	  	<input type=submit name='cari' value='Cari'>
		<input type=submit name='reset_mahasiswa' value='Reset'>
	 </td>
  </tr>
  </table></form><br/>";
}

function FilterSemesterMatakuliahPaket($prodi,$tahun_id, $module) {
$semester = $_SESSION['semester'];
 echo "<table class=box cellspacing=1 cellpadding=4  >
  <form action='' method=POST>
  <input type=hidden name='m' value='$module'>
  <tr>
	  <td  >Semester</td>
	  <td  > 
		<select  name=\"semester\" onChange='this.form.submit()'>".opsemestermatakuliahpaket($prodi, $tahun_id, $semester)."</select>
	</td>
	</tr>
	</table>
	</form>";
}

function FilterJenisUjian($module) {
global $jenisujian;
$jenisujian = $_SESSION['jenisujian'];
 echo "<table width='600px'  border='0' class='datatable full1'>
  <form action='' method=POST>
  <input type=hidden name='m' value='$module'>
  <tr>
	  <td  >Jenis Ujian</td>
	  <td >
		<select  name=\"jenisujian\" onChange='this.form.submit()'>".opjenisujian($jenisujian)."</select>
	</td>
	</tr>
	</table>
	</form>";
	  
}


function FilterAngkatan($module) {
 
 echo "<table class=box cellspacing=1 cellpadding=4  >
  <form action='' method=POST>
  <input type=hidden name='m' value='$module'>
  <tr>
	  <td >Angkatan</td>
	  <td >
		<select  name=\"angkatan\" onChange='this.form.submit()'>".optahun($_SESSION['angkatan'])."</select>
	</td>
	<td >
	  	<input type=submit name='go' value='Go'>
	 </td>
	</tr>
	</table>
	</form>";
}
?>