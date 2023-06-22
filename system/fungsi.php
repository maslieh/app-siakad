<?php

//fungsi untuk outomatik logout
function login_validate() {
	//ukuran waktu dalam detik
	$timer=3000;
	//untuk menambah masa validasi
	$_SESSION["expires_by"] = time() + $timer;
}

function login_check() {
	//mengambil nilai session pertama
	$exp_time = $_SESSION["expires_by"];
	
	//jika waktu sistem lebih kecil dari nilai waktu session
	if (time() < $exp_time) {
		//panggil fungsi dan tambah waktu session
		login_validate();
		return true; 
	}else{
		//jika waktu session lebih kecil dari waktu session atau lewat batas
		//unset session
		unset($_SESSION["expires_by"]);
		return false; 
	}
}


/// fungsi untuk membuta session
function BuatSesi($str, $def='') {
  if (isset($_REQUEST[$str])) {
	$_SESSION[$str] = $_REQUEST[$str];
	return $_REQUEST[$str];
  }
  else {
    if (isset($_SESSION[$str])) return $_SESSION[$str];
    else {
	  $_SESSION[$str] = $def;
	  return $def;
    }
  }
}
//////////////////////////
// fungsi utk buat cookies
function TulisCookie($variabel, $value) {
	$nextyear = mktime (0,0,0,date("m"),date("d"),date("Y")+1);
setcookie($variabel,$value,$nextyear);
}

function GetSpace($maxlength,$length){
	$spacer="";
	for ($i=1;$i<=$maxlength-$length;$i++)
		$spacer .="&nbsp;";
	return $spacer;	
}
/////////////////////////////////////////////////////////
  
function kdauto($tabel, $inisial){
	//include "inc.koneksidb.php";
	global $koneksi_db;
	
	$results	= $koneksi_db->sql_query("SELECT * FROM $tabel");
	$col = $koneksi_db->sql_fetchfields($results);
    $field = $col[0]->name;
	$panjang = $col[0]->max_length;

 	$qry	= $koneksi_db->sql_query("SELECT max(".$field.")  FROM ".$tabel);
 	$row	= $koneksi_db->sql_fetchrow($qry); 
 	if ($row[0]=="") {
 		$angka=0;
	}
 	else {
 		$angka		= substr($row[0], strlen($inisial));
 	}
	
 	$angka++;
 	$angka	=strval($angka); 
 	$tmp	="";
 	for($i=1; $i<=($panjang-strlen($inisial)-strlen($angka)); $i++) {
		$tmp=$tmp."0";	
	}
 	return $inisial.$tmp.$angka;
}



///////////////////
function unik_number() {
        $chars = '0123456789';
        mt_srand((double)microtime() * 1000000 * getmypid()); // seed the random number generater (must be done)
        $number = '';
        while (strlen($number) < 3)
        $number .= substr($chars, (mt_rand()%strlen($chars)), 1);
        return $number;

}
////////////////////////

// Format Password
function gen_pass($m) {
    $m = intval($m);
    $pass = "";
    for ($i = 0; $i < $m; $i++) {
        $te = mt_rand(48, 122);
        if (($te > 57 && $te < 65) || ($te > 90 && $te < 97)) $te = $te - 9;
        $pass .= chr($te);
    }
    return $pass;
}

//////////////////
function text_filter($message, $type="") {

    if (intval($type) == 2) {
        $message = htmlspecialchars(trim($message), ENT_QUOTES);
    } else {
        $message = strip_tags(urldecode($message));
        $message = htmlspecialchars(trim($message), ENT_QUOTES);
    }
   
    return $message;
}

// Mail check
function checkemail($email) {
    global $error;
    $email = strtolower($email);
    if ((!$email) || ($email=="") || (!preg_match("/^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,6}$/", $email))) $error .= "<center>Error, E-Mail address invalid!<br />Please use the standard format (<b>admin@domain.com</b>)</center>";
    if ((strlen($email) >= 4) && (substr($email, 0, 4) == "www.")) $error .= "<center>Error, E-Mail address invalid!<br />Please remove the beginning (<b>www.</b>)</center>";
    if (strrpos($email, " ") > 0) $error .= "<center>Error, E-Mail address invalid!<br />Please do not use spaces.</center>";
    return $error;
}

function is_valid_email($mail) {
	// checks email address for correct pattern
	// simple: 	"/^[-_a-z0-9]+(\.[-_a-z0-9]+)*@[-a-z0-9]+(\.[-a-z0-9]+)*\.[a-z]{2,6}$/i"
	$r = 0;
	if($mail) {
		$p  =	"/^[-_a-z0-9]+(\.[-_a-z0-9]+)*@[-a-z0-9]+(\.[-a-z0-9]+)*\.(";
		// TLD  (01-30-2004)
		$p .=	"com|edu|gov|int|mil|net|org|aero|biz|coop|info|museum|name|pro|arpa";
		// ccTLD (01-30-2004)
		$p .=	"ac|ad|ae|af|ag|ai|al|am|an|ao|aq|ar|as|at|au|aw|az|ba|bb|bd|";
		$p .=	"be|bf|bg|bh|bi|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|";
		$p .=	"cg|ch|ci|ck|cl|cm|cn|co|cr|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|";
		$p .=	"ec|ee|eg|eh|er|es|et|fi|fj|fk|fm|fo|fr|ga|gd|ge|gf|gg|gh|gi|";
		$p .=	"gl|gm|gn|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|";
		$p .=	"im|in|io|iq|ir|is|it|je|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|";
		$p .=	"ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|mg|mh|mk|ml|";
		$p .=	"mm|mn|mo|mp|mq|mr|ms|mt|mu|mv|mw|mx|my|mz|na|nc|ne|nf|ng|ni|";
		$p .=	"nl|no|np|nr|nu|nz|om|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|ps|pt|pw|";
		$p .=	"py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|";
		$p .=	"sr|st|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tm|tn|to|tp|tr|tt|tv|tw|";
		$p .=	"tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|";
		$p .=	"za|zm|zw";
		$p .=	")$/i";

		$r = preg_match($p, $mail) ? 1 : 0;
	}
	return $r;
}

// Mail send
function mail_send($email, $smail, $subject, $message, $id="", $pr="") {
    $email = text_filter($email);
    $smail = text_filter($smail);
    $subject = text_filter($subject);
    $id = intval($id);
    $pr = (!$pr) ? "3" : "".intval($pr)."";
    $message = (!$id) ? "".$message."" : "".$message."<br /><br />IP: ".getenv("REMOTE_ADDR")."<br />User agent: ".getenv("HTTP_USER_AGENT")."";
    $mheader = "MIME-Version: 1.0\n"
    ."Content-Type: text/html; charset=utf-8\n"
    ."Reply-To: \"$smail\" <$smail>\n"
    ."From: \"$smail\" <$smail>\n"
    ."Return-Path: <$smail>\n"
    ."X-Priority: $pr\n"
    ."X-Mailer: Berbagi Berkah Gratis\n";
    @mail($email, $subject, $message, $mheader);
}

function limittxt ($nama, $limit){
    if (strlen ($nama) > $limit) {
    $nama = substr($nama, 0, $limit) .'...';
    }else {
        $nama = $nama;
    }
return $nama;
}


function randomPassword()
{
// function untuk membuat password random 6 digit karakter
 
$digit = 6;
$karakter = "ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789abcdefghijklmnopqrstuvwxyz";
 
srand((double)microtime()*1000000);
$i = 0;
$pass = "";
while ($i <= $digit-1)
{
$num = rand() % 32;
$tmp = substr($karakter,$num,1);
$pass = $pass.$tmp;
$i++;
}
return $pass;
}


function datetimes($tgl,$Jam=true){
/*Contoh Format : 2007-08-15 01:27:45*/
$tanggal = strtotime($tgl);
$bln_array = array (
			'01'=>'Januari',
			'02'=>'Februari',
			'03'=>'Maret',
			'04'=>'April',
			'05'=>'Mei',
			'06'=>'Juni',
			'07'=>'Juli',
			'08'=>'Agustus',
			'09'=>'September',
			'10'=>'Oktober',
			'11'=>'November',
			'12'=>'Desember'
			);
$hari_arr = Array ('0'=>'Minggu',
				   '1'=>'Senin',
				   '2'=>'Selasa',
					'3'=>'Rabu',
					'4'=>'Kamis',
					'5'=>'Jum`at',
					'6'=>'Sabtu'
				   );
$hari = @$hari_arr[date('w',$tanggal)];
$tggl = date('j',$tanggal);
$bln = @$bln_array[date('m',$tanggal)];
$thn = date('Y',$tanggal);
$jam = $Jam ? date ('H:i:s',$tanggal) : '';
return "$hari, $tggl $bln $thn $jam";			
}

function gethari($tgl){
/*Contoh Format : 2007-08-15 01:27:45*/
$tanggal = strtotime($tgl);
$hari_arr = Array ('0'=>'Minggu',
				   '1'=>'Senin',
				   '2'=>'Selasa',
					'3'=>'Rabu',
					'4'=>'Kamis',
					'5'=>'Jum`at',
					'6'=>'Sabtu'
				   );
$hari = @$hari_arr[date('w',$tanggal)];
return "$hari";			
}


function getbulan($tgl){
/*Contoh Format : 2007-08-15 01:27:45*/
$tanggal = strtotime($tgl);
$bln_array = array (
			'01'=>'Jan',
			'02'=>'Feb',
			'03'=>'Mar',
			'04'=>'Apr',
			'05'=>'Mei',
			'06'=>'Jun',
			'07'=>'Jul',
			'08'=>'Ags',
			'09'=>'Sep',
			'10'=>'Okt',
			'11'=>'Nov',
			'12'=>'Des'
			);
$bln = @$bln_array[date('m',$tanggal)];
return "$bln";			
}

function converttgl ($date){
$bln_array = array ('01'=>'Januari',
			'02'=>'Februari',
			'03'=>'Maret',
			'04'=>'April',
			'05'=>'Mei',
			'06'=>'Juni',
			'07'=>'Juli',
			'08'=>'Agustus',
			'09'=>'September',
			'10'=>'Oktober',
			'11'=>'November',
			'12'=>'Desember'
			);
$date = explode ('-',$date);

return $date[2] . ' ' . $bln_array[$date[1]] . ' ' . $date[0];			
				
}


function tgldikti ($date){
$date = explode ('-',$date);

return $date[0].''.$date[1].''.$date[2];			
				
}

function seo($s) {
    $c = array (' ');
    $d = array ('-','/','\\',',','.','#',':',';','\'','"','[',']','{','}',')','(','|','`','~','!','@','%','$','^','&','*','=','?','+');
    $s = str_replace($d, '', $s); // Hilangkan karakter yang telah disebutkan di array $d
    $s = strtolower(str_replace($c, '-', $s)); // Ganti spasi dengan tanda - dan ubah hurufnya menjadi kecil semua
    return $s;
}

function cek_pass_sama () {
	$userMAN=md5($_SESSION['UserName']);
	$passMAN=$_SESSION['PassWord'];
	if ($userMAN==$passMAN){
	echo "<script>window.location.href='index.php?m=36';</script>";
	}
}
/*
function web_login (){
 
global $UserID,$koneksi_db;
$user          = $_POST['username'];
$password      = md5($_POST['password']);
$password2	   = $_POST['password'];
$query         = $koneksi_db->sql_query ("SELECT * FROM user WHERE username='$user' AND password='$password'");
$total         = $koneksi_db->sql_numrows($query);
$data          = $koneksi_db->sql_fetchrow ($query);

	$koneksi_db->sql_freeresult ($query);
	if ($total > 0 && $user == $data['username'] && $password == $data['password']){
	if($data['aktif']!='N'){

	if ($password2!=$user){
	//echo write_mysql_log("Login Sukses");
	//session_is_registered ('UserID') ;
	$_SESSION['UserID']= $data[0];
	//session_is_registered ('UserName') ;
	$_SESSION['UserName']= $data[1];
	//session_is_registered ('PassWord') ;
	$_SESSION['PassWord']= $data[2];
	//session_is_registered ('Email') ;
	$_SESSION['Email']= $data[4];
	//session_is_registered ('Level') ;
	$_SESSION['Level']= $data[5];


	header ("location:index.php");
	exit;
	
	} else {
			//session_is_registered ('UserID') ;
			$_SESSION['UserID']= $data[0];
			//session_is_registered ('UserName') ;
			$_SESSION['UserName']= $data[1];
			//session_is_registered ('PassWord') ;
			$_SESSION['PassWord']= $data[2];
			//session_is_registered ('Email') ;
			$_SESSION['Email']= $data[4];
			//session_is_registered ('Level') ;
			$_SESSION['Level']= $data[5];

			header ("location:index.php?m=36");
			exit;
	}
	}else{
		echo '<div id="#popup"></div>
						<div id="popup">
						<div class="window">
							<a href="" class="close-button" title="Close">X</a>
						<h2>ANDA BELUM MELAKUKAN PEMBAYARAN ATAU SEDANG CUTI ATAU TELAH DINYATAKAN LULUS</h2>
						</div>
						</div>';
	}
	}else {
	return '<div class=error><small><font color="#FF0000">User atau Password Salah</font></small></div>';
	}

}
*/
function web_login (){
 
global $UserID,$koneksi_db;
$user          = $_POST['username'];
$password      = md5($_POST['password']);
$password2	   = $_POST['password'];
$query         = $koneksi_db->sql_query ("SELECT * FROM user WHERE username='$user' AND password='$password'");
$total         = $koneksi_db->sql_numrows($query);
$data          = $koneksi_db->sql_fetchassoc($query);

 
	if ($total > 0 && $user == $data['username'] && $password == $data['password']){
	//if ($data['level'] == 'MAHASISWA'){
	//header("location: http://atvi.ac.id/sia/index.html");	
	//}else{ 	
	if ($data['angket']!='Y'){
		#header ("location:index.php?m=angket");
		$_SESSION['idm'] = $data['userid'];
		echo "<script>window.location.href='index.php?m=angket';</script>";
		exit;
	}
	if($data['aktif']!='N'){
	if ($data['angket']!='Y'){
		#header ("location:index.php?m=angket");
 		$_SESSION['idm'] = $data['userid'];
		echo "<script>window.location.href='index.php?m=angket';</script>";
		exit;
	}		
	
	if ($password2!=$user){
	//echo write_mysql_log("Login Sukses");
	//session_is_registered ('UserID') ;
	$_SESSION['UserID']= $data['userid'];
	//session_is_registered ('UserName') ;
	$_SESSION['UserName']= $data['username'];
	$_SESSION['Name']= $data['nama'];
	//session_is_registered ('PassWord') ;
	$_SESSION['PassWord']= $data['password'];
	//session_is_registered ('Email') ;
	$_SESSION['Email']= $data['email'];
	//session_is_registered ('Level') ;
	$_SESSION['Level']= $data['level'];

	#header ("location:index.php");
	echo "<script>window.location.href='index.php';</script>";
	exit;
	
	} else {
			//session_is_registered ('UserID') ;
			$_SESSION['UserID']= $data['userid'];
			//session_is_registered ('UserName') ;
			$_SESSION['UserName']= $data['username'];
			$_SESSION['Name']= $data['nama'];
			//session_is_registered ('PassWord') ;
			$_SESSION['PassWord']= $data['password'];
			//session_is_registered ('Email') ;
			$_SESSION['Email']= $data['email'];
			//session_is_registered ('Level') ;
			$_SESSION['Level']= $data['level'];

			#header ("location:index.php?m=36");
			 echo "<script>window.location.href='index.php';</script>";
			exit;
	}
	}else{
		echo '<div  class="alert alert-danger alert-dismissable"  >ANDA BELUM MELAKUKAN PEMBAYARAN ATAU SEDANG CUTI ATAU TELAH DINYATAKAN LULUS </div>';
	}
	}else {
	return '<div  class="alert alert-danger alert-dismissable"  >User atau Password Salah </div>';
	}

}


function cek_login (){
    global $UserID;
    if (($_SESSION['UserID']) && isset ($_SESSION['UserID']) && !empty ($_SESSION['UserID'])){
    return true;
    }else {
        return false;
    }
}


function logout (){
unset($_SESSION['UserID']);
unset($_SESSION['UserName']);
unset($_SESSION['Name']);
unset($_SESSION['Email']);
unset($_SESSION['Level']);
unset($_SESSION['prodi']);
unset($_SESSION['semester']);
unset($_SESSION['kelas']);
return '<br/><small><font color="#FF0000">Logout Sukses</font></small>';
//header ("location:index.php");
//exit;

}


function cekVersi (){
    global $versidemo;
    if ($versidemo=="0"){
    	return true;
    }else {
		echo '<div class="error" style="width:70%">Maaf.. Anda tidak diperkenankan mengakses halaman ini di Versi ini (Demo Aplikasi)</div>';
        return false;
    }
}

function Terbilang($satuan){
$huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
if ($satuan < 12)
return " " . $huruf[$satuan];
elseif ($satuan < 20)
return Terbilang($satuan - 10) . " belas";
elseif ($satuan < 100)
return Terbilang($satuan / 10) . " puluh" . Terbilang($satuan % 10);
elseif ($satuan < 200)
return " seratus" . Terbilang($satuan - 100);
elseif ($satuan < 1000)
return Terbilang($satuan / 100) . " ratus" . Terbilang($satuan % 100);
elseif ($satuan < 2000)
return " seribu" . Terbilang($satuan - 1000);
elseif ($satuan < 1000000)
return Terbilang($satuan / 1000) . " ribu" . Terbilang($satuan % 1000);
elseif ($satuan < 1000000000)
return Terbilang($satuan / 1000000) . " juta" . Terbilang($satuan % 1000000);
elseif ($satuan >= 1000000000)
echo "Hasil terbilang tidak dapat di proses karena nilai uang terlalu besar!"; 
}

function Terbilanghari($harian){
$harii = array("", "SENIN", "SELASA", "RABU", "KAMIS", "JUMAT", "SABTU", "MINGGU");
if ($harian < 7)
return " " . $harii[$harian];
}

function TampilkanJudul($str='') {
  //echo "<p><font face='Times New Roman' size=6 color=gray>$str</font></p>";
  echo "<div class=Judul>$str</div>";
}
?>