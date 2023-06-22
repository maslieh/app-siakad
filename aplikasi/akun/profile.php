<?php

if (!login_check()) {
    //alihkan user ke halaman logout
    logout();
    session_destroy();
    //echo '<div class="message"><div class="success"><h3>Logout SUKSES</h3></div></div>';
    echo '<meta http-equiv="refresh" content="0; url=index.php" />';
    //exit(0);
}

if (!cek_login()) {
    header('location:index.php');
    exit();
}

function Edit()
{
    global $koneksi_db, $row, $avatar;
    $idms = $_SESSION['idm'];
    if ($_SESSION['Level'] == 'MAHASISWA') {
        $we = $koneksi_db->sql_fetchassoc(
            $koneksi_db->sql_query(
                "SELECT * FROM m_mahasiswa where idm='" . $idms . "' limit 1 "
            )
        );
        $foto =
            $we['foto'] == ''
                ? 'gambar/no_avatars.gif'
                : 'gambar/' . $we['foto'] . '';

        echo '
		<table  border="0" cellspacing="1" class="datatable full" cellpadding="1">
		<thead>
		  <tr>
			<td width="149">NIM</td>
			<td width="436"><b>' .
            $row['NIM'] .
            '</b></td>
			<td width="37" valign="top" rowspan="4"><img src="' .
            $foto .
            '" width="90" height="120"></td>
		  </tr>
		  <tr>
			<td>NAMA</td>
			<td><b >' .
            $row['nama_mahasiswa'] .
            '</b></td>
		  </tr>
		  <tr>
			<td>PROGRAM STUDI </td>
			<td><b >' .
            viewprodi('' . $row['kode_prodi'] . '') .
            '</b ></td>
		  </tr>
		   <tr>
			<td>KONSENTRASI </td>
			<td><b >' .
            viewkonsentrasi('' . $row['kode_konsentrasi'] . '') .
            '</b ></td>
		  </tr>
		  </thead>
		</table>
	  ';

        echo '<ul class="nav nav-tabs">';
        echo '<li class="active"><a data-toggle="tab" href="#tab1">Biodata</a></li>';

        echo '</ul> ';

        echo '<form action="" method="post"  class="cmxform" id="form_input" style="width:100%"  enctype="multipart/form-data">
        <input type="hidden" name="m" value="' .
            $_GET['m'] .
            '"/>
        <input type="hidden" name="op"  value="simpanMHS"/>
		<input type="hidden" name="idm" value="' .
            $row['idm'] .
            '"/>';

        echo '<div class="tab-content">
  			<div id="tab1" class="tab-pane fade in active">';
        biodata_siswa();
        echo '</div>';
        //////////////////////
        echo '<div id="tab2" class="tab-pane fade">';
        //  alamat_siswa();
        echo '</div>';

        echo '<div id="tab3" class="tab-pane fade">';
        //  kontak_siswa();
        echo '</div>';
        echo '</div>';
        echo '<input type="submit" class="tombols ui-corner-all" value="Simpan"/> 
          <input type="button" class="tombols ui-corner-all" value="Kembali" onClick="window.location = \'index.php\'"/>
		  </form>';
    } else {
        echo '
		<table  border="0" cellspacing="1" class="datatable full" cellpadding="1">
		<thead>
		  <tr>
			<td width="149">NIDN</td>
			<td width="436"><b>' .
            $row['NIDN'] .
            '</b></td>
			<td width="37" valign="top" rowspan="4"><img src="' .
            $avatar .
            '" width="80" height="80"></td>
		  </tr>
		  <tr>
			<td>NAMA</td>
			<td><b >' .
            $row['gelar_depan'] .
            ' ' .
            $row['nama_dosen'] .
            ', ' .
            $row['gelar_belakang'] .
            '</b></td>
		  </tr>
		  <tr>
			<td>JABATAN</td>
			<td><b >' .
            viewAplikasi('02', '' . $row['jabatan_akademik'] . '') .
            '</b ></td>
		  </tr>
		  <tr>
			<td>PANGKAT/GOLONGAN</td>
			<td><b >' .
            viewAplikasi('56', '' . $row['pangkat_golongan'] . '') .
            '</b ></td>
		  </tr>
		  </thead>
		</table>
	  ';

        echo '<ul class="nav nav-tabs">';
        echo '<li class="active"><a data-toggle="tab" href="#tab1">Biodata</a></li>';
        echo '<li  ><a data-toggle="tab" href="#tab2">Alamat</a></li>';
        echo '<li  ><a data-toggle="tab" href="#tab3">Kontak</a></li>';
        echo '</ul> ';

        echo '<form action="" method="post"  class="cmxform" id="form_input" style="width:100%">
        <input type="hidden" name="m" value="' .
            $_GET['m'] .
            '"/>
        <input type="hidden" name="op"  value="simpan"/>
		<input type="hidden" name="idd" value="' .
            $row['idd'] .
            '"/>';

        echo '<div class="tab-content">
  			<div id="tab1" class="tab-pane fade in active">';
        biodata();
        echo '</div>';
        echo '<div id="tab2" class="tab-pane fade">';
        alamat();
        echo '</div>';
        echo '<div id="tab3" class="tab-pane fade">';
        kontak();
        echo '</div>';
        echo '</div>';
        echo '<input type="submit" class="tombols ui-corner-all" value="Simpan"/> 
          <input type="button" class="tombols ui-corner-all" value="Kembali" onClick="window.location = \'index.php\'"/>
		  </form>';
    }
}

function biodata_siswa()
{
    global $row;
    echo '  
       
<fieldset  class="ui-widget ui-widget-content ui-corner-all" >
          <legend class="ui-widget ui-widget-header ui-corner-all">Biodata Mahasiswa</legend>
	<table   border="0" class="datatable full1">
		<tr>
			<td align="left" valign="top">NIM<font color="red"> *</font></td>
			<td><input name="nim" disabled type="text" required   value="' .
        $row['NIM'] .
        '" /></td>
		</tr>
		<tr>
			<td align="left" valign="top">Nama Mahasiswa<font color="red"> *</font></td>
			<td><input name="nama_mahasiswa"  type="text" required   value="' .
        $row['nama_mahasiswa'] .
        '" /></td>
		</tr>
		<tr>
			<td align="left" valign="top">Tempat Lahir<font color="red"> *</font></td>
			<td><input name="tempat_lahir"  type="text" required   value="' .
        $row['tempat_lahir'] .
        '" /></td>
		</tr>
		<tr>
			<td align="left" valign="top">Tanggal Lahir<font color="red"> *</font></td>
			<td><input name="tanggal_lahir"  type="text" class="tcal date" required  value="' .
        $row['tanggal_lahir'] .
        '" /></td>
		</tr>				
		<tr>
			<td  align="left" valign="top">Jenis Kelamin<font color="red"> *</font></td>
			<td  ><select name="jenis_kelamin"  required   />' .
        opAplikasi('08', '' . $row['jenis_kelamin'] . '') .
        '</select></td>
		</tr>
		<tr>
			<td  align="left" valign="top">Agama<font color="red"> *</font></td>
			<td  ><select name="agama"  required    />' .
        opAplikasi('51', '' . $row['agama'] . '') .
        '</select></td>
		</tr>
		<tr>
			<td  align="left" valign="top">Warga Negara<font color="red"> *</font></td>
			<td  ><select name="warga_negara"  required    />' .
        opAplikasi('50', '' . $row['warga_negara'] . '') .
        '</select></td>
		</tr>
		<tr>
			<td  align="left" valign="top">Status Sipil<font color="red"> *</font></td>
			<td  ><select name="status_sipil"  required    />' .
        opAplikasi('52', '' . $row['status_sipil'] . '') .
        '</select></td>
		</tr>
		<tr>
			<td align="left" valign="top">Telepon<font color="red"> *</font></td>
			<td><input name="telepon"  type="text" required  value="' .
        $row['telepon'] .
        '" /></td>
		</tr>
		<tr>
			<td align="left" valign="top">HP<font color="red"> *</font></td>
			<td><input name="hp"  type="text"   value="' .
        $row['hp'] .
        '" /></td>
		</tr>
		<tr>
			<td align="left" valign="top">Email<font color="red"> *</font></td>
			<td><input name="email"  type="text"   value="' .
        $row['email'] .
        '" /></td>
		</tr>
		<tr>
			<td align="left" valign="top">Foto<font color="red"> *</font></td>
			<td><input name="gambar"  type="file"   /> <font color="red"><i>Ukuran Foto harus 4:3 tidak boleh lebih 2mb</i></font></td>
			
		</tr>	
      
            </table>
        </fieldset> ';
}
function alamat_siswa()
{
    global $row;

    echo '  
        <fieldset class="ui-widget ui-widget-content ui-corner-all" >
            <legend class="ui-widget ui-widget-header ui-corner-all">Alamat  ' .
        $row['nama_mahasiswa'] .
        '</legend>
            &nbsp;<font color="red"><br></font>
            <table    class="datatable full1">
               <tr>
                    <td  valign="top">Alamat<font color="red"></font></td>
                    <td>
					<textarea name="alamat_siswa" class="required"  cols=40 rows=1>' .
        $row['alamat'] .
        '</textarea>
					</td>
                </tr>
                <tr>
                    <td width="150"  valign="top">Kota/Kabupaten<font color="red"> *</font></td>
                    <td  >
					<select name="kode_kota_siswa"  class="required"   />' .
        opkota('' . $row['kode_kota'] . '') .
        '</select>
					</td>
                </tr>
                <tr>
                    <td width="150"  valign="top">Propinsi<font color="red"> *</font></td>
                    <td  >
					<select name="kode_propinsi_siswa"  class="required"   />' .
        oppropinsi('' . $row['kode_propinsi'] . '') .
        '</select>
					</td>
                </tr>
             	<tr>
                    <td valign="top">Pos<font color="red"> *</font></td>
                    <td>
					<input name="kode_pos_siswa"  type="text" class=" required number" id="" value="' .
        $row['kode_pos'] .
        '" />
					</td>
                </tr>
            </table>
        </fieldset> ';
}

function kontak_siswa()
{
    global $row;
    echo '  
        <fieldset class="ui-widget ui-widget-content ui-corner-all" >
            <legend class="ui-widget ui-widget-header ui-corner-all">Kontak ' .
        $row['nama_mahasiswa'] .
        '</legend>
            &nbsp;<font color="red"><br></font>
            <table class="table table-striped table-bordered table-hover"  >
              	<tr>
					<td align="left" valign="top">Telepon<font color="red"> *</font></td>
					<td><input name="telepon"  type="text" class="number required"  value="' .
        $row['telepon'] .
        '" /></td>
				</tr>
				<tr>
					<td align="left" valign="top">HP<font color="red"> *</font></td>
					<td><input name="hp"  type="text" class="number required"  value="' .
        $row['hp'] .
        '" /></td>
				</tr>
				<tr>
					<td align="left" valign="top">Email<font color="red"> *</font></td>
					<td><input name="email"  type="text" class="email required"  value="' .
        $row['email'] .
        '" /></td>
				</tr>			
            </table>
        </fieldset> ';
}

function biodata()
{
    global $row;
    echo '  
        <fieldset class="ui-widget ui-widget-content ui-corner-all" >
            <legend class="ui-widget ui-widget-header ui-corner-all">Biodata ' .
        $row['nama_dosen'] .
        '</legend>
            &nbsp;<font color="red"><br></font>
            <table   class="datatable full1">
                <tr>
                    <td align="left" valign="top">Nama Dosen<font color="red"> *</font></td>
                    <td><input name="nama_dosen"  type="text" class="full required"  value="' .
        $row['nama_dosen'] .
        '" /></td>
                </tr>
                <tr>
                    <td align="left" valign="top">Gelar Depan<font color="red"> *</font></td>
                    <td><input name="gelar_depan"  type="text" class=""  value="' .
        $row['gelar_depan'] .
        '" /></td>
                </tr>
                <tr>
                    <td align="left" valign="top">Gelar Belakang<font color="red"> *</font></td>
                    <td><input name="gelar_belakang"  type="text" class=""  value="' .
        $row['gelar_belakang'] .
        '" /></td>
                </tr>				
                <tr>
                    <td align="left" valign="top">Tempat Lahir<font color="red"> *</font></td>
                    <td><input name="tempat_lahir"  type="text" class="full required"  value="' .
        $row['tempat_lahir'] .
        '" /></td>
                </tr>
                <tr>
                    <td align="left" valign="top">Tanggal Lahir<font color="red"> *</font></td>
                    <td><input name="tanggal_lahir"  type="text" class="tcal date required"  value="' .
        $row['tanggal_lahir'] .
        '" /></td>
                </tr>				
                <tr>
                    <td  align="left" valign="top">Jenis Kelamin<font color="red"> *</font></td>
                    <td  ><select name="jenis_kelamin"  class="required"   />' .
        opAplikasi('08', '' . $row['jenis_kelamin'] . '') .
        '</select></td>
                </tr>
                <tr>
                    <td  align="left" valign="top">Agama<font color="red"> *</font></td>
                    <td  ><select name="agama"  class="required"   />' .
        opAplikasi('51', '' . $row['agama'] . '') .
        '</select></td>
                </tr>
            </table>
        </fieldset> ';
}

function alamat()
{
    global $row;

    echo '  
        <fieldset class="ui-widget ui-widget-content ui-corner-all" >
            <legend class="ui-widget ui-widget-header ui-corner-all">Alamat  ' .
        $row['nama_dosen'] .
        '</legend>
            &nbsp;<font color="red"><br></font>
            <table   class="datatable full1">
                <tr>
                    <td  valign="top">Alamat<font color="red"></font></td>
                    <td><textarea name="alamat" class="required"  cols=40 rows=1>' .
        $row['alamat'] .
        '</textarea>	</td>
                </tr>
                <tr>
                    <td width="150"  valign="top">Kota/Kabupaten<font color="red"> *</font></td>
                    <td  >	<select name="kode_kota"  class="required"   />' .
        opkota('' . $row['kode_kota'] . '') .
        '</select>	</td>
                </tr>
                <tr>
                    <td width="150"  valign="top">Propinsi<font color="red"> *</font></td>
                    <td  >	<select name="kode_propinsi"  class="required"   />' .
        oppropinsi('' . $row['kode_propinsi'] . '') .
        '</select>	</td>
                </tr>
             	<tr>
                    <td valign="top">Pos<font color="red"> *</font></td>
                    <td>	<input name="kode_pos"  type="text" class=" required number" id="" value="' .
        $row['kode_pos'] .
        '" />	</td>
                </tr>
            </table>
        </fieldset> ';
}

function kontak()
{
    global $row;
    echo '  
        <fieldset class="ui-widget ui-widget-content ui-corner-all" >
            <legend class="ui-widget ui-widget-header ui-corner-all">Kontak ' .
        $row['nama_dosen'] .
        '</legend>
            &nbsp;<font color="red"><br></font>
            <table   class="datatable full1">
                <tr>
                    <td align="left" valign="top">No KTP<font color="red"> *</font></td>
                    <td><input name="ktp"  type="text" class="required number"  value="' .
        $row['ktp'] .
        '" /></td>
                </tr>				
                <tr>
                    <td align="left" valign="top">No. Telepon<font color="red"> *</font></td>
                    <td><input name="telephon"  type="text" class="required number"  value="' .
        $row['telephon'] .
        '" /></td>
                </tr>				
                <tr>
                    <td align="left" valign="top">No. HP<font color="red"> *</font></td>
                    <td><input name="hp"  type="text" class="required number"  value="' .
        $row['hp'] .
        '" /></td>
                </tr>				
                <tr>
                    <td align="left" valign="top">Email<font color="red"> *</font></td>
                    <td><input name="email"  type="text" class="full required email"  value="' .
        $row['email'] .
        '" /></td>
                </tr>				
            </table>
        </fieldset> ';
}
function simpanMHS()
{
    global $koneksi_db, $w;
    $idm = $_REQUEST['idm'];
    $rand = rand();
    $ekstensi = ['png', 'jpg', 'jpeg', 'gif', 'JPG', 'PNG', 'JPEG'];
    $nama_file = $_FILES['gambar']['name'];
    $ukuran = $_FILES['gambar']['size'];
    $ext = pathinfo($nama_file, PATHINFO_EXTENSION);

    if ($nama_file != '') {
        if (!in_array($ext, $ekstensi)) {
            echo '<script language="javascript">';
            echo 'alert("Upload gagal, ekstensi harus jpeg, jpg, png,tif,JPG,PNG)';
            echo '</script>';
        } else {
            if ($ukuran < 2044070) {
                $xx = $rand . '_' . $nama_file;
                move_uploaded_file(
                    $_FILES['gambar']['tmp_name'],
                    'gambar/' . $rand . '_' . $nama_file
                );
                $s =
                    "update m_mahasiswa  set 
								
								nama_mahasiswa='" .
                    $_REQUEST['nama_mahasiswa'] .
                    "',
								warga_negara='" .
                    $_REQUEST['warga_negara'] .
                    "',
								status_sipil='" .
                    $_REQUEST['status_sipil'] .
                    "',
								agama='" .
                    $_REQUEST['agama'] .
                    "',
								jenis_kelamin='" .
                    $_REQUEST['jenis_kelamin'] .
                    "',
								tempat_lahir='" .
                    $_REQUEST['tempat_lahir'] .
                    "',
								tanggal_lahir='" .
                    $_REQUEST['tanggal_lahir'] .
                    "',
								telepon='" .
                    $_REQUEST['telepon'] .
                    "',
								hp='" .
                    $_REQUEST['hp'] .
                    "',
								foto='" .
                    $xx .
                    "',
								email='" .
                    $_REQUEST['email'] .
                    "'
								where idm='" .
                    $idm .
                    "' ";
                $koneksi_db->sql_query($s);
                echo '<script language="javascript">';
                echo 'alert("Upload foto berhasil")';
                echo '</script>';
            } else {
                echo '<script language="javascript">';
                echo 'alert("Upload gagal ukuran file terlalu besar, ukuran tidak boleh lebih dari 2MB")';
                echo '</script>';
            }
        }
    } else {
        $s =
            "update m_mahasiswa  set 
							
								nama_mahasiswa='" .
            $_REQUEST['nama_mahasiswa'] .
            "',
								warga_negara='" .
            $_REQUEST['warga_negara'] .
            "',
								status_sipil='" .
            $_REQUEST['status_sipil'] .
            "',
								agama='" .
            $_REQUEST['agama'] .
            "',
								jenis_kelamin='" .
            $_REQUEST['jenis_kelamin'] .
            "',
								tempat_lahir='" .
            $_REQUEST['tempat_lahir'] .
            "',
								tanggal_lahir='" .
            $_REQUEST['tanggal_lahir'] .
            "',
								telepon='" .
            $_REQUEST['telepon'] .
            "',
								hp='" .
            $_REQUEST['hp'] .
            "',
								email='" .
            $_REQUEST['email'] .
            "'
								where idm='" .
            $idm .
            "' ";
        $koneksi_db->sql_query($s);
    }

    $t =
        "update user set  
				nama='" .
        $_REQUEST['nama_mahasiswa'] .
        "', 
				email='" .
        $_REQUEST['email'] .
        "'
				where userid='" .
        $idm .
        "' ";
    $koneksi_db->sql_query($t);
    echo "<div  class='error'>Proses Menyimpan Data...</div>";
    echo "<meta http-equiv='refresh' content='3; url=index.php?m=" .
        $_GET['m'] .
        '&op=edit&sub=Biodata&idm=' .
        $_REQUEST['idm'] .
        "'>";
}

////////////////////////
function simpan()
{
    global $koneksi_db, $w;
    $idd = $_REQUEST['idd'];
    $s =
        "update m_dosen set 
				nama_dosen='" .
        $_REQUEST['nama_dosen'] .
        "',
				gelar_depan='" .
        $_REQUEST['gelar_depan'] .
        "',
				gelar_belakang='" .
        $_REQUEST['gelar_belakang'] .
        "',
				agama='" .
        $_REQUEST['agama'] .
        "',
				jenis_kelamin='" .
        $_REQUEST['jenis_kelamin'] .
        "',
				tempat_lahir='" .
        $_REQUEST['tempat_lahir'] .
        "',
				tanggal_lahir='" .
        $_REQUEST['tanggal_lahir'] .
        "',
				ktp='" .
        $_REQUEST['ktp'] .
        "',
				telephon='" .
        $_REQUEST['telephon'] .
        "',
				hp='" .
        $_REQUEST['hp'] .
        "',
				email='" .
        $_REQUEST['email'] .
        "',
				alamat='" .
        $_REQUEST['alamat'] .
        "',
				kode_kota='" .
        $_REQUEST['kode_kota'] .
        "',
				kode_propinsi='" .
        $_REQUEST['kode_propinsi'] .
        "',
				kode_pos='" .
        $_REQUEST['kode_pos'] .
        "'
				where idd='" .
        $_REQUEST['idd'] .
        "' ";
    $r = $koneksi_db->sql_query($s);
    echo "<div  class='error'>Proses Menyimpan Data...</div>";
    echo "<meta http-equiv='refresh' content='3; url=index.php?m=" .
        $_GET['m'] .
        "'>";
}

$go = empty($_REQUEST['op']) ? 'Edit' : $_REQUEST['op'];

echo '<div id="title" align="right">
        <font style="color:#FF9900; font-size:30px;"><strong>.:</strong></font>
        <font style="font-size:18px; color:#999999">Edit Profile</font><br />
        	<a href="index.php">Home</a> &raquo; <a href="index.php?m=' .
    $_GET['m'] .
    '">Profile</a>  &raquo; ' .
    $go .
    '  
    </div>';
echo '<div class="mainContentCell"><div class="content">';
$go();
echo '</div></div>';

?>
