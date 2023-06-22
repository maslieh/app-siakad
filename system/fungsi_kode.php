<?php

function optahun($p)
{
    $mulai = date('Y') + 1;
    $akhir = date('Y') - 10;
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
    while ($r = $koneksi_db->sql_fetchassoc($query)) {
        $cl = $r['idj'] == $p ? 'selected' : '';
        $opsi .= "<option value=\"$r[idj]\" $cl>$r[jam]>>$r[mulai]-$r[sampai]</option>";
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
    $r = $koneksi_db->sql_fetchassoc($query);
    return '[' . $r[jam] . '] ' . $r[mulai] . '-' . $r[sampai];
}

function optopik($p)
{
    global $koneksi_db;
    $opsi .= "<option value=\"\" >..::Pilih Topik::..</option>";
    $query = $koneksi_db->sql_query(' SELECT * FROM `t_topik`  ');
    while ($r = $koneksi_db->sql_fetchassoc($query)) {
        $cl = $r[id] == $p ? 'selected' : '';
        $opsi .= "<option value=\"$r[id]\" $cl>$r[topik]</option>";
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
        $cl = $r[0] == $p ? 'selected' : '';
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
        $cl = $r[0] == $p ? 'selected' : '';
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
        $cl = $r[0] == $p ? 'selected' : '';
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
        $cl = $r[0] == $p ? 'selected' : '';
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
        $cl = $r[0] == $p ? 'selected' : '';
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
        $whr[] = "jenis_presensi='$jenis_presensi'";
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
    return $q[0];
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
    return $q[0];
}

function oplevel($p)
{
    global $koneksi_db;
    $result = $koneksi_db->sql_query(
        "SHOW COLUMNS FROM `user` WHERE Field ='level' "
    );
    $col2 = $koneksi_db->sql_fetchassoc($result);
    preg_match("/^enum\(\'(.*)\'\)$/", $col2['Type'], $matches);
    $enum = explode("','", $matches[1]);

    $opsi = "<option value=\"\" >..::Pilih Level::..</option>";

    foreach ($enum as $k => $v) {
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
    $arr = ['Ya->Y', 'Tidak->N'];
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
    //$arr = array('UTS->Nilai Ujian Tengah Semester','TUGAS->Nilai Tugas');
    //$arr = array('HADIR->Nilai Kehadiran','TUGAS->Nilai Tugas','UTS->Nilai Ujian Tengah Semester','UAS->Nilai Ujian Akhir Semester');
    $arr = [
        'TUGAS->Nilai Tugas',
        'UTS->Nilai Ujian Tengah Semester',
        'UAS->Nilai Ujian Akhir Semester',
    ];
    for ($i = 0; $i < sizeof($arr); $i++) {
        $r = Explode('->', $arr[$i]);
        $cl = $r[1] == $p ? 'selected' : '';
        $opsi .= "<option value=\"$r[0]\" $cl>$r[1]</option>";
    }
    return $opsi;
}

function opJenisNilai2($p)
{
    //$arr = array('UTS->Nilai Ujian Tengah Semester','TUGAS->Nilai Tugas');
    //$arr = array('HADIR->Nilai Kehadiran','TUGAS->Nilai Tugas','UTS->Nilai Ujian Tengah Semester','UAS->Nilai Ujian Akhir Semester');
    $arr = [
        'HADIR->Nilai Kehadiran',
        'TUGAS->Nilai Tugas',
        'UTS->Nilai Ujian Tengah Semester',
        'UAS->Nilai Ujian Akhir Semester',
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
    $prd = $koneksi_db->sql_fetchassoc(
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
    $prd = $koneksi_db->sql_fetchassoc(
        $koneksi_db->sql_query(
            "SELECT * FROM m_program_studi where kode_prodi='$prodi'"
        )
    );
    $NamaSesi = empty($prodi) ? '' : $prd['nama_sesi'];

    for ($i = 0; $i < $prd['batas_sesi']; $i++) {
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
        $cl = $r[0] == $p ? 'selected' : '';
        $opsi .= "<option value=\"$r[0]\" $cl>$r[1] (S$r[3])</option>";
    }
    return $opsi;
}

function tgl_ngajar_admin($prodi, $tahun_id, $kode_mk, $kelas, $p)
{
    global $koneksi_db;
    if (!empty($prodi)) {
        $whr[] = "a.kode_prodi='$prodi'";
    }
    if (!empty($tahun_id)) {
        $whr[] = "a.tahun_id='$tahun_id'";
    }
    if (!empty($kode_mk)) {
        $whr[] = "a.id='$kode_mk'";
    }
    if (!empty($kelas)) {
        $whr[] = "a.kelas='$kelas'";
    }
    $whr[] = "a.jenis_presensi='H'";
    //if (!empty($user)) $whr[] = "idd='$user'";
    if (!empty($whr)) {
        $strwhr = 'where ' . implode(' and ', $whr);
    }
    //$pengampu =array();

    $opsi .= "<option value=\"\" >..::Pilih Tanggal::..</option>";
    $query = $koneksi_db->sql_query(
        " SELECT a.tanggal, a.jam, mulai, sampai  FROM t_dosen_presensi a inner join m_jam b on a.jam=b.idj $strwhr"
    );
    while ($r = $koneksi_db->sql_fetchrow($query)) {
        $pengampu = explode('|', $r[idd]);
        //if ($dosen = in_array($pengampu) ) {
        //if (in_array($dosen, $pengampu)) {
        $cl = $r[0] == $p ? 'selected' : '';
        $opsi .= "<option value=\"$r[0]$r[1]\" $cl>$r[0]|$r[2]-$r[3]</option>";
    }

    return $opsi;
}

function tgl_ngajar($prodi, $tahun_id, $user, $kode_mk, $kelas, $p)
{
    global $koneksi_db;
    if (!empty($prodi)) {
        $whr[] = "a.kode_prodi='$prodi'";
    }
    if (!empty($tahun_id)) {
        $whr[] = "a.tahun_id='$tahun_id'";
    }
    if (!empty($user)) {
        $whr[] = "a.idd='$user'";
    }
    if (!empty($kode_mk)) {
        $whr[] = "a.id='$kode_mk'";
    }
    if (!empty($kelas)) {
        $whr[] = "a.kelas='$kelas'";
    }
    //if (!empty($kelas)) $whr[] = "a.tanggal='$tgl2'";
    $whr[] = "a.jenis_presensi='H'";
    //$whr[] = "a.ver='0'";
    $tgl = date('Y-m-d');
    if (!empty($whr)) {
        $strwhr = 'where ' . implode(' and ', $whr);
    }
    //$pengampu =array();

    $opsi .= "<option value=\"\" >..::Pilih Tanggal::..</option>";
    $query = $koneksi_db->sql_query(
        " SELECT a.tanggal,  a.jam, mulai, sampai  FROM t_dosen_presensi a inner join m_jam b on a.jam=b.idj $strwhr "
    );
    while ($r = $koneksi_db->sql_fetchrow($query)) {
        $pengampu = explode('|', $r[idd]);
        //if ($dosen = in_array($pengampu) ) {
        //if (in_array($dosen, $pengampu)) {
        $cl = $r[0] == $p ? 'selected' : '';
        $opsi .= "<option value=\"$r[0]$r[1]\" $cl>$r[0] | $r[2] - $r[3]</option>";
    }

    return $opsi;
}

function opperiodeyudisium($p)
{
    global $koneksi_db;
    $opsi .= "<option value=\"\" >..::Pilih Periode::..</option>";
    $query = $koneksi_db->sql_query('SELECT * FROM m_wisuda ');
    while ($r = $koneksi_db->sql_fetchassoc($query)) {
        $cl = $r['idw'] == $p ? 'selected' : '';
        $opsi .= "<option value=\"$r[idw]\" $cl>$r[tahun_id] ($r[nama])</option>";
    }
    return $opsi;
}

function oppmb($p, $prodi)
{
    global $koneksi_db;
    $opsi .= "<option value=\"\" >..::PMB::..</option>";
    $query = $koneksi_db->sql_query("SELECT * FROM m_pmb where buka='Y'");
    while ($r = $koneksi_db->sql_fetchrow($query)) {
        $cl = $r[0] == $p ? 'selected' : '';
        $opsi .= "<option value=\"$r[0]\" $cl>$r[0] - $r[5] ($r[6])</option>";
    }
    return $opsi;
}

function opmatakuliah($prodi, $tahun_id, $p)
{
    global $koneksi_db;
    if (!empty($prodi)) {
        $whr[] = "kode_prodi='$prodi'";
    }
    if (!empty($tahun_id)) {
        $whr[] = "tahun_id='$tahun_id'";
    }
    //if (!empty($semester)) $whr[] = "semester='$semester'";
    if (!empty($whr)) {
        $strwhr = 'where ' . implode(' and ', $whr);
    }

    $opsi .= "<option value=\"\" >..::Mata Kuliah::..</option>";
    $query = $koneksi_db->sql_query(
        " SELECT * FROM `m_mata_kuliah` $strwhr order by nama_mk"
    );
    while ($r = $koneksi_db->sql_fetchassoc($query)) {
        $cl = $r['id'] == $p ? 'selected' : '';
        $opsi .= "<option value=\"$r[id]\" $cl>(Semester: $r[semester] ) $r[kode_mk] - $r[nama_mk]</option>";
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
    $query = $koneksi_db->sql_query(" SELECT * FROM `view_jadwal` $strwhr");
    while ($r = $koneksi_db->sql_fetchrow($query)) {
        $cl = $r[0] == $p ? 'selected' : '';
        $opsi .= "<option value=\"$r[0]\" $cl>$r[15] - $r[16]</option>";
    }
    return $opsi;
}

function opmatakuliahdosen($prodi, $tahun_id, $user, $p)
{
    global $koneksi_db;
    if (!empty($prodi)) {
        $whr[] = "kode_prodi='$prodi'";
    }
    if (!empty($tahun_id)) {
        $whr[] = "tahun_id='$tahun_id'";
    }
    if (!empty($user)) {
        $whr[] = "idd='$user'";
    }
    if (!empty($whr)) {
        $strwhr = 'where ' . implode(' and ', $whr);
    }

    $opsi .= "<option value=\"\" >..::Mata Kuliah::..</option>";
    $query = $koneksi_db->sql_query(
        " SELECT distinct id, kode_mk, nama_mk  FROM `view_jadwal` $strwhr order by nama_mk"
    );
    while ($r = $koneksi_db->sql_fetchassoc($query)) {
        $pengampu = explode('|', $r[idd]);
        $cl = $r['id'] == $p ? 'selected' : '';
        $opsi .= "<option value=\"$r[id]\" $cl>$r[kode_mk] | $r[nama_mk]</option>";
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
        $cl = $r[0] == $p ? 'selected' : '';
        $opsi .= "<option value=\"$r[0]\" $cl>$r[6]-$r[7]</option>";
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
        $cl = $r[0] == $p ? 'selected' : '';
        $opsi .= "<option value=\"$r[0]\" $cl>$r[0]</option>";
    }
    return $opsi;
}

function opkelas($prodi, $tahun_id, $user, $kode_mk, $p)
{
    global $koneksi_db;
    $kode_mk = $_SESSION['kode_mk'];
    if (!empty($prodi)) {
        $whr[] = "kode_prodi='$prodi'";
    }
    if (!empty($tahun_id)) {
        $whr[] = "tahun_id='$tahun_id'";
    }
    if (!empty($user)) {
        $whr[] = "idd='$user'";
    }
    if (!empty($kode_mk)) {
        $whr[] = "id='$kode_mk'";
    }
    if (!empty($whr)) {
        $strwhr = 'where ' . implode(' and ', $whr);
    }

    $opsi .= "<option value=\"\" >..::Kelas::..</option>";
    $query = $koneksi_db->sql_query(
        " SELECT DISTINCT kelas FROM `view_jadwal` $strwhr "
    );
    while ($r = $koneksi_db->sql_fetchrow($query)) {
        $cl = $r[0] == $p ? 'selected' : '';
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
        $cl = $r[1] == $p ? 'selected' : '';
        $opsi .= "<option value=\"$r[1]\" $cl>$r[2]</option>";
    }
    return $opsi;
}
function kecamatan($p)
{
    global $koneksi_db;
    $opsi .= "<option value=\"\" >..::Pilih Kota::..</option>";
    $query = $koneksi_db->sql_query('select dwc.id_wil as id_wil, data_wilayah.nm_wil as provinsi,dw.nm_wil as kab,dwc.nm_wil as kecamatan from
    data_wilayah
    inner join data_wilayah dw on data_wilayah.id_wil=dw.id_induk_wilayah
    inner join data_wilayah dwc on dw.id_wil=dwc.id_induk_wilayah
    where data_wilayah.id_level_wil=1');
    while ($r = $koneksi_db->sql_fetchrow($query)) {
        $cl = $r[0] == $p ? 'selected' : '';
        $opsi .= "<option value=\"$r[0]\" $cl>$r[3], $r[2], $r[1]</option>";
    }
    return $opsi;
}

function transport($kode, $default)
{
    global $koneksi_db;
    $opsi .= "<option value=\"\" >..::Pilih Kode::..</option>";
    $query = $koneksi_db->sql_query(
        "SELECT * FROM r_kode where aplikasi='$kode'"
    );
    while ($r = $koneksi_db->sql_fetchrow($query)) {
        $cl = $r[2] == $default ? 'selected' : '';
        $opsi .= "<option value=\"$r[2]\" $cl>$r[3]</option>";
    }
    return $opsi;
}
function jnstinggal($kode, $default)
{
    global $koneksi_db;
    $opsi .= "<option value=\"\" >..::Pilih Kode::..</option>";
    $query = $koneksi_db->sql_query(
        "SELECT * FROM r_kode where aplikasi='$kode'"
    );
    while ($r = $koneksi_db->sql_fetchrow($query)) {
        $cl = $r[2] == $default ? 'selected' : '';
        $opsi .= "<option value=\"$r[2]\" $cl>$r[3]</option>";
    }
    return $opsi;
}

function oppropinsi($p)
{
    global $koneksi_db;
    $opsi .= "<option value=\"\" >..::Pilih Propinsi::..</option>";
    $query = $koneksi_db->sql_query('SELECT * FROM r_propinsi ');
    while ($r = $koneksi_db->sql_fetchrow($query)) {
        $cl = $r[0] == $p ? 'selected' : '';
        $opsi .= "<option value=\"$r[0]\" $cl>$r[1]</option>";
    }
    return $opsi;
}

function bayar($p)
{
    global $koneksi_db;
    $opsi .= "<option value=\"\" >..::Pilih bayar::..</option>";
    $query = $koneksi_db->sql_query('SELECT * FROM m_biaya ');
    while ($r = $koneksi_db->sql_fetchrow($query)) {
        $cl = $r[0] == $p ? 'selected' : '';
        $opsi .= "<option value=\"$r[0]\" $cl>$r[8] </option>";
    }
    return $opsi;
}

function oppwn($p)
{
    global $koneksi_db;
    $opsi .= "<option value=\"\" >..::Pilih Propinsi::..</option>";
    $query = $koneksi_db->sql_query('SELECT * FROM kewarganegaraan ');
    while ($r = $koneksi_db->sql_fetchrow($query)) {
        $cl = $r[0] == $p ? 'selected' : '';
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
        $cl = $r[0] == $p ? 'selected' : '';
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
        $cl = $r[0] == $p ? 'selected' : '';
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
        $cl = $r[0] == $p ? 'selected' : '';
        $opsi .= "<option value=\"$r[0]\" $cl>$r[5]</option>";
    }
    return $opsi;
}

function optahunakademik($p)
{
    global $koneksi_db;
    $opsi .= "<option value=\"\" >..::Pilih Kode::..</option>";
    $query = $koneksi_db->sql_query('SELECT * FROM m_tahun order by id_t desc');
    while ($r = $koneksi_db->sql_fetchrow($query)) {
        $cl = $r[0] == $p ? 'selected' : '';
        $opsi .= "<option value=\"$r[0]\" $cl>$r[3]</option>";
    }
    return $opsi;
}

function opkonsentrasi($p)
{
    global $koneksi_db;
    $opsi .= "<option value=\"\" >..::Pilih Kode::..</option>";
    $query = $koneksi_db->sql_query('SELECT * FROM m_konsentrasi');
    while ($r = $koneksi_db->sql_fetchrow($query)) {
        $cl = $r[0] == $p ? 'selected' : '';
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
        $cl = $r[0] == $p ? 'selected' : '';
        $opsi .= "<option value=\"$r[0]\" $cl>$r[1]</option>";
    }
    return $opsi;
}
function opdos($p)
{
    global $koneksi_db;
    $opsi .= "<option value=\"\" >..::Pilih Dosen::..</option>";
    $query = $koneksi_db->sql_query('SELECT * FROM m_dosen ');
    while ($r = $koneksi_db->sql_fetchrow($query)) {
        $cl = $r[0] == $p ? 'selected' : '';
        $opsi .= "<option value=\"$r[0]\" $cl>$r[8]</option>";
    }
    return $opsi;
}

function oppa($p)
{
    global $koneksi_db;
    $opsi .= "<option value=\"\" >..::Pilih Dosen::..</option>";
    $query = $koneksi_db->sql_query('SELECT * FROM m_dosen ');
    while ($r = $koneksi_db->sql_fetchrow($query)) {
        $cl = $r[1] == $p ? 'selected' : '';
        $opsi .= "<option value=\"$r[1]\" $cl>$r[8]</option>";
    }
    return $opsi;
}

function opdosen($p)
{
    global $koneksi_db;
    $opsi .= "<option value=\"\" >..::Pilih Dosen::..</option>";
    $query = $koneksi_db->sql_query('SELECT * FROM m_dosen ');
    while ($r = $koneksi_db->sql_fetchrow($query)) {
        $cl = $r[0] == $p ? 'selected' : '';
        $opsi .= "<option value=\"$r[0]\" $cl>$r[8]</option>";
    }
    return $opsi;
}

function opdosenPA($p)
{
    global $koneksi_db;
    $opsi .= "<option value=\"\" >..::Pilih Dosen::..</option>";
    $query = $koneksi_db->sql_query('SELECT * FROM m_dosen ');
    while ($r = $koneksi_db->sql_fetchrow($query)) {
        $cl = $r[0] == $p ? 'selected' : '';
        $opsi .= "<option value=\"$r[1]\" $cl>$r[8]</option>";
    }
    return $opsi;
}

function opmahasiswa($p)
{
    global $koneksi_db;

    $opsi .= "<option value=\"\" >..::Pilih Mahasiswa::..</option>";
    $query = $koneksi_db->sql_query('SELECT * FROM m_mahasiswa');
    while ($r = $koneksi_db->sql_fetchrow($query)) {
        $cl = $r[0] == $p ? 'selected' : '';
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
        $cl = $r[0] == $p ? 'selected' : '';
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
        $cl = $r[0] == $p ? 'selected' : '';
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
        $cl = $r[0] == $p ? 'selected' : '';
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
        $cl = $r[0] == $p ? 'selected' : '';
        $opsi .= "<option value=\"$r[0]\" $cl>$r[6]-$r[7]</option>";
    }
    return $opsi;
}

//////////////////////
function viewdosen($p)
{
    global $koneksi_db;
    $query = $koneksi_db->sql_query(" SELECT * FROM `m_dosen` where idd ='$p'");
    $r = $koneksi_db->sql_fetchassoc($query);
    $opsi .=
        "<u><b>$r[gelar_depan] " .
        strtoupper($r[nama_dosen]) .
        ", $r[gelar_belakang]</b></u><br/>NIP. $r[nip]";
    return $opsi;
}

function viewdosenPA($p)
{
    global $koneksi_db;
    $query = $koneksi_db->sql_query(" SELECT * FROM `m_dosen` where nip ='$p'");
    $r = $koneksi_db->sql_fetchassoc($query);
    $opsi .=
        "<u><b>$r[gelar_depan] " .
        strtoupper($r['nama_dosen']) .
        ", $r[gelar_belakang]</b></u><br/>NIP. $r[nip]";
    return $opsi;
}

function viewlektorkepala()
{
    global $koneksi_db;
    $query = $koneksi_db->sql_query(
        " SELECT * FROM `m_dosen` where jabatan_akademik ='D' limit 1"
    );
    $r = $koneksi_db->sql_fetchassoc($query);
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
        $cl = $r[2] == $default ? 'selected' : '';
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
        $cl = $r[2] == $default ? 'selected' : '';
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
    $prd = $koneksi_db->sql_fetchassoc(
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
    $prd = $koneksi_db->sql_fetchassoc(
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

function CekBatasNilaiOnline()
{
    global $koneksi_db, $tahun_id;
    $query = $koneksi_db->sql_query(
        "SELECT * FROM m_tahun where tahun_id ='" .
            $tahun_id .
            "' and nilai_mulai <= CURDATE() and nilai_selesai >= CURDATE()"
    );
    $total = $koneksi_db->sql_numrows($query);
    $data = $koneksi_db->sql_fetchrow($query);
    //$koneksi_db->sql_freeresult ($query);
    if ($total > 0) {
        //echo "bisa krs";
        return true;
    } else {
        echo '<div class="error" style="width:70%">BATAS PENGISIAN NILAI BELUM DIMULAI ATAU SUDAH DITUTUP</div>';
        return false;
    }
}

function CekBatasKRS()
{
    global $koneksi_db, $tahun_id;
    $query = $koneksi_db->sql_query(
        "SELECT * FROM m_tahun where tahun_id ='" .
            $tahun_id .
            "' and krs_mulai <= CURDATE() and krs_selesai >= CURDATE()"
    );
    $total = $koneksi_db->sql_numrows($query);
    $data = $koneksi_db->sql_fetchassoc($query);
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
        "SELECT * FROM m_tahun where tahun_id ='" .
            $tahun_id .
            "' and krs_online_mulai <= CURDATE() and krs_online_selesai >= CURDATE()"
    );
    $total = $koneksi_db->sql_numrows($query);
    $data = $koneksi_db->sql_fetchassoc($query);
    //$koneksi_db->sql_freeresult ($query);
    if ($data != '') {
        //echo "bisa krs";
        return true;
    } else {
        echo '<div class="error" style="width:70%">BATAS PENGAMBILAN KRS ONLINE BELUM DIMULAI ATAU SUDAH DITUTUP</div>';
        return false;
    }
}

function CekBatasKuliah()
{
    global $koneksi_db, $tahun_id;
    $query = $koneksi_db->sql_query(
        "SELECT * FROM m_tahun where tahun_id ='" .
            $tahun_id .
            "' and kuliah_mulai <= CURDATE() and kuliah_selesai >= CURDATE()"
    );
    $total = $koneksi_db->sql_numrows($query);
    $data = $koneksi_db->sql_fetchassoc($query);
    //$koneksi_db->sql_freeresult ($query);
    if ($total > 0) {
        //echo "bisa krs";
        return true;
    } else {
        echo '<div class="error" style="width:70%">PERKULIAHAN BELUM DIMULAI ATAU SUDAH SELESAI </div>';
        return false;
    }
}

function CekBatasUbahKRS()
{
    global $koneksi_db;
    $query = $koneksi_db->sql_query(
        "SELECT * FROM m_tahun where tahun_id ='" .
            $tahun_id .
            "' and krs_ubah_mulai <= CURDATE() and krs_ubah_selesai >= CURDATE()"
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
        "SELECT * FROM m_tahun where tahun_id ='" .
            $tahun_id .
            "' and kss_cetak_mulai <= CURDATE() and kss_cetak_selesai >= CURDATE()"
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
        "SELECT * FROM m_tahun where tahun_id ='" .
            $tahun_id .
            "' and bayar_mulai <= CURDATE() and bayar_selesai >= CURDATE()"
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
        "SELECT * FROM m_tahun where tahun_id ='" .
            $tahun_id .
            "' and cuti_mulai <= CURDATE() "
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
        "SELECT * FROM m_tahun where tahun_id ='" .
            $tahun_id .
            "' and mundur_mulai <= CURDATE() "
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

    return $q[0];
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

    return $q[0];
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

    return $q[0];
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

    return $q[0];
}

function opKrsSiswa($kode)
{
    global $koneksi_db;
    $opsi .= "<option value=\"\" >..::Pilih MataKuliah::..</option>";
    return $opsi;
}

function opKelasSiswa($kode)
{
    global $koneksi_db;
    $opsi .= "<option value=\"\" >..::Pilih Kelas::..</option>";
    return $opsi;
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
    if (!empty($kode_mk)) {
        $whr[] = "id='$kode_mk'";
    }
    if (!empty($kelas)) {
        $whr[] = "kelas='$kelas'";
    }
    if (!empty($jenis_nilai)) {
        $whr[] = "jenis_nilai='$jenis_nilai'";
    }
    if (!empty($idm)) {
        $whr[] = "idm='$idm'";
    }
    if (!empty($whr)) {
        $strwhr = 'where ' . implode(' and ', $whr);
    }

    $row = $koneksi_db->sql_fetchassoc(
        $koneksi_db->sql_query(
            "SELECT max(nilai_ke) as maximal FROM t_mahasiswa_nilai $strwhr "
        )
    );
    return $row['maximal'];
}

function gradeNilai($prodi, $nilai)
{
    global $koneksi_db;
    $q = $koneksi_db->sql_fetchassoc(
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
    $q = $koneksi_db->sql_fetchassoc(
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
    $q = $koneksi_db->sql_fetchassoc(
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
    if (!empty($idm)) {
        $whr[] = "validasi='1'";
    }
    if (!empty($idm)) {
        $whr[] = "verifi_pa='1'";
    }
    if (!empty($whr)) {
        $strwhr = 'where ' . implode(' and ', $whr);
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
    if (empty($nilai)) {
        $whr[] = "nilai!=''";
    }
    if (!empty($idm)) {
        $whr[] = "idm='$idm'";
    }
    if (!empty($idm)) {
        $whr[] = "validasi='1'";
    }
    if (!empty($idm)) {
        $whr[] = "verifi_pa='1'";
    }
    if (!empty($whr)) {
        $strwhr = 'where ' . implode(' and ', $whr);
    }

    $q = $koneksi_db->sql_fetchrow(
        $koneksi_db->sql_query(
            "SELECT  sum(sks) as t FROM (select distinct id, sks from t_mahasiswa_krs $strwhr)as s"
        )
    );
    return $q[0];
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
    if (!empty($idm)) {
        $whr[] = "validasi='1'";
    }
    if (!empty($idm)) {
        $whr[] = "verifi_pa='1'";
    }
    if (!empty($whr)) {
        $strwhr = 'where ' . implode(' and ', $whr);
    }

    $q = $koneksi_db->sql_fetchrow(
        $koneksi_db->sql_query(
            "SELECT sum(bobot) as t FROM (select distinct id, bobot from t_mahasiswa_krs  $strwhr) as s	"
        )
    );
    return $q[0];
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
    if (!empty($idm)) {
        $whr[] = "validasi='1'";
    }
    if (!empty($idm)) {
        $whr[] = "verifi_pa='1'";
    }
    if (!empty($whr)) {
        $strwhr = 'where ' . implode(' and ', $whr);
    }

    $q = $koneksi_db->sql_fetchrow(
        $koneksi_db->sql_query(
            "SELECT sum(maxip) as t  from (select distinct id, max(ip) as maxip from t_mahasiswa_krs $strwhr group by id) as s	"
        )
    );
    return $q[0];
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
    $prd = $koneksi_db->sql_fetchassoc(
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
    return $q[0];
}

function hitungpresensidosen($prodi, $id, $kelas, $tahun, $jenis, $idd)
{
    global $koneksi_db;
    if (!empty($prodi)) {
        $whr[] = "kode_prodi='$prodi'";
    }
    if (!empty($id)) {
        $whr[] = "id='$id'";
    }
    if (!empty($kelas)) {
        $whr[] = "kelas='$kelas'";
    }
    if (!empty($tahun)) {
        $whr[] = "tahun_id='$tahun'";
    }
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
    return $q[0];
}

function opsemestermatakuliahpaket($prodi, $tahun_id, $p)
{
    global $koneksi_db;
    if (!empty($prodi)) {
        $whr[] = "a.kode_prodi='$prodi'";
    }
    if (!empty($tahun_id)) {
        $whr[] = "a.tahun_id='$tahun_id'";
    }
    if (!empty($whr)) {
        $strwhr = 'where ' . implode(' and ', $whr);
    }

    $opsi .= "<option value=\"\" >..::Semester::..</option>";
    $query = $koneksi_db->sql_query(
        " SELECT distinct semester  FROM  view_jadwal a inner join m_mata_kuliah b on a.id=b.id $strwhr"
    );
    while ($r = $koneksi_db->sql_fetchassoc($query)) {
        $cl = $r['semester'] == $p ? 'selected' : '';
        $opsi .= "<option value=\"$r[semester]\" $cl>$r[semester] </option>";
    }

    return $opsi;
}

function opjenisujian($p)
{
    global $koneksi_db;
    $result = $koneksi_db->sql_query(
        "SHOW COLUMNS FROM `t_jadwal_ujian` WHERE Field ='ujian' "
    );
    $col2 = $koneksi_db->sql_fetchassoc($result);
    preg_match("/^enum\(\'(.*)\'\)$/", $col2['Type'], $matches);
    $enum = explode("','", $matches[1]);

    $opsi = "<option value=\"\" >..::Pilih Jenis Ujian::..</option>";

    foreach ($enum as $k => $v) {
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

function opjamujian($prodi, $p)
{
    global $koneksi_db;
    if (!empty($prodi)) {
        $whr = "where kode_prodi='$prodi'";
    }
    $opsi .= "<option value=\"\" >..::Jam::..</option>";
    $query = $koneksi_db->sql_query(" SELECT * FROM `m_jam_ujian` $whr ");
    while ($r = $koneksi_db->sql_fetchassoc($query)) {
        $cl = $r['idj'] == $p ? 'selected' : '';
        $opsi .= "<option value=\"$r[idj]\" $cl>$r[jam]>>$r[mulai]-$r[sampai]</option>";
    }
    return $opsi;
}

function opmatakuliahujian($prodi, $tahun_id, $p)
{
    global $koneksi_db;
    if (!empty($prodi)) {
        $whr[] = "kode_prodi='$prodi'";
    }
    if (!empty($tahun_id)) {
        $whr[] = "tahun_id='$tahun_id'";
    }
    if (!empty($whr)) {
        $strwhr = 'where ' . implode(' and ', $whr);
    }

    $opsi .= "<option value=\"\" >..::Mata Kuliah::..</option>";
    $query = $koneksi_db->sql_query(
        " SELECT * FROM `view_jadwal` $strwhr group by id order by nama_mk"
    );
    while ($r = $koneksi_db->sql_fetchrow($query)) {
        $cl = $r[0] == $p ? 'selected' : '';
        $opsi .= "<option value=\"$r[0]\" $cl>$r[16] - $r[17]</option>";
    }
    return $opsi;
}

function opmhskuliah($prodi, $tahun_id, $kode_mk, $kelas, $p)
{
    global $koneksi_db;
    $kode_mk = $_SESSION['kode_mk'];
    $kelas = $_SESSION['kelas'];
    if (!empty($prodi)) {
        $whr[] = "t_mahasiswa_krs.kode_prodi='$prodi'";
    }
    if (!empty($tahun_id)) {
        $whr[] = "tahun_id='$tahun_id'";
    }
    if (!empty($kelas)) {
        $whr[] = "kelas='$kelas'";
    }
    if (!empty($kode_mk)) {
        $whr[] = "id='$kode_mk'";
    }
    if (!empty($whr)) {
        $strwhr = 'where ' . implode(' and ', $whr);
    }

    $opsi .= "<option value=\"\" >..::Nama Mahasiswa::..</option>";
    $query = $koneksi_db->sql_query(
        " SELECT distinct t_mahasiswa_krs.idm as idm, NIM, nama_mahasiswa FROM t_mahasiswa_krs inner join m_mahasiswa on m_mahasiswa.idm=t_mahasiswa_krs.idm $strwhr "
    );
    while ($r = $koneksi_db->sql_fetchassoc($query)) {
        $cl = $r['idm'] == $p ? 'selected' : '';
        $opsi .= "<option value=\"$r[idm]\" $cl>$r[NIM] | $r[nama_mahasiswa]</option>";
    }
    return $opsi;
}

?>
