<?php
		header('Content-Type:application/json; charset=utf-8');
?>
<? include '../lib/header.php'; ?>
<?
	if($method == "POST") {
		$seq = $_POST['seq'];
		$serviceType = $_POST['serviceType'];
		$tutor = $_POST['tutorID'];
		$userID = $_POST['userID'];
		$lectureStart = $_POST['lectureStart'];
		$lectureEnd = $_POST['lectureEnd'];
		$period = $_POST['period'];
		$orderStatus = $_POST['orderStatus'];
		$progress = $_POST['progress'];
		$retaken  = $_POST['retaken'];
		$testType  = $_POST['testType'];

		if($retaken == "Y") { // 재응시 요청 처리
			$query ="SELECT seq, userID, contentsCode, lectureOpenSeq FROM nynStudy WHERE seq='".$seq."'";
			$resultR = mysql_query($query);
			$rsR = mysql_fetch_array($resultR);
			$countRS = mysql_num_rows($resultR);

			if($countRS > 0){
				$userIDRE = $rsR['userID'];
				$contentsCodeRE = $rsR['contentsCode'];
				$lectureOpenSeqRE = $rsR['lectureOpenSeq'];

				if($testType == "mid") {
					$queryB = " UPDATE nynStudy SET
											midSaveTime = null, midScore = null, midCheckTime = null, midCaptchaTime = null,
											midStatus = 'N', totalScore = null, midIP = null, reTest = '".$_SESSION['loginUserID']."', reDate='".$inputDate."', midSaveTime = null
											WHERE seq='".$seq."'";				
					$queryBD = "DELETE from nynTestAnswer where userID='".$userIDRE."' AND contentsCode ='".$contentsCodeRE."' AND lectureOpenSeq='".$lectureOpenSeqRE."' AND testType='mid'";
				} else {
					$queryB = " UPDATE nynStudy SET
											testStartTime = null, testEndTime = null, testScore = null,
											testStatus = 'N', totalScore = null, testIP = null, reTest = '".$_SESSION['loginUserID']."', reDate='".$inputDate."'
											WHERE seq='".$seq."'";				
					$queryBD = "DELETE from nynTestAnswer where userID='".$userIDRE."' AND contentsCode ='".$contentsCodeRE."' AND lectureOpenSeq='".$lectureOpenSeqRE."' AND testType='final'";					
				}

				$resultB = mysql_query($queryB);
				$resultBD = mysql_query($queryBD);
			}

			if($resultBD){
				echo "success";
			} else {
				echo "error";
			}
			exit;
		}

		if($userID == "") {
			echo "error";
			exit;
		}
		if($period == "") {
			$period = "2";
		}
		if($tutor == "") {
			$tutor = "tutor";
		}
		if($orderStatus != "") { // 값이 있으면 order에서 넘어온것, 배열로 받는다.
			$loopNum = count($_POST['contentsCode']);
		} else {
			$loopNum = "1";
		}

		$lectureReStudy = date("Y-m-d", strtotime($lectureEnd."+".$period."Month"));

		$queryA = "SELECT A.companyCode, B.companyScale 
							 FROM nynMember AS A
							 LEFT OUTER 
							 JOIN nynCompany AS B ON A.companyCode=B.companyCode
							 WHERE userID='".$userID."'";
		$resultA = mysql_query($queryA);
		$rsA = mysql_fetch_assoc($resultA);
		$companyCode = $rsA[companyCode];
		$companyScale = $rsA[companyScale];

				if($orderStatus != "") { // 값이 있으면 order에서 넘어온것, 배열로 받는다.
					$contentsCodeKey = ARRAY_KEYS($_POST['contentsCode']);
					$orderNum = $_POST['orderNum'];

					//결제 상태 업데이트. 이후 아래부터는 과정 등록
					$queryB = "UPDATE nynOrder SET orderStatus='".$orderStatus."' WHERE orderNum='".$orderNum."'";
					$resultB = mysql_query($queryB);

					if($orderStatus == "N") { // 취소인 경우 과정 삭제
						for($a=0; $a<$loopNum; $a++) {
							$contentsCodeValue = $contentsCodeKey[$a];
							$contentsCodeQ = $_POST['contentsCode'][$contentsCodeValue];

							$queryD = "DELETE FROM nynStudy 
												 WHERE contentsCode='".$contentsCodeQ."' 
												 AND lectureStart='".$lectureStart."' 
												 AND lectureEnd='".$lectureEnd."' 
												 AND userID='".$userID."'";
							$resultD = mysql_query($queryD);
						}

						if($resultD){
							echo "success";
						} else {
							echo "error";
						}
						exit;
					}

				} else {
					$contentsCodeQ = $_POST['contentsCode'];
				}

		for($i=0; $i<$loopNum; $i++) {

				if($orderStatus != "") { // 값이 있으면 order에서 넘어온것, 배열로 받는다.
					$contentsCodeValue = $contentsCodeKey[$i];
					$contentsCodeQ = $_POST['contentsCode'][$contentsCodeValue];
				}

				$queryC = " SELECT chapter, totalPassMid, totalPassTest, totalPassReport, price, rPrice01, rPrice02, rPrice03, sourceType
										FROM nynContents WHERE contentsCode='".$contentsCodeQ."'";
				$resultC = mysql_query($queryC);
				$rsC = mysql_fetch_assoc($resultC);
				$chapter = $rsC[chapter];
				$totalPassMid = $rsC[totalPassMid];
				$totalPassTest = $rsC[totalPassTest];
				$totalPassReport = $rsC[totalPassReport];
				$price = $rsC[price];
				$rPrice01 = $rsC[rPrice01];
				$rPrice02 = $rsC[rPrice02];
				$rPrice03 = $rsC[rPrice03];
				$sourceType = $rsC[sourceType];

				if($companyScale == "B") { // 대규모 1000인 미만
					$rPrice = $rPrice02;
				} else if($companyScale == "C") { // 대규모 1000인 이상
					$rPrice = $rPrice03;
				} else { // 우선지원 환급
					$rPrice = $rPrice01;
				}

				if($serviceType == "3") { // 일반(비환급)
					$rPrice = "0";
				}

				if($totalPassMid > 0 ) {
					$midStatusQ = "midStatus='N', ";
				}
				if($totalPassTest > 0 ) {
					$testStatusQ = "testStatus='N', ";
				}
				if($totalPassReport > 0 ) {
					$reportStatusQ = "reportStatus='N', ";
				}

				$queryL = "SELECT seq 
									 FROM nynLectureOpen 
									 WHERE lectureStart='".$lectureStart."' 
									 AND lectureEnd='".$lectureEnd."' 
									 AND contentsCode='".$contentsCodeQ."'";
				$resultL = mysql_query($queryL);
				$rsL = mysql_fetch_assoc($resultL);
				$countL = mysql_num_rows($resultL);

				if($serviceType == 0 || $serviceType == 1) {
					$serviceTypeL = 1;  //신청 시에만 0의 구분자가 있으며 실제 수강은 1로 등록
				} else {
					$serviceTypeL = $serviceType;
				}

				if($countL == 0) { // 처음 개설한다면 추가.
					$sql="INSERT INTO nynLectureOpen 
								SET lectureStart='".$lectureStart."',
										lectureEnd='".$lectureEnd."',
										contentsCode='".$contentsCodeQ."',
										serviceType='".$serviceTypeL."'";
					$result = mysql_query($sql);
					$lectureOpenSeq = mysql_insert_id();

				} else { // 이미 개설되어 있으면 값을 불러온다.
					$lectureOpenSeq = $rsL[seq];
				}

				if($progress == "100" || $progress == "80") {
					$progressQ = "progress='".$progress."', accessIP='".$userIP."', ";
				} else {
					$progressQ = "";
				}

				$queryQ =  "lectureOpenSeq=".$lectureOpenSeq.", 
										contentsCode='".$contentsCodeQ."',
										companyCode='".$companyCode."',
										serviceType='".$serviceTypeL."',
										tutor='".$tutor."',
										userID='".$userID."',
										lectureStart='".$lectureStart."',
										lectureEnd='".$lectureEnd."',
										lectureReStudy='".$lectureReStudy."',
										".$progressQ."
										".$midStatusQ."
										".$testStatusQ."
										".$reportStatusQ."
										price='".$price."',
										rPrice='".$rPrice."'";

				if($seq == "") { // 수강 등록
					$query = "INSERT INTO nynStudy SET ".$queryQ;
					$result = mysql_query($query);
					$seqV = mysql_insert_id();

				} else { // 수강 수정
					$query = "UPDATE nynStudy SET ".$queryQ." WHERE seq=".$seq;
					$result = mysql_query($query);
				}

				if($progress == "100" || $progress == "80") { //차수별 진도등록
					if($progress == "80"){
						$chapter = ROUND($chapter*(4/5));
					}
					for($m=1; $m<=$chapter; $m++) {

						if($sourceType == 'book') {
							//$queryK = "SELECT chapterSize FROM nynChapter WHERE contentsCode='".$contentsCodeQ."' and chapter='".$m."'";
							//$resultK = mysql_query($queryK);
							//$rsK = mysql_fetch_array($resultK);

							$chapterSizeQ = "mobileLastPage='99', ";
						}

						$queryG = " INSERT INTO nynProgress 
												SET lectureOpenSeq='".$lectureOpenSeq."', 
														userID='".$userID."',
														chapter='".$m."',
														contentsCode='".$contentsCodeQ."',
														progress='100',
														startTime='".$inputDate."',
														endTime='".$inputDate."',
														progressID=CONCAT('".$lectureOpenSeq."','_','".$contentsCodeQ."','_','".$_SESSION['loginUserID']."','_','".$m."'), 
														studyIP='".$userIP."', "
														.$chapterSizeQ."
														totalTime='0'";
						$resultG = mysql_query($queryG);
					}
				}
		}

				if($result){
					echo $seqV;
				} else {
					echo "error";
				}
				exit;

	} else if($method == "DELETE") { // 수강 삭제
			parse_str(file_get_contents("php://input"), $_DEL);
			$seq = $_DEL['seq'];
			$allDelete = $_DEL['allDelete'];
			$lectureDay = $_DEL['lectureDay'];
			$lectureSE = EXPLODE('~',$lectureDay);
			$companyCode = $_DEL['companyCode'];
			$inputDateA = EXPLODE(" ", $inputDate);

			if($_SESSION['loginUserLevel'] > 4) {
				echo '{"result" : "삭제권한이 없습니다."}';
				exit;
			}

			if($allDelete == "Y"){ //전체삭제
				if(!$lectureDay){
					echo '{"result" : "수강기간을 선택해주세요."}';
					exit;
				}
				if($companyCode ){
					$queryC = " AND companyCode='".$companyCode."'";
				}

					$queryD = "SELECT seq, contentsCode, lectureOpenSeq, userID FROM nynStudy WHERE lectureStart='".$lectureSE[0]."' AND lectureEnd='".$lectureSE[1]."'".$queryC;
					$resultD = mysql_query($queryD);

					while($rsD = mysql_fetch_array($resultD)){
						$queryU = "UPDATE nynStudy SET deleteID='".$_SESSION['loginUserID']."' WHERE seq=".$rsD['seq'];
						$resultU = mysql_query($queryU);

						$queryI = "INSERT INTO nynStudyBackup SELECT * FROM nynStudy WHERE seq=".$rsD['seq'];
						$resultI = mysql_query($queryI);

						$query = "DELETE FROM nynStudy WHERE seq=".$rsD['seq'];
						$result = mysql_query($queryB);

						//$query = "DELETE FROM nynProgress WHERE contentsCode='".$rsD['contentsCode']."' AND lectureOpenSeq='".$rsD['lectureOpenSeq']."' AND userID='".$rsD['userID']."'";
						//$result = mysql_query($query);
					}

					if($result){
						echo '{"result" : "success"}';
						exit;
					} else {
						echo '{"result" : "오류가 발생하였습니다. 다시 검색 후 시도해 주세요."}';
						exit;
					}
					exit;
			}

			//개별삭제
			$queryA = "SELECT contentsCode, lectureOpenSeq, userID FROM nynStudy WHERE seq=".$seq;
			$resultA = mysql_query($queryA);
			$rsA = mysql_fetch_array($resultA);
			$contentsCode = $rsA['contentsCode'];
			$lectureOpenSeq = $rsA['lectureOpenSeq'];
			$userID = $rsA['userID'];

			if($_SESSION['loginUserID']){ // 삭제한 사람의 ID 업데이트
				$queryU = "UPDATE nynStudy SET deleteID='".$_SESSION['loginUserID']."' WHERE seq=".$seq;
				$resultU = mysql_query($queryU);
			}

			//테이블 백업
			$queryI = "INSERT INTO nynStudyBackup SELECT * FROM nynStudy WHERE seq=".$seq;
			$resultI = mysql_query($queryI);

			$queryB = "DELETE FROM nynStudy WHERE seq=".$seq;
			$resultB = mysql_query($queryB);

			$query = "DELETE FROM nynProgress WHERE contentsCode='".$contentsCode."' AND lectureOpenSeq='".$lectureOpenSeq."' AND userID='".$userID."'";
			$result = mysql_query($query);

			if($result){
				echo "success";
			} else {
				echo "error";
			}
			exit;

	} else if($method == "GET") { // 회원사 정보 불러옴
			$list = $_GET['list'];
			$listCount = $_GET['listCount'];
			$page = $_GET['page'];
			$seq = $_GET['seq'];
			$lectureOpenSeq = $_GET['lectureOpenSeq']; // 개설차수
			$contentsCode = $_GET['contentsCode']; // 과정코드
			$contentsName = $_GET['contentsName']; // 과정명
			$companyCode = $_GET['companyCode']; // 사업자번호
			$companyName = $_GET['companyName']; // 회사명
			$serviceType = $_GET['serviceType']; // 환급, 능력개발, 일반 등 과정 구분
			$tutor = $_GET['tutor']; // 첨삭(채점)강사 아이디
			$tutorName = $_GET['tutorName']; // 첨삭(채점)강사 아이디
			$userID = $_GET['userID']; // 훈련생 아이디
			$userName = $_GET['userName']; // 훈련생 이름
			$lectureDay = $_GET['lectureDay']; // 수강일
		  $lectureSE = EXPLODE('~',$lectureDay);
			$progress01 = $_GET['progress01']; //진도율 범위 01
			$progress02 = $_GET['progress02']; //진도율 범위 02
			$testCopy = $_GET['testCopy']; // 모사답안여부
			$reportCopy = $_GET['reportCopy']; // 모사답안여부
			$passOK = $_GET['passOK']; // 수료여부
			$midStatus = $_GET['midStatus']; //중간응시여부
			$testStatus = $_GET['testStatus']; //최종응시여부
			$reportStatus = $_GET['reportStatus']; //레포트응시여부, 반려확인
			$monitor = $_GET['monitor'];
			$correct = $_GET['correct'];
			$searchMode = $_GET['searchMode']; // 월별 검색, 수강날짜별 검색
			$searchYear = STR_REPLACE('년','',$_GET['searchYear']);
			$searchMonth = STR_REPLACE('월','',$_GET['searchMonth']);
			$department = $_GET['department'];

			$searchType     = $_GET['searchType'];
			$searchValue    = $_GET['searchValue'];

			if ($searchType != "") {
				switch ($searchType) {
					case 'searchUserName':
						$qSearchName = "AND (A.userID LIKE '%".$searchValue."%' OR B.userName LIKE '%".$searchValue."%')";
						break;

					case 'searchMarketer':
						$qSearchMarketer = "AND (D.marketerID LIKE '%".$searchValue."%' OR H.userName LIKE '%".$searchValue."%')";
						break;

					case 'searchTutor':
						$qSearchTutor = "AND (A.tutor LIKE '%".$searchValue."%' OR C.userName LIKE '%".$searchValue."%')";
						break;
				}
			}

			if(STRLEN($searchMonth)==1) {
				$searchMonth = "0".$searchMonth;
			}
/*
			if($lectureDay == "" && $companyCode == "" && $userID == ""){
				echo "value error";
				exit;
			}
*/
			if(!$_SESSION['loginUserID']) {
					echo '{"result" : "로그아웃되었습니다."}';
					exit;
			}

			if($list == "") {
				$list = 10;
			}
			if($listCount != "") {
				$list = $listCount;
			}
			if($page == "") {
				$page = 1;
			}
			if($sortType == "") {
				$sortType = "A.lectureStart DESC, A.lectureEnd,B.userName, E.contentsName";
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
			if($contentsName != "") {
				$qContentsName = " AND E.contentsName like '%".$contentsName."%'";
			}
			if($companyCode != "") {
				$qCompanyCode = " AND A.companyCode='".$companyCode."'";
			}
			if($companyName != "") {
				$qCompanyName = " AND D.companyName LIKE '%".$companyName."%'";
			}
			if($serviceType != "") {
				$qServiceType = " AND A.serviceType='".$serviceType."'";
			}
			if($tutor != "") {
				$qTutor = " AND A.tutor='".$tutor."'";
			}
			if($tutorName != "") {
				$qTutorName = " AND C.userName like '%".$tutorName."%'";
			}
			if($userID != "") {
				$qUserID = " AND A.userID='".$userID."'";
			}
			if($userName != "") {
				$qUserName = " AND B.userName LIKE '%".$userName."%'";
			}
			if($lectureDay != "") {
				$qLectureStart = " AND (A.lectureStart='".TRIM($lectureSE[0])."' AND A.lectureEnd='".TRIM($lectureSE[1])."')";
			}
			if($progress01 != "" && $progress02 != "") {
				$qProgress = " AND (A.progress BETWEEN ".$progress01." AND ".$progress02.")";
			}
			if($testCopy != "") {
				$qTestCopy = " AND A.testCopy='".$testCopy."'";
			}
			if($reportCopy != "") {
				$qReportCopy = " AND (A.testCopy='".$reportCopy."' OR A.reportCopy='".$reportCopy."')";
			}
			if($passOK != "") {
				$qPassOK = " AND A.passOK='".$passOK."'";
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
				$qMonitor = " AND (A.testStatus IN ('Y','C') OR A.reportStatus IN ('Y','C'))";
			}
			if($correct == "N")  {
				$qCorrect = " AND (midStatus='Y' OR testStatus='Y' OR reportStatus='Y' OR (testStatus='V' AND NOW() >= testEndTime))";
			}
			if($_SESSION[loginUserLevel] == '5') {
				$queryM = "SELECT * FROM nynMatching WHERE matchingType='marketer' AND matchingValue='".$_SESSION[loginUserID]."'";
				$resultM = mysql_query($queryM);
				$countM = mysql_num_rows($resultM);
				$qMarketer = "AND D.marketerID IN (";
				$m = 1;
				if($countM != 0) {
					while($rsM = mysql_fetch_array($resultM)) {
						$qMarketer .= "'".$rsM[userID]."'"; 
						if($countM != $m) {
							$qMarketer .= ", ";
						} else {
							$qMarketer .= ") ";
						}
						$m++;
					}
				} else {
					$qMarketer = "AND D.marketerID='".$_SESSION[loginUserID]."'";
				}
			}
			if($_SESSION[loginUserLevel] == '6') {
				$qMarketer = "AND D.marketerID='".$_SESSION[loginUserID]."'";
			}
			if($_SESSION[loginUserLevel] == '7') {
				$qTutorList = "AND A.tutor='".$_SESSION[loginUserID]."'";
			}
			if($_SESSION[loginUserLevel] == '8') {
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

			$qSearch = $qSeq.$qLectureOpenSeq.$qContentsCode.$qContentsName.$qCompanyCode.$qCompanyName.$qMidStatus.$qTestStatus.$qReportStatus.$qCorrect.$qMarketer;
			$qSearch .= $qSearchName.$qSearchMarketer.$qSearchTutor;
			$qSearch .= $qServiceType.$qTutor.$qTutorName.$qUserID.$qUserName.$qLectureStart.$qProgress.$qTestCopy.$qReportCopy.$qPassOK.$qMonitor.$qTutorList.$qUserList;

			$que = "SELECT A.*, 
										 A.companyCode, 
										 D.companyName, 
										 B.department, 
										 F.certPass,
										 E.sort01
							FROM nynStudy AS A
							LEFT OUTER
							JOIN nynMember AS B ON A.userID=B.userID
							LEFT OUTER
							JOIN nynMember AS C ON A.tutor=C.userID AND C.userLevel=7
							LEFT OUTER
							JOIN nynMember AS G ON A.marketer=G.userID
							LEFT OUTER
							JOIN nynCompany AS D ON A.companyCode=D.companyCode
							LEFT OUTER
							JOIN nynContents AS E ON A.contentsCode=E.contentsCode 
							LEFT OUTER
							JOIN nynCert AS F ON A.userID=F.userID AND A.lectureStart=F.lectureStart
							WHERE B.userLevel <> 12 ".$qSearch;
			$res = mysql_query($que);
			$allPost = mysql_num_rows($res);
			$currentLimit = ($list * $page) - $list; //몇 번째의 글부터 가져오는지
			$sqlLimit = ' LIMIT '.$currentLimit.', '.$list; //limit sql 구문

			$query = "SELECT A.*, 
											 IF(ISNULL(B.userName),'입력오류',B.userName) AS userName, 
											 IF(ISNULL(C.userName),'배정오류',C.userName) AS tutorName, 
											 A.companyCode, 
											 D.companyName, 
											 B.department, 
											 IF(ISNULL(E.contentsName),'입력오류',E.contentsName) AS contentsName,
											 F.certPass,
											 E.sort01,
											 IF(ISNULL(G.userName),'배정오류',G.userName) AS marketerName
								FROM nynStudy AS A
								LEFT OUTER
								JOIN nynMember AS B ON A.userID=B.userID
								LEFT OUTER
								JOIN nynMember AS C ON A.tutor=C.userID AND C.userLevel=7
								LEFT OUTER
								JOIN nynMember AS G ON A.marketer=G.userID
								LEFT OUTER
								JOIN nynCompany AS D ON A.companyCode=D.companyCode
								LEFT OUTER
								JOIN nynContents AS E ON A.contentsCode=E.contentsCode 
								LEFT OUTER
								JOIN nynCert AS F ON A.userID=F.userID AND A.lectureStart=F.lectureStart
								WHERE B.userLevel <> 12 ".$qSearch." 
								ORDER BY ".$sortType." ".$sortValue.$sqlLimit;
			$result = mysql_query($query);
			$count = mysql_num_rows($result);

			$a = 0;
			$adminapi = array(); //DB 값이 없는 경우 배열선언 부분
			$adminapi[nowTime] = $inputDate;
			$adminapi[totalCount] = "$allPost"; //총 개시물 수

			while($rs = mysql_fetch_array($result)) {
				$adminapi[study][$a][seq] = $rs[seq];
				$adminapi[study][$a][user][userID] = $rs[userID];
				$adminapi[study][$a][user][userName] = $rs[userName];
				$adminapi[study][$a][company][companyCode] = $rs[companyCode];
				$adminapi[study][$a][company][companyName] = $rs[companyName];
				$adminapi[study][$a][contents][contentsCode] = $rs[contentsCode];
				$adminapi[study][$a][contents][contentsName] = $rs[contentsName];
				$adminapi[study][$a][contents][sort01] = $rs[sort01];
				$adminapi[study][$a][tutor][tutorID] = $rs[tutor];
				$adminapi[study][$a][tutor][tutorName] = $rs[tutorName];
				$adminapi[study][$a][marketer][marketerID] = $rs[marketer];
				$adminapi[study][$a][marketer][marketerName] = $rs[marketerName];
				$adminapi[study][$a][lectureOpenSeq] = $rs[lectureOpenSeq];
				$adminapi[study][$a][serviceType] = $rs[serviceType];
				$adminapi[study][$a][lectureStart] = $rs[lectureStart];
				$adminapi[study][$a][lectureEnd] = $rs[lectureEnd];
				$adminapi[study][$a][lectureReStudy] = $rs[lectureReStudy];
				$tutorDeadline = date("Y-m-d", strtotime($rs[lectureEnd]."+4Day"));
				$adminapi[study][$a][tutorDeadline] = $tutorDeadline;
				$adminapi[study][$a][progress] = $rs[progress];
				$adminapi[study][$a][accessIP] = $rs[accessIP];
				$adminapi[study][$a][midScore] = $rs[midScore];
				$adminapi[study][$a][midStatus] = $rs[midStatus];
				$adminapi[study][$a][testStartTime] = $rs[testStartTime];
				$adminapi[study][$a][testEndTime] = $rs[testEndTime];
				$adminapi[study][$a][testTempSaveTime] = $rs[testTempSaveTime];
				$adminapi[study][$a][testCaptchaTime] = $rs[testCaptchaTime];
				$adminapi[study][$a][testScore] = $rs[testScore];
				$adminapi[study][$a][testStatus] = $rs[testStatus];
				$adminapi[study][$a][reportCaptchaTime] = $rs[reportCaptchaTime];
				$adminapi[study][$a][reportScore] = $rs[reportScore];
				$adminapi[study][$a][reportStatus] = $rs[reportStatus];
				$adminapi[study][$a][totalScore] = $rs[totalScore];
				$adminapi[study][$a][midSaveTime] = $rs[midSaveTime];
				$adminapi[study][$a][midCheckTime] = $rs[midCheckTime];
				$adminapi[study][$a][testSaveTime] = $rs[testSaveTime];
				$adminapi[study][$a][testCheckTime] = $rs[testCheckTime];
				$adminapi[study][$a][reportSaveTime] = $rs[reportSaveTime];
				$adminapi[study][$a][reportCheckTime] = $rs[reportCheckTime];
				$adminapi[study][$a][midIP] = $rs[midIP];
				$adminapi[study][$a][testIP] = $rs[testIP];
				$adminapi[study][$a][reportIP] = $rs[reportIP];
				$adminapi[study][$a][midCheckIP] = $rs[midCheckIP];
				$adminapi[study][$a][testCheckIP] = $rs[testCheckIP];
				$adminapi[study][$a][reportCheckIP] = $rs[reportCheckIP];
				$adminapi[study][$a][midTutorTempSave] = $rs[midTutorTempSave];
				$adminapi[study][$a][testTutorTempSave] = $rs[testTutorTempSave];
				$adminapi[study][$a][reportTutorTempSave] = $rs[reportTutorTempSave];

				if($rs[midTutorTempSave] == 'Y') { // 중간평가 임시채점이 된 경우 가점수 출력
					$queryMid = "SELECT SUM(score) AS tempMidScore FROM nynTestAnswer 
											 WHERE userID='".$rs[userID]."' AND lectureOpenSeq='".$rs[lectureOpenSeq]."' AND contentsCode='".$rs[contentsCode]."' AND testType='mid'";
					$resultMid = mysql_query($queryMid);
					$rsMid = mysql_fetch_array($resultMid);

					$adminapi[study][$a][tempMidScore] = $rsMid[tempMidScore];
				}
				if($rs[testTutorTempSave] == 'Y') { // 최종평가 임시채점이 된 경우 가점수 출력
					$queryFinal = "SELECT SUM(score) AS tempTestScore FROM nynTestAnswer 
												 WHERE userID='".$rs[userID]."' AND lectureOpenSeq='".$rs[lectureOpenSeq]."' AND contentsCode='".$rs[contentsCode]."' AND testType='final'";
					$resultFinal = mysql_query($queryFinal);
					$rsFinal = mysql_fetch_array($resultFinal);

					$adminapi[study][$a][tempTestScore] = $rsFinal[tempTestScore];
				}
				if($rs[reportTutorTempSave] == 'Y') { // 과제 임시채점이 된 경우 가점수 출력
					$queryReport = "SELECT SUM(score) AS tempReportScore FROM nynReportAnswer 
													WHERE userID='".$rs[userID]."' AND lectureOpenSeq='".$rs[lectureOpenSeq]."' AND contentsCode='".$rs[contentsCode]."'";
					$resultReport = mysql_query($queryReport);
					$rsFinal = mysql_fetch_array($resultReport);

					if($rsFinal[tempReportScore] == null) {
						$tempReportScore = '-';
					} else {
						$tempReportScore = $rsFinal[tempReportScore];
					}

					$adminapi[study][$a][tempReportScore] = $tempReportScore;
				}

				$adminapi[study][$a][reTest] = $rs[reTest];
				$adminapi[study][$a][reDate] = $rs[reDate];
				$adminapi[study][$a][testCopy] = $rs[testCopy];
				$adminapi[study][$a][reportCopy] = $rs[reportCopy];

				$sLectureStart = $rs[lectureStart]." 00:00:00";
				$sLectureEnd = $rs[lectureEnd]." 23:59:59";

				/*if($inputDate >= $sLectureStart && $inputDate <= $sLectureEnd) {
					$passOK = "W";
				} else { */
					$passOK = $rs[passOK];
				//}
				$adminapi[study][$a][passOK] = $passOK;
				$adminapi[study][$a][failReason] = $rs[failReason];
				$adminapi[study][$a][price] = $rs[price];
				$adminapi[study][$a][rPrice] = $rs[rPrice];
				$adminapi[study][$a][certPass] = $rs[certPass];
				$adminapi[study][$a][writerID] = $rs[writerID];
				$adminapi[study][$a][writeDate] = $rs[writeDate];
				$a++;
			}
			$json_encoded = json_encode($adminapi);
			print_r($json_encoded);
		}
		
	@mysql_close();
?>