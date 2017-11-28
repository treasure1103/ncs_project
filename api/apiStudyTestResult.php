<?php
		header('Content-Type:application/json; charset=utf-8');
?>
<? include '../lib/header.php'; ?>
<?
	if($method == "POST") {

		//레벨 7(교.강사) 아니면 접근불가
			if($_SESSION['loginUserLevel'] > 8) {
				echo '{"result" : "level error"}';
				exit;
			}
			$seq = $_POST['seq'];
			$testSeq = $_POST['testSeq'];
			$userID = $_POST['userID'];
			$testType = $_POST['testType'];
			$lectureOpenSeq = $_POST['lectureOpenSeq'];
			$contentsCode = $_POST['contentsCode'];
			$rightAnswer = $_POST['rightAnswer'];
			$correct = $_POST['correct'];
			$cTypeScore = $_POST['cTypeScore'];
			$testCopy = $_POST['testCopy'];
			$temp = $_POST['temp'];
			$reScore = $_POST['reScore']; //재채점처리여부

				if($reScore == "Y"){  //재채점 승인
					if($testType == "final") {
						$testStatusQ = "testStatus ='Y' ";
					} else if($testType == "report") {
						$testStatusQ = "reportStatus ='Y' ";
					} else {
						$testStatusQ = "midStatus ='Y' ";
					}
					$queryR = " UPDATE nynStudy SET ".$testStatusQ." 
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

			if($testCopy == ""){
				$testCopy = "N";
				$testCopyQ = ", testCopy='N'";
			} else {
				$testCopyQ = ", testCopy='".$testCopy."'";
			}

			if($testCopy == "Y"){ // 모사답안 처리
				$queryB = " UPDATE nynReportAnswer
										SET comment='".$comment."'
										WHERE seq=".$seq;
				$resultB = mysql_query($queryB);

				$queryY = " UPDATE nynStudy 
										SET testCopy='Y', 
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

			//키값으로 새로운 배열 생성
			$seqKey = ARRAY_KEYS($_POST['seq']);
			$loopNum = COUNT($seqKey);
			$j = 0;

			// 채점 및 첨삭
			for($i=0; $i<$loopNum; $i++) {
				$seqValue = $seqKey[$i];
				$query = "SELECT * FROM nynTest WHERE seq='".$testSeq[$seqValue]."'";
				$result = mysql_query($query);
				$rs = mysql_fetch_array($result);

				if($rs[examType] == "C") { // 서술형인 경우 점수, 첨삭내용 반영

					if($cTypeScore[$j] != ""){
						$scoreQ = ", score='".$cTypeScore[$j]."'";
					}
					$queryY = " UPDATE nynTestAnswer
											SET correct='".addslashes($correct[$j])."'".$scoreQ."
											WHERE seq=".$seq[$seqValue];
					$resultY = mysql_query($queryY);
					$j++;

				} else { // 객관식, 단답형인 경우 점수만 반영

					if($rightAnswer[$seqValue]) {
						if($rightAnswer[$seqValue] == "Y") {
							$score = $rs[score];
						} else if($rightAnswer[$seqValue] == "N") {
							$score = "0";
						}
							$queryB = "UPDATE nynTestAnswer
												SET score='".$score."'
												WHERE seq=".$seq[$seqValue];
							$resultB = mysql_query($queryB);
					}
				}
			}

			if($temp == "Y") {  // 임시저장 시
				if($testType == "mid"){  // 중간평가
					$queryY = " UPDATE nynStudy
											SET midTutorTempSave='Y'
											WHERE userID='".$userID."' 
											AND contentsCode='".$contentsCode."' 
											AND lectureOpenSeq='".$lectureOpenSeq."'";
					$resultY = mysql_query($queryY);

				} else {  // 최종평가
					$queryY = " UPDATE nynStudy
											SET testTutorTempSave='Y'
											WHERE userID='".$userID."' 
											AND contentsCode='".$contentsCode."' 
											AND lectureOpenSeq='".$lectureOpenSeq."'";
					$resultY = mysql_query($queryY);
				}

				if($resultY){
					echo '{"result" : "success"}';
				} else {
					echo '{"result" : "error"}';
				}
				exit;
			}

			//평가시험점수 계산
			$queryC = " SELECT SUM(score) AS testScore 
									FROM nynTestAnswer 
									WHERE lectureOpenSeq='".$lectureOpenSeq."' 
									AND contentsCode='".$contentsCode."' 
									AND testType='".$testType."' 
									AND userID='".$userID."'";
			$resultC = mysql_query($queryC);
			$rsC = mysql_fetch_array($resultC);

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

			//점수 확인
			$queryD = " SELECT progress, midScore, midStatus, testScore, testStatus, reportStatus, reportScore
									FROM nynStudy 
									WHERE lectureOpenSeq='".$lectureOpenSeq."' 
									AND contentsCode='".$contentsCode."' 
									AND userID='".$userID."'";
			$resultD = mysql_query($queryD);
			$rsD = mysql_fetch_array($resultD);

			if($testType == "mid") { //중간평가 환산 점수
				//$conversionMid = floor((($rsC[testScore]/$rsE[totalPassMid])*$rsE[midRate])*10)/10;
				$conversionMid = ROUND(($rsC[testScore]*$rsE[midRate]/100),1);

				if($rsD[testStatus] == "C") { // 최종평가 채점완료면 최종평가 점수 환산 소수1자리까지 구함 2자리 이하 버림
					//$conversionTest = floor((($rsD[testScore]/$rsE[totalPassTest])*$rsE[testRate])*10)/10;
					$conversionTest = ROUND(($rsD[testScore]*$rsE[testRate]/100),1);
				} else { // 미채점이면 0으로 계산
					$conversionTest = 0;
				}

			} else if($testType == "final") { //최종평가 환산 점수
				//$conversionTest = floor((($rsC[testScore]/$rsE[totalPassTest])*$rsE[testRate])*10)/10;
				$conversionTest = ROUND(($rsC[testScore]*$rsE[testRate]/100),1);
				
				if($rsD[midStatus] == "C") { // 중간평가 채점완료면 중간평가 점수 환산 소수1자리까지 구함 2자리 이하 버림
					//$conversionMid = floor((($rsD[midScore]/$rsE[totalPassMid])*$rsE[midRate])*10)/10;
					$conversionMid = ROUND(($rsD[midScore]*$rsE[midRate]/100),1);
				} else { // 미채점이면 0으로 계산
					$conversionMid = 0;
				}
			}

			if($rsE[reportEA] > 0) { // 과제가 있는 과정인지 검사

				if($rsD[reportStatus] == "C") { // 채점완료면 과제점수 환산
					//$conversionReport = floor((($rsD[reportScore]/$rsE[totalPassReport])*$rsE[reportRate])*10)/10;
					$conversionReport = ROUND(($rsD[reportScore]*$rsE[reportRate]/100),1);
				} else { // 미채점이면 0으로 계산
					$conversionReport = 0;
				}					

				//중간+최종+과제 점수 합산

				$totalScore = $conversionMid+$conversionTest+$conversionReport;

			} else { // 과제가 없으면 중간+최종점수 합산

				$totalScore = $conversionMid+$conversionTest;
			}

			//수료 여부 확인
			if($rsD[progress] >= $rsE[passProgress]) { // 1. 수료기준 : 진도율 확인

				if($rsE[passScore] <= $totalScore) { // 2. 수료기준 : 총점 확인

					if($rsE[passTest] <= $rsC[testScore]) { // 3. 수료기준 : 평가시험 점수 확인

						if($rsE[reportEA] > 0) { // 과제가 있는 과정이라면
							
							if($rsE[passReport] <= $rsD[reportScore]) { // 4. 수료기준 : 과제 점수 확인
								$passOK = "Y";

							} else {
								$passOK = "N";
							}

						} else { // 과제가 없는 과정이라면 수료
							$passOK = "Y";
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

			if($testType == "mid") {
				$queryZ = " UPDATE nynStudy 
										SET midScore='".$rsC[testScore]."',
												midCheckTime='".$inputDate."', 
												midCheckIP='".$userIP."',
												totalScore='".$totalScore."', 
												midStatus='C', 
												midTutorTempSave=null, 
												passOK='".$passOK."'
										WHERE userID='".$userID."' 
										AND contentsCode='".$contentsCode."' 
										AND lectureOpenSeq='".$lectureOpenSeq."'";
			} else {
				$queryZ = " UPDATE nynStudy 
										SET testScore='".$rsC[testScore]."',
												testCheckTime='".$inputDate."', 
												testCheckIP='".$userIP."',
												totalScore='".$totalScore."', 
												testStatus='C', 
												testTutorTempSave=null, 
												testCopy='".$testCopy."', 
												passOK='".$passOK."'".$tempQ."
										WHERE userID='".$userID."' 
										AND contentsCode='".$contentsCode."' 
										AND lectureOpenSeq='".$lectureOpenSeq."'";
			}
				$resultZ = mysql_query($queryZ);

			if($resultZ){
				echo '{"result" : "success"}';
			} else {
				echo '{"result" : "error"}';
			}
			exit;

	} else if($method == "GET") {

		if($_GET['admin'] == "Y") {
			if($_SESSION['loginUserLevel'] <= 8) {
				$userID = $_GET['userID'];
			}
		} else {
			$userID = $_SESSION['loginUserID'];
		}
			$testType = $_GET['testType'];
			$lectureOpenSeq = $_GET['lectureOpenSeq'];

			$query = "SELECT A.lectureStart, A.lectureEnd, A.progress, A.midSaveTime, A.midIP, A.midCheckTime, A.midScore, A.midStatus,
											 A.testSaveTime, A.testIP, A.testCheckTime, A.testScore, A.testStatus, A.testCopy, A.testStartTime, A.testEndTime,
											 A.companyCode, B.userName, C.companyName, D.contentsCode, D.contentsName, D.mid01EA, D.mid02EA, D.mid03EA, D.mid04EA,
											 D.mid01Score, D.mid02Score, D.mid03Score, D.mid04Score, D.test01EA, D.test02EA, D.test03EA, D.test04EA,
											 D.test01Score, D.test02Score, D.test03Score, D.test04Score, D.passProgress, D.passTest
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
			$rs = mysql_fetch_array($result);
			$adminapi = array(); //DB 값이 없는 경우 배열선언 부분

			if($testType == "mid") {
				$status = $rs[midStatus];
				$submitIP = $rs[midIP];
				$saveTime = $rs[midSaveTime];
				$checkTime = $rs[midCheckTime];
				$userScore = $rs[midScore];
				$aTypeEA = $rs[mid01EA];
				$bTypeEA = $rs[mid02EA];
				$cTypeEA = $rs[mid03EA];
				$dTypeEA = $rs[mid04EA];
				$aTypeScore = $rs[mid01Score];
				$bTypeScore = $rs[mid02Score];
				$cTypeScore = $rs[mid03Score];
				$dTypeScore = $rs[mid04Score];
				$testStartTime = "";
				$testEndTime = "";

			} else {
				$status = $rs[testStatus];
				$submitIP = $rs[testIP];
				$saveTime = $rs[testSaveTime];
				$checkTime = $rs[testCheckTime];
				$userScore = $rs[testScore];
				$aTypeEA = $rs[test01EA];
				$bTypeEA = $rs[test02EA];
				$cTypeEA = $rs[test03EA];
				$dTypeEA = $rs[test04EA];
				$aTypeScore = $rs[test01Score];
				$bTypeScore = $rs[test02Score];
				$cTypeScore = $rs[test03Score];
				$dTypeScore = $rs[test04Score];
				$testStartTime = $rs[testStartTime];
				$testEndTime = $rs[testEndTime];
			}

			$totalCount = $aTypeEA + $bTypeEA + $cTypeEA + $dTypeEA;
			$adminapi[nowTime] = $inputDate;
			$adminapi[userID] = $userID;
			$adminapi[userName] = $rs[userName];
			$adminapi[companyName] = $rs[companyName];
			$adminapi[testType] = $testType;
			$adminapi[lectureOpenSeq] = "$lectureOpenSeq";
			$adminapi[lectureStart] = $rs[lectureStart];
			$adminapi[lectureEnd] = $rs[lectureEnd];
			$adminapi[contentsName] = $rs[contentsName];
			$adminapi[contentsCode] = $rs[contentsCode];
			$adminapi[progress] = $rs[progress];
			$adminapi[status] = "$status";
			$adminapi[testStartTime] = "$testStartTime";
			$adminapi[testEndTime] = "$testEndTime";
			$adminapi[submitIP] = "$submitIP";
			$adminapi[saveTime] = "$saveTime";
			$adminapi[checkTime] = "$checkTime";
			$adminapi[totalCount] = "$totalCount";
			$adminapi[aTypeEA] = "$aTypeEA";
			$adminapi[aTypeScore] ="$aTypeScore";
			$adminapi[bTypeEA] = "$bTypeEA";
			$adminapi[bTypeScore] = "$bTypeScore";
			$adminapi[cTypeEA] = "$cTypeEA";
			$adminapi[cTypeScore] = "$cTypeScore";
			$adminapi[dTypeEA] = "$dTypeEA";
			$adminapi[dTypeScore] = "$dTypeScore";
			$adminapi[userScore] = "$userScore"; // 획득점수
			$adminapi[testCopy] = $rs[testCopy];

			$queryX = "SELECT * FROM nynStudyEnd WHERE gubun='studyEnd' AND companyCode='".$rs[companyCode]."' AND lectureStart='".$rs[lectureStart]."' AND lectureEnd='".$rs[lectureEnd]."'";
			$resultX = mysql_query($queryX);
			$totalCountX = mysql_num_rows($resultX);

			if($totalCountX > 0) {
				$adminapi[studyEnd] = "Y";
			} else {
				$adminapi[studyEnd] = "N";
			}

			$dTypeTotalScore = 0;
			if($dTypeEA > 0) {  // 진위형
				$queryD = " SELECT A.*, B.exam, B.answer, B.commentary, B.sourceChapter, B.score AS baseScore
										FROM nynTestAnswer AS A
										LEFT OUTER
										JOIN nynTest AS B ON A.testSeq=B.seq
										WHERE A.lectureOpenSeq='".$lectureOpenSeq."' 
										AND A.testType='".$testType."' 
										AND A.userID='".$userID."' 
										AND A.examType='D' 
										ORDER BY A.seq";
				$resultD = mysql_query($queryD);
				$d = 0;

					while($rsD = mysql_fetch_array($resultD)) {
						$adminapi[dType][$d][seq] = $rsD[seq];
						$adminapi[dType][$d][testSeq] = $rsD[testSeq];
						$adminapi[dType][$d][exam] = $rsD[exam];

						//진위형 보기항목 불러옴
						$qExamP = "SELECT exampleNum, example FROM nynTestExample WHERE testSeq=".$rsD[testSeq]." ORDER BY testSeq, exampleNum";
						$rExamP = mysql_query($qExamP);

							for($e=0; $e<=4; $e++) {
								$n=$e+1;
								if(mysql_result($rExamP,$e,'exampleNum') != "") {
										$adminapi[dType][$d][example0.$n] = stripslashes(mysql_result($rExamP,$e,'example'));
								}
							}

						$adminapi[dType][$d][userAnswer] = $rsD[userAnswer];
						$adminapi[dType][$d][answer] = $rsD[answer];
						$adminapi[dType][$d][score] = $rsD[score];
						$adminapi[dType][$d][baseScore] = $rsD[baseScore];
						$adminapi[dType][$d][commentary] = $rsD[commentary];
						$adminapi[dType][$d][sourceChapter] = $rsD[sourceChapter];
						$dTypeTotalScore = $dTypeTotalScore + $rsD[score];
						$d++;
					}
			}

			$aTypeTotalScore = 0;
			if($aTypeEA > 0) {  // 객관식
				$queryA = " SELECT A.*, B.exam, B.answer, B.commentary, B.sourceChapter, B.score AS baseScore
										FROM nynTestAnswer AS A
										LEFT OUTER
										JOIN nynTest AS B ON A.testSeq=B.seq
										WHERE A.lectureOpenSeq='".$lectureOpenSeq."' 
										AND A.testType='".$testType."' 
										AND A.userID='".$userID."' 
										AND A.examType='A' 
										ORDER BY A.seq";
				$resultA = mysql_query($queryA);
				$a = 0;

					while($rsA = mysql_fetch_array($resultA)) {
						$adminapi[aType][$a][seq] = $rsA[seq];
						$adminapi[aType][$a][testSeq] = $rsA[testSeq];
						$adminapi[aType][$a][exam] = $rsA[exam];

						//객관식 보기항목 불러옴
						$qExamP = "SELECT exampleNum, example FROM nynTestExample WHERE testSeq=".$rsA[testSeq]." ORDER BY testSeq, exampleNum";
						$rExamP = mysql_query($qExamP);

							for($e=0; $e<=4; $e++) {
								$n=$e+1;
								if(mysql_result($rExamP,$e,'exampleNum') != "") {
										$adminapi[aType][$a][example0.$n] = stripslashes(mysql_result($rExamP,$e,'example'));
								}
							}

						$adminapi[aType][$a][userAnswer] = $rsA[userAnswer];
						$adminapi[aType][$a][answer] = $rsA[answer];
						$adminapi[aType][$a][score] = $rsA[score];
						$adminapi[aType][$a][baseScore] = $rsA[baseScore];
						$adminapi[aType][$a][commentary] = $rsA[commentary];
						$adminapi[aType][$a][sourceChapter] = $rsA[sourceChapter];
						$aTypeTotalScore = $aTypeTotalScore + $rsA[score];
						$a++;
					}
			}

			$bTypeTotalScore = 0;
			if($bTypeEA > 0) {  // 단답형
				$queryB = " SELECT A.*, B.exam, B.answerText, B.commentary, B.sourceChapter, B.score AS baseScore
										FROM nynTestAnswer AS A
										LEFT OUTER
										JOIN nynTest AS B ON A.testSeq=B.seq
										WHERE A.lectureOpenSeq='".$lectureOpenSeq."' 
										AND A.testType='".$testType."' 
										AND A.userID='".$userID."' 
										AND A.examType='B'
										ORDER BY A.seq";
				$resultB = mysql_query($queryB);
				$b = 0;

					while($rsB = mysql_fetch_array($resultB)) {
						$adminapi[bType][$b][seq] = $rsB[seq];
						$adminapi[bType][$b][testSeq] = $rsB[testSeq];
						$adminapi[bType][$b][exam] = $rsB[exam];
						$adminapi[bType][$b][userAnswer] = $rsB[userTextAnswer];
						$adminapi[bType][$b][answer] = $rsB[answerText];
						$adminapi[bType][$b][score] = $rsB[score];
						$adminapi[bType][$b][baseScore] = $rsB[baseScore];
						$adminapi[bType][$b][commentary] = $rsB[commentary];
						$adminapi[bType][$b][sourceChapter] = $rsB[sourceChapter];
						$bTypeTotalScore = $bTypeTotalScore + $rsB[score];
						$b++;
					}
			}

			$cTypeTotalScore = 0;
			if($cTypeEA > 0) {  // 서술형
				$queryC = " SELECT A.*, B.exam, B.answerText, B.commentary, B.sourceChapter, B.score AS baseScore
										FROM nynTestAnswer AS A
										LEFT OUTER
										JOIN nynTest AS B ON A.testSeq=B.seq
										WHERE A.lectureOpenSeq='".$lectureOpenSeq."' 
										AND A.testType='".$testType."' 
										AND A.userID='".$userID."' 
										AND A.examType='C'
										ORDER BY A.seq";
				$resultC = mysql_query($queryC);
				$c = 0;

					while($rsC = mysql_fetch_array($resultC)) {
						$adminapi[cType][$c][seq] = $rsC[seq];
						$adminapi[cType][$c][testSeq] = $rsC[testSeq];
						$adminapi[cType][$c][exam] = $rsC[exam];
						$adminapi[cType][$c][userAnswer] = $rsC[userTextAnswer];
						$adminapi[cType][$c][answer] = $rsC[answerText];
						$adminapi[cType][$c][score] = $rsC[score];
						$adminapi[cType][$c][baseScore] = $rsC[baseScore];
						$adminapi[cType][$c][correct] = $rsC[correct];
						$adminapi[cType][$c][commentary] = $rsC[commentary];
						$adminapi[cType][$c][sourceChapter] = $rsC[sourceChapter];
						$cTypeTotalScore = $cTypeTotalScore + $rsC[score];
						$c++;
					}
			}

			$adminapi[aTypeTotalScore] = "$aTypeTotalScore";
			$adminapi[bTypeTotalScore] = "$bTypeTotalScore";
			$adminapi[cTypeTotalScore] = "$cTypeTotalScore";
			$adminapi[dTypeTotalScore] = "$dTypeTotalScore";

			$json_encoded = json_encode($adminapi);
			print_r($json_encoded);
		}
?>