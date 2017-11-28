<?php
	header('Content-Type:application/json; charset=utf-8');
?>
<? include '../lib/header.php'; ?>
<?
	if($method == "POST") {
		$agreement = $_POST['agreement'];
		$privacy = $_POST['privacy'];
		$caution = $_POST['caution'];
		$acs = $_POST['acs'];
		$midCopy = $_POST['midCopy'];
		$testCopy = $_POST['testCopy'];
		$reportCopy = $_POST['reportCopy'];

		if($agreement) {
				$agreementQ = "agreement='".addslashes(trim($agreement))."'";
		}
		if($privacy) {
				$privacyQ = "privacy='".addslashes(trim($privacy))."'";
		}
		if($caution) {
				$cautionQ = "caution='".addslashes(trim($caution))."'";
		}
		if($acs) {
				$acsQ = "acs='".addslashes(trim($acs))."'";
		}
		if($midCopy) {
				$midCopyQ = "midCopy='".addslashes(trim($midCopy))."'";
		}
		if($testCopy) {
				$testCopyQ = "testCopy='".addslashes(trim($testCopy))."'";
		}
		if($reportCopy) {
				$reportCopyQ = "reportCopy='".addslashes(trim($reportCopy))."'";
		}

		$query = "UPDATE nynSiteInfo SET ".$agreementQ.$privacyQ.$cautionQ.$acsQ.$midCopyQ.$testCopyQ.$reportCopyQ;
		$result = mysql_query($query);

		if($result) {
			echo '{"result" : "success"}';
		} else {
			echo '{"result" : "error"}';
		}
		exit;

	} else if($method == "GET") { // 정보 불러옴
		$query = "SELECT * FROM nynSiteInfo";
		$result = mysql_query($query);
		$adminapi = array(); //DB 값이 없는 경우 배열선언 부분

		while($rs = mysql_fetch_array($result)) {
			$adminapi[agreement] = stripslashes($rs[agreement]);
			$adminapi[privacy] = stripslashes($rs[privacy]);
			$adminapi[caution] = stripslashes($rs[caution]);
			$adminapi[acs] = stripslashes($rs[acs]);
			$adminapi[midCopy] = stripslashes($rs[midCopy]);
			$adminapi[testCopy] = stripslashes($rs[testCopy]);
			$adminapi[reportCopy] = stripslashes($rs[reportCopy]);
		}

		$json_encoded = json_encode($adminapi);
		print_r($json_encoded);
	}
?>