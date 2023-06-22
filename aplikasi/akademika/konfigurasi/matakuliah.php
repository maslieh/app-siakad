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

function Matakuliah()
{
    global $koneksi_db;
    $prodi = $_SESSION['prodi'];

    $qf = $koneksi_db->sql_query('SELECT * FROM m_program_studi ');
    $totalf = $koneksi_db->sql_numrows($qf);
    if ($totalf < 1) {
        echo '<div class=error>Data Program Studi Masih Kosong, Klik <a href="index.php?m=31&op=edit_prodi&md=1">Add Program Studi</a></div>';
    } else {

        echo "<input type=button class=button-red value='Tambah Mata Kuliah' onclick=\"window.location.href='index.php?m=" .
            $_GET['m'] .
            "&op=editMakul&md=1';\">";
        /////////////////
        $besar = $koneksi_db->sql_fetchassoc(
            $koneksi_db->sql_query(
                "select max(semester) as besar from m_mata_kuliah where kode_prodi='$prodi' "
            )
        );
        $besarx = $besar['besar'];

        if (empty($besar)) {
            echo '<div class="alert alert-danger"><b>Mata Kuliah ' .
                $prodi .
                '</b>
				 Kosong 
			</div>';
        } else {
            //echo $besarx;
            for ($i = 0; $i < $besarx; $i++) {
                $smtr = $i + 1;
                $T = $koneksi_db->sql_fetchassoc(
                    $koneksi_db->sql_query(
                        "select COUNT(kode_mk) as total from m_mata_kuliah where kode_prodi='$prodi' and semester='$smtr'"
                    )
                );

                echo '<div class="panel panel-primary">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapse' .
                    $i .
                    '">
        ' .
                    viewprodi($prodi) .
                    ' SEMESTER ' .
                    $smtr .
                    ' <span class="badge pull-right">' .
                    $T['total'] .
                    ' Makul</span>
		</a>
      </h4>
    </div>';

                echo '<div id="collapse' .
                    $i .
                    '" class="panel-collapse collapse">
        	<div class="panel-body">
            <div class="table-responsive">
                <table   class="table table-striped table-bordered table-hover" >
					<tr>
						<th width="10" rowspan="2" valign="middle" align="center">ID MK</th>
						<th width="100" rowspan="2" valign="middle">KODE MK</th>
						<th width="350" rowspan="2" valign="middle">MATAKULIAH</th>
						
						<th colspan="4"align="center" >SKS</th>
						<th width="20" rowspan="2" >&nbsp;</th>
					</tr>
					<tr>
						<th width="20" align="center">SKS</th>
						<th width="20" align="center" >T</th>
						<th width="20" align="center">P</th>
						<th width="20" align="center" >L</th>
					</tr>';
                $s = "select  * from m_mata_kuliah
								where kode_prodi='$prodi' and semester='$smtr'
								order by semester asc";
                $r = $koneksi_db->sql_query($s);
                $st = "select sum(sks_mk) as ttl from m_mata_kuliah  where kode_prodi='$prodi' and semester='$smtr'";
                $s_sks = $koneksi_db->sql_fetchrow($koneksi_db->sql_query($st));
                while ($k = $koneksi_db->sql_fetchassoc($r)) {
                    $id = $k['id'];
                    $tsks = $tsks + $k['sks_mk'];

                    $pengampu = [];
                    $pengampu = explode('|', $k[idd]);

                    echo '<tr>
                                <td valign="top"align="center">' .
                        $k['id'] .
                        '</td>
                                <td valign="top">' .
                        $k['kode_mk'] .
                        '</td>
                                <td valign="top ">' .
                        $k['nama_mk'] .
                        '</td>
                                ';

                    echo '
								<td valign="top" align="center">' .
                        $k['sks_mk'] .
                        '</td>
								<td valign="top" align="center">' .
                        $k['sks_teori'] .
                        '</td>
								<td valign="top" align="center">' .
                        $k['sks_praktek'] .
                        '</td>
								<td valign="top" align="center">' .
                        $k['sks_lapangan'] .
                        '</td>
								<td valign="top" align="center"><a href="index.php?m=' .
                        $_GET['m'] .
                        '&op=editMakul&md=0&id=' .
                        $id .
                        '" >Edit</a></td>
                            </tr>';
                }
                echo '</table>
				</div>
                <div class="panel-footer">
						Total SKS
                       SEMESTER ' .
                    $smtr .
                    ' : <span class="badge">' .
                    $s_sks['ttl'] .
                    ' SKS</span>, 
					   TOTAL SELURUH : <span class="badge">' .
                    $tsks .
                    ' SKS</span>                   
					</div>
					</div>
             </div>';
                echo '</div>';
            }
        }

        ////////////FormImport();
        ?>&nbsp<?php
    }
    FormImport();?>&nbsp<?php
}
/*
function pengampu($mk) {
global $koneksi_db, $pr,$tahun_id;
$prodi = $_SESSION['prodi'];

$peng .= '<table width="100%"  border="0" class="">
<tr><th>Kelas</th><th>Dosen</th></tr>
';
 
		$qpp = $koneksi_db->sql_query( "SELECT * FROM `m_kelas` 
		where kode_prodi='$prodi' " );
		$jumlah=$koneksi_db->sql_numrows($qpp);
		if ($jumlah > 0){
			while($wf = $koneksi_db->sql_fetchrow($qpp)){
			$id=$wf[0];
			$w = $koneksi_db->sql_fetchrow($koneksi_db->sql_query( "SELECT * FROM t_dosen_pengajaran where kelas='$id' and kode_mk='$mk' and tahun_id='$tahun_id' limit 1 " ));
			$peng .='<tr><td >'.$wf[kelas].'</td>
			<td >
			<select  name="pengampu['.$id.']" >'.opdosen($w[idd]).'</select>
			</td>
			</tr>';
			}
		
				} else {
		$peng .= '<tr > <td  colspan="2" align=center>Belum ada Kelas</td></tr>';
		}
$peng .= '</table>';

return $peng;
}
*/

function FormImport()
{
    echo ' <br />
    <h3>Import Data Matakuliah</h3>
	
<div >
<a href="files/format_matakuliah.xls" class="btn btn-danger">Download Format</a>
</div>
<div class="col-md-4">
	
<form action="" method="post" id="form_input" enctype="multipart/form-data">
<input type="hidden" name="m" value="mahasiswa" />
<input type="hidden" name="op" value="Import"/>
<div class="form-group">
	<input type="file" name="fileimport" required  class="form-control">
	<button type="submit" class="btn btn-default">Proses Import Data</button>
</div>
				
</form>
</div>
<div class="col-md-8">	      
<div class="alert alert-success">				
File yang diimport harus berekstensi .xls.<br /><br/>
 
 </div></div>';
}

function editMakul()
{
    global $koneksi_db;
    $prodi = $_SESSION['prodi'];
    $pr = $koneksi_db->sql_fetchassoc(
        $koneksi_db->sql_query(
            "SELECT kode_prodi, nama_prodi FROM m_program_studi where kode_prodi='$prodi' limit 1 "
        )
    );

    if (empty($prodi) || !isset($prodi)) {
        echo "<meta http-equiv='refresh' content='0; url=index.php?m=" .
            $_GET['m'] .
            "'>";
    }
    $md = $_REQUEST['md'] + 0;
    if ($md == 0) {
        $id = $_REQUEST['id'];
        $w = $koneksi_db->sql_fetchassoc(
            $koneksi_db->sql_query(
                "SELECT * FROM m_mata_kuliah where id='$id' limit 1 "
            )
        );
        $jdl = 'Edit Data Mata Kuliah';
    } else {
        $w = [];
        $jdl = 'Tambah Mata Kuliah';
    }

    //$aktif = ($w['buka'] == 'Y') ? 'checked' : '';

    echo '  
    <form action="" method="post"  class="cmxform" id="form_input" style="width:100%">
		<input type="hidden" name="id" value="' .
        $id .
        '"/>
         <input type="hidden" name="m" value="' .
        $_GET['m'] .
        '"/>
        <input type="hidden" name="op" value="simpanMakul"/>
		<input type="hidden" name="md" value="' .
        $md .
        '"/>
		<input type="hidden" name="kode_prodi" value="' .
        $pr['kode_prodi'] .
        '"/>
	   
		<fieldset class="ui-widget ui-widget-content ui-corner-all" >
            <legend class="ui-widget ui-widget-header ui-corner-all">' .
        $jdl .
        ' Prodi (' .
        $pr['kode_prodi'] .
        ')' .
        $pr['nama_prodi'] .
        '</legend>
            &nbsp;<font color="red"><br></font>
            <table width="100%"  border="0" class="datatable full">
                <tr>
                    <td align="right" valign="top">Kode Mata Kuliah<font color="red"> *</font></td>
                    <td>
					<input name="kode_mk"  type="text" class="required" id="" value="' .
        $w['kode_mk'] .
        '" />
					</td>
                </tr>
                <tr>
                    <td align="right" valign="top">Nama Mata Kuliah<font color="red"> *</font></td>
                    <td>
					<input name="nama_mk"  type="text" class="required full" id="" value="' .
        $w['nama_mk'] .
        '" />
					</td>
                </tr>
                <tr>
                    <td align="right" valign="top">SKS Teori<font color="red"> *</font></td>
                    <td>
					<input name="sks_teori"  type="text" class="required number" id="" value="' .
        $w['sks_teori'] .
        '" />
					</td>
                </tr>
                <tr>
                    <td align="right" valign="top">SKS Praktek<font color="red"> *</font></td>
                    <td>
					<input name="sks_praktek"  type="text" class="required number" id="" value="' .
        $w['sks_praktek'] .
        '" />
					</td>
                </tr>
                <tr>
                    <td align="right" valign="top">SKS Lapangn<font color="red"> *</font></td>
                    <td>
					<input name="sks_lapangan"  type="text" class="required number" id="" value="' .
        $w['sks_lapangan'] .
        '" />
					</td>
                </tr>
				
                <tr>
                    <td align="right"valign="top">Semester<font color="red"> *</font></td>
                    <td>
					<select name="semester"  class="required number"  />' .
        opsmtrmk('' . $w['semester'] . '') .
        '</select>
					</td>
                </tr>
                		
                <tr>
                    <td align="right"valign="top">Jenis Mata Kuliah<font color="red"> *</font></td>
                    <td>
					<select name="kode_jenis"  class="required "  />' .
        opAplikasi('28', '' . $w['kode_jenis'] . '') .
        '</select>
					</td>
                </tr>							
                <tr>
                    <td align="right"valign="top">Status Mata Kuliah<font color="red"> *</font></td>
                    <td>
					<select name="kode_status"  class="required "  />' .
        opAplikasi('14', '' . $w['kode_status'] . '') .
        '</select>
					</td>
                </tr>				
             
                <tr>
                    <td align="right"valign="top">Ada Silabus<font color="red"> *</font></td>
                    <td>
					<select name="silabus"  class="required "  />' .
        opYN('' . $w['silabus'] . '') .
        '</select>
					</td>
                </tr>	
                <tr>
                    <td align="right"valign="top">Satuan Acara Perkuliahan<font color="red"> *</font></td>
                    <td>
					<select name="satuan_acara_perkuliahan"  class="required "  />' .
        opYN('' . $w['satuan_acara_perkuliahan'] . '') .
        '</select>
					</td>
                </tr>								
                <tr>
                    <td align="right"valign="top">Ada Bahan Ajar<font color="red"> *</font></td>
                    <td>
					<select name="bahan_ajar"  class="required "  />' .
        opYN('' . $w['bahan_ajar'] . '') .
        '</select>
					</td>
                </tr>								
                <tr>
                    <td align="right"valign="top">Diktat<font color="red"> *</font></td>
                    <td>
					<select name="diktat"  class="required "  />' .
        opYN('' . $w['diktat'] . '') .
        '</select>
					</td>
                </tr>								
											
                 <tr>
                    <td colspan="2">
                        <input type="submit" class=tombols ui-corner-all value="Simpan"/> 
                        <input type="button" class=tombols ui-corner-all value="Kembali" onClick="window.location = \'index.php?m=' .
        $_GET['m'] .
        '\'"/></td>
                  </tr>
            </table>
        </fieldset>
    </form>
 ';

    echo '<script type="text/javascript">
$(document).ready(function() {
 $("#form1").validate();
})

</script>';
}

function Import()
{
    require 'system/excel_reader2.php';
    global $koneksi_db, $prodi, $tahun_id;
    $prodi = $_SESSION['prodi'];
    $_tahun = substr($tahun_id, 0, 4) + 0;
    //echo $prodi, "TEST";
    $w = $koneksi_db->sql_fetchassoc(
        $koneksi_db->sql_query(
            "SELECT * FROM m_program_studi where kode_prodi='" .
                $prodi .
                "' limit 1 "
        )
    );
    $kodept = $w['kode_pt'];
    $kodefak = $w['kode_fak'];
    $kodejen = $w['kode_jenjang'];

    //$filex_namex = strip(strtolower($_FILES['filesiswa']['tmp_name']));

    // membaca file excel yang diupload
    $data = new Spreadsheet_Excel_Reader($_FILES['fileimport']['tmp_name']);

    // membaca jumlah baris dari data excel
    $baris = $data->rowcount($sheet_index = 0);

    // nilai awal counter untuk jumlah data yang sukses dan yang gagal diimport
    $sukses_import = 0;
    $sukses_update = 0;
    $gagal = 0;

    // import data excel mulai baris ke-2
    for ($i = 2; $i <= $baris; $i++) {
        //$idm = kdauto('m_mahasiswa','M');

        // membaca data nidn (kolom ke-1 dan seterusnya)
        // kolom identitas mahasiswa
        $kode_prd = $data->val($i, 1);
        $jenjang = $data->val($i, 2);
        $kode_mk = $data->val($i, 3);
        $nama_mk = $data->val($i, 4);
        $sks_mk = $data->val($i, 5);
        $sks_teori = $data->val($i, 6);
        $sks_praktek = $data->val($i, 7);
        $sks_lapangan = $data->val($i, 8);
        $semester = $data->val($i, 9);
        $kode_jenis = $data->val($i, 10);
        $kode_status = $data->val($i, 11);
        $silabus = $data->val($i, 12);
        $satuan_ap = $data->val($i, 13);
        $bahan_ajar = $data->val($i, 14);
        $diktat = $data->val($i, 15);

        //insert data
        $qada = "select * from  m_mata_kuliah where nama_mk='$nama_mk'";
        $qryu = $koneksi_db->sql_query($qada);
        if ($koneksi_db->sql_numrows($qryu) > 0) {
            //	$key=$qryu['idkrs'];
            /*
				$su = "update m_mahasiswa SET 
						kode_pt='".$kodept."',
						kode_fak='".$kodefak."',
						kode_jurusan='".$kodejurusan."',
						kode_prodi='".$prodi."',
						kode_jenjang='".$kodejen."',
						nama_mahasiswa='".$nama_mahasiswa."',
						agama='".$agama."',
						jenis_kelamin='".$jenis_kelamin."',
						tempat_lahir='".$tempat_lahir."',
						tanggal_lahir='".$tanggal_lahir."',
						telepon='".$telepon."',
						hp='".$hp."',
						email='".$email."',
						status_masuk='".$status_masuk."'
						WHERE NIM = '".$NIM."'
					";
				$hasil_update =  $koneksi_db->sql_query($su);
				*/
            //	$sukses_update++;
            //$nama_update .= "<li>".$nama_mk."</li>";
        } else {
            $si =
                "insert into m_mata_kuliah SET 
						kode_pt='" .
                $kodept .
                "',
						kode_fak='" .
                $kodefak .
                "',
						kode_prodi='" .
                $kode_prd .
                "',
						kode_jenjang='" .
                $jenjang .
                "',
						kode_mk='" .
                $kode_mk .
                "',
						nama_mk='" .
                $nama_mk .
                "',
						sks_mk='" .
                $sks_mk .
                "',
						sks_teori='" .
                $sks_teori .
                "',
						sks_praktek='" .
                $sks_praktek .
                "',
						sks_lapangan='" .
                $sks_lapangan .
                "',
						semester='" .
                $semester .
                "',
						kode_jenis='" .
                $kode_jenis .
                "',
						kode_status='" .
                $kode_status .
                "',
						silabus='" .
                $silabus .
                "',
						satuan_acara_perkuliahan='" .
                $satuan_ap .
                "',
						bahan_ajar='" .
                $bahan_ajar .
                "',
						diktat='" .
                $diktat .
                "'
					";

            $hasil_import = $koneksi_db->sql_query($si);
            $nama_hasil .= '<li>' . $nama_mk . '</li>';
        }

        if ($hasil_import) {
            $sukses_import++;
        } else {
            $gagal++;
        }
    }

    echo "<fieldset class=cari>
	<legend> Proses import data </legend>
<table width=400 border=0>
	<tr><td align=right>Mata Kuliah Berhasil Di Import</td><td width=100> " .
        $sukses_import .
        "</td></tr>
	<tr><td align=right></td><td width=100><ul> " .
        $nama_hasil .
        "</ul></td></tr>
	<tr><td align=right>Jumlah data yang gagal diimport </td><td>: " .
        $gagal .
        "</td></tr>
</table>
</fieldset>	<br/>
<input id=kembali type=button class=button-red class=ui-button tombols ui-corner-all value=Kembali ke daftar Dosen style=\"font-size: 11px;height: inherit;\"  onclick=\"window.location='index.php?m=" .
        $_GET['m'] .
        "'\">
	";
}
////simpan /
function simpanMakul()
{
    global $koneksi_db, $tahun_id;
    $md = $_REQUEST['md'] + 0;
    $id = $_REQUEST['id'];

    $besar = $koneksi_db->sql_fetchassoc(
        $koneksi_db->sql_query('select max(id) as besar from m_mata_kuliah ')
    );
    $besarx = $besar['besar'] + 1;

    $kd_mk = $md == 0 ? $id : $besarx;

    if (trim($_POST['kode_prodi']) == '') {
        $pesan[] = 'Kode Prodi kosong, ulangi kembali';
    } elseif (trim($_POST['kode_mk']) == '') {
        $pesan[] = 'Form Kode Mata Kuliah  masih kosong, ulangi kembali';
    } elseif (trim($_POST['nama_mk']) == '') {
        $pesan[] = 'Form Nama Mata Kuliah  masih kosong, ulangi kembali';
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
        echo "<meta http-equiv='refresh' content='3; url=index.php?m=" .
            $_GET['m'] .
            "&op=editMakul&md=1'>";
    } else {
        $w = $koneksi_db->sql_fetchassoc(
            $koneksi_db->sql_query(
                "SELECT * FROM m_program_studi where kode_prodi='" .
                    $_REQUEST['kode_prodi'] .
                    "' limit 1 "
            )
        );
        $pt = $w['kode_pt'];
        $fak = $w['kode_fak'];
        $jenjang = $w['kode_jenjang'];

        //$d = $koneksi_db->sql_fetchrow($koneksi_db->sql_query( "SELECT * FROM t_dosen where idd='".$_REQUEST['NIDN']."' limit 1 " ));
        //$d_prodi = $d['kode_prodi'];
        //$d_jenjang = $d['kode_jenjang'];
        $sks_mk =
            $_REQUEST['sks_teori'] +
            $_REQUEST['sks_praktek'] +
            $_REQUEST['sks_lapangan'];

        if (!empty($_POST['pengampu'])) {
            $stridd = implode('|', $_POST['pengampu']);
        }

        if ($md == 0) {
            $s =
                "update m_mata_kuliah set 
					kode_mk='" .
                $_REQUEST['kode_mk'] .
                "',
					kode_pt='" .
                $pt .
                "',
					kode_fak='" .
                $fak .
                "',
					kode_prodi='" .
                $_REQUEST['kode_prodi'] .
                "',
					kode_jenjang='" .
                $jenjang .
                "',
					nama_mk='" .
                $_REQUEST['nama_mk'] .
                "',
					sks_mk='" .
                $sks_mk .
                "',
					sks_teori='" .
                $_REQUEST['sks_teori'] .
                "',
					sks_praktek='" .
                $_REQUEST['sks_praktek'] .
                "',
					sks_lapangan='" .
                $_REQUEST['sks_lapangan'] .
                "',
					semester='" .
                $_REQUEST['semester'] .
                "',
					kode_jenis='" .
                $_REQUEST['kode_jenis'] .
                "',
					kode_status='" .
                $_REQUEST['kode_status'] .
                "',
					satuan_acara_perkuliahan='" .
                $_REQUEST['satuan_acara_perkuliahan'] .
                "',
					bahan_ajar='" .
                $_REQUEST['bahan_ajar'] .
                "',
					diktat='" .
                $_REQUEST['diktat'] .
                "'
					where id='" .
                $_REQUEST['id'] .
                "'  ";
            $koneksi_db->sql_query($s);
        } else {
            $qf = $koneksi_db->sql_query(
                "SELECT * FROM m_mata_kuliah where kode_mk ='" .
                    $_REQUEST['kode_mk'] .
                    "' and kode_prodi='" .
                    $_REQUEST['kode_prodi'] .
                    "' limit 1 "
            );
            $totalf = $koneksi_db->sql_numrows($qf);
            if ($totalf > 0) {
                echo '<div class=error>Kode Mata Kuliah ' .
                    $_REQUEST['kode_mk'] .
                    ' di ' .
                    $_REQUEST['kode_prodi'] .
                    ' sudah ada</div>';
            } else {
                $sa =
                    "INSERT INTO m_mata_kuliah set 
					kode_mk='" .
                    $_REQUEST['kode_mk'] .
                    "',
					kode_pt='" .
                    $pt .
                    "',
					kode_fak='" .
                    $fak .
                    "',
					kode_prodi='" .
                    $_REQUEST['kode_prodi'] .
                    "',
					kode_jenjang='" .
                    $jenjang .
                    "',
					nama_mk='" .
                    $_REQUEST['nama_mk'] .
                    "',
					sks_mk='" .
                    $sks_mk .
                    "',
					sks_teori='" .
                    $_REQUEST['sks_teori'] .
                    "',
					sks_praktek='" .
                    $_REQUEST['sks_praktek'] .
                    "',
					sks_lapangan='" .
                    $_REQUEST['sks_lapangan'] .
                    "',
					semester='" .
                    $_REQUEST['semester'] .
                    "',
					kode_jenis='" .
                    $_REQUEST['kode_jenis'] .
                    "',
					kode_status='" .
                    $_REQUEST['kode_status'] .
                    "',
					silabus='" .
                    $_REQUEST['silabus'] .
                    "',
					satuan_acara_perkuliahan='" .
                    $_REQUEST['satuan_acara_perkuliahan'] .
                    "',
					bahan_ajar='" .
                    $_REQUEST['bahan_ajar'] .
                    "',
					diktat='" .
                    $_REQUEST['diktat'] .
                    "'
					";
                $koneksi_db->sql_query($sa);
            }
        }
    }
    Matakuliah();
}

function Jenis()
{
    global $koneksi_db;
    $prodi = $_SESSION['prodi'];
    $id = $_REQUEST['id'];

    if (!empty($id) && isset($id)) {
        $wp = $koneksi_db->sql_fetchassoc(
            $koneksi_db->sql_query(
                "SELECT * FROM r_kode where id='$id' and aplikasi='28' limit 1 "
            )
        );
        $sembunyi = 'style="display:block;"';
        $judul = 'Edit Jenis Matakuliah';
        $pilihprodi = $wp['kode_prodi'];
    } else {
        $sembunyi = 'style="display:none;"';
        $judul = 'Tambah Jenis Matakuliah';
        $pilihprodi = $prodi;
    }
    echo "<input type=button  class=\"tombols ui-corner-all\" value='Tambah Jenis Matakuliah' onclick=\"return toggleView('form-hide')\" >";
    echo '<div id="form-hide" ' . $sembunyi . '>';
    echo '<form action="" method="post"  class="cmxform" id="form_input" style="width:60%">
        <input type="hidden" name="m" value="' .
        $_GET['m'] .
        '"/>
        <input type="hidden" name="op" value="simpanJenis"/>
		<input type="hidden" name="id" value="' .
        $id .
        '"/> 
        <fieldset class="ui-widget ui-widget-content ui-corner-all" >
            <legend class="ui-widget ui-widget-header ui-corner-all">' .
        $judul .
        '</legend>
            &nbsp;<font color="red"><br></font>
            <table width="600"  border="0" class="datatable full1">		
				
                <tr>
                    <td   align="right" valign="top">Kode<font color="red"> *</font></td>
                    <td  >	<input name="kode"  type="text" class=" required" id="" value="' .
        $wp['kode'] .
        '" />	</td>
                </tr>
                <tr>
                    <td  align="right" valign="top">Nama Jenis Kurikulum<font color="red"> *</font></td>
                    <td  >	<input name="parameter"  type="text" class=" required full" id="" value="' .
        $wp['parameter'] .
        '" />	</td>
                </tr>				             
                 <tr>
                    <td colspan="2">
                        <input type="submit" class="tombols ui-corner-all" value="Simpan"/> 
                        <input type="button" class="tombols ui-corner-all" value="Kembali" onClick="window.location = \'index.php?m=' .
        $_GET['m'] .
        '&op=Jenis\'"/></td>
                  </tr>					
            </table>
        </fieldset></form> ';
    echo '</div>';

    echo '<table class="table table-striped table-bordered table-hover"  >
   	<thead>
     <tr>
	   <th width="5%" align="center">No</th>
	    <th  align="center">Kode</th>
	    <th  align="center">Jenis Matakuliah</th>
	   <th width="30%" align="center"></th>
     </tr>
	 </thead>
	 <tbody>';

    $qpp = $koneksi_db->sql_query("SELECT * FROM `r_kode` 
		where aplikasi='28' ");
    $jumlah = $koneksi_db->sql_numrows($qpp);
    if ($jumlah > 0) {
        while ($wf = $koneksi_db->sql_fetchassoc($qpp)) {
            $n++;
            echo "<tr>
			  	<td c>$n.</td>
				<td >$wf[kode]</td>
				<td >$wf[parameter]</td>
				<td >
					<a href=# class=btn onclick=window.location.href='index.php?m=" .
                $_GET['m'] .
                "&op=Jenis&id=$wf[id]'>
					<i class='fa fa-folder'></i></a>
					<a href=# class=btn onclick=window.location.href='index.php?m=" .
                $_GET['m'] .
                "&op=Jenis&id=$wf[id]'>
					<i class='fa fa-edit'></i></a>
				</td>
				</tr>";
        }
    } else {
        echo '<tr > <th  colspan="4" align=center>Belum ada Data</th></tr>';
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
function simpanJenis()
{
    global $koneksi_db;
    $id = $_REQUEST['id'];

    if (trim($_POST['kode']) == '') {
        $pesan[] = 'Form kode Jenis Matakuliah masih kosong, ulangi kembali';
    } elseif (trim($_POST['parameter']) == '') {
        $pesan[] = 'Form Nama Jenis Matakuliah masih kosong, ulangi kembali';
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
            "&op=Jenis'>";
    } else {
        if (!empty($id)) {
            $s =
                "update r_kode set 	
					kode='" .
                strtoupper($_REQUEST['kode']) .
                "',
					parameter='" .
                strtoupper($_REQUEST['parameter']) .
                "'
					where id='" .
                $_REQUEST['id'] .
                "' ";
            $koneksi_db->sql_query($s);
        } else {
            $s =
                "INSERT INTO r_kode set 
			  		aplikasi='28',
					keterangan='JENIS MATAKULIAH',
			  		kode='" .
                strtoupper($_REQUEST['kode']) .
                "',
					parameter='" .
                strtoupper($_REQUEST['parameter']) .
                "'
					";
            $koneksi_db->sql_query($s);
        }
    }
    echo "<meta http-equiv='refresh' content='2; url=index.php?m=" .
        $_GET['m'] .
        "&op=Jenis'>";
    //echo $s;
    Jenis();
}

/* 
function Setara() {
global $koneksi_db;
	$prodi = $_SESSION['prodi'];
	
	$qf = $koneksi_db->sql_query( "SELECT * FROM m_program_studi " );
	$totalf = $koneksi_db->sql_numrows($qf);
	if ($totalf < 1) { 
	echo '<div class=error>Data Program Studi Masih Kosong, Klik <a href="index.php?m=31&op=edit_prodi&md=1">Add Program Studi</a></div>'; 
	} else {
	
	if (isset($_POST['update']) ) {
		if (is_array($_POST['mk_setara'])) {
			foreach($_POST['mk_setara'] as $key=>$val) {
				//$aktif = $_POST['aktif'][$key];
				//$level = $_POST['level'][$key];
				//$levelx = implode('.',$level);
				$update = "UPDATE `m_mata_kuliah` SET `mk_setara` = '$val' WHERE `id` = '$key'";
				$simpan = mysql_query($update );
				//echo $update.'<br/>';
			}
		}
	}
				
/////////////////
	$besar = $koneksi_db->sql_fetchrow($koneksi_db->sql_query("select max(semester) as besar from m_mata_kuliah where kode_prodi='$prodi' " ));
		$besarx = $besar['besar'];
		
		if ( empty($besar) ) {
		
        echo ' 
		<div class="pelajaran-top" ">
				<div class="kiri">Mata Kuliah '.$prodi.'</div>
				<div class="kanan">Kosong</div>
			</div>';
		} else {
		//echo $besarx;
		for ($i=0; $i<$besarx; $i++) {
		$smtr = $i+1 ;
    	$T = $koneksi_db->sql_fetchrow($koneksi_db->sql_query("select COUNT(kode_mk) as total from m_mata_kuliah where kode_prodi='$prodi' and semester='$smtr'" ));
		echo '<form action="" method=POST>
            <div class="pelajaran-top" ibu="'.$i.'">
				<div class="kiri">'.viewprodi($prodi).' SEMESTER '.$smtr.' </div>
				<div class="kanan">'.$T['total'].' Makul</div>
			</div>
            <div style="clear: both"></div>
            <div style="display: none" anak="'.$i.'">
                <table width="650" class="sk" border="1">
					<tr>
						<th width="100"  valign="middle">Kode</th>
						<th width="200"  valign="middle">Mata Kuliah</th>
						<th >Mata kulah Setara</th>
					</tr>';
						$s = "select  * from m_mata_kuliah
								where kode_prodi='$prodi' and semester='$smtr'
								order by semester asc";
						$r = $koneksi_db->sql_query($s);
						$st = "select sum(sks_mk) as ttl from m_mata_kuliah  where kode_prodi='$prodi' and semester='$smtr'";
						$s_sks = $koneksi_db->sql_fetchrow($koneksi_db->sql_query($st));		
						while ($k = $koneksi_db->sql_fetchrow($r)) {
						$id = $k['id'];
						$tsks = $tsks + $k['sks_mk'];
						
						$pengampu =array();
						$pengampu = explode("|", $k[idd]);
						
                        echo '<tr>
                                <td valign="top" align="center">'.$k['kode_mk'].'</td>
                                <td valign="top ">'.$k['nama_mk'].'</td>
                                ';
					
						echo '
								<td valign="top" >
								<select name="mk_setara['.$k['id'].']"  />'.opmatakuliah('',$smtr,$k['mk_setara']).'</select>
								</td>
                            </tr>';
						}
                echo '</table>
                <div class="pelajaran-bottom">
					<div class="kiri"><input type="submit" name="update" class=tombols ui-corner-all value="Update"/></div>
					<div class="kanan">
                        <DIV class="pengajar">SEMESTER '.$smtr.' : '.$s_sks['ttl'].' SKS, TOTAL SELURUH : '.$tsks.' SKS </DIV>                    
					</div>
					
					</div>
                <div style="clear: both"></div>
                <br />
            </div></form>';
		}
		}	
////////////
	}
}
*/
$arrSub = [
    'Jenis Mata Kuliah->Jenis',
    'Data Mata Kuliah->Matakuliah',
    //'Mata Kuliah Setara->Setara'
];

$go = empty($_REQUEST['op']) ? 'Matakuliah' : $_REQUEST['op'];
echo '<div id="title" align="right">
        <font style="color:#FF9900; font-size:30px;"><strong>.:</strong></font>
        <font style="font-size:18px; color:#999999">Mata Kuliah</font><br />
        	<a href="index.php">Home</a> &raquo; <a href="?m=' .
    $_GET['m'] .
    '">Kurikulum</a>  &raquo; ' .
    $go .
    '  
    </div>';

echo '<div class="mainContentCell"><div class="content">';
echo '<ul class="nav nav-tabs">';
for ($i = 0; $i < sizeof($arrSub); $i++) {
    $mn = explode('->', $arrSub[$i]);
    $c = $mn[1] == $go ? 'class=active' : '';
    echo "<li $c><a  href='index.php?m=" .
        $_GET['m'] .
        "&op=$mn[1]'><span>$mn[0]</span></a></li>";
}
echo '</ul>';
echo '<div class="clear"></div>';

$go();
echo '</div></div>';
?>