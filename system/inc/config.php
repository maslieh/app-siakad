<?php
date_default_timezone_set('Asia/Jakarta');
ini_set( "display_errors", true );
define( "HOST", "localhost" );
//nama database
define( "DATABASE_NAME", "k4645222_siakadssr" );
define( "DB_USERNAME", "k4645222_ssr" );

define( "PORT", 3306);
//password mysql
define( "DB_PASSWORD", "ssradmin@@" );
//dir admin
//define( "DIR_ADMIN", "importer/aplikasi/");
//main directory
//define( "DIR_MAIN", "importer/");

//define ('SITE_ROOT', $_SERVER['DOCUMENT_ROOT']."/".DIR_MAIN);

define('DB_CHARACSET', 'utf8');

require_once ('Database.php');
require_once ('Datatable.php');
require_once ('My_pagination.php');
require_once ('url.php');
require_once ('DTable.php');
require_once ('Table_Clean.php');
require_once ('feeder_function.php');
$db=new Database("mysql");

//postgre
//$pgs=new Database("pgsql");

//pagination
$pg=New My_pagination();
$dtable = new TableData();

$new_table = new DTable("mysql");
$clean = new Table_Clean("mysql");

function handleException( $exception ) {
  echo  $exception->getMessage();
}

set_exception_handler( 'handleException' );


?>
