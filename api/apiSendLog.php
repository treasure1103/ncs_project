<?php
	header('Content-Type:application/json; charset=utf-8');
?>
<? include '../lib/header.php'; ?>
<?
		if(!$_SESSION['loginUserLevel']){
			$userLevelCheck = 10;
		} else {
			$userLevelCheck = $_SESSION['loginUserLevel'];
		}

		if($userLevelCheck >= 7) {
			echo "error";
			exit;
		}

		$sendMethod = $_GET['sendMethod'];
		$companyCode = $_GET['companyCode'];
		$companyName = $_GET['companyName'];
		$userID = $_GET['userID'];
		$userName = $_GET['userName'];
		$startDate = $_GET['startDate'];
		$endDate = $_GET['endDate'];
		$gubun = $_GET['gubun'];
		$seq = $_GET['seq'];

		if($list == "") {
			$list = 10;
		}
		if($page == "") {
			$page = 1;
		}
		if($sortType == "") {
			$sortType = "A.seq";
		}
		if($sortValue == "") {
			$sortValue = "DESC";
		}
		if($seq != "") {
			$qSeq = " AND A.seq='".$seq."'";
		}
		if($sendMethod != "") {
			$qSendMethod = " AND A.sendMethod='".$sendMethod."'";
		}
		if($companyCode != "") {
			$qCompanyCode = " AND A.companyCode='".$companyCode."'";
		}
		if($companyName != "") {
			$qCompanyName = " AND C.companyName LIKE '%".$companyName."%'";
		}
		if($userID != "") {
			$qUserID = " AND A.userID='".$userID."'";
		}
		if($userName != "") {
			$qUserName = " AND B.userName LIKE '%".$userName."%'";
		}
		if($startDate != "" && $endDate != "") {
			$startDate = $startDate." 00:00:00";
			$endDate = $endDate." 23:59:59";
			$qSendDate = " AND (A.sendDate BETWEEN '".$startDate."' AND '".$endDate."')";
		}

		switch ($gubun) {
				case "tutor":
						$qGubun = " AND A.sendMethod IN ('smsTutor', 'emailTutor') ";
						break;

				case "student":
						$qGubun = " AND A.sendMethod IN ('sms', 'email') ";
						break;
		}		

		$qSearch = $qSeq.$qSendMethod.$qCompanyCode.$qCompanyName.$qUserID.$qUserName.$qSendDate.$qGubun;

		$que = "SELECT A.*, B.userName, C.companyName, D.contentsName
							FROM nynSendLog AS A
							LEFT OUTER
							JOIN nynMember AS B ON A.userID=B.userID
							LEFT OUTER
							JOIN nynCompany AS C ON A.companyCode=C.companyCode
							LEFT OUTER
							JOIN nynContents AS D ON A.contentsCode=D.contentsCode
							WHERE A.seq <> 0 ".$qSearch;
		$res = mysql_query($que);
		$allPost = mysql_num_rows($res);
		$currentLimit = ($list * $page) - $list; //몇 번째의 글부터 가져오는지
		$sqlLimit = ' limit '.$currentLimit.', '.$list; //limit sql 구문

		$query = "SELECT A.*, B.userName, C.companyName, D.contentsName
							FROM nynSendLog AS A
							LEFT OUTER
							JOIN nynMember AS B ON A.userID=B.userID
							LEFT OUTER
							JOIN nynCompany AS C ON A.companyCode=C.companyCode
							LEFT OUTER
							JOIN nynContents AS D ON A.contentsCode=D.contentsCode
							WHERE A.seq <> 0 ".$qSearch." 
							ORDER BY ".$sortType." ".$sortValue.$sqlLimit;
		$result = mysql_query($query);
		$adminapi = array();
		$a=0;

		$adminapi[totalCount] = "$allPost"; //총 개시물 수

		while($rs = mysql_fetch_array($result)) {
/*
			if($rs[sendMethod] == "sms") {
				$message = $rs[message];
			} else {
				$message = $rs[content];
			}
*/
			$message = $rs[message];
			

				switch ($rs[sendType]) {
						case "start":
								if($gubun == 'tutor') {
									$typeName = "배정안내";
								} else {
									$typeName = "학습시작";
								}
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
								if($gubun == 'tutor') {
									$typeName = "첨삭시작";
								} else {
									$typeName = "종강일";
								}
								break;
						
						case "etc":
								$typeName = "기타";
								break;

						case "push":
								$typeName = "첨삭독려";
								break;
			}

			$adminapi[sendLog][$a][seq] = $rs[seq];
			$adminapi[sendLog][$a][sendMethod] = $rs[sendMethod];
			$adminapi[sendLog][$a][sendType] = $typeName;
			$adminapi[sendLog][$a][lectureOpenSeq] = $rs[lectureOpenSeq];
			$adminapi[sendLog][$a][lectureStart] = $rs[lectureStart];
			$adminapi[sendLog][$a][lectureEnd] = $rs[lectureEnd];
			$adminapi[sendLog][$a][userID] = $rs[userID];
			$adminapi[sendLog][$a][userName] = $rs[userName];
			$adminapi[sendLog][$a][companyCode] = $rs[companyCode];
			$adminapi[sendLog][$a][companyName] = $rs[companyName];
			$adminapi[sendLog][$a][contentsCode] = $rs[contentsCode];
			$adminapi[sendLog][$a][contentsName] = $rs[contentsName];
			$adminapi[sendLog][$a][message] = $message;
			$adminapi[sendLog][$a][receiveTarget] = $rs[receiveTarget];
			$adminapi[sendLog][$a][sendTarget] = $rs[sendTarget];
			$adminapi[sendLog][$a][sendDate] = $rs[sendDate];
			$adminapi[sendLog][$a][inputDate] = $rs[inputDate];
			$adminapi[sendLog][$a][sendID] = $rs[sendID];
			$a++;
		}

		$json_encoded = json_encode($adminapi);
		print_r($json_encoded);
?>