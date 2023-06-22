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
    echo "<meta http-equiv='refresh' content='3; url=index.php?m=khs'>";
}
$w = $koneksi_db->sql_fetchassoc(
    $koneksi_db->sql_query(
        "SELECT * FROM m_mahasiswa where idm='" . $idm . "' limit 1 "
    )
);
//$qq = $koneksi_db->sql_fetchrow($koneksi_db->sql_query( "SELECT * FROM t_mahasiswa_krs where idm='".$idm."'" ));
$foto =
    $perguruantinggi['logo'] == ''
        ? 'images/logo-depan.png'
        : 'images/' . $perguruantinggi['logo'] . '';

$tanggal = converttgl(date('Y-m-d'));

echo '
<table width="700" border="1" align=center cellspacing="1" cellpadding="1" class=no-style>
  <tr>
    <td width="16%" rowspan="4"><img src="' .
    $foto .
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
    ' 
	</td>
	
  </tr>
  
  <tr>
    <td align=center><h2>KARTU HASIL STUDI</h2></td>
  </tr>
</table>';

echo '
</br>
<table width="100%" border="1" cellspacing="1" cellpadding="1" class=no-style>
  <tr>
    <td width="150">NAMA</td>
    <td width="5">:</td>
    <td width="230"><strong>' .
    $w['nama_mahasiswa'] .
    '</strong></td>
    <td width="30">&nbsp;</td>
 <td>TAHUN AJARAN </td>
    <td>:</td>
    <td><strong>' .
    strtoupper(NamaTahun($tahun_id, $prodi)) .
    '</strong></td>
  </tr>
  <tr>
    <td>NIM</td>
    <td>:</td>
    <td><strong>' .
    $w['NIM'] .
    '</strong></td>
    <td>&nbsp;</td>
    <td>PROGRAM STUDI </td>
    <td>:</td>
    <td><strong>' .
    viewprodi('' . $w['kode_prodi'] . '') .
    '</strong></td>
  </tr>
  <tr>
    <td>ANGKATAN</td>
    <td>:</td>
    <td><strong>' .
    $w['tahun_masuk'] .
    '</strong></td>
    <td>&nbsp;</td>
    
  </tr>

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
$q = "select  k.*, m.* from t_mahasiswa_krs k 
					left outer join m_mata_kuliah m on k.id=m.id
					where k.kode_prodi='$w[kode_prodi]' and k.idm='$w[idm]' and k.tahun_id='$tahun_id'";

$pilih = $koneksi_db->sql_query($q);
$jumlah = $koneksi_db->sql_numrows($pilih);

$jumlah_mk = jumlah_mk($w['kode_prodi'], $tahun_id, $w['idm']);
$jumlah_sks = jumlah_sks($w['kode_prodi'], $tahun_id, $w['idm']);
$jumlah_ip = jumlah_ip($w['kode_prodi'], $tahun_id, $w['idm']);
if (!empty($jumlah_ip) && !empty($jumlah_sks)) {
    $kumulatif = round($jumlah_ip / $jumlah_sks, 2);
}

//beban studi yad

if (substr($tahun_id, 4) == 1) {
    $tahun_id_sebelumnya = substr($tahun_id, 0, 4) - 1 . '2';
} else {
    $tahun_id_sebelumnya = substr($tahun_id, 0, 4) . '1';
}

if ($jumlah_sks != null) {
    $wsks = $koneksi_db->sql_fetchassoc(
        $koneksi_db->sql_query(
            "SELECT * FROM m_krs
								where kode_prodi='" .
                $w['kode_prodi'] .
                "' and `ipk_min` <= '$kumulatif' and  `ipk_max` >= '$kumulatif' limit 1"
        )
    );

    $boleh = $wsks['jml_sks'];
} else {
    $jumlah_mk2 = jumlah_mk($w['kode_prodi'], $tahun_id_sebelumnya, $w['idm']);
    $jumlah_sks2 = jumlah_sks(
        $w['kode_prodi'],
        $tahun_id_sebelumnya,
        $w['idm']
    );
    $jumlah_ip2 = jumlah_ip($w['kode_prodi'], $tahun_id_sebelumnya, $w['idm']);
    if (!empty($jumlah_ip2) && !empty($jumlah_sks2)) {
        $kumulatif2 = round($jumlah_ip2 / $jumlah_sks2, 2);
    }

    $wsks2 = $koneksi_db->sql_fetchassoc(
        $koneksi_db->sql_query(
            "SELECT * FROM m_krs
								where kode_prodi='" .
                $w['kode_prodi'] .
                "' and `ipk_min` <= '$kumulatif2' and  `ipk_max` >= '$kumulatif2' limit 1"
        )
    );

    $boleh = $wsks2['jml_sks'];
}

$jumlah_sks_semua = jumlah_sks($w['kode_prodi'], $_tahun_id, $w['idm']);
$jumlah_ip_semua = jumlah_ip($w['kode_prodi'], $_tahun_id, $w['idm']);
if (!empty($jumlah_ip_semua) && !empty($jumlah_sks_semua)) {
    $kumulatif_semua = round($jumlah_ip_semua / $jumlah_sks_semua, 2);
}

$pr = $koneksi_db->sql_fetchassoc(
    $koneksi_db->sql_query("SELECT * FROM m_predikat
				where `bobot_min` <= '$kumulatif_semua' and  `bobot_max` >= '$kumulatif_semua' limit 1")
);

$no = 0;
if ($jumlah > 0) {
    while ($k = $koneksi_db->sql_fetchassoc($pilih)) {
        $no++;
        $validasi = $koneksi_db->sql_fetchassoc(
            $koneksi_db->sql_query(
                "SELECT validasi FROM t_mahasiswa_krs where kode_prodi='" .
                    $w['kode_prodi'] .
                    "'
				and tahun_id='" .
                    $tahun_id .
                    "' and id='" .
                    $k['id'] .
                    "' and idm='" .
                    $k['idm'] .
                    "' "
            )
        );

        if ($validasi['validasi'] == 1) {
            echo '<tr >
					<td  align=center>' .
                $no .
                '</td> 
					<td valign="top" align="center">' .
                $k['kode_mk'] .
                '</td>
					<td valign="top ">' .
                $k['nama_mk'] .
                '</td>
					<td valign="top align=center">' .
                $k['sks'] .
                '</td>	
					<td valign="top align=center">' .
                $k['nilai'] .
                '</td>				
					<td valign="top align=center">' .
                $k['bobot'] .
                '</td>
					<td valign="top align=center">' .
                $k['ip'] .
                '</td>
				</tr>';
        } else {
            echo '<tr >
					<td  align=center>' .
                $no .
                '</td> 
					<td valign="top" align="center">' .
                $k['kode_mk'] .
                '</td>
					<td valign="top ">' .
                $k['nama_mk'] .
                '</td>
					<td valign="top align=center">' .
                $k['sks'] .
                '</td>	
					<td valign="top align=center">--</td>				
					<td valign="top align=center">--</td>
					<td valign="top align=center">--</td>
				</tr>';
        }
    }
} else {
    echo '<tr><td colspan=6> Belum ambil KRS</td></tr>';
}
/// perulangan

echo '<tr><td colspan=7></td></tr>';
echo '
  <tr>
    <th  colspan=3 width="470">JUMLAH</th>
    <td width="50">' .
    $jumlah_sks .
    '</td>
    <td width="50">&nbsp;</td>
    <td width="50">&nbsp;</td>
    <td width="80">' .
    $jumlah_ip .
    '</td>
  </tr>
  ';
echo '<tr><td colspan=7></td></tr>';

if ($validasi['validasi'] == 1) {
    echo '
  <tr>
    <th colspan=3 >INDEKS PRESTASI SEMESTER ' .
        strtoupper(viewsmtr('' . $qq['semester']) . '') .
        '</th>
    <td colspan=4>' .
        $kumulatif .
        '</td>
  </tr>
  <tr>
    <th colspan=3>INDEKS PRESTASI KUMULATIF </th>
    <td colspan=4>' .
        $kumulatif_semua .
        '</td>
  </tr>
  <tr>
    <th colspan=3>Beban SKS Semester Yang Akan Datang</th>
    <td colspan=4>' .
        $boleh .
        '</td>
  </tr>
</table>';
} else {
    echo '
  <tr>
    <th colspan=3 >INDEKS PRESTASI SEMESTER ' .
        strtoupper(viewsmtr('' . $qq['semester']) . '') .
        '</th>
    <td colspan=4>--</td>
  </tr>
  <tr>
    <th colspan=3>INDEKS PRESTASI KUMULATIF </th>
    <td colspan=4>--</td>
  </tr>
  <tr>
    <th colspan=3>Beban SKS Semester Yang Akan Datang</th>
    <td colspan=4>--</td>
  </tr>
</table>

<br/>
<font color="RED"> keterangan : -- = Belum ada Nilai atau belum di validasi </font> ';
}

echo '</td></tr></table>';
?>
