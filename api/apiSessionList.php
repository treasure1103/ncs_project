<?php
	header('Content-Type:application/json; charset=utf-8');
?>
<? include '../lib/header.php'; ?>
<?
	if($method == "POST") { // 회원사 등록 및 수정은 POST로 받는다.
		$userID = $_POST['userID'];

		$query = "UPDATE nynSession SET security='X' WHERE userID='".$userID."' AND security='O'";
		$result = mysql_query($query);

		if($result){
			echo '{"result" : "success"}';
		} else {
			echo '{"result" : "error"}';
		}
		exit;

	} else {

		$query = "SELECT A.*, B.userName FROM nynSession AS A 
							LEFT OUTER 
							JOIN nynMember AS B
							ON A.userID=B.userID
							WHERE A.security='O' ORDER BY A.inputDate DESC";
		$result = mysql_query($query);
		$adminapi = array();
		$count = mysql_num_rows($result);
		$a=0;
		
		$adminapi[totalCount] = $count;

		while($rs = mysql_fetch_array($result)) {
			$adminapi[session][$a][userID] = $rs[userID];
			$adminapi[session][$a][userName] = $rs[userName];
			$adminapi[session][$a][ip] = $rs[remoteIP];
			$adminapi[session][$a][inputDate] = $rs[inputDate];
			$a++;
		}

		$json_encoded = json_encode($adminapi);
		print_r($json_encoded);
	}
?>