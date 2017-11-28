<?php
		header('Content-Type:application/json; charset=utf-8');
?>
<? include '../lib/header.php'; ?>
<?
	if($method == "POST") {  // 유저 평가 제출 저장
			$userID = $_SESSION['loginUserID'];
			$lectureOpenSeq = $_POST['lectureOpenSeq'];
			$contentsCode = $_POST['contentsCode'];
			$testType = $_POST['testType'];
			$examType = $_POST['examType'];
			$testEnd = $_POST['testEnd'];
			$endTime = $_POST['endTime'];
			$seq = $_POST['seq'];
			$loopNum = count($seq);

			//시험 제한 시간 체크, 종료일 체크 후 접근 막기
			$queryZ = " SELECT lectureEnd, midStatus, testStartTime, testEndTime, testStatus FROM nynStudy
									WHERE lectureOpenSeq='".$lectureOpenSeq."'
									AND contentsCode='".$contentsCode."'
									AND userID='".$userID."'";
			$resultZ = mysql_query($queryZ);
			$rsZ = mysql_fetch_array($resultZ);

			$lectureEnd = $rsZ[lectureEnd]." 23:59:59";

			if($inputDate > $lectureEnd) { // 기간이 지났는지 확인
				echo '{"result" : "date error"}';
				exit;
			}

			if($testType == "mid") { //중간평가 응시가 완료된 경우 접근 금지
				if($rsZ[midStatus] == "C") {
					echo '{"result" : "mid error"}';
					exit;
				}
			}

			$testEndTimePlus = date("Y-m-d H:i:s", strtotime("+1 minutes", strtotime($rsZ[testEndTime])));  // 오차 1분의 여유를 줌
			if($testType == "final") { 	//시험응시가 완료되었거나 응시시간이 지난경우 접근금지
				if($endTime == "Y"){
					$query = " UPDATE nynStudy SET testOverTime='Y' 
									WHERE lectureOpenSeq='".$lectureOpenSeq."' 
									AND contentsCode='".$contentsCode."' 
									AND userID='".$userID."'";
					$result = mysql_query($query);
				}
				if($rsZ[testStatus] == "C" || $rsZ[testStatus] == "Y" || $inputDate >= $testEndTimePlus) {
					echo '{"result" : "timeEnd"}';
					exit;
				}
			}

			for($i=0; $i<$loopNum; $i++) {
				$userAnswerName = "userAnswer".$seq[$i];
				$userAnswer = $_POST[$userAnswerName];

				if($userAnswer) {
					if($examType[$i] == "A" || $examType[$i] == "D") {
							$query = "UPDATE nynTestAnswer
												SET userAnswer='".$userAnswer."',
														inputDate='".$inputDate."',
														testType='".$testType."'
												WHERE seq=".$seq[$i];
							$result = mysql_query($query);

					} else {
						$query = "UPDATE nynTestAnswer
											SET userTextAnswer='".addslashes($userAnswer)."',
													inputDate='".$inputDate."',
													testType='".$testType."'
											WHERE seq=".$seq[$i];
						$result = mysql_query($query);
					}
				}
			}

			if($testEnd == "Y") { // 최종 제출일 경우

				if($endTime != "Y") { // 정상제출일때만 정답 빈값 확인, 제한시간끝으로 제출될때는 체크하지 않음
					$queryY = " SELECT orderBy FROM nynTestAnswer
											WHERE contentsCode='".$contentsCode."' AND userID='".$userID."' AND testType='".$testType."' AND lectureOpenSeq='".$lectureOpenSeq."'
											AND (((examType='A' OR examType='D') AND (userAnswer IS NULL OR userAnswer=''))
											OR ((examType='B' OR examType='C') AND (userTextAnswer IS NULL OR userTextAnswer='')))
											ORDER BY orderBy LIMIT 1";
					$resultY = mysql_query($queryY);
					$rsY = mysql_fetch_array($resultY);
					$examNum = $rsY[orderBy];

					if($examNum) {
						echo '{"result" : "문제 '.$examNum.'번 정답을 입력하세요."}';
						exit;
					}
				}

				if($testType == "mid") {
					$queryQ = "midStatus='Y', midSaveTime='".$inputDate."', midIP='".$userIP."'";
				} else if($testType == "final") {
					$queryQ = "testStatus='Y', testSaveTime='".$inputDate."', testIP='".$userIP."'";
				}
			} else { // 평가 응시 중인 경우
				if($testType == "mid") {
					$queryQ = "midStatus='V', midSaveTime='".$inputDate."', midIP='".$userIP."'";
				} else if($testType == "final") {
					$queryQ = "testStatus='V', testSaveTime='".$inputDate."', testIP='".$userIP."'";
				}
			}

					if($inputDate > $lectureEnd) { // 최종 데이터 저장 처리 중 응시시간이 지났다면 시간 조정함.
						$inputDate = $lectureEnd;
					}

					$query = " UPDATE nynStudy
											SET ".$queryQ."
											WHERE lectureOpenSeq='".$lectureOpenSeq."'
											AND contentsCode='".$contentsCode."'
											AND userID='".$userID."'";
					$result = mysql_query($query);

			if($result) {
				echo '{"result" : "success"}';
			} else {
				echo '{"result" : "error"}';
			}
			exit;

	} else if($method == "GET") { // 평가 배정 및 출력
			$userID = $_SESSION['loginUserID'];
			$lectureOpenSeq = $_GET['lectureOpenSeq'];
			$contentsCode = $_GET['contentsCode'];
			$testType =$_GET['testType'];

			if($userID == "" || $lectureOpenSeq == "" || $contentsCode == "" || $testType == "") {
				echo '{"result" : "필수값 누락"}';
				exit;
			}

			if($testType == "mid") {  // 최종평가 일때
				$queryZ1 = " SELECT progress FROM nynStudy
										WHERE lectureOpenSeq='".$lectureOpenSeq."'
										AND contentsCode='".$contentsCode."'
										AND userID='".$userID."'";
				$resultZ1 = mysql_query($queryZ1);
				$rsZ1 = mysql_fetch_array($resultZ1);

				if($rsZ1[progress] < 50) {  // 진도율 80% 이상인지 체크
					echo '{"result" : "접근 오류"}';
					exit;
				}
			}

			if($testType == "final") {  // 최종평가 일때
				$queryZ = " SELECT progress FROM nynStudy
										WHERE lectureOpenSeq='".$lectureOpenSeq."'
										AND contentsCode='".$contentsCode."'
										AND userID='".$userID."'";
				$resultZ = mysql_query($queryZ);
				$rsZ = mysql_fetch_array($resultZ);

				if($rsZ[progress] < 80) {  // 진도율 80% 이상인지 체크
					echo '{"result" : "접근 오류"}';
					exit;
				}
			}

			if($list == "") {
				$list = 99;
			}

			if($page == "") {
				$page = 1;
			}

			//문제배정여부 확인
			$queryA = " SELECT *
									FROM nynTestAnswer
									WHERE lectureOpenSeq='".$lectureOpenSeq."'
									AND contentsCode='".$contentsCode."'
									AND testType='".$testType."'
									AND userID='".$userID."'
									ORDER BY seq";
			$resultA = mysql_query($queryA);
			$countA = mysql_num_rows($resultA);

			if($testType == "mid") {
				$queryB = "SELECT mid01EA, mid02EA, mid03EA, mid04EA, contentsName FROM nynContents WHERE contentsCode='".$contentsCode."'";
				$resultB = mysql_query($queryB);
				$rsB = mysql_fetch_array($resultB);

				$test01EA = $rsB[mid01EA];
				$test02EA = $rsB[mid02EA];
				$test03EA = $rsB[mid03EA];
				$test04EA = $rsB[mid04EA];

			} else if($testType == "final") {
				$queryB = "SELECT test01EA, test02EA, test03EA, test04EA, contentsName, testTime FROM nynContents WHERE contentsCode='".$contentsCode."'";
				$resultB = mysql_query($queryB);
				$rsB = mysql_fetch_array($resultB);

				$test01EA = $rsB[test01EA];
				$test02EA = $rsB[test02EA];
				$test03EA = $rsB[test03EA];
				$test04EA = $rsB[test04EA];
			}

			if($countA == 0) { //배정이 되어 있지 않다면 배정 시작
				$b=1;

				if($test04EA > 0) {  // 진위형 배정
					$queryC = " SELECT *
											FROM nynTestMapping A
											LEFT OUTER
											JOIN nynTest B ON A.testSeq=B.seq
											WHERE A.testType='".$testType."' AND A.contentsCode='".$contentsCode."' AND B.examType='D'
											ORDER BY RAND()
											LIMIT ".$test04EA;
					$resultC = mysql_query($queryC);

					while($rsC = mysql_fetch_array($resultC)) {
						$queryD = " INSERT INTO nynTestAnswer
												SET lectureOpenSeq='".$lectureOpenSeq."',
														contentsCode='".$contentsCode."',
														testType='".$testType."',
														testSeq='".$rsC[testSeq]."',
														examType='D',
														orderBy='".$b."',
														userID='".$userID."'";
						$resultD = mysql_query($queryD);
						$b++;
					}
				}

				if($test01EA > 0) {  // 객관식 배정
					$queryC = " SELECT *
											FROM nynTestMapping A
											LEFT OUTER
											JOIN nynTest B ON A.testSeq=B.seq
											WHERE A.testType='".$testType."' AND A.contentsCode='".$contentsCode."' AND B.examType='A'
											ORDER BY RAND()
											LIMIT ".$test01EA;
					$resultC = mysql_query($queryC);

					while($rsC = mysql_fetch_array($resultC)) {
						$queryD = " INSERT INTO nynTestAnswer
												SET lectureOpenSeq='".$lectureOpenSeq."',
														contentsCode='".$contentsCode."',
														testType='".$testType."',
														testSeq='".$rsC[testSeq]."',
														examType='A',
														orderBy='".$b."',
														userID='".$userID."'";
						$resultD = mysql_query($queryD);
						$b++;
					}
				}

				if($test02EA > 0) { // 단답형 배정
					$queryC = " SELECT *
											FROM nynTestMapping A
											LEFT OUTER
											JOIN nynTest B ON A.testSeq=B.seq
											WHERE A.testType='".$testType."' AND A.contentsCode='".$contentsCode."' AND B.examType='B'
											ORDER BY RAND()
											LIMIT ".$test02EA;
					$resultC = mysql_query($queryC);

					while($rsC = mysql_fetch_array($resultC)) {
						$queryD = " INSERT INTO nynTestAnswer
												SET lectureOpenSeq='".$lectureOpenSeq."',
														contentsCode='".$contentsCode."',
														testType='".$testType."',
														testSeq='".$rsC[testSeq]."',
														examType='B',
														orderBy='".$b."',
														userID='".$userID."'";
						$resultD = mysql_query($queryD);
						$b++;
					}
				}

				if($test03EA > 0) { // 서술형 배정
					$queryC = " SELECT *
											FROM nynTestMapping A
											LEFT OUTER
											JOIN nynTest B ON A.testSeq=B.seq
											WHERE A.testType='".$testType."' AND A.contentsCode='".$contentsCode."' AND B.examType='C'
											ORDER BY RAND()
											LIMIT ".$test03EA;
					$resultC = mysql_query($queryC);

					while($rsC = mysql_fetch_array($resultC)) {
						$queryD = " INSERT INTO nynTestAnswer
												SET lectureOpenSeq='".$lectureOpenSeq."',
														contentsCode='".$contentsCode."',
														testType='".$testType."',
														testSeq='".$rsC[testSeq]."',
														examType='C',
														orderBy='".$b."',
														userID='".$userID."'";
						$resultD = mysql_query($queryD);
						$b++;
					}
				}

				if($testType == "final") { // 배정 후 시험 제한 시간 계산
					$testTime = $rsB[testTime];
					$testEndTime = date("Y/m/d H:i:s", strtotime('+'.$testTime.'minutes'));

					//최종평가 제한시간 기록
					$queryF = " UPDATE nynStudy
											SET testStartTime='".$inputDate."',
													testEndTime='".$testEndTime."',
													testSaveTime='".$inputDate."',
													testStatus='V',
													testIP='".$userIP."'
											WHERE lectureOpenSeq='".$lectureOpenSeq."'
											AND contentsCode='".$contentsCode."'
											AND userID='".$userID."'";
					$resultF = mysql_query($queryF);
				}
			}

			if($testType == "final") {
				$query = "SELECT testStartTime, testEndTime, testStatus FROM nynStudy
									WHERE lectureOpenSeq='".$lectureOpenSeq."'
									AND contentsCode='".$contentsCode."'
									AND userID='".$userID."'";
				$result = mysql_query($query);
				$rs = mysql_fetch_array($result);

				//시험응시가 완료되었거나 응시시간이 지난경우 접근금지
				if($rs[testStatus] == "C" || $rs[testStatus] == "Y" || $inputDate >= $rs[testEndTime]) {
					echo '{"result" : "응시 완료 또는 응시 시간 만료거나 문제 배정되어 있음"}';
					exit;
				}
			}

			$currentLimit = ($list * $page) - $list; //몇 번째의 글부터 가져오는지
			$sqlLimit = ' LIMIT '.$currentLimit.', '.$list; //limit sql 구문

			$queryE = " SELECT A.*, B.exam, B.score
									FROM nynTestAnswer AS A
									LEFT OUTER
									JOIN nynTest AS B ON A.testSeq=B.seq
									WHERE A.lectureOpenSeq='".$lectureOpenSeq."'
									AND A.contentsCode='".$contentsCode."'
									AND A.testType='".$testType."'
									AND A.userID='".$userID."'
									ORDER BY A.orderBy".$sqlLimit;
			$resultE = mysql_query($queryE);

			$a = 0;
			$adminapi = array(); //DB 값이 없는 경우 배열선언 부분
			$totalCount = $test01EA + $test02EA + $test03EA + $test04EA;
			$adminapi[userID] = "$userID";
			$adminapi[lectureOpenSeq] = "$lectureOpenSeq";
			$adminapi[contentsName] = $rsB[contentsName];
			$adminapi[contentsCode] = $contentsCode;
			$adminapi[testType] = $testType;

			if($testType == "final") {
				$adminapi[testStartTime] = $rs[testStartTime];
				$adminapi[testEndTime] = $rs[testEndTime];
				$adminapi[nowTime] = $inputDate;
			}

			$adminapi[totalCount] = "$totalCount";
			$adminapi[aTypeEA] = "$test01EA";
			$adminapi[bTypeEA] = "$test02EA";
			$adminapi[cTypeEA] = "$test03EA";
			$adminapi[dTypeEA] = "$test04EA";
			$adminapi[examList] = "$list";
			$adminapi[examPage] = "$page";

			while($rsE = mysql_fetch_array($resultE)) {
				$adminapi[studyTest][$a][seq] = $rsE[seq];
				$adminapi[studyTest][$a][orderBy] = $rsE[orderBy];
				$adminapi[studyTest][$a][testSeq] = $rsE[testSeq];
				$adminapi[studyTest][$a][examType] = $rsE[examType];
				$adminapi[studyTest][$a][exam] = $rsE[exam];

				if($rsE[examType] == "A" || $rsE[examType] == "D") {
					$adminapi[studyTest][$a][userAnswer] = $rsE[userAnswer];

					//객관식 보기항목 불러옴
					$qExamP = "SELECT exampleNum, example FROM nynTestExample WHERE testSeq=".$rsE[testSeq]." ORDER BY testSeq, exampleNum";
					$rExamP = mysql_query($qExamP);

						for($e=0; $e<=4; $e++) {
							$n=$e+1;
							if(mysql_result($rExamP,$e,'exampleNum') != "") {
									$adminapi[studyTest][$a][example0.$n] = stripslashes(mysql_result($rExamP,$e,'example'));
							}
						}

				} else {
					$adminapi[studyTest][$a][userAnswer] = $rsE[userTextAnswer];
				}

				$adminapi[studyTest][$a][score] = $rsE[score];
				$a++;
			}

			$json_encoded = json_encode($adminapi);
			print_r($json_encoded);
		}
?>