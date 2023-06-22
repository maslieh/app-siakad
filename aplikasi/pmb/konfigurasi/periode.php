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

function PMB()
{
    global $koneksi_db, $prodi;
    echo "<input type=button  class=\"tombols ui-corner-all\" value='Tambah Masa PMB ' onclick=\"window.location.href='index.php?m=" .
        $_GET['m'] .
        "&op=editpmb&md=1';\">
";
    //	if (!empty($prodi)) $whr = "where kode_prodi='$prodi'";

    $sql = 'select * from m_pmb ';
    $q = $koneksi_db->sql_query($sql);
    $jumlah = $koneksi_db->sql_numrows($q);
    if ($jumlah > 0) {
        echo '
<div class="table-responsive">
<table class="table table-striped table-bordered table-hover" id="dataTables-example">
   	<thead>
     <tr>
	   <th width="5%" align="center">No</th>
	   <th width="15%" align="center">Kode</th>
       <th width="15%" align="center">Tgl. Mulai</th>
       <th width="10%" align="center">Tgl. USM</th>
	    <th width="10%" align="center">Tgl. Her</th>
		<th width="10%" align="center">Nilai Minimal</th>
		<th width="10%" align="center">Status</th>
	   <th width="5%" align="center"></th>
     </tr>
	 </thead>
	 <tbody>';

        while ($wr = $koneksi_db->sql_fetchassoc($q)) {
            $n++;
            $id = $wr['pmb_id'];

            if ($wr['buka'] == 'N') {
                $tombol =
                    '<input class=button-red  type="submit"  name="BUKA" value="Tutup">';
            } else {
                $tombol =
                    '<input class=button-blue  type="submit" disabled="disabled" name="TUTUP" value="Buka">';
            }

            echo '<tr bgcolor="#f2f2f2">
					<td  align=center>' .
                $n .
                '</td> 
				   <td  align=center>' .
                $wr['pmb_id'] .
                '</a></td>
				   <td  align=left>' .
                $wr['tgl_mulai'] .
                '</td>
				   <td  align=left>' .
                $wr['ujian_mulai'] .
                '</td>
				   <td  align=left>' .
                $wr['bayar_mulai'] .
                '</td>
				   <td  align=left>' .
                $wr['nilai_minimal'] .
                '</td>
				   <td  align=left>
				   <form method="post" action="" id="namaform">
         			<input type="hidden" name="m" value="pmb.setup"/>
        			<input type="hidden" name="op" value="buka"/>
					<input name="id" type="hidden" id="id" value="' .
                $id .
                '">
					' .
                $tombol .
                '
					</form>
				   </td>
					<td  align=center>
					<a href="#" class="btn" onclick="window.location.href=\'index.php?m=' .
                $_GET['m'] .
                '&op=editpmb&md=0&id=' .
                $id .
                '\';"><i class="fa fa-edit"></i></a>
					</td>
				 </tr>';
        }

        echo '</tbody>
		</table></div>';
    } else {
        echo '<div class="alert alert-danger"> 
			Belum ada Data
			</div>';
    }
}

function editpmb()
{
    global $koneksi_db, $prodi, $tahun_id;
    $md = $_REQUEST['md'] + 0;
    if ($md == 0) {
        $id = $_REQUEST['id'];
        $w = $koneksi_db->sql_fetchassoc(
            $koneksi_db->sql_query(
                "SELECT * FROM m_pmb where pmb_id='$id' limit 1 "
            )
        );
        $jdl = 'Edit Masa PMB';
        $pilihprodi = $w['kode_prodi'];
    } else {
        $w = [];
        $jdl = 'Tambah Masa PMB';
        $pilihprodi = $prodi;
        $ww = $koneksi_db->sql_fetchassoc(
            $koneksi_db->sql_query('SELECT max(pmb_id) as kodemax FROM m_pmb ')
        );
        $kode = $ww['kodemax'];
        $noUrut = (int) substr($kode, 3, 3);
        $noUrut++;
        $char = 'REG' . substr(date('Y'), 2, 2) . substr(date('m'), -2);
        $id = $char . sprintf('%03s', $noUrut);
    }

    echo '  
<div class="panel-body">
    <form action="" method="post"  id="form_input" style="width:100%">
		<input type="hidden" name="id" value="' .
        $id .
        '"/>
         <input type="hidden" name="m" value="' .
        $_GET['m'] .
        '"/>
        <input type="hidden" name="op" value="SimpanPMB"/>
		<input type="hidden" name="md" value="' .
        $md .
        '"/>
		
<div class="form-group row">
		<!--	<label for="prodi" class="col-sm-4 col-form-label" align="right">Program Studi<font color="red"> *</font></label>
			<div class="col-sm-8">
			  <select name="kode_prodi"  class="form-control form-control-sm"   />' .
        opprodi('' . $pilihprodi . '') .
        '</select>
			</div>-->
		</div>
		<div class="form-group row">
			<label for="tahun" class="col-sm-4 col-form-label" align="right">Tahun PMB<font color="red"> *</font></label>
			<div class="col-sm-8">
			  <select name="tahun"  class="form-control form-control-sm"  required />' .
        optahun('' . $w['tahun'] . '') .
        '</select>
			</div>
		</div>
		<div class="form-group row">
			<label for="kapasitas" class="col-sm-4 col-form-label" align="right">Kapasitas<font color="red"> *</font></label>
			<div class="col-sm-8">
			 <input name="kapasitas"   placeholder="30" type="number" class="form-control form-control-sm number" required id="" value="' .
        $w['kapasitas'] .
        '" />
			</div>
		</div>
		<div class="form-group row">
			<label for="kapasitas" class="col-sm-4 col-form-label" align="right">Biaya Pendaftaran<font color="red"> *</font></label>
			<div class="col-sm-8">
			 <input name="harga"   placeholder="2000000" type="number" class="form-control form-control-sm number" required id="" value="' .
        $w['harga'] .
        '" />
			</div>
		</div>
		<div class="form-group row">
			<label for="kapasitas" class="col-sm-4 col-form-label" align="right">Nilai Minimal Lulus USM<font color="red"> *</font></label>
			<div class="col-sm-8">
			 <input name="nilai_minimal"   placeholder="80" type="number" class="form-control form-control-sm number" required id="" value="' .
        $w['nilai_minimal'] .
        '" />
			</div>
		</div>
        <div class="form-group row">
			<label for="Pendaftaran" class="col-sm-4 col-form-label" align="right">Pendaftaran<font color="red"> *</font></label>
			<div class="col-sm-4">
				<input name="tgl_mulai"  placeholder="Tanggal Mulai"  type="text" class="form-control form-control-sm tcal date required" id="" value="' .
        $w['tgl_mulai'] .
        '" />
			</div>
			<div class="col-sm-4">
				<input name="tgl_selesai" placeholder="Tanggal Selesai" type="text" class="form-control form-control-sm tcal date required" id="" value="' .
        $w['tgl_selesai'] .
        '" />
			</div>
		</div>
		<div class="form-group row">
			<label for="ujian" class="col-sm-4 col-form-label" align="right">Ujian Saringan Masuk<font color="red"> *</font></label>
			<div class="col-sm-4">
				<input name="ujian_mulai"  placeholder="Ujian Mulai"  type="text" class="form-control form-control-sm tcal date required" id="" value="' .
        $w['ujian_mulai'] .
        '" />
			</div>
			<div class="col-sm-4">
				<input name="ujian_selesai" placeholder="Ujian Selesai" type="text" class="form-control form-control-sm tcal date required" id="" value="' .
        $w['ujian_selesai'] .
        '" />
			</div>
		</div>
		<div class="form-group row">
			<label for="Pembayaran" class="col-sm-4 col-form-label" align="right">Pembayaran dan Pendaftaran Ulang<font color="red"> *</font></label>
			<div class="col-sm-4">
				<input name="bayar_mulai"  placeholder="Pembayaran Mulai"  type="text" class="form-control form-control-sm tcal date required" id="" value="' .
        $w['bayar_mulai'] .
        '" />
			</div>
			<div class="col-sm-4">
				<input name="bayar_selesai" placeholder="Pembayaran Selesai" type="text" class="form-control form-control-sm tcal date required" id="" value="' .
        $w['bayar_selesai'] .
        '" />
			</div>
			
		</div>
		<div class="form-check">
			<input type="submit" class="tombols ui-corner-all" value="Simpan"/> 
			<input type="button" class="tombols ui-corner-all" value="Kembali" onClick="window.location = \'index.php?m=' .
        $_GET['m'] .
        '\'"/></td>
		</div>
			
    </form></div>
  </div>
 ';
}

////simpan /
function SimpanPMB()
{
    global $koneksi_db, $prodi;
    $md = $_REQUEST['md'] + 0;
    $id = $_REQUEST['id'];

    if (trim($_POST['harga']) == '') {
        $pesan[] = 'Form Kode Prodi kosong, ulangi kembali';
    } elseif (trim($_POST['kapasitas']) == '') {
        $pesan[] = 'Form kapasitas masih kosong, ulangi kembali';
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
        echo "<meta http-equiv='refresh' content='1; url=index.php?m=" .
            $_GET['m'] .
            "&op=editpmb&md=1'>";
    } else {
        $wi = $koneksi_db->sql_fetchassoc(
            $koneksi_db->sql_query(
                "SELECT * FROM m_program_studi where kode_prodi='" .
                    $_REQUEST['kode_prodi'] .
                    "' limit 1 "
            )
        );

        if ($md == 0) {
            $s =
                "update m_pmb set 
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
					tahun='" .
                $_REQUEST['tahun'] .
                "',
					kapasitas='" .
                $_REQUEST['kapasitas'] .
                "',
					harga='" .
                $_REQUEST['harga'] .
                "',
					nilai_minimal='" .
                $_REQUEST['nilai_minimal'] .
                "',
					tgl_mulai='" .
                $_REQUEST['tgl_mulai'] .
                "',
					tgl_selesai='" .
                $_REQUEST['tgl_selesai'] .
                "',
					ujian_mulai='" .
                $_REQUEST['ujian_mulai'] .
                "',
					ujian_selesai='" .
                $_REQUEST['ujian_selesai'] .
                "',
					bayar_mulai='" .
                $_REQUEST['bayar_mulai'] .
                "',
					bayar_selesai='" .
                $_REQUEST['bayar_selesai'] .
                "'
					where pmb_id='" .
                $_REQUEST['id'] .
                "'
					 ";
            $koneksi_db->sql_query($s);
        } else {
            $idx = $_REQUEST['tahun'] . '' . $_REQUEST['semester'];
            $s =
                "INSERT INTO m_pmb set 
					pmb_id='" .
                $_REQUEST['id'] .
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
					kode_konsentrasi='" .
                $wi['kode_konsentrasi'] .
                "',
					kode_prodi='" .
                $_REQUEST['kode_prodi'] .
                "',
					tahun='" .
                $_REQUEST['tahun'] .
                "',
					kapasitas='" .
                $_REQUEST['kapasitas'] .
                "',
					harga='" .
                $_REQUEST['harga'] .
                "',
					nilai_minimal='" .
                $_REQUEST['nilai_minimal'] .
                "',
					tgl_mulai='" .
                $_REQUEST['tgl_mulai'] .
                "',
					tgl_selesai='" .
                $_REQUEST['tgl_selesai'] .
                "',
					ujian_mulai='" .
                $_REQUEST['ujian_mulai'] .
                "',
					ujian_selesai='" .
                $_REQUEST['ujian_selesai'] .
                "',
					bayar_mulai='" .
                $_REQUEST['bayar_mulai'] .
                "',
					bayar_selesai='" .
                $_REQUEST['bayar_selesai'] .
                "'
					";
            $koneksi_db->sql_query($s);
        }
    }
    //echo $s;
    PMB();
}

function buka()
{
    global $koneksi_db;

    $sql =
        "UPDATE m_pmb SET 
			buka='Y'
			where pmb_id='" .
        $_POST['id'] .
        "' ";

    $koneksi_db->sql_query($sql);

    $sql2 =
        "UPDATE m_pmb SET 
			buka='N'
			where pmb_id!='" .
        $_POST['id'] .
        "' ";
    $koneksi_db->sql_query($sql2);

    PMB();
}

////////////////////

$go = empty($_REQUEST['op']) ? 'PMB' : $_REQUEST['op'];

echo '<div id="title" align="right">
        <font style="color:#FF9900; font-size:30px;"><strong>.:</strong></font>
        <font style="font-size:18px; color:#999999">Konfigurasi PMB</font><br />
        	<a href="index.php">Home</a> &raquo; <a href="index.php?m=' .
    $_GET['m'] .
    '">Periode PMB</a>  &raquo; ' .
    $go .
    '  
    </div>';

echo '<div  class="panes" id="panel1" style="display: block;">
<div class="mainContentCell"><div class="content">	';
$go();
echo '</div></div></div>';
?>