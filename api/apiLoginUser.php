<?php
	header('Content-Type:application/json; charset=utf-8');
?>
<? include '../lib/header.php'; ?>
<?
	if($method == "POST") {
		$agreement = $_POST['agreement'];
		$mobile01 = $_POST['mobile01'];
		$mobile02 = $_POST['mobile02'];
		$mobile03 = $_POST['mobile03'];
		$email01 = $_POST['email01'];
		$email02 = $_POST['email02'];
		$pwd = $_POST['pwd'];
		$zipCode = $_POST['zipCode'];
		$address01 = $_POST['address01'];
		$address02 = $_POST['address02'];

		//비밀번호 수정인 경우 암호화 처리 후 수정 요청
		if($pwd != "") {
			$hash = password_hash($pwd, PASSWORD_DEFAULT);
			$pwdQ = ", pwd='".$hash."'";
		}

		$query = "UPDATE nynMember 
							SET agreement='".$agreement."', 
									agreeDate='".$inputDate."',
									mobile01='".$mobile01."',
									mobile02='".$mobile02."',
									mobile03='".$mobile03."',
									zipCode='".$zipCode."',
									address01='".$address01."',
									address02='".$address02."',
									email01='".$email01."',
									email02='".$email02."',
									infoUpdate='".$inputDate."'".$pwdQ." 
							WHERE userID='".$_SESSION[loginUserID]."'";
		$result = mysql_query($query);

		if($result){
			echo '{"result" : "success"}';
		} else {
			echo '{"result" : "error"}';
		}
		exit;

	} else if($method == "GET") {
		$query = "SELECT A.*, B.value02 
							FROM nynMember A 
							LEFT OUTER 
							JOIN nynCategory B 
							ON A.userLevel=B.value01 AND B.division=
							(SELECT seq FROM nynCategory WHERE value01='userLevel')
							WHERE A.userID='".$_SESSION[loginUserID]."'";
		$result = mysql_query($query);
		$adminapi = array(); //DB 값이 없는 경우 배열선언 부분

		if($_SESSION[loginUserID]) {
			while($rs = mysql_fetch_array($result)) {
				$adminapi[seq] = $rs[seq];
				$adminapi[userID] = $rs[userID];
				$adminapi[userName] = $rs[userName];
				$adminapi[birth] = $rs[birth];
				$adminapi[sex] = $rs[sex];
				$adminapi[companyCode] = $rs[companyCode];
				$adminapi[phone01] = $rs[phone01];
				$adminapi[phone02] = $rs[phone02];
				$adminapi[phone03] = $rs[phone03];
				$adminapi[mobile01] = $rs[mobile01];
				$adminapi[mobile02] = $rs[mobile02];
				$adminapi[mobile03] = $rs[mobile03];
				$adminapi[email01] = $rs[email01];
				$adminapi[email02] = $rs[email02];
				$adminapi[zipCode] = $rs[zipCode];
				$adminapi[address01] = $rs[address01];
				$adminapi[address02] = $rs[address02];
				$adminapi[userLevel][userGrade] = $rs[value02];
				$adminapi[userLevel][userLevel] = $rs[userLevel];

				$queryLV="SELECT A.oldLevel, A.inputDate, B.value02
									FROM nynLevelChange A
									LEFT OUTER 
									JOIN nynCategory B
									ON A.oldLevel=B.value01 AND B.division=
									(SELECT seq FROM nynCategory WHERE value01='userLevel')
									WHERE A.userID='".$rs[userID]."' ORDER BY A.seq DESC LIMIT 1";
				$resultLV = mysql_query($queryLV);

				$adminapi[userLevel][oldGrade] = mysql_result($resultLV,0,'value02');
				$adminapi[userLevel][oldLevel] = mysql_result($resultLV,0,'oldLevel');
				$adminapi[userLevel][changeDate] = mysql_result($resultLV,0,'inputDate');
				$adminapi[smsReceive] = $rs[smsReceive];
				$adminapi[emailReceive] = $rs[emailReceive];
				$adminapi[memo] = $rs[memo];
				$adminapi[loginIP] = $rs[loginIP];
				$adminapi[loginTime] = $rs[loginTime];
				$adminapi[infoUpdate] = $rs[infoUpdate];
				$adminapi[inputDate] = $rs[inputDate];
				$adminapi[agreement] = $rs[agreement];
				$adminapi[agreeDate] = $rs[agreeDate];
				$adminapi[nowTime] = $inputDate;
			}
		} else {
			$adminapi[userID] = '';
		}

		$json_encoded = json_encode($adminapi);
		print_r($json_encoded);
	}
		
	@mysql_close();
?>