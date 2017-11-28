<?php
		header('Content-Type:application/json; charset=utf-8');
?>
<? include '../lib/header.php'; ?>
<?
	if($method == "POST") { // 등록 및 수정
		$contentsCode = $_POST['contentsCode'];
		$originCode = $_POST['originCode'];

		if($_POST['testSeq'] != "") {
   		$loopNum = 1;
			$_POST['testSeq'][0] = $_POST['testSeq'];
		} else {
			$loopNum = count($_POST['testSeq']);
		}

		if($contentsCode != "") {
			$queryD = "DELETE FROM nynTestMapping 
								 WHERE testType = '".$testType."' 
								 AND contentsCode='".$contentsCode."' 
								 AND testSeq IN 
								 (SELECT seq FROM nynTest WHERE testType = '".$testType."' AND originCode='".$originCode."')";
			$resultD = mysql_query($queryD);

				for($i=0; $i<$loopNum; $i++) {
					$testSeq = $_POST['testSeq'][$i];
					$query = "INSERT INTO nynTestMapping SET
											contentsCode='".$contentsCode."', 
											testType = '".$testType."', 
											testSeq=".$testSeq;
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
		$testType = $_GET['testType'];

			$qCon = "SELECT contentsName FROM nynContents WHERE contentsCode='".$contentsCode."'";
			$rCon = mysql_query($qCon);

			$query = "SELECT * FROM nynTestMapping WHERE testType='".$testType."' AND contentsCode='".$contentsCode."' ORDER BY testSeq";
			$result = mysql_query($query);
			$count = mysql_num_rows($result);
			$a = 0;
			$adminapi = array(); //DB 값이 없는 경우 배열선언 부분


			$adminapi[contentsCode] = $contentsCode;
			$adminapi[contentsName] = mysql_result($rCon,0,'contentsName');
			$adminapi[testType] = $testType;
			$adminapi[totalCount] = "$count"; //총 개시물 수

			while($a<$count && $count>0) {
				$adminapi[testMapping][$a][seq] = mysql_result($result,$a,'seq');
				$adminapi[testMapping][$a][contentsCode] = $contentsCode;
				$adminapi[testMapping][$a][testSeq] = mysql_result($result,$a,'testSeq');
				$a++;
			}
			
			$json_encoded = json_encode($adminapi);
			print_r($json_encoded);
	}
		
	@mysql_close();
?>