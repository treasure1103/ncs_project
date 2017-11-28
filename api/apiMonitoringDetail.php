<?php
	header('Content-Type:application/json; charset=utf-8');
?>
<? include '../lib/header.php'; ?>
<?
			$list = $_GET['list'];
			$page = $_GET['page'];
			$seq = $_GET['seq'];
			$lectureOpenSeq = $_GET['lectureOpenSeq']; // 개설차수
			$contentsCode = $_GET['contentsCode']; // 과정코드
			$contentsName = $_GET['contentsName']; // 과정명
			$serviceType = $_GET['serviceType']; // 환급, 능력개발, 일반 등 과정 구분
			$tutor = $_GET['tutor']; // 첨삭(채점)강사 아이디
			$tutorName = $_GET['tutorName']; // 첨삭(채점)강사 아이디
			$userID = $_GET['userID']; // 훈련생 아이디
			$userName = $_GET['userName']; // 훈련생 이름
			$lectureDay = $_GET['lectureDay']; // 수강일
		  $lectureSE = EXPLODE('~',$lectureDay);
			$testCopy = $_GET['testCopy']; // 모사답안여부
			$reportCopy = $_GET['reportCopy']; // 모사답안여부
			$passOK = $_GET['passOK']; // 수료여부
			$testStatus = $_GET['testStatus']; //시험응시여부
			$reportStatus = $_GET['reportStatus']; //레포트응시여부, 반려확인
			$monitor = $_GET['monitor'];
			$companyCode = $_GET['companyCode'];
/*
			if($lectureDay == "" && $companyCode == "" && $userID == ""){
				echo "value error";
				exit;
			}
*/
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
			if($lectureOpenSeq != "") {
				$qLectureOpenSeq = " AND A.lectureOpenSeq='".$lectureOpenSeq."'";
			}
			if($contentsCode != "") {
				$qContentsCode = " AND A.contentsCode='".$contentsCode."'";
			}
			if($companyCode != "") {
				$qCompanyCode = " AND A.companyCode='".$companyCode."'";
			}
			if($contentsName != "") {
				$qContentsName = " AND E.contentsName like '%".$contentsName."%'";
			}
			if($serviceType != "") {
				$qServiceType = " AND A.serviceType='".$serviceType."'";
			}
			if($lectureDay != "") {
				$qLectureStart = " AND (A.lectureStart='".TRIM($lectureSE[0])."' AND A.lectureEnd='".TRIM($lectureSE[1])."')";
			}
			if($testCopy != "") {
				$qTestCopy = " AND A.testCopy='".$testCopy."'";
			}
			if($reportCopy != "") {
				$qReportCopy = " AND A.reportCopy='".$reportCopy."'";
			}
			if($passOK != "") {
				$qPassOK = " AND A.passOK='".$passOK."'";
			}
			if($testStatus != "") {
				$qTestStatus = " AND A.testStatus='".$testStatus."'";
			}
			if($reportStatus != "") {
				$qReportStatus = " AND A.reportStatus='".$reportStatus."'";
			}
			if($monitor == "Y") {
				$qMonitor = " AND (A.testStatus IN ('Y','C') OR A.reportStatus IN ('Y','C'))";
			}

			$qSearch = $qSeq.$qLectureOpenSeq.$qContentsCode.$qContentsName.$qTestStatus.$qReportStatus.$qCompanyCode;
			$qSearch .= $qServiceType.$qLectureStart.$qTestCopy.$qReportCopy.$qPassOK.$qMonitor.$qTutorList;

			$que = "SELECT A.*, 
										 IF(ISNULL(B.userName),'입력오류',B.userName) AS userName, 
										 IF(ISNULL(C.userName),'입력오류',C.userName) AS tutorName, 
										 B.department, 
										 IF(ISNULL(E.contentsName),'입력오류',E.contentsName) AS contentsName, D.comment
							FROM nynStudy AS A
							LEFT OUTER
							JOIN nynMember AS B ON A.userID=B.userID
							LEFT OUTER
							JOIN nynMember AS C ON A.tutor=C.userID AND C.userLevel=7
							LEFT OUTER
							JOIN nynContents AS E ON A.contentsCode=E.contentsCode 
							LEFT OUTER
							JOIN nynReportAnswer AS D ON A.userID=D.userID AND A.lectureOpenSeq=D.lectureOpenSeq
							WHERE A.serviceType <> 9 ".$qSearch;
			$res = mysql_query($que);
			$allPost = mysql_num_rows($res);
			$currentLimit = ($list * $page) - $list; //몇 번째의 글부터 가져오는지
			$sqlLimit = ' LIMIT '.$currentLimit.', '.$list; //limit sql 구문

			$query = "SELECT A.*, 
											 IF(ISNULL(B.userName),'입력오류',B.userName) AS userName, 
											 IF(ISNULL(C.userName),'입력오류',C.userName) AS tutorName, 
											 B.department, 
											 IF(ISNULL(E.contentsName),'입력오류',E.contentsName) AS contentsName, D.comment
								FROM nynStudy AS A
								LEFT OUTER
								JOIN nynMember AS B ON A.userID=B.userID
								LEFT OUTER
								JOIN nynMember AS C ON A.tutor=C.userID AND C.userLevel=7
								LEFT OUTER
								JOIN nynContents AS E ON A.contentsCode=E.contentsCode 
								LEFT OUTER
								JOIN nynReportAnswer AS D ON A.userID=D.userID AND A.lectureOpenSeq=D.lectureOpenSeq
								WHERE A.serviceType <> 9 ".$qSearch." 
								ORDER BY ".$sortType." ".$sortValue.$sqlLimit;
			$result = mysql_query($query);
			$count = mysql_num_rows($result);

			$a = 0;
			$adminapi = array();
			$adminapi[totalCount] = "$allPost";

			while($rs = mysql_fetch_array($result)) {
				$adminapi[study][$a][seq] = $rs[seq];
				$adminapi[study][$a][user][userID] = $rs[userID];
				$adminapi[study][$a][user][userName] = $rs[userName];
				$adminapi[study][$a][contents][contentsCode] = $rs[contentsCode];
				$adminapi[study][$a][contents][contentsName] = $rs[contentsName];
				$adminapi[study][$a][tutor][tutorID] = $rs[tutor];
				$adminapi[study][$a][tutor][tutorName] = $rs[tutorName];
				$adminapi[study][$a][lectureOpenSeq] = $rs[lectureOpenSeq];
				$adminapi[study][$a][serviceType] = $rs[serviceType];
				$adminapi[study][$a][lectureStart] = $rs[lectureStart];
				$adminapi[study][$a][lectureEnd] = $rs[lectureEnd];
				$tutorDeadline = date("Y-m-d", strtotime($rs[lectureEnd]."+4Day"));
				$adminapi[study][$a][tutorDeadline] = $tutorDeadline;
				$adminapi[study][$a][midScore] = $rs[midScore];
				$adminapi[study][$a][midStatus] = $rs[midStatus];
				$adminapi[study][$a][testScore] = $rs[testScore];
				$adminapi[study][$a][testStatus] = $rs[testStatus];
				$adminapi[study][$a][reportScore] = $rs[reportScore];
				$adminapi[study][$a][reportStatus] = $rs[reportStatus];
				$adminapi[study][$a][totalScore] = $rs[totalScore];
				$adminapi[study][$a][testCopy] = $rs[testCopy];
				$adminapi[study][$a][reportCopy] = $rs[reportCopy];
				$adminapi[study][$a][midCheckIP] = $rs[midCheckIP];
				$adminapi[study][$a][testCheckIP] = $rs[testCheckIP];
				$adminapi[study][$a][reportCheckIP] = $rs[reportCheckIP];
				$sLectureStart = $rs[lectureStart]." 00:00:00";
				$sLectureEnd = $rs[lectureEnd]." 23:59:59";

				if($inputDate >= $sLectureStart && $inputDate <= $sLectureEnd) {
					$passOK = "W";
				} else {
					$passOK = $rs[passOK];
				}
				$adminapi[study][$a][passOK] = $passOK;
				$adminapi[study][$a][comment] = $rs[comment];
				$a++;
			}
			$json_encoded = json_encode($adminapi);
			print_r($json_encoded);
		
	@mysql_close();
?>