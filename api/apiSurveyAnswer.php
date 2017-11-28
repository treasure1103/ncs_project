<?php
		header('Content-Type:application/json; charset=utf-8');
?>
<? include '../lib/header.php'; ?>
<?
	if($method == "POST") {  // 유저 설문 제출 저장
			$userID = $_SESSION['loginUserID'];
			$lectureOpenSeq = $_POST['lectureOpenSeq'];
			$contentsCode = $_POST['contentsCode'];
			$surveyType = $_POST['surveyType'];
			$seq = $_POST['seq'];
			$loopNum = count($seq);

			//종료일 체크 후 접근 막기
			$queryZ = " SELECT lectureEnd FROM nynStudy 
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

			for($i=0; $i<$loopNum; $i++) {
				$userAnswerName = "userAnswer".$seq[$i];
				$userAnswer = $_POST[$userAnswerName];
				if(!$userAnswer) {
					$j = $i+1;
					echo '{"result" : "설문 '.$j.'번 응답 부탁드립니다."}';
					exit;
				}

				$qSelect = "SELECT * FROM nynSurveyAnswer WHERE surveySeq='".$seq[$i]."' AND lectureOpenSeq='".$lectureOpenSeq."' AND userID='".$_SESSION['loginUserID']."'";
				$qResult = mysql_query($qSelect);
				$count = mysql_num_rows($qResult);

				if($count == 0) {
					if($surveyType[$i] == "A") {
							$query = "INSERT INTO nynSurveyAnswer
												SET userAnswer='".$userAnswer."', 
														inputDate='".$inputDate."', 
														lectureOpenSeq='".$lectureOpenSeq."',
														contentsCode='".$contentsCode."',
														userID='".$_SESSION[loginUserID]."',
														surveyType='A',
														surveySeq='".$seq[$i]."'";
							$result = mysql_query($query);

					} else {
							$query = "INSERT INTO nynSurveyAnswer
												SET userTextAnswer='".addslashes($userAnswer)."', 
														inputDate='".$inputDate."', 
														lectureOpenSeq='".$lectureOpenSeq."',
														contentsCode='".$contentsCode."',
														userID='".$_SESSION[loginUserID]."',
														surveyType='B',
														surveySeq='".$seq[$i]."'";
							$result = mysql_query($query);
					}
				}
			}

			//최종평가 제한시간 기록
			$queryF = " UPDATE nynStudy SET survey='Y'
									WHERE lectureOpenSeq='".$lectureOpenSeq."' 
									AND contentsCode='".$contentsCode."' 
									AND userID='".$_SESSION[loginUserID]."'";
			$resultF = mysql_query($queryF);			

			if($resultF) {
				echo '{"result" : "success"}';
			} else {
				echo '{"result" : "error"}';
			}
				exit;

	}	if($method == "GET") {  // 유저 평가 제출 저장
			$contentsCode = $_GET['contentsCode'];
			$lectureOpenSeq = $_GET['lectureOpenSeq'];
			$userID = $_GET['userID'];
			$lectureDay = $_GET['lectureDay'];
			$lectureSE = EXPLODE('~',$lectureDay);

			if($page == "") {
				$page = 1;
			}
			if($list == "") {
				$list = 10;
			}
			if($userID != "") {
				$qUserID = " AND A.userID='".$userID."'";
			}
			if($lectureOpenSeq != "") {
				$qLectureOpenSeq = " AND A.lectureOpenSeq=".$lectureOpenSeq;
			}
			if($contentsCode != "") {
				$qContentsCode = " AND A.contentsCode='".$contentsCode."'";
			}
			if($lectureDay != "") {
				$qLectureDay = " AND A.lectureStart='".TRIM($lectureSE[0])."' AND A.lectureEnd='".TRIM($lectureSE[1])."'";
			}

			$queryQ = $qContentsCode.$qLectureOpenSeq.$qUserID.$qLectureDay;

			$adminapi = array(); //DB 값이 없는 경우 배열선언 부분

			$queryZ = " SELECT DISTINCT(A.userID), C.userName, A.contentsCode, A.lectureOpenSeq, A.lectureStart, A.lectureEnd, LEFT(A.inputDate,10) AS inputDate, B.contentsName
									FROM nynSurveyAnswer AS A
									LEFT OUTER
									JOIN nynContents AS B ON A.contentsCode=B.contentsCode
									LEFT OUTER
									JOIN nynMember AS C ON A.userID=C.userID
									WHERE A.seq <> 0 ".$queryQ;
			$resultZ = mysql_query($queryZ);
			$allPost = mysql_num_rows($resultZ);
			$currentLimit = ($list * $page) - $list; //몇 번째의 글부터 가져오는지
			$sqlLimit = ' LIMIT '.$currentLimit.', '.$list; //limit sql 구문

			$queryB = " SELECT DISTINCT(A.userID), C.userName, A.contentsCode, A.lectureOpenSeq, A.lectureStart, A.lectureEnd, LEFT(A.inputDate,10) AS inputDate, B.contentsName
									FROM nynSurveyAnswer AS A
									LEFT OUTER
									JOIN nynContents AS B ON A.contentsCode=B.contentsCode
									LEFT OUTER
									JOIN nynMember AS C ON A.userID=C.userID
									WHERE A.seq <> 0 ".$queryQ." ORDER BY A.seq DESC ".$sqlLimit;
			$resultB = mysql_query($queryB);
			$b=0;
			$adminapi[totalCount] = "$allPost"; //총 개시물 수

			while($rsB = mysql_fetch_array($resultB)) {

			$queryC = " SELECT lectureStart, lectureEnd FROM nynStudy WHERE lectureOpenSeq='".$rsB[lectureOpenSeq]."'";
			$resultC = mysql_query($queryC);
			$rsC = mysql_fetch_array($resultC);

				$adminapi[survey][$b][userID] = $rsB[userID];
				$adminapi[survey][$b][userName] = $rsB[userName];
				$adminapi[survey][$b][lectureOpenSeq] = $rsB[lectureOpenSeq];
				$adminapi[survey][$b][lectureStart] = $rsC[lectureStart];
				$adminapi[survey][$b][lectureEnd] = $rsC[lectureEnd];
				$adminapi[survey][$b][contentsCode] = $rsB[contentsCode];
				$adminapi[survey][$b][contentsName] = $rsB[contentsName];
				$adminapi[survey][$b][inputDate] = $rsB[inputDate];

					$query = "SELECT * FROM nynSurveyAnswer WHERE lectureOpenSeq='".$rsB[lectureOpenSeq]."' 
										AND contentsCode='".$rsB[contentsCode]."' 
										AND userID='".$rsB[userID]."' ORDER BY seq";
					$result = mysql_query($query);
					$count = mysql_num_rows($result);
					$a=0;

					while($rs = mysql_fetch_array($result)) {
						$adminapi[survey][$b][answer][$a][surveySeq] = $rs[surveySeq];
						if($rs[surveyType] == "A") {
							$userAnswer = $rs[userAnswer];
						} else {
							$userAnswer = $rs[userTextAnswer];
						}
						
						$queryC = "SELECT * FROM nynSurvey WHERE seq='".$rs[surveySeq]."'";
						$resultC = mysql_query($queryC);
						$c=0;

						while($rsC = mysql_fetch_array($resultC)) {
							$adminapi[survey][$b][answer][$a][exam][$c][surveyType] = $rs[surveyType];
							$adminapi[survey][$b][answer][$a][exam][$c][userAnswer] = $userAnswer;
							$adminapi[survey][$b][answer][$a][exam][$c][exam] = $rsC[exam];

							if($rsC[surveyType] == "A") {
								$queryA = "SELECT * FROM nynSurveyExample WHERE surveySeq=".$rs[surveySeq]." ORDER BY exampleNum";
								$resultA = mysql_query($queryA);
								$n=1;

									while($rsA = mysql_fetch_array($resultA)) {
										if($rsA[exampleNum] != "") {
											$adminapi[survey][$b][answer][$a][exam][$c][example0.$n] = stripslashes($rsA[example]);
											$n++;
										}
									}
							}
						$c++;
					}
					$a++;
				}
				$b++;
			}

			$json_encoded = json_encode($adminapi);
			print_r($json_encoded);
	}
?>