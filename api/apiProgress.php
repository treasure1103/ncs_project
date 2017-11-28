<?php
	header('Content-Type:application/json; charset=utf-8');
?>
<? include '../lib/header.php'; ?>
<?
	$userID = $_GET['userID'];
	$lectureOpenSeq = $_GET['lectureOpenSeq'];

	if($userID == "" || $lectureOpenSeq == "") {
		echo '{"result" : "필수값 누락"}';
		exit;
	}

	$query = "SELECT A.lectureStart, A.lectureEnd, A.progress, A.userID, B.userName, C.companyName, D.contentsCode, D.contentsName FROM nynStudy AS A 
						LEFT OUTER
						JOIN nynMember AS B ON A.userID=B.userID
						LEFT OUTER
						JOIN nynCompany AS C ON A.companyCode=C.companyCode
						LEFT OUTER
						JOIN nynContents AS D ON A.contentsCode=D.contentsCode
						WHERE A.lectureOpenSeq='".$lectureOpenSeq."' AND A.userID='".$userID."'";
	$result = mysql_query($query);
	$rs = mysql_fetch_array($result);

	$a = 0;
	$adminapi = array(); //DB 값이 없는 경우 배열선언 부분
	$adminapi[userID] = $userID;
	$adminapi[userName] = $rs[userName];
	$adminapi[companyName] = $rs[companyName];
	$adminapi[lectureOpenSeq] = "$lectureOpenSeq";
	$adminapi[lectureStart] = $rs[lectureStart];
	$adminapi[lectureEnd] = $rs[lectureEnd];
	$adminapi[totalProgress] = $rs[progress];
	$adminapi[contentsName] = $rs[contentsName];
	$adminapi[contentsCode] = $rs[contentsCode];

	$queryA = " SELECT A.*, B.chapterName
							FROM nynProgress AS A
							LEFT OUTER
							JOIN nynChapter AS B ON A.contentsCode=B.contentsCode AND A.chapter=B.chapter
							WHERE A.lectureOpenSeq='".$lectureOpenSeq."' AND A.userID='".$userID."'";
	$resultA = mysql_query($queryA);

	$count = mysql_num_rows($resultA);
	$adminapi[totalCount] = "$count";

	while($rsA = mysql_fetch_array($resultA)) {
		$adminapi[progress][$a][seq] = $rsA[seq];
		$adminapi[progress][$a][chapter] = $rsA[chapter];
		$adminapi[progress][$a][chapterName] = $rsA[chapterName];
		$adminapi[progress][$a][progress] = $rsA[progress];
		$adminapi[progress][$a][startTime] = $rsA[startTime];
		$adminapi[progress][$a][endTime] = $rsA[endTime];
		$adminapi[progress][$a][studyIP] = $rsA[studyIP];
		$adminapi[progress][$a][totalTime] = $rsA[totalTime];
		$adminapi[progress][$a][lastPage] = $rsA[lastPage];
		$adminapi[progress][$a][mobileLastPage] = $rsA[mobileLastPage];
		$a++;
	}

	$json_encoded = json_encode($adminapi);
	print_r($json_encoded);
	mysql_close();
?>