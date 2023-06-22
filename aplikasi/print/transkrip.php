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

echo '<table width="700"  border="0" cellspacing="1" cellpadding="1" ><tr><td align=left>';

global $koneksi_db, $tahun_id, $programstudi;
$idm = $_REQUEST['idm'];
if (empty($idm) || !isset($idm)) {
    echo "<meta http-equiv='refresh' content='3; url=index.php?m=transkrip'>";
}
$w = $koneksi_db->sql_fetchassoc(
    $koneksi_db->sql_query(
        "SELECT * FROM m_mahasiswa where idm='" .
            $idm .
            "' and aktif='1' limit 1 "
    )
);
$qq = $koneksi_db->sql_fetchassoc(
    $koneksi_db->sql_query(
        "SELECT * FROM t_mahasiswa_krs where idm='" . $idm . "'"
    )
);
$ta = $koneksi_db->sql_fetchassoc(
    $koneksi_db->sql_query(
        "SELECT * FROM t_mahasiswa_ta where idm='" . $idm . "' limit 1 "
    )
);

$logo =
    $perguruantinggi['logo'] == ''
        ? 'images/logo-depan.png'
        : 'images/' . $perguruantinggi['logo'] . '';
$foto =
    $w['foto'] == ''
        ? 'images/no_avatar.gif'
        : 'images/avatar/' . $w['foto'] . '';

$jumlah_mk = jumlah_mk($w['kode_prodi'], '', $w['idm']);
$jumlah_sks = jumlah_sks($w['kode_prodi'], '', $w['idm']);
$jumlah_ip = jumlah_ip($w['kode_prodi'], '', $w['idm']);
if (!empty($jumlah_ip) && !empty($jumlah_sks)) {
    $kumulatif = round($jumlah_ip / $jumlah_sks, 2);
}

$pr = $koneksi_db->sql_fetchassoc(
    $koneksi_db->sql_query("SELECT * FROM m_predikat
				where `bobot_min` <= '$kumulatif' and  `bobot_max` >= '$kumulatif' limit 1")
);

$tanggal = converttgl(date('Y-m-d'));
echo '
<table width="700" border="1" align=center cellspacing="1" cellpadding="1" class=no-style>
  <tr>
    <td width="16%" rowspan="4"><img src="' .
    $logo .
    '" width="80" height="80"></td>
    <td valign=top align=center><h1>' .
    $perguruantinggi['nama_pt'] .
    '</h1></td>
  </tr>
  <tr>
    <td width="84%" align=center>
	' .
    $perguruantinggi['alamat_1'] .
    ' 
	' .
    viewkota($perguruantinggi['kode_kota']) .
    ' 
	' .
    viewpropinsi($perguruantinggi['kode_propinsi']) .
    ' 
	</td>
  </tr>
  <tr>
    <td align=center>Telp. ' .
    $perguruantinggi['telepon'] .
    ' 
	Email ' .
    $perguruantinggi['email'] .
    '  
	Website ' .
    $perguruantinggi['website'] .
    ' </td>
  </tr>
  <tr>
    <td align=center><u><h2>TRANSKRIP NILAI</h2></u></td>
  </tr>
</table>';

echo '
		<table  border="0" cellspacing="1" class="datatable " cellpadding="1">
		<thead>
		  <tr>
			<td width="149">NIM</td>
			<td width="436"><b>' .
    $w['NIM'] .
    '</b></td>
			<td width="120" valign="top" rowspan="6"><img src="' .
    $foto .
    '" width="120px" height="140px"></td>
		  </tr>
		  <tr>
			<td>NAMA MAHASISWA</td>
			<td><b >' .
    $w['nama_mahasiswa'] .
    '</b></td>
		  </tr>
		  <tr>
			<td>Tempat, Tanggal Lahir </td>
			<td><strong>' .
    strtoupper($w['tempat_lahir']) .
    ',' .
    strtoupper(converttgl($w['tanggal_lahir'])) .
    '</strong></td>
		  </tr>
		  <tr>
			<td>PROGRAM STUDI </td>
			<td><b ><strong>' .
    strtoupper(viewAplikasi('04', '' . $w['kode_jenjang'] . '')) .
    ' ' .
    strtoupper(viewprodi('' . $w['kode_prodi'] . '')) .
    '</strong></b ></td>
		  </tr>
		   <tr>
			<td>KONSENTRASI</td>
			<td><b >' .
    viewkonsentrasi('' . $w['kode_konsentrasi'] . '') .
    '</b ></td>
		  </tr>
		  <tr>
			<td>Tanggal Kelulusan </td>
			<td><strong>' .
    strtoupper(converttgl($w['tanggal_lulus'])) .
    '</strong></td>
		  </tr>
		  </thead>
		</table>';

echo '<br/>
<table width="100%" border="1" cellspacing="1" cellpadding="1" class=rapor>
  <tr>
    <th width="10">NO</th>
    <th width="50">KODE MK </th>
    <th width="410" >MATA KULIAH </th>
    <th width="50">SKS</th>
    <th width="50">Nilai Huruf</th>
    <th width="50">Bobot</th>
    <th width="80">SKS * B </th>
  </tr>';

$qkrs = "select  k.*, m.*, min(k.nilai) from t_mahasiswa_krs k 
							left outer join m_mata_kuliah m on k.id=m.id
							where k.idm='$qq[idm]' and k.validasi='1' and k.verifi_pa='1' 
							GROUP BY k.idm, k.id 
							ORDER BY m.nama_mk";

$pkrs = $koneksi_db->sql_query($qkrs);
$jkrs = $koneksi_db->sql_numrows($pkrs);
$no = 0;
if ($jkrs > 0) {
    // perulanagn makul
    while ($mk = $koneksi_db->sql_fetchassoc($pkrs)) {
        $no++;
        echo '<tr >
							<td  align=center>' .
            $no .
            '</td> 
							<td  align="center">' .
            $mk['kode_mk'] .
            '</td>
							<td >' .
            $mk['nama_mk'] .
            '</td>';

        $qn = "select  sks, ip, max(bobot) as bobot, min(nilai) as nilai from t_mahasiswa_krs
							where kode_prodi='$w[kode_prodi]' and id='$mk[id]' and idm='$w[idm]' and validasi='1' and verifi_pa='1'";
        $pn = $koneksi_db->sql_query($qn);
        $jn = $koneksi_db->sql_numrows($pn);
        /// perulanagn nilai makul
        if ($jn > 0) {
            $wn = $koneksi_db->sql_fetchassoc($pn);
            $M = round($wn['bobot'] * $wn['sks'], 2);
            echo '<td  align="center">' .
                $wn['sks'] .
                '</td>
								<td  align="center">' .
                $wn['nilai'] .
                '</td>
									<td  align="center">' .
                $wn['bobot'] .
                '</td>
									<td  align="center">' .
                $M .
                '</td>
									</tr>';
        } else {
            echo '
								<td  align="center">-</td>
								<td  align="center">-</td>
									<td  align="center">-</td>
									<td  align="center">-</td>
									</tr>';
        }
    }
}

$total = $M + $total - $M;

//}

//$total = count($total);
//} else {

//echo '<tr><td colspan=7> Belum Lulus</td></tr>';
//}
/// perulangan

echo '<tr><td colspan=7></td></tr>';
echo '
  <tr>
    <td  colspan=3 width="470">JUMLAH</td>
    <td width="50" align=center>' .
    $jumlah_sks .
    '</td>
    <td width="50">&nbsp;</td>
    <td width="50">&nbsp;</td>
    <td width="80" align=center><b>' .
    $jumlah_ip .
    '</b></td>
  </tr> ';
echo '<tr><td colspan=7></td></tr>';
echo '
  <tr>
    <td colspan=3 >Jumlah Kredit Kumulatif</td>
    <td colspan=4><b>: ' .
    $jumlah_sks .
    ' SKS</b></td>
  </tr>
  <tr>
    <td colspan=3>Indeks Prestasi Kumulatif</td>
    <td colspan=4>: ' .
    $kumulatif .
    '</td>
  </tr>
  <tr>
    <td colspan=3>PREDIKAT</td>
    <td colspan=4>: ' .
    $pr['predikat'] .
    '</td>
  </tr>
  <tr>
    <td colspan=3 valign=top>Judul Tugas Akhir </td>
    <td colspan=5>: ' .
    strtoupper($ta['judul_ta']) .
    '</td>
  </tr>
</table>';

echo '<table>
<tr><td colspan=6><u><b>Keterangan :</b></u></td></tr>
<tr><td colspan=3 width=200><u><b>Predikat IPK :</b></u></td><td colspan=3><u><b>Prestasi :</b></u></td></tr>
<tr><td>3.50-4.00</td><td>=</td><td>Terpuji</td><td>AM</td><td>:</td><td>Angka Mutu</td></tr>
<tr><td>3.25-3.49</td><td>=</td><td>Sangat Memuaskan</td><td>HM</td><td>:</td><td>Huruf Mutu</td></tr>
<tr><td>3.00-3.24</td><td>=</td><td>Memuaskan</td><td>M</td><td>:</td><td>Mutu</td></tr>
<tr><td>2.75-2.99</td><td>=</td><td>Cukup</td><td></td><td></td><td></td></tr>
<tr><td>2.50-2.74</td><td>=</td><td>Sedang</td><td></td><td></td><td></td></tr>

</table>
';

echo '</td></tr></table>';
?>