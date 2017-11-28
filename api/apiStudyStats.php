<?php
		header('Content-Type:application/json; charset=utf-8');
?>
<? include '../lib/header.php'; ?>
<?
	if($method == "GET") { // 회원사 정보 불러옴
		$list = $_GET['list'];
		$page = $_GET['page'];
		$seq = $_GET['seq'];
		$year = $_GET['year'];
		
		$adminapi = array(); //DB 값이 없는 경우 배열선언 부분

		if($year != "") {  // 연도 통계

			if($year == "now") {
				$year = substr($inputDate,0,4);
			}

			$adminapi[searchType] = $year."년 통계";
			$query = "SELECT 
								(
								SELECT COUNT(*)
								FROM nynStudy
								WHERE serviceType NOT IN (3,9) AND lectureEnd BETWEEN '".$year."-01-01' AND '".$year."-12-31') AS studyCount,
								(
								SELECT COUNT(DISTINCT(companyCode))
								FROM nynStudy
								WHERE serviceType NOT IN (3,9) AND lectureEnd BETWEEN '".$year."-01-01' AND '".$year."-12-31') AS companyCount,
								(
								SELECT COUNT(DISTINCT(contentsCode))
								FROM nynStudy
								WHERE serviceType NOT IN (3,9) AND lectureEnd BETWEEN '".$year."-01-01' AND '".$year."-12-31') AS contentsCount,
								(
								SELECT COUNT(DISTINCT(userID))
								FROM nynStudy
								WHERE serviceType NOT IN (3,9) AND lectureEnd BETWEEN '".$year."-01-01' AND '".$year."-12-31') AS userCount,
								(
								SELECT COUNT(*)
								FROM nynStudy
								WHERE serviceType NOT IN (3,9) AND lectureEnd BETWEEN '".$year."-01-01' AND '".$year."-12-31' AND passOK='Y') AS passCount";
			$result = mysql_query($query);
			$count = mysql_num_rows($result);

			$adminapi[totalCount] = "$count";

			while($rs = mysql_fetch_array($result)) {
				$adminapi[studyCount] = $rs[studyCount];
				$adminapi[companyCount] = $rs[companyCount];
				$adminapi[contentsCount] = $rs[contentsCount];
				$adminapi[userCount] = $rs[userCount];
				$adminapi[passCount] = $rs[passCount];
				$passRate = ($rs[passCount]/$rs[studyCount])*100;
				$adminapi[passRate] = "$passRate";
			}

		} else { // 진행중인 과정 통계

			if($_SESSION[loginUserLevel] == '5' || $_SESSION[loginUserLevel] == '6') {
				//$qMKT = "AND tutor='".$_SESSION[loginUserID]."'";
			}
			if($_SESSION[loginUserLevel] == '7') {
				$qTutor = "AND tutor='".$_SESSION[loginUserID]."'";
			}
			if($_SESSION[loginUserLevel] == '8') {
				$queryM = "SELECT * FROM nynMatching WHERE userID='".$_SESSION[loginUserID]."' AND matchingType='manager'";
				$resultM = mysql_query($queryM);
				$countM = mysql_num_rows($resultM);

				if($countM > 0 ) {
					$qUserList = " AND companyCode IN (";
					$m = 1;

					while($rsM = mysql_fetch_array($resultM)) {
						$qUserList .= "'".$rsM['matchingValue']."'";
						if($countM != $m) {
							$qUserList .= ", ";
						}
						$m++;
					}
					$qUserList .= ")";
				} else {
					$qUserList = " AND companyCode='".$_SESSION[loginCompanyCode]."'";
				}
			}

			$adminapi[searchType] = "진행중인 과정";
			$query = "SELECT A.lectureStart, A.lectureEnd,
								(
								SELECT COUNT(*)
								FROM nynStudy
								WHERE serviceType NOT IN (3,9) AND lectureStart=A.lectureStart AND lectureEnd=A.lectureEnd ".$qTutor.$qUserList.") AS studyCount,
								(
								SELECT COUNT(*)
								FROM nynStudy
								WHERE serviceType NOT IN (3,9) AND lectureStart=A.lectureStart AND lectureEnd=A.lectureEnd AND (midStatus='Y' OR midStatus='C') ".$qTutor.$qUserList.") AS midSubmit,
								(
								SELECT COUNT(*)
								FROM nynStudy
								WHERE serviceType NOT IN (3,9) AND lectureStart=A.lectureStart AND lectureEnd=A.lectureEnd AND midStatus='C' ".$qTutor.$qUserList.") AS midComplete,
								(
								SELECT COUNT(*)
								FROM nynStudy
								WHERE serviceType NOT IN (3,9) AND lectureStart=A.lectureStart AND lectureEnd=A.lectureEnd AND (testStatus='Y' OR testStatus='C') ".$qTutor.$qUserList.") AS testSubmit,
								(
								SELECT COUNT(*)
								FROM nynStudy
								WHERE serviceType NOT IN (3,9) AND lectureStart=A.lectureStart AND lectureEnd=A.lectureEnd AND testStatus='C' ".$qTutor.$qUserList.") AS testComplete,
								(
								SELECT COUNT(*)
								FROM nynStudy
								WHERE serviceType NOT IN (3,9) AND lectureStart=A.lectureStart AND lectureEnd=A.lectureEnd AND (reportStatus='Y' OR reportStatus='C') ".$qTutor.$qUserList.") AS reportSubmit,
								(
								SELECT COUNT(*)
								FROM nynStudy
								WHERE serviceType NOT IN (3,9) AND lectureStart=A.lectureStart AND lectureEnd=A.lectureEnd AND reportStatus='C' ".$qTutor.$qUserList.") AS reportComplete
								FROM (
								SELECT DISTINCT(lectureStart) AS lectureStart, lectureEnd
								FROM nynStudy 
								WHERE serviceType NOT IN (3,9) ".$qTutor.$qUserList.") AS A
								WHERE CURDATE() BETWEEN A.lectureStart AND A.lectureEnd";
			$result = mysql_query($query);
			$count = mysql_num_rows($result);
			$a = 0;

			$adminapi[totalCount] = "$count";

			while($rs = mysql_fetch_array($result)) {
				$adminapi[study][$a][lectureStart] = $rs[lectureStart];
				$adminapi[study][$a][lectureEnd] = $rs[lectureEnd];
				$tutorDeadline = date("Y-m-d", strtotime($rs[lectureEnd]."+4Day"));
				$adminapi[study][$a][tutorDeadline] = $tutorDeadline;
				$adminapi[study][$a][studyCount] = $rs[studyCount];
				$adminapi[study][$a][midSubmit] = $rs[midSubmit];
				$adminapi[study][$a][midComplete] = $rs[midComplete];
				$adminapi[study][$a][testSubmit] = $rs[testSubmit];
				$adminapi[study][$a][testComplete] = $rs[testComplete];
				$adminapi[study][$a][reportSubmit] = $rs[reportSubmit];
				$adminapi[study][$a][reportComplete] = $rs[reportComplete];

					$queryA = " SELECT 
											(
											SELECT companyName
											FROM nynCompany
											WHERE companyCode=A.companyCode) AS companyName,
											(
											SELECT COUNT(*)
											FROM nynStudy
											WHERE serviceType NOT IN (3,9) AND lectureStart='".$rs[lectureStart]."' AND lectureEnd='".$rs[lectureEnd]."' AND companyCode=A.companyCode ".$qTutor.$qUserList.") AS studyCount,
											(
											SELECT COUNT(*)
											FROM nynStudy
											WHERE serviceType NOT IN (3,9) AND lectureStart='".$rs[lectureStart]."' AND lectureEnd='".$rs[lectureEnd]."' AND companyCode=A.companyCode AND (midStatus='Y' OR midStatus='C') ".$qTutor.$qUserList.") AS midSubmit,
											(
											SELECT COUNT(*)
											FROM nynStudy
											WHERE serviceType NOT IN (3,9) AND lectureStart='".$rs[lectureStart]."' AND lectureEnd='".$rs[lectureEnd]."' AND companyCode=A.companyCode AND midStatus='C' ".$qTutor.$qUserList.") AS midComplete,
											(
											SELECT COUNT(*)
											FROM nynStudy
											WHERE serviceType NOT IN (3,9) AND lectureStart='".$rs[lectureStart]."' AND lectureEnd='".$rs[lectureEnd]."' AND companyCode=A.companyCode AND (testStatus='Y' OR testStatus='C') ".$qTutor.$qUserList.") AS testSubmit,
											(
											SELECT COUNT(*)
											FROM nynStudy
											WHERE serviceType NOT IN (3,9) AND lectureStart='".$rs[lectureStart]."' AND lectureEnd='".$rs[lectureEnd]."' AND companyCode=A.companyCode AND testStatus='C' ".$qTutor.$qUserList.") AS testComplete,
											(
											SELECT COUNT(*)
											FROM nynStudy
											WHERE serviceType NOT IN (3,9) AND lectureStart='".$rs[lectureStart]."' AND lectureEnd='".$rs[lectureEnd]."' AND companyCode=A.companyCode AND (reportStatus='Y' OR reportStatus='C') ".$qTutor.$qUserList.") AS reportSubmit,
											(
											SELECT COUNT(*)
											FROM nynStudy
											WHERE serviceType NOT IN (3,9) AND lectureStart='".$rs[lectureStart]."' AND lectureEnd='".$rs[lectureEnd]."' AND companyCode=A.companyCode AND reportStatus='C' ".$qTutor.$qUserList.") AS reportComplete
											FROM (
											SELECT DISTINCT(companyCode) AS companyCode
											FROM nynStudy
											WHERE serviceType NOT IN (3,9) AND lectureStart='".$rs[lectureStart]."' AND lectureEnd='".$rs[lectureEnd]."' ".$qTutor.$qUserList.") AS A";
					$resultA = mysql_query($queryA);
					$b=0;
		
					while($rsA = mysql_fetch_array($resultA)) {
						$adminapi[study][$a][company][$b][companyName] = $rsA[companyName];
						$adminapi[study][$a][company][$b][studyCount] = $rsA[studyCount];
						$adminapi[study][$a][company][$b][midSubmit] = $rsA[midSubmit];
						$adminapi[study][$a][company][$b][midComplete] = $rsA[midComplete];
						$adminapi[study][$a][company][$b][testSubmit] = $rsA[testSubmit];
						$adminapi[study][$a][company][$b][testComplete] = $rsA[testComplete];
						$adminapi[study][$a][company][$b][reportSubmit] = $rsA[reportSubmit];
						$adminapi[study][$a][company][$b][reportComplete] = $rsA[reportComplete];
						$b++;
					}

				$a++;
			}
		}
			$json_encoded = json_encode($adminapi);
			print_r($json_encoded);
	}

	/*
SELECT DISTINCT(A.lectureStart), A.lectureEnd,
(SELECT COUNT(*) FROM nynStudy AS B WHERE B.lectureStart=A.lectureStart AND B.lectureEnd=A.lectureEnd AND B.serviceType='1') AS totalStudy, 
(SELECT SUM(price) FROM nynStudy AS C WHERE C.lectureStart=A.lectureStart AND C.lectureEnd=A.lectureEnd AND C.serviceType='1') AS totalPrice,
(SELECT SUM(rPrice) FROM nynStudy AS D WHERE D.lectureStart=A.lectureStart AND D.lectureEnd=A.lectureEnd AND D.serviceType='1' AND D.passOK='Y') AS totalReturnPrice,
(SELECT COUNT(*) FROM nynStudy AS E WHERE E.lectureStart=A.lectureStart AND E.lectureEnd=A.lectureEnd AND E.serviceType='1' AND E.passOK='Y') AS totalPassOK
FROM nynStudy AS A WHERE A.serviceType='1' ORDER BY A.lectureStart DESC, A.lectureEnd DESC


SELECT DISTINCT(MID(A.lectureStart,6,2)) AS month,
(SELECT COUNT(*) FROM nynStudy AS B WHERE MID(B.lectureStart,6,2)=month AND B.serviceType='1' AND companyCode='3128662465') AS totalStudy, 
(SELECT SUM(price) FROM nynStudy AS C WHERE MID(C.lectureStart,6,2)=month AND C.serviceType='1') AS totalPrice,
(SELECT SUM(rPrice) FROM nynStudy AS D WHERE MID(D.lectureStart,6,2)=month AND D.serviceType='1' AND D.passOK='Y') AS totalReturnPrice,
(SELECT COUNT(*) FROM nynStudy AS E WHERE MID(E.lectureStart,6,2)=month AND E.serviceType='1' AND E.passOK='Y') AS totalPassOK
FROM nynStudy AS A WHERE LEFT(A.lectureStart,4)='2016' AND A.companyCode='3128662465' AND A.serviceType='1' ORDER BY month DESC	
	*/
		
	@mysql_close();
?>