<?php
	header('Content-Type:application/json; charset=utf-8');
?>
<? include '../lib/header.php'; ?>
<?
	$seq = $_POST['seq'];
	$pwd = generateRenStr(6,P);
	$hash = password_hash($pwd, PASSWORD_DEFAULT);

	$query = "UPDATE nynMember 
						SET infoUpdate='".$inputDate."',
								pwd='".$hash."'
						WHERE seq=".$seq;
	$result = mysql_query($query);

	//이후 문자 또는 이메일 발송 처리

	$queryE = "SELECT mobile01, mobile02, mobile03, email01, email02 FROM nynMember WHERE seq=".$seq;
	$resultE = mysql_query($queryE);
	$rs = mysql_fetch_array($resultE);

	$toMail = $rs[email01]."@".$rs[email02];
	$fromMail = "admin@nayanet.kr";

	$subject = "[이상에듀] 임시 비밀번호 안내드립니다.";

	$content = "임시 비밀번호는 ".$hash." 입니다. <br />로그인 후 새로운 비밀번호를 설정 하시기 바랍니다.";
	//$filepath = $_SERVER["DOCUMENT_ROOT"]."/member/join_mail.php";
	$filepath = "";
	$var = "";

	mail_fsend($toMail, $fromMail, $subject, $content, '', '', '', $filepath, $var);

	if($result) {
		echo "success";
	} else {
		echo "error";
	}
?>