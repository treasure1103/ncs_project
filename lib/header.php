<?
	include '../lib/dbConnect.php'; 
	include '../lib/global.php'; 
	include '../lib/function.php'; 
	include '../lib/passwordHash.php';
 	//session_set_save_handler ("sess_open", "sess_close", "sess_read", "sess_write_renew", "sess_destroy", "sess_gc");
	session_set_cookie_params( 0, "/", ".".$_siteURL );
	ini_set('session.cache_limiter' ,'nocache, must-revalidate-revalidate');
	session_start();
	//header('P3P: CP="NOI CURa ADMa DEVa TAIa OUR DELa BUS IND PHY ONL UNI COM NAV INT DEM PRE"');
?>
