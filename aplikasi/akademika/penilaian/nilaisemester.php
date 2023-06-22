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
    global $koneksi_db, $tahun_id, $user, $prodi;
    $prodi = $_SESSION['prodi'];
    $kode_mk = $_SESSION['kode_mk'];
    $kelas = $_SESSION['kelas'];

    $jTugas = '';

    echo '<div class="row">';
    echo '<div class="col-md-6">';
    if ($_SESSION['Level'] == 'DOSEN') {
        FilterMataKuliahDosen($prodi, $tahun_id, $user, $_GET['m']);
        echo '</div><div class="col-md-6">';
        FilterKelas($prodi, $tahun_id, $user, $_GET['m']);
    } elseif ($_SESSION['Level'] == 'PA') {
        FilterMataKuliahDosen($prodi, $tahun_id, $user, $_GET['m']);
        echo '</div><div class="col-md-6">';
        FilterKelas($prodi, $tahun_id, $user, $_GET['m']);
    } else {
        FilterMataKuliahDosen($prodi, $tahun_id, '', $_GET['m']);
        echo '</div><div class="col-md-6">';
        FilterKelas($prodi, $tahun_id, '', $_GET['m']);
    }
    echo '</div></div >';

    $headerTG = $headerTG == '1' ? "<th  scope='col'></th>" : $headerTG;
    $headerUL = $headerUL == '1' ? "<th  scope='col'></th>" : $headerUL;

    $tombolTG = $tombolTG == '' ? "<th  scope='col'></th>" : $tombolTG;
    $tombolUL = $tombolUL == '' ? "<th  scope='col'></th>" : $tombolUL;

    $col = $jTugas;
    $colMP = $col + 8;

    echo '<a class="btn" href="index.php?m=' .
        $_GET['m'] .
        '&op=input" class="button-red">Tambah Nilai Mahasiswa</a><br/>';

    $ord = 'order by nim';
    $whr[] = "t_mahasiswa_krs.kode_prodi='$prodi'";
    $whr[] = "t_mahasiswa_krs.tahun_id='$tahun_id'";
    $whr[] = "kelas='$kelas'";
    $whr[] = "id='$kode_mk'";
    $whr[] = "verifi_pa='1'";
    //$whr[] = "status_krs='T'";
    //if (!empty($_SESSION['kelas'])) $whr[] = "kelas='$_SESSION[kelas]'";
    if (!empty($whr)) {
        $strwhr = 'where ' . implode(' and ', $whr);
    }

    require 'system/pagination_class.php';
    $sql = "select * from t_mahasiswa_krs inner join m_mahasiswa on t_mahasiswa_krs.idm=m_mahasiswa.idm $strwhr $ord";
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
        echo "
	
	
	<div class='table-responsive'>
		<table  class='table'  >
			  <tr>
				<th scope='col' rowspan='3' valign=middle align=center width=3> No.</th>
				<th scope='col'rowspan='3' valign=middle align=center width=60>NIM</th>
				<th scope='col'rowspan='3' valign=middle align=center width=150>Nama Mahasiswa</th>
				<th scope='col'colspan='" .
            $colMP .
            "' valign=middle align=center>" .
            viewmatakuliah($prodi, $kode_mk) .
            "</th>
			  </tr>
			  <tr>
				<th scope='col'rowspan='2' width=30 valign=middle>Hadir</th>
				<th scope='col'rowspan='2' width=30 valign=middle>Tugas</th>
				<th scope='col'rowspan='2' width=30 valign=middle>UTS</th>
				<th scope='col'rowspan='2' width=30 valign=middle>UAS</th>
				<th scope='col'rowspan='2' width=30 align=center style='background-color:#eee;' valign=middle>Rata</th>
				<th scope='col'rowspan='2' width=30 align=center style=background-color:#FDFECB; valign=middle>Nilai</th>
				<th scope='col'rowspan='2' width=30 align=center style=background-color:#FDFECB; valign=middle>Bobot</th>
				<th scope='col'rowspan='2' width=30 align=center style=background-color:#FDFECB; valign=middle>Lulus</th>
			  </tr>
			  <tr>
			  	$headerUL
			  	$headerTG
			  </tr>	
			  <tbody>		
			";

        while ($k = $koneksi_db->sql_fetchassoc($result)) {
            $no++;

            $w = $koneksi_db->sql_fetchassoc(
                $koneksi_db->sql_query(
                    "SELECT idm, NIM, nama_mahasiswa, jenis_kelamin  FROM m_mahasiswa where idm='" .
                        $k['idm'] .
                        "' "
                )
            );
            $wtugas = $koneksi_db->sql_fetchassoc(
                $koneksi_db->sql_query(
                    "SELECT nilai FROM t_mahasiswa_nilai where tahun_id='$tahun_id' and id='" .
                        $k['id'] .
                        "' and idm='" .
                        $k['idm'] .
                        "' and jenis_nilai='TUGAS' "
                )
            );
            $wuts = $koneksi_db->sql_fetchassoc(
                $koneksi_db->sql_query(
                    "SELECT nilai FROM t_mahasiswa_nilai where tahun_id='$tahun_id' and id='" .
                        $k['id'] .
                        "' and  idm='" .
                        $k['idm'] .
                        "' and jenis_nilai='UTS' "
                )
            );
            $wuas = $koneksi_db->sql_fetchassoc(
                $koneksi_db->sql_query(
                    "SELECT nilai FROM t_mahasiswa_nilai where tahun_id='$tahun_id' and id='" .
                        $k['id'] .
                        "' and  idm='" .
                        $k['idm'] .
                        "' and jenis_nilai='UAS' "
                )
            );
            $whadir = $koneksi_db->sql_fetchassoc(
                $koneksi_db->sql_query(
                    "SELECT nilai FROM t_mahasiswa_nilai where tahun_id='$tahun_id' and id='" .
                        $k['id'] .
                        "' and  idm='" .
                        $k['idm'] .
                        "' and jenis_nilai='HADIR' "
                )
            );

            echo "
				<tr>
				<th scope='row'>$no</th>
				<td >$w[NIM]</td>
				<td >$w[nama_mahasiswa]</td>";
            //// nilai kehadiran
            $presensi_dosen = total_presensi_dosen(
                $k['kode_prodi'],
                $k['tahun_id'],
                $k['kelas'],
                $k['id'],
                'H',
                $k['idd']
            );
            $presensi_mahasiswa = total_presensi_mahasiswa(
                $k['kode_prodi'],
                $k['tahun_id'],
                $k['kelas'],
                $k['id'],
                'H',
                $k['idm']
            );

            //////////////////////////
            echo '<td align=center>' . $whadir['nilai'] . '</td>'; // nilai kehadiran
            echo '<td align=center>' . $wtugas['nilai'] . '</td>'; ///nilai Tugas
            echo '<td align=center>' . $wuts['nilai'] . '</td>'; ///nilai UTS
            echo '<td align=center>' . $wuas['nilai'] . '</td>'; // nilai UAS
            echo '<td align=center>' . $k['jumlah_nilai'] . '</td>';
            echo '<td align=center>' . $k['nilai'] . '</td>';
            echo '<td align=center>' . $k['bobot'] . '</td>';
            echo '<td align=center>' . $k['lulus'] . '</td>';
            echo '</tr>';
        }
        echo '<tr><th colspan= 3></th>';
        echo '<th ></th>';
        echo "<th align=right><a href='index.php?m=" .
            $_GET['m'] .
            "&op=edit&jenis=Tugas&ke=1' class='btn btn-primary' >Ubah</a></th>";
        echo "<th align=right><a href='index.php?m=" .
            $_GET['m'] .
            "&op=edit&jenis=UTS&ke=1' class='btn btn-primary' >Ubah</a></th>";
        echo "<th align=right><a href='index.php?m=" .
            $_GET['m'] .
            "&op=edit&jenis=UAS&ke=1' class='btn btn-primary' >Ubah</a></th>";

        echo '</tbody>
			</table> </div>
		 ';

        echo $obj->total;
        echo '<br/>';
        echo $obj->anchors;
    } else {
        echo '<div class="alert alert-danger" >Belum ada Data</div>';
    }

    echo "  
            <input type=button  class=\"tombols ui-corner-all\" value='Cetak Nilai' onclick=\"bukajendela('cetak.php?m=cetaknilai'); return false\" > 
 ";
    if ($_SESSION['Level'] != 'DOSEN') {
        FormImport(); ?>&nbsp<?php
    }
}

function FormImport()
{
    echo ' <br/> <br/> <br/> <br/>
    <h3>Import Data NILAI</h3>
	
<div >
<a href="files/format_nilai.xls" class="btn btn-danger">Download Format</a>
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
File yang diimport harus berekstensi .xls.<br/> 
<li>Fitur ini Lebih baik digunakan Sekali, terutama bagi kampus yang baru pertama kali install</li>
<li>Fitur ini digunakan untuk import NILAI massal semua jurusan dan mahasiswa</li>
<br/>
 
 </div></div>';
}

function Import()
{
    require 'system/excel_reader2.php';
    global $koneksi_db, $prodi, $tahun_id, $kode_mk;
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
        $id_mhs = $data->val($i, 3);
        $kelas = $data->val($i, 4);
        $id_mk = $data->val($i, 5);
        $jns_nilai = $data->val($i, 6);
        $nilaike = $data->val($i, 7);
        $nilai = $data->val($i, 8);
        $id_krs = $data->val($i, 9);

        //insert data
        $qada = "select * from t_mahasiswa_krs where jenis_nilai='$jns_nilai'  ";
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
            $sukses_update++;
            $nama_update .= '<li>' . $kelas . '</li>';
        } else {
            $si =
                "insert into t_mahasiswa_nilai SET 
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
						tahun_id='" .
                $tahun_id .
                "',
						idm='" .
                $id_mhs .
                "',
						kelas='" .
                $kelas .
                "',
						jenis_nilai='" .
                $jns_nilai .
                "',
						nilai_ke='" .
                $nilaike .
                "',
						nilai='" .
                $nilai .
                "',
						id='" .
                $id_mk .
                "'
					";

            $hasil_import = $koneksi_db->sql_query($si);
            $nama_hasil .= '<li>' . $kelas . '</li>';
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

function input()
{
    global $koneksi_db, $tahun_id, $user;
    $prodi = $_SESSION['prodi'];
    $kode_mk = $_SESSION['kode_mk'];
    $kelas = $_SESSION['kelas'];

    $level = $koneksi_db->sql_fetchassoc(
        $koneksi_db->sql_query("SELECT level FROM user where userid='$user' ")
    );

    if ($level['level'] != 'ADMIN') {
        if (CekBatasNilaiOnline()) {
            if (!empty($prodi) && !empty($kode_mk) && !empty($kelas)) {
                echo '<div class="panel-body">
		 <form action="" method="post"  class="cmxform" id="form_input" name="form_input" style="width:100%">
				<input type="hidden" name="id" value="' .
                    $id .
                    '"/>
				<input type="hidden" name="m" value="' .
                    $_GET['m'] .
                    '"/>
				<input type="hidden" name="op" value="Simpan"/>
			 <div class="table-responsive">
			<table class=box cellspacing=1 cellpadding=4  >
			  <tr>
				  <td  >Jenis Nilai</td>
				  <td  > 
					<div><select  class="required" name="jenis_nilai" >' .
                    opJenisNilai($jenis_nilai) .
                    '</select></div>
				</td>
				</tr>
			</table>
			</div>
			<br/>
			 <div class="table-responsive">
		<table class="table table-striped table-bordered table-hover"  >
			<thead>
			 <tr>
			   <th width="5%" align="center">No.</th>
			   <th width="150" align="center">NIM</th>
			   <th align="center">Nama Mahasiswa</th>
			   <th align="center">Kelas</th>
			   <th align="center" width="100">Nilai</th>
			 </tr>
			 </thead>
			 <tbody>';

                $whr[] = "t_mahasiswa_krs.kode_prodi='$prodi'";
                $whr[] = "t_mahasiswa_krs.tahun_id='$tahun_id'";
                $whr[] = "id='$kode_mk'";
                $whr[] = "status_krs='T'";
                $whr[] = "verifi_pa='1'";
                $whr[] = "kelas='$kelas'";

                if (!empty($whr)) {
                    $strwhr = 'where ' . implode(' and ', $whr);
                }

                $q = "select  * from t_mahasiswa_krs inner join m_mahasiswa on t_mahasiswa_krs.idm=m_mahasiswa.idm $strwhr order by nim";
                $pilih = $koneksi_db->sql_query($q);
                $jumlah = $koneksi_db->sql_numrows($pilih);

                if ($jumlah > 0) {
                    while ($k = $koneksi_db->sql_fetchassoc($pilih)) {
                        $no++;
                        $w = $koneksi_db->sql_fetchassoc(
                            $koneksi_db->sql_query(
                                "SELECT idm, NIM, nama_mahasiswa FROM m_mahasiswa where idm='" .
                                    $k['idm'] .
                                    "' "
                            )
                        );
                        $id = $k['idkrs'];
                        echo '<tr >
							<td  align=center>' .
                            $no .
                            '</td> 
							<td valign="top" align="center">' .
                            $w['NIM'] .
                            '</td>
							<td valign="top ">' .
                            $w['nama_mahasiswa'] .
                            '</td>
							<td valign="top align=center">' .
                            viewkelas($k['kelas']) .
                            '</td>
							<td valign="top" align=center>
								<input name="nilai[' .
                            $id .
                            ']"  type="text" class="number kecil" id="" value="" />
							
							</td>
						</tr>';
                    }
                    echo ' <thead> 	<tr ><th  colspan="5" align=right>
					<input type="submit" name="simpan" class=tombols ui-corner-all value="Input Nilai"/>
					<input type="button" class=tombols ui-corner-all value="Kembali" onClick="window.location = \'index.php?m=' .
                        $_GET['m'] .
                        '\'"/>
					</th>	</tr></thead>';
                } else {
                    echo ' <thead> 	<tr ><th  colspan="5" align=center>Belum ada data</th>	</tr></thead>';
                }

                echo '</tbody>
				</table></div></form></div>';
            } else {
                echo "<div  class='error'>Program studi, Mata Kuliah dan Kelas belum dipilih</div>";

                echo '<input type="button" class=tombols ui-corner-all value="Kembali" onClick="window.location = \'index.php?m=' .
                    $_GET['m'] .
                    '\'"/>';
            }
        }
    } else {
        if (!empty($prodi) && !empty($kode_mk) && !empty($kelas)) {
            echo ' <div class="panel-body">
		 <form action="" method="post"  class="cmxform" id="form_input" name="form_input" style="width:100%">
				<input type="hidden" name="id" value="' .
                $id .
                '"/>
				<input type="hidden" name="m" value="' .
                $_GET['m'] .
                '"/>
				<input type="hidden" name="op" value="Simpan"/>
		 <div class="table-responsive">
			<table class=box cellspacing=1 cellpadding=4  >
			  <tr>
				  <td  >Jenis Nilai</td>
				  <td  > 
					<select  class="required" name="jenis_nilai" >' .
                opJenisNilai($jenis_nilai) .
                '</select>
				</td>
				</tr>
			</table>
			</div>
			<br/>
			<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover"  >
			<thead>
			 <tr>
			   <th width="5%" align="center">No.</th>
			   <th width="150" align="center">NIM</th>
			   <th align="center">Nama Mahasiswa</th>
			   <th align="center">Kelas</th>
			   <th align="center" width="100">Nilai</th>
			 </tr>
			 </thead>
			 <tbody>';

            $whr[] = "t_mahasiswa_krs.kode_prodi='$prodi'";
            $whr[] = "t_mahasiswa_krs.tahun_id='$tahun_id'";
            $whr[] = "id='$kode_mk'";
            $whr[] = "status_krs='T'";
            $whr[] = "verifi_pa='1'";
            $whr[] = "kelas='$kelas'";

            if (!empty($whr)) {
                $strwhr = 'where ' . implode(' and ', $whr);
            }

            $q = "select  * from t_mahasiswa_krs inner join m_mahasiswa on t_mahasiswa_krs.idm=m_mahasiswa.idm $strwhr order by nim";
            $pilih = $koneksi_db->sql_query($q);
            $jumlah = $koneksi_db->sql_numrows($pilih);

            if ($jumlah > 0) {
                while ($k = $koneksi_db->sql_fetchassoc($pilih)) {
                    $no++;
                    $w = $koneksi_db->sql_fetchassoc(
                        $koneksi_db->sql_query(
                            "SELECT idm, NIM, nama_mahasiswa FROM m_mahasiswa where idm='" .
                                $k['idm'] .
                                "' "
                        )
                    );
                    $id = $k['idkrs'];
                    echo '<tr >
							<td  align=center>' .
                        $no .
                        '</td> 
							<td valign="top" align="center">' .
                        $w['NIM'] .
                        '</td>
							<td valign="top ">' .
                        $w['nama_mahasiswa'] .
                        '</td>
							<td valign="top align=center">' .
                        viewkelas($k['kelas']) .
                        '</td>
							<td valign="top" align=center>
								<input name="nilai[' .
                        $id .
                        ']"  type="text" class="number kecil" id="" value="" />
							</td>
						</tr>';
                }
                echo ' <thead> 	<tr ><th  colspan="5" align=right>
					<input type="submit" name="simpan" class=tombols ui-corner-all value="Input Nilai"/>
					<input type="button" class=tombols ui-corner-all value="Kembali" onClick="window.location = \'index.php?m=' .
                    $_GET['m'] .
                    '\'"/>
					</th>	</tr></thead>';
            } else {
                echo ' <thead> 	<tr ><th  colspan="5" align=center>Belum ada data</th>	</tr></thead>';
            }

            echo '</tbody>
				</table></div></form></div>';
        } else {
            echo "<div  class='error'>Program studi, Mata Kuliah dan Kelas belum dipilih</div>";

            echo '<input type="button" class=tombols ui-corner-all value="Kembali" onClick="window.location = \'index.php?m=' .
                $_GET['m'] .
                '\'"/>';
        }
    }
}

function edit()
{
    global $koneksi_db, $tahun_id, $user;
    $prodi = $_SESSION['prodi'];
    $kode_mk = $_SESSION['kode_mk'];
    $kelas = $_SESSION['kelas'];
    $jenis = $_GET['jenis'];
    $ke = $_GET['ke'];

    $level = $koneksi_db->sql_fetchassoc(
        $koneksi_db->sql_query("SELECT level FROM user where userid='$user' ")
    );

    if ($level['level'] != 'ADMIN') {
        if (CekBatasNilaiOnline()) {
            if (!empty($prodi) && !empty($kode_mk) && !empty($kelas)) {
                echo ' <div class="table-responsive">
		 <form action="" method="post"  class="cmxform" id="form_input" name="form_input" style="width:100%">
				<input type="hidden" name="id" value="' .
                    $id .
                    '"/>
				<input type="hidden" name="jenis_nilai" value="' .
                    $jenis .
                    '"/>
				<input type="hidden" name="ke" value="' .
                    $ke .
                    '"/>
				<input type="hidden" name="m" value="' .
                    $_GET['m'] .
                    '"/>
				<input type="hidden" name="op" value="Update"/>
				
			<table class=box cellspacing=1 cellpadding=4  >
			  <tr>
				  <td w >Jenis Nilai</td>
				  <td  > 
				  <h1>Nilai ' .
                    $jenis .
                    ' Ke ' .
                    $ke .
                    '</h1>
				</td>
				</tr>
			</table>
			<br/>
			
		<table class="table table-striped table-bordered table-hover"  >
			<thead>
			 <tr>
			   <th width="5%" align="center">No.</th>
			   <th width="150" align="center">NIM</th>
			   <th align="center">Nama Mahasiswa</th>
			   <th align="center">Kelas</th>
			   <th align="center" width="100">Nilai</th>
			 </tr>
			 </thead>
			 <tbody>';

                $whr[] = "t_mahasiswa_nilai.kode_prodi='$prodi'";
                $whr[] = "t_mahasiswa_nilai.tahun_id='$tahun_id'";
                $whr[] = "id='$kode_mk'";
                $whr[] = "kelas='$kelas'";
                $whr[] = "jenis_nilai='$jenis'";
                $whr[] = "nilai_ke='$ke'";

                if (!empty($whr)) {
                    $strwhr = 'where ' . implode(' and ', $whr);
                }

                $q = "select t_mahasiswa_nilai.*, t_mahasiswa_nilai.idm, NIM, nama_mahasiswa from t_mahasiswa_nilai inner join m_mahasiswa on t_mahasiswa_nilai.idm=m_mahasiswa.idm $strwhr order by nim";
                $pilih = $koneksi_db->sql_query($q);
                $jumlah = $koneksi_db->sql_numrows($pilih);

                if ($jumlah > 0) {
                    while ($k = $koneksi_db->sql_fetchassoc($pilih)) {
                        $no++;
                        $id = $k['idnm'];

                        echo '<tr >
							<td  align=center>' .
                            $no .
                            '</td> 
							<td valign="top" align="center">' .
                            $k['NIM'] .
                            '</td>
							<td valign="top ">' .
                            $k['nama_mahasiswa'] .
                            '</td>
							<td valign="top align=center">' .
                            viewkelas($k['kelas']) .
                            '</td>
							<td valign="top" align=center>
								<input name="nilai[' .
                            $id .
                            ']"  type="text" class="number kecil" id="" value="' .
                            $k['nilai'] .
                            '" />
							</td>
						</tr>';
                    }
                    echo ' <thead> 	<tr ><th  colspan="5" align=right>
						<input type="submit" name="simpan" class=tombols ui-corner-all value="Update Nilai"/>
						<input type="button" class=tombols ui-corner-all value="Kembali" onClick="window.location = \'index.php?m=' .
                        $_GET['m'] .
                        '\'"/>
					</th>	</tr></thead>';
                } else {
                    echo ' <thead> 	<tr ><th  colspan="5" align=center>Belum ada data</th>	</tr></thead>';
                }

                echo '</tbody>
				</table></div></form>';
            } else {
                echo "<div  class='error'>Program studi, Mata Kuliah dan Kelas belum dipilih</div>";

                echo '<input type="button" class=tombols ui-corner-all value="Kembali" onClick="window.location = \'index.php?m=' .
                    $_GET['m'] .
                    '\'"/>';
            }
        }
    } else {
        if (!empty($prodi) && !empty($kode_mk) && !empty($kelas)) {
            echo '
		 <form action="" method="post"  class="cmxform" id="form_input" name="form_input" style="width:100%">
				<input type="hidden" name="id" value="' .
                $id .
                '"/>
				<input type="hidden" name="jenis_nilai" value="' .
                $jenis .
                '"/>
				<input type="hidden" name="ke" value="' .
                $ke .
                '"/>
				<input type="hidden" name="m" value="' .
                $_GET['m'] .
                '"/>
				<input type="hidden" name="op" value="Update"/>
				 <div class="table-responsive">
			<table class=box cellspacing=1 cellpadding=4  >
			  <tr>
				  <td width=100>Jenis Nilai</td>
				  <td  > 
				  <h1>Nilai ' .
                $jenis .
                ' Ke ' .
                $ke .
                '</h1>
				</td>
				</tr>
			</table>
			</div>
			<br/>
			 <div class="table-responsive">
		<table class="table table-striped table-bordered table-hover"  >
			<thead>
			 <tr>
			   <th width="5%" align="center">No.</th>
			   <th width="150" align="center">NIM</th>
			   <th align="center">Nama Mahasiswa</th>
			   <th align="center">Kelas</th>
			   <th align="center" width="100">Nilai</th>
			 </tr>
			 </thead>
			 <tbody>';

            $whr[] = "t_mahasiswa_nilai.kode_prodi='$prodi'";
            $whr[] = "t_mahasiswa_nilai.tahun_id='$tahun_id'";
            $whr[] = "id='$kode_mk'";
            $whr[] = "kelas='$kelas'";
            $whr[] = "jenis_nilai='$jenis'";
            $whr[] = "nilai_ke='$ke'";

            if (!empty($whr)) {
                $strwhr = 'where ' . implode(' and ', $whr);
            }

            $q = "select t_mahasiswa_nilai.*, t_mahasiswa_nilai.idm, NIM, nama_mahasiswa from t_mahasiswa_nilai inner join m_mahasiswa on t_mahasiswa_nilai.idm=m_mahasiswa.idm $strwhr order by nim";
            $pilih = $koneksi_db->sql_query($q);
            $jumlah = $koneksi_db->sql_numrows($pilih);

            if ($jumlah > 0) {
                while ($k = $koneksi_db->sql_fetchassoc($pilih)) {
                    $no++;

                    $id = $k['idnm'];
                    echo '<tr >
							<td  align=center>' .
                        $no .
                        '</td> 
							<td valign="top" align="center">' .
                        $k['NIM'] .
                        '</td>
							<td valign="top ">' .
                        $k['nama_mahasiswa'] .
                        '</td>
							<td valign="top align=center">' .
                        viewkelas($k['kelas']) .
                        '</td>
							<td valign="top" align=center>
								<input name="nilai[' .
                        $id .
                        ']"  type="text" class="number kecil" id="" value="' .
                        $k['nilai'] .
                        '" />
							</td>
						</tr>';
                }
                echo ' <thead> 	<tr ><th  colspan="5" align=right>
						<input type="submit" name="simpan" class=tombols ui-corner-all value="Update Nilai"/>
						<input type="button" class=tombols ui-corner-all value="Kembali" onClick="window.location = \'index.php?m=' .
                    $_GET['m'] .
                    '\'"/>
					</th>	</tr></thead>';
            } else {
                echo ' <thead> 	<tr ><th  colspan="5" align=center>Belum ada data</th>	</tr></thead>';
            }

            echo '</tbody>
				</table></div></form>';
        } else {
            echo "<div  class='error'>Program studi, Mata Kuliah dan Kelas belum dipilih</div>";

            echo '<input type="button" class=tombols ui-corner-all value="Kembali" onClick="window.location = \'index.php?m=' .
                $_GET['m'] .
                '\'"/>';
        }
    }
}

function Simpan()
{
    global $koneksi_db, $prodi, $tahun_id, $kode_mk;

    $ww = $koneksi_db->sql_fetchassoc(
        $koneksi_db->sql_query(
            "SELECT mk_lp FROM `m_mata_kuliah` where `id` = '$kode_mk' "
        )
    );
    $lp = $ww['mk_lp'];

    if (!empty($_POST['jenis_nilai'])) {
        $jenis_nilai = $_POST['jenis_nilai'];

        if (is_array($_POST['nilai'])) {
            foreach ($_POST['nilai'] as $key => $val) {
                if ($val < 101) {
                    if ($val == '') {
                        $val = 0;
                    }

                    $w = $koneksi_db->sql_fetchassoc(
                        $koneksi_db->sql_query("SELECT * FROM `t_mahasiswa_krs` 
				where `idkrs` = '$key' ")
                    );
                    $nilaike =
                        nilai_ke(
                            $prodi,
                            $tahun_id,
                            $kode_mk,
                            $w['kelas'],
                            $jenis_nilai,
                            $w['idm']
                        ) + 1;
                    if (
                        ($jenis_nilai == 'HADIR' ||
                            $jenis_nilai == 'TUGAS' ||
                            $jenis_nilai == 'UTS' ||
                            $jenis_nilai == 'UAS') &&
                        $nilaike > 1
                    ) {
                        echo "<script>alert('Nilai sudah pernah di input !');</script>";
                        echo "<meta http-equiv='refresh' content='1; url=index.php?m=" .
                            $_GET['m'] .
                            "'>";
                    } else {
                        $so =
                            "insert into t_mahasiswa_nilai SET 
							kode_pt='" .
                            $w['kode_pt'] .
                            "',
							kode_fak='" .
                            $w['kode_fak'] .
                            "',
							kode_jenjang='" .
                            $w['kode_jenjang'] .
                            "',
							kode_prodi='" .
                            $w['kode_prodi'] .
                            "',
							tahun_id='" .
                            $w['tahun_id'] .
                            "',
							kelas='" .
                            $w['kelas'] .
                            "',				
							idm='" .
                            $w['idm'] .
                            "',
							id='" .
                            $w['id'] .
                            "',
							jenis_nilai='" .
                            $_POST['jenis_nilai'] .
                            "',
							nilai_ke='" .
                            $nilaike .
                            "',
							nilai='" .
                            $val .
                            "'
							";

                        echo "<div  class='error'>Proses Menyimpan Data...</div>";
                        echo "<meta http-equiv='refresh' content='1; url=index.php?m=" .
                            $_GET['m'] .
                            "'>";
                    }
                    $koneksi_db->sql_query($so);
                    $hadir1 = hadir(
                        $w['kode_prodi'],
                        $w['tahun_id'],
                        $w['id'],
                        $w['idm']
                    );
                    $tugas1 = tugas(
                        $w['kode_prodi'],
                        $w['tahun_id'],
                        $w['id'],
                        $w['idm']
                    );
                    $uts1 = uts(
                        $w['kode_prodi'],
                        $w['tahun_id'],
                        $w['id'],
                        $w['idm']
                    );
                    $uas1 = uas(
                        $w['kode_prodi'],
                        $w['tahun_id'],
                        $w['id'],
                        $w['idm']
                    );

                    $rata =
                        $hadir1 * 0.1 +
                        $tugas1 * 0.2 +
                        $uts1 * 0.3 +
                        $uas1 * 0.4;

                    $presensi_dosen = total_presensi_dosen(
                        $k['kode_prodi'],
                        $k['tahun_id'],
                        $k['kelas'],
                        $k['id'],
                        'H',
                        $k['idd']
                    );
                    $presensi_mahasiswa = total_presensi_mahasiswa(
                        $k['kode_prodi'],
                        $k['tahun_id'],
                        $k['kelas'],
                        $k['id'],
                        'H',
                        $k['idm']
                    );

                    $nilaiakhir = $rata;

                    $nl = $koneksi_db->sql_fetchassoc(
                        $koneksi_db->sql_query("SELECT * FROM m_nilai where 
				kode_prodi='$w[kode_prodi]' and `nilai_min` <= $nilaiakhir and  
				`nilai_max` >= $nilaiakhir limit 1")
                    );
                    //echo "SELECT * FROM m_nilai	where kode_prodi='$w[kode_prodi]' and `nilai_min` <= $nilaiakhir and  `nilai_max` >= $nilaiakhir limit 1";
                    $ip = $nl['bobot'] * $k['sks'];

                    if ($nilaiakhir == '') {
                        $nilaiakhir = 0;
                    } elseif ($nl['nilai'] == '') {
                        $nl['nilai'] = 0;
                    } elseif ($nl['bobot'] == '') {
                        $nl['bobot'] = 0;
                    } elseif ($ip == '') {
                        $ip = 0;
                    }
                    $update = $koneksi_db->sql_query(
                        "UPDATE `t_mahasiswa_krs` SET `jumlah_nilai`='$nilaiakhir', `nilai`='$nl[nilai]', `bobot`='$nl[bobot]', `ip`='$ip', `lulus`='$nl[lulus]' WHERE `idkrs` = '$w[idkrs]'"
                    );
                }
            }
        }
    } else {
        echo "<div  class='error'>Form Jenis Nilai belum ditentukan</div>";
        echo "<meta http-equiv='refresh' content='2; url=index.php?m=" .
            $_GET['m'] .
            "'>";
    }
}

function Update()
{
    global $koneksi_db, $prodi, $tahun_id, $kode_mk;

    if (is_array($_POST['nilai'])) {
        foreach ($_POST['nilai'] as $key => $val) {
            if ($val < 101) {
                if ($val == '') {
                    $val = 0;
                }
                $w = $koneksi_db->sql_fetchassoc(
                    $koneksi_db->sql_query(
                        "SELECT * FROM `t_mahasiswa_nilai` where `idnm` = '$key' "
                    )
                );
                $k = $koneksi_db->sql_fetchassoc(
                    $koneksi_db->sql_query("SELECT * FROM `t_mahasiswa_krs` 
					where `idm` = '$w[idm]'
					and `id` = '$w[id]'
					and `kode_prodi` = '$w[kode_prodi]'
					and `tahun_id` = '$w[tahun_id]'
					 ")
                );

                $so =
                    "UPDATE t_mahasiswa_nilai SET 
						nilai='" .
                    $val .
                    "'
						where idnm='" .
                    $key .
                    "'
						";

                $koneksi_db->sql_query($so);

                $hadir1 = hadir(
                    $w['kode_prodi'],
                    $w['tahun_id'],
                    $w['id'],
                    $w['idm']
                );
                $tugas1 = tugas(
                    $w['kode_prodi'],
                    $w['tahun_id'],
                    $w['id'],
                    $w['idm']
                );
                $uts1 = uts(
                    $w['kode_prodi'],
                    $w['tahun_id'],
                    $w['id'],
                    $w['idm']
                );
                $uas1 = uas(
                    $w['kode_prodi'],
                    $w['tahun_id'],
                    $w['id'],
                    $w['idm']
                );

                $rata =
                    $hadir1 * 0.1 + $tugas1 * 0.2 + $uts1 * 0.3 + $uas1 * 0.4;

                $presensi_dosen = total_presensi_dosen(
                    $k['kode_prodi'],
                    $k['tahun_id'],
                    $k['kelas'],
                    $k['id'],
                    'H',
                    $k['idd']
                );
                $presensi_mahasiswa = total_presensi_mahasiswa(
                    $k['kode_prodi'],
                    $k['tahun_id'],
                    $k['kelas'],
                    $k['id'],
                    'H',
                    $k['idm']
                );

                $nilaiakhir = $rata;
                $nl = $koneksi_db->sql_fetchassoc(
                    $koneksi_db->sql_query(
                        "SELECT * FROM m_nilai where kode_prodi='$w[kode_prodi]' and `nilai_min` <= $nilaiakhir and  `nilai_max` >= $nilaiakhir limit 1"
                    )
                );
                //echo "SELECT * FROM m_nilai	where kode_prodi='$w[kode_prodi]' and `nilai_min` <= $nilaiakhir and  `nilai_max` >= $nilaiakhir limit 1";
                $ip = $nl['bobot'] * $k['sks'];

                if ($nilaiakhir == '') {
                    $nilaiakhir = 0;
                } elseif ($nl['nilai'] == '') {
                    $nl['nilai'] = 0;
                } elseif ($nl['bobot'] == '') {
                    $nl['bobot'] = 0;
                } elseif ($ip == '') {
                    $ip = 0;
                }
                $update = $koneksi_db->sql_query(
                    "UPDATE `t_mahasiswa_krs` SET `jumlah_nilai`='$nilaiakhir', `nilai`='$nl[nilai]', `bobot`='$nl[bobot]', `ip`='$ip', `lulus`='$nl[lulus]' WHERE `idkrs` = '$k[idkrs]'"
                );
                //echo "UPDATE `t_mahasiswa_krs` SET `jumlah_nilai`='$nilaiakhir', `nilai`='$nl[nilai]', `bobot`='$nl[bobot]', `ip`='$ip', `lulus`='$nl[lulus]' WHERE `idkrs` = '$k[idkrs]'";
            }
        }
        echo "<div  class='error'>Proses Menyimpan Data...</div>";
        echo "<meta http-equiv='refresh' content='1; url=index.php?m=" .
            $_GET['m'] .
            "'>";
    }
}

$kode_mk = BuatSesi('kode_mk');
$kelas = BuatSesi('kelas');

$go = empty($_REQUEST['op']) ? 'Daftar' : $_REQUEST['op'];

echo '<div id="title" align="right">
        <font style="color:#FF9900; font-size:30px;"><strong>.:</strong></font>
        <font style="font-size:18px; color:#999999">Nilai Mahasiswa Mahasiswa</font><br />
        	<a href="index.php">Home</a> &raquo; <a href="index.php?m=' .
    $_GET['m'] .
    '">Nilai Mahasiswa</a>  &raquo; ' .
    $go .
    '  
    </div>';
echo '<div class="mainContentCell"><div class="content">';
$go();
echo '</div></div>';

?>