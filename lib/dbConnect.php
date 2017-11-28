<?
  header("Cache-Control: no-cache"); //# 캐쉬를 사용 안함
  header("Pragma: no-cache");  //# 캐시를 사용 안함
	//$hostName="218.232.94.238";
	$hostName="192.168.2.14";
	$DBuserName="ncscenter";
	$dbName="ncscenter";
	$userPassword="ncs!@1004$";
	$bd = mysql_connect($hostName, $DBuserName, $userPassword) or die("db connect error");
				mysql_select_db($dbName, $bd) or die("db connect error db");
				mysql_query("set session character_set_connection=utf8;");
				mysql_query("set session character_set_results=utf8;");
				mysql_query("set session character_set_client=utf8;");
	$emma_host=$hostName;
	$emma_user=$DBuserName;
	$emma_db="emma";
	$emma_passwd=$userPassword;
?>