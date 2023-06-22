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
        if (!empty($whr)) {
            $strwhr = 'where ' . implode(' and ', $whr);
        }

        require 'system/pagination_class.php';
        $sql =
            "select * from t_pmb_mahasiswa,m_program_studi where 
			t_pmb_mahasiswa.kode_prodi=m_program_studi.kode_prodi AND  terima='N' AND kode_pmb='" .
            $_SESSION['pmb_id'] .
            "'";
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
	 <form action="" method="post"  class="cmxform" id="form_input" style="width:100%">
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
		   <th width="5%" align="center">No</th>
		   <th align="center">ID PMB</th>
		   <th align="center">Nama Mahasiswa</th>
		   <th align="center">Jurusan/Prodi</th>
		   <th align="center">Status</th>
		   <th align="center" width=20%>Nilai</th>
		   <th align="center" width="8%">Lulus</th>
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
                    $wr['nama_prodi'] .
                    '</td>
					<td  align=left>' .
                    viewAplikasi('06', '' . $wr['status_masuk'] . '') .
                    '</td>
					<td  align=center>
						<input name="nilai_usm[' .
                    $id .
                    ']"  type="text" class="number kecil" id="" value="' .
                    $wr['nilai_usm'] .
                    '" />
					</td>
					<td  align=center>' .
                    $wr['lulus_usm'] .
                    '</td>
				</tr>';
            }
            echo '<tr > 
				<th  colspan="4" align=right></th>
				<th>
				<input type="submit" class=tombols ui-corner-all value="Update"/>
				</th><th></th>
				</tr>';
            echo '</tbody>
			</table></form>';

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
    global $koneksi_db;
    $pmb_id = $_SESSION['pmb_id'];
    if (is_array($_POST['nilai_usm'])) {
        $w = $koneksi_db->sql_fetchrow(
            $koneksi_db->sql_query(
                "SELECT * FROM m_pmb where pmb_id='" . $pmb_id . "' limit 1 "
            )
        );
        $nilai = $w[9];
        foreach ($_POST['nilai_usm'] as $key => $val) {
            $lulus = $val >= $nilai ? 'Y' : 'N';

            $update = $koneksi_db->sql_query(
                "UPDATE `t_pmb_mahasiswa` SET `nilai_usm` = '$val', lulus_usm='$lulus' WHERE `idmpmb` = '$key'"
            );
        }
    }
    Daftar();
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
        <font style="font-size:18px; color:#999999">Nilai Ujian Saringan Masuk</font><br />
        	<a href="index.php">Home</a> &raquo; <a href="?m=' .
    $_GET['m'] .
    '">Nilai USM </a>  &raquo; ' .
    $go .
    '  
    </div>';
echo '<div class="mainContentCell"><div class="content">';
$go();
echo '</div></div>';

?>