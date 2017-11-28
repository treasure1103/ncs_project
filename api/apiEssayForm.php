<?php
		header('Content-Type:application/json; charset=utf-8');
?>
<? include '../lib/header.php'; ?>
<?
	if($method == "GET") {  
		$searchYear = STR_REPLACE('년','',$_GET['searchYear']);
		$searchMonth = STR_REPLACE('월','',$_GET['searchMonth']);
		if(STRLEN($searchMonth)==1) { 
			$searchMonth = "0".$searchMonth;
		}
		$companyCode = $_GET['companyCode'];
		$surveySeq = $_GET['surveySeq'];
		$lectureDay = $_GET['lectureDay'];		
		$lectureSE = EXPLODE('~',$lectureDay);
		$type = $_GET['type'];

		if($page == "") {
			$page = 1;
		}
		if($list == "") {
			$list = 10;
		}
		if($surveySeq != "") {
			$queryQ = " AND A.surveySeq=".$surveySeq;
		}else {
			$queryQ = " AND A.surveySeq in ('5','6')";
		}
		if($companyCode != "") {
			$queryQ .= " AND D.companyCode='".$companyCode."'";
		}
		if(!$lectureDay) {
			if($searchMonth == "0") {
				$qSearchYM =  " AND LEFT(D.lectureStart,4)='".$searchYear."'";
			} else {
				$qSearchYM =  " AND LEFT(D.lectureStart,7)='".$searchYear."-".$searchMonth."'";
			}
		} else {
			$qSearchYM =  " AND (D.lectureStart='".TRIM($lectureSE[0])."' AND D.lectureEnd='".TRIM($lectureSE[1])."')";
		}

		if($type == "survey") {
				$queryS = "SELECT orderby,exam from nynSurvey where surveyType='B' AND orderBy in ('5','6')";
				$resultB = mysql_query($queryS);
				$allPost = mysql_num_rows($resultB);
		}else {
			$queryZ = " SELECT A.userID,A.surveySeq, A.userTextAnswer, A.contentsCode,C.userName, D.lectureStart, D.lectureEnd, D.lectureEnd, B.contentsName, E.companyName, E.companyCode
								FROM nynSurveyAnswer AS A
								LEFT OUTER
								JOIN nynContents AS B ON A.contentsCode=B.contentsCode
								LEFT OUTER
								JOIN nynMember AS C ON A.userID=C.userID
								LEFT OUTER
								JOIN nynStudy AS D ON A.lectureOpenSeq=D.lectureOpenSeq AND A.userID=D.userID
								LEFT OUTER
								JOIN nynCompany AS E ON D.companyCode=E.companyCode
								WHERE A.seq <> 0 ".$queryQ.$qSearchYM;
			$resultZ = mysql_query($queryZ);
			$allPost = mysql_num_rows($resultZ);
			$currentLimit = ($list * $page) - $list; //몇 번째의 글부터 가져오는지
			$sqlLimit = ' LIMIT '.$currentLimit.', '.$list; //limit sql 구문

			$queryB = " SELECT A.userID,A.surveySeq, A.userTextAnswer, A.contentsCode,C.userName, D.lectureStart, D.lectureEnd, D.lectureEnd, B.contentsName, E.companyName, E.companyCode
									FROM nynSurveyAnswer AS A
									LEFT OUTER
									JOIN nynContents AS B ON A.contentsCode=B.contentsCode
									LEFT OUTER
									JOIN nynMember AS C ON A.userID=C.userID									
									LEFT OUTER
									JOIN nynStudy AS D ON A.lectureOpenSeq=D.lectureOpenSeq AND A.userID=D.userID
									LEFT OUTER
									JOIN nynCompany AS E ON D.companyCode=E.companyCode
									WHERE A.seq <> 0 ".$queryQ.$qSearchYM." ORDER BY A.surveySeq, A.seq DESC ".$sqlLimit;
			$resultB = mysql_query($queryB);
		}		
		
		$b=0;
		$adminapi = array(); //DB 값이 없는 경우 배열선언 부분
		$adminapi[totalCount] = "$allPost"; //총 개시물 수

		while($rsB = mysql_fetch_array($resultB)) {
			$adminapi[survey][$b]['userID'] = $rsB['userID'];
			$adminapi[survey][$b]['userName'] = $rsB['userName'];
			$adminapi[survey][$b]['lectureStart'] = $rsB['lectureStart'];
			$adminapi[survey][$b]['lectureEnd'] = $rsB['lectureEnd'];
			$adminapi[survey][$b]['contentsCode'] = $rsB['contentsCode'];
			$adminapi[survey][$b]['contentsName'] = $rsB['contentsName'];
			$adminapi[survey][$b]['surveySeq'] = $rsB['surveySeq'];
			$adminapi[survey][$b]['userTextAnswer'] = $rsB['userTextAnswer'];
			$adminapi[survey][$b]['companyCode'] = $rsB['companyCode'];
			$adminapi[survey][$b]['companyName'] = $rsB['companyName'];	
			$adminapi[survey][$b]['orderby'] = $rsB['orderby'];	
			$adminapi[survey][$b]['exam'] = $rsB['exam'];	
				
			$b++;
		}

		$json_encoded = json_encode($adminapi);
		print_r($json_encoded);
	}
		
	@mysql_close();
?>