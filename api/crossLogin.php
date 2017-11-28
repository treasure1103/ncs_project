<?
	include '../lib/header.php';

	if($_SESSION['loginUserID'] != "") {
		$query = "SELECT loginTime FROM nynMember WHERE userID='".$_SESSION['loginUserID']."'";
		$result = mysql_query($query);
		$rsC = mysql_fetch_array($result);
		$loginTime = $rsC['loginTime'];
		$inputDate = date('Y-m-d H:i:s'); // 현재 시간
		$time = time();
		$loginCheckTime = date('Y-m-d H:i:s', strtotime('-8 hours', $time));

		if($loginTime < $loginCheckTime) { // 로그인 한지 8시간이 지나면 강제 로그아웃.
			session_destroy();
			echo '{"result" : "로그인 한지 8시간이 경과하였습니다. 데이터 안정성을 위해 다시 로그인 해주시기 바랍니다."}';
			exit;
		}

		$SQL = "SELECT count(seq) cnt  FROM nynSession WHERE sesskey='".$_COOKIE["PHPSESSID"]."' order by seq desc limit 1 ";
		$RS = mysql_query($SQL);
		$R = mysql_fetch_assoc($RS);
		if($R["cnt"] == 0){
			session_destroy();
			/// 스크립터에서 실행하는걸로 변경
			echo '{"result" : "다른 기기에서 로그인되어 자동으로 로그아웃됩니다."}';
			exit;

		} else {
			echo '{"result" : "success"}';
			exit;
		}

	} else {
		echo '{"result" : "success"}';
		exit;
	}
		
	@mysql_close();
?>
