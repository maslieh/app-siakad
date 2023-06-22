<?php

if (!cek_pass_sama()) {
    global $koneksi_db, $development, $user; ?>
<div id="title" align="right">
    <font style="color:#FF9900; font-size:30px;"><strong>.:</strong></font>
    <font style="font-size:18px; color:#999999">Selamat Datang <?php echo '' .
        ucfirst($_SESSION['UserName']) .
        ''; ?></font><br />
    <a href="index.php">Home</a> &raquo; <?php echo '' .
        ucfirst($_SESSION['UserName']) .
        ''; ?>
</div>

<div class="clear"></div>
<div class="mainContentCell">
    <div class="content">
        <!-----------------BATAS UTAMA-------------------------->

        <div class="newsItem">

            <h1>Sistem Informasi Akademik </h1>


            <?php $level = $koneksi_db->sql_fetchassoc(
                $koneksi_db->sql_query(
                    "SELECT level FROM user where userid='$user' "
                )
            ); ?>


            &nbsp;<font color=\"red\"></font>
            <p style="font-size:14px;margin-top:-30px;">
                Selamat Datang di Sistem Informasi Akademik (SIAKAD) <?php echo $perguruantinggi[
                    'nama_pt'
                ]; ?>.
                Sistem ini masih dalam taraf pengembangan, olehnya itu saran dan kritikan sangat diperlukan untuk
                perbaikan dimasa yang akan datang.
                Semoga dengan kehadiran sistem ini menjadikan <?php echo $perguruantinggi[
                    'nama_pt'
                ]; ?> akuntabel dalam pengelolaan akademik mahasiswa.
                <br /><br />
                Mengganti Password<br />
                Kepada seluruh user agar secara berkala mengganti Password demi keamanan <br />
                <br />Logout<br />
                Demi keamanan data di SIAKAD <?php echo $perguruantinggi[
                    'nama_pt'
                ]; ?>, jangan lupa Logout sebelum meninggalkan komputer yang anda gunakan
            </p>
            <p style="font-size:14px;">Terima Kasih<br /><br />
                Kepala Unit Teknologi Informasi dan Pangkalan Data </p>


        </div>

        <div class="clear"></div>
        <!-----------------BATAS UTAMA-------------------------->
    </div>
    <hr />
    <hr />
    <div class="content">
        <!-----------------BATAS UTAMA-------------------------->


        <div class="row">
            <?php
            global $koneksi_db;
            $prodi = $_SESSION['prodi'];
            $id = $_REQUEST['id'];
            $sql =
                'select m.kode_prodi, COUNT(*) as total, p.nama_prodi from m_mahasiswa as m,m_program_studi as p where m.kode_prodi=p.kode_prodi group by kode_prodi ';
            $q = $koneksi_db->sql_query($sql);
            $jumlah = $koneksi_db->sql_numrows($q);
            $nama_jurusan = '';
            $jumlah = null;
            //Query SQL
            while ($wr = $koneksi_db->sql_fetchassoc($hal_array['hasil'])) {
                $n++;
                $id = $wr[0];
                $jur = $wr['nama_prodi'];

                $nama_jurusan .= "'$jur'" . ', ';
                //Mengambil nilai total dari database
                $jum = $wr['total'];
                $jm += $jum;
                // echo $jumlah;
            }
            $sqlw =
                'select m.kode_prodi, COUNT(*) as total, p.nama_prodi from m_mata_kuliah as m,m_program_studi as p where m.kode_prodi=p.kode_prodi group by kode_prodi ';
            $qw = $koneksi_db->sql_query($sqlw);
            $jumlahw = $koneksi_db->sql_numrows($qw);
            $nama_jurusanw = '';
            $jumlahw = null;
            //Query SQL
            while ($wrw = $koneksi_db->sql_fetchassoc($hal_array['hasil'])) {
                $n++;
                $idw = $wrw[0];
                $jurw = $wrw['nama_prodi'];

                $nama_jurusanw .= "'$jurw'" . ', ';
                //Mengambil nilai total dari database
                $jumw = $wrw['total'];
                $jmw += $jumw;
                // echo $jumlah
            }
            ?>



            <div class="col-xl-3 col-lg-4">
                <div class="card-box">
                    <h4 class="header-title">Data Mahasiswa</h4>
                    <div class="my-4">
                        <label class="text-muted">TOTAL</label>
                        <h2 class="font-weight-normal mb-2"> <?= $jm ?> <i class="mdi mdi-arrow-up text-success"></i>
                        </h2>
                    </div>
                    <div class="chartjs-chart-example chartjs-chart">
                        <canvas id="myChart"></canvas>
                    </div>

                    <div>
                        <?php
                        $qpp = $koneksi_db->sql_query(
                            'select m.kode_prodi, COUNT(*) as total, p.nama_prodi from m_mahasiswa as m,m_program_studi as p where m.kode_prodi=p.kode_prodi group by kode_prodi'
                        );
                        $jumlah = $koneksi_db->sql_numrows($qpp);

                        while ($wf = $koneksi_db->sql_fetchassoc($qpp)) {

                            $sd = $wf['total'];
                            $sa += $sd;
                            $sz = ($sd / $sa) * 100;
                            ?>
                        <?= $wf[
                            'nama_prodi'
                        ] ?><span class="float-right font-weight-normal">
                            <?= $sd ?>
                        </span>
                        <div class="progress mb-3">
                            <div class="progress-bar bg-success" role="progressbar" style="width:<?= $wf[
                                'total'
                            ] ?>%" aria-valuenow="  
                        <?= $wf[
                            'total'
                        ] ?>" aria-valuemin="0" aria-valuemax="10000"></div>
                        </div>
                        <?php
                        }
                        ?>
                    </div>
                </div> <!-- end card-box -->
            </div> <!-- end col -->

            <div class="col-xl-3 col-lg-4">
                <div class="card-box">
                    <h4 class="header-title mb-3">Jenis Kelamin Mahasiswa Berdasarkan jurusan</h4>
                    <div class="row text-center">
                        <div class="col-sm-4 mb-3">
                            <h3 class="font-weight-light"></h3>
                            <p class="text-muted text-overflow"></p>
                        </div>

                    </div>
                    <div class="chartjs-chart-example chartjs-chart">
                        <canvas id="jk"></canvas>
                    </div>
                </div> <!-- end card-box -->

            </div> <!-- end col -->
            <div class="col-xl-3 col-lg-4">
                <div class="card-box">
                    <h4 class="header-title">Data Matakuliah</h4>
                    <div class="my-4">
                        <label class="text-muted">TOTAL</label>
                        <h2 class="font-weight-normal mb-2"> <?= $jmw ?> <i class="mdi mdi-arrow-up text-success"></i>
                        </h2>
                    </div>
                    <div class="chartjs-chart-example chartjs-chart">
                        <canvas id="matkul"></canvas>
                    </div>

                    <div>
                        <?php
                        $qppw = $koneksi_db->sql_query(
                            'select m.kode_prodi, COUNT(*) as total, p.nama_prodi from m_mata_kuliah as m,m_program_studi as p where m.kode_prodi=p.kode_prodi group by kode_prodi'
                        );
                        $jumlahw = $koneksi_db->sql_numrows($qppw);

                        while ($wfw = $koneksi_db->sql_fetchassoc($qppw)) {

                            $sdw = $wfw['total'];
                            $saw += $sdw;
                            $szw = ($sdw / $saw) * 100;
                            ?>
                        <?= $wfw[
                            'nama_prodi'
                        ] ?><span class="float-right font-weight-normal">
                            <?= $sdw ?>
                        </span>
                        <div class="progress mb-3">
                            <div class="progress-bar bg-success" role="progressbar" style="width:<?= $wfw[
                                'total'
                            ] ?>%" aria-valuenow="  
                        <?= $wfw[
                            'total'
                        ] ?>" aria-valuemin="0" aria-valuemax="10000"></div>
                        </div>
                        <?php
                        }
                        ?>
                    </div>
                </div> <!-- end card-box -->
            </div> <!-- end col -->


        </div>
        <hr />
        <hr />
        <div class="row">
            <?php
            global $koneksi_db;
            $prodi = $_SESSION['prodi'];
            $id = $_REQUEST['id'];
            $sqlq =
                'select m.kode_prodi, COUNT(*) as total, p.nama_prodi from m_dosen as m,m_program_studi as p where m.kode_prodi=p.kode_prodi group by kode_prodi ';
            $qq = $koneksi_db->sql_query($sqlq);
            $jumlahq = $koneksi_db->sql_numrows($qq);
            $nama_jurusanq = '';
            $jumlahq = null;
            //Query SQL
            while ($wrq = $koneksi_db->sql_fetchassoc($qq)) {
                $n++;
                $idq = $wrq[0];
                $jurq = $wrq['nama_prodi'];

                $nama_jurusanq .= "'$jurq'" . ', ';
                //Mengambil nilai total dari database
                $jumq = $wrq['total'];
                $jmq += $jumq;
                // echo $jumlah;
            }
            ?>








        </div>
        <!-- end row -->
        <div class="clear"></div>
        <!-----------------BATAS UTAMA-------------------------->

    </div>

</div>


<?php
} ?>