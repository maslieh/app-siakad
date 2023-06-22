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
    echo "<div class=error>Ma'af.. Data tidak dapat dihapus, hanya bisa diedit</div>";
    Daftar();
}

function Daftar()
{
    global $koneksi_db, $tahun_id;
    $prodi = $_SESSION['prodi'];

    echo "<input type=button  class=\"tombols ui-corner-all\" value='Tambah Biaya " .
        viewprodi($prodi) .
        "' onclick=\"window.location.href='index.php?m=" .
        $_GET['m'] .
        "&op=Edit';\">
";
    echo '<table class="table table-striped table-bordered table-hover"  >
   	<thead>
     <tr>
	   <th width="5%" align="center">No</th>
	   <th  align="center">Nama Pembayaran</th>
	   <th  align="center">Dicicil</th> 
	   <th  align="center">Berlaku</th>
		<th  align="center">Biaya</th>
	   <th width="" align="center"></th>
     </tr>
	 </thead>
	 <tbody>';

    $qpp = $koneksi_db->sql_query("SELECT * from `m_biaya` 
		where kode_prodi='$prodi' and tahun_id='$tahun_id'");
    $jumlah = $koneksi_db->sql_numrows($qpp);
    $n = 0;
    if ($jumlah > 0) {
        while ($wf = $koneksi_db->sql_fetchassoc($qpp)) {
            $n++;
            echo "<tr>
			  	<td   >$n.</td>
				<td  >" .
                $wf['nama_bayar'] .
                "</td>
				<td  >" .
                $wf['kali_bayar'] .
                "  </td>
                <td  >" .
                $wf['berlaku'] .
                "  semester</td>
				<td  >Rp. " .
                number_format($wf['biaya']) .
                "</td>
				
				<td  >
					<a class='btn' href=# onclick=window.location.href='index.php?m=" .
                $_GET['m'] .
                "&op=Edit&idb=$wf[idb]'><i class='fa fa-edit'></i></a>
				</td>
				</tr>";
        }
    } else {
        echo '<tr > <th  colspan="7" align=center>Belum ada Data</th></tr>';
    }

    echo '</tbody>
</table>';
}

function Edit()
{
    global $koneksi_db, $tahun_id;
    $prodi = $_SESSION['prodi'];
    $idb = $_REQUEST['idb'];
    //echo $idb;
    if (!empty($idb) && isset($idb)) {
        $wp = $koneksi_db->sql_fetchassoc(
            $koneksi_db->sql_query(
                "SELECT * FROM m_biaya where idb='$idb' limit 1 "
            )
        );
        //$sembunyi = 'style="display:block;"';
        $judul = 'Edit Data Biaya';
        $pilihprodi = $wp['kode_prodi'];
        $pilihtahun = $wp['tahun_id'];
        $biaya = Terbilang($wp['biaya']);
    } else {
        //$sembunyi = 'style="display:none;"';
        $judul = 'Tambah Data Biaya';
        $pilihprodi = $prodi;
        $pilihtahun = $tahun_id;
        $wp = '';
        $biaya = '';
    }
    $cicilan = explode('#', $wp['cicilan']);
    ?>
<SCRIPT type=text/javascript>
var i = (2 < 2) ? 2 : 2;

function addRow(tabel) {
    var rowCount = $('#' + tabel + ' tr').length;
    $('#' + tabel + ' tbody>tr:last').clone(true).insertAfter('#' + tabel + ' tbody>tr:last');
}

function removeRow(tabel, count) {
    var rowCount = $('#' + tabel + ' tr').length;
    if (rowCount > count)
        $('#' + tabel + ' tr:last').remove();
}
</SCRIPT>

<form action="" method="post" class="cmxform" id="form_input" style="width:100%">
    <input type="hidden" name="m" value="<?= $_GET['m'] ?>" />
    <input type="hidden" name="op" value="simpan" />
    <input type="hidden" name="idb" value="<?= $idb ?>" />
    <fieldset class="ui-widget ui-widget-content ui-corner-all">
        <legend class="ui-widget ui-widget-header ui-corner-all"><?= $judul ?></legend>
        &nbsp;<font color="red"><br></font>
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="datatable full1">

            <tr>
                <td width="150" align="left" valign="top">Program Studi<font color="red"> *</font>
                </td>
                <td> <select name="kode_prodi" class="required" /><?= opprodi(
                    '' . $pilihprodi . ''
                ) ?></select> </td>
            </tr>
            <tr>
                <td width="150" align="left" valign="top">Tahun<font color="red"> *</font>
                </td>
                <td> <select name="tahun_id" class="required" /><?= optapel(
                    '' . $pilihtahun . ''
                ) ?></select> </td>
            </tr>
            <tr>
                <td width="150" align="left" valign="top">Pembayaran<font color="red"> *</font>
                </td>
                <td> <input name="nama_bayar" type="text" class=" required number" id="" value="<?= $wp[
                    'nama_bayar'
                ] ?>" /></td>
            </tr>
            <tr>
                <td width="150" align="left" valign="top">Cicilan kali?<font color="red"> *</font>
                </td>
                <td> <input name="kali_bayar" type="text" class=" required number" id="" value="<?= $wp[
                    'kali_bayar'
                ] ?>" /></td>
            </tr>
            <tr>
                <td width="150" align="left" valign="top">Berlaku<font color="red"> *</font>
                </td>
                <td> <input name="berlaku" type="text" class=" required number" id="" value="<?= $wp[
                    'berlaku'
                ] ?>" /></td>
            </tr>
            <tr>
                <td width="150" align="left" valign="top">Besar Biaya<font color="red"> *</font>
                </td>
                <td> <input name="biaya" type="text" class=" required number" id="" value="<?= $wp[
                    'biaya'
                ] ?>" /></td>
            </tr>

            <tr>
                <td colspan="2">
                    <input type="submit" class="tombols ui-corner-all" value="Simpan" />
                    <input type="button" class="tombols ui-corner-all" value="Kembali" onClick="window.location = 'index.php?m=<?= $_GET[
                        'm'
                    ] ?>'" />
                </td>
            </tr>
        </table>
    </fieldset>
</form>

<?php
}

////simpan /
function simpan()
{
    global $koneksi_db;
    $idb = $_REQUEST['idb'];

    if (trim($_POST['biaya']) == '') {
        $pesan[] = 'Form Biaya masih kosong, ulangi kembali';
    } elseif (trim($_POST['kode_prodi']) == '') {
        $pesan[] = 'Form Prodi masih kosong, ulangi kembali';
    } elseif (trim($_POST['tahun_id']) == '') {
        $pesan[] = 'Form Tahun masih kosong, ulangi kembali';
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
            "'>";
    } else {
        $wi = $koneksi_db->sql_fetchassoc(
            $koneksi_db->sql_query(
                "SELECT * FROM m_program_studi where kode_prodi='" .
                    $_REQUEST['kode_prodi'] .
                    "' limit 1 "
            )
        );
        $adaa = $koneksi_db->sql_numrows(
            $koneksi_db->sql_query("SELECT * FROM `m_biaya` 
								where kode_prodi='$_POST[kode_prodi]' 
								and tahun_id='$_POST[tahun_id]' and nama_bayar='$_POST[nama_bayar]'")
        );
        //$cicilan = implode('#', $_POST['cicilan']);
        if ($adaa > 0 && empty($idb)) {
            echo "<script>alert('Biaya sudah pernah di input !')</script>";
        } elseif (!empty($idb)) {
            $s =
                "  update m_biaya set 
                nama_bayar='" .
                $_REQUEST['nama_bayar'] .
                "',
				kali_bayar='" .
                $_REQUEST['kali_bayar'] .
                "',
					tahun_id='" .
                $_REQUEST['tahun_id'] .
                "',
                berlaku='" .
                $_REQUEST['berlaku'] .
                "',
					biaya='" .
                $_REQUEST['biaya'] .
                "'
					where idb='" .
                $_REQUEST['idb'] .
                "' 
					";
            $koneksi_db->sql_query($s);
        } else {
            $s =
                "INSERT INTO m_biaya set 
			  		kode_pt='" .
                $wi['kode_pt'] .
                "',
					kode_fak='" .
                $wi['kode_fak'] .
                "',
					kode_jenjang='" .
                $wi['kode_jenjang'] .
                "',
					kode_konsentrasi='" .
                $wi['kode_konsentrasi'] .
                "',
					kode_prodi='" .
                $_REQUEST['kode_prodi'] .
                "',
				nama_bayar='" .
                $_REQUEST['nama_bayar'] .
                "',
				kali_bayar='" .
                $_REQUEST['kali_bayar'] .
                "',
					tahun_id='" .
                $_REQUEST['tahun_id'] .
                "',
                berlaku='" .
                $_REQUEST['berlaku'] .
                "',
					biaya='" .
                $_REQUEST['biaya'] .
                "'
					";
            $koneksi_db->sql_query($s);
        }
    }
    echo "<meta http-equiv='refresh' content='2; url=index.php?m=" .
        $_GET['m'] .
        "'>";
    //echo $s;
    Daftar();
}

$go = empty($_REQUEST['op']) ? 'Daftar' : $_REQUEST['op'];
echo '<div id="title" align="right">
        <font style="color:#FF9900; font-size:30px;"><strong>.:</strong></font>
        <font style="font-size:18px; color:#999999">Master Biaya</font><br />
        	<a href="index.php">Home</a> &raquo; <a href="index.php?m=' .
    $_GET['m'] .
    '">Set Biaya</a>  &raquo; ' .
    $go .
    '  
    </div>';
echo '<div class="mainContentCell"><div class="content">';
$go();
echo '</div></div>';
?>
