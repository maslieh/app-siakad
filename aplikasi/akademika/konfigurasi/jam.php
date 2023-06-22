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
    global $koneksi_db;
    $prodi = $_SESSION['prodi'];
    $idj = $_REQUEST['idj'];

    if (!empty($idj) && isset($idj)) {
        $wp = $koneksi_db->sql_fetchassoc(
            $koneksi_db->sql_query(
                "SELECT * FROM m_jam where idj='$idj' and kode_prodi='$prodi' limit 1 "
            )
        );
        $sembunyi = 'style="display:block;"';
        $judul = 'Edit Data Jam Perkuliahan';
        $pilihprodi = $wp['kode_prodi'];
    } else {
        $sembunyi = 'style="display:none;"';
        $judul = 'Tambah Data Jam Perkuliahan';
        $pilihprodi = $prodi;
    }
    echo "<input type=button  class=\"tombols ui-corner-all\" value='Tambah Jam Kuliah' onclick=\"return toggleView('form-hide')\" >";
    echo '<div id="form-hide" ' . $sembunyi . '>';
    echo '
<div class="table-responsive">
<form action="" method="post"  class="cmxform" id="form_input" style="width:100%">
        <input type="hidden" name="m" value="' .
        $_GET['m'] .
        '"/>
        <input type="hidden" name="op" value="simpan"/>
		<input type="hidden" name="idj" value="' .
        $idj .
        '"/> 
        <fieldset class="ui-widget ui-widget-content ui-corner-all" >
            <legend class="ui-widget ui-widget-header ui-corner-all">' .
        $judul .
        '</legend>
            &nbsp;<font color="red"><br></font>
            <table width="600"  border="0"  class="datatable full1">		
                <tr>
                    <td  align="right" valign="top">Program Studi<font color="red"> *</font></td>
                    <td  >	<select name="kode_prodi" style="width:300px" class="required"   />' .
        opprodi('' . $pilihprodi . '') .
        '</select>	</td>
                </tr>
                <tr>
                    <td width="150" align="right" valign="top">Jam Ke<font color="red"> *</font></td>
                    <td  >	<select name="jam"  class="required number"   />' .
        opwaktu('' . $wp['jam'] . '') .
        '</select>		</td>
                </tr>
                <tr>
                    <td width="150" align="right" valign="top">Dari Jam<font color="red"> *</font></td>
                    <td  >	<input name="mulai"  type="time" class=" required time" id="" value="' .
        $wp['mulai'] .
        '" /> </td>
                </tr>
                <tr>
                    <td width="150" align="right" valign="top">Sampai Jam<font color="red"> *</font></td>
                    <td  >	<input name="sampai"  type="time" class=" required time" id="" value="' .
        $wp['sampai'] .
        '" /> </td>
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
    echo '</div></div>';

    echo '<table class="table table-striped table-bordered table-hover"  >
   	<thead>
     <tr>
	   <th width="5%" align="center">No</th>
	   <th  align="center">Jam</th>
	   <th  align="center">Dari</th>
	   <th  align="center">Sampai</th>
	   <th width="10%" align="center">ID JAM</th>
	   <th width="30%" align="center"></th>
     </tr>
	 </thead>
	 <tbody>';

    $qpp = $koneksi_db->sql_query(
        "SELECT * FROM `m_jam` where kode_prodi='$prodi' "
    );
    $jumlah = $koneksi_db->sql_numrows($qpp);
    if ($jumlah > 0) {
        while ($wf = $koneksi_db->sql_fetchassoc($qpp)) {
            $n++;
            echo "<tr>
			  	<td $c>$n</td>
				<td $c>$wf[jam]</td>
				<td $c>$wf[mulai]</td>
				<td $c>$wf[sampai]</td>
			  	<td $c>$wf[idj]</td>
				<td $c>
					<a href=# class=btn onclick=window.location.href='index.php?m=" .
                $_GET['m'] .
                "&op=Daftar&idj=$wf[idj]'><i class='fa fa-folder'></i></a>
					<a href=# class=btn onclick=window.location.href='index.php?m=" .
                $_GET['m'] .
                "&op=Daftar&idj=$wf[idj]'><i class='fa fa-edit'></i></a>
					<a href=# class=btn onclick=window.location.href='index.php?m=" .
                $_GET['m'] .
                "&op=hapus&idj=$wf[idj]'><i class='fa fa-trash-o'></i></a>
				</td>
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
function simpan()
{
    global $koneksi_db;
    $idj = $_REQUEST['idj'];

    if (trim($_POST['jam']) == '') {
        $pesan[] = 'Form Jam masih kosong, ulangi kembali';
    } elseif (trim($_POST['kode_prodi']) == '') {
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
            "'>";
    } else {
        $wi = $koneksi_db->sql_fetchassoc(
            $koneksi_db->sql_query(
                "SELECT * FROM m_program_studi where kode_prodi='" .
                    $_REQUEST['kode_prodi'] .
                    "' limit 1 "
            )
        );

        if (!empty($idj)) {
            $s =
                "update m_jam set 
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
					jam='" .
                $_REQUEST['jam'] .
                "',
					mulai='" .
                $_REQUEST['mulai'] .
                "',
					sampai='" .
                $_REQUEST['sampai'] .
                "'
					where idj='" .
                $_REQUEST['idj'] .
                "' ";
            $koneksi_db->sql_query($s);
        } else {
            $s =
                "INSERT INTO m_jam set 
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
					jam='" .
                $_REQUEST['jam'] .
                "',
					mulai='" .
                $_REQUEST['mulai'] .
                "',
					sampai='" .
                $_REQUEST['sampai'] .
                "'
					";
            $koneksi_db->sql_query($s);
        }
    }
    //echo $s;
    Daftar();
}

$go = empty($_REQUEST['op']) ? 'Daftar' : $_REQUEST['op'];
echo '<div id="title" align="right">
        <font style="color:#FF9900; font-size:30px;"><strong>.:</strong></font>
        <font style="font-size:18px; color:#999999">Master Jam Kuliah</font><br />
        	<a href="index.php">Home</a> &raquo; <a href="?m=' .
    $_GET['m'] .
    '">Jam</a>  &raquo; ' .
    $go .
    '  
    </div>';
echo '<div class="mainContentCell"><div class="content">';
$go();
echo '</div></div>';
?>