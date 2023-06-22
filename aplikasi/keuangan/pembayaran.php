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

function Daftar()
{
    global $koneksi_db, $user, $tahun_id, $w;
    ///// opsi mahasiswa dan bukan mahasiswa
    if ($_SESSION['Level'] != 'MAHASISWA') {
        FilterMahasiswa($_GET['m']);
        $whr = [];
        $ord = '';
        if (
            $_SESSION['reset_mahasiswa'] == 'Reset' &&
            empty($_SESSION['kolom_mahasiswa']) &&
            empty($_SESSION['kunci_mahasiswa'])
        ) {
            echo '';
        } else {
            $whr[] = "$_SESSION[kolom_mahasiswa] like '%$_SESSION[kunci_mahasiswa]%' ";
            $ord = "order by $_SESSION[kolom_mahasiswa]";
        }
        $whr[] = "status_aktif='A'";
        if (!empty($whr)) {
            $strwhr = 'where ' . implode(' and ', $whr);
        }

        $ambilmhs = $koneksi_db->sql_query(
            "SELECT a.*,b.* from t_mahasiswa_krs a inner join m_mahasiswa b on a.idm=b.idm $strwhr  limit 1 "
        );
    } else {
        $ambilmhs = $koneksi_db->sql_query(
            'SELECT a.*,b.* FROM t_mahasiswa_krs a inner join m_mahasiswa b on a.idm=b.idm where a.idm=b.idm  limit 1 '
        );
    }

    if ($koneksi_db->sql_numrows($ambilmhs) > 0) {

        $wm = $koneksi_db->sql_fetchassoc($ambilmhs);
        $status = $wm['status_aktif'];
        //$tahunbayar=$wm['tahun_id'];
        $idm = $wm['idm'];
        $fotonya =
            $wm['foto'] == ''
                ? 'gambar/no_avatars.gif'
                : 'gambar/' . $wm['foto'] . '';

        echo '
		<table  border="0" cellspacing="1"class="datatable full" cellpadding="1">
		<thead>
		  <tr>
			<td width="149">NIM</td>
			<td width="436"><b>' .
            $wm['NIM'] .
            '</b></td>
			<td width="37" valign="top" rowspan="5"><img src="' .
            $fotonya .
            '" width="160" height="200"></td>
		  </tr>
		  <tr>
			<td>NAMA</td>
			<td><b >' .
            $wm['nama_mahasiswa'] .
            '</b></td>
		  </tr>
		  <tr>
			<td>KONSENTRASI</td>
			<td><b >' .
            viewkonsentrasi('' . $wm['kode_konsentrasi'] . '') .
            '</b ></td>
		  </tr>
		  <tr>
			<td>PROGRAM STUDI </td>
			<td><b >' .
            viewprodi('' . $wm['kode_prodi'] . '') .
            '</b ></td>
		  </tr>
		  </thead>
		</table>
	  ';

        ///// end opsi mahasiswa dan bukan mahasiswa
        $biaya = $koneksi_db->sql_fetchassoc(
            $koneksi_db->sql_query(
                "SELECT * FROM m_biaya where kode_prodi='" .
                    $wm['kode_prodi'] .
                    "' and tahun_id='$tahun_id' limit 1 "
            )
        );
        $byr = $koneksi_db->sql_fetchassoc(
            $koneksi_db->sql_query(
                "SELECT sum(bayar) AS B FROM t_mahasiswa_bayar where idm='$idm' and biaya='" .
                    $biaya['biaya'] .
                    "'  "
            )
        );
        $bayarke =
            $koneksi_db->sql_numrows(
                $koneksi_db->sql_query(
                    "SELECT * FROM t_mahasiswa_bayar where idm='$idm' and biaya='" .
                        $biaya['biaya'] .
                        "'"
                )
            ) + 1;
        //$cicilan = explode("#", $biaya["cicilan"]);
        //$jbayar = $koneksi_db->sql_fetchassoc($byr);
        $bayarlunas = $biaya['biaya'] - $byr['B'];

        //echo $byr['B'];;

        //   if ($biaya['biaya'] <= $byr['B']) {
        //        echo '<h2><font color=blue>Mahasiswa bersangkutan ddsudah lunas</font></h2>';
        //    } else {
        ?>
<script type="text/javascript">
function ubahstatus() {
    var tipe = document.getElementById("jenisbayar").value;
    if (tipe == "Lunas") {
        //$('#bayarcicil').attr('disabled','disabled');
        //$('#bayarlunas').removeAttr('disabled');
        $('#lunas').show();
        $('#cicil').hide();
        $('#bayar').val(<?= $bayarlunas ?>);
    } else if (tipe == "Cicil") {
        //$('#bayarlunas').attr('disabled','disabled');
        //$('#bayarcicil').removeAttr('disabled');
        $('#cicil').show();
        $('#lunas').hide();
        $('#bayar').val(<?= $cicilan[$bayarke - 1] ?>);
    }
}
</script>
<?php echo ' <br/> 
			<form action="" method="post"  class="cmxform" id="form_input" style="width:100%">
   	<input type="hidden" name="id" value="' .
    $w['id'] .
    '"/>
				<input type="hidden" name="idm" value="' .
    $wm['idm'] .
    '"/>
				<input type="hidden" name="m" value="' .
    $_GET['m'] .
    '"/>
				<input type="hidden" name="op" value="simpan"/>
				<input type="hidden" name="urut" value="' .
    $bayarke .
    '"/>
				<input type="hidden" name="biaya" value="' .
    $biaya['biaya'] .
    '"/>
    	<input type="hidden" name="tahun_id" value="' .
    $tahun_id .
    '"/>
			   
				<fieldset class="ui-widget ui-widget-content ui-corner-all" >
					
				<table width="100%"  border="0">
				<tr>
			<td  align="left" valign="top">Pembayaran<font color="red"> *</font></td>
			<td  ><select name="idb"  required   />' .
    bayar('' . $w['idb'] . '') .
    '</select></td>
		</tr>
				<tr>
				<td width="200" align="left" valign="top">Tanggal Bayar<font color="red"> *</font></td>
				<td><input name="tanggal"  type="text" class="tcal date required"  value="' .
    $w['tanggal'] .
    '" /></td>
				</tr>
				<tr>
				<td width="200" align="left" valign="top">Pembayaran<font color="red"> *</font></td>
				<td><input name="bayar"  type="text" class=" required"/></td>
				</tr>
                <tr>
				<td width="200" align="left" valign="top">Keterangan<font color="red"> *</font></td>
				<td><input name="ket"  type="text" class="tcal  required"  value="' .
    $w['ket'] .
    '" /></td>
				</tr>
				<tr><td colspan=2>
				<input type="submit" class=tombols ui-corner-all value="Simpan"/> 
				<input type="button" class=tombols ui-corner-all value="Kembali" onClick="window.location = \'index.php?m=' .
    $_GET['m'] .
    '\'"/>
				</td></tr>
				</table>
				</fieldset>
				</form> ';
//   }
    }
}

function simpan()
{
    global $koneksi_db, $user, $tahun_id;
    $id = $_REQUEST['id'];
    $idb = $_REQUEST['idb'];
    $idm = $_REQUEST['idm'];
    $w = $koneksi_db->sql_fetchassoc(
        $koneksi_db->sql_query(
            "SELECT * FROM m_mahasiswa where idm='$idm' limit 1 "
        )
    );
    $prodi = $w['kode_prodi'];
    $db = $koneksi_db->sql_fetchassoc(
        $koneksi_db->sql_query(
            "SELECT * FROM m_biaya where idb='$idb' and kode_prodi = '$prodi'limit 1 "
        )
    );
    $biaya = $db['biaya'];
    $sisaan = $koneksi_db->sql_numrows(
        $ss = $koneksi_db->sql_query(
            "SELECT * FROM t_mahasiswa_bayar where idm='$idm' and idb='$idb'"
        )
    );
    if ($sisaan == 0) {
        $jml_bayar = $_REQUEST['bayar'];
        $sisa = $biaya - $_REQUEST['bayar'];
        $biaya1 = $db['biaya'];
    } else {
        $db = $koneksi_db->sql_fetchassoc(
            $koneksi_db->sql_query(
                "SELECT max(sisa) as sisa FROM t_mahasiswa_bayar where  idm='$idm' and idb='$idb'"
            )
        );
        $db1 = $koneksi_db->sql_fetchassoc(
            $koneksi_db->sql_query(
                "SELECT sum(bayar) as bayar FROM t_mahasiswa_bayar where  idm='$idm' and idb='$idb'"
            )
        );
        $jml_bayar = $db1['bayar'] + $_REQUEST['bayar'];
        $sisa = $db['sisa'] - $_REQUEST['bayar'];
        $biaya1 = '0';
    }
    //  echo $sisa;
    $s =
        "INSERT INTO t_mahasiswa_bayar set 
			kode_pt='" .
        $w['kode_pt'] .
        "',
			kode_fak='" .
        $w['kode_fak'] .
        "',
			kode_jenjang='" .
        $w['kode_jenjang'] .
        "',
			kode_konsentrasi='" .
        $w['kode_konsentrasi'] .
        "',
			kode_prodi='" .
        $w['kode_prodi'] .
        "',
			tahun_id='" .
        $tahun_id .
        "',
			idm='" .
        $_REQUEST['idm'] .
        "',
			tanggal='" .
        $_REQUEST['tanggal'] .
        "',
			urut='" .
        $_REQUEST['urut'] .
        "',
			biaya='" .
        $biaya1 .
        "',
        sisa='" .
        $sisa .
        "',
        jml_bayar='" .
        $jml_bayar .
        "',
			bayar='" .
        $_REQUEST['bayar'] .
        "',
		idb='" .
        $_REQUEST['idb'] .
        "',
		ket='" .
        $_REQUEST['ket'] .
        "',
			petugas='" .
        $user .
        "'
			";
    $koneksi_db->sql_query($s);
    //echo $s;
    echo '<div class="sukses"><b>Pembayaran Berhasil disimpan</b></div><br />';
    echo "<meta http-equiv='refresh' content='2; url=index.php?m=" .
        $_GET['m'] .
        "'>";
    $_SESSION['kolom_mahasiswa'] = '';
    $_SESSION['kunci_mahasiswa'] = '';
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
        <font style="font-size:18px; color:#999999">Pembayaran Mahasiswa</font><br />
        	<a href="index.php">Home</a> &raquo; <a href="index.php?m=' .
    $_GET['m'] .
    '">Pembayaran</a>  &raquo; ' .
    $go .
    '  
    </div>';
echo '<div class="mainContentCell"><div class="content">';
$go();
echo '</div></div>';

?>
