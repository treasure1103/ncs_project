<?php
		header('Content-Type:application/json; charset=utf-8');
?>
<? include '../lib/header.php'; ?>
<?
		if($method == "POST") {
			$gubun = $_POST['gubun'];
			$lectureStart = $_POST['lectureStart'];
			$lectureEnd = $_POST['lectureEnd'];
			$companyCode = $_POST['companyCode'];
			$today = substr($inputDate,0,10);

			if($lectureEnd >= $today) {
				echo '{"result" : "수강 기간중에는 할 수 없습니다."}';
				exit;
			}

			$query = "SELECT * FROM nynStudyEnd WHERE gubun='".$gubun."' AND companyCode='".$companyCode."' AND lectureStart='".$lectureStart."' AND lectureEnd='".$lectureEnd."'";
			$result = mysql_query($query);
			$rs = mysql_fetch_array($result);
			$count = mysql_num_rows($result);

			if($count > 0) { // 있으면 마감 취소, Delete
				$queryA = "DELETE FROM nynStudyEnd WHERE gubun='".$gubun."' AND companyCode='".$companyCode."' AND lectureStart='".$lectureStart."' AND lectureEnd='".$lectureEnd."'";
				$resultA = mysql_query($queryA);

				if($gubun == 'studyEnd') {
					if($resultA) { //nynStudy - studyEnd : N Update
						$queryB = "UPDATE nynStudy SET studyEnd='N' WHERE companyCode='".$companyCode."' AND lectureStart='".$lectureStart."' AND lectureEnd='".$lectureEnd."' AND serviceType='1'";
						$resultB = mysql_query($queryB);
					}
				} else {
					if($resultA) { //nynStudy - resultView : N Update
						$queryB = "UPDATE nynStudy SET resultView='N' WHERE companyCode='".$companyCode."' AND lectureStart='".$lectureStart."' AND lectureEnd='".$lectureEnd."' AND serviceType='1'";
						$resultB = mysql_query($queryB);
					}
				}

			} else { // 없으면 마감 처리, Insert
				$queryA = " INSERT INTO nynStudyEnd (gubun, lectureStart, lectureEnd, companyCode, userID, inputDate)
										VALUES ('".$gubun."', '".$lectureStart."', '".$lectureEnd."', '".$companyCode."', '".$_SESSION['loginUserID']."', '".$inputDate."')";
				$resultA = mysql_query($queryA);

				if($gubun == 'studyEnd') {
					if($resultA) { //nynStudy - studyEnd : Y Update
						$queryB = "UPDATE nynStudy SET studyEnd='Y' WHERE companyCode='".$companyCode."' AND lectureStart='".$lectureStart."' AND lectureEnd='".$lectureEnd."' AND serviceType='1'";
						$resultB = mysql_query($queryB);
					}
				} else {
					if($resultA) { //nynStudy - resultView : Y Update
						$queryB = "UPDATE nynStudy SET resultView='Y' WHERE companyCode='".$companyCode."' AND lectureStart='".$lectureStart."' AND lectureEnd='".$lectureEnd."' AND serviceType='1'";
						$resultB = mysql_query($queryB);
					}
				}
			}

			if($resultB) {
				echo '{"result" : "처리되었습니다."}';
			} else {
				echo '{"result" : "error"}';
			}

		} else if($method == "GET") {

			if(!$_SESSION['loginUserLevel']){
				$userLevelCheck = 10;
			} else {
				$userLevelCheck = $_SESSION['loginUserLevel'];
			}

			if($userLevelCheck > 9) {
				echo "error";
				exit;
			}

			$list = $_GET['list'];
			$page = $_GET['page'];
			$seq = $_GET['seq'];
			$year = $_GET['year'];
			$companyCode = $_GET['companyCode'];
			$lectureDay = $_GET['lectureDay']; // 수강일
			$lectureSE = EXPLODE('~',$lectureDay);

			if($list == "") {
				$list = 10;
			}
			if($page == "") {
				$page = 1;
			}
			if($companyCode != "") {
				$qCompanyCode = " AND companyCode='".$companyCode."'";
			}
			if($lectureDay != "") {
				$qLectureStart = " AND (lectureStart='".TRIM($lectureSE[0])."' AND lectureEnd='".TRIM($lectureSE[1])."')";
			}

			$qSearch = $qLectureStart;

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

			$adminapi = array(); //DB 값이 없는 경우 배열선언 부분

			$que = "SELECT A.lectureStart, A.lectureEnd	FROM (
								SELECT DISTINCT(lectureStart) AS lectureStart, lectureEnd
								FROM nynStudy 
								WHERE serviceType IN (1,3) ".$qSearch." AND companyCode NOT IN ('0000000000')) AS A
								ORDER BY A.lectureStart DESC, A.lectureEnd DESC";
			$res = mysql_query($que);
			$allPost = mysql_num_rows($res);
			$currentLimit = ($list * $page) - $list; //몇 번째의 글부터 가져오는지
			$sqlLimit = ' LIMIT '.$currentLimit.', '.$list; //limit sql 구문

			$query = "SELECT A.lectureStart, A.lectureEnd,
								(
								SELECT COUNT(*)
								FROM nynStudy
								WHERE serviceType = 1 AND lectureStart=A.lectureStart AND lectureEnd=A.lectureEnd".$qUserList.") AS studyCount,
								(
								SELECT COUNT(*)
								FROM nynStudy
								WHERE serviceType = 1 AND lectureStart=A.lectureStart AND lectureEnd=A.lectureEnd AND passOK='Y'".$qUserList.") AS studyPassCount,
								(
								SELECT COUNT(*)
								FROM nynStudy
								WHERE serviceType <> 1 AND lectureStart=A.lectureStart AND lectureEnd=A.lectureEnd".$qUserList.") AS studyBeCount,
								(
								SELECT SUM(price)
								FROM nynStudy
								WHERE lectureStart=A.lectureStart AND lectureEnd=A.lectureEnd".$qUserList.") AS totalPrice,
								(
								SELECT SUM(rPrice)
								FROM nynStudy
								WHERE lectureStart=A.lectureStart AND lectureEnd=A.lectureEnd AND passOK='Y'".$qUserList.") AS totalRPrice,
								(
								SELECT COUNT(*)
								FROM nynStudy
								WHERE serviceType = 1 AND lectureStart=A.lectureStart AND lectureEnd=A.lectureEnd AND (midStatus='Y' OR midStatus='C')".$qUserList.") AS midSubmit,
								(
								SELECT COUNT(*)
								FROM nynStudy
								WHERE serviceType = 1 AND lectureStart=A.lectureStart AND lectureEnd=A.lectureEnd AND midStatus='C'".$qUserList.") AS midComplete,
								(
								SELECT COUNT(*)
								FROM nynStudy
								WHERE serviceType = 1 AND lectureStart=A.lectureStart AND lectureEnd=A.lectureEnd AND (testStatus='Y' OR testStatus='C')".$qUserList.") AS testSubmit,
								(
								SELECT COUNT(*)
								FROM nynStudy
								WHERE serviceType = 1 AND lectureStart=A.lectureStart AND lectureEnd=A.lectureEnd AND testStatus='C'".$qUserList.") AS testComplete,
								(
								SELECT COUNT(*)
								FROM nynStudy
								WHERE serviceType = 1 AND lectureStart=A.lectureStart AND lectureEnd=A.lectureEnd AND (reportStatus='Y' OR reportStatus='C')".$qUserList.") AS reportSubmit,
								(
								SELECT COUNT(*)
								FROM nynStudy
								WHERE serviceType = 1 AND lectureStart=A.lectureStart AND lectureEnd=A.lectureEnd AND reportStatus='C'".$qUserList.") AS reportComplete
								FROM (
								SELECT DISTINCT(lectureStart) AS lectureStart, lectureEnd
								FROM nynStudy 
								WHERE serviceType IN (1,3) ".$qSearch."  AND companyCode NOT IN ('0000000000') ".$qCompanyCode.$qUserList." ) AS A 
								ORDER BY A.lectureStart DESC, A.lectureEnd DESC ".$sqlLimit;
			$result = mysql_query($query);
			$count = mysql_num_rows($result);
			$a = 0;

			$adminapi[totalCount] = "$allPost";

			while($rs = mysql_fetch_array($result)) {
				$adminapi[study][$a][lectureStart] = $rs[lectureStart];
				$adminapi[study][$a][lectureEnd] = $rs[lectureEnd];
				$tutorDeadline = date("Y-m-d", strtotime($rs[lectureEnd]."+4Day"));
				$adminapi[study][$a][tutorDeadline] = $tutorDeadline;
				$adminapi[study][$a][studyCount] = $rs[studyCount];
				$adminapi[study][$a][studyBeCount] = $rs[studyBeCount];
				$adminapi[study][$a][midSubmit] = $rs[midSubmit];
				$adminapi[study][$a][midComplete] = $rs[midComplete];
				$adminapi[study][$a][testSubmit] = $rs[testSubmit];
				$adminapi[study][$a][testComplete] = $rs[testComplete];
				$adminapi[study][$a][reportSubmit] = $rs[reportSubmit];
				$adminapi[study][$a][reportComplete] = $rs[reportComplete];
				$adminapi[study][$a][studyPassCount] = $rs[studyPassCount];
				$adminapi[study][$a][totalPrice] = $rs[totalPrice];

					$queryA = " SELECT companyCode, 
											(
											SELECT companyName
											FROM nynCompany
											WHERE companyCode=A.companyCode) AS companyName,
											(
											SELECT COUNT(*)
											FROM nynStudy
											WHERE serviceType = 1 AND lectureStart='".$rs[lectureStart]."' AND lectureEnd='".$rs[lectureEnd]."' AND companyCode=A.companyCode AND passOK='Y'".$qUserList.") AS studyPassCount,
											(
											SELECT COUNT(*)
											FROM nynStudy
											WHERE serviceType = 1 AND lectureStart='".$rs[lectureStart]."' AND lectureEnd='".$rs[lectureEnd]."' AND companyCode=A.companyCode".$qUserList.") AS studyCount,
											(
											SELECT COUNT(*)
											FROM nynStudy
											WHERE serviceType <> 1 AND lectureStart='".$rs[lectureStart]."' AND lectureEnd='".$rs[lectureEnd]."' AND companyCode=A.companyCode".$qUserList.") AS studyBeCount,
											(
											SELECT SUM(price)
											FROM nynStudy
											WHERE lectureStart='".$rs[lectureStart]."' AND lectureEnd='".$rs[lectureEnd]."' AND companyCode=A.companyCode".$qUserList.") AS totalPrice,
											(
											SELECT SUM(rPrice)
											FROM nynStudy
											WHERE lectureStart='".$rs[lectureStart]."' AND lectureEnd='".$rs[lectureEnd]."' AND companyCode=A.companyCode AND passOK='Y'".$qUserList.") AS totalRPrice,
											(
											SELECT COUNT(*)
											FROM nynStudy
											WHERE serviceType = 1 AND lectureStart='".$rs[lectureStart]."' AND lectureEnd='".$rs[lectureEnd]."' AND companyCode=A.companyCode AND (midStatus='Y' OR midStatus='C')".$qUserList.") AS midSubmit,
											(
											SELECT COUNT(*)
											FROM nynStudy
											WHERE serviceType = 1 AND lectureStart='".$rs[lectureStart]."' AND lectureEnd='".$rs[lectureEnd]."' AND companyCode=A.companyCode AND midStatus='C'".$qUserList.") AS midComplete,
											(
											SELECT COUNT(*)
											FROM nynStudy
											WHERE serviceType = 1 AND lectureStart='".$rs[lectureStart]."' AND lectureEnd='".$rs[lectureEnd]."' AND companyCode=A.companyCode AND (testStatus='Y' OR testStatus='C')".$qUserList.") AS testSubmit,
											(
											SELECT COUNT(*)
											FROM nynStudy
											WHERE serviceType = 1 AND lectureStart='".$rs[lectureStart]."' AND lectureEnd='".$rs[lectureEnd]."' AND companyCode=A.companyCode AND testStatus='C'".$qUserList.") AS testComplete,
											(
											SELECT COUNT(*)
											FROM nynStudy
											WHERE serviceType = 1 AND lectureStart='".$rs[lectureStart]."' AND lectureEnd='".$rs[lectureEnd]."' AND companyCode=A.companyCode AND (reportStatus='Y' OR reportStatus='C')".$qUserList.") AS reportSubmit,
											(
											SELECT COUNT(*)
											FROM nynStudy
											WHERE serviceType = 1 AND lectureStart='".$rs[lectureStart]."' AND lectureEnd='".$rs[lectureEnd]."' AND companyCode=A.companyCode AND reportStatus='C'".$qUserList.") AS reportComplete
											FROM (
											SELECT DISTINCT(companyCode) AS companyCode
											FROM nynStudy
											WHERE serviceType IN (1,3) AND companyCode NOT IN ('0000000000') 
											AND lectureStart='".$rs[lectureStart]."' AND lectureEnd='".$rs[lectureEnd]."' ".$qCompanyCode.$qUserList.") AS A";
					$resultA = mysql_query($queryA);
					$b=0;
		
					while($rsA = mysql_fetch_array($resultA)) {
						$adminapi[study][$a][company][$b][companyName] = $rsA[companyName];
						$adminapi[study][$a][company][$b][companyCode] = $rsA[companyCode];
						$adminapi[study][$a][company][$b][studyCount] = $rsA[studyCount];
						$adminapi[study][$a][company][$b][studyBeCount] = $rsA[studyBeCount];
						$adminapi[study][$a][company][$b][midSubmit] = $rsA[midSubmit];
						$adminapi[study][$a][company][$b][midComplete] = $rsA[midComplete];
						$adminapi[study][$a][company][$b][testSubmit] = $rsA[testSubmit];
						$adminapi[study][$a][company][$b][testComplete] = $rsA[testComplete];
						$adminapi[study][$a][company][$b][reportSubmit] = $rsA[reportSubmit];
						$adminapi[study][$a][company][$b][reportComplete] = $rsA[reportComplete];
						$adminapi[study][$a][company][$b][studyPassCount] = $rsA[studyPassCount];
						$adminapi[study][$a][company][$b][totalPrice] = $rsA[totalPrice];
						if($rsA[totalRPrice] != ''){
							$totalRPrice = $rsA[totalRPrice];
						}else{
							$totalRPrice = 0;
						}
						$adminapi[study][$a][company][$b][totalRPrice] = $totalRPrice;

						$queryB = " SELECT userID, inputDate FROM nynStudyEnd WHERE gubun='studyEnd' AND companyCode='".$rsA[companyCode]."' 
												AND lectureStart='".$rs[lectureStart]."' AND lectureEnd='".$rs[lectureEnd]."'";
						$resultB = mysql_query($queryB);
						$rsB = mysql_fetch_array($resultB);
						$countB = mysql_num_rows($resultB);

						if($countB > 0) {
							$adminapi[study][$a][company][$b][studyEnd] = 'Y';
							$adminapi[study][$a][company][$b][userIDS] = $rsB[userID];
							$adminapi[study][$a][company][$b][inputDateS] = $rsB[inputDate];
						} else {
							$adminapi[study][$a][company][$b][studyEnd] = 'N';
						}

						$queryC = " SELECT userID, inputDate FROM nynStudyEnd WHERE gubun='resultView' AND companyCode='".$rsA[companyCode]."' 
												AND lectureStart='".$rs[lectureStart]."' AND lectureEnd='".$rs[lectureEnd]."'";
						$resultC = mysql_query($queryC);
						$rsC = mysql_fetch_array($resultC);
						$countC = mysql_num_rows($resultC);

						if($countC > 0) {
							$adminapi[study][$a][company][$b][resultView] = 'Y';
							$adminapi[study][$a][company][$b][userIDR] = $rsC[userID];
							$adminapi[study][$a][company][$b][inputDateR] = $rsC[inputDate];
						} else {
							$adminapi[study][$a][company][$b][resultView] = 'N';
						}

						/*
						if($companyCode) {
							$queryC = "SELECT DISTINCT(contentsCode) FROM nynStudy WHERE companyCode='".$companyCode."' AND lectureStart='".$lectureStart."' AND lectureEnd='".$lectureEnd."'";
							$resultC = mysql_query($queryC);
							$rsC = mysql_fetch_array($resultC);
							$countC= mysql_num_rows($resultC);

							$queryD = " SELECT B.userName, A.progress, A.midScore, A.testScore, A.reportScore, A.totalScore, A.passOK, A.price, A.rPrice 
													FROM nynStudy AS A 
													LEFT OUTER 
													JOIN nynMember AS B
													ON A.userID=B.userName 
													WHERE A.companyCode='".$companyCode."' AND A.lectureStart='".$lectureStart."' AND A.lectureEnd='".$lectureEnd."' AND ";
							$resultD = mysql_query($queryD);
							$rsD = mysql_fetch_array($resultD);
							$countD= mysql_num_rows($resultD);
						}
						*/

						$b++;
					}

				$a++;
			}
			$json_encoded = json_encode($adminapi);
			print_r($json_encoded);
	}
		
	@mysql_close();
?>