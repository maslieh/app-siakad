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

function hapus()
{
    $idm = $_REQUEST['idm'];
    mysql_query("DELETE FROM t_pmb_mahasiswa WHERE idmpmb='$idm'");
    mysql_query("DELETE FROM t_pmb_mahasiswa_ortu WHERE idmpmb='$idm'");
    mysql_query("DELETE FROM t_pmb_mahasiswa_pendidikan WHERE idmpmb='$idm'");
    echo '<div class=error>Data Berhasil dihapus</div>';
    Daftar();
}

function Daftar()
{
    global $koneksi_db;
    $prodi = $_SESSION['prodi'];
    $pmb_id = $_SESSION['pmb_id'];

    echo '<div class="row">
<div class="col-md-4 pull-right">';
    FilterPeriodePMB($_GET['m']);
    echo '</div><div class="col-md-8">';
    FilterMahasiswaPMB($_GET['m']);
    echo '</div></div>';

    if (!empty($pmb_id)) {
        #FilterMahasiswaPMB($_GET['m']);
        $whr = [];
        $ord = '';
        if (
            $_SESSION['reset_mahasiswa'] != 'Reset' &&
            !empty($_SESSION['kolom_mahasiswa']) &&
            !empty($_SESSION['kunci_mahasiswa'])
        ) {
            $whr[] = "$_SESSION[kolom_mahasiswa] like '%$_SESSION[kunci_mahasiswa]%' ";
            $ord = "order by $_SESSION[kolom_mahasiswa]";
        }

        if (!empty($_SESSION['prodi'])) {
            $whr[] = "kode_prodi='$_SESSION[prodi]'";
        }
        //if (!empty($_SESSION['kode_pmb'])) $whr[] = "kode_pmd='$_SESSION[pmb_id]'";
        $whr[] = "kode_pmb='$_SESSION[pmb_id]'";
        $whr[] = " terima='N'";
        if (!empty($whr)) {
            $strwhr = 'where ' . implode(' and ', $whr);
        }

        echo "<input type=button  class=\"tombols ui-corner-all\" value='Tambah Calon Mahasiswa' onclick=\"window.location.href='index.php?m=" .
            $_GET['m'] .
            "&op=add';\">";

        /*   
$sql = "select * from t_pmb_mahasiswa $strwhr";
$q = $koneksi_db->sql_query($sql);
$jumlah=$koneksi_db->sql_numrows($q);
if ($jumlah > 0){
*/
        require 'system/pagination_class.php';
        $sql = "select * from t_pmb_mahasiswa $strwhr $ord";
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
            echo '
<div class="table-responsive">
<table class="table table-striped table-bordered table-hover" id="dataTables-example">
   	<thead>
     <tr>
	   <th width="5%" align="center">No</th>
	   <th align="center">ID PMB</th>
       <th align="center">Nama Mahasiswa</th>
       <th align="center">Status</th>
	   <th width=10% align="center"></th>
     </tr>
	 </thead>
	 <tbody>';
            while ($wr = $koneksi_db->sql_fetchassoc($result)) {
                $n++;
                $id = $wr['idmpmb'];
                echo '<tr bgcolor="#f2f2f2">
				<td  align=center>' .
                    $n .
                    '</td> 
				<td  align=center>' .
                    $wr['idmpmb'] .
                    '</a></td>
				<td  align=left>' .
                    $wr['nama_mahasiswa'] .
                    '</td>
				<td  align=left>' .
                    viewAplikasi('06', '' . $wr['status_masuk'] . '') .
                    '</td>
				<td  align=center>
					
					<a href="#" class="btn" onclick="window.location.href=\'index.php?m=' .
                    $_GET['m'] .
                    '&op=edit&idm=' .
                    $id .
                    '\';"><i class="fa fa-edit"></i></a>
					<a href="#" class="btn" onclick="window.location.href=\'index.php?m=' .
                    $_GET['m'] .
                    '&op=hapus&idm=' .
                    $id .
                    '\';"><i class="fa fa-trash-o"></i></a>
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
    }
}

function add()
{
    global $koneksi_db, $w;
    $prodi = $_SESSION['prodi'];
    $pmb_id = $_SESSION['pmb_id'];
    $tahun = date('Y');

    $autokode = kdauto('t_pmb_mahasiswa', $tahun);
    $jdl = 'Tambah Data Calon Mahasiswa';
    $kode =
        '<input name="kode_"  disabled="disabled"  type="text" class="" id="" value="' .
        $autokode .
        '" />
		<input name="idm"  type="hidden" class="required" id="" value="' .
        $autokode .
        '" />
	';

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

    echo '
<div class="panel-body">
<form action="" method="post"  class="cmxform" id="form_input" style="width:100%">
	<input type="hidden" name="m" value="' .
        $_GET['m'] .
        '"/>
	<input type="hidden" name="op" value="simpanadd"/>
	<input type="hidden" name="md" value="' .
        $md .
        '"/>
	<input type="hidden" name="kode_prodi" value="' .
        $prodi .
        '"/>
	<fieldset class="ui-widget ui-widget-content ui-corner-all" >
          <legend class="ui-widget ui-widget-header ui-corner-all">Biodata Mahasiswa Baru</legend>
	<table width="600"  border="0" class="datatable full1">
	
		<tr>
			<td align="left"  valign="top">ID PMB<font color="red"> *</font></td>
			<td>' .
        $kode .
        '</td>
		</tr>
		<tr>
			<td  align="left" valign="top">Kode PMB<font color="red"> *</font></td>
			<td  ><select name="kode_pmb"  class="required"   />' .
        oppmb('' . $pmb_id . '', '' . $prodi . '') .
        '</select></td>
		</tr>
		
		<tr>
			<td align="left" valign="top">Nama Calon Mahasiswa<font color="red"> *</font></td>
			<td><input name="nama_mahasiswa"  type="text" class="full required"  value="' .
        $w['nama_mahasiswa'] .
        '" /></td>
		</tr>
		<tr>
			<td align="left" valign="top">Tempat Lahir<font color="red"> *</font></td>
			<td><input name="tempat_lahir"  type="text" class=" required"  value="' .
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
			<td  align="left" valign="top">Warga Negara<font color="red"> *</font></td>
			<td  ><select name="warga_negara"  class="required "   />' .
        oppwn('' . $w['warga_negara'] . '') .
        '</select></td>
		</tr>
		<tr>
			<td  align="left" valign="top">Status Sipil<font color="red"> *</font></td>
			<td  ><select name="status_sipil"  class="required "   />' .
        opAplikasi('52', '' . $w['status_sipil'] . '') .
        '</select></td>
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
		
	</table> 
	</fieldset>
	<br/>
		
	<table width="400"  border="0">
		<tr>
			<td  align="left" valign="top" >Status Calon<font color="red"> *</font></td>
			<td  ><select name="status_masuk"  id="status_masuk"  onchange=ubahstatus() class="required"   />' .
        opAplikasi('06', '' . $w['status_masuk'] . '') .
        '</select></td>
		</tr>
	
		</table> 
	<br/>
	<fieldset id="asalsekolah" style="display:none; class="ui-widget ui-widget-content ui-corner-all" >
          <legend class="ui-widget ui-widget-header ui-corner-all">Asal Sekolah</legend>
	<table width="600"  border="0" class="datatable full1">
		<tr>
            <td align="left" valign="top">Nama Sekolah<font color="red"> *</font></td>
            <td  ><input name="SekolahID"  type="text" class="required"  value="' .
        $w['SekolahID'] .
        '" /></td>
        </tr>	
		<tr>
			<td  align="left" valign="top">NIS<font color="red"> *</font></td>
			<td  ><input name="nis_asal"  type="text" class="number full"  value="' .
        $w['nis_asal'] .
        '" /></td>
		</tr>
		<tr>
			<td  align="left" valign="top">Nilai UN<font color="red"> *</font></td>
			<td  ><input name="nilai_un"  type="text" class=" number"  value="' .
        $w['nilai_un'] .
        '" /></td>
		</tr>
		</table> 
	</fieldset>
	<br/>
	
	<fieldset id="asalpt" style="display:none; class="ui-widget ui-widget-content ui-corner-all" >
          <legend class="ui-widget ui-widget-header ui-corner-all">Asal Perguruan Tinggi</legend>
	<table width="600"  border="0" class="datatable full1">
		<tr>
            <td align="left" valign="top">Nama Perguruan Tinggi<font color="red"> *</font></td>
            <td  ><input name="PerguruanTinggiID"  type="text" class=""  value="' .
        $w['PerguruanTinggiID'] .
        '" /></td>
        </tr>	
		
		<tr>
			<td  align="left" valign="top">Jenjang<font color="red"> *</font></td>
			<td  ><select name="asal_jenjang"  class=""   />' .
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
			<td  ><input name="nim_asal"  type="text" class="number "  value="' .
        $w['nim_asal'] .
        '" /></td>
		</tr>
		<tr>
			<td  align="left" valign="top">SKS Diakui<font color="red"> *</font></td>
			<td  ><input name="sks_diakui"  type="text" class="number"  value="' .
        $w['sks_diakui'] .
        '" /></td>
		</tr>
		</table> 
	</fieldset>
	<br/>	

	<input type="submit" class="tombols ui-corner-all" value="Simpan"/> 
	<input type="button" class="tombols ui-corner-all" value="Kembali" onClick="window.location = \'index.php?m=' .
        $_GET['m'] .
        '\'"/></td>
													

	</form></di>';
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
            "SELECT * FROM t_pmb_mahasiswa where idmpmb='" . $idm . "' limit 1 "
        )
    );
    $foto =
        $w['foto'] == ''
            ? 'images/no_avatar.gif'
            : 'images/avatar/' . $w['foto'] . '';
    $arrSub = [
        'Data Pribadi->Biodata',
        // 'Alamat->Alamat',
        // 'Orang Tua->Ortu',
        'Akademik->Akademik',
    ];

    echo '
		<table  border="0" cellspacing="1"class="datatable full" cellpadding="1">
		<thead>
		  <tr>
			<td width="149">ID PMB</td>
			<td width="436"><b>' .
        $w['idmpmb'] .
        '</b></td>
			<td width="37" valign="top" rowspan="5"><img src="' .
        $foto .
        '" width="80" height="80"></td>
		  </tr>
		  <tr>
			<td>NAMA</td>
			<td><b >' .
        $w['nama_mahasiswa'] .
        '</b></td>
		  </tr>
		  <tr>
			<td>konsentrasi</td>
			<td><b >' .
        viewkonsentrasi('' . $w['kode_konsentrasi'] . '') .
        '</b ></td>
		  </tr>
		  <tr>
			<td>PROGRAM STUDI PILIHAN</td>
			<td><b >' .
        viewprodi('' . $w['kode_prodi'] . '') .
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
    echo '</div></div>';
}

function Biodata()
{
    global $w;
    echo '
<form action="" method="post"  class="cmxform" id="form_input" style="width:100%">
        <input type="hidden" name="m" value="' .
        $_GET['m'] .
        '"/>
        <input type="hidden" name="op" value="edit"/>
		<input type="hidden" name="sub" value="biodataSav"/>
		<input type="hidden" name="md" value="' .
        $md .
        '"/>
		<input type="hidden" name="idm" value="' .
        $w['idmpmb'] .
        '"/> 
<fieldset  class="ui-widget ui-widget-content ui-corner-all" >
          <legend class="ui-widget ui-widget-header ui-corner-all">Biodata Calon Mahasiswa</legend>
	<table width="600"  border="0" class="datatable full1">
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
                    <td colspan="2">
                        <input type="submit" class="tombols ui-corner-all" value="Simpan"/> 
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
        <input type="hidden" name="m" value="' .
        $_GET['m'] .
        '"/>
        <input type="hidden" name="op" value="edit"/>
		<input type="hidden" name="sub" value="alamatSav"/>
		<input type="hidden" name="md" value="' .
        $md .
        '"/>
		<input type="hidden" name="idm" value="' .
        $w['idmpmb'] .
        '"/>
	<fieldset  class="ui-widget ui-widget-content ui-corner-all" >
          <legend class="ui-widget ui-widget-header ui-corner-all">Alamat Calon Mahasiswa</legend>
            <table width="600"  border="0" class="datatable full1">
                <tr>
                    <td width="150" valign="top">Alamat<font color="red"></font></td>
                    <td>
					<textarea name="alamat" class="required"  cols=40 rows=1>' .
        $w['alamat'] .
        '</textarea>
					</td>
                </tr>
                <tr>
                    <td width="150"  valign="top">Kota/Kabupaten<font color="red"> *</font></td>
                    <td  >
					<select name="kode_kota"  class="required"   />' .
        opkota('' . $w['kode_kota'] . '') .
        '</select>
					</td>
                </tr>
                <tr>
                    <td width="150"  valign="top">Propinsi<font color="red"> *</font></td>
                    <td  >
					<select name="kode_propinsi"  class="required"   />' .
        oppropinsi('' . $w['kode_propinsi'] . '') .
        '</select>
					</td>
                </tr>
             	<tr>
                    <td valign="top">Pos<font color="red"> *</font></td>
                    <td>
					<input name="kode_pos"  type="text" class=" required number" id="" value="' .
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
            "SELECT * FROM t_pmb_mahasiswa_ortu where hubungan='AYAH' and idmpmb='" .
                $w['idmpmb'] .
                "' limit 1 "
        )
    );
    $i = $koneksi_db->sql_fetchassoc(
        $koneksi_db->sql_query(
            "SELECT * FROM t_pmb_mahasiswa_ortu where hubungan='IBU' and idmpmb='" .
                $w['idmpmb'] .
                "' limit 1 "
        )
    );
    echo '  
<form action="" method="post"  class="cmxform" id="form_input" style="width:100%">
        <input type="hidden" name="m" value="' .
        $_GET['m'] .
        '"/>
        <input type="hidden" name="op" value="edit"/>
		<input type="hidden" name="sub" value="ortuSav"/>
		<input type="hidden" name="md" value="' .
        $md .
        '"/>
		<input type="hidden" name="idm" value="' .
        $w['idmpmb'] .
        '"/>
	<fieldset  class="ui-widget ui-widget-content ui-corner-all" >
          <legend class="ui-widget ui-widget-header ui-corner-all">Biodata Ayah</legend>
            <table width="600"  border="0" class="datatable full1">
				<tr>
					<td align="left" width="150"  valign="top">Nama<font color="red"> *</font></td>
					<td><input name="a_nama"  type="text" class="full required"  value="' .
        $a['nama'] .
        '" /></td>
				</tr>
				<tr>
					<td  align="left" valign="top">Agama<font color="red"> *</font></td>
					<td  ><select name="a_agama"  class="required "   />' .
        opAplikasi('51', '' . $a['agama'] . '') .
        '</select></td>
				</tr>
				<tr>
					<td  align="left" valign="top">Pendidikan<font color="red"> *</font></td>
					<td  ><select name="a_pendidikan"  class="required "   />' .
        opAplikasi('1', '' . $a['pendidikan'] . '') .
        '</select></td>
				</tr>
				<tr>
					<td  align="left" valign="top">Pekerjaan<font color="red"> *</font></td>
					<td  ><select name="a_pekerjaan"  class="required "   />' .
        opAplikasi('55', '' . $a['pekerjaan'] . '') .
        '</select></td>
				</tr>
				<tr>
					<td  align="left" valign="top">Penghasilan<font color="red"> *</font></td>
					<td  ><select name="a_penghasilan"  class="required "   />' .
        opAplikasi('69', '' . $a['penghasilan'] . '') .
        '</select></td>
				</tr>								
				<tr>
					<td  align="left" valign="top">Status<font color="red"> *</font></td>
					<td  ><select name="a_hidup"  class="required "   />' .
        opAplikasi('53', '' . $a['hidup'] . '') .
        '</select></td>
				</tr>							
                <tr>
                    <td  valign="top">Alamat<font color="red"></font></td>
                    <td>
					<textarea name="a_alamat" class="required"  cols=40 rows=1>' .
        $a['alamat'] .
        '</textarea>
					</td>
                </tr>
                <tr>
                    <td width="150"  valign="top">Kota/Kabupaten<font color="red"> *</font></td>
                    <td  >
					<select name="a_kota"  class="required"   />' .
        opkota('' . $a['kota'] . '') .
        '</select>
					</td>
                </tr>
                <tr>
                    <td width="150"  valign="top">Propinsi<font color="red"> *</font></td>
                    <td  >
					<select name="a_propinsi"  class="required"   />' .
        oppropinsi('' . $a['propinsi'] . '') .
        '</select>
					</td>
                </tr>
             	<tr>
                    <td valign="top">Pos<font color="red"> *</font></td>
                    <td>
					<input name="a_pos"  type="text" class=" required number" id="" value="' .
        $a['pos'] .
        '" />
					</td>
                </tr>
				<tr>
					<td align="left" valign="top">Telepon<font color="red"> *</font></td>
					<td><input name="a_telepon"  type="text" class="number required"  value="' .
        $a['telepon'] .
        '" /></td>
				</tr>
				<tr>
					<td align="left" valign="top">HP<font color="red"> *</font></td>
					<td><input name="a_hp"  type="text" class="number required"  value="' .
        $a['hp'] .
        '" /></td>
				</tr>
				<tr>
					<td align="left" valign="top">Email<font color="red"> *</font></td>
					<td><input name="a_email"  type="text" class="email full required"  value="' .
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
					<td align="left" width="150"  valign="top">Nama<font color="red"> *</font></td>
					<td><input name="i_nama"  type="text" class="full required"  value="' .
        $i['nama'] .
        '" /></td>
				</tr>
				<tr>
					<td  align="left" valign="top">Agama<font color="red"> *</font></td>
					<td  ><select name="i_agama"  class="required "   />' .
        opAplikasi('51', '' . $i['agama'] . '') .
        '</select></td>
				</tr>
				<tr>
					<td  align="left" valign="top">Pendidikan<font color="red"> *</font></td>
					<td  ><select name="i_pendidikan"  class="required "   />' .
        opAplikasi('1', '' . $i['pendidikan'] . '') .
        '</select></td>
				</tr>
				<tr>
					<td  align="left" valign="top">Pekerjaan<font color="red"> *</font></td>
					<td  ><select name="i_pekerjaan"  class="required "   />' .
        opAplikasi('55', '' . $i['pekerjaan'] . '') .
        '</select></td>
				</tr>
				<tr>
					<td  align="left" valign="top">Penghasilan<font color="red"> *</font></td>
					<td  ><select name="i_penghasilan"  class="required "   />' .
        opAplikasi('69', '' . $i['penghasilan'] . '') .
        '</select></td>
				</tr>								
				<tr>
					<td  align="left" valign="top">Status<font color="red"> *</font></td>
					<td  ><select name="i_hidup"  class="required "   />' .
        opAplikasi('53', '' . $i['hidup'] . '') .
        '</select></td>
				</tr>							
                <tr>
                    <td  valign="top">Alamat<font color="red"></font></td>
                    <td>
					<textarea name="i_alamat" class="required"  cols=40 rows=1>' .
        $i['alamat'] .
        '</textarea>
					</td>
                </tr>
                <tr>
                    <td width="150"  valign="top">Kota/Kabupaten<font color="red"> *</font></td>
                    <td  >
					<select name="i_kota"  class="required"   />' .
        opkota('' . $i['kota'] . '') .
        '</select>
					</td>
                </tr>
                <tr>
                    <td width="150"  valign="top">Propinsi<font color="red"> *</font></td>
                    <td  >
					<select name="i_propinsi"  class="required"   />' .
        oppropinsi('' . $i['propinsi'] . '') .
        '</select>
					</td>
                </tr>
             	<tr>
                    <td valign="top">Pos<font color="red"> *</font></td>
                    <td>
					<input name="i_pos"  type="text" class=" required number" id="" value="' .
        $i['pos'] .
        '" />
					</td>
                </tr>
				<tr>
					<td align="left" valign="top">Telepon<font color="red"> *</font></td>
					<td><input name="i_telepon"  type="text" class="number required"  value="' .
        $i['telepon'] .
        '" /></td>
				</tr>
				<tr>
					<td align="left" valign="top">HP<font color="red"> *</font></td>
					<td><input name="i_hp"  type="text" class="number required"  value="' .
        $i['hp'] .
        '" /></td>
				</tr>
				<tr>
					<td align="left" valign="top">Email<font color="red"> *</font></td>
					<td><input name="i_email"  type="text" class="email full required"  value="' .
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
        <input type="hidden" name="m" value="' .
        $_GET['m'] .
        '"/>
        <input type="hidden" name="op" value="edit"/>
		<input type="hidden" name="sub" value="akademikSav"/>
		<input type="hidden" name="md" value="' .
        $md .
        '"/>
		<input type="hidden" name="idm" value="' .
        $w['idmpmb'] .
        '"/>  ';
    echo '
	<fieldset id="asalpt"  class="ui-widget ui-widget-content ui-corner-all" >
          <legend class="ui-widget ui-widget-header ui-corner-all">Data Akademik</legend>
		<table width="600"  border="0" class="datatable full1">
		<tr>
			<td  align="left"  width="150"  valign="top">Program Studi<font color="red"> *</font></td>
			<td  ><select name="kode_prodi"  class="required "   />' .
        opprodi('' . $w['kode_prodi'] . '') .
        '</select></td>
		</tr>

		<tr>
			<td  align="left" valign="top">Status Masuk<font color="red"> *</font></td>
			<td  ><select name="status_masuk"  id="status_masuk" disabled  class="required"   />' .
        opAplikasi('06', '' . $w['status_masuk'] . '') .
        '</select>		</td>
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
			<td  align="left" width="150"  valign="top">Kode Sekolah<font color="red"> *</font></td>
			<td  ><input name="kode_sekolah"  type="text" class=""  value="' .
        $w['kode_sekolah'] .
        '" /></td>
		</tr>
		<tr>
			<td  align="left" valign="top">Nama Sekolah<font color="red"> *</font></td>
			<td  ><input name="nama_sekolah"  type="text" class="full"  value="' .
        $w['nama_sekolah'] .
        '" /></td>
		</tr>
		<tr>
			<td  align="left" valign="top">NIS<font color="red"> *</font></td>
			<td  ><input name="nis_asal"  type="text" class="number full"  value="' .
        $w['nis_asal'] .
        '" /></td>
		</tr>
		<tr>
			<td  align="left" valign="top">Nilai UN<font color="red"> *</font></td>
			<td  ><input name="nilai_un"  type="text" class=" number"  value="' .
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
			<td  align="left" valign="top">Nama Perguruan Tinggi<font color="red"> *</font></td>
			<td  ><input name="PerguruanTinggiID"  type="text" class="full"  value="' .
        $w['PerguruanTinggiID'] .
        '" /></td>
		</tr>
		
		<tr>
			<td  align="left" valign="top">Jenjang<font color="red"> *</font></td>
			<td  ><select name="asal_jenjang"  class=""   />' .
        opAplikasi('04', '' . $w['asal_jenjang'] . '') .
        '</select></td>
		</tr>
	
		<tr>
			<td  align="left" valign="top">NIM Asal<font color="red"> *</font></td>
			<td  ><input name="nim_asal"  type="text" class="number "  value="' .
        $w['nim_asal'] .
        '" /></td>
		</tr>
		<tr>
			<td  align="left" valign="top">SKS Diakui<font color="red"> *</font></td>
			<td  ><input name="sks_diakui"  type="text" class="number"  value="' .
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
                "SELECT * FROM t_pmb_mahasiswa_pendidikan where id='$id' limit 1 "
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
        <input type="hidden" name="m" value="' .
        $_GET['m'] .
        '"/>
        <input type="hidden" name="op" value="edit"/>
		<input type="hidden" name="sub" value="pendidikanSav"/>
		<input type="hidden" name="md" value="' .
        $md .
        '"/>
		<input type="hidden" name="idm" value="' .
        $w['idmpmb'] .
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
                    <td align="right" width="150"  valign="top">Jenjang Studi<font color="red"></font></td>
                    <td>	<select name="jenjang_studi"  class="required"   />' .
        opAplikasi('01', '' . $wp['jenjang_studi'] . '') .
        '</select>		</td>
                </tr>
                <tr>
                    <td align="right" valign="top">Bidang Keilmuan<font color="red"></font></td>
                    <td>	<select name="kode_bidang"  class="required"   />' .
        opAplikasi('42', '' . $wp['kode_bidang'] . '') .
        '</select>		</td>
                </tr>
				
                <tr>
                    <td width="150" align="right" valign="top">Gelar Akademik<font color="red"> *</font></td>
                    <td  >	<input name="gelar"  type="text" class=" required " id="" value="' .
        $wp['gelar'] .
        '" />	</td>
                </tr>
                <tr>
                    <td width="150" align="right" valign="top">Perguruan Tinggi<font color="red"> *</font></td>
                    <td  >	<select name="kode_pt"  class="required"   />' .
        opdaftarpt('' . $wp['kode_pt'] . '') .
        '</select>	</td>
                </tr>
                <tr>
                    <td width="150" align="right" valign="top">Tanggal Ijazah<font color="red"> *</font></td>
                    <td  >	<input name="tgl_ijazah"  type="text" class=" required tcal date" id="" value="' .
        $wp['tgl_ijazah'] .
        '" />	</td>
                </tr>
				
                <tr>
                    <td width="150" align="right" valign="top">Tahun<font color="red"> *</font></td>
                    <td  >	<select name="tahun"  class="required"   />' .
        optahun('' . $wp['tahun'] . '') .
        '</select>	</td>
                </tr>
                <tr>
                    <td width="150" align="right" valign="top">SKS Lulus<font color="red"> *</font></td>
                    <td  >	<input name="sks_lulus"  type="text" class=" required number" id="" value="' .
        $wp['sks_lulus'] .
        '" />	</td>
                </tr>
                <tr>
                    <td width="150" align="right" valign="top">IPK Akhir<font color="red"> *</font></td>
                    <td  >	<input name="ipk_akhir"  type="text" class=" required number" id="" value="' .
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
        "SELECT * FROM `t_pmb_mahasiswa_pendidikan` where idmpmb='$w[idmpmb]' "
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
    global $koneksi_db;

    if (!empty($_SESSION['prodi']) && !empty($_SESSION['pmb_id'])) {
        if (trim($_POST['kode_prodi']) == '') {
            $pesan[] = 'Form Prodimasih kosong, ulangi kembali';
        }
        if (trim($_POST['kode_pmb']) == '') {
            $pesan[] = 'Form Pmb masih kosong, ulangi kembali';
        } elseif (trim($_POST['idm']) == '') {
            $pesan[] = 'Form NIM masih kosong, ulangi kembali';
        } elseif (trim($_POST['nama_mahasiswa']) == '') {
            $pesan[] = 'Form Nama Mahasiswa masih kosong, ulangi kembali';
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
                "&op=add'>";
        } else {
            $wi = $koneksi_db->sql_fetchassoc(
                $koneksi_db->sql_query(
                    "SELECT * FROM m_pmb where pmb_id='" .
                        $_REQUEST['kode_pmb'] .
                        "' limit 1 "
                )
            );
            $s =
                "insert into t_pmb_mahasiswa SET 
					idmpmb='" .
                $_REQUEST['idm'] .
                "',
					kode_pt='" .
                $wi['kode_pt'] .
                "',
					kode_fak='" .
                $wi['kode_fak'] .
                "',
					kode_jenjang='" .
                $wi['kode_jenjang'] .
                "',
					kode_prodi='" .
                $_REQUEST['kode_prodi'] .
                "',
					kode_pmb='" .
                $_REQUEST['kode_pmb'] .
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
					";
            $koneksi_db->sql_query($s);
            //echo $s;
            echo "<meta http-equiv='refresh' content='0; url=index.php?m=" .
                $_GET['m'] .
                '&op=edit&idm=' .
                $_REQUEST['idm'] .
                "'>";
        }
    }
}

////simpan /

function biodataSav()
{
    global $koneksi_db;
    $idm = $_REQUEST['idm'];

    $s =
        "update t_pmb_mahasiswa  set 
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
          hp='" .
        $_REQUEST['hp'] .
        "',
          email='" .
        $_REQUEST['email'] .
        "',
		warga_negara='" .
        $_REQUEST['warga_negara'] .
        "'
				where idmpmb='" .
        $idm .
        "' ";
    $koneksi_db->sql_query($s);
    echo "<div  class='error'>Proses Menyimpan Data...</div>";
    echo "<meta http-equiv='refresh' content='3; url=index.php?m=" .
        $_GET['m'] .
        '&op=edit&sub=Biodata&idm=' .
        $_REQUEST['idm'] .
        "'>";
}

function alamatSav()
{
    global $koneksi_db;
    $idm = $_REQUEST['idm'];

    $s =
        "update t_pmb_mahasiswa  set 
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
				where idmpmb='" .
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

        //$batas_studi = HitungBatasStudi($_REQUEST['semester_masuk'], $_REQUEST['kode_prodi']);

        $s =
            "update t_pmb_mahasiswa  set 
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
            $wi['kode_konsentrasi'] .
            "',
				kode_jenjang='" .
            $wi['kode_jenjang'] .
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
				where idmpmb='" .
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
        "SELECT * FROM t_pmb_mahasiswa_ortu where idmpmb='$idm' and hubungan='AYAH' limit 1 "
    );
    $totala = $koneksi_db->sql_numrows($qa);
    $wa = $koneksi_db->sql_fetchassoc($qa);
    if ($totala > 0) {
        $sa =
            "update t_pmb_mahasiswa_ortu  set 
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
					where idmpmb='" .
            $idm .
            "' and hubungan='AYAH'";
    } else {
        $sa =
            "insert into t_pmb_mahasiswa_ortu SET 
					idmpmb='" .
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
        "SELECT * FROM t_pmb_mahasiswa_ortu where idmpmb='$idm' and hubungan='IBU' limit 1 "
    );
    $totali = $koneksi_db->sql_numrows($qi);
    if ($totali > 0) {
        $si =
            "update t_pmb_mahasiswa_ortu  set 
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
				where idmpmb='" .
            $idm .
            "' and hubungan='IBU'";
    } else {
        $si =
            "insert into t_pmb_mahasiswa_ortu SET 
				 idmpmb='" .
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
            "SELECT * FROM t_pmb_mahasiswa_pendidikan where id='" .
                $_REQUEST['idp'] .
                "' and idmpmb='" .
                $_REQUEST['idm'] .
                "' limit 1 "
        );
        $totalp = $koneksi_db->sql_numrows($qp);
        if ($totalp > 0) {
            $sa =
                "update t_pmb_mahasiswa_pendidikan  set 
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
				where idmpmb='" .
                $idm .
                "'and  id ='" .
                $_REQUEST['idp'] .
                "'";
        } else {
            $sa =
                "insert into t_pmb_mahasiswa_pendidikan SET 
					idmpmb='" .
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

$pmb_id = BuatSesi('pmb_id');
$kolom_mahasiswa = BuatSesi('kolom_mahasiswa');
$kunci_mahasiswa = BuatSesi('kunci_mahasiswa');

if ($_REQUEST['reset_mahasiswa'] == 'Reset') {
    $_SESSION['kolom_mahasiswa'] = '';
    $_SESSION['kunci_mahasiswa'] = '';
}

$go = empty($_REQUEST['op']) ? 'Daftar' : $_REQUEST['op'];

echo '<div id="title" align="right">
        <font style="color:#FF9900; font-size:30px;"><strong>.:</strong></font>
        <font style="font-size:18px; color:#999999">Data Calon Mahasiswa Baru</font><br />
        	<a href="index.php">Home</a> &raquo; <a href="?m=' .
    $_GET['m'] .
    '">Mahasiswa Baru</a>  &raquo; ' .
    $go .
    '  
    </div>';
echo '
<div class="mainContentCell"><div class="content">';
$go();
echo '</div></div>';
?>

<script type="text/javascript">
$(function() {
    $("#help").hover(function() {
        $(this).children("#help div.popupballoon").fadeIn();
    }, function(e) {
        $(this).children("#help  div.popupballoon").hide();
    });
});
</script>