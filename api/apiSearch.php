<?php
	header('Content-Type:application/json; charset=utf-8');
?>
<? include '../lib/header.php'; ?>
<?
	$searchMode = $_GET['searchMode'];
	$searchCode = $_GET['searchCode'];
	$searchName = $_GET['searchName'];
	$searchDay = $_GET['searchDay'];
	$request = $_GET['request'];
	$companyCode = $_GET['companyCode'];
	$lectureDay = $_GET['lectureDay'];
	$lectureSE = EXPLODE('~',$lectureDay);

	if($searchMode == "contents" || $searchMode == "company" || $searchMode == "lectureDay" || $searchMode == "study") {

		switch($searchMode){
			case "contents":
				if($searchCode != "") {
					$queryQ = " AND contentsCode LIKE '%".$searchCode."%' ";
				}
				if($searchName != "") {
					$queryQ = " AND contentsName LIKE '%".$searchName."%' ";
				}
				$query = "SELECT DISTINCT(contentsCode), contentsName, seq FROM nynContents WHERE seq <> '0' ".$queryQ." ORDER BY contentsName";
				$aSearchCode = "contentsCode";
				$aSearchName = "contentsName";
			break;

			case "company":
				if($searchCode != "") {
					$queryQ = " AND companyCode LIKE '%".$searchCode."%' ";
				}
				if($searchName != "") {
					$queryQ = " AND companyName LIKE '%".$searchName."%' ";
				}
				if($_SESSION[loginUserLevel] == '5' || $_SESSION[loginUserLevel] == '6') { //영업팀장, 영업사원
					$qMarketer1 = " LEFT OUTER JOIN nynCompany C ON A.companyCode=C.companyCode ";
					$qMarketer2 = " AND C.marketerID='".$_SESSION[loginUserID]."' ";
				}
				if($_SESSION[loginUserLevel] == '7') { // 교강사
					$qTutor = " AND tutor='".$_SESSION[loginUserID]."'";
				}
				if($_SESSION[loginUserLevel] == '8') { // 교육담당자
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
				$query = "SELECT DISTINCT(companyCode), companyName, seq FROM nynCompany WHERE seq <> '0' ".$qMarketer2.$qTutor.$qUserList.$queryQ." ORDER BY companyName";
				$aSearchCode = "companyCode";
				$aSearchName = "companyName";
			break;

			case "lectureDay":
				if($searchDay != "") {
					$queryQ = " AND A.lectureStart BETWEEN '".$searchDay."-01' AND '".$searchDay."-31'";
				}
				if($_SESSION[loginUserLevel] == '5' || $_SESSION[loginUserLevel] == '6') { //영업팀장, 영업사원
					$qMarketer1 = " LEFT OUTER JOIN nynCompany C ON A.companyCode=C.companyCode ";
					$qMarketer2 = " AND C.marketerID='".$_SESSION[loginUserID]."' ";
				}
				if($_SESSION[loginUserLevel] == '7') { // 교강사
					$qTutor = " AND A.tutor='".$_SESSION[loginUserID]."'";
				}
				if($_SESSION[loginUserLevel] == '8') { // 교육담당자
					$queryM = "SELECT * FROM nynMatching WHERE userID='".$_SESSION[loginUserID]."' AND matchingType='manager'";
					$resultM = mysql_query($queryM);
					$countM = mysql_num_rows($resultM);

					if($countM > 0 ) {
						$qUserList = " AND A.companyCode IN (";
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
						$qUserList = " AND A.companyCode='".$_SESSION[loginCompanyCode]."'";
					}
				}

				$query = "SELECT DISTINCT(lectureStart), lectureEnd FROM nynStudy A 
									LEFT OUTER JOIN nynMember B ON A.userID=B.userID".$qMarketer1."
									WHERE A.serviceType <> 9 ".$qMarketer2.$qTutor.$qUserList.$queryQ." ORDER BY lectureStart DESC, lectureEnd DESC";
				$aSearchCode = "lectureOpenSeq";
				$aSearchName = "lectureStart";
			break;

			case "study":
				if($lectureDay != "") { 
					if($_SESSION[loginUserLevel] == '5' || $_SESSION[loginUserLevel] == '6') { //영업팀장, 영업사원
						$qMarketer = " AND B.marketerID='".$_SESSION[loginUserID]."' ";
					}
					if($_SESSION[loginUserLevel] == '7') { // 교강사
						$qTutorList = "AND A.tutor='".$_SESSION[loginUserID]."'";
					}
					if($_SESSION[loginUserLevel] == '8') { // 교육담당자
						$queryM = "SELECT * FROM nynMatching WHERE userID='".$_SESSION[loginUserID]."' AND matchingType='manager'";
						$resultM = mysql_query($queryM);
						$countM = mysql_num_rows($resultM);

						if($countM > 0 ) {
							$qUserList = " AND A.companyCode IN (";
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
							$qUserList = " AND A.companyCode='".$_SESSION[loginCompanyCode]."'";
						}
					}

					if($request == "contents") {
						$query = "SELECT DISTINCT(A.contentsCode), B.contentsName FROM nynStudy AS A
											LEFT OUTER
											JOIN nynContents AS B ON A.contentsCode=B.contentsCode
											WHERE A.lectureStart='".TRIM($lectureSE[0])."' AND A.lectureEnd='".TRIM($lectureSE[1])."'".$qTutorList.$qUserList."
											ORDER BY contentsName";
					} else {
						$query = "SELECT DISTINCT(A.companyCode), B.companyName FROM nynStudy AS A
											LEFT OUTER
											JOIN nynCompany AS B ON A.companyCode=B.companyCode
											WHERE A.lectureStart='".TRIM($lectureSE[0])."' AND A.lectureEnd='".TRIM($lectureSE[1])."'".$qMarketer.$qTutorList.$qUserList."
											ORDER BY companyName";
					}
				}

				if($companyCode != "") {
					$query = "SELECT DISTINCT(lectureStart), lectureEnd FROM nynStudy
										WHERE companyCode='".$companyCode."' 
										ORDER BY lectureStart DESC, lectureEnd DESC";
				}
			break;
		}

		$result = mysql_query($query);
		$count = mysql_num_rows($result);
		$a = 0;
		$adminapi = array(); //DB 값이 없는 경우 배열선언 부분

		$adminapi[searchMode] = "$searchMode";
		$adminapi[totalCount] = "$count";

		while($a<$count && $count>0) {
			if($searchMode == 'lectureDay') {
				$adminapi[searchResult][$a][lectureStart] = mysql_result($result,$a,'lectureStart');
				$adminapi[searchResult][$a][lectureEnd] = mysql_result($result,$a,'lectureEnd');

			} else if($searchMode == 'study') {
				if($lectureDay != "") {
					if($request == "contents") {
						$adminapi[searchResult][$a][contentsCode] = mysql_result($result,$a,'contentsCode');
						$adminapi[searchResult][$a][contentsName] = mysql_result($result,$a,'contentsName');

					} else {
						$adminapi[searchResult][$a][companyCode] = mysql_result($result,$a,'companyCode');
						$adminapi[searchResult][$a][companyName] = mysql_result($result,$a,'companyName');
					}

				} else {
					$adminapi[searchResult][$a][lectureStart] = mysql_result($result,$a,'lectureStart');
					$adminapi[searchResult][$a][lectureEnd] = mysql_result($result,$a,'lectureEnd');
				}

			} else {
				$adminapi[searchResult][$a][seq] = mysql_result($result,$a,'seq');
				$adminapi[searchResult][$a][searchCode] = mysql_result($result,$a,$aSearchCode);
				$adminapi[searchResult][$a][searchName] = mysql_result($result,$a,$aSearchName);
			}
			$a++;
		}

		$json_encoded = json_encode($adminapi);
		print_r($json_encoded);

	} else {
		echo "error";
		exit;
	}
?>