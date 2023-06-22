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

global $koneksi_db, $tahun_id, $programstudi;
$idm = $_REQUEST['idm'];
if (empty($idm) || !isset($idm)) {
    echo "<meta http-equiv='refresh' content='3; url=index.php?m=error'>";
}
$w = $koneksi_db->sql_fetchassoc(
    $koneksi_db->sql_query(
        "SELECT * FROM m_mahasiswa where idm='" .
            $idm .
            "' and status_aktif='L' limit 1 "
    )
);

$wj = $koneksi_db->sql_fetchassoc(
    $koneksi_db->sql_query(
        "SELECT * FROM t_mahasiswa_wisuda where idm='" . $idm . "' limit 1 "
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

$tanggal = converttgl(date('Y-m-d'));
$jurusan = strtolower(viewkonsentrasi('' . $w['kode_konsentrasi'] . ''));
$programx = strtolower(viewprodi('' . $w['kode_prodi'] . ''));

$namamhs = strtolower($w['nama_mahasiswa']);
$tempat = strtolower($w['tempat_lahir']);
$jenjang = $w['kode_jenjang'];
if ($jenjang = 'A') {
    $program = 'STRATA (S-3)';
} elseif ($jenjang = 'B') {
    $program = 'STRATA (S-2)';
} elseif ($jenjang = 'C') {
    $program = 'STRATA (S-1)';
} elseif ($jenjang = 'D') {
    $program = 'DIPLOMA VI';
} elseif ($jenjang = 'E') {
    $program = 'DIPLOMA III';
} elseif ($jenjang = 'F') {
    $program = 'DIPLOMA II';
} elseif ($jenjang = 'G') {
    $program = 'DIPLOMA I';
} elseif ($jenjang = 'H') {
    $program = 'SP I';
} elseif ($jenjang = 'I') {
    $program = 'SP II';
} elseif ($jenjang = 'J') {
    $program = 'PROFESI';
}
?>

<table width="800" border="0" cellspacing="1" cellpadding="1">
    <tr>
        <td align=left>

            <p class="MsoNormal" style="text-align: right; margin: 0cm 0cm 0pt;" align="right">
                <span style="line-height: 115%; font-size: 14pt;">
                    <span style="font-family: Calibri;">
                        Nomor : <?php echo $wj['no_ijazah']; ?>
                    </span>
                </span>
            </p>

            <p class="MsoNormal" style="text-align: center; margin: 0cm 0cm 0pt;" align="center">
                <span style="line-height: 115%; font-size: 18pt;">
                    <span style="font-family: Calibri;">
                        REPUBLIK INDONESIA
                    </span>
                </span>
            </p>

            <p class="MsoNormal" style="text-align: center; margin: 0cm 0cm 0pt;" align="center">
                <span style="line-height: 115%; font-size: 18pt;">
                    <span style="font-family: Calibri;">
                        <?php echo '' . strtoupper($badanhukum['nama_badan_hukum']) . ''; ?>
                    </span>
                </span>
            </p>

            <p class="MsoNormal" style="text-align: center; margin: 0cm 0cm 0pt;" align="center">
                <strong>
                    <span
                        style="line-height: 115%; font-family: &quot;Times New Roman&quot;,&quot;serif&quot;; font-size: 20pt; mso-ascii-theme-font: major-bidi; mso-hansi-theme-font: major-bidi; mso-bidi-theme-font: major-bidi;">
                        <?php echo '' . strtoupper($perguruantinggi['nama_pt']) . ''; ?>
                    </span>
                </strong>
            </p>
            <br />
            <p class="MsoNormal" style="text-align: center; margin: 0cm 0cm 0pt;" align="center">
                <strong>
                    <span style="line-height: 115%; font-size: 20pt;">
                        <span style="font-family: Calibri;">
                            IJAZAH
                        </span>
                    </span>
                </strong>
            </p>
            <br />
            <p class="MsoNormal" style="margin: 0cm 0cm 0pt;">
                <span
                    style="line-height: 115%; font-family: &quot;Berlin Sans FB&quot;,&quot;sans-serif&quot;; font-size: 13pt;">
                    Diberikan kepada :
                </span>
            </p>

            <table class="MsoTableGrid"
                style="margin: auto auto auto 19.6pt; border-collapse: collapse; mso-yfti-tbllook: 1184; mso-padding-alt: 0cm 5.4pt 0cm 5.4pt; mso-border-insideh: none; mso-border-insidev: none;"
                border="0" cellspacing="0" cellpadding="0">
                <tbody>
                    <tr style="mso-yfti-irow: 0; mso-yfti-firstrow: yes; ">
                        <td style="padding-bottom: 0cm; background-color: transparent; padding-left: 5.4pt; width: 163pt; padding-right: 5.4pt; padding-top: 0cm; border: #f0f0f0;"
                            width="217" valign="midle">
                            <p class="MsoNormal" style="line-height: normal; margin: 0cm 0cm 0pt;">
                                <span
                                    style="font-family: &quot;Berlin Sans FB&quot;,&quot;sans-serif&quot;; font-size: 13pt;">
                                    Nama
                                </span>
                            </p>
                        </td>
                        <td style="padding-bottom: 0cm; background-color: transparent; padding-left: 5.4pt; width: 14.2pt; padding-right: 5.4pt; padding-top: 0cm; border: #f0f0f0;"
                            width="19" valign="midle">
                            <p class="MsoNormal" style="line-height: normal; margin: 0cm 0cm 0pt;">
                                <span
                                    style="font-family: &quot;Berlin Sans FB&quot;,&quot;sans-serif&quot;; font-size: 13pt;">:</span>
                                <span
                                    style="font-family: &quot;Berlin Sans FB&quot;,&quot;sans-serif&quot;; font-size: 20pt;"></span>
                            </p>
                        </td>
                        <td style="padding-bottom: 0cm; background-color: transparent; padding-left: 5.4pt; width: 262.2pt; padding-right: 5.4pt; padding-top: 0cm; border: #f0f0f0;"
                            width="350" valign="top">
                            <p class="MsoNormal" style="line-height: normal; margin: 0cm 0cm 0pt;">
                                <strong>
                                    <span style="font-family: &quot;Monotype Corsiva&quot;; font-size: 20pt;">
                                        <?php echo '' . ucwords($namamhs) . ''; ?>
                                    </span>
                                </strong>
                            </p>
                        </td>
                    </tr>
                    <tr style="mso-yfti-irow: 1;">
                        <td style="padding-bottom: 0cm; background-color: transparent; padding-left: 5.4pt; width: 163pt; padding-right: 5.4pt; padding-top: 0cm; border: #f0f0f0;"
                            width="217" valign="top">
                            <p class="MsoNormal" style="line-height: normal; margin: 0cm 0cm 0pt;">
                                <span
                                    style="font-family: &quot;Berlin Sans FB&quot;,&quot;sans-serif&quot;; font-size: 13pt;">
                                    Nomor Induk Mahasiswa
                                </span>
                            </p>
                        </td>
                        <td style="padding-bottom: 0cm; background-color: transparent; padding-left: 5.4pt; width: 14.2pt; padding-right: 5.4pt; padding-top: 0cm; border: #f0f0f0;"
                            width="19" valign="top">
                            <p class="MsoNormal" style="line-height: normal; margin: 0cm 0cm 0pt;">
                                <span
                                    style="font-family: &quot;Berlin Sans FB&quot;,&quot;sans-serif&quot;; font-size: 13pt;">:</span>
                                <span
                                    style="font-family: &quot;Berlin Sans FB&quot;,&quot;sans-serif&quot;; font-size: 20pt;"></span>
                            </p>
                        </td>
                        <td style="padding-bottom: 0cm; background-color: transparent; padding-left: 5.4pt; width: 262.2pt; padding-right: 5.4pt; padding-top: 0cm; border: #f0f0f0;"
                            width="350" valign="top">
                            <p class="MsoNormal" style="line-height: normal; margin: 0cm 0cm 0pt;">
                                <strong>
                                    <span style="font-family: &quot;Monotype Corsiva&quot;; font-size: 14pt;">
                                        <?php echo '' . $w['NIM'] . ''; ?>
                                    </span>
                                </strong>
                                <span
                                    style="font-family: &quot;Berlin Sans FB&quot;,&quot;sans-serif&quot;; font-size: 13pt;"></span>
                            </p>
                        </td>
                    </tr>
                    <tr style="mso-yfti-irow: 2; mso-yfti-lastrow: yes;">
                        <td style="padding-bottom: 0cm; background-color: transparent; padding-left: 5.4pt; width: 163pt; padding-right: 5.4pt; padding-top: 0cm; border: #f0f0f0;"
                            width="217" valign="top">
                            <p class="MsoNormal" style="line-height: normal; margin: 0cm 0cm 0pt;">
                                <span
                                    style="font-family: &quot;Berlin Sans FB&quot;,&quot;sans-serif&quot;; font-size: 13pt;">
                                    Tempat dan Tanggal Lahir
                                </span>
                            </p>
                        </td>
                        <td style="padding-bottom: 0cm; background-color: transparent; padding-left: 5.4pt; width: 14.2pt; padding-right: 5.4pt; padding-top: 0cm; border: #f0f0f0;"
                            width="19" valign="top">
                            <p class="MsoNormal" style="line-height: normal; margin: 0cm 0cm 0pt;">
                                <span
                                    style="font-family: &quot;Berlin Sans FB&quot;,&quot;sans-serif&quot;; font-size: 13pt;">:</span>
                                <span
                                    style="font-family: &quot;Berlin Sans FB&quot;,&quot;sans-serif&quot;; font-size: 20pt;"></span>
                            </p>
                        </td>
                        <td style="padding-bottom: 0cm; background-color: transparent; padding-left: 5.4pt; width: 262.2pt; padding-right: 5.4pt; padding-top: 0cm; border: #f0f0f0;"
                            width="350" valign="top">
                            <p class="MsoNormal" style="line-height: normal; margin: 0cm 0cm 0pt;">
                                <strong>
                                    <span style="font-family: &quot;Monotype Corsiva&quot;; font-size: 14pt;">
                                        <?php echo ' ' . ucwords($tempat) . ''; ?>, <?php echo '' .
    converttgl($w['tanggal_lahir']) .
    ''; ?>
                                    </span>
                                </strong>
                                <span style="font-size: small;">
                                    <span style="font-family: &quot;Berlin Sans FB&quot;,&quot;sans-serif&quot;;">
                                    </span>
                                    <span
                                        style="font-family: &quot;Berlin Sans FB&quot;,&quot;sans-serif&quot;; font-size: 13pt;"></span>
                                </span>
                            </p>
                        </td>
                    </tr>
                </tbody>
            </table>

            <p class="MsoNormal" style="text-align: center; margin: 0cm 0cm 0pt;" align="center">
                <strong>
                    <span
                        style="line-height: 115%; font-family: &quot;Arial Narrow&quot;,&quot;sans-serif&quot;; font-size: 14pt;">&nbsp;</span>
                </strong>
            </p>

            <p class="MsoNormal" style="text-align: center; margin: 0cm 0cm 0pt;" align="center">
                <strong>
                    <span
                        style="line-height: 115%; font-family: &quot;Arial Narrow&quot;,&quot;sans-serif&quot;; font-size: 14pt;">
                        LULUS UJIAN PROGRAM <?php echo $program; ?>
                    </span>
                </strong>
            </p>

            <p class="MsoNormal" style="text-align: center; margin: 0cm 0cm 0pt;" align="center">
                <span
                    style="line-height: 115%; font-family: &quot;Arial Narrow&quot;,&quot;sans-serif&quot;; font-size: 13pt;">
                    Pada tanggal : <?php echo converttgl($w['tanggal_lulus']); ?>
                </span>
            </p>

            <p class="MsoNormal" style="text-align: center; margin: 0cm 0cm 0pt;" align="center">
                <span
                    style="line-height: 115%; font-family: &quot;Arial Narrow&quot;,&quot;sans-serif&quot;; font-size: 13pt;">&nbsp;</span>
            </p>

            <p class="MsoNormal" style="margin: 0cm 0cm 0pt;">
                <span
                    style="line-height: 115%; font-family: &quot;Berlin Sans FB&quot;,&quot;sans-serif&quot;; font-size: 13pt;">
                    Kepada yang bersangkutan diberikan sebutan <?php echo $programstudi[
      'gelar_panjang'
  ]; ?> (<?php echo $programstudi['gelar']; ?>) pada :
                </span>
            </p>

            <table class="MsoTableGrid"
                style="margin: auto auto auto 19.6pt; border-collapse: collapse; mso-yfti-tbllook: 1184; mso-padding-alt: 0cm 5.4pt 0cm 5.4pt; mso-border-insideh: none; mso-border-insidev: none;"
                border="0" cellspacing="0" cellpadding="0">
                <tbody>
                    <tr style="mso-yfti-irow: 0; mso-yfti-firstrow: yes;">
                        <td style="padding-bottom: 0cm; background-color: transparent; padding-left: 5.4pt; width: 163pt; padding-right: 5.4pt; padding-top: 0cm; border: #f0f0f0;"
                            width="217" valign="top">
                            <p class="MsoNormal" style="line-height: normal; margin: 0cm 0cm 0pt;">
                                <span
                                    style="font-family: &quot;Berlin Sans FB&quot;,&quot;sans-serif&quot;; font-size: 13pt;">
                                    Jurusan
                                </span>
                            </p>
                        </td>
                        <td style="padding-bottom: 0cm; background-color: transparent; padding-left: 5.4pt; width: 14.2pt; padding-right: 5.4pt; padding-top: 0cm; border: #f0f0f0;"
                            width="19" valign="top">
                            <p class="MsoNormal" style="line-height: normal; margin: 0cm 0cm 0pt;"><span
                                    style="font-family: &quot;Berlin Sans FB&quot;,&quot;sans-serif&quot;; font-size: 13pt;">:</span>
                            </p>
                        </td>
                        <td style="padding-bottom: 0cm; background-color: transparent; padding-left: 5.4pt; width: 262.2pt; padding-right: 5.4pt; padding-top: 0cm; border: #f0f0f0;"
                            width="350" valign="top">
                            <p class="MsoNormal" style="line-height: normal; margin: 0cm 0cm 0pt;">
                                <strong>
                                    <span style="font-family: &quot;Monotype Corsiva&quot;; font-size: 16pt;">
                                        <?php echo '' . ucwords($jurusan) . ''; ?>
                                    </span>
                                </strong>
                            </p>
                        </td>
                    </tr>
                    <tr style="mso-yfti-irow: 1;">
                        <td style="padding-bottom: 0cm; background-color: transparent; padding-left: 5.4pt; width: 163pt; padding-right: 5.4pt; padding-top: 0cm; border: #f0f0f0;"
                            width="217" valign="top">
                            <p class="MsoNormal" style="line-height: normal; margin: 0cm 0cm 0pt;"><span
                                    style="font-family: &quot;Berlin Sans FB&quot;,&quot;sans-serif&quot;; font-size: 13pt;">Program
                                    Studi</span></p>
                        </td>
                        <td style="padding-bottom: 0cm; background-color: transparent; padding-left: 5.4pt; width: 14.2pt; padding-right: 5.4pt; padding-top: 0cm; border: #f0f0f0;"
                            width="19" valign="top">
                            <p class="MsoNormal" style="line-height: normal; margin: 0cm 0cm 0pt;"><span
                                    style="font-family: &quot;Berlin Sans FB&quot;,&quot;sans-serif&quot;; font-size: 13pt;">:</span>
                            </p>
                        </td>
                        <td style="padding-bottom: 0cm; background-color: transparent; padding-left: 5.4pt; width: 262.2pt; padding-right: 5.4pt; padding-top: 0cm; border: #f0f0f0;"
                            width="350" valign="top">
                            <p class="MsoNormal" style="line-height: normal; margin: 0cm 0cm 0pt;">
                                <strong><span style="font-family: &quot;Monotype Corsiva&quot;; font-size: 14pt;">
                                        <?php echo ' ' . ucwords($programx) . ''; ?>
                                    </span>
                                </strong><span
                                    style="font-family: &quot;Berlin Sans FB&quot;,&quot;sans-serif&quot;; font-size: 13pt;"></span>
                            </p>
                        </td>
                    </tr>
                </tbody>
            </table>
            <p class="MsoNormal" style="line-height: normal; margin: 0cm 0cm 0pt;"><span
                    style="font-family: &quot;Berlin Sans FB&quot;,&quot;sans-serif&quot;; font-size: 12pt;">
                    (Terakreditasi berdasarkan Surat Keputusan Badan Akreditasi Nasional Perguruan Tinggi (BAN-PT) Nomor
                    : <?php echo $programstudi[
    'no_sk_ban'
]; ?> )
                </span></p>

            <p class="MsoNormal" style="margin: 0cm 0cm 0pt;"><span
                    style="line-height: 115%; font-family: &quot;Berlin Sans FB&quot;,&quot;sans-serif&quot;; font-size: 13pt;">&nbsp;</span>
            </p>
            <p class="MsoNormal" style="margin: 0cm 0cm 0pt;"><span
                    style="line-height: 115%; font-family: &quot;Berlin Sans FB&quot;,&quot;sans-serif&quot;; font-size: 13pt;">
                    Dan kepadanya diberikan hak dan kewenangan sesuai dengan ijazah yang dimilikinya.
                </span></p>
            <p class="MsoNormal" style="margin: 0cm 0cm 0pt;"><span
                    style="line-height: 115%; font-family: &quot;Berlin Sans FB&quot;,&quot;sans-serif&quot;; font-size: 13pt;">&nbsp;</span>
            </p>

            <table border="0" cellspacing="0" cellpadding="0" width="100%">
                <tbody>
                    <tr>
                        <td valign="top" width="40%">
                            <p class="MsoNormal" style="text-align: center; line-height: normal; margin: 0cm 0cm 0pt;"
                                align="center"><span
                                    style="font-family: &quot;Berlin Sans FB&quot;,&quot;sans-serif&quot;; font-size: 13pt;">&nbsp;</span>
                            </p>
                        </td>
                        <td valign="top">
                            <p class="MsoNormal" style="text-align: center; line-height: normal; margin: 0cm 0cm 0pt;"
                                align="center"><span
                                    style="font-family: &quot;Berlin Sans FB&quot;,&quot;sans-serif&quot;; font-size: 13pt;">&nbsp;</span>
                            </p>
                        </td>
                        <td valign="top" width="40%">
                            <p class="MsoNormal" style="text-align: center; line-height: normal; margin: 0cm 0cm 0pt;"
                                align="center"><span
                                    style="font-family: &quot;Berlin Sans FB&quot;,&quot;sans-serif&quot;; font-size: 13pt;">
                                    <?php echo '' .
    viewkota($perguruantinggi['kode_kota']) .
    ', ' .
    $tanggal .
    ''; ?>
                                </span></p>
                        </td>
                    </tr>
                    <tr style="mso-yfti-irow: 1;">
                        <td valign="top">
                            <p class="MsoNormal" style="text-align: center; line-height: normal; margin: 0cm 0cm 0pt;"
                                align="center"><span
                                    style="font-family: &quot;Berlin Sans FB&quot;,&quot;sans-serif&quot;; font-size: 13pt;">
                                    Pembantu Direktur
                                </span></p>
                            <p class="MsoNormal" style="text-align: center; line-height: normal; margin: 0cm 0cm 0pt;"
                                align="center"><span
                                    style="font-family: &quot;Berlin Sans FB&quot;,&quot;sans-serif&quot;; font-size: 13pt;">
                                    Bidang Akademik,
                                </span></p>
                        </td>
                        <td valign="top" rowspan="4" align="center">
                            <table class="rapor" height="150" width="120">
                                <tr>
                                    <th>Foto</th>
                                </tr>
                            </table>
                        </td>
                        <td valign="top">
                            <p class="MsoNormal" style="text-align: center; line-height: normal; margin: 0cm 0cm 0pt;"
                                align="center"><span
                                    style="font-family: &quot;Berlin Sans FB&quot;,&quot;sans-serif&quot;; font-size: 13pt;">
                                    Direktur
                                </span></p>
                        </td>
                    </tr>
                    <tr style="height: 51.9pt; mso-yfti-irow: 2;">
                        <td valign="top">&nbsp;
                        </td>
                        <td valign="top">&nbsp;
                        </td>
                    </tr>
                    <tr style="height: 12.95pt; mso-yfti-irow: 3; mso-yfti-lastrow: yes;">
                        <td valign="top">
                            <p class="MsoNormal" style="text-align: center; line-height: normal; margin: 0cm 0cm 0pt;"
                                align="center">
                                <span style="text-decoration: underline;"><span
                                        style="font-family: &quot;Berlin Sans FB&quot;,&quot;sans-serif&quot;; font-size: 11pt;">
                                        <?php echo '' . viewdosen($programstudi['ketua_prodi']) . ''; ?>
                                    </span></span>
                            </p>
                        </td>

                        <td valign="top">
                            <p class="MsoNormal" style="text-align: center; line-height: normal; margin: 0cm 0cm 0pt;"
                                align="center">
                                <span style="text-decoration: underline;"><span
                                        style="font-family: &quot;Berlin Sans FB&quot;,&quot;sans-serif&quot;; font-size: 11pt;">
                                        <?php echo '' . ucwords(viewlektorkepala()) . ''; ?>
                                    </span></span>
                            </p>
                        </td>
                    </tr>
                </tbody>
            </table>