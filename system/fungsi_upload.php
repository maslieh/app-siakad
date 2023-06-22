<?php
// Upload gambar untuk berita

function UploadGambar($fupload_name,$dire){
  //direktori gambar
  $vdir_upload = $dir;
  $vfile_upload = $vdir_upload . $fupload_name;

	//$ext_image = $tipe_file; // mendapatkan extension file yang di upload
	$ext_image  = $_FILES['fupload']['type'];


 //Simpan gambar dalam ukuran sebenarnya
  move_uploaded_file($_FILES["fupload"]["tmp_name"], $vfile_upload);
  
 // menyesuaikan type gambar yang akan di resize 
 switch($ext_image)
 {
 case 'image/jpg':
 case 'image/jpeg':
 $im_src = imagecreatefromjpeg($vfile_upload);
 break;
 case 'image/gif':
 $im_src = imagecreatefromgif($vfile_upload);
 break;
 case 'image/png':
 $im_src = imagecreatefrompng($vfile_upload);
 break;
 default:
 $im_src = false;
 break;
 }

 // mengambil ukuran asli dari gambar width dan height 
 $src_width = imageSX($im_src);
 $src_height = imageSY($im_src);

  //Simpan dalam versi small 110 pixel
  //Set ukuran gambar hasil perubahan
  $dst_width = 110;
  $dst_height = ($dst_width/$src_width)*$src_height;

 // Proses pembuatan image
 $im = imagecreatetruecolor($dst_width,$dst_height);
 imagecopyresampled($im, $im_src, 0, 0, 0, 0, $dst_width, $dst_height, $src_width, $src_height);

 // nah ini proses penyimpanan image hasil ke folder yang sama berdasarkan extensinya, trus hasil gambar gw kasi nama hasil_namafile.pg 
 switch($ext_image)
 {
 case 'image/jpg':
 case 'image/jpeg':
 imagejpeg($im,$vdir_upload . "small_" .  $fupload_name);
 break;
 case 'image/gif':
 imagegif($im,$vdir_upload . "small_" .  $fupload_name);
 break;
 case 'image/png':
 imagepng($im,$vdir_upload . "small_" .  $fupload_name);
 break;
 default:

 break;
 }
 
}


// Upload file untuk download file
function UploadFile($fupload_name){
  //direktori file
  $vdir_upload = "../../../files/";
  $vfile_upload = $vdir_upload . $fupload_name;

  //Simpan file
  move_uploaded_file($_FILES["fupload"]["tmp_name"], $vfile_upload);
}


?>
