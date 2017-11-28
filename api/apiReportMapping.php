<?php
	header('Content-Type:application/json; charset=utf-8');
?>
<? include '../lib/header.php'; ?>
<?
	if($method == "POST") { // 등록 및 수정
		$contentsCode = $_POST['contentsCode'];
		$originCode = $_POST['originCode'];

		if($_POST['seq'] != "") {
   		$loopNum = 1;
			$_POST['reportSeq'][0] = $_POST['seq'];
		} else {
			$loopNum = count($_POST['reportSeq']);
		}

		if($contentsCode != "") {
			$queryD = "DELETE FROM lmsReportMapping WHERE contentsCode='".$contentsCode."' AND reportSeq IN (SELECT seq FROM lmsReport WHERE originCode='".$originCode."')";
			$resultD = mysql_query($queryD);

				for($i=0; $i<$loopNum; $i++) {
					$reportSeq = $_POST['reportSeq'][$i];
					$query = "insert into lmsReportMapping set
											contentsCode='".$contentsCode."', 
											reportSeq=".$reportSeq;
					$result = mysql_query($query);
				}
		}

		if($result) {
			echo "success";
		} else {
			echo "error";
		}
		exit;

	} else if($method == "GET") { // json 출력
		$contentsCode = $_GET['contentsCode'];
		
			$qCon = "SELECT contentsName FROM lmsContents WHERE contentsCode='".$contentsCode."'";
			$rCon = mysql_query($qCon);

			$query = "SELECT * FROM lmsReportMapping WHERE contentsCode='".$contentsCode."' ORDER BY reportSeq";
			$result = mysql_query($query);
			$count = mysql_num_rows($result);
			$a = 0;
			$adminapi = array(); //DB 값이 없는 경우 배열선언 부분

			$adminapi[testMappingInfo][contentsCode] = $contentsCode;
			$adminapi[testMappingInfo][contentsName] = mysql_result($rCon,0,'contentsName');
			$adminapi[testMappingInfo][totalCount] = "$count"; //총 개시물 수

			while($a<$count && $count>0) {
				$adminapi[testMapping][$a][seq] = mysql_result($result,$a,'seq');
				$adminapi[testMapping][$a][contentsCode] = $contentsCode;
				$adminapi[testMapping][$a][reportSeq] = mysql_result($result,$a,'reportSeq');
				$a++;
			}
			
			$json_encoded = json_encode($adminapi);
			print_r($json_encoded);
	}
?>