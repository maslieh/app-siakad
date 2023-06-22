<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <?php global $judul_situs; ?>
    <meta charset="utf-8" />

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" href="images/logofav.png" type="image/x-icon" />
    <title><?php echo $judul_situs; ?></title>
    <meta name="Description" content="Sistem Informasi Akademik Mahasiswa" />
    <meta name="Keywords" content="Sistem Informasi Akademik Mahasiswa" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="Distribution" content="Global" />
    <meta name="Author" content="Administrator" />
    <meta name="Robots" content="index,follow" />

    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <!-- BOOTSTRAP STYLES-->
    <link href="assets/css/bootstrap2x2x.css" rel="stylesheet" />
    <!-- FONTAWESOME STYLES-->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLES-->
    <link href="assets/css/custom.css" rel="stylesheet" />
    <link href="assets/css/form.css" rel="stylesheet" />
    <!-- GOOGLE FONTS-->

    <link href="assets/js/dataTables/dataTables.bootstrap.css" rel="stylesheet">

    <script src="js/jquery-ui.js"></script>
    <script src="assets/js/Chart.js" type='text/javascript'></script>
    <link rel="stylesheet" href="assets/css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="assets/js/select2/css/select2.min.css">
    <link rel="stylesheet" href="assets/js/select2/css/select2-bootstrap.css">
    <link rel="stylesheet" href="js/jquery-ui2.css">
    <!-- JQUERY SCRIPTS -->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <script src="assets/js/jquery.form.js"></script>
    <script type='text/javascript'>
    function show(page, div) {
        //var site = "";
        $.ajax({
            url: page,
            success: function(response) {
                $(div).html(response);
            },
            dataType: "html"
        });
        return false;
    }
    </script>
    <script language="javascript">
    all_checked = true;

    function checkall(formName, boxName) {
        for (i = 0; i < document.getElementById(formName).elements.length; i++) {
            var formElement = document.getElementById(formName).elements[i];
            if (formElement.type == 'checkbox' && formElement.name == boxName && formElement.disabled == false) {
                formElement.checked = all_checked;
            }
        }
        all_checked = all_checked ? false : true;
    }
    </script>

    <script language="JavaScript">
    function bukajendela(url) {
        window.open(url, "window_baru",
            "toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=auto,height=auto,left =304,top = 150.5"
        );
    }
    </script>
    <link href="https://fonts.googleapis.com/css?family=Questrial&display=swap" rel="stylesheet">
    <style type="text/css">
    body {
        font-family: 'Questrial', sans-serif;
        font-size: 14px;
        word-break: normal;
    }

    td {
        padding-right: 6px;
    }



    #divLoading {
        width: 50px;
        height: 50px;
        position: absolute;
        top: 50%;
        left: 50%;
        margin-top: -25px;
        margin-left: -25px;
        z-index: 9999999;
    }
    </style>

</head>

<body>
    <div id="wrapper">
        <nav class="navbar navbar-default navbar-cls-top " role="navigation" style="background:#3a77ab;">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <span class="navbar-brand"><a href="#" class="logo_text">
                        <img src="images/logo-login.png" width="140" height="30">
                    </a></span>


            </div>

            <div
                style="color: white;padding: 15px 50px 5px 50px;float: right;font-size: 16px;height:60px;background:#3a77ab;">
                <?php Modul('prodi_aktif'); ?>
            </div>
        </nav>
        <!-- /. NAV TOP  -->
        <nav class="navbar-default navbar-side" role="navigation">
            <center>

                <?php
                global $koneksi_db, $development, $user;
                $namam = strtoupper(ucfirst($_SESSION['Name']));
                $id = $_SESSION['username'];

                $w = $koneksi_db->sql_fetchassoc(
                    $koneksi_db->sql_query(
                        "SELECT * FROM user where nama='$namam' limit 1 "
                    )
                );
                $id = $w['userid'];
                $level = $w['level'];
                $nama = $w['nama'];
                if ($level == 'ADMIN') {
                    echo '<br/><img src="images/logo-user.png" style="
			border-radius: 150px;padding:6px;
			-webkit-border-radius: 150px;
			-moz-border-radius: 150px;
			background: url(URL) no-repeat;
			box-shadow: 0 0 8px rgba(0, 0, 0, .8);
			-webkit-box-shadow: 0 0 8px rgba(0, 0, 0, .8);
			-moz-box-shadow: 0 0 8px rgba(0, 0, 0, .8);width:80px;float:left;margin-left:20px;"><br/>
					<div style="color: white;margin-top:-10px;margin-bottom:10px;">' .
                        $namam .
                        '- <br/><a href="index.php?m=37"><font style="color: #3399cc">Logout</font> </a><br/> <br/></div>';
                } elseif ($level == 'MAHASISWA') {
                    $w = $koneksi_db->sql_fetchassoc(
                        $koneksi_db->sql_query(
                            "SELECT * FROM m_mahasiswa where idm='$id' limit 1 "
                        )
                    );
                    $foto =
                        $w['foto'] == ''
                            ? 'gambar/no_avatars.gif'
                            : 'gambar/' . $w['foto'] . '';
                    echo '<br/>
		<div><img src="' .
                        $foto .
                        '" style="
			border-radius: 180px;padding:6px;
			-webkit-border-radius: 180px;
			-moz-border-radius: 180px;
			background: url(URL) no-repeat;
			box-shadow: 0 0 8px rgba(0, 0, 0, .8);
			-webkit-box-shadow: 0 0 8px rgba(0, 0, 0, .8);
			-moz-box-shadow: 0 0 8px rgba(0, 0, 0, .8);width:150px;float:left;margin-left:70px;"></div><br/><br/><br/><br/><br/><br/><br/><br/>
					<div style="color: white;margin-top:-10px;margin-bottom:10px;width:150px">' .
                        $nama .
                        '<br/><a href="index.php?m=37"><font style="color: #3399cc">Logout</font> </a><br/> <br/></div>';
                } else {
                    $w = $koneksi_db->sql_fetchassoc(
                        $koneksi_db->sql_query(
                            "SELECT * FROM m_dosen where idd='$id' limit 1 "
                        )
                    );
                    $foto =
                        $w['foto'] == ''
                            ? 'gambar/no_avatars.gif'
                            : 'gambar/' . $w['foto'] . '';
                    echo '<br/>
		<div><img src="' .
                        $foto .
                        '" style="
			border-radius: 180px;padding:6px;
			-webkit-border-radius: 180px;
			-moz-border-radius: 180px;
			background: url(URL) no-repeat;
			box-shadow: 0 0 8px rgba(0, 0, 0, .8);
			-webkit-box-shadow: 0 0 8px rgba(0, 0, 0, .8);
			-moz-box-shadow: 0 0 8px rgba(0, 0, 0, .8);width:150px;float:left;margin-left:60px;"></div><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
					<div style="color: white;margin-top:-10px;margin-bottom:10px;width:150px">' .
                        $nama .
                        '<br/><a href="index.php?m=37"><font style="color: #3399cc">Logout</font> </a><br/> <br/></div>';
                }

//
?></center>
            <div class="sidebar-collapse"><?php Komponen('menu'); ?></div>
        </nav>
        <div id="page-wrapper">
            <div id="page-inner">