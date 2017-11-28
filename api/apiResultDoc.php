<?php
	header('Content-Type:application/json; charset=utf-8');
?>
<? include '../lib/header.php'; ?>
<?
		$companyCode = $_GET['companyCode'];
		$lectureDay = $_GET['lectureDay']; // 수강일
		$lectureSE = EXPLODE('~',$lectureDay);
		$lectureStart = $lectureSE[0];
		$lectureEnd = $lectureSE[1];

		if(!$_SESSION[loginUserID]) { // 세션이 없으면 접근 거부
			echo "error";
			exit;
		}

		$queryS = "SELECT
							(SELECT COUNT(*) FROM nynStudy WHERE companyCode='".$companyCode."' AND lectureStart='".$lectureStart."' AND lectureEnd='".$lectureEnd."' AND serviceType=1) AS totalCount,
							(SELECT COUNT(*) FROM nynStudy WHERE companyCode='".$companyCode."' AND lectureStart='".$lectureStart."' AND lectureEnd='".$lectureEnd."' AND serviceType=1 AND passOK='Y') AS passOK,
							(SELECT SUM(price) FROM nynStudy WHERE companyCode='".$companyCode."' AND lectureStart='".$lectureStart."' AND lectureEnd='".$lectureEnd."' AND serviceType=1) AS totalPrice,
							(SELECT SUM(rPrice) FROM nynStudy WHERE companyCode='".$companyCode."' AND lectureStart='".$lectureStart."' AND lectureEnd='".$lectureEnd."' AND serviceType=1 AND passOK='Y') AS totalRprice"; 
		$resultS = mysql_query($queryS);
		$rsS = mysql_fetch_array($resultS);

		$query = "SELECT DISTINCT(A.contentsCode), B.contentsName FROM nynStudy AS A
							LEFT OUTER
							JOIN nynContents AS B
							ON A.contentsCode=B.contentsCode
							WHERE A.companyCode='".$companyCode."' AND A.lectureStart='".$lectureStart."' 
							AND A.lectureEnd='".$lectureEnd."' AND A.serviceType=1
							ORDER BY B.contentsName"; 
		$result = mysql_query($query);

		$a = 0;
		$adminapi = array(); //DB 값이 없는 경우 배열선언 부분
		$adminapi[lectureStart] = $lectureStart;
		$adminapi[lectureEnd] = $lectureEnd;
		$adminapi[totalCount] = $rsS[totalCount];
		$adminapi[totalPass] = $rsS[passOK];
		$adminapi[totalPrice] = $rsS[totalPrice];
		$adminapi[totalRprice] = $rsS[totalRprice];

		while($rs = mysql_fetch_array($result)) {
			$adminapi[result][$a][contentsName] = $rs[contentsName];

			$queryA = "SELECT A.*, B.userName, C.contentsName FROM nynStudy AS A
								LEFT OUTER 
								JOIN nynMember AS B
								ON A.userID=B.userID
								LEFT OUTER 
								JOIN nynContents AS C
								ON A.contentsCode=C.contentsCode
								WHERE A.companyCode='".$companyCode."' AND A.lectureStart='".$lectureStart."' 
								AND A.lectureEnd='".$lectureEnd."' AND C.contentsCode='".$rs[contentsCode]."'"; 
			$resultA = mysql_query($queryA);
			$b = 0;
			
			while($rsA = mysql_fetch_array($resultA)) {
				$adminapi[result][$a][study][$b][userName] = $rsA[userName];
				$adminapi[result][$a][study][$b][progress] = $rsA[progress];

				if($rsA[midScore] == null) {
					$midScore = "0";
				} else {
					$midScore = $rsA[midScore];
				}
				if($rsA[testScore] == null) {
					$testScore = "0";
				} else {
					$testScore = $rsA[testScore];
				}
				if($rsA[reportScore] == null) {
					$reportScore = "0";
				} else {
					$reportScore = $rsA[reportScore];
				}
				if($rsA[totalScore] == null) {
					$totalScore = "0";
				} else {
					$totalScore = $rsA[totalScore];
				}
				$adminapi[result][$a][study][$b][midScore] = $midScore;
				$adminapi[result][$a][study][$b][testScore] = $testScore;
				$adminapi[result][$a][study][$b][reportScore] = $reportScore;
				$adminapi[result][$a][study][$b][totalScore] = $totalScore;
				$adminapi[result][$a][study][$b][passOK] = $rsA[passOK];
				$adminapi[result][$a][study][$b][price] = $rsA[price];
				$adminapi[result][$a][study][$b][rPrice] = $rsA[rPrice];
				$b++;
			}
			$a++;
		}
		$json_encoded = json_encode($adminapi);
		print_r($json_encoded);
?>