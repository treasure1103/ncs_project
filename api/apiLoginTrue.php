<?php
	header('Content-Type:application/json; charset=utf-8');
?>
<? include '../lib/header.php'; ?>
<?
	if($method == "POST") { // 로그인 시 아이디 비번 체크
			$userID = $_POST['userID'];
			$pwd = $_POST['pwd'];

			$queryI = "SELECT A.userID, 
									A.pwd, 
									A.userName, 
									A.userLevel, 
									B.value02 AS userLevelName, 
									B.value03 AS userType
						FROM nynMember A 
						LEFT OUTER 
						JOIN nynCategory B
						ON A.userLevel=B.value01 
						WHERE A.userID='".$userID."' 
						AND B.division=(SELECT seq FROM nynCategory WHERE value01='userLevel') 
						AND A.userDelete <> 'Y'";
			$resultI = mysql_query($queryI);
			$rsI = mysql_fetch_assoc($resultI);
			$originalPwd = $rsI[pwd];

				if(password_verify($pwd, $originalPwd)){
					$_SESSION['loginUserID'] = $rsI[userID];
					$_SESSION['loginUserName'] = $rsI[userName];
					$_SESSION['loginUserLevel'] = $rsI[userLevel];
					$_SESSION['loginUserLevelName'] = $rsI[userLevelName];

					echo "success";

				} else { 
					echo "error";
				}
				exit;
	}
		
	@mysql_close();

?>