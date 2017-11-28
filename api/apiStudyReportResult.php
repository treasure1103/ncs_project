<?php
		header('Content-Type:application/json; charset=utf-8');
?>
<? include '../lib/header.php'; ?>
<?
	if($method == "POST") {
		$seq = $_POST['seq'];
		$userID = $_POST['userID'];
		$lectureOpenSeq = $_POST['lectureOpenSeq'];
		$contentsCode = $_POST['contentsCode'];
		$reportScore = $_POST['reportScore'];
		$comment = addslashes($_POST['comment']); 
		$reportCopy = $_POST['reportCopy']; //모사답안 여부
		$return = $_POST['return']; //반려여부
		$temp = $_POST['temp']; //임시저장여부
		$reScore = $_POST['reScore']; //재채점처리여부

			if($reScore == "Y"){
				$queryR = " UPDATE nynStudy SET reportStatus='Y'
										WHERE userID='".$userID."' 
										AND contentsCode='".$contentsCode."' 
										AND lectureOpenSeq='".$lectureOpenSeq."'";
				$resultR = mysql_query($queryR);

				if($resultR) {
					echo '{"result" : "success"}';
				} else {
					echo '{"result" : "error"}';
				}
				exit;
			}

			if($temp == "Y"){
				$tempQ = ", reportTutorTempSave='Y'";
			} else {
				$tempQ = ", reportTutorTempSave=null";
			}

			if($reportCopy == ""){
				$reportCopy = "N";
				$reportCopyQ = ", reportCopy='N'";
			} else {
				$reportCopyQ = ", reportCopy='".$reportCopy."'";
			}

			if($reportCopy == "Y"){ // 모사답안 처리
				$queryB = " UPDATE nynReportAnswer
										SET comment='".$comment."'
										WHERE seq=".$seq;
				$resultB = mysql_query($queryB);

				$queryY = " UPDATE nynStudy 
										SET reportCopy='Y', 
												passOK='N'".$tempQ."
										WHERE userID='".$userID."' 
										AND contentsCode='".$contentsCode."' 
										AND lectureOpenSeq='".$lectureOpenSeq."'";
				$resultY = mysql_query($queryY);

				if($resultY){
					echo '{"result" : "success"}';
				} else {
					echo '{"result" : "error"}';
				}
					exit;
			}

			if($return == "Y"){ // 반려 처리
				$queryA = " UPDATE nynReportAnswer
										SET comment='".$comment."'
										WHERE seq=".$seq;
				$resultA = mysql_query($queryA);

				$queryZ = " UPDATE nynStudy 
										SET reportStatus='R'".$reportCopyQ.$tempQ."
										WHERE userID='".$userID."' 
										AND contentsCode='".$contentsCode."' 
										AND lectureOpenSeq='".$lectureOpenSeq."'";
				$resultZ = mysql_query($queryZ);

				if($resultZ){
					echo '{"result" : "success"}';
				} else {
					echo '{"result" : "error"}';
				}
					exit;

			} else {
				$queryZ = " UPDATE nynStudy 
										SET reportStatus='Y'".$reportCopyQ.$tempQ."
										WHERE userID='".$userID."' 
										AND contentsCode='".$contentsCode."' 
										AND lectureOpenSeq='".$lectureOpenSeq."'";
				$resultZ = mysql_query($queryZ);
			}

			if($reportScore != ""){
				$reportScoreQ = ", score='".$reportScore."'";
			}

			// 첨삭내용 처리
			$queryA = " UPDATE nynReportAnswer
									SET comment='".$comment."'".$reportScoreQ."
									WHERE seq=".$seq;
			$resultA = mysql_query($queryA);

			if($temp == "Y") {
				echo '{"result" : "success"}';
				exit;
			}

			// 수료 정보 확인
			$queryE = "SELECT passProgress, 
												totalPassMid,
												passTest, 
												totalPassTest,
												passReport, 
												totalPassReport,
												passScore, 
												reportEA,
												midRate,
												testRate,
												reportRate
								 FROM nynContents 
								 WHERE contentsCode='".$contentsCode."'";
			$resultE = mysql_query($queryE);
			$rsE = mysql_fetch_array($resultE);

			//총점 계산
			$queryD = " SELECT progress, midScore, midStatus, testStatus, testScore
									FROM nynStudy 
									WHERE lectureOpenSeq='".$lectureOpenSeq."' 
									AND contentsCode='".$contentsCode."' 
									AND userID='".$userID."'";
			$resultD = mysql_query($queryD);
			$rsD = mysql_fetch_array($resultD);

			//중간평가 환산 점수
			if($rsD[midStatus] == "C") { // 채점완료면 중간평가 점수 환산 소수1자리까지 구함 2자리 이하 버림
				//$conversionMid = floor((($rsD[midScore]/$rsE[totalPassMid])*$rsE[midRate])*10)/10;
				$conversionMid = ROUND(($rsD[midScore]*$rsE[midRate]/100),1);
			} else { // 미채점이면 0으로 계산
				$conversionMid = 0;
			}

			//레포트 환산 점수
			//$conversionReport = floor((($reportScore/$rsE[totalPassReport])*$rsE[reportRate])*10)/10;
			$conversionReport = ROUND(($reportScore*$rsE[reportRate]/100),1);

			if($rsD[testStatus] == "C") { // 최종평가가 채점완료면 최종평가 점수 환산
				//$conversionTest = floor((($rsD[testScore]/$rsE[totalPassTest])*$rsE[testRate])*10)/10;
				$conversionTest = ROUND(($rsD[testScore]*$rsE[testRate]/100),1);
			} else { // 미채점이면 0으로 계산
				$conversionTest = 0;
			}

			//중간+최종+과제 점수 합산
			$totalScore = $conversionMid+$conversionTest+$conversionReport;

			// 수료 여부 확인
			if($rsD[progress] >= $rsE[passProgress]) { // 1. 수료기준 : 진도율 확인

				if($rsE[passScore] <= $totalScore) { // 2. 수료기준 : 총점 확인

					if($rsE[passTest] <= $rsD[testScore]) { // 3. 수료기준 : 평가시험 점수 확인

							if($rsE[passReport] <= $reportScore) { // 4. 수료기준 : 과제 점수 확인
								$passOK = "Y";

							} else {
								$passOK = "N";
							}

					} else {
						$passOK = "N";
					}

				} else {
					$passOK = "N";
				}

			} else {
				$passOK = "N";
			}

			$queryZ = " UPDATE nynStudy 
									SET reportScore='".$reportScore."',
											reportCheckTime='".$inputDate."', 
											reportCheckIP='".$userIP."',
											totalScore='".$totalScore."', 
											reportStatus='C', 
											reportCopy='".$reportCopy."', 
											reportTutorTempSave=null, 
											passOK='".$passOK."'
									WHERE userID='".$userID."' 
									AND contentsCode='".$contentsCode."' 
									AND lectureOpenSeq='".$lectureOpenSeq."'";
			$resultZ = mysql_query($queryZ);

			if($result){
				echo '{"result" : "success"}';
			} else {
				echo '{"result" : "error"}';
			}
			exit;

	} else if($method == "GET") { // 

		if($_GET['admin'] == "Y") {
			if($_SESSION['loginUserLevel'] <= 8) {
				$userID = $_GET['userID'];
			}
		} else {
			$userID = $_SESSION['loginUserID'];
		}
			$lectureOpenSeq = $_GET['lectureOpenSeq'];

			$query = "SELECT A.lectureStart, A.lectureEnd, A.progress, A.reportSaveTime, A.reportIP, A.reportCheckTime,
											 A.reportScore, A.reportStatus, A.reportCopy, A.companyCode, B.userName, C.companyName, D.contentsName, D.reportEA,
											 D.contentsCode, D.passProgress, D.passTest, D.passReport, D.totalPassReport
								FROM nynStudy AS A
								LEFT OUTER
								JOIN nynMember AS B ON A.userID=B.userID
								LEFT OUTER
								JOIN nynCompany AS C ON A.companyCode=C.companyCode
								LEFT OUTER
								JOIN nynContents AS D ON A.contentsCode=D.contentsCode
								WHERE A.lectureOpenSeq='".$lectureOpenSeq."'
								AND A.userID='".$userID."'";
			$result = mysql_query($query);
			$totalCount = mysql_num_rows($result);			
			$rs = mysql_fetch_array($result);
			$a=0;
			$adminapi = array(); //DB 값이 없는 경우 배열선언 부분

			$examAttachURL = "/attach/report/exam/";
			$exampleAttachURL = "/attach/report/example/";
			$answerAttachURL = "/attach/report/answer/";
			$rubricAttachURL = "/attach/report/rubric/";

			$adminapi[totalCount] = "$totalCount";
			$adminapi[nowTime] = $inputDate;
			$adminapi[userID] = $userID;
			$adminapi[userName] = $rs[userName];
			$adminapi[companyName] = $rs[companyName];
			$adminapi[lectureOpenSeq] = "$lectureOpenSeq";
			$adminapi[lectureStart] = $rs[lectureStart];
			$adminapi[lectureEnd] = $rs[lectureEnd];
			$adminapi[contentsName] = $rs[contentsName];
			$adminapi[contentsCode] = $rs[contentsCode];
			$adminapi[progress] = $rs[progress];
			$adminapi[reportStatus] = $rs[reportStatus];
			$adminapi[reportScore] = $rs[reportScore];
			$adminapi[reportIP] = $rs[reportIP];
			$adminapi[reportSaveTime] = $rs[reportSaveTime];
			$adminapi[reportCheckTime] = $rs[reportCheckTime];
			$adminapi[reportEA] = $rs[reportEA];
			$adminapi[totalPassReport] = $rs[totalPassReport];
			$adminapi[reportCopy] = $rs[reportCopy];
			$adminapi[examAttachURL] = $examAttachURL;
			$adminapi[exampleAttachURL] = $exampleAttachURL;
			$adminapi[answerAttachURL] = $answerAttachURL;
			$adminapi[rubricAttachURL] = $rubricAttachURL;

			$queryX = "SELECT * FROM nynStudyEnd WHERE gubun='studyEnd' AND companyCode='".$rs[companyCode]."' AND lectureStart='".$rs[lectureStart]."' AND lectureEnd='".$rs[lectureEnd]."'";
			$resultX = mysql_query($queryX);
			$totalCountX = mysql_num_rows($resultX);

			if($totalCountX > 0) {
				$adminapi[studyEnd] = "Y";
			} else {
				$adminapi[studyEnd] = "N";
			}

			$queryA = "SELECT A.*, B.examNum, B.sourceChapter, B.exam, B.examAttach, B.example, B.exampleAttach, B.exampleAttachLink, B.rubric, B.rubricAttach, B.rubricAttachLink, B.score AS baseScore
								 FROM nynReportAnswer AS A
								 LEFT OUTER
								 JOIN nynReport AS B ON A.reportSeq=B.seq
								 WHERE A.lectureOpenSeq='".$lectureOpenSeq."' AND A.userID='".$userID."'";
			$resultA = mysql_query($queryA);

			while($rsA = mysql_fetch_array($resultA)) {
				$adminapi[reportResult][$a][seq] = $rsA[seq];
				$adminapi[reportResult][$a][reportSeq] = $rsA[reportSeq];
				$adminapi[reportResult][$a][exam] = stripslashes($rsA[exam]);
				$adminapi[reportResult][$a][examAttach] = $rsA[examAttach];
				$adminapi[reportResult][$a][example] = stripslashes($rsA[example]);
				$adminapi[reportResult][$a][exampleAttach] = $rsA[exampleAttach];
				$adminapi[reportResult][$a][exampleAttachLink] = $rsA[exampleAttachLink];
				$adminapi[reportResult][$a][rubric] = stripslashes($rsA[rubric]);
				$adminapi[reportResult][$a][rubricAttach] = stripslashes($rsA[rubricAttach]);
				$adminapi[reportResult][$a][rubricAttachLink] = stripslashes($rsA[rubricAttachLink]);
				$adminapi[reportResult][$a][score] = $rsA[score];
				$adminapi[reportResult][$a][baseScore] = $rsA[baseScore];
				$adminapi[reportResult][$a][answerType] = $rsA[answerType];
				$adminapi[reportResult][$a][answerAttach] = $rsA[answerAttach];
				$adminapi[reportResult][$a][attachLink] = $rsA[attachLink];
				$adminapi[reportResult][$a][answerText] = stripslashes($rsA[answerText]);
				$adminapi[reportResult][$a][comment] = $rsA[comment];
				$adminapi[reportResult][$a][sourceChapter] = $rsA[sourceChapter];
				$a++;
			}

			$json_encoded = json_encode($adminapi);
			print_r($json_encoded);
	}
		
	@mysql_close();
?>