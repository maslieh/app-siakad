<?php global $config; ?>

</div>
<!-- /. PAGE INNER  -->
<footer class="main-footer">
    <div class="pull-right hidden-xs">
        <b>Version</b> 2.4.13
    </div>
    <strong>Copyright &copy; 2022 <a href="#">SyntaxError</a>.</strong> All rights
    reserved.
</footer>
</div>

<!-- /. PAGE WRAPPER  -->
</div>
<!-- /. WRAPPER  -->
<!-- JQUERY SCRIPTS -->


<!-- BOOTSTRAP SCRIPTS -->


<!-- BOOTSTRAP SCRIPTS -->
<script src="assets/js/bootstrap.min.js"></script>
<!-- METISMENU SCRIPTS -->
<script src="assets/js/jquery.metisMenu.js"></script>
<!-- CUSTOM SCRIPTS -->
<script src="assets/js/custom.js"></script>
<script src="js/Chart.bundle.min.js"></script>
<script src="assets/js/select2/js/select2.full.js"></script>
<!--script src="assets/js/moment.min.js"></script-->

<script type="text/javascript">
$(document).ready(function() {
    $('input[type=text]').addClass('form-control');
    $("select").addClass("form-control");
    $("textarea").addClass("form-control");
    $("table").addClass("table");
});
</script>

<script src="assets/js/bootstrap-datepicker.js"></script>
<script>
$(function() {
    $(".date").datepicker({
        format: 'yyyy-mm-dd'
    });
});
</script>

<script src="assets/js/dataTables/jquery.dataTables.js"></script>
<script src="assets/js/dataTables/dataTables.bootstrap.js"></script>
<script>
$(document).ready(function() {
    $('#dataTables-example').dataTable();

    $("select").select2({
        theme: "bootstrap",
        maximumSelectionSize: 6,
        containerCssClass: ':all:'
    });;

});
</script>
<script type="text/javascript">
function pagination(page) {
    window.location.href = 'index.php?m=<?= $_GET[
        'm'
    ] ?>&starting=' + page + '&random=' + Math.random();
}
</script>

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
while ($wr = $koneksi_db->sql_fetchassoc($q)) {
    $n++;
    $id = $wr[0];
    $jur = $wr['nama_prodi'];

    $nama_jurusan .= "'$jur'" . ', ';
    //Mengambil nilai total dari database
    $jum = $wr['total'];
    $jumlah .= "$jum" . ', ';
    // echo $jumlah;
}
?>

<script>
var ctx = document.getElementById("myChart");
var myChart = new Chart(ctx, {
    type: 'pie',
    data: {
        labels: [<?php echo $nama_jurusan; ?>],
        datasets: [{
            data: [<?php echo $jumlah; ?>],
            backgroundColor: ["#02a8b5", "#fa5c7c", "#cac15d", "#ca445d"],
            borderColor: "transparent",
            borderWidth: "4",
        }, ]
    },

    options: {

        cutoutPercentage: 70,
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        }
    }
});
</script>


<?php
global $koneksi_db;
$prodi = $_SESSION['prodi'];
$id = $_REQUEST['id'];
$sqlw =
    'select m.kode_prodi, COUNT(*) as total, p.nama_prodi from m_mata_kuliah as m,m_program_studi as p where m.kode_prodi=p.kode_prodi group by kode_prodi ';
$qw = $koneksi_db->sql_query($sqlw);
$jumlahw = $koneksi_db->sql_numrows($qw);
$nama_jurusanw = '';
$jumlahw = null;
//Query SQL
while ($wrw = $koneksi_db->sql_fetchassoc($qw)) {
    $n++;
    $idw = $wrw[0];
    $jurw = $wrw['nama_prodi'];

    $nama_jurusanw .= "'$jurw'" . ', ';
    //Mengambil nilai total dari database
    $jumw = $wrw['total'];
    $jumlahw .= "$jumw" . ', ';
    // echo $jumlah;
}
?>

<script>
var ctx = document.getElementById("matkul");
var matkul = new Chart(ctx, {
    type: 'pie',
    data: {
        labels: [<?php echo $nama_jurusanw; ?>],
        datasets: [{
            data: [<?php echo $jumlahw; ?>],
            backgroundColor: ["#02a8b5", "#fa5c7c", "#cac15d", "#ca445d"],
            borderColor: "transparent",
            borderWidth: "1",
        }, ]
    },
    cutoutPercentage: 20,
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        }
    }
});
</script>


<?php
global $koneksi_db;
$prodi = $_SESSION['prodi'];
$id = $_REQUEST['id'];
$sql =
    'select m.kode_prodi, COUNT(*) as total, p.nama_prodi from m_dosen as m,m_program_studi as p where m.kode_prodi=p.kode_prodi group by kode_prodi ';
$q = $koneksi_db->sql_query($sql);
$jumlah = $koneksi_db->sql_numrows($q);
$nama_jurusan = '';
$jumlah = null;
//Query SQL
while ($wr = $koneksi_db->sql_fetchassoc($q)) {
    $n++;
    $id = $wr[0];
    $jur = $wr['nama_prodi'];

    $nama_jurusan .= "'$jur'" . ', ';
    //Mengambil nilai total dari database
    $jum = $wr['total'];
    $jumlah .= "$jum" . ', ';
    // echo $jumlah;
}
?>

<script>
var ctx = document.getElementById("dosen");
var dosen = new Chart(ctx, {
    type: 'pie',
    data: {
        labels: [<?php echo $nama_jurusan; ?>],
        datasets: [{
            data: [<?php echo $jumlah; ?>],
            backgroundColor: ["#02a8b5", "#fa5c7c", "#cac15d", "#ca445d"],
            borderColor: "transparent",
            borderWidth: "4",
        }, ]
    },

    options: {

        cutoutPercentage: 70,
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        }
    }
});
</script>
<?php
$jns = "SELECT l.kode_prodi, l.totall, l.prodi,pp.totalp from 
(SELECT a.kode_prodi, count(*) as totall, p.nama_prodi as prodi   
FROM `m_mahasiswa` as a, m_program_studi p
where a.kode_prodi=p.kode_prodi AND a.jenis_kelamin='L' group by kode_prodi,jenis_kelamin) as l,
(SELECT kode_prodi, count(*) as totalp from m_mahasiswa where jenis_kelamin='P' group by kode_prodi, jenis_kelamin) as pp
where l.kode_prodi=pp.kode_prodi
 ";
$q1 = $koneksi_db->sql_query($jns);
$jumlah = $koneksi_db->sql_numrows($q1);
$jk = '';
$jumlah1 = null;
//Query SQL
while ($ww = $koneksi_db->sql_fetchassoc($q1)) {
    $n++;
    $id1 = $ww[0];
    $jur1 = $ww['prodi'];
    $nama_jurusan1 .= "'$jur1'" . ', ';
    //Mengambil nilai total dari database

    $jum1 = $ww['total'];
    $jumlah1 .= "$jum1" . ', ';

    $l = $ww['totall'];
    $ll .= "$l" . ',';

    $l1 = $ww['totalp'];
    $ll1 .= "$l1" . ',';
    // echo $jumlah;
}

/// ABSENSI MAHASISWA GRAFIK BATANG
$ab = $koneksi_db->sql_query(
    "
	SELECT h.prodi,h.total,i.totali,s.totals,a.totala FROM 
	(select m.kode_prodi, COUNT(*) as total, p.nama_prodi as prodi from t_mahasiswa_presensi as m,m_program_studi as p where m.kode_prodi=p.kode_prodi AND jenis_presensi='H' group by kode_prodi,jenis_presensi) as h, 
	(select kode_prodi, COUNT(*) as totali from t_mahasiswa_presensi where jenis_presensi='I' group by kode_prodi,jenis_presensi) as i ,
	(select kode_prodi, COUNT(*) as totals from t_mahasiswa_presensi where jenis_presensi='S' group by kode_prodi,jenis_presensi) as s ,
	(select kode_prodi, COUNT(*) as totala from t_mahasiswa_presensi m where jenis_presensi='A' group by kode_prodi,jenis_presensi) as a
	WHERE h.kode_prodi=i.kode_prodi AND h.kode_prodi=s.kode_prodi AND h.kode_prodi=a.kode_prodi AND s.kode_prodi=i.kode_prodi AND a.kode_prodi=i.kode_prodi AND a.kode_prodi=s.kode_prodi"
);
while ($ap = $koneksi_db->sql_fetchassoc($ab)) {
    $had = $ap['total'];
    $hadir .= "$had" . ',';

    $izi = $ap['totali'];
    $izin .= "$izi" . ',';

    $sak = $ap['totals'];
    $sakit .= "$sak" . ',';

    $alp = $ap['totala'];
    $alpa .= "$alp" . ',';

    $prodi = $ap['prodi'];
    $prdo .= "'$prodi'" . ', ';
}
?>

<script>
var jk = ['Laki-laki', 'Perempuan'];
var ctx = document.getElementById("jk");
var color = Chart.helpers.color;
var jk = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: [<?= $nama_jurusan1 ?>],
        datasets: [{

                label: "Laki-laki",
                backgroundColor: "rgba(89, 193, 115,0.8)",
                borderColor: "#59C173",
                pointBackgroundColor: "#59C173",
                pointBorderColor: "#fff",
                pointHoverBackgroundColor: "#fff",
                pointHoverBorderColor: "#59C173",
                data: [<?= $ll ?>],
            },
            {
                label: "Perempuan",
                backgroundColor: "rgba(161, 127, 224,0.8)",
                borderColor: "#a17fe0",
                pointBackgroundColor: "#a17fe0",
                pointBorderColor: "#fff",
                pointHoverBackgroundColor: "#fff",
                pointHoverBorderColor: "#a17fe0",
                data: [<?= $ll1 ?>],
            },


        ],
    },
    options: {

        cutoutPercentage: 80,
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        }
    }
});
</script>


<script>
var jk = ['Laki-laki', 'Perempuan'];
var ctx = document.getElementById("m_absen");
var color = Chart.helpers.color;
var m_absen = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: [<?= $prdo ?>],
        datasets: [{

                label: "Hadir",
                backgroundColor: "rgba(89, 193, 115,0.8)",
                borderColor: "#25fe6c",
                pointBackgroundColor: "#25fe6c",
                pointBorderColor: "#fff",
                pointHoverBackgroundColor: "#fff",
                pointHoverBorderColor: "#25fe6c",
                data: [<?= $hadir ?>],
            },
            {
                label: "Izin",
                backgroundColor: "rgba(20, 241, 241,0.8)",
                borderColor: "#14f1a0",
                pointBackgroundColor: "#14f1a0",
                pointBorderColor: "#fff",
                pointHoverBackgroundColor: "#fff",
                pointHoverBorderColor: "#14f1a0",
                data: [<?= $izin ?>],
            },
            {
                label: "Sakit",
                backgroundColor: "rgba(215, 236, 38,0.8)",
                borderColor: "#d7ec26",
                pointBackgroundColor: "#d7ec26",
                pointBorderColor: "#fff",
                pointHoverBackgroundColor: "#fff",
                pointHoverBorderColor: "#d7ec26",
                data: [<?= $sakit ?>],
            },
            {
                label: "Alpha",
                backgroundColor: "rgba(236, 38, 75,0.8)",
                borderColor: "#ec264b",
                pointBackgroundColor: "#ec264b",
                pointBorderColor: "#fff",
                pointHoverBackgroundColor: "#fff",
                pointHoverBorderColor: "#ec264b",
                data: [<?= $alpa ?>],
            },


        ],
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        }
    }
});
</script>



</body>

</html>