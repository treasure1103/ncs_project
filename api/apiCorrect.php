<?php
	header('Content-Type:application/json; charset=utf-8');
?>
<? include '../lib/header.php'; ?>
<?
		$searchMode = $_GET['searchMode']; // 월별 검색, 수강날짜별 검색
		$searchYear = STR_REPLACE('년','',$_GET['searchYear']);
		$searchMonth = STR_REPLACE('월','',$_GET['searchMonth']);
		if(STRLEN($searchMonth)==1) { 
			$searchMonth = "0".$searchMonth;
		}
		$list = $_GET['list'];
		$page = $_GET['page'];
		$seq = $_GET['seq'];
		$contentsCode = $_GET['contentsCode']; // 과정코드
		$contentsName = $_GET['contentsName']; // 과정명
		$tutorID = $_GET['tutorID']; // 첨삭(채점)강사 아이디
		$tutorName = $_GET['tutorName']; // 첨삭(채점)강사 아이디
		$userID = $_GET['userID']; // 훈련생 아이디
		$userName = $_GET['userName']; // 훈련생 이름
		$lectureDay = $_GET['lectureDay']; // 수강일
		$lectureSE = EXPLODE('~',$lectureDay);
		$testStatus = $_GET['testStatus']; //시험응시여부
		$midStatus = $_GET['midStatus']; // 중간시험응시여부
		$reportStatus = $_GET['reportStatus']; //레포트응시여부, 반려확인
		
		if($searchMode == "month") {
			if($searchYear && $searchMonth){
				if($searchMonth == "0") {
					$qSearchYM =  "";
				} else {
					$qSearchYM =  "AND MID(A.lectureStart,6,2)='".$searchMonth."'";
				}
			}
		} else {
			if($searchYear && $searchMonth){
				if($searchMonth == "0") {
					$qSearchYM =  "AND LEFT(A.lectureStart,4)='".$searchYear."'";
				} else {
					$qSearchYM =  "AND LEFT(A.lectureStart,7)='".$searchYear."-".$searchMonth."'";
				}
			}
		}
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
		if($contentsCode != "") {
			$qContentsCode = " AND A.contentsCode='".$contentsCode."'";
		}
		if($companyCode != "") {
			$qCompanyCode = " AND A.companyCode='".$companyCode."'";
		}
		if($lectureDay != "") {
			$qLectureStart = " AND (A.lectureStart='".TRIM($lectureSE[0])."' AND A.lectureEnd='".TRIM($lectureSE[1])."')";
		}
		if($midStatus != "") {
			$qMidStatus = " AND A.midStatus='".$midStatus."'";
		}
		if($testStatus != "") {
			$qTestStatus = " AND A.testStatus='".$testStatus."'";
		}
		if($reportStatus != "") {
			$qReportStatus = " AND A.reportStatus='".$reportStatus."'";
		}
		if($monitor == "Y") {
			$qMonitor = " AND (A.midStatus IN ('Y','C') OR A.testStatus IN ('Y','C') OR A.reportStatus IN ('Y','C'))";
		}

		$qSearch = $qSeq.$qLectureOpenSeq.$qContentsCode.$qContentsName.$qMidStatus.$qTestStatus.$qReportStatus.$qCompanyCode.$qSearchYM;
		$qSearch .= $qServiceType.$qLectureStart.$qTestCopy.$qReportCopy.$qPassOK.$qMonitor.$qTutorList;

		$que = "SELECT A.*, 
									 IF(ISNULL(B.userName),'입력오류',B.userName) AS userName, 
									 IF(ISNULL(C.userName),'입력오류',C.userName) AS tutorName, 
									 IF(ISNULL(E.contentsName),'입력오류',E.contentsName) AS contentsName
						FROM nynStudy AS A
						LEFT OUTER
						JOIN nynMember AS B ON A.userID=B.userID
						LEFT OUTER
						JOIN nynMember AS C ON A.tutor=C.userID AND C.userLevel=7
						LEFT OUTER
						JOIN nynContents AS E ON A.contentsCode=E.contentsCode 
						LEFT OUTER
						JOIN nynReportAnswer AS D ON A.userID=D.userID AND A.lectureOpenSeq=D.lectureOpenSeq
						WHERE A.serviceType in ('1','5') AND (A.midStatus <> 'N' or A.testStatus <> 'N' or A.reportStatus <> 'N' ) ".$qSearch;
		$res = mysql_query($que);
		$allPost = mysql_num_rows($res);
		$currentLimit = ($list * $page) - $list; //몇 번째의 글부터 가져오는지
		$sqlLimit = ' LIMIT '.$currentLimit.', '.$list; //limit sql 구문

		$query = "SELECT A.*, 
										 IF(ISNULL(B.userName),'입력오류',B.userName) AS userName, 
										 IF(ISNULL(C.userName),'입력오류',C.userName) AS tutorName, 
										 IF(ISNULL(E.contentsName),'입력오류',E.contentsName) AS contentsName
							FROM nynStudy AS A
							LEFT OUTER
							JOIN nynMember AS B ON A.userID=B.userID
							LEFT OUTER
							JOIN nynMember AS C ON A.tutor=C.userID AND C.userLevel=7
							LEFT OUTER
							JOIN nynContents AS E ON A.contentsCode=E.contentsCode 
							LEFT OUTER
							JOIN nynReportAnswer AS D ON A.userID=D.userID AND A.lectureOpenSeq=D.lectureOpenSeq
							WHERE A.serviceType in ('1','5') AND (A.midStatus <> 'N' or A.testStatus <> 'N' or A.reportStatus <> 'N' ) ".$qSearch." 
							ORDER BY ".$sortType." ".$sortValue.$sqlLimit;
		$result = mysql_query($query);
		$count = mysql_num_rows($result);

		$a = 0;
		$adminapi = array();
		$adminapi[totalCount] = "$allPost";

		while($rs = mysql_fetch_array($result)) {
			$adminapi[study][$a][seq] = $rs['seq'];
			$adminapi[study][$a][user][userID] = $rs['userID'];
			$adminapi[study][$a][user][userName] = $rs['userName'];
			$adminapi[study][$a][contents][contentsCode] = $rs['contentsCode'];
			$adminapi[study][$a][contents][contentsName] = $rs['contentsName'];
			$adminapi[study][$a][tutor][tutorID] = $rs['tutor'];
			$adminapi[study][$a][tutor][tutorName] = $rs['tutorName'];
			$adminapi[study][$a][lectureStart] = $rs['lectureStart'];
			$adminapi[study][$a][lectureEnd] = $rs['lectureEnd'];
			$tutorDeadline = date("Y-m-d", strtotime($rs['lectureEnd']."+2Day"));
			$adminapi[study][$a][tutorDeadline] = $tutorDeadline;
			
			if($rs['serviceType'] == "3"){
					$midCheckTime ="평가없음";
					$testCheckTime ="평가없음";
					$reportCheckTime ="과제없음";
			}else {
				if($rs['midStatus'] == "N"){
					$midCheckTime ="미응시";
				}else {
					if($rs['midCheckTime'] == null){ 
						$midCheckTime ="첨삭 대기중";
					} else {
						$midCheckTime= $rs['midCheckTime'];
					}
				}
				
				if($rs['testStatus'] == "N"){
					$testCheckTime ="미응시";
				}else {
					if($rs['testCheckTime'] == null ){
						$testCheckTime ="첨삭 대기중";
					} else {
						$testCheckTime= $rs['testCheckTime'];
					}
				}
				
				if($rs['reportStatus'] == "N"){
					$reportCheckTime = "미응시";
				}else if ($rs['reportStatus'] ==null){
					$reportCheckTime = "과제없음";
				}else {
					if($rs['reportCheckTime'] == null ){
						$reportCheckTime ="첨삭 대기중";
					} else {
						$reportCheckTime= $rs['reportCheckTime'];
					}
				}				
			}
			$adminapi[study][$a][midCheckTime] = $midCheckTime;
			$adminapi[study][$a][testCheckTime] = $testCheckTime;
			$adminapi[study][$a][reportCheckTime] = $reportCheckTime;
			$adminapi[study][$a][totalCheckTime] = $rs['totalCheckTime'];
			$adminapi[study][$a][midCheckIP] = $rs['midCheckIP'];
			$adminapi[study][$a][testCheckIP] = $rs['testCheckIP'];
			$adminapi[study][$a][reportCheckIP] = $rs['reportCheckIP'];
			$adminapi[study][$a][midStatus] = $rs['midStatus'];
			$adminapi[study][$a][testStatus] = $rs['testStatus'];
			$adminapi[study][$a][reportStatus] = $rs['reportStatus'];

			$a++;
		}
		$json_encoded = json_encode($adminapi);
		print_r($json_encoded);

		
	@mysql_close();

?>