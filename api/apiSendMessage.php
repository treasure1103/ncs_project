<?php
	header('Content-Type:application/json; charset=utf-8');
?>
<? include '../lib/header.php'; ?>
<?
	if($method == "POST") { // 회원사 등록 및 수정은 POST로 받는다.
		$seq = $_POST['seq'];
		$message = $_POST['message'];

		$query = "UPDATE nynSendMessage SET message='".$message."' WHERE seq='".$seq."'";
		$result = mysql_query($query);

		if($result){
			echo '{"result" : "success"}';
		} else {
			echo '{"result" : "error"}';
		}
		exit;

	} else {
		$seq = $_GET['seq'];
		if($seq) {
			$queryQ = "WHERE seq='".$seq."'";
		}

		$query = "SELECT * FROM nynSendMessage ".$queryQ." ORDER BY seq ASC";
		$result = mysql_query($query);
		$adminapi = array();
		$a=0;

		while($rs = mysql_fetch_array($result)) {
			switch ($rs[sendType]) {
					case "start":
							$typeName = "학습시작";
							break;

					case "0":
							$typeName = "0%미만";
							break;

					case "30":
							$typeName = "30%미만";
							break;

					case "50":
							$typeName = "50%미만";
							break;

					case "79":
							$typeName = "79%미만";
							break;

					case "final":
							$typeName = "최종독려";
							break;

					case "end":
							$typeName = "종강일";
							break;
					
					case "etc":
							$typeName = "기타";
							break;
			}

			$adminapi[sendLog][$a][seq] = $rs[seq];
			$adminapi[sendLog][$a][device] = $rs[device];
			$adminapi[sendLog][$a][sendType] = $typeName;
			$adminapi[sendLog][$a][message] = $rs[message];
			$a++;
		}

		$json_encoded = json_encode($adminapi);
		print_r($json_encoded);
	}
?>