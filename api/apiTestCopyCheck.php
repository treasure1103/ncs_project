<?php
	header("Content-Type: application/json; charset=UTF-8;");
?>
<? include '../lib/header.php'; ?>
<?
	if($method == "POST") {
		$seq = $_POST['seq'];

		$query = "SELECT userTextAnswer, lectureOpenSeq, testSeq FROM nynTestAnswer WHERE seq='".$seq."'";
		$result = mysql_query($query);
		$rs = mysql_fetch_array($result);
		$userTextAnswer = strlen($rs['userTextAnswer']);
		$lectureOpenSeq = $rs['lectureOpenSeq'];
		$testSeq = $rs['testSeq'];

		$queryA = "SELECT userTextAnswer, userID FROM nynTestAnswer WHERE seq <> '".$seq."' AND lectureOpenSeq='".$lectureOpenSeq."' AND testSeq='".$testSeq."' AND examType='C'";
		$resultA = mysql_query($queryA);

		while($rsA = mysql_fetch_array($resultA)) {
			$userID = $rsA['userID'];
			$answerText2 = strlen($rsA['userTextAnswer']);
			$answerTextR = $userTextAnswer - $answerText2;

			if($answerTextR > -20 && $answerTextR < 20) {
				echo '{"result" : "ID : '.$userID.' 님과 모사답안 가능성 있습니다."}';
				exit;
			}
		}
		echo '{"result" : "정상입니다."}';
		exit;
	}
?>