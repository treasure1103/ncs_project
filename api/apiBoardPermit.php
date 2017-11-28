<?php
	header('Content-Type:application/json; charset=utf-8');
?>
<? include '../lib/header.php'; ?>
<?
	if($method == "POST") {
		$boardCode = $_POST['boardCode'];
		$listPermit = $_POST['listPermit'];
		$viewPermit = $_POST['viewPermit'];
		$writePermit = $_POST['writePermit'];
		$replyPermit = $_POST['replyPermit'];
		$deletePermit = $_POST['deletePermit'];
		$commentPermit = $_POST['commentPermit'];
		$secretPermit = $_POST['secretPermit'];
		$topPermit = $_POST['topPermit'];
		$userID = $_POST['userID'];
		$memo = $_POST['memo'];

		if($_SESSION['loginUserLevel'] > 4) { //관리자만 수정 가능
			echo "error";
			exit;
		}

		if($listPermit == "") {
			$listPermit = "10";
		}
		if($viewPermit == "") {
			$viewPermit = "10";
		}
		if($writePermit == "") {
			$writePermit = "10";
		}
		if($replyPermit == "") {
			$replyPermit = "10";
		}
		if($deletePermit == "") {
			$deletePermit = "10";
		}
		if($commentPermit == "") {
			$commentPermit = "10";
		}
		if($secretPermit == "") {
			$secretPermit = "10";
		}
		if($topPermit == "") {
			$topPermit = "10";
		}

			$queryQ =  "listPermit=".$listPermit.",
									viewPermit=".$viewPermit.",
									writePermit=".$writePermit.",
									replyPermit=".$replyPermit.",
									deletePermit=".$deletePermit.",
									commentPermit=".$commentPermit.",
									secretPermit=".$secretPermit.",
									topPermit=".$topPermit."";

				if($boardCode == "") {
					$query = "INSERT INTO nynBoardPermit SET boardCode=".$boardCode.", inputDate='".$inputDate."', ".$queryQ;
				} else {
					$query = "UPDATE nynBoardPermit SET ".$queryQ." WHERE boardCode=".$boardCode;
				}
					$result = mysql_query($query);

				if($result){
					echo "success";
				} else {
					echo "error";
				}
					exit;

	} else if($method == "GET") {
			$boardCode = $_GET['boardCode'];

			if($boardCode != "") {
				$qBC = " AND boardCode=".$boardCode;
			}
			if($seq != "") {
				$qSeq = " AND seq=".$seq;
			}
			$qSearch = $qBC.$qSeq;

			$query = "SELECT * FROM nynBoardPermit WHERE seq <> 0 ".$qSearch." ORDER BY boardCode ";
			$result = mysql_query($query);
			$count = mysql_num_rows($result);
			$a = 0;
			$adminapi = array(); //DB 값이 없는 경우 배열선언 부분

			while($rs = mysql_fetch_array($result)) {
				$adminapi[boardPermit][$a][seq] = $rs[seq];
				$adminapi[boardPermit][$a][boardCode] = $rs[boardCode];
				$adminapi[boardPermit][$a][listPermit] = $rs[listPermit];
				$adminapi[boardPermit][$a][viewPermit] = $rs[viewPermit];
				$adminapi[boardPermit][$a][writePermit] = $rs[writePermit];
				$adminapi[boardPermit][$a][replyPermit] = $rs[replyPermit];
				$adminapi[boardPermit][$a][deletePermit] = $rs[deletePermit];
				$adminapi[boardPermit][$a][commentPermit] = $rs[commentPermit];
				$adminapi[boardPermit][$a][secretPermit] = $rs[secretPermit];
				$adminapi[boardPermit][$a][topPermit] = $rs[topPermit];
				$a++;
			}

			$json_encoded = json_encode($adminapi);
			print_r($json_encoded);
		}
		
	@mysql_close();
?>