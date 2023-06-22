<?php
global $char128asc, $char128charWidth;
$char128asc =
    ' !"#$%&\'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\]^_`abcdefghijklmnopqrstuvwxyz{|}~';
$char128wid = [
    '212222',
    '222122',
    '222221',
    '121223',
    '121322',
    '131222',
    '122213',
    '122312',
    '132212',
    '221213', // 0-9
    '221312',
    '231212',
    '112232',
    '122132',
    '122231',
    '113222',
    '123122',
    '123221',
    '223211',
    '221132', // 10-19
    '221231',
    '213212',
    '223112',
    '312131',
    '311222',
    '321122',
    '321221',
    '312212',
    '322112',
    '322211', // 20-29
    '212123',
    '212321',
    '232121',
    '111323',
    '131123',
    '131321',
    '112313',
    '132113',
    '132311',
    '211313', // 30-39
    '231113',
    '231311',
    '112133',
    '112331',
    '132131',
    '113123',
    '113321',
    '133121',
    '313121',
    '211331', // 40-49
    '231131',
    '213113',
    '213311',
    '213131',
    '311123',
    '311321',
    '331121',
    '312113',
    '312311',
    '332111', // 50-59
    '314111',
    '221411',
    '431111',
    '111224',
    '111422',
    '121124',
    '121421',
    '141122',
    '141221',
    '112214', // 60-69
    '112412',
    '122114',
    '122411',
    '142112',
    '142211',
    '241211',
    '221114',
    '413111',
    '241112',
    '134111', // 70-79
    '111242',
    '121142',
    '121241',
    '114212',
    '124112',
    '124211',
    '411212',
    '421112',
    '421211',
    '212141', // 80-89
    '214121',
    '412121',
    '111143',
    '111341',
    '131141',
    '114113',
    '114311',
    '411113',
    '411311',
    '113141', // 90-99
    '114131',
    '311141',
    '411131',
    '211412',
    '211214',
    '211232',
    '23311120',
]; // 100-106

////Define Function
function bar128($text)
{
    // Part 1, make list of widths
    global $char128asc, $char128wid;
    $w = $char128wid[($sum = 104)]; // START symbol
    $onChar = 1;
    for (
        $x = 0;
        $x < strlen($text);
        $x++ // GO THRU TEXT GET LETTERS
    ) {
        if (!(($pos = strpos($char128asc, $text[$x])) === false)) {
            // SKIP NOT FOUND CHARS
            $w .= $char128wid[$pos];
            $sum += $onChar++ * $pos;
        }
    }
    $w .= $char128wid[$sum % 103] . $char128wid[106]; //Check Code, then END
    //Part 2, Write rows
    $html = '<table cellpadding=0 cellspacing=0><tr>';
    for (
        $x = 0;
        $x < strlen($w);
        $x += 2 // code 128 widths: black border, then white space
    ) {
        $html .= "<td><div class=\"b128\" style=\"border-left-width:{$w[$x]};width:{$w[$x +
                1]}\"></div>";
    }
    return "$html<tr><td  colspan=" .
        strlen($w) .
        " align=center><font family=tahoma size=2><b>$text</table>";
}

function optahun($p)
{
    $mulai = date('Y') + 2;
    $akhir = 5;
    $opsi .= "<option value=\"\" >..::Pilih Tahun::..</option>";
    for ($i = $mulai; $i > $akhir; $i--) {
        $cl = $i == $p ? 'selected' : '';
        $opsi .= "<option value=\"$i\" $cl>$i</option>";
    }
    return $opsi;
}

function opwaktu($p)
{
    $akhir = 24;
    $opsi .= "<option value=\"\" >..::Jam::..</option>";
    for ($i = 1; $i <= $akhir; $i++) {
        $cl = $i == $p ? 'selected' : '';
        $opsi .= "<option value=\"$i\" $cl>$i</option>";
    }
    return $opsi;
}

function opjam($prodi, $p)
{
    global $koneksi_db;
    if (!empty($prodi)) {
        $whr = "where kode_prodi='$prodi'";
    }
    $opsi .= "<option value=\"\" >..::Jam::..</option>";
    $query = $koneksi_db->sql_query(" SELECT * FROM `m_jam` $whr ");
    while ($r = $koneksi_db->sql_fetchrow($query)) {
        $cl = $r[idj] == $p ? 'selected' : '';
        $opsi .= "<option value=\"$r[idj]\" $cl>$r[jam]>>$r[mulai]-$r[sampai]</option>";
    }
    return $opsi;
}

function opnilai($prodi, $p)
{
    global $koneksi_db;
    if (!empty($prodi)) {
        $whr = "where kode_prodi='$prodi'";
    }
    $opsi .= "<option value=\"\" >..</option>";
    $query = $koneksi_db->sql_query(" SELECT * FROM `m_nilai` $whr ");
    while ($r = $koneksi_db->sql_fetchrow($query)) {
        $cl = $r[nilai] == $p ? 'selected' : '';
        $opsi .= "<option value=\"$r[nilai]\" $cl>$r[nilai]</option>";
    }
    return $opsi;
}

function viewjam($prodi, $p)
{
    global $koneksi_db;
    if (!empty($prodi)) {
        $whr = "and kode_prodi='$prodi' ";
    }
    $query = $koneksi_db->sql_query(
        " SELECT * FROM `m_jam` where jam='$p' $whr "
    );
    $r = $koneksi_db->sql_fetchrow($query);
    return '[' . $r[jam] . '] ' . $r[mulai] . '-' . $r[sampai];
}

function optopik($p)
{
    global $koneksi_db;
    $opsi .= "<option value=\"\" >..::Pilih Topik::..</option>";
    $query = $koneksi_db->sql_query(' SELECT * FROM `t_topik`  ');
    while ($r = $koneksi_db->sql_fetchrow($query)) {
        $cl = $r[id] == $p ? 'selected' : '';
        $opsi .= "<option value=\"$r[0]\" $cl>$r[topik]</option>";
    }
    return $opsi;
}

function opkatalog($p)
{
    global $koneksi_db;
    $max = 0;
    $q = $koneksi_db->sql_query(' SELECT * FROM `r_katalog`  ORDER BY nama');
    while ($row = $koneksi_db->sql_fetchrow($q)) {
        $newmax = strlen($row[2]);
        if ($newmax > $max) {
            $max = $newmax;
        }
    }

    $opsi .= "<option value=\"\" >..::Pilih Katalog::..</option>";
    $query = $koneksi_db->sql_query(
        ' SELECT * FROM `r_katalog` ORDER BY nama '
    );
    while ($r = $koneksi_db->sql_fetchrow($query)) {
        $len = strlen($r[2]);
        $space = GetSpace($max, $len);
        $cl = $r[id] == $p ? 'selected' : '';
        $opsi .=
            "<option value=\"$r[0]\" $cl>" .
            strtoupper($r[2]) .
            "$space-$r[1]</option>";
    }
    return $opsi;
}

function oprak($p)
{
    global $koneksi_db;
    $opsi .= "<option value=\"\" >..::Pilih Rak::..</option>";
    $query = $koneksi_db->sql_query(' SELECT * FROM `r_rak`  ');
    while ($r = $koneksi_db->sql_fetchrow($query)) {
        $cl = $r[id] == $p ? 'selected' : '';
        $opsi .= "<option value=\"$r[0]\" $cl>$r[1]</option>";
    }
    return $opsi;
}

function opformat($p)
{
    global $koneksi_db;
    $opsi .= "<option value=\"\" >..::Pilih Format::..</option>";
    $query = $koneksi_db->sql_query(' SELECT * FROM `r_format`  ');
    while ($r = $koneksi_db->sql_fetchrow($query)) {
        $cl = $r[id] == $p ? 'selected' : '';
        $opsi .= "<option value=\"$r[0]\" $cl>$r[1]</option>";
    }
    return $opsi;
}

function oppenulis($p)
{
    global $koneksi_db;
    $opsi .= "<option value=\"\" >..::Pilih Penulis::..</option>";
    $query = $koneksi_db->sql_query(' SELECT * FROM `r_penulis`  ');
    while ($r = $koneksi_db->sql_fetchrow($query)) {
        $cl = $r[id] == $p ? 'selected' : '';
        $opsi .= "<option value=\"$r[0]\" $cl>$r[2]</option>";
    }
    return $opsi;
}

function oppenerbit($p)
{
    global $koneksi_db;
    $opsi .= "<option value=\"\" >..::Pilih Penerbit::..</option>";
    $query = $koneksi_db->sql_query(' SELECT * FROM `r_penerbit`  ');
    while ($r = $koneksi_db->sql_fetchrow($query)) {
        $cl = $r[id] == $p ? 'selected' : '';
        $opsi .= "<option value=\"$r[0]\" $cl>$r[2]</option>";
    }
    return $opsi;
}

function opsemester($p)
{
    $arr = ['Semester Ganjil->1', 'Semester Genap->2'];
    $opsi .= "<option value=\"\" >..::Pilih Semester::..</option>";
    for ($i = 0; $i < sizeof($arr); $i++) {
        $r = Explode('->', $arr[$i]);
        $cl = $r[1] == $p ? 'selected' : '';
        $opsi .= "<option value=\"$r[1]\" $cl>$r[0]</option>";
    }
    return $opsi;
}

function total_presensi_mahasiswa(
    $prodi,
    $tahun,
    $kelas,
    $kode_mk,
    $jenis_presensi,
    $idm
) {
    global $koneksi_db;
    if (!empty($prodi)) {
        $whr[] = "kode_prodi='$prodi'";
    }
    if (!empty($tahun)) {
        $whr[] = "tahun_id='$tahun'";
    }
    //if (!empty($semester)) $whr[] = "semester='$semester'";
    if (!empty($kelas)) {
        $whr[] = "kelas='$kelas'";
    }
    if (!empty($kode_mk)) {
        $whr[] = "id='$kode_mk'";
    }
    if (!empty($jenis_presensi)) {
        $whr[] = "jenis_presensi != '$jenis_presensi'";
    }
    if (!empty($idm)) {
        $whr[] = "idm='$idm'";
    }
    if (!empty($whr)) {
        $strwhr = 'where ' . implode(' and ', $whr);
    }
    $q = $koneksi_db->sql_fetchrow(
        $koneksi_db->sql_query("SELECT sum(presensi) as t FROM t_mahasiswa_presensi 
	$strwhr 
	")
    );
    return $q['t'];
}

function jml_presensi_mahasiswa(
    $prodi,
    $tahun,
    $kelas,
    $kode_mk,
    $jenis_presensi,
    $tanggal
) {
    global $koneksi_db;
    if (!empty($prodi)) {
        $whr[] = "kode_prodi='$prodi'";
    }
    if (!empty($tahun)) {
        $whr[] = "tahun_id='$tahun'";
    }
    //if (!empty($semester)) $whr[] = "semester='$semester'";
    if (!empty($kelas)) {
        $whr[] = "kelas='$kelas'";
    }
    if (!empty($kode_mk)) {
        $whr[] = "id='$kode_mk'";
    }
    if (!empty($jenis_presensi)) {
        $whr[] = "jenis_presensi = '$jenis_presensi'";
    }
    if (!empty($tanggal)) {
        $whr[] = "tanggal = '$tanggal'";
    }
    if (!empty($whr)) {
        $strwhr = 'where ' . implode(' and ', $whr);
    }
    $q = $koneksi_db->sql_fetchrow(
        $koneksi_db->sql_query("SELECT sum(presensi) as t FROM t_mahasiswa_presensi 
	$strwhr 
	")
    );
    return $q['t'];
}

function total_presensi_dosen(
    $prodi,
    $tahun,
    $kelas,
    $kode_mk,
    $jenis_presensi,
    $idd
) {
    global $koneksi_db;
    if (!empty($prodi)) {
        $whr[] = "kode_prodi='$prodi'";
    }
    if (!empty($tahun)) {
        $whr[] = "tahun_id='$tahun'";
    }
    //if (!empty($semester)) $whr[] = "semester='$semester'";
    if (!empty($kelas)) {
        $whr[] = "kelas='$kelas'";
    }
    if (!empty($kode_mk)) {
        $whr[] = "id='$kode_mk'";
    }
    if (!empty($jenis_presensi)) {
        $whr[] = "jenis_presensi='$jenis_presensi'";
    }
    if (!empty($idd)) {
        $whr[] = "idd='$idd'";
    }
    if (!empty($whr)) {
        $strwhr = 'where ' . implode(' and ', $whr);
    }
    $q = $koneksi_db->sql_fetchrow(
        $koneksi_db->sql_query("SELECT sum(presensi) as t FROM t_dosen_presensi 
	$strwhr 
	")
    );
    return $q['t'];
}

function oplevel($p)
{
    $ss = mysql_query('SHOW FIELDS FROM user');
    while ($as = mysql_fetch_array($ss)) {
        $arrs = $as['Type'];
        if (substr($arrs, 0, 4) == 'enum' && $as['Field'] == 'level') {
            break;
        }
    }

    $opsi .= "<option value=\"\" >..::Pilih Level::..</option>";

    $arrs = '' . substr($arrs, 4);
    $arr = eval('$arr5 = array' . $arrs . ';');
    foreach ($arr5 as $k => $v) {
        if ($v == $p) {
            $opsi .=
                '<option value="' .
                $v .
                '" selected="selected">' .
                $v .
                '</option>';
        } else {
            $opsi .= '<option value="' . $v . '">' . $v . '</option>';
        }
    }
    return $opsi;
}

function opYN($p)
{
    $arr = ['Ya->Y', 'Tidak->T'];
    $opsi .= "<option value=\"\" ></option>";
    for ($i = 0; $i < sizeof($arr); $i++) {
        $r = Explode('->', $arr[$i]);
        $cl = $r[1] == $p ? 'selected' : '';
        $opsi .= "<option value=\"$r[1]\" $cl>$r[0]</option>";
    }
    return $opsi;
}

function opMK($p)
{
    $arr = ['Mandiri->M', 'Kelompok->K'];
    $opsi .= "<option value=\"\" >..::Pilih::..</option>";
    for ($i = 0; $i < sizeof($arr); $i++) {
        $r = Explode('->', $arr[$i]);
        $cl = $r[1] == $p ? 'selected' : '';
        $opsi .= "<option value=\"$r[1]\" $cl>$r[0]</option>";
    }
    return $opsi;
}

function opJenisNilai($p)
{
    //$arr = array('UAS->Nilai Akhir');
    $arr = [
        'TUGAS->Nilai Tugas',
        'UTS->Nilai Ujian Tengah Semester',
        'UAS->Nilai Ujian Akhir Semester',
        'HADIR->Nilai Absensi',
    ];
    for ($i = 0; $i < sizeof($arr); $i++) {
        $r = Explode('->', $arr[$i]);
        $cl = $r[1] == $p ? 'selected' : '';
        $opsi .= "<option value=\"$r[0]\" $cl>$r[1]</option>";
    }
    return $opsi;
}

function opsmtr($p)
{
    global $koneksi_db, $prodi, $tahun_id;
    $smt = substr($tahun_id, 4, 4);
    if ($smt % 2 == 0) {
        $sm = 'Genap';
    } else {
        $sm = 'Ganjil';
    }

    $opsi .= "<option value=\"\" >..::Semester::..</option>";
    $prd = $koneksi_db->sql_fetchrow(
        $koneksi_db->sql_query(
            "SELECT * FROM m_program_studi where kode_prodi='$prodi'"
        )
    );
    $NamaSesi = empty($prodi) ? '' : $prd['nama_sesi'];

    for ($i = 0; $i < $prd[batas_sesi]; $i++) {
        $smtr = $i + 1;
        if ($i % 2 == 0) {
            $s = 'Genap';
        } else {
            $s = 'Ganjil';
        }
        $cl = $smtr == $p ? 'selected' : '';
        if ($s != $sm) {
            $opsi .=
                "<option value=\"$smtr\" $cl>$NamaSesi $smtr (" .
                Terbilang($smtr) .
                ')</option>';
        }
    }
    return $opsi;
}

function opsmtrmk($p)
{
    global $koneksi_db, $prodi;

    $opsi .= "<option value=\"\" >..::Semester::..</option>";
    $prd = $koneksi_db->sql_fetchrow(
        $koneksi_db->sql_query(
            "SELECT * FROM m_program_studi where kode_prodi='$prodi'"
        )
    );
    $NamaSesi = empty($prodi) ? '' : $prd['nama_sesi'];

    for ($i = 0; $i < $prd[batas_sesi]; $i++) {
        $smtr = $i + 1;
        $cl = $smtr == $p ? 'selected' : '';
        $opsi .=
            "<option value=\"$smtr\" $cl>$NamaSesi $smtr (" .
            Terbilang($smtr) .
            ')</option>';
    }
    return $opsi;
}

function oppaketkrs($prodi, $p)
{
    global $koneksi_db, $tahun_id;
    $smt = substr($tahun_id, 4, 4);
    if ($smt % 2 == 0) {
        $sm = 'Genap';
    } else {
        $sm = 'Ganjil';
    }

    $opsi .= "<option value=\"\" >..::Paket KRS::..</option>";
    $prd = $koneksi_db->sql_fetchrow(
        $koneksi_db->sql_query(
            "SELECT * FROM m_program_studi where kode_prodi='$prodi'"
        )
    );
    $NamaSesi = empty($prodi) ? '' : $prd['nama_sesi'];

    for ($i = 0; $i < $prd[batas_sesi]; $i++) {
        $smtr = $i + 1;
        if ($i % 2 == 0) {
            $s = 'Genap';
        } else {
            $s = 'Ganjil';
        }
        $cl = $smtr == $p ? 'selected' : '';
        if ($s != $sm) {
            $opsi .= "<option value=\"$smtr\" $cl>$NamaSesi $smtr ($prd[nama_prodi])</option>";
        }
    }
    return $opsi;
}

function optapel($p)
{
    global $koneksi_db;
    $opsi .= "<option value=\"\" >..::Pilih Tahun::..</option>";
    $query = $koneksi_db->sql_query('SELECT * FROM m_tahun ');
    while ($r = $koneksi_db->sql_fetchrow($query)) {
        $cl = $r['tahun_id'] == $p ? 'selected' : '';
        $opsi .= "<option value=\"$r[0]\" $cl>$r[1] (S$r[3])</option>";
    }
    return $opsi;
}

function tgl_ngajar_admin($prodi, $tahun_id, $user, $p)
{
    global $koneksi_db;
    if (!empty($prodi)) {
        $whr[] = "kode_prodi='$prodi'";
    }
    if (!empty($tahun)) {
        $whr[] = "tahun_id='$tahun_id'";
    }
    if (!empty($user)) {
        $whr[] = "idd='$idd'";
    }
    if (!empty($whr)) {
        $strwhr = 'where ' . implode(' and ', $whr);
    }
    //$pengampu =array();

    $opsi .= "<option value=\"\" >..::Pilih Tanggal::..</option>";
    $query = $koneksi_db->sql_query(
        " SELECT tanggal FROM `t_dosen_presensi` $strwhr"
    );
    while ($r = $koneksi_db->sql_fetchrow($query)) {
        $pengampu = explode('|', $r[idd]);
        //if ($dosen = in_array($pengampu) ) {
        //if (in_array($dosen, $pengampu)) {
        $cl = $r['tanggal'] == $p ? 'selected' : '';
        $opsi .= "<option value=\"$r[tanggal]\" $cl>$r[0]</option>";
    }

    return $opsi;
}

function tgl_ngajar($prodi, $tahun_id, $kelas, $id, $user, $p)
{
    global $koneksi_db;
    if (!empty($prodi)) {
        $whr[] = "kode_prodi='$prodi'";
    }
    if (!empty($tahun)) {
        $whr[] = "tahun_id='$tahun_id'";
    }
    if (!empty($tahun)) {
        $whr[] = "kelas='$kelas'";
    }
    if (!empty($tahun)) {
        $whr[] = "id='$id'";
    }
    if (!empty($user)) {
        $whr[] = "idd='$user'";
    }
    if (!empty($whr)) {
        $strwhr = 'where ' . implode(' and ', $whr);
    }
    //$pengampu =array();

    $opsi .= "<option value=\"\" >..::Pilih Tanggal::..</option>";
    $query = $koneksi_db->sql_query(
        " SELECT tanggal FROM `t_dosen_presensi` $strwhr"
    );
    while ($r = $koneksi_db->sql_fetchrow($query)) {
        $pengampu = explode('|', $r[idd]);
        //if ($dosen = in_array($pengampu) ) {
        //if (in_array($dosen, $pengampu)) {
        $cl = $r['tanggal'] == $p ? 'selected' : '';
        $opsi .= "<option value=\"$r[tanggal]\" $cl>$r[0]</option>";
    }

    return $opsi;
}

function opperiodeyudisium($p)
{
    global $koneksi_db;
    $opsi .= "<option value=\"\" >..::Pilih Periode::..</option>";
    $query = $koneksi_db->sql_query('SELECT * FROM m_wisuda ');
    while ($r = $koneksi_db->sql_fetchrow($query)) {
        $cl = $r['idw'] == $p ? 'selected' : '';
        $opsi .= "<option value=\"$r[0]\" $cl>$r[tahun_id] ($r[nama])</option>";
    }
    return $opsi;
}

function oppmb($p, $prodi)
{
    global $koneksi_db;
    $opsi .= "<option value=\"\" >..::PMB::..</option>";
    $query = $koneksi_db->sql_query(
        "SELECT * FROM m_pmb where kode_prodi='$prodi' and buka='Y'"
    );
    while ($r = $koneksi_db->sql_fetchrow($query)) {
        $cl = $r['pmb_id'] == $p ? 'selected' : '';
        $opsi .= "<option value=\"$r[0]\" $cl>$r[5] ($r[6])</option>";
    }
    return $opsi;
}

function opmatakuliah($prodi, $tahun_id, $p)
{
    global $koneksi_db;
    if (!empty($prodi)) {
        $whr[] = "kode_prodi='$prodi'";
    }
    if (!empty($tahun)) {
        $whr[] = "tahun_id='$tahun_id'";
    }
    //if (!empty($semester)) $whr[] = "semester='$semester'";
    if (!empty($whr)) {
        $strwhr = 'where ' . implode(' and ', $whr);
    }

    $opsi .= "<option value=\"\" >..::Mata Kuliah::..</option>";
    $query = $koneksi_db->sql_query(
        " SELECT * FROM `m_mata_kuliah` $strwhr order by nama_mk asc"
    );
    while ($r = $koneksi_db->sql_fetchrow($query)) {
        $cl = $r['id'] == $p ? 'selected' : '';
        $opsi .= "<option value=\"$r[id]\" $cl>$r[5] ($r[0]) - $r[6]</option>";
    }
    return $opsi;
}

function opmatakuliahkrs($prodi, $tahun_id, $kelas, $p)
{
    global $koneksi_db;
    if (!empty($prodi)) {
        $whr[] = "kode_prodi='$prodi'";
    }
    if (!empty($tahun)) {
        $whr[] = "tahun_id='$tahun_id'";
    }
    if (!empty($kelas)) {
        $whr[] = "kelas='$kelas'";
    }

    if (!empty($whr)) {
        $strwhr = 'where ' . implode(' and ', $whr);
    }

    $opsi .= "<option value=\"\" >..::Mata Kuliah::..</option>";
    $query = $koneksi_db->sql_query(
        " SELECT * FROM `view_jadwal` $strwhr GROUP BY kode_mk ORDER BY nama_mk"
    );
    while ($r = $koneksi_db->sql_fetchrow($query)) {
        $cl = $r['id'] == $p ? 'selected' : '';
        $opsi .= "<option value=\"$r[id]\" $cl>$r[15] - $r[16]</option>";
    }
    return $opsi;
}

function opmatakuliahdosen($prodi, $tahun_id, $user, $p)
{
    global $koneksi_db;
    if (!empty($prodi)) {
        $whr[] = "kode_prodi='$prodi'";
    }
    if (!empty($tahun)) {
        $whr[] = "tahun_id='$tahun_id'";
    }
    if (!empty($user)) {
        $whr[] = "idd='$user'";
    }
    if (!empty($whr)) {
        $strwhr = 'where ' . implode(' and ', $whr);
    }
    //$pengampu =array();

    $opsi .= "<option value=\"\" >..::Mata Kuliah::..</option>";
    $query = $koneksi_db->sql_query(
        " SELECT * FROM `view_jadwal` $strwhr GROUP BY kode_mk ORDER BY nama_mk"
    );
    while ($r = $koneksi_db->sql_fetchrow($query)) {
        $pengampu = explode('|', $r[idd]);
        //if ($dosen = in_array($pengampu) ) {
        //if (in_array($dosen, $pengampu)) {
        $cl = $r['id'] == $p ? 'selected' : '';
        $opsi .= "<option value=\"$r[id]\" $cl>$r[15] | $r[16]</option>";
    }

    return $opsi;
}

function opruang($prodi, $p, $dosen)
{
    global $koneksi_db;
    if (!empty($prodi)) {
        $whr[] = "kode_prodi='$prodi'";
    }
    //if (!empty($dosen)) $whr[] = "penasehat='$dosen'";
    if (!empty($whr)) {
        $strwhr = 'where ' . implode(' and ', $whr);
    }

    $opsi .= "<option value=\"\" >..::Ruang::..</option>";
    $query = $koneksi_db->sql_query(" SELECT * FROM `m_ruang` $strwhr ");
    while ($r = $koneksi_db->sql_fetchrow($query)) {
        $cl = $r['idr'] == $p ? 'selected' : '';
        $opsi .= "<option value=\"$r[0]\" $cl>$r[7]</option>";
    }
    return $opsi;
}

function krs_mahasiswa($prodi, $tahun_id)
{
    global $koneksi_db;
    $opsi .= "<option value=\"\" >..::Pilih Matakuliah::..</option>";
    $query = $koneksi_db->sql_query(
        "SELECT * FROM view_jadwal where tahun_id='$tahun_id' ORDER BY nama_mk"
    );
    while ($r = $koneksi_db->sql_fetchrow($query)) {
        $cl = $r['id'] == $p ? 'selected' : '';
        $opsi .= "<option value=\"$r[0]\" $cl>$r[15] - $r[16]</option>";
    }
    return $opsi;
}

function kelas_mahasiswa($prodi)
{
    global $koneksi_db;
    $kode_mk = $_GET['kode_mk'];
    $opsi .= "<option value=\"\" >..::Pilih Kelas::..</option>";
    $kelas = $koneksi_db->sql_query(
        "SELECT * FROM view_jadwal WHERE id='$kode_mk'"
    );
    while ($r = $koneksi_db->sql_fetchrow($kelas)) {
        $cl = $r['id'] == $p ? 'selected' : '';
        $opsi .= "<option value=\"$r[0]\" $cl>$r[13]</option>";
    }
    return $opsi;
}

function opkelas_krs($prodi, $tahun_id)
{
    global $koneksi_db;

    $opsi .= "<option value=\"\" >..::Kelas::..</option>";
    $query = $koneksi_db->sql_query(
        " SELECT DISTINCT kelas FROM `view_jadwal` where tahun_id='$tahun_id' "
    );
    while ($r = $koneksi_db->sql_fetchrow($query)) {
        $cl = $r['kelas'] == $p ? 'selected' : '';
        $opsi .= "<option value=\"$r[0]\" $cl>$r[0]</option>";
    }
    return $opsi;
}

function opkelas($prodi, $p, $dosen)
{
    global $koneksi_db;
    $kode_mk = $_SESSION['kode_mk'];
    if (!empty($prodi)) {
        $whr[] = "kode_prodi='$prodi'";
    }
    if (!empty($tahun)) {
        $whr[] = "tahun_id='$tahun_id'";
    }
    if (!empty($kode_mk)) {
        $whr[] = "id='$kode_mk'";
    }

    //if (!empty($dosen)) $whr[] = "penasehat='$dosen'";
    if (!empty($whr)) {
        $strwhr = 'where ' . implode(' and ', $whr);
    }

    $opsi .= "<option value=\"\" >..::Kelas::..</option>";
    $query = $koneksi_db->sql_query(
        " SELECT DISTINCT kelas FROM `view_jadwal` $strwhr "
    );
    while ($r = $koneksi_db->sql_fetchrow($query)) {
        $cl = $r['kelas'] == $p ? 'selected' : '';
        $opsi .= "<option value=\"$r[0]\" $cl>$r[0]</option>";
    }
    return $opsi;
}

function opkelaskrs($prodi, $p)
{
    global $koneksi_db;
    $kode_mk = $_SESSION['kode_mk'];
    if (!empty($prodi)) {
        $whr[] = "kode_prodi='$prodi'";
    }
    if (!empty($tahun)) {
        $whr[] = "tahun_id='$tahun_id'";
    }
    //if (!empty($dosen)) $whr[] = "penasehat='$dosen'";
    if (!empty($whr)) {
        $strwhr = 'where ' . implode(' and ', $whr);
    }

    $opsi .= "<option value=\"\" >..::Kelas::..</option>";
    $query = $koneksi_db->sql_query(
        "SELECT DISTINCT kelas FROM `view_jadwal` $strwhr"
    );
    while ($r = $koneksi_db->sql_fetchrow($query)) {
        if (
            $koneksi_db->sql_numrows(
                $koneksi_db->sql_query(
                    "SELECT * FROM view_jadwal where kelas='" .
                        $r[kelas] .
                        "'  and id='$kode_mk'"
                )
            ) > 0
        ) {
            $cl = $r['kelas'] == $p ? 'selected' : '';
            $opsi .= "<option value=\"$r[0]\" $cl>$r[0]</option>";
        }
    }
    return $opsi;
}

function opkurikulum($prodi, $p)
{
    global $koneksi_db;
    if (!empty($prodi)) {
        $whr[] = "kode_prodi='$prodi'";
    }
    //if (!empty($semester)) $whr[] = "semester='$semester'";
    if (!empty($whr)) {
        $strwhr = 'where ' . implode(' and ', $whr);
    }

    $opsi .= "<option value=\"\" >..::Kurikulum::..</option>";
    $query = $koneksi_db->sql_query(" SELECT * FROM `m_kurikulum` $strwhr");
    while ($r = $koneksi_db->sql_fetchrow($query)) {
        $cl = $r['id'] == $p ? 'selected' : '';
        $opsi .= "<option value=\"$r[id]\" $cl>$r[0] | $r[nama_kurikulum]</option>";
    }
    return $opsi;
}

function opkota($p)
{
    global $koneksi_db;
    $opsi .= "<option value=\"\" >..::Pilih Kota::..</option>";
    $query = $koneksi_db->sql_query('SELECT * FROM r_kota order by nama_kota');
    while ($r = $koneksi_db->sql_fetchrow($query)) {
        $cl = $r['kode_kota'] == $p ? 'selected' : '';
        $opsi .= "<option value=\"$r[1]\" $cl>$r[2]</option>";
    }
    return $opsi;
}

function oppropinsi($p)
{
    global $koneksi_db;
    $opsi .= "<option value=\"\" >..::Pilih Propinsi::..</option>";
    $query = $koneksi_db->sql_query('SELECT * FROM r_propinsi ');
    while ($r = $koneksi_db->sql_fetchrow($query)) {
        $cl = $r['kode_propinsi'] == $p ? 'selected' : '';
        $opsi .= "<option value=\"$r[0]\" $cl>$r[1]</option>";
    }
    return $opsi;
}

function opbenua($p)
{
    global $koneksi_db;
    $opsi .= "<option value=\"\" >..::Pilih Benua::..</option>";
    $query = $koneksi_db->sql_query('SELECT * FROM r_benua ');
    while ($r = $koneksi_db->sql_fetchrow($query)) {
        $cl = $r['KodeBenua'] == $p ? 'selected' : '';
        $opsi .= "<option value=\"$r[0]\" $cl>$r[1]</option>";
    }
    return $opsi;
}

function opnegara($p)
{
    global $koneksi_db;
    $opsi .= "<option value=\"\" >..::Pilih Negara::..</option>";
    $query = $koneksi_db->sql_query('SELECT * FROM r_negara ');
    while ($r = $koneksi_db->sql_fetchrow($query)) {
        $cl = $r['KodeNegara'] == $p ? 'selected' : '';
        $opsi .= "<option value=\"$r[3]\" $cl>$r[4]</option>";
    }
    return $opsi;
}

function opdaftarpt($p)
{
    global $koneksi_db;
    $opsi .= "<option value=\"\" >..::Pilih Perguruan Tinggi::..</option>";
    $query = $koneksi_db->sql_query('SELECT * FROM r_perguruan_tinggi ');
    while ($r = $koneksi_db->sql_fetchrow($query)) {
        $cl = $r[0] == $p ? 'selected' : '';
        $opsi .= "<option value=\"$r[0]\" $cl>$r[1]</option>";
    }
    return $opsi;
}

function opdaftarsekolah($p)
{
    global $koneksi_db;
    $opsi .= "<option value=\"\" >..::Pilih Sekolah::..</option>";
    $query = $koneksi_db->sql_query('SELECT * FROM r_asalsekolah ');
    while ($r = $koneksi_db->sql_fetchrow($query)) {
        $cl = $r[0] == $p ? 'selected' : '';
        $opsi .= "<option value=\"$r[0]\" $cl>$r[1]</option>";
    }
    return $opsi;
}

function oppt($p)
{
    global $koneksi_db;
    $opsi .= "<option value=\"\" >..::Pilih Kode::..</option>";
    $query = $koneksi_db->sql_query('SELECT * FROM m_perguruan_tinggi');
    while ($r = $koneksi_db->sql_fetchrow($query)) {
        $cl = $r['kode_pt'] == $p ? 'selected' : '';
        $opsi .= "<option value=\"$r[0]\" $cl>$r[2]</option>";
    }
    return $opsi;
}

function opfak($p)
{
    global $koneksi_db;
    $opsi .= "<option value=\"\" >..::Pilih Kode::..</option>";
    $query = $koneksi_db->sql_query('SELECT * FROM m_fakultas');
    while ($r = $koneksi_db->sql_fetchrow($query)) {
        $cl = $r['kode_fakultas'] == $p ? 'selected' : '';
        $opsi .= "<option value=\"$r[0]\" $cl>$r[2]</option>";
    }
    return $opsi;
}

function opjur($p)
{
    global $koneksi_db;
    $opsi .= "<option value=\"\" >..::Pilih Kode::..</option>";
    $query = $koneksi_db->sql_query('SELECT * FROM m_kosentrasi');
    while ($r = $koneksi_db->sql_fetchrow($query)) {
        $opsi .= "<option value=\"$r[0]\" $cl>$r[3]</option>";
        $cl = $r['kode_kosentrasi'] == $p ? 'selected' : '';
    }
    return $opsi;
}

function opprodi($p)
{
    global $koneksi_db;
    $opsi .= "<option value=\"\" >..::Pilih Kode::..</option>";
    $query = $koneksi_db->sql_query('SELECT * FROM m_program_studi');
    while ($r = $koneksi_db->sql_fetchrow($query)) {
        $cl = $r['kode_prodi'] == $p ? 'selected' : '';
        $opsi .= "<option value=\"$r[0]\" $cl>$r[5]</option>";
    }
    return $opsi;
}

function opkonsentrasi($p)
{
    global $koneksi_db;
    $opsi .= "<option value=\"\" >..::Pilih Kode::..</option>";
    $query = $koneksi_db->sql_query('SELECT * FROM m_konsentrasi');
    while ($r = $koneksi_db->sql_fetchrow($query)) {
        $cl = $r['kode_konsentrasi'] == $p ? 'selected' : '';
        $opsi .= "<option value=\"$r[0]\" $cl>$r[4]</option>";
    }
    return $opsi;
}

function opprodiasal($p)
{
    global $koneksi_db;
    $opsi .= "<option value=\"\" >..::Pilih Kode::..</option>";
    $query = $koneksi_db->sql_query('SELECT * FROM r_prodi');
    while ($r = $koneksi_db->sql_fetchrow($query)) {
        $cl = $r['kode_prodi'] == $p ? 'selected' : '';
        $opsi .= "<option value=\"$r[0]\" $cl>$r[1]</option>";
    }
    return $opsi;
}
function opdos($p)
{
    global $koneksi_db;
    $opsi .= "<option value=\"\" >..::Pilih Dosen::..</option>";
    $query = $koneksi_db->sql_query(
        'SELECT * FROM m_dosen order by nama_dosen asc '
    );
    while ($r = $koneksi_db->sql_fetchrow($query)) {
        $cl = $r['idd'] == $p ? 'selected' : '';
        $opsi .= "<option value=\"$r[0]\" $cl>$r[8]</option>";
    }
    return $opsi;
}
function opdosen($p)
{
    global $koneksi_db;
    $opsi .= "<option value=\"\" >..::Pilih Dosen::..</option>";
    $query = $koneksi_db->sql_query('SELECT * FROM m_dosen ');
    while ($r = $koneksi_db->sql_fetchrow($query)) {
        $cl = $r['idd'] == $p ? 'selected' : '';
        $opsi .= "<option value=\"$r[0]\" $cl>$r[8]</option>";
    }
    return $opsi;
}

function opmahasiswa($p)
{
    global $koneksi_db;

    $opsi .= "<option value=\"\" >..::Pilih Mahasiswa::..</option>";
    $query = $koneksi_db->sql_query('SELECT * FROM m_mahasiswa');
    while ($r = $koneksi_db->sql_fetchrow($query)) {
        $cl = $r['idm'] == $p ? 'selected' : '';
        $opsi .= "<option value=\"$r[0]\" $cl>$r[6]</option>";
    }
    return $opsi;
}

function opasalsekolah($p)
{
    global $koneksi_db;
    $opsi .= "<option value=\"\" >..::Pilih Asal Sekolah::..</option>";
    $query = $koneksi_db->sql_query('SELECT * FROM r_asalsekolah');
    while ($r = $koneksi_db->sql_fetchrow($query)) {
        $cl = $r['SekolahID'] == $p ? 'selected' : '';
        $opsi .= "<option value=\"$r[0]\" $cl>$r[1]</option>";
    }
    return $opsi;
}

function opkategori($p)
{
    global $koneksi_db;
    $opsi .= "<option value=\"\" >..::Pilih Kategori::..</option>";
    $query = $koneksi_db->sql_query('SELECT * FROM m_kategori');
    while ($r = $koneksi_db->sql_fetchrow($query)) {
        $cl = $r['kategoriID'] == $p ? 'selected' : '';
        $opsi .= "<option value=\"$r[0]\" $cl>$r[1]</option>";
    }
    return $opsi;
}

function opasalpt($p)
{
    global $koneksi_db;
    $opsi .= "<option value=\"\" >..::Pilih Asal Perguruan Tinggi::..</option>";
    $query = $koneksi_db->sql_query('SELECT * FROM r_perguruan_tinggi');
    while ($r = $koneksi_db->sql_fetchrow($query)) {
        $cl = $r['PerguruanTinggiID'] == $p ? 'selected' : '';
        $opsi .= "<option value=\"$r[0]\" $cl>$r[1]</option>";
    }
    return $opsi;
}

function opmahasiswapa($p, $dosen)
{
    global $koneksi_db, $prodi;
    $whr[] = "kode_prodi='$prodi'";
    if (!empty($dosen)) {
        $whr[] = "where pa='$dosen'";
    }
    if (!empty($whr)) {
        $strwhr = 'where ' . implode(' and ', $whr);
    }

    $opsi .= "<option value=\"\" >..::Pilih Mahasiswa::..</option>";
    $query = $koneksi_db->sql_query("SELECT * FROM m_mahasiswa $whr");
    while ($r = $koneksi_db->sql_fetchrow($query)) {
        $cl = $r['idm'] == $p ? 'selected' : '';
        $opsi .= "<option value=\"$r[0]\" $cl>$r[6]-$r[7]</option>";
    }
    return $opsi;
}

//////////////////////
function viewdosen($p)
{
    global $koneksi_db;
    $query = $koneksi_db->sql_query(" SELECT * FROM `m_dosen` where idd ='$p'");
    $r = $koneksi_db->sql_fetchrow($query);
    $opsi .=
        "<u><b>$r[gelar_depan] " .
        strtoupper($r[nama_dosen]) .
        ", $r[gelar_belakang]</b></u><br/>NIP. $r[nip]";
    return $opsi;
}

function viewlektorkepala()
{
    global $koneksi_db;
    $query = $koneksi_db->sql_query(
        " SELECT * FROM `m_dosen` where jabatan_akademik ='D' limit 1"
    );
    $r = $koneksi_db->sql_fetchrow($query);
    $opsi .=
        "<u><b>$r[gelar_depan] " .
        strtoupper($r[nama_dosen]) .
        ", $r[gelar_belakang]</b></u><br/>NIP. $r[NIDN]";
    return $opsi;
}

function viewsmtr($p)
{
    $arr = [
        '1' => 'Satu',
        '2' => 'Dua',
        '3' => 'Tiga',
        '4' => 'Empat',
        '5' => 'Lima',
        '6' => 'Enam',
        '7' => 'Tujuh',
        '8' => 'Delapan',
        '9' => 'Sembilan',
        '10' => 'Sepuluh',
    ];
    return $arr[$p];
}

function viewfakultas($p)
{
    global $koneksi_db;
    $query = $koneksi_db->sql_query(
        "SELECT nama_fakultas FROM m_fakultas where kode_fakultas='$p' limit 1"
    );
    $r = $koneksi_db->sql_fetchrow($query);
    $opsi .= "$r[2]";
    return $opsi;
}

/*function viewkonsentrasi($p) {
global $koneksi_db;
	$query  = $koneksi_db->sql_query ("SELECT * FROM m_kosentrasi where kode_konsentrasi='$p' limit 1");
	$r = $koneksi_db->sql_fetchrow ($query);
	$opsi .= "$r[3]";
	return $opsi;
}*/

function viewprodi($p)
{
    global $koneksi_db;
    $query = $koneksi_db->sql_query(
        "SELECT * FROM m_program_studi where kode_prodi='$p' limit 1"
    );
    $r = $koneksi_db->sql_fetchrow($query);
    $opsi .= "$r[5]";
    return $opsi;
}

function viewkonsentrasi($p)
{
    global $koneksi_db;
    $query = $koneksi_db->sql_query(
        "SELECT * FROM m_konsentrasi where kode_konsentrasi='$p' limit 1"
    );
    $r = $koneksi_db->sql_fetchrow($query);
    $opsi .= "$r[4]";
    return $opsi;
}

function viewpmb($p)
{
    global $koneksi_db;
    $query = $koneksi_db->sql_query(
        "SELECT * FROM m_pmb where pmb_id='$p' limit 1"
    );
    $r = $koneksi_db->sql_fetchrow($query);
    $opsi .= "$r[0]";
    return $opsi;
}

function viewtapel($p)
{
    global $koneksi_db;
    $query = $koneksi_db->sql_query(
        "SELECT * FROM m_tahun where tahun_id='$p'"
    );
    $r = $koneksi_db->sql_fetchrow($query);
    $opsi .= "$r[1]";
    return $opsi;
}

function viewkelas($p)
{
    global $koneksi_db;
    $query = $koneksi_db->sql_query(
        " SELECT DISTINCT kelas FROM `view_jadwal` where kelas='$p' "
    );
    //$query  = $koneksi_db->sql_query (" SELECT * FROM `m_kelas` where idk ='$p'");
    $r = $koneksi_db->sql_fetchrow($query);
    $opsi .= "$r[0]";
    return $opsi;
}

function viewmatakuliah($prodi, $p)
{
    global $koneksi_db;
    $query = $koneksi_db->sql_query(
        " SELECT * FROM `m_mata_kuliah` where kode_prodi = '$prodi' and id ='$p'"
    );
    $r = $koneksi_db->sql_fetchrow($query);
    $opsi .= "$r[5]";
    return $opsi;
}

function viewkota($p)
{
    global $koneksi_db;
    $query = $koneksi_db->sql_query(
        "SELECT * FROM r_kota where kode_kota='$p'"
    );
    $r = $koneksi_db->sql_fetchrow($query);
    $opsi .= "$r[2]";
    return $opsi;
}

function viewpropinsi($p)
{
    global $koneksi_db;
    $query = $koneksi_db->sql_query(
        "SELECT * FROM r_propinsi where kode_propinsi='$p'"
    );
    $r = $koneksi_db->sql_fetchrow($query);
    $opsi .= "$r[1]";
    return $opsi;
}

function viewdaftarpt($p)
{
    global $koneksi_db;
    $query = $koneksi_db->sql_query(
        "SELECT * FROM r_perguruan_tinggi where kode_pt='$p'"
    );
    $r = $koneksi_db->sql_fetchrow($query);
    $opsi .= "$r[1]";
    return $opsi;
}

function mtAgama($kdag)
{
    global $koneksi_db;
    $opsi .= "<option value=\"\" >..::Pilih Kode::..</option>";
    $query = $koneksi_db->sql_query(
        "SELECT * FROM m_agama where kode_ag='$kdag'"
    );
    while ($r = $koneksi_db->sql_fetchrow($query)) {
        $cl = $r['kode_ag'] == $default ? 'selected' : '';
        $opsi .= "<option value=\"$r[2]\" $cl>$r[3]</option>";
    }
    return $opsi;
}
///////////////////
function opAplikasi($kode, $default)
{
    global $koneksi_db;
    $opsi .= "<option value=\"\" >..::Pilih Kode::..</option>";
    $query = $koneksi_db->sql_query(
        "SELECT * FROM r_kode where aplikasi='$kode'"
    );
    while ($r = $koneksi_db->sql_fetchrow($query)) {
        $cl = $r['kode'] == $default ? 'selected' : '';
        $opsi .= "<option value=\"$r[2]\" $cl>$r[3]</option>";
    }
    return $opsi;
}

function viewAplikasi($kode, $default)
{
    global $koneksi_db;
    $query = $koneksi_db->sql_query(
        "SELECT * FROM r_kode where aplikasi='$kode' and kode ='$default' limit 1"
    );
    $r = $koneksi_db->sql_fetchrow($query);
    $opsi .= "$r[3]";
    return $opsi;
}

function HitungBatasStudi($tahun_id, $prodi)
{
    global $koneksi_db;
    $DefJumlahTahun = 3;
    $prd = $koneksi_db->sql_fetchrow(
        $koneksi_db->sql_query(
            "SELECT * FROM m_program_studi where kode_prodi ='$prodi' limit 1"
        )
    );

    $_thn = substr($tahun_id, 0, 4);
    $_ses = substr($tahun_id, 4, 1);

    $_jmlthn =
        $prd['jumlah_sesi'] == 0
            ? $DefJumlahTahun
            : floor($prd['batas_sesi'] / $prd['jumlah_sesi']);

    $_sisa = $prd['batas_sesi'] % $prd['jumlah_sesi'];
    $_BatasTahun = $_thn + $_jmlthn;
    $_BatasSemes = $_ses + $_sisa;
    $_BatasSemes =
        $_BatasSemes > $prd['jumlah_sesi']
            ? $_BatasSemes - $prd['jumlah_sesi']
            : $_BatasSemes;
    $BatasStudi = $_BatasTahun . $_BatasSemes;
    return $BatasStudi;
}

function NamaTahun($tahun, $prodi)
{
    global $koneksi_db;
    $arr = ['1' => 'Ganjil', '2' => 'Genap'];
    $_tahun = substr($tahun, 0, 4) + 0;
    $_tahun1 = $_tahun + 1;
    $_smt = substr($tahun, 4, 4);
    $prd = $koneksi_db->sql_fetchrow(
        $koneksi_db->sql_query(
            "SELECT * FROM m_program_studi where kode_prodi ='$prodi' limit 1"
        )
    );
    $NamaSesi = empty($prodi) ? '' : $prd['nama_sesi'];
    return $NamaSesi . ' ' . $arr[$_smt] . " $_tahun/$_tahun1";
}

function BuatNIM($tahun, $fak)
{
    global $koneksi_db;

    $_tahun = substr($tahun, 0, 4) + 0;
    $inisial = $fak . $_tahun;
    //$besar = $koneksi_db->sql_numrows($koneksi_db->sql_query("select * from m_mahasiswa where kode_prodi='$prodi' and semester_masuk='$tahun' " ));
    //$besar = $besar+1;
    $row = $koneksi_db->sql_fetchrow(
        $koneksi_db->sql_query(
            "select max(NIM) from m_mahasiswa where kode_fak='$fak' and tahun_masuk='$_tahun' "
        )
    );
    //$besarx = $besar['besar'];
    $panjang = 15;
    if ($row[0] == '') {
        $angka = 0;
    } else {
        $angka = substr($row[0], strlen($inisial));
    }

    $angka++;
    $angka = strval($angka);
    $tmp = '';
    for ($i = 1; $i <= $panjang - strlen($inisial) - strlen($angka); $i++) {
        $tmp = $tmp . '0';
    }
    return $inisial . $tmp . $angka;
}

function CekBatasKRS()
{
    global $koneksi_db;
    $query = $koneksi_db->sql_query(
        "SELECT * FROM m_tahun where buka='Y' and krs_mulai <= CURDATE() and krs_selesai >= CURDATE()"
    );
    $total = $koneksi_db->sql_numrows($query);
    $data = $koneksi_db->sql_fetchrow($query);
    //$koneksi_db->sql_freeresult ($query);
    if ($total > 0) {
        //echo "bisa krs";
        return true;
    } else {
        echo '<div class="error" style="width:70%">BATAS PENGAMBILAN KRS BELUM DIMULAI ATAU SUDAH DITUTUP</div>';
        return false;
    }
}

function CekBatasKRSOnline()
{
    global $koneksi_db;
    $query = $koneksi_db->sql_query(
        "SELECT * FROM m_tahun where buka='Y' and krs_online_mulai <= CURDATE() and krs_online_selesai >= CURDATE()"
    );
    $total = $koneksi_db->sql_numrows($query);
    $data = $koneksi_db->sql_fetchrow($query);
    //$koneksi_db->sql_freeresult ($query);
    if ($total > 0) {
        //echo "bisa krs";
        return true;
    } else {
        echo '<div class="error" style="width:70%">BATAS PENGAMBILAN KRS ONLINE BELUM DIMULAI ATAU SUDAH DITUTUP</div>';
        return false;
    }
}

function CekBatasUbahKRS()
{
    global $koneksi_db;
    $query = $koneksi_db->sql_query(
        "SELECT * FROM m_tahun where buka='Y' and krs_ubah_mulai <= CURDATE() and krs_ubah_selesai >= CURDATE()"
    );
    $total = $koneksi_db->sql_numrows($query);
    $data = $koneksi_db->sql_fetchrow($query);
    //$koneksi_db->sql_freeresult ($query);
    if ($total > 0) {
        //echo "bisa krs";
        return true;
    } else {
        echo '<div class="error" style="width:70%">BATAS MERUBAH STATUS KRS BELUM DIMULAI ATAU SUDAH DITUTUP</div>';
        return false;
    }
}

function CekBatasCetakKSS()
{
    global $koneksi_db;
    $query = $koneksi_db->sql_query(
        "SELECT * FROM m_tahun where buka='Y' and kss_cetak_mulai <= CURDATE() and kss_cetak_selesai >= CURDATE()"
    );
    $total = $koneksi_db->sql_numrows($query);
    $data = $koneksi_db->sql_fetchrow($query);
    //$koneksi_db->sql_freeresult ($query);
    if ($total > 0) {
        //echo "bisa krs";
        return true;
    } else {
        echo '<div class="error" style="width:70%">BATAS CETAK KSS BELUM DIMULAI ATAU SUDAH DITUTUP</div>';
        return false;
    }
}

function CekBatasBiaya()
{
    global $koneksi_db;
    $query = $koneksi_db->sql_query(
        "SELECT * FROM m_tahun where buka='Y' and bayar_mulai <= CURDATE() and bayar_selesai >= CURDATE()"
    );
    $total = $koneksi_db->sql_numrows($query);
    $data = $koneksi_db->sql_fetchrow($query);
    //$koneksi_db->sql_freeresult ($query);
    if ($total > 0) {
        //echo "bisa krs";
        return true;
    } else {
        echo '<div class="error" style="width:70%">BATAS PEMABAYAR BELUM DIMULAI ATAU SUDAH DITUTUP</div>';
        return false;
    }
}

function CekBatasCuti()
{
    global $koneksi_db;
    $query = $koneksi_db->sql_query(
        "SELECT * FROM m_tahun where buka='Y' and cuti_mulai <= CURDATE() "
    );
    $total = $koneksi_db->sql_numrows($query);
    $data = $koneksi_db->sql_fetchrow($query);
    //$koneksi_db->sql_freeresult ($query);
    if ($total > 0) {
        //echo "bisa krs";
        return true;
    } else {
        echo '<div class="error" style="width:70%">BATAS PENGAJUAN CUTI BELUM DIMULAI</div>';
        return false;
    }
}

function CekBatasMundur()
{
    global $koneksi_db;
    $query = $koneksi_db->sql_query(
        "SELECT * FROM m_tahun where buka='Y' and mundur_mulai <= CURDATE() "
    );
    $total = $koneksi_db->sql_numrows($query);
    $data = $koneksi_db->sql_fetchrow($query);
    //$koneksi_db->sql_freeresult ($query);
    if ($total > 0) {
        //echo "bisa krs";
        return true;
    } else {
        echo '<div class="error" style="width:70%">BATAS PENGAJUAN MUNDUR BELUM DIMULAI</div>';
        return false;
    }
}

function CekPerwalian($p)
{
    global $koneksi_db;
    $query = $koneksi_db->sql_query(
        "SELECT * FROM m_kelas where penasehat='$p' "
    );
    $total = $koneksi_db->sql_numrows($query);
    $data = $koneksi_db->sql_fetchrow($query);
    //$koneksi_db->sql_freeresult ($query);
    if ($total > 0) {
        //echo "bisa input krs";
        return true;
    } else {
        echo '<div class="error" style="width:70%">Maaf.. Anda tidak menjadi Wali Kelas</div>';
        return false;
    }
}

function tugas($prodi, $tahun, $kode_mk, $idm)
{
    global $koneksi_db;
    $q = $koneksi_db->sql_fetchrow(
        $koneksi_db->sql_query("SELECT nilai as t FROM t_mahasiswa_nilai 
	where kode_prodi='$prodi' 
	and tahun_id='$tahun' 
	and id='$kode_mk' 
	and idm='$idm' 
	and jenis_nilai='TUGAS'
	")
    );

    return $q['t'];
}

function uts($prodi, $tahun, $kode_mk, $idm)
{
    global $koneksi_db;
    $q = $koneksi_db->sql_fetchrow(
        $koneksi_db->sql_query("SELECT nilai as t FROM t_mahasiswa_nilai 
	where kode_prodi='$prodi' 
	and tahun_id='$tahun' 
	and id='$kode_mk' 
	and idm='$idm' 
	and jenis_nilai='UTS'
	")
    );

    return $q['t'];
}

function uas($prodi, $tahun, $kode_mk, $idm)
{
    global $koneksi_db;
    $q = $koneksi_db->sql_fetchrow(
        $koneksi_db->sql_query("SELECT nilai as t FROM t_mahasiswa_nilai 
	where kode_prodi='$prodi' 
	and tahun_id='$tahun' 
	and id='$kode_mk' 
	and idm='$idm' 
	and jenis_nilai='UAS'
	")
    );

    return $q['t'];
}

function hadir($prodi, $tahun, $kode_mk, $idm)
{
    global $koneksi_db;
    $q = $koneksi_db->sql_fetchrow(
        $koneksi_db->sql_query("SELECT nilai as t FROM t_mahasiswa_nilai 
	where kode_prodi='$prodi' 
	and tahun_id='$tahun' 
	and id='$kode_mk' 
	and idm='$idm' 
	and jenis_nilai='HADIR'
	")
    );

    return $q['t'];
}

/*function total_nilai($prodi, $tahun, $kode_mk,  $idm ) {
global $koneksi_db;
$q = $koneksi_db->sql_fetchrow($koneksi_db->sql_query( "SELECT nilai as t FROM t_mahasiswa_nilai 
	where kode_prodi='$prodi' 
	and tahun_id='$tahun' 
	and id='$kode_mk' 
	and idm='$idm' 
	" ));				
		
return  $q['t'];
}*/

/*function banyak_nilai($prodi, $tahun, $kode_mk, $idm ) {
global $koneksi_db;
$total = $koneksi_db->sql_numrows($koneksi_db->sql_query( "SELECT nilai FROM t_mahasiswa_nilai 
	where kode_prodi='$prodi' 
	and tahun_id='$tahun' 
	and id='$kode_mk' 
	and idm='$idm' 
	" ));					
return  $total;
}*/

function nilai_ke($prodi, $tahun, $kode_mk, $kelas, $jenis_nilai, $idm)
{
    global $koneksi_db;
    if (!empty($prodi)) {
        $whr[] = "kode_prodi='$prodi'";
    }
    if (!empty($tahun)) {
        $whr[] = "tahun_id='$tahun'";
    }
    //if (!empty($semester)) $whr[] = "semester='$semester'";
    if (!empty($kode_mk)) {
        $whr[] = "id='$kode_mk'";
    }
    //if (!empty($kelas)) $whr[] = "kelas='$kelas'";
    if (!empty($jenis_nilai)) {
        $whr[] = "jenis_nilai='$jenis_nilai'";
    }
    if (!empty($idm)) {
        $whr[] = "idm='$idm'";
    }
    if (!empty($whr)) {
        $strwhr = 'where ' . implode(' and ', $whr);
    }

    $row = $koneksi_db->sql_fetchrow(
        $koneksi_db->sql_query(
            "SELECT max(nilai_ke) as maximal FROM t_mahasiswa_nilai $strwhr "
        )
    );
    return $row['maximal'];
}

function nilai($prodi, $tahun, $kode_mk, $kelas, $jenis_nilai, $idm)
{
    global $koneksi_db;
    if (!empty($prodi)) {
        $whr[] = "kode_prodi='$prodi'";
    }
    if (!empty($tahun)) {
        $whr[] = "tahun_id='$tahun'";
    }
    //if (!empty($semester)) $whr[] = "semester='$semester'";
    if (!empty($kode_mk)) {
        $whr[] = "id='$kode_mk'";
    }
    //if (!empty($kelas)) $whr[] = "kelas='$kelas'";
    if (!empty($jenis_nilai)) {
        $whr[] = "jenis_nilai='$jenis_nilai'";
    }
    if (!empty($idm)) {
        $whr[] = "idm='$idm'";
    }
    if (!empty($whr)) {
        $strwhr = 'where ' . implode(' and ', $whr);
    }

    $row = $koneksi_db->sql_fetchrow(
        $koneksi_db->sql_query("SELECT nilai FROM t_mahasiswa_nilai $strwhr ")
    );
    return $row['nilai'];
}

function gradeNilai($prodi, $nilai)
{
    global $koneksi_db;
    $q = $koneksi_db->sql_fetchrow(
        $koneksi_db->sql_query("SELECT * FROM m_nilai
	where kode_prodi='$prodi' 
	and nilai_min <= $nilai and   nilai_max >= $nilai
	")
    );

    return $q['nilai'];
}

function bobotNilai($prodi, $nilai)
{
    global $koneksi_db;
    $q = $koneksi_db->sql_fetchrow(
        $koneksi_db->sql_query("SELECT * FROM m_nilai
	where kode_prodi='$prodi' 
	and nilai_min <= $nilai and  nilai_max >= $nilai
	")
    );

    return $q['bobot'];
}

function lulusNilai($prodi, $nilai)
{
    global $koneksi_db;
    $q = $koneksi_db->sql_fetchrow(
        $koneksi_db->sql_query("SELECT * FROM m_nilai
	where kode_prodi='$prodi' 
	and nilai_min <= $nilai and  nilai_max >= $nilai
	")
    );

    return $q['lulus'];
}

function jumlah_mk($prodi, $tahun, $idm)
{
    global $koneksi_db;
    if (!empty($prodi)) {
        $whr[] = "kode_prodi='$prodi'";
    }
    if (!empty($tahun)) {
        $whr[] = "tahun_id='$tahun'";
    }
    if (!empty($semester)) {
        $whr[] = "semester='$semester'";
    }
    if (!empty($idm)) {
        $whr[] = "idm='$idm'";
    }
    if (!empty($whr)) {
        $strwhr = 'where' . implode(' and ', $whr);
    }

    $total = $koneksi_db->sql_numrows(
        $koneksi_db->sql_query("SELECT id FROM t_mahasiswa_krs $strwhr")
    );
    return $total;
}

function jumlah_sks($prodi, $tahun, $idm)
{
    global $koneksi_db;
    if (!empty($prodi)) {
        $whr[] = "kode_prodi='$prodi'";
    }
    if (!empty($tahun)) {
        $whr[] = "tahun_id='$tahun'";
    }
    //if (!empty($semester)) $whr[] = "semester='$semester'";
    if (!empty($idm)) {
        $whr[] = "idm='$idm'";
    }
    if (!empty($whr)) {
        $strwhr = 'where ' . implode(' and ', $whr);
    }

    $q = $koneksi_db->sql_fetchrow(
        $koneksi_db->sql_query("SELECT sum(sks) as t FROM (SELECT sks from t_mahasiswa_krs  $strwhr and 
     t_mahasiswa_krs.nilai !='' GROUP BY idm, id ) as jumlah")
    );

    return $q['t'];
}

function jumlah_bobot($prodi, $tahun, $semester, $idm)
{
    global $koneksi_db;
    if (!empty($prodi)) {
        $whr[] = "kode_prodi='$prodi'";
    }
    if (!empty($tahun)) {
        $whr[] = "tahun_id='$tahun'";
    }
    if (!empty($semester)) {
        $whr[] = "semester='$semester'";
    }
    if (!empty($idm)) {
        $whr[] = "idm='$idm'";
    }
    if (!empty($whr)) {
        $strwhr = 'where ' . implode(' and ', $whr);
    }

    $q = $koneksi_db->sql_fetchrow(
        $koneksi_db->sql_query("SELECT sum(bobot) as t FROM (SELECT bobot from t_mahasiswa_krs  $strwhr and 
     t_mahasiswa_krs.nilai !='' GROUP BY idm, id ) as jumlah")
    );
    return $q['t'];
}

function jumlah_ip($prodi, $tahun, $idm)
{
    global $koneksi_db;
    if (!empty($prodi)) {
        $whr[] = "kode_prodi='$prodi'";
    }
    if (!empty($tahun)) {
        $whr[] = "tahun_id='$tahun'";
    }
    //if (!empty($semester)) $whr[] = "semester='$semester'";
    if (!empty($idm)) {
        $whr[] = "idm='$idm'";
    }
    if (!empty($whr)) {
        $strwhr = 'where ' . implode(' and ', $whr);
    }

    $q = $koneksi_db->sql_fetchrow(
        $koneksi_db->sql_query("SELECT sum(ip) as t FROM (SELECT ip from t_mahasiswa_krs  $strwhr and 
     t_mahasiswa_krs.nilai !='' GROUP BY idm, id ) as jumlah")
    );
    return $q['t'];
}

function kolomjadwal($prodi)
{
    global $koneksi_db, $prodi, $tahun_id;
    $smt = substr($tahun_id, 4, 4);
    if ($smt % 2 == 0) {
        $sm = 'Genap';
    } else {
        $sm = 'Ganjil';
    }
    $arr = [
        '1' => 'Satu',
        '2' => 'Dua',
        '3' => 'Tiga',
        '4' => 'Emapt',
        '5' => 'Lima',
        '6' => 'Enam',
        '7' => 'Tujuh',
        '8' => 'Delapan',
        '9' => 'Sembilan',
        '10' => 'Sepuluh',
    ];
    $prd = $koneksi_db->sql_fetchrow(
        $koneksi_db->sql_query(
            "SELECT * FROM m_program_studi where kode_prodi='$prodi'"
        )
    );
    $NamaSesi = empty($prodi) ? '' : $prd['nama_sesi'];

    for ($i = 0; $i < $prd[batas_sesi]; $i++) {
        $smtr = $i + 1;
        if ($i % 2 == 0) {
            $s = 'Genap';
        } else {
            $s = 'Ganjil';
        }
        if ($s != $sm) {
            $opsi .= "<th>$arr[$smtr]</th>";
        }
    }
    return $opsi;
}

function hitungpresensimahasiswa($prodi, $tahun, $jenis, $kode_mk, $idm)
{
    global $koneksi_db;
    if (!empty($prodi)) {
        $whr[] = "kode_prodi='$prodi'";
    }
    if (!empty($tahun)) {
        $whr[] = "tahun_id='$tahun'";
    }
    //if (!empty($semester)) $whr[] = "semester='$semester'";
    if (!empty($jenis)) {
        $whr[] = "jenis_presensi='$jenis'";
    }
    if (!empty($kode_mk)) {
        $whr[] = "id='$kode_mk'";
    }
    $whr[] = "idm='$idm'";
    if (!empty($whr)) {
        $strwhr = 'where ' . implode(' and ', $whr);
    }

    $q = $koneksi_db->sql_fetchrow(
        $koneksi_db->sql_query(
            "SELECT sum(presensi) as t FROM t_mahasiswa_presensi $strwhr "
        )
    );
    return $q['t'];
}

function hitungpresensidosen($prodi, $tahun, $jenis, $idd)
{
    global $koneksi_db;
    if (!empty($prodi)) {
        $whr[] = "kode_prodi='$prodi'";
    }
    if (!empty($tahun)) {
        $whr[] = "tahun_id='$tahun'";
    }
    //if (!empty($semester)) $whr[] = "semester='$semester'";
    if (!empty($jenis)) {
        $whr[] = "jenis_presensi='$jenis'";
    }
    $whr[] = "idd='$idd'";
    if (!empty($whr)) {
        $strwhr = 'where ' . implode(' and ', $whr);
    }

    $q = $koneksi_db->sql_fetchrow(
        $koneksi_db->sql_query(
            "SELECT sum(presensi) as t FROM t_dosen_presensi $strwhr "
        )
    );
    return $q['t'];
}
?>
