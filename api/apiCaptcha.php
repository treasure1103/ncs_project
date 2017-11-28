<?php
	header('Content-Type:application/json; charset=utf-8');
?>
<? include '../lib/header.php'; ?>
<?
	$values = explode(",",$_POST['class_agent_pk']);
	$lectureOpenSeq = $values[1];
	$contentsCode = $values[0];
	$evalCD = $_POST['eval_cd'];

	switch ($evalCD) {
		case "01": //진도
			echo '{"result" : "success"}';
			exit;
			break;

		case "02": //평가시험
			$updateQ = "testCaptchaTime='".$inputDate."'";
			break;

		case "03": //과제
			$updateQ = "reportCaptchaTime='".$inputDate."'";
			break;

		case "04": //진행평가
			$updateQ = "midCaptchaTime='".$inputDate."'";
	}

	$query = "UPDATE nynStudy SET ".$updateQ." WHERE lectureOpenseq='".$lectureOpenSeq."' AND contentsCode='".$contentsCode."' AND userID='".$_SESSION['loginUserID']."'";
	$result = mysql_query($query);

	if($result) {
		echo '{"result" : "success"}';
	} else {
		echo '{"result" : "error"}';
	}	
	exit;
?>