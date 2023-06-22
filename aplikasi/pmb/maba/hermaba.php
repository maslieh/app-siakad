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
    global $koneksi_db;
    $prodi = $_SESSION['prodi'];
    $pmb_id = $_SESSION['pmb_id'];
    echo '<div class="row"><div class="col-md-4 pull-right">';
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
            $whr[] = " $_SESSION[kolom_mahasiswa] like '%$_SESSION[kunci_mahasiswa]%' ";
            $ord = " order by $_SESSION[kolom_mahasiswa]";
        }
        //$whr[] = "status_aktif='A'";
        if (!empty($_SESSION['prodi'])) {
            $whr[] = "kode_prodi='$_SESSION[prodi]'";
        }
        $whr[] = " kode_pmb='$_SESSION[pmb_id]'";
        $whr[] = " terima='N'";
        $whr[] = " lulus_usm='Y'";
        if (!empty($whr)) {
            $strwhr = 'where ' . implode(' and ', $whr);
        }

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
	 <form action="" method="post"  class="cmxform" id="form_input" name="form_input" style="width:100%">
			<input type="hidden" name="id" value="' .
                $id .
                '"/>
			<input type="hidden" name="m" value="' .
                $_GET['m'] .
                '"/>
			<input type="hidden" name="op" value="update"/>
	<table class="table table-striped table-bordered table-hover"  >
		<thead>
		 <tr>
		   <th width="5%" align="center"><a href="javascript:checkall(\'form_input\', \'terima[]\');">ALL</a></th>
		   <th align="center">ID PMB</th>
		   <th align="center">Nama Mahasiswa</th>
		   <th align="center">Status</th>
		   <th align="center" width=10%>Nilai</th>
		   <th align="center" width="10%">Lulus</th>
		 </tr>
		 </thead>
		 <tbody>';

            while ($wr = $koneksi_db->sql_fetchassoc($result)) {
                $n++;
                $id = $wr['idmpmb'];
                echo '<tr bgcolor="#f2f2f2">
					<td  align=center><input type="checkbox" name="terima[]" value="' .
                    $id .
                    '"></td> 
					<td  align=center>' .
                    $wr['idmpmb'] .
                    '</a></td>
					<td  align=left>' .
                    $wr['nama_mahasiswa'] .
                    '</td>
					<td  align=left>' .
                    viewAplikasi('06', '' . $wr['status_masuk'] . '') .
                    '</td>
					<td  align=center>' .
                    $wr['nilai_usm'] .
                    '</td>
					<td  align=center>' .
                    $wr['lulus_usm'] .
                    '</td>
				</tr>';
            }

            echo '</tbody>
			</table>
			<input type="submit" class=tombols ui-corner-all value="Terima"/>
			</form>';

            echo $obj->total;
            echo '<br/>';
            echo $obj->anchors;
        } else {
            echo '<div class="alert alert-danger">Belum ada Data</th>
			</div>';
        }
    }
}

function update()
{
    global $koneksi_db, $prodi, $tahun_id;
    if (is_array($_POST['terima'])) {
        foreach ($_POST['terima'] as $key => $val) {
            $update = $koneksi_db->sql_query(
                "UPDATE `t_pmb_mahasiswa` SET `terima` = 'Y'  WHERE `idmpmb` = '$val'"
            );
            $wi = $koneksi_db->sql_fetchassoc(
                $koneksi_db->sql_query(
                    "SELECT * FROM t_pmb_mahasiswa where `idmpmb` = '$val' limit 1 "
                )
            );
            $autokode = kdauto('m_mahasiswa', 'M');
            $tahun = substr($tahun_id, 0, 4);
            $tanggal = date('Y-m-d');
            $nim = BuatNIM($tahun_id, $wi['kode_fak']);
            $wwe = $koneksi_db->sql_fetchassoc(
                $koneksi_db->sql_query(
                    "SELECT * FROM m_tahun where tahun_id='" .
                        $tahun_id .
                        "' limit 1 "
                )
            );
            $tah = $wwe['tahun'];
            $ww = $koneksi_db->sql_fetchassoc(
                $koneksi_db->sql_query(
                    "SELECT max(NIM) as kodeTerbesar FROM m_mahasiswa where kode_prodi='$prodi' AND tahun_masuk='$tah'"
                )
            );
            $kodeBarang = $ww['kodeTerbesar'];
            $urutan = substr($kodeBarang, 7, 3);
            $huruf = $wi['kode_prodi'];
            $no1 = $ww['kodeTerbesar'] + 1;
            $hun = substr($wwe['tahun'], 2);
            $kodeBarang = $huruf . $hun . sprintf('%00s', $urutan);
            $hasil = $urutan + 1;
            $pk_n = strlen($hasil);
            if ($pk_n == 1) {
                $jumlah_mhs = $huruf . $hun . '000' . $hasil;
            } elseif ($pk_n == 2) {
                $jumlah_mhs = $huruf . $hun . '00' . $hasil;
            } else {
                $jumlah_mhs = $huruf . $hun . '0' . $hasil;
            }

            $s =
                "insert into m_mahasiswa SET 
					idm='" .
                $autokode .
                "',
					NIM='" .
                $jumlah_mhs .
                "',
					kode_pt='" .
                $wi['kode_pt'] .
                "',
					kode_fak='" .
                $wi['kode_fak'] .
                "',
					kode_konsentrasi='" .
                $wi['kode_konsentrasi'] .
                "',
					kode_prodi='" .
                $wi['kode_prodi'] .
                "',
					kode_jenjang='" .
                $wi['kode_jenjang'] .
                "',
					nama_mahasiswa='" .
                $wi['nama_mahasiswa'] .
                "',
					warga_negara='" .
                $wi['warga_negara'] .
                "',
					status_sipil='" .
                $wi['status_sipil'] .
                "',
					agama='" .
                $wi['agama'] .
                "',
					jenis_kelamin='" .
                $wi['jenis_kelamin'] .
                "',
					tempat_lahir='" .
                $wi['tempat_lahir'] .
                "',
					tanggal_lahir='" .
                $wi['tanggal_lahir'] .
                "',
					telepon='" .
                $wi['telepon'] .
                "',
					hp='" .
                $wi['hp'] .
                "',
					email='" .
                $wi['email'] .
                "',
					status_masuk='" .
                $wi['status_masuk'] .
                "',
					tahun_masuk='" .
                $tahun .
                "',
					semester_masuk='" .
                $tahun_id .
                "',
					tanggal_masuk='" .
                $tanggal .
                "',
					status_aktif='A',
					SekolahID='" .
                $wi['SekolahID'] .
                "',
					nis_asal='" .
                $wi['nis_asal'] .
                "',
					nilai_un='" .
                $wi['nilai_un'] .
                "',
					PerguruanTinggiID='" .
                $wi['kode_asal_pt'] .
                "',
					nim_asal='" .
                $wi['nim_asal'] .
                "',
					asal_jenjang='" .
                $wi['asal_jenjang'] .
                "',
					asal_prodi='" .
                $wi['asal_prodi'] .
                "',
					sks_diakui='" .
                $wi['sks_diakui'] .
                "'
					";
            //echo $s;
            $koneksi_db->sql_query($s);
            $su =
                "insert into user SET 
							userid='" .
                $autokode .
                "',
							username='" .
                $jumlah_mhs .
                "',
							password='" .
                md5($jumlah_mhs) .
                "',
							nama='" .
                $wi['nama_mahasiswa'] .
                "',
							email='" .
                $wi['email'] .
                "',
							level='MAHASISWA'
							";
            echo $su;
            $koneksi_db->sql_query($su);
        }
    }
    echo "<div  class='error'>Proses Menyimpan Data...</div>";
    echo "<meta http-equiv='refresh' content='3; url=index.php?m=" .
        $_GET['m'] .
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
        <font style="font-size:18px; color:#999999">Her Registrasi Mahasiswa Baru</font><br />
        	<a href="index.php">Home</a> &raquo; <a href="?m=' .
    $_GET['m'] .
    '">Her Registrasi Mahasiswa Baru</a>  &raquo; ' .
    $go .
    '  
    </div>';
echo '<div class="mainContentCell"><div class="content">';
$go();
echo '</div></div>';

?>
