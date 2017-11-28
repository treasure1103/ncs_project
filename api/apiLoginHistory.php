<?php
  header('Content-Type:application/json; charset=utf-8');
  include '../lib/header.php';
  if($_SESSION[loginUserLevel] > 8) { // 관리자만 접근(열람)가능
    echo "error";
    exit;
  }

	$userID = $_GET['userID'];

	$query = "SELECT * FROM nynMemberHistory WHERE userID ='".$userID."' ORDER BY loginTime DESC";
	$result = mysql_query($query);
  $allPost = mysql_num_rows($result);
  $adminapi = array();
  $adminapi[totalCount] = $allPost; //총 개시물 수
  $a = 0;


	while($rs = mysql_fetch_array($result)) {
		$adminapi[loginHistory][$a][loginTime]      = $rs['loginTime'];       // 로그인시간
    $adminapi[loginHistory][$a][loginIP]        = $rs['loginIP'];         // 로그인아이피
    $adminapi[loginHistory][$a][device]         = $rs['device'];          // 디바이스정보
    $adminapi[loginHistory][$a][os]             = $rs['OS'];              // 접속 디바이스 OS
    $adminapi[loginHistory][$a][browser]        = $rs['browser'];         // 접속 브라우저
    $adminapi[loginHistory][$a][browserVersion] = $rs['browserVersion'];  // 접속 브라우저 버전
		$a++;
	}

	$json_encoded = json_encode($adminapi);
	print_r($json_encoded);

		
	@mysql_close();
?>
