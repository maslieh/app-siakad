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
    global $koneksi_db, $user, $tahun_id;
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
            "SELECT a.*,b.* FROM t_mahasiswa_krs a inner join m_mahasiswa b on a.idm=b.idm where a.idm=$user limit 1 "
        );
    }

    if ($koneksi_db->sql_numrows($ambilmhs) > 0) {

        $wm = $koneksi_db->sql_fetchassoc($ambilmhs);
        $status = $wm['status_aktif'];
        $tahunbayar = $wm['tahun_id'];
        $idm = $wm['idm'];
        $fotonya =
            $wm['foto'] == ''
                ? 'images/no_avatar.gif'
                : 'images/avatar/' . $wm['foto'] . '';

        echo '
		<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
		<thead>
		  <tr>
			<td width="149">NIM</td>
			<td width="436"><b>' .
            $wm['NIM'] .
            '</b></td>
			<td width="37" valign="top" rowspan="5"><img src="' .
            $fotonya .
            '" width="80" height="80"></td>
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
		</table></div>
	  ';

        // align="center" cellpadding="1" cellspacing="0" class="rapor full"
        ?>


<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">

        <thead>
            <tr>
                <th style="text-align: center; vertical-align: middle;" rowspan="2">No <?= $idm ?></th>
                <th style="text-align: center; vertical-align: middle;" colspan="2" rowspan="2">Tanggal Bayar</th>
                <th style="text-align: center; vertical-align: middle;" colspan="2" rowspan="2">Nama Pembayaran</th>
                <th style="text-align: center; vertical-align: middle;" colspan="2" rowspan="2">Keterangan</th>
                <th style="text-align: center; vertical-align: middle;" colspan="2">Tagihan</th>
                <th style="text-align: center; vertical-align: middle;" colspan="2">Saldo</th>
                <th style="text-align: center; vertical-align: middle;" rowspan="2">tgl server</th>
            </tr>
            <tr>
                <th style="text-align: center; vertical-align: middle;">Tagihan</th>
                <th style="text-align: center; vertical-align: middle;">Bayar</th>
                <th style="text-align: center; vertical-align: middle;">Sisa</th>
                <th style="text-align: center; vertical-align: middle;">Dibayar</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $qpp = $koneksi_db->sql_query(
                "SELECT a.id, sum(a.bayar) as bayar, a.biaya, b.nama_bayar ,a.jml_bayar, b.kali_bayar,a.tanggal,a.ket,a.sisa,a.date  from `t_mahasiswa_bayar` as a,  m_biaya as b 
            where a.idb=b.idb and a.tahun_id='$tahun_id' and idm = '$idm' group by a.idm,a.biaya, a.idb, a.tanggal order by a.id ASC"
            );
            $jumlah = $koneksi_db->sql_numrows($qpp);
            $n = 0;
            $saldo = 0;
            if ($jumlah > 0) {
                while ($wf = $koneksi_db->sql_fetchassoc($qpp)) {

                    // $saldo = $saldo + $wf['bayar'];
                    //  $saldo = $saldo - $wf['biaya'];
                    $bayar = $wf['bayar'];
                    $sisa = $wf['sisa'] - $wf['bayar'];
                    $n++;
                    ?>
            <tr>

                <td><?= $n++ ?></td>
                <td colspan="2"><?= $wf['tanggal'] ?> </td>
                <td colspan="2">
                    <?= $wf['nama_bayar'] ?>
                </td>
                <td colspan="2"><?= $wf['ket'] ?> </td>
                <td>Rp. <?= number_format($wf['biaya']) ?> </td>
                <td>Rp. <?= number_format($bayar) ?></td>
                <td>Rp. <?= number_format($wf['sisa']) ?></td>
                <td>Rp. <?= number_format($wf['jml_bayar']) ?></td>
                <td>
                    <em> <?= $wf['date'] ?></em>
                </td>
            </tr>

            <?php
                }
            } else {
                echo '<tr > <th  colspan="9" align=center>Belum ada Data</th></tr>';
            }

            echo '</tbody></table><div>';

    }
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
        <font style="font-size:18px; color:#999999">Riwayat Pembayaran Mahasiswa</font><br />
        	<a href="index.php">Home</a> &raquo; <a href="index.php?m=' .
    $_GET['m'] .
    '">Riwayat Pembayaran</a>  &raquo; ' .
    $go .
    '  
    </div>';
echo '<div class="mainContentCell"><div class="content">';
$go();
echo '</div></div>';

?>