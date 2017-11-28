<?php
	header("Content-Type: application/json; charset=UTF-8;");
?>
<? include '../lib/header.php'; ?>
<?
	if($method == "POST") {
		$seq = $_POST['seq'];

		$query = "SELECT answerText, lectureOpenSeq, reportSeq FROM nynReportAnswer WHERE seq='".$seq."'";
		$result = mysql_query($query);
		$rs = mysql_fetch_array($result);
		$answerText = strlen($rs[answerText]);
		$lectureOpenSeq = $rs[lectureOpenSeq];
		$reportSeq = $rs[reportSeq];

		$queryA = "SELECT answerText, userID FROM nynReportAnswer WHERE seq <> '".$seq."' AND lectureOpenSeq='".$lectureOpenSeq."' AND reportSeq='".$reportSeq."' AND answerType='text'";
		$resultA = mysql_query($queryA);

		while($rsA = mysql_fetch_array($resultA)) {
			$userID = $rsA[userID];
			$answerText2 = strlen($rsA[answerText]);
			$answerTextR = $answerText - $answerText2;

			if($answerTextR > -20 && $answerTextR < 20) {
				echo '{"result" : "ID : '.$userID.' 님과 모사답안 가능성 있습니다."}';
				exit;
			}
		}
		echo '{"result" : "정상입니다."}';
		exit;
	}
?>