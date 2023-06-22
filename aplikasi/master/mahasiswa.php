<?php

//ini_set('display_errors', 1);

if (!cek_login()) {
    header('location:index.php');
    exit();
}

if (!login_check()) {
    //alihkan user ke halaman logout
    logout();
    session_destroy();
    //echo '<div class="message"><div class="success"><h3>Logout SUKSES</h3></div></div>';
    echo '<meta http-equiv="refresh" content="0; url=index.php" />';
    //exit(0);
}

function hapus()
{
    echo "<div class=error>Ma'af.. Data tidak dapat dihapus, hanya bisa diedit</div>";
    Daftar();
}

function pilihmahasiswa($p)
{
    global $koneksi_db;
    $prodi = $_SESSION['prodi'];
    $opsi .= "<option value=\"\" >..::Pilih Mahasiswa::..</option>";
    $query = $koneksi_db->sql_query(
        "SELECT * FROM m_mahasiswa where kode_prodi='$prodi' "
    );
    while ($r = $koneksi_db->sql_fetchassoc($query)) {
        $cl = $r['NIM'] == $p ? 'selected' : '';
        $opsi .= "<option value=\"$r[NIM]\" $cl>$r[NIM]</option>";
    }
    return $opsi;
}

function Daftar()
{
    echo '<div class="row"><div class="col-md-4">';
    echo '</div><div class="col-md-8">';
    FilterMahasiswa($_GET['m']);
    echo '</div>
<div class="col-md-12">';
    echo "<input type=button  class=\"tombols ui-corner-all\" value='Tambah Mahasiswa' onclick=\"window.location.href='index.php?m=" .
        $_GET['m'] .
        "&op=add';\" >";

    echo '</div></div>';

    global $koneksi_db;
    $prodi = $_SESSION['prodi'];

    $whr = [];
    $ord = '';
    if (
        $_SESSION['reset_mahasiswa'] != 'Reset' &&
        !empty($_SESSION['kolom_mahasiswa']) &&
        !empty($_SESSION['kunci_mahasiswa'])
    ) {
        $whr[] = "$_SESSION[kolom_mahasiswa] like '%$_SESSION[kunci_mahasiswa]%' ";
        $ord = "order by $_SESSION[kolom_mahasiswa]";
        //$ord = ($_SESSION['kolom_mahasiswa'] =="" ) ? "NIM": $_SESSION['kolom_mahasiswa'];
    }

    $whr[] = "kode_prodi='$_SESSION[prodi]'";
    $whr[] = "nama_mahasiswa!=''";
    if (!empty($whr)) {
        $strwhr = 'where ' . implode(' and ', $whr);
    }

    require 'system/pagination_class.php';
    $sql = "select * from m_mahasiswa $strwhr $ord";
    if (isset($_GET['starting'])) {
        //starting page
        $starting = $_GET['starting'];
    } else {
        $starting = 0;
    }
    $n = $starting;
    $recpage = 20; //jumlah data yang ditampilkan per page(halaman)
    $obj = new pagination_class($koneksi_db, $sql, $starting, $recpage);
    $result = $obj->result;
    if ($koneksi_db->sql_numrows($result) != 0) {
        echo '<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover"  >
   	<thead>
     <tr>
	   <th width="5%" align="center">No</th>
	   <th align="center">NIM</th>
       <th align="center">Nama</th>
       <th align="center">Kontak</th>
       <th align="center">Tahun</th>
	   <th align="center">Status</th>
	   <th align="center">ID Mahasiswa</th>
	   <th align="center">Edit</th>
     </tr>
	 </thead>
	 <tbody>';

        while ($wr = $koneksi_db->sql_fetchassoc($result)) {
            $n++;
            $id = $wr['idm'];
            echo '<tr bgcolor="#f2f2f2">
				<td  align=center>' .
                $n .
                '</td> 
				<td  align=center>' .
                $wr['NIM'] .
                '</a></td>
				<td  align=left>' .
                $wr['nama_mahasiswa'] .
                '</td>
				<td  align=left>Tlp. ' .
                $wr['telepon'] .
                ', HP. ' .
                $wr['hp'] .
                ' Email.' .
                $wr['email'] .
                '</td>
				<td  align=left>' .
                $wr['tahun_masuk'] .
                '</td>
				<td  align=center>' .
                viewAplikasi('05', '' . $wr['status_aktif'] . '') .
                '</td>
				<td  align=left>' .
                $wr['idm'] .
                '</td>
				<td  align=center>
				
					<a href="#" class="btn btn-primary" onclick="window.location.href=\'index.php?m=' .
                $_GET['m'] .
                '&op=edit&idm=' .
                $id .
                '\';"><i class="fa fa-edit"></i></a>

				</td>
			</tr>';
        }

        echo '</tbody>
		</table></div>';

        echo $obj->total;
        echo '<br/>';
        echo $obj->anchors;
    } else {
        echo '<div class="alert alert-danger">Belum ada Data</th>
			</div>';
    }

    FormImport();?>&nbsp<?php
}

function add()
{
    global $koneksi_db, $tahun_id;
    $prodi = $_SESSION['prodi'];
    $w = $koneksi_db->sql_fetchassoc(
        $koneksi_db->sql_query(
            "SELECT * FROM m_program_studi where kode_prodi='" .
                $prodi .
                "' limit 1 "
        )
    );
    $kodept = $w['kode_pt'];
    $kodefak = $w['kode_fak'];
    $kodeprodi = $w['kode_prodi'];
    $kodejen = $w['kode_jenjang'];
    $wwe = $koneksi_db->sql_fetchassoc(
        $koneksi_db->sql_query(
            "SELECT * FROM m_tahun where tahun_id='" . $tahun_id . "' limit 1 "
        )
    );
    $tah = $wwe['tahun'];
    $ww = $koneksi_db->sql_fetchassoc(
        $koneksi_db->sql_query(
            "SELECT max(NIM) as kodeTerbesar FROM m_mahasiswa where kode_prodi='$kodeprodi' AND tahun_masuk='$tah'"
        )
    );
    $kodeBarang = $ww['kodeTerbesar'];
    $urutan = substr($kodeBarang, 7, 3);
    $huruf = $w['kode_prodi'];
    $no1 = $ww['kodeTerbesar'] + 1;
    $hun = substr($wwe['tahun'], 2);
    $kodeBarang = $huruf . $hun . sprintf('%00s', $urutan);
    $hasil = $urutan + 1;
    $pk_n = strlen($hasil);
    if ($pk_n == 1) {
        $jumlah_mhs = $huruf . $hun . '00' . $hasil;
    } elseif ($pk_n == 2) {
        $jumlah_mhs = $huruf . $hun . '0' . $hasil;
    } else {
        $jumlah_mhs = $huruf . $hun . $hasil;
    }

    echo '
<script type="text/javascript"> 
function ubahstatus(){
var tipe = document.getElementById("status_masuk").value;
		if (tipe == "B"){
			$(\'#asalsekolah\').show();
			$(\'#asalpt\').hide();
		}else if (tipe == "P"){ 
			$(\'#asalpt\').show();
			$(\'#asalsekolah\').hide();
		}
}
</script>
';
    $autokode = kdauto('m_mahasiswa', 'M');
    //	$nim = BuatNIM($kodeproi, $hun);
    $jdl = 'Tambah Data Mahasiswa';
    $kode =
        '<input name="kode_"  disabled="disabled"  type="text" class="" id="" value="' .
        $autokode .
        '" />
		<input name="idm"  type="hidden" required id="" value="' .
        $autokode .
        '" />
	';
    echo '<form action="" method="post"  class="cmxform" id="form_input" style="width:100%" enctype="multipart/form-data">
	<input type="hidden" name="m" value="mahasiswa"/>
	<input type="hidden" name="op" value="simpanadd"/>
	<input type="hidden" name="md" value="' .
        $md .
        '"/>
	<input type="hidden" name="idm" value="' .
        $idm .
        '"/> 
	<fieldset class="ui-widget ui-widget-content ui-corner-all" >
          <legend class="ui-widget ui-widget-header ui-corner-all">Biodata Mahasiswa Baru</legend>
	<table width="600"  border="0" class="datatable full1">
		<tr>
			<td align="left"  valign="top">ID Record<font color="red"> *</font></td>
			<td>' .
        $kode .
        '</td>
		</tr>

		<tr>
			<td align="left" valign="top">NIM<font color="red"> *</font></td>
			<td><input name="NIM"  type="text" required  value="' .
        $jumlah_mhs .
        '" /></td>
		</tr>
		<tr>
            <td align="left" valign="top">Penasehat Akademik<font color="red"> </font></td>
            <td >	<select name="pa"/>' .
        oppa('' . $wp['pa'] . '') .
        '</select>	</td>
        </tr>
		<tr>
			<td align="left" valign="top">Nama Mahasiswa<font color="red"> *</font></td>
			<td><input name="nama_mahasiswa"  type="text" required   value="' .
        $w['nama_mahasiswa'] .
        '" /></td>
		</tr>
		<tr>
			<td align="left" valign="top">Tempat Lahir<font color="red"> *</font></td>
			<td><input name="tempat_lahir"  type="text" required   value="' .
        $w['tempat_lahir'] .
        '" /></td>
		</tr>
		<tr>
			<td align="left" valign="top">Tanggal Lahir<font color="red"> *</font></td>
			<td><input name="tanggal_lahir"  type="text" class="tcal date" required  value="' .
        $w['tanggal_lahir'] .
        '" /></td>
		</tr>				
		<tr>
			<td  align="left" valign="top">Jenis Kelamin<font color="red"> *</font></td>
			<td  ><select name="jenis_kelamin"  required   />' .
        opAplikasi('08', '' . $w['jenis_kelamin'] . '') .
        '</select></td>
		</tr>
		<tr>
			<td  align="left" valign="top">Agama<font color="red"> *</font></td>
			<td  ><select name="agama"  required    />' .
        opAplikasi('51', '' . $w['agama'] . '') .
        '</select></td>
		</tr>
		<tr>
			<td  align="left" valign="top">Warga Negara<font color="red"> *</font></td>
			<td  ><select name="warga_negara"  required    />' .
        opAplikasi('50', '' . $w['warga_negara'] . '') .
        '</select></td>
		</tr>
		<tr>
			<td  align="left" valign="top">Status Sipil<font color="red"> *</font></td>
			<td  ><select name="status_sipil"  required    />' .
        opAplikasi('52', '' . $w['status_sipil'] . '') .
        '</select></td>
		</tr>
		<tr>
			<td align="left" valign="top">Telepon<font color="red"> </font></td>
			<td><input name="telepon"  type="text" /></td>
		</tr>
		<tr>
			<td align="left" valign="top">HP<font color="red"> </font></td>
			<td><input name="hp"  type="text"   value="' .
        $w['hp'] .
        '" /></td>
		</tr>
		<tr>
			<td align="left" valign="top">Email<font color="red"> </font></td>
			<td><input name="email"  type="text"  /></td>
		</tr>
		<tr>
			<td align="left" valign="top">Foto<font color="red"> *</font></td>
			<td><input name="gambar"  type="file"   /> <font color="red"><i>Ukuran Foto harus 4:3 tidak boleh lebih 2mb</i></font></td>
			
		</tr>
		
	</table> 
	</fieldset>
	<br/>
		<fieldset class="ui-widget ui-widget-content ui-corner-all" >
          <legend class="ui-widget ui-widget-header ui-corner-all">Data Akademik</legend>
	<table width="600"  border="0" class="datatable full1">
		<tr>
			<td  align="left" width=100  valign="top" style="width:20%">Program Studi<font color="red"> *</font></td>
			<td  ><select name="kode_prodi"  required    />' .
        opprodi('' . $prodi . '') .
        '</select></td>
		</tr>

		<tr>
			<td  align="left" valign="top">Status Masuk<font color="red"> *</font></td>
			<td  ><select name="status_masuk"  id="status_masuk"  onchange=ubahstatus() required   />' .
        opAplikasi('06', '' . $w['status_masuk'] . '') .
        '</select></td>
		</tr>
	
		</table> 
	</fieldset>
	<br/>
	<fieldset id="asalsekolah" style="display:none; class="ui-widget ui-widget-content ui-corner-all" >
          <legend class="ui-widget ui-widget-header ui-corner-all">Asal Sekolah</legend>
	<table   border="0" class="datatable full1">
		<tr>
            <td align="left" valign="top" style="width:20%">Nama Sekolah<font color="red"> *</font></td>

            <td  ><input name="SekolahID"  type="text" required  value="' .
        $w['SekolahID'] .
        '" /></td>
        </tr>	
		<tr>
			<td  align="left" valign="top">NIS<font color="red"> *</font></td>
			<td  ><input name="nis_asal"  type="text" required  value="' .
        $w['nis_asal'] .
        '" /></td>
		</tr>
		<tr>
			<td  align="left" valign="top">Nilai UN<font color="red"> *</font></td>
			<td  ><input name="nilai_un"  type="text" required  value="' .
        $w['nilai_un'] .
        '" /></td>
		</tr>
		</table> 
	</fieldset>
	<br/>
	
	<fieldset id="asalpt" style="display:none; class="ui-widget ui-widget-content ui-corner-all" >
          <legend class="ui-widget ui-widget-header ui-corner-all">Asal Perguruan Tinggi</legend>
	<table   border="0" class="datatable full1">
	
		<tr>
            <td align="left" valign="top" style="width:20%">Nama Perguruan Tinggi<font color="red"> *</font></td>
            
            <td  ><input name="PerguruanTinggiID"  type="text" class=""  value="' .
        $w['PerguruanTinggiID'] .
        '" /></td>
        </tr>	
		
		<tr>
			<td  align="left" valign="top">Jenjang<font color="red"> *</font></td>
			<td  ><select name="asal_jenjang"  style="width:250px" class=""   />' .
        opAplikasi('04', '' . $w['asal_jenjang'] . '') .
        '</select></td>
		</tr>
		<tr>
			<td  align="left" valign="top">Program Studi<font color="red"> *</font></td>
			<td  ><input name="asal_prodi"  type="text" class=""   value="' .
        $w['asal_prodi'] .
        '"/></td>
		</tr>	
		<tr>
			<td  align="left" valign="top">NIM Asal<font color="red"> *</font></td>
			<td  ><input name="nim_asal"  type="text" class=""  value="' .
        $w['nim_asal'] .
        '" /></td>
		</tr>
		<tr>
			<td  align="left" valign="top">SKS Diakui<font color="red"> *</font></td>
			<td  ><input name="sks_diakui"  type="text" class=""  value="' .
        $w['sks_diakui'] .
        '" /></td>
		</tr>
		</table> 
	</fieldset>
	<br/>	

	<input type="submit" class="tombols ui-corner-all" value="Simpan"/> 
	<input type="button" class="tombols ui-corner-all" value="Kembali" onClick="window.location = \'index.php?m=' .
        $_GET['m'] .
        '\'"/>
													

	</form>';
}

function edit()
{
    global $koneksi_db, $w;
    $idm = $_REQUEST['idm'];
    if (empty($idm) || !isset($idm)) {
        echo "<meta http-equiv='refresh' content='3; url=index.php?m=" .
            $_GET['m'] .
            "'>";
    }
    $w = $koneksi_db->sql_fetchassoc(
        $koneksi_db->sql_query(
            "SELECT * FROM m_mahasiswa where idm='" . $idm . "' limit 1 "
        )
    );
    $foto =
        $w['foto'] == ''
            ? 'gambar/no_avatars.gif'
            : 'gambar/' . $w['foto'] . '';
    $arrSub = [
        'Data Pribadi->Biodata',
        'Alamat->Alamat',
        'Orang Tua->Ortu',
        'Akademik->Akademik',
    ];

    echo '
	<div class="container">
	  <div class="row">
	      <div class="col-sm">
		<table  border="0" cellspacing="1"class="datatable full" cellpadding="1">
		<thead>
		  <tr>
			<td width="149">NIM</td>
			<td width="436"><b>' .
        $w['NIM'] .
        '</b></td>
			<td width="37" valign="top" rowspan="5"><img src="' .
        $foto .
        '" width="90" height="120"></td>
		  </tr>
		  <tr>
			<td>NAMA</td>
			<td><b >' .
        $w['nama_mahasiswa'] .
        '</b></td>
		  </tr>
		  <tr>
			<td>PROGRAM STUDI </td>
			<td><b >' .
        viewprodi('' . $w['kode_prodi'] . '') .
        '</b ></td>
		  </tr>
		   <tr>
			<td>KONSENTRASI </td>
			<td><b >' .
        viewkonsentrasi('' . $w['kode_konsentrasi'] . '') .
        '</b ></td>
		  </tr>

		  </thead>
		</table>
	  ';

    $sub = empty($_REQUEST['sub']) ? 'Biodata' : $_REQUEST['sub'];
    $cp = $sub == 'Pendidikan' ? 'class=current' : '';

    echo '<ul class="nav nav-tabs">';
    for ($i = 0; $i < sizeof($arrSub); $i++) {
        $mn = explode('->', $arrSub[$i]);
        $c = $mn[1] == $sub ? 'class="active"' : '';
        echo "<li $c><a  href='index.php?m=" .
            $_GET['m'] .
            "&op=edit&sub=$mn[1]&idm=$idm'><span>$mn[0]</span></a></li>";
    }
    if ($w['kode_jenjang'] == 'A') {
        echo "<li $cp><a  href='index.php?m=" .
            $_GET['m'] .
            "&op=edit&sub=Pendidikan&idm=$idm' title='Khusus Mahasiswa Jenjang S-3' >Pendidikan</a></li>";
    } else {
        echo "<li class='disabled'><a $cp href=#  title='Khusus Mahasiswa Jenjang S-3' >Pendidikan</a></li>";
    }

    echo '</ul>';
    echo '<div class="tab-content"><div class="tab-pane fade active in" ><br/>';
    $sub();
    echo '</div></div>
	</div></div></div>';
}

function Biodata()
{
    global $w;
    echo '
<form action="" method="post"  class="cmxform" id="form_input" style="width:100%"  enctype="multipart/form-data">
        <input type="hidden" name="m" value="mahasiswa"/>
        <input type="hidden" name="op" value="edit"/>
		<input type="hidden" name="sub" value="biodataSav"/>
		<input type="hidden" name="md" value="' .
        $md .
        '"/>
		<input type="hidden" name="idm" value="' .
        $w['idm'] .
        '"/> 
<fieldset  class="ui-widget ui-widget-content ui-corner-all" >
          <legend class="ui-widget ui-widget-header ui-corner-all">Biodata Mahasiswa</legend>
	<table   border="0" class="datatable full1">
		<tr>
			<td align="left" valign="top">NIM<font color="red"> *</font></td>
			<td><input name="NIM"  type="text" readonly required  value="' .
        $w['NIM'] .
        '" /></td>
		</tr>
	<tr>
			<td align="left" width="150" valign="top">Nama Calon Mahasiswa<font color="red"> *</font></td>
			<td><input name="nama_mahasiswa"  type="text" class="full required"  value="' .
        $w['nama_mahasiswa'] .
        '" /></td>
		</tr>
		<tr>
			<td align="left" valign="top">Tempat Lahir<font color="red"> *</font></td>
			<td><input name="tempat_lahir"  type="text" class="full required"  value="' .
        $w['tempat_lahir'] .
        '" /></td>
		</tr>
		<tr>
			<td align="left" valign="top">Tanggal Lahir<font color="red"> *</font></td>
			<td><input name="tanggal_lahir"  type="text" class="tcal date required"  value="' .
        $w['tanggal_lahir'] .
        '" /></td>
		</tr>				
		<tr>
			<td  align="left" valign="top">Jenis Kelamin<font color="red"> *</font></td>
			<td  ><select name="jenis_kelamin"  class="required"   />' .
        opAplikasi('08', '' . $w['jenis_kelamin'] . '') .
        '</select></td>
		</tr>
		<tr>
			<td  align="left" valign="top">Agama<font color="red"> *</font></td>
			<td  ><select name="agama"  class="required "   />' .
        opAplikasi('51', '' . $w['agama'] . '') .
        '</select></td>
		</tr>
        
		<tr>
			<td  align="left" valign="top">Status Sipil<font color="red"> *</font></td>
			<td  ><select name="status_sipil"  class="required "   />' .
        opAplikasi('52', '' . $w['status_sipil'] . '') .
        '</select></td>
		</tr>
        	<tr>
			<td align="left" valign="top">Nama Ibu<font color="red"> *</font></td>
			<td><input name="nama_ibu"  type="text" class="full required"  value="' .
        $w['nama_ibu'] .
        '" /></td>
		</tr>
        <tr>
        <td  align="left" valign="top">&nbsp;</font></td>
        <td><hr><h3>ALAMAT</h3> <hr></td>
        
        </tr>
        	<tr>
			<td align="left" valign="top">NIK<font color="red"> *</font></td>
			<td><input name="nik"  type="text" class="full required"  value="' .
        $w['nik'] .
        '" /></td>
		</tr>
		<tr>
			<td  align="left" valign="top">Warga Negara<font color="red"> *</font></td>
			<td  ><select name="warga_negara"  class="required "   />' .
        oppwn('' . $w['warga_negara'] . '') .
        '</select></td>
		</tr>
        	<tr>
			<td align="left" valign="top">Jalan<font color="red"> *</font></td>
			<td><input name="jalan"  type="text" class="full required"  value="' .
        $w['jalan'] .
        '" /></td>
		</tr>
        	<tr>
			<td align="left" valign="top">Dusun/komplek/kampung<font color="red"> *</font></td>
			<td><input name="dusun"  type="text" class="full required"  value="' .
        $w['dusun'] .
        '" /></td>
		</tr>
        	<tr>
			<td align="left" valign="top">RT/RT<font color="red"> *</font></td>
			<td><input name="rt"  type="text" class="full required"  value="' .
        $w['rt'] .
        '" /> / <input name="rw"  type="text" class="full required"  value="' .
        $w['rw'] .
        '" /></td>
		</tr>
        </tr>
        	<tr>
			<td align="left" valign="top">Kelurahan<font color="red"> *</font></td>
			<td><input name="kelurahan"  type="text" class="full required"  value="' .
        $w['kelurahan'] .
        '" /></td>
		</tr>
        <tr>
			<td  align="left" valign="top">Kecamatan<font color="red"> *</font></td>
			<td  ><select name="kecamatan"  class="required "   />' .
        kecamatan('' . $w['kecamatan'] . '') .
        '</select></td>
		</tr>
        <tr>
			<td align="left" valign="top">Kode Pos<font color="red"> *</font></td>
			<td><input name="kode_pos"  type="text" class="full required"  value="' .
        $w['kode_pos'] .
        '" /></td>
		</tr>
        <tr>
			<td  align="left" valign="top">Alat transport<font color="red"> *</font></td>
			<td  ><select name="kendaraan"  class="required "   />' .
        opAplikasi('99', '' . $w['kendaraan'] . '') .
        '</select></td>
		</tr>
        <tr>
			<td  align="left" valign="top">Jenis Tinggal<font color="red"> *</font></td>
			<td  ><select name="jns_tinggal"  class="required "   />' .
        opAplikasi('98', '' . $w['jns_tinggal'] . '') .
        '</select></td>
		</tr>
        <tr>
        <td  align="left" valign="top">&nbsp;</font></td>
        <td><hr><h3>KONTAK</h3> <hr></td>
        
        </tr>
		<tr>
			<td align="left" valign="top">Telepon<font color="red"> *</font></td>
			<td><input name="telepon"  type="text" class="number required"  value="' .
        $w['telepon'] .
        '" /></td>
		</tr>
		<tr>
			<td align="left" valign="top">HP<font color="red"> *</font></td>
			<td><input name="hp"  type="text" class="number required"  value="' .
        $w['hp'] .
        '" /></td>
		</tr>
		<tr>
			<td align="left" valign="top">Email<font color="red"> *</font></td>
			<td><input name="email"  type="text" class="email required"  value="' .
        $w['email'] .
        '" /></td>
		</tr>	
		<tr>
			<td align="left" valign="top">Foto<font color="red"> *</font></td>
			<td><input name="gambar"  type="file"   /> <font color="red"><i>Ukuran Foto harus 4:3 tidak boleh lebih 2mb</i></font></td>
			
		</tr>			
                 <tr>
                    <td colspan="2">
                        <input type="submit" class="tombols name="simpan" ui-corner-all" value="Simpan"/> 
                        <input type="button" class="tombols ui-corner-all" value="Kembali" onClick="window.location = \'index.php?m=' .
        $_GET['m'] .
        '\'"/></td>
                  </tr>													
	</table>
</fieldset></form> ';
}

function Alamat()
{
    global $w;
    echo ' 
<form action="" method="post"  class="cmxform" id="form_input" style="width:100%">
        <input type="hidden" name="m" value="mahasiswa"/>
        <input type="hidden" name="op" value="edit"/>
		<input type="hidden" name="sub" value="alamatSav"/>
		<input type="hidden" name="md" value="' .
        $md .
        '"/>
		<input type="hidden" name="idm" value="' .
        $w['idm'] .
        '"/>
	<fieldset  class="ui-widget ui-widget-content ui-corner-all" >
          <legend class="ui-widget ui-widget-header ui-corner-all">Alamat Mahasiswa</legend>
            <table width="600"  border="0" class="datatable full1">
                <tr>
                    <td  valign="top">Alamat<font color="red">*</font></td>
                    <td>
					<textarea name="alamat" required  cols=40 rows=1>' .
        $w['alamat'] .
        '</textarea>
					</td>
                </tr>
                <tr>
                    <td   valign="top">Kota/Kabupaten<font color="red"> *</font></td>
                    <td  >
					<select name="kode_kota"  required   />' .
        opkota('' . $w['kode_kota'] . '') .
        '</select>
					</td>
                </tr>
                <tr>
                    <td width="150"  valign="top">Propinsi<font color="red"> *</font></td>
                    <td  >
					<select name="kode_propinsi"  required   />' .
        oppropinsi('' . $w['kode_propinsi'] . '') .
        '</select>
					</td>
                </tr>
             	<tr>
                    <td valign="top">Pos<font color="red"> *</font></td>
                    <td>
					<input name="kode_pos"  type="text" required id="" value="' .
        $w['kode_pos'] .
        '" />
					</td>
                </tr>
                 <tr>
                    <td colspan="2">
                        <input type="submit" class="tombols ui-corner-all" value="Simpan"/> 
                        <input type="button" class="tombols ui-corner-all" value="Kembali" onClick="window.location = \'index.php?m=' .
        $_GET['m'] .
        '\'"/></td>
                  </tr>			
            </table>
		</fieldset><br/>';
}

function Ortu()
{
    global $koneksi_db, $w;
    $a = $koneksi_db->sql_fetchassoc(
        $koneksi_db->sql_query(
            "SELECT * FROM t_mahasiswa_ortu where hubungan='AYAH' and idm='" .
                $w['idm'] .
                "' limit 1 "
        )
    );
    $i = $koneksi_db->sql_fetchassoc(
        $koneksi_db->sql_query(
            "SELECT * FROM t_mahasiswa_ortu where hubungan='IBU' and idm='" .
                $w['idm'] .
                "' limit 1 "
        )
    );
    echo '  
<form action="" method="post"  class="cmxform" id="form_input" style="width:100%">
        <input type="hidden" name="m" value="mahasiswa"/>
        <input type="hidden" name="op" value="edit"/>
		<input type="hidden" name="sub" value="ortuSav"/>
		<input type="hidden" name="md" value="' .
        $md .
        '"/>
		<input type="hidden" name="idm" value="' .
        $w['idm'] .
        '"/>
	<fieldset  class="ui-widget ui-widget-content ui-corner-all" >
          <legend class="ui-widget ui-widget-header ui-corner-all">Biodata Ayah</legend>
            <table width="600"  border="0" class="datatable full1">
				<tr>
					<td align="left" valign="top">Nama<font color="red"> *</font></td>
					<td><input name="a_nama"  type="text" required   value="' .
        $a['nama'] .
        '" /></td>
				</tr>
				<tr>
					<td  align="left" valign="top">Agama<font color="red"> *</font></td>
					<td  ><select name="a_agama"  required    />' .
        opAplikasi('51', '' . $a['agama'] . '') .
        '</select></td>
				</tr>
				<tr>
					<td  align="left" valign="top">Pendidikan<font color="red"> *</font></td>
					<td  ><select name="a_pendidikan"  required    />' .
        opAplikasi('1', '' . $a['pendidikan'] . '') .
        '</select></td>
				</tr>
				<tr>
					<td  align="left" valign="top">Pekerjaan<font color="red"> *</font></td>
					<td  ><select name="a_pekerjaan"  required    />' .
        opAplikasi('55', '' . $a['pekerjaan'] . '') .
        '</select></td>
				</tr>
				<tr>
					<td  align="left" valign="top">Penghasilan<font color="red"> *</font></td>
					<td  ><select name="a_penghasilan"  required    />' .
        opAplikasi('69', '' . $a['penghasilan'] . '') .
        '</select></td>
				</tr>								
				<tr>
					<td  align="left" valign="top">Status<font color="red"> *</font></td>
					<td  ><select name="a_hidup"  required    />' .
        opAplikasi('53', '' . $a['hidup'] . '') .
        '</select></td>
				</tr>							
                <tr>
                    <td  valign="top">Alamat<font color="red"></font></td>
                    <td>
					<textarea name="a_alamat" required  cols=40 rows=1>' .
        $a['alamat'] .
        '</textarea>
					</td>
                </tr>
                <tr>
                    <td width="150"  valign="top">Kota/Kabupaten<font color="red"> *</font></td>
                    <td  >
					<select name="a_kota"  required   />' .
        opkota('' . $a['kota'] . '') .
        '</select>
					</td>
                </tr>
                <tr>
                    <td width="150"  valign="top">Propinsi<font color="red"> *</font></td>
                    <td  >
					<select name="a_propinsi"  required   />' .
        oppropinsi('' . $a['propinsi'] . '') .
        '</select>
					</td>
                </tr>
             	<tr>
                    <td valign="top">Pos<font color="red"> *</font></td>
                    <td>
					<input name="a_pos"  type="text" required id="" value="' .
        $a['pos'] .
        '" />
					</td>
                </tr>
				<tr>
					<td align="left" valign="top">Telepon<font color="red"> *</font></td>
					<td><input name="a_telepon"  type="text" required  value="' .
        $a['telepon'] .
        '" /></td>
				</tr>
				<tr>
					<td align="left" valign="top">HP<font color="red"> *</font></td>
					<td><input name="a_hp"  type="text" required  value="' .
        $a['hp'] .
        '" /></td>
				</tr>
				<tr>
					<td align="left" valign="top">Email<font color="red"> *</font></td>
					<td><input name="a_email"  type="text" required  value="' .
        $a['email'] .
        '" /></td>
				</tr>	
							
            </table>
		</fieldset><br/>';

    echo '  
	<fieldset  class="ui-widget ui-widget-content ui-corner-all" >
          <legend class="ui-widget ui-widget-header ui-corner-all">Biodata Ibu</legend>
            <table width="600"  border="0" class="datatable full1">
				<tr>
					<td align="left" valign="top">Nama<font color="red"> *</font></td>
					<td><input name="i_nama"  type="text" required   value="' .
        $i['nama'] .
        '" /></td>
				</tr>
				<tr>
					<td  align="left" valign="top">Agama<font color="red"> *</font></td>
					<td  ><select name="i_agama"  required    />' .
        opAplikasi('51', '' . $i['agama'] . '') .
        '</select></td>
				</tr>
				<tr>
					<td  align="left" valign="top">Pendidikan<font color="red"> *</font></td>
					<td  ><select name="i_pendidikan"  required    />' .
        opAplikasi('1', '' . $i['pendidikan'] . '') .
        '</select></td>
				</tr>
				<tr>
					<td  align="left" valign="top">Pekerjaan<font color="red"> *</font></td>
					<td  ><select name="i_pekerjaan"  required    />' .
        opAplikasi('55', '' . $i['pekerjaan'] . '') .
        '</select></td>
				</tr>
				<tr>
					<td  align="left" valign="top">Penghasilan<font color="red"> *</font></td>
					<td  ><select name="i_penghasilan"  required    />' .
        opAplikasi('69', '' . $i['penghasilan'] . '') .
        '</select></td>
				</tr>								
				<tr>
					<td  align="left" valign="top">Status<font color="red"> *</font></td>
					<td  ><select name="i_hidup"  required    />' .
        opAplikasi('53', '' . $i['hidup'] . '') .
        '</select></td>
				</tr>							
                <tr>
                    <td  valign="top">Alamat<font color="red">*</font></td>
                    <td>
					<textarea name="i_alamat" required  cols=40 rows=1>' .
        $i['alamat'] .
        '</textarea>
					</td>
                </tr>
                <tr>
                    <td width="150"  valign="top">Kota/Kabupaten<font color="red"> *</font></td>
                    <td  >
					<select name="i_kota"  required   />' .
        opkota('' . $i['kota'] . '') .
        '</select>
					</td>
                </tr>
                <tr>
                    <td width="150"  valign="top">Propinsi<font color="red"> *</font></td>
                    <td  >
					<select name="i_propinsi"  required   />' .
        oppropinsi('' . $i['propinsi'] . '') .
        '</select>
					</td>
                </tr>
             	<tr>
                    <td valign="top">Pos<font color="red"> *</font></td>
                    <td>
					<input name="i_pos"  type="text" required id="" value="' .
        $i['pos'] .
        '" />
					</td>
                </tr>
				<tr>
					<td align="left" valign="top">Telepon<font color="red"> *</font></td>
					<td><input name="i_telepon"  type="text" required  value="' .
        $i['telepon'] .
        '" /></td>
				</tr>
				<tr>
					<td align="left" valign="top">HP<font color="red"> *</font></td>
					<td><input name="i_hp"  type="text" required  value="' .
        $i['hp'] .
        '" /></td>
				</tr>
				<tr>
					<td align="left" valign="top">Email<font color="red"> *</font></td>
					<td><input name="i_email"  type="text" required  value="' .
        $i['email'] .
        '" /></td>
				</tr>				
            </table>
		</fieldset><br/>';
    echo ' <input type="submit" class="tombols ui-corner-all" value="Simpan"/> 
       <input type="button" class="tombols ui-corner-all" value="Kembali" onClick="window.location = \'index.php?m=' .
        $_GET['m'] .
        '\'"/></form>';
}

function Akademik()
{
    global $w;

    echo ' 
<form action="" method="post"  class="cmxform" id="form_input" style="width:100%">
        <input type="hidden" name="m" value="mahasiswa"/>
        <input type="hidden" name="op" value="edit"/>
		<input type="hidden" name="sub" value="akademikSav"/>
		<input type="hidden" name="md" value="' .
        $md .
        '"/>
		<input type="hidden" name="idm" value="' .
        $w['idm'] .
        '"/>  ';
    echo '
	<fieldset id="asalpt"  class="ui-widget ui-widget-content ui-corner-all" >
          <legend class="ui-widget ui-widget-header ui-corner-all">Data Akademik</legend>
		<table width="600"  border="0" class="datatable full1">
		<tr>
			<td  align="left"   valign="top">Program Studi<font color="red"> *</font></td>
			<td  ><select name="kode_prodi"  required    />' .
        opprodi('' . $w['kode_prodi'] . '') .
        '</select></td>
		</tr>
		<tr>
			<td  align="left"   valign="top">Konsentrasi<font color="red"> </font></td>
			<td  ><select name="kode_konsentrasi"  class=""   />' .
        opkonsentrasi('' . $w['kode_konsentrasi'] . '') .
        '</select></td>
		</tr>
		<tr>
			<td  align="left" valign="top">Status Masuk<font color="red"> *</font></td>
			<td  ><select name="status_masuk"  id="status_masuk" disabled  required   />' .
        opAplikasi('06', '' . $w['status_masuk'] . '') .
        '</select></td>
		</tr>
		<tr>
			<td  align="left" valign="top">Tahun Masuk<font color="red"> *</font></td>
			<td  ><select name="tahun_masuk"  required   />' .
        optahun('' . $w['tahun_masuk'] . '') .
        '</select></td>
		</tr>	
		<tr>
			<td  align="left" valign="top">Semester Masuk<font color="red"> *</font></td>
			<td  ><select name="semester_masuk"  required   />' .
        optapel('' . $w['semester_masuk'] . '') .
        '</select></td>
		</tr>	
		<tr>
			<td align="left" valign="top">Tanggal Masuk<font color="red"> *</font></td>
			<td><input name="tanggal_masuk"  type="text" class="date tcal" required  value="' .
        $w['tanggal_masuk'] .
        '" /></td>
		</tr>		
		
		<tr>
			<td  align="left" valign="top">Status<font color="red"> *</font></td>
			<td  ><select name="status_aktif"  required   />' .
        opAplikasi('05', '' . $w['status_aktif'] . '') .
        '</select></td>
		</tr>				
		</table>
	</fieldset><br/> ';

    if ($w['status_masuk'] == 'B') {
        baru();
    } elseif ($w['status_masuk'] == 'P') {
        pindahan();
    }

    echo ' <input type="submit" class="tombols ui-corner-all" value="Simpan"/> 
       <input type="button" class="tombols ui-corner-all" value="Kembali" onClick="window.location = \'index.php?m=' .
        $_GET['m'] .
        '\'"/></form>';
}

function baru()
{
    global $w;
    echo '  
<fieldset id="asalpt"  class="ui-widget ui-widget-content ui-corner-all" >
          <legend class="ui-widget ui-widget-header ui-corner-all">Asal Sekolah</legend>
		<table width="600"  border="0" class="datatable full1">
		<tr>
            <td align="left" valign="top" style="width:15%">Nama Sekolah<font color="red"> *</font></td>
            <td ><input name="SekolahID"  type="text" required  value="' .
        $w['SekolahID'] .
        '" />	</td>
        </tr>	
		<tr>
			<td  align="left" valign="top">NIS<font color="red"> *</font></td>
			<td  ><input name="nis_asal"  type="text" required  value="' .
        $w['nis_asal'] .
        '" /></td>
		</tr>
		<tr>
			<td  align="left" valign="top">Nilai UN<font color="red"> *</font></td>
			<td  ><input name="nilai_un"  type="text" required  value="' .
        $w['nilai_un'] .
        '" /></td>
		</tr>
		</table> 
	</fieldset>
	 ';
}

function pindahan()
{
    global $w;
    echo '  
<fieldset id="asalpt"  class="ui-widget ui-widget-content ui-corner-all" >
          <legend class="ui-widget ui-widget-header ui-corner-all">Asal Perguruan Tinggi</legend>
	<table width="600"  border="0" class="datatable full1">
		<tr>
			<td  align="left" valign="top" style="width:20%">Kode Perguruan Tinggi<font color="red"> *</font></td>
			<td  ><input name="kode_asal_pt"  type="text" required  value="' .
        $w['kode_asal_pt'] .
        '" /></td>
		</tr>
		<tr>
			<td  align="left" valign="top">Nama Perguruan Tinggi<font color="red"> *</font></td>
			<td  ><input name="nama_asal_pt"  type="text" required  value="' .
        $w['nama_asal_pt'] .
        '" /></td>
		</tr>

		<tr>
			<td  align="left" valign="top">Jenjang<font color="red"> *</font></td>
			<td  ><select name="asal_jenjang"  style="width:300px" required   />' .
        opAplikasi('04', '' . $w['asal_jenjang'] . '') .
        '</select></td>
		</tr>
		<tr>
			<td  align="left" valign="top">Program Studi<font color="red"> *</font></td>
			<td  ><input name="asal_prodi"  type="text" class=""   value="' .
        $w['asal_prodi'] .
        '"/></td>
		</tr>	
		<tr>
			<td  align="left" valign="top">NIM Asal<font color="red"> *</font></td>
			<td  ><input name="nim_asal"  type="text" required  value="' .
        $w['nim_asal'] .
        '" /></td>
		</tr>
		<tr>
			<td  align="left" valign="top">SKS Diakui<font color="red"> *</font></td>
			<td  ><input name="sks_diakui"  type="text" required  value="' .
        $w['sks_diakui'] .
        '" /></td>
		</tr>
		</table> 
	</fieldset>
	';
}

function Pendidikan()
{
    global $koneksi_db, $w;
    $id = $_REQUEST['id'];
    $idm = $_REQUEST['idm'];
    if (!empty($id) && isset($id)) {
        $wp = $koneksi_db->sql_fetchassoc(
            $koneksi_db->sql_query(
                "SELECT * FROM t_mahasiswa_pendidikan where id='$id' limit 1 "
            )
        );
        $sembunyi = 'style="display:block;"';
    } else {
        $sembunyi = 'style="display:none;"';
    }
    echo "<input type=button class=button-blue value='Tambah Pendidikan' onclick=\"return toggleView('search_hide')\" >";
    echo '<div id="form-hide" ' . $sembunyi . '>';
    echo '
<form action="" method="post"  class="cmxform" id="form_input" style="width:100%">
        <input type="hidden" name="m" value="mahasiswa"/>
        <input type="hidden" name="op" value="edit"/>
		<input type="hidden" name="sub" value="pendidikanSav"/>
		<input type="hidden" name="md" value="' .
        $md .
        '"/>
		<input type="hidden" name="idm" value="' .
        $w['idm'] .
        '"/>
		<input type="hidden" name="idp" value="' .
        $id .
        '"/> 
        <fieldset class="ui-widget ui-widget-content ui-corner-all" >
            <legend class="ui-widget ui-widget-header ui-corner-all">Edit Data Pendidikan ' .
        $w['nama_mahasiswa'] .
        '</legend>
            &nbsp;<font color="red"><br></font>
            <table width="600"  border="0" class="datatable full1">
                <tr>
                    <td align="right" valign="top">Jenjang Studi<font color="red"></font></td>
                    <td>	<select name="jenjang_studi"  required   />' .
        opAplikasi('01', '' . $wp['jenjang_studi'] . '') .
        '</select>		</td>
                </tr>
                <tr>
                    <td align="right" valign="top">Bidang Keilmuan<font color="red"></font></td>
                    <td>	<select name="kode_bidang"  required   />' .
        opAplikasi('42', '' . $wp['kode_bidang'] . '') .
        '</select>		</td>
                </tr>
				
                <tr>
                    <td width="150" align="right" valign="top">Gelar Akademik<font color="red"> *</font></td>
                    <td  >	<input name="gelar"  type="text" required id="" value="' .
        $wp['gelar'] .
        '" />	</td>
                </tr>
                <tr>
                    <td width="150" align="right" valign="top">Perguruan Tinggi<font color="red"> *</font></td>
                    <td  >	<select name="kode_pt"  required   />' .
        opdaftarpt('' . $wp['kode_pt'] . '') .
        '</select>	</td>
                </tr>
                <tr>
                    <td width="150" align="right" valign="top">Tanggal Ijazah<font color="red"> *</font></td>
                    <td  >	<input name="tgl_ijazah"  type="text" class="tcal date" required id="" value="' .
        $wp['tgl_ijazah'] .
        '" />	</td>
                </tr>
				
                <tr>
                    <td width="150" align="right" valign="top">Tahun<font color="red"> *</font></td>
                    <td  >	<select name="tahun"  required   />' .
        optahun('' . $wp['tahun'] . '') .
        '</select>	</td>
                </tr>
                <tr>
                    <td width="150" align="right" valign="top">SKS Lulus<font color="red"> *</font></td>
                    <td  >	<input name="sks_lulus"  type="text" required id="" value="' .
        $wp['sks_lulus'] .
        '" />	</td>
                </tr>
                <tr>
                    <td width="150" align="right" valign="top">IPK Akhir<font color="red"> *</font></td>
                    <td  >	<input name="ipk_akhir"  type="text" required id="" value="' .
        $wp['ipk_akhir'] .
        '" />	</td>
                </tr>
                 <tr>
                    <td colspan="2">
                        <input type="submit" class="tombols ui-corner-all" value="Simpan"/> 
                        <input type="button" class="tombols ui-corner-all" value="Kembali" onClick="window.location = \'index.php?m=' .
        $_GET['m'] .
        '\'"/></td>
                  </tr>					
            </table>
        </fieldset></form> ';
    echo '</div>';

    echo '<table class="table table-striped table-bordered table-hover"  >
   	<thead>
     <tr>
	   <th width="5%" align="center">No</th>
	   <th  align="center">Tahun</th>
	   <th  align="center">Tgl Ijazah</th>
	   <th  align="center">Gelar</th>
       <th  align="center">Jenjang</th>
       <th  align="center">Bidang</th>
	   <th width="30%" align="center"></th>
     </tr>
	 </thead>
	 <tbody>';

    $qpp = $koneksi_db->sql_query(
        "SELECT * FROM `t_mahasiswa_pendidikan` where idm='$w[idm]' "
    );
    $jumlah = $koneksi_db->sql_numrows($qpp);
    if ($jumlah > 0) {
        while ($wf = $koneksi_db->sql_fetchassoc($qpp)) {
            $n++;
            echo "<tr>
			  	<td $c>$n</td>
				<td $c>$wf[tahun]</td>
				<td $c>$wf[tgl_ijazah]</td>
				<td $c>$wf[gelar]</td>
				<td $c>" .
                viewAplikasi('01', '' . $wf['jenjang_studi'] . '') .
                "</td>
				<td $c>" .
                viewAplikasi('42', '' . $wf['kode_bidang'] . '') .
                "</td>
				<td $c>
				<a href='index.php?m=" .
                $_GET['m'] .
                "&op=edit&sub=Pendidikan&idm=$wf[idm]&id=$wf[id]' >Edit</a></td>
				</tr>";
        }
    } else {
        echo '<tr > <th  colspan="6" align=center>Belum ada Data</th></tr>';
    }

    echo '</tbody>
		</table>';
    echo '
<script type="text/javascript"> 
function toggleView(){
	//$("#toggleView").click(function(){
		$("#form-hide").toggle();
		return false;
}
</script>
';
}
////simpan /
function simpanadd()
{
    global $koneksi_db, $tahun_id;

    $wi = $koneksi_db->sql_fetchassoc(
        $koneksi_db->sql_query(
            "SELECT * FROM m_program_studi where kode_prodi='" .
                $_REQUEST['kode_prodi'] .
                "' limit 1 "
        )
    );

    $qd = $koneksi_db->sql_query(
        "SELECT * FROM m_mahasiswa where NIM='" .
            $_REQUEST['NIM'] .
            "' limit 1 "
    );
    $totald = $koneksi_db->sql_numrows($qd);
    $wd = $koneksi_db->sql_fetchassoc($qd);
    if ($totald > 0) {
        echo '<div class=error>Kode NIM' .
            $_REQUEST['NIM'] .
            ' sudah dipakai oleh ' .
            $wd['nama_mahasiswa'] .
            '</div>';
    } else {
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
                    $_tahun = substr($tahun_id, 0, 4) + 0;
                    $s =
                        "insert into m_mahasiswa SET 
									idm='" .
                        $_REQUEST['idm'] .
                        "',
									NIM='" .
                        $_REQUEST['NIM'] .
                        "',
									kode_pt='" .
                        $wi['kode_pt'] .
                        "',
									pa='" .
                        $_REQUEST['pa'] .
                        "',
									kode_fak='" .
                        $wi['kode_fak'] .
                        "',
									kode_prodi='" .
                        $_REQUEST['kode_prodi'] .
                        "',
									kode_jenjang='" .
                        $wi['kode_jenjang'] .
                        "',
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
                        "',
									status_masuk='" .
                        $_REQUEST['status_masuk'] .
                        "',
									tahun_masuk='" .
                        $_tahun .
                        "',
									foto='" .
                        $xx .
                        "',
									semester_masuk='" .
                        $tahun_id .
                        "',
									SekolahID='" .
                        $_REQUEST['SekolahID'] .
                        "',
									nis_asal='" .
                        $_REQUEST['nis_asal'] .
                        "',
									nilai_un='" .
                        $_REQUEST['nilai_un'] .
                        "',
									PerguruanTinggiID='" .
                        $_REQUEST['PerguruanTinggiID'] .
                        "',
									nim_asal='" .
                        $_REQUEST['nim_asal'] .
                        "',
									asal_jenjang='" .
                        $_REQUEST['asal_jenjang'] .
                        "',
									asal_prodi='" .
                        $_REQUEST['asal_prodi'] .
                        "'
									";
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
            $_tahun = substr($tahun_id, 0, 4) + 0;
            $s =
                "insert into m_mahasiswa SET 
									idm='" .
                $_REQUEST['idm'] .
                "',
									NIM='" .
                $_REQUEST['NIM'] .
                "',
									kode_pt='" .
                $wi['kode_pt'] .
                "',
									pa='" .
                $_REQUEST['pa'] .
                "',
									kode_fak='" .
                $wi['kode_fak'] .
                "',
									kode_prodi='" .
                $_REQUEST['kode_prodi'] .
                "',
									kode_jenjang='" .
                $wi['kode_jenjang'] .
                "',
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
                "',
									status_masuk='" .
                $_REQUEST['status_masuk'] .
                "',
									tahun_masuk='" .
                $_tahun .
                "',
									semester_masuk='" .
                $tahun_id .
                "',
									SekolahID='" .
                $_REQUEST['SekolahID'] .
                "',
									nis_asal='" .
                $_REQUEST['nis_asal'] .
                "',
									nilai_un='" .
                $_REQUEST['nilai_un'] .
                "',
									PerguruanTinggiID='" .
                $_REQUEST['PerguruanTinggiID'] .
                "',
									nim_asal='" .
                $_REQUEST['nim_asal'] .
                "',
									asal_jenjang='" .
                $_REQUEST['asal_jenjang'] .
                "',
									asal_prodi='" .
                $_REQUEST['asal_prodi'] .
                "'
									";
            $koneksi_db->sql_query($s);
        }

        $su =
            "insert into user SET 
						userid='" .
            $_REQUEST['idm'] .
            "',
						username='" .
            $_REQUEST['NIM'] .
            "',
						password='" .
            md5($_REQUEST['NIM']) .
            "',
						nama='" .
            $_REQUEST['nama_mahasiswa'] .
            "',
						email='" .
            $_REQUEST['email'] .
            "',
						level='MAHASISWA'
						";
        $koneksi_db->sql_query($su);

        echo '<div class=error>Proses menyimpan Data....</div>';
        echo "<meta http-equiv='refresh' content='3; url=index.php?m=" .
            $_GET['m'] .
            '&op=edit&idm=' .
            $_REQUEST['idm'] .
            "'>";
    }
}

////simpan /

function biodataSav()
{
    global $koneksi_db;
    $idm = $_REQUEST['idm'];

    $qd = $koneksi_db->sql_query(
        "SELECT * FROM m_mahasiswa where idm!='$idm' and NIM='" .
            $_REQUEST['NIM'] .
            "' limit 1 "
    );
    $totald = $koneksi_db->sql_numrows($qd);
    $wd = $koneksi_db->sql_fetchassoc($qd);
    if ($totald > 0) {
        echo '<div class=error>Kode NIM' .
            $_REQUEST['NIM'] .
            ' sudah dipakai oleh ' .
            $wd['nama_mahasiswa'] .
            '</div>';
    } else {
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
								NIM='" .
                        $_REQUEST['NIM'] .
                        "',
						 nama_mahasiswa='" .
                        $_REQUEST['nama_mahasiswa'] .
                        "',
                tempat_lahir='" .
                        $_REQUEST['tempat_lahir'] .
                        "',
                 tanggal_lahir='" .
                        $_REQUEST['tanggal_lahir'] .
                        "',
                 jenis_kelamin='" .
                        $_REQUEST['jenis_kelamin'] .
                        "',
                 agama='" .
                        $_REQUEST['agama'] .
                        "',
                  status_sipil='" .
                        $_REQUEST['status_sipil'] .
                        "',
                  nama_ibu='" .
                        $_REQUEST['nama_ibu'] .
                        "',
                  nik='" .
                        $_REQUEST['nik'] .
                        "',
                  warga_negara='" .
                        $_REQUEST['warga_negara'] .
                        "',
                  jalan='" .
                        $_REQUEST['jalan'] .
                        "',
                  dusun='" .
                        $_REQUEST['dusun'] .
                        "',
                  rt='" .
                        $_REQUEST['rt'] .
                        "',
                rw='" .
                        $_REQUEST['rw'] .
                        "',
                kelurahan='" .
                        $_REQUEST['kelurahan'] .
                        "',
                kecamatan='" .
                        $_REQUEST['kecamatan'] .
                        "',
                kode_pos='" .
                        $_REQUEST['kode_pos'] .
                        "',
                  kendaraan='" .
                        $_REQUEST['kendaraan'] .
                        "',
                  jns_tinggal='" .
                        $_REQUEST['jns_tinggal'] .
                        "',
                 telepon='" .
                        $_REQUEST['telepon'] .
                        "',
                 email='" .
                        $_REQUEST['email'] .
                        "',
                 hp='" .
                        $_REQUEST['hp'] .
                        "',
								foto='" .
                        $xx .
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
								NIM='" .
                $_REQUEST['NIM'] .
                "',
                nama_mahasiswa='" .
                $_REQUEST['nama_mahasiswa'] .
                "',
                tempat_lahir='" .
                $_REQUEST['tempat_lahir'] .
                "',
                 tanggal_lahir='" .
                $_REQUEST['tanggal_lahir'] .
                "',
                 jenis_kelamin='" .
                $_REQUEST['jenis_kelamin'] .
                "',
                 agama='" .
                $_REQUEST['agama'] .
                "',
                  status_sipil='" .
                $_REQUEST['status_sipil'] .
                "',
                  nama_ibu='" .
                $_REQUEST['nama_ibu'] .
                "',
                  nik='" .
                $_REQUEST['nik'] .
                "',
                  warga_negara='" .
                $_REQUEST['warga_negara'] .
                "',
                  jalan='" .
                $_REQUEST['jalan'] .
                "',
                  dusun='" .
                $_REQUEST['dusun'] .
                "',
                  rt='" .
                $_REQUEST['rt'] .
                "',
                rw='" .
                $_REQUEST['rw'] .
                "',
                kelurahan='" .
                $_REQUEST['kelurahan'] .
                "',
                kecamatan='" .
                $_REQUEST['kecamatan'] .
                "',
                kode_pos='" .
                $_REQUEST['kode_pos'] .
                "',
                  kendaraan='" .
                $_REQUEST['kendaraan'] .
                "',
                  jns_tinggal='" .
                $_REQUEST['jns_tinggal'] .
                "',
                 telepon='" .
                $_REQUEST['telepon'] .
                "',
                 email='" .
                $_REQUEST['email'] .
                "',
                 hp='" .
                $_REQUEST['hp'] .
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
}

function alamatSav()
{
    global $koneksi_db;
    $idm = $_REQUEST['idm'];

    $s =
        "update m_mahasiswa  set 
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
				where idm='" .
        $idm .
        "' ";
    $koneksi_db->sql_query($s);
    echo "<div  class='error'>Proses Menyimpan Data...</div>";
    echo "<meta http-equiv='refresh' content='3; url=index.php?m=" .
        $_GET['m'] .
        '&op=edit&sub=Alamat&idm=' .
        $_REQUEST['idm'] .
        "'>";
}

function akademikSav()
{
    global $koneksi_db;
    $idm = $_REQUEST['idm'];
    if (trim($_POST['kode_prodi']) == '') {
        $pesan[] = 'Form Prodi masih kosong, ulangi kembali';
    }

    if (!count($pesan) == 0) {
        echo "<div align='left'>";
        echo '&nbsp; <b> Kesalahan Input : </b><br>';
        foreach ($pesan as $indeks => $pesan_tampil) {
            $urut_pesan++;
            echo "<font color='#FF0000' align='left'>";
            echo '&nbsp; &nbsp;';
            echo "$urut_pesan . $pesan_tampil <br>";
            echo '</font>';
        }
        echo '</div><br>';
        echo "<meta http-equiv='refresh' content='2; url=index.php?m=" .
            $_GET['m'] .
            "&op=edit&sub=Akademik&idm=$idm'>";
    } else {
        $wi = $koneksi_db->sql_fetchassoc(
            $koneksi_db->sql_query(
                "SELECT * FROM m_program_studi where kode_prodi='" .
                    $_REQUEST['kode_prodi'] .
                    "' limit 1 "
            )
        );

        $batas_studi = HitungBatasStudi(
            $_REQUEST['semester_masuk'],
            $_REQUEST['kode_prodi']
        );

        $s =
            "update m_mahasiswa  set 
		  		kode_pt='" .
            $wi['kode_pt'] .
            "',
				kode_fak='" .
            $wi['kode_fak'] .
            "',
				kode_prodi='" .
            $_REQUEST['kode_prodi'] .
            "',
				kode_konsentrasi='" .
            $_REQUEST['kode_konsentrasi'] .
            "',
				kode_jenjang='" .
            $wi['kode_jenjang'] .
            "',
				tahun_masuk='" .
            $_REQUEST['tahun_masuk'] .
            "',
				semester_masuk='" .
            $_REQUEST['semester_masuk'] .
            "',
				tanggal_masuk='" .
            $_REQUEST['tanggal_masuk'] .
            "',
				batas_studi='" .
            $batas_studi .
            "',
				status_aktif='" .
            $_REQUEST['status_aktif'] .
            "',
				SekolahID='" .
            $_REQUEST['SekolahID'] .
            "',
				nis_asal='" .
            $_REQUEST['nis_asal'] .
            "',
				nilai_un='" .
            $_REQUEST['nilai_un'] .
            "',
				PerguruanTinggiID='" .
            $_REQUEST['PerguruanTinggiID'] .
            "',
				nim_asal='" .
            $_REQUEST['nim_asal'] .
            "',
				asal_jenjang='" .
            $_REQUEST['asal_jenjang'] .
            "',
				asal_prodi='" .
            $_REQUEST['asal_prodi'] .
            "',
				sks_diakui='" .
            $_REQUEST['sks_diakui'] .
            "'
				where idm='" .
            $idm .
            "' ";
        $koneksi_db->sql_query($s);
        echo "<div  class='error'>Proses Menyimpan Data...</div>";
        echo "<meta http-equiv='refresh' content='3; url=index.php?m=" .
            $_GET['m'] .
            '&op=edit&sub=Akademik&idm=' .
            $_REQUEST['idm'] .
            "'>";
    }
}

function ortuSav()
{
    global $koneksi_db;
    $idm = $_REQUEST['idm'];
    //simpan ayah
    $qa = $koneksi_db->sql_query(
        "SELECT * FROM t_mahasiswa_ortu where idm='$idm' and hubungan='AYAH' limit 1 "
    );
    $totala = $koneksi_db->sql_numrows($qa);
    $wa = $koneksi_db->sql_fetchassoc($qa);
    if ($totala > 0) {
        $sa =
            "update t_mahasiswa_ortu  set 
					nama='" .
            $_REQUEST['a_nama'] .
            "',
					agama='" .
            $_REQUEST['a_agama'] .
            "',
					pendidikan='" .
            $_REQUEST['a_pendidikan'] .
            "',
					pekerjaan='" .
            $_REQUEST['a_pekerjaan'] .
            "',
					penghasilan='" .
            $_REQUEST['a_penghasilan'] .
            "',
					hidup='" .
            $_REQUEST['a_hidup'] .
            "',				
					alamat='" .
            $_REQUEST['a_alamat'] .
            "',
					kota='" .
            $_REQUEST['a_kota'] .
            "',
					propinsi='" .
            $_REQUEST['a_propinsi'] .
            "',
					pos='" .
            $_REQUEST['a_pos'] .
            "',	
					telepon='" .
            $_REQUEST['a_telepon'] .
            "',
					hp='" .
            $_REQUEST['a_hp'] .
            "',
					email='" .
            $_REQUEST['a_email'] .
            "'
					where idm='" .
            $idm .
            "' and hubungan='AYAH'";
    } else {
        $sa =
            "insert into t_mahasiswa_ortu SET 
					idm='" .
            $_REQUEST['idm'] .
            "',
					hubungan='AYAH',
					nama='" .
            $_REQUEST['a_nama'] .
            "',
					agama='" .
            $_REQUEST['a_agama'] .
            "',
					pendidikan='" .
            $_REQUEST['a_pendidikan'] .
            "',
					pekerjaan='" .
            $_REQUEST['a_pekerjaan'] .
            "',
					penghasilan='" .
            $_REQUEST['a_penghasilan'] .
            "',
					hidup='" .
            $_REQUEST['a_hidup'] .
            "',				
					alamat='" .
            $_REQUEST['a_alamat'] .
            "',
					kota='" .
            $_REQUEST['a_kota'] .
            "',
					propinsi='" .
            $_REQUEST['a_propinsi'] .
            "',
					pos='" .
            $_REQUEST['a_pos'] .
            "',	
					telepon='" .
            $_REQUEST['a_telepon'] .
            "',
					hp='" .
            $_REQUEST['a_hp'] .
            "',
					email='" .
            $_REQUEST['a_email'] .
            "'
					";
    }
    $koneksi_db->sql_query($sa);

    //simpan ibu
    $qi = $koneksi_db->sql_query(
        "SELECT * FROM t_mahasiswa_ortu where idm='$idm' and hubungan='IBU' limit 1 "
    );
    $totali = $koneksi_db->sql_numrows($qi);
    if ($totali > 0) {
        $si =
            "update t_mahasiswa_ortu  set 
				nama='" .
            $_REQUEST['i_nama'] .
            "',
				agama='" .
            $_REQUEST['i_agama'] .
            "',
				pendidikan='" .
            $_REQUEST['i_pendidikan'] .
            "',
				pekerjaan='" .
            $_REQUEST['i_pekerjaan'] .
            "',
				penghasilan='" .
            $_REQUEST['i_penghasilan'] .
            "',
				hidup='" .
            $_REQUEST['i_hidup'] .
            "',				
				alamat='" .
            $_REQUEST['i_alamat'] .
            "',
				kota='" .
            $_REQUEST['i_kota'] .
            "',
				propinsi='" .
            $_REQUEST['i_propinsi'] .
            "',
				pos='" .
            $_REQUEST['i_pos'] .
            "',	
				telepon='" .
            $_REQUEST['i_telepon'] .
            "',
				hp='" .
            $_REQUEST['i_hp'] .
            "',
				email='" .
            $_REQUEST['i_email'] .
            "'
				where idm='" .
            $idm .
            "' and hubungan='IBU'";
    } else {
        $si =
            "insert into t_mahasiswa_ortu SET 
				 idm='" .
            $_REQUEST['idm'] .
            "',
				hubungan='IBU',
				nama='" .
            $_REQUEST['i_nama'] .
            "',
				agama='" .
            $_REQUEST['i_agama'] .
            "',
				pendidikan='" .
            $_REQUEST['i_pendidikan'] .
            "',
				pekerjaan='" .
            $_REQUEST['i_pekerjaan'] .
            "',
				penghasilan='" .
            $_REQUEST['i_penghasilan'] .
            "',
				hidup='" .
            $_REQUEST['i_hidup'] .
            "',				
				alamat='" .
            $_REQUEST['i_alamat'] .
            "',
				kota='" .
            $_REQUEST['i_kota'] .
            "',
				propinsi='" .
            $_REQUEST['i_propinsi'] .
            "',
				pos='" .
            $_REQUEST['i_pos'] .
            "',	
				telepon='" .
            $_REQUEST['i_telepon'] .
            "',
				hp='" .
            $_REQUEST['i_hp'] .
            "',
				email='" .
            $_REQUEST['i_email'] .
            "'
				";
    }
    $koneksi_db->sql_query($si);
    echo "<div  class='error'>Proses Menyimpan Data...</div>";
    echo "<meta http-equiv='refresh' content='3; url=index.php?m=" .
        $_GET['m'] .
        '&op=edit&sub=Ortu&idm=' .
        $_REQUEST['idm'] .
        "'>";
}

function pendidikanSav()
{
    global $koneksi_db, $w;
    $idm = $_REQUEST['idm'];

    //simpan pendidikan
    if ($w['kode_jenjang'] == 'A') {
        $qp = $koneksi_db->sql_query(
            "SELECT * FROM t_mahasiswa_pendidikan where id='" .
                $_REQUEST['idp'] .
                "' and idm='" .
                $_REQUEST['idm'] .
                "' limit 1 "
        );
        $totalp = $koneksi_db->sql_numrows($qp);
        if ($totalp > 0) {
            $sa =
                "update t_mahasiswa_pendidikan  set 
					jenjang_studi='" .
                $_REQUEST['jenjang_studi'] .
                "',
					gelar='" .
                $_REQUEST['gelar'] .
                "',
					kode_pt='" .
                $_REQUEST['kode_pt'] .
                "',
					kode_bidang='" .
                $_REQUEST['kode_bidang'] .
                "',
					tgl_ijazah='" .
                $_REQUEST['tgl_ijazah'] .
                "',
					tahun='" .
                $_REQUEST['tahun'] .
                "',
					sks_lulus='" .
                $_REQUEST['sks_lulus'] .
                "',
					ipk_akhir='" .
                $_REQUEST['ipk_akhir'] .
                "'
				where idm='" .
                $idm .
                "'and  id ='" .
                $_REQUEST['idp'] .
                "'";
        } else {
            $sa =
                "insert into t_mahasiswa_pendidikan SET 
					idm='" .
                $idm .
                "',
					jenjang_studi='" .
                $_REQUEST['jenjang_studi'] .
                "',
					gelar='" .
                $_REQUEST['gelar'] .
                "',
					kode_pt='" .
                $_REQUEST['kode_pt'] .
                "',
					kode_bidang='" .
                $_REQUEST['kode_bidang'] .
                "',
					tgl_ijazah='" .
                $_REQUEST['tgl_ijazah'] .
                "',
					tahun='" .
                $_REQUEST['tahun'] .
                "',
					sks_lulus='" .
                $_REQUEST['sks_lulus'] .
                "',
					ipk_akhir='" .
                $_REQUEST['ipk_akhir'] .
                "'
				";
        }
        $koneksi_db->sql_query($sa);
    }
    echo "<div  class='error'>Proses Menyimpan Data...</div>";
    echo "<meta http-equiv='refresh' content='3; url=index.php?m=" .
        $_GET['m'] .
        '&op=edit&sub=Pendidikan&idm=' .
        $idm .
        "'>";
}

function FormImport()
{
    echo ' <br />
    <h3>Import Data Mahasiswa</h3>
	
<div class="col-md-4">
	<div >
<a href="files/format_mhs.xls" class="btn btn-danger">Download Format</a>
</div>
<form action="" method="post" id="form_input" enctype="multipart/form-data">
<input type="hidden" name="m" value="mahasiswa" />
<input type="hidden" name="op" value="Import"/>
<div class="form-group">
	<input type="file" name="fileimport" required  class="form-control">
	<button type="submit" class="btn btn-default">Proses Import Data</button>
</div>
				
</form>
</div>
<div class="col-md-8">	      
<div class="alert alert-success">				
File yang diimport harus berekstensi .xls. Format isi file adalah sebagai berikut : <br /><br/>
 [NIM], [nama_mahasiswa], [agama], [jenis_kelamin], [tempat_lahir], [tanggal_lahir], [telepon], [hp], [email], [status_masuk], [tahun_masuk], [NIP-PA]<br /> 
Format tanggal adalah YYYY-MM-DD <br /> 
 
 </div></div>';
}

function Import()
{
    require 'system/excel_reader2.php';
    global $koneksi_db, $tahun_id;
    $prodi = $_SESSION['prodi'];
    $_tahun = substr($tahun_id, 0, 4) + 0;
    //echo $prodi, "TEST";
    $w = $koneksi_db->sql_fetchassoc(
        $koneksi_db->sql_query(
            "SELECT * FROM m_program_studi where kode_prodi='" .
                $prodi .
                "' limit 1 "
        )
    );
    $kodept = $w['kode_pt'];
    $kodefak = $w['kode_fak'];
    $kodejen = $w['kode_jenjang'];

    //$filex_namex = strip(strtolower($_FILES['filesiswa']['tmp_name']));

    // membaca file excel yang diupload
    $data = new Spreadsheet_Excel_Reader($_FILES['fileimport']['tmp_name']);

    // membaca jumlah baris dari data excel
    $baris = $data->rowcount($sheet_index = 0);

    // nilai awal counter untuk jumlah data yang sukses dan yang gagal diimport
    $sukses_import = 0;
    $sukses_update = 0;
    $gagal = 0;

    // import data excel mulai baris ke-2
    for ($i = 2; $i <= $baris; $i++) {
        $idm = kdauto('m_mahasiswa', 'M');

        // membaca data nidn (kolom ke-1 dan seterusnya)
        // kolom identitas mahasiswa
        $kode_prodi = $data->val($i, 1);
        $jenjang = $data->val($i, 2);
        $NIM = $data->val($i, 3);
        $nama_mahasiswa = $data->val($i, 4);
        $agama = $data->val($i, 5);
        $jenis_kelamin = $data->val($i, 6);
        $tempat_lahir = $data->val($i, 7);
        $tanggal_lahir = $data->val($i, 8);
        $alamat = $data->val($i, 9);
        $telepon = $data->val($i, 10);
        $hp = $data->val($i, 11);
        $email = $data->val($i, 12);
        $statusmasuk = $data->val($i, 13);
        $tahun_masuk = $data->val($i, 14);
        $pa = $data->val($i, 15);

        // ini temporary aja buat export semua prodi jg lupa ubah di insert nya juga
        //$kdpt = 			$data->val($i, 12);
        //$kdfak = 			$data->val($i, 13);
        //$kdjenjang = 		$data->val($i, 14);
        // $kdprodi = 		$data->val($i, 15);
        //$status_masuk = 		$data->val($i, 12);
        //$pa	 	= 		$data->val($i, 13);

        //insert data mahasiswa
        $qada = "select * from m_mahasiswa where nama_mahasiswa='$nama_mahasiswa' and tanggal_lahir='$tanggal_lahir'";
        $qryu = $koneksi_db->sql_query($qada);
        if ($koneksi_db->sql_numrows($qryu) > 0) {
            //ini untuk update data mahasiswa
            /*
				$su = "update m_mahasiswa SET 
						kode_pt='".$kodept."',
						kode_fak='".$kodefak."',
						kode_jurusan='".$kodejurusan."',
						kode_prodi='".$prodi."',
						kode_jenjang='".$kodejen."',
						nama_mahasiswa='".$nama_mahasiswa."',
						agama='".$agama."',
						jenis_kelamin='".$jenis_kelamin."',
						tempat_lahir='".$tempat_lahir."',
						tanggal_lahir='".$tanggal_lahir."',
						telepon='".$telepon."',
						hp='".$hp."',
						email='".$email."',
						status_masuk='".$status_masuk."'
						WHERE NIM = '".$NIM."'
					";
				$hasil_update =  $koneksi_db->sql_query($su);
				*/
            //$sukses_update++;
            //$nama_update .= "<li>".$nama_mahasiswa."</li>";
        } else {
            $si =
                "insert into m_mahasiswa SET 
						idm='" .
                $idm .
                "',
						kode_pt='" .
                $kodept .
                "',
						kode_fak='" .
                $kodefak .
                "',
						kode_prodi='" .
                $kode_prodi .
                "',
						kode_jenjang='" .
                $jenjang .
                "',
						NIM = '" .
                $NIM .
                "',
						nama_mahasiswa='" .
                $nama_mahasiswa .
                "',
						agama='" .
                $agama .
                "',
						jenis_kelamin='" .
                $jenis_kelamin .
                "',
						tempat_lahir='" .
                $tempat_lahir .
                "',
						tanggal_lahir='" .
                $tanggal_lahir .
                "',
						alamat='" .
                $alamat .
                "',
						telepon='" .
                $telepon .
                "',
						hp='" .
                $hp .
                "',
						email='" .
                $email .
                "',
						tahun_masuk='" .
                $tahun_masuk .
                "',
						status_masuk='" .
                $statusmasuk .
                "',
						pa='" .
                $pa .
                "'
					";

            $hasil_import = $koneksi_db->sql_query($si);
            $nama_hasil .= '<li>' . $nama_mahasiswa . '</li>';

            $su =
                "insert into user SET 
							userid='" .
                $idm .
                "',
							username='" .
                $NIM .
                "',
							password='" .
                md5($NIM) .
                "',
							nama='" .
                $nama_mahasiswa .
                "',
							email='" .
                $email .
                "',
							level='MAHASISWA'
							";
            $koneksi_db->sql_query($su);
        }

        if ($hasil_import) {
            $sukses_import++;
        } else {
            $gagal++;
        }
    }

    echo "<fieldset class=cari>
	<legend> Proses import data </legend>
<table width=400 border=0>
	<tr><td align=right>Jumlah data yang sukses diimport </td><td width=100>: " .
        $sukses_import .
        "</td></tr>
	<tr><td align=right></td><td width=100><ul> " .
        $nama_hasil .
        "</ul></td></tr>
	<tr><td align=right>Jumlah data yang sudah ada </td><td>: " .
        $sukses_update .
        "</td></tr>
	<tr><td align=right></td><td width=100><ul> " .
        $nama_update .
        "</ul></td></tr>
	<tr><td align=right>Jumlah data yang gagal diimport </td><td>: " .
        $gagal .
        "</td></tr>
</table>
</fieldset>	<br/>
<input id=kembali type=button class=button-red class=ui-button tombols ui-corner-all value=Kembali ke daftar Dosen style=\"font-size: 11px;height: inherit;\"  onclick=\"window.location='index.php?m=" .
        $_GET['m'] .
        "'\">
	";
}

$kolom_mahasiswa = BuatSesi('kolom_mahasiswa');
$kunci_mahasiswa = BuatSesi('kunci_mahasiswa');

if ($_REQUEST['reset_mahasiswa'] == 'Reset') {
    $_SESSION['kolom_mahasiswa'] = '';
    $_SESSION['kunci_mahasiswa'] = '';
}

$go = empty($_REQUEST['op']) ? 'Daftar' : $_REQUEST['op'];

echo '<div id="title" align="right">
        <font style="color:#FF9900; font-size:30px;"><strong>.:</strong></font>
        <font style="font-size:18px; color:#999999">Data Mahasiswa</font><br />
        	<a href="?m=home">Home</a> &raquo; <a href="?m=' .
    $_GET['m'] .
    '">Mahasiswa</a>  &raquo; ' .
    $go .
    '  
    </div>';
echo '<div class="mainContentCell"><div class="content">';
$go();
echo '</div></div>';
?>
