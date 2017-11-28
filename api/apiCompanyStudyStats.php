<?php
	header('Content-Type:application/json; charset=utf-8');
?>
<? include '../lib/header.php'; ?>
<?
	$companyCode = $_GET['companyCode'];

	if($companyCode == "") {
		echo '{"result" : "필수값 누락"}';
		exit;
	}

	$query = "SELECT DISTINCT(A.lectureStart), A.lectureEnd,
						(SELECT COUNT(*) FROM nynStudy AS B WHERE B.companyCode=A.companyCode AND B.lectureStart=A.lectureStart AND B.lectureEnd=A.lectureEnd AND B.serviceType='1') AS totalStudy, 
						(SELECT SUM(price) FROM nynStudy AS C WHERE C.companyCode=A.companyCode AND C.lectureStart=A.lectureStart AND C.lectureEnd=A.lectureEnd AND C.serviceType='1') AS totalPrice,
						(SELECT SUM(rPrice) FROM nynStudy AS D WHERE D.companyCode=A.companyCode AND D.lectureStart=A.lectureStart AND D.lectureEnd=A.lectureEnd AND D.serviceType='1' AND D.passOK='Y') AS totalReturnPrice,
						(SELECT COUNT(*) FROM nynStudy AS E WHERE E.companyCode=A.companyCode AND E.lectureStart=A.lectureStart AND E.lectureEnd=A.lectureEnd AND E.serviceType='1' AND E.passOK='Y') AS totalPassOK
						FROM nynStudy AS A WHERE A.serviceType='1' AND A.companyCode='".$companyCode."' ORDER BY A.lectureStart DESC, A.lectureEnd DESC";
	$result = mysql_query($query);
	$count = mysql_num_rows($result);
	$a = 0;
	$adminapi = array(); //DB 값이 없는 경우 배열선언 부분

	$adminapi[companyCode] = $companyCode;
	$adminapi[totalCount] = "$count";

	while($rs = mysql_fetch_array($result)) {
		$adminapi[companyStudyStats][$a][lectureStart] = $rs[lectureStart];
		$adminapi[companyStudyStats][$a][lectureEnd] = $rs[lectureEnd];
		$adminapi[companyStudyStats][$a][totalStudy] = $rs[totalStudy];
		$adminapi[companyStudyStats][$a][totalPrice] = $rs[totalPrice];
		$adminapi[companyStudyStats][$a][totalReturnPrice] = $rs[totalReturnPrice];
		$adminapi[companyStudyStats][$a][totalPassOK] = $rs[totalPassOK];
		$totalPassRate = ROUND((($rs[totalPassOK]/$rs[totalStudy])*100),1);
		$adminapi[companyStudyStats][$a][totalPassRate] = "$totalPassRate";
		$a++;
	}

	$json_encoded = json_encode($adminapi);
	print_r($json_encoded);

	@mysql_close();
?>