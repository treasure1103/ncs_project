<?php
		header('Content-Type:application/json; charset=utf-8');
?>
<? include '../lib/header.php'; ?>
<?
	if($method == "POST") {
		$userID = $_SESSION['loginUserID'];
		$lectureOpenSeq = $_POST['lectureOpenSeq'];
		$contentsCode = $_POST['contentsCode'];
		$reportEnd = $_POST['reportEnd'];
		$answerType = $_POST['answerType'];
		$seq = $_POST['seq'];
		$loopNum = count($seq);

		if(!$userID) {
			echo '{"result" : "로그아웃상태입니다. 다시 로그인 하시기 바랍니다."}';
			exit;
		}

		//종료일 체크 후 접근 막기
		$queryZ = " SELECT lectureEnd, reportStatus FROM nynStudy 
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

		if($rsZ[testStatus] == "C" || $rsZ[testStatus] == "Y") { // 완료 후 재제출 불가
			echo '{"result" : "access error"}';
			exit;
		}

		for($i=0; $i<$loopNum; $i++) {
			$answerTypeName = "answerType".$seq[$i];
			$answerType = $_POST[$answerTypeName];
			$answerTextName = "answerText".$seq[$i];
			$answerText = addslashes($_POST[$answerTextName]);

				if($answerType == "attach") { // 파일 제출 시
					$answerAttachName = "answerAttach".$seq[$i];
					$attachFile01Name = $_FILES[$answerAttachName]["name"];
					$answerTypeQ = "answerType='attach', ";
					$attachURL = "/attach/report/answer/";
					$uploadDir = $_SERVER['DOCUMENT_ROOT'].$attachURL;
					$uploadDate = date('i');

//echo $attachFile01Name;

					if($attachFile01Name != "") { //첨부파일01이 있을 경우 업로드
						$attachFile01Temp = $_FILES[$answerAttachName]['tmp_name']; // 업로드 파일 임시저장파일
						$attachFile01Path = $attachURL.$attachFile01Name;
						$attachFile01Save = $uploadDir.$attachFile01Name;

						$nameOK=1;
						$j=1;

						while($nameOK > 0){
							if(file_Exists($attachFile01Save)) { // 같은 파일명이 존재한다면
								$attachFile01Name = $uploadDate.$j."_".$_FILES[$answerAttachName]["name"];
								$attachFile01Path = $attachURL.$attachFile01Name;
								$attachFile01Save = $uploadDir.$attachFile01Name; // 파일명 앞에 시간을 붙임.
								$j++;
							} else {
								$nameOK = 0;
							}
						}
							@move_uploaded_file($attachFile01Temp, $attachFile01Save);
							$upAttachFile01 = "answerAttach='".$_FILES[$answerAttachName]["name"]."', attachLink='".$attachFile01Path."', ";
							$answerTextQ = "";

						} else {
							$upAttachFile01 = "";
							$answerTextQ = "";
						}

				} else { // 직접 작성 시
					$answerTextQ = "answerText='".$answerText."', ";
					$answerTypeQ = "answerType='text', ";
					$upAttachFile01 = "";
				}

					$query = "UPDATE nynReportAnswer
										SET ".$upAttachFile01.$answerTextQ.$answerTypeQ."
												inputDate='".$inputDate."'
										WHERE userID='".$userID."' AND seq='".$seq[$i]."'";
					$result = mysql_query($query);
		}

			if ($reportEnd == "Y") { //최종제출 처리
				$reportStatus = "Y";
			} else { // 임시저장시
				$reportStatus = "V";
			}

			$query = "UPDATE nynStudy
								SET reportStatus='".$reportStatus."',
										reportSaveTime='".$inputDate."',
										reportIP='".$userIP."'
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

	} else if($method == "GET") { // 과제 배정 및 출력
			$userID = $_SESSION['loginUserID'];
			$lectureOpenSeq = $_GET['lectureOpenSeq'];
			$contentsCode = $_GET['contentsCode'];

			if($userID == "" || $lectureOpenSeq == "" || $contentsCode == "") {
				echo '{"result" : "필수값 누락"}';
				exit;
			}

			//잘못된 접근인지 확인
			$query = "SELECT lectureEnd, progress, reportStatus FROM nynStudy 
								WHERE lectureOpenSeq='".$lectureOpenSeq."' 
								AND contentsCode='".$contentsCode."' 
								AND userID='".$userID."'";
			$result = mysql_query($query);
			$rs = mysql_fetch_array($result);

			$lectureEnd = $rs[lectureEnd]." 23:59:59";
			$reserveDate = date("Ymd", strtotime($lectureEnd."+1Day"))."060000";

			//응시가 완료되었거나 기간이 지난경우 접근 금지
			if($rs[reportStatus] == "C" || $inputDate > $lectureEnd) {
				echo '{"result" : "잘못된 접근"}';
				exit;
			}

			//과제배정여부 확인
			$queryA = " SELECT * 
									FROM nynReportAnswer 
									WHERE lectureOpenSeq='".$lectureOpenSeq."' 
									AND contentsCode='".$contentsCode."' 
									AND userID='".$userID."' 
									ORDER BY seq";
			$resultA = mysql_query($queryA);
			$countA = mysql_num_rows($resultA);

			$queryB = "SELECT reportEA, contentsName FROM nynContents WHERE contentsCode='".$contentsCode."'";
			$resultB = mysql_query($queryB);
			$rsB = mysql_fetch_array($resultB);
			$reportEA = $rsB[reportEA];

			if($countA == 0) { //배정이 되어 있지 않다면 배정 시작
					$queryC = " SELECT *
											FROM nynReportMapping A
											LEFT OUTER
											JOIN nynReport B ON A.reportSeq=B.seq
											WHERE A.contentsCode='".$contentsCode."' 
											ORDER BY RAND()
											LIMIT ".$reportEA;
					$resultC = mysql_query($queryC);

					while($rsC = mysql_fetch_array($resultC)) {
						$queryD = " INSERT INTO nynReportAnswer 
												SET lectureOpenSeq='".$lectureOpenSeq."', 
														contentsCode='".$contentsCode."', 
														reportSeq='".$rsC[reportSeq]."', 
														insertDate='".$inputDate."',
														userID='".$userID."'";
						$resultD = mysql_query($queryD);
					}
			}

			$queryE = " SELECT A.*, B.exam, B.examAttach, B.examAttachLink, B.score
									FROM nynReportAnswer AS A
									LEFT OUTER
									JOIN nynReport AS B ON A.reportSeq=B.seq
									WHERE A.lectureOpenSeq='".$lectureOpenSeq."' 
									AND A.contentsCode='".$contentsCode."' 
									AND A.userID='".$userID."'
									ORDER BY A.seq";
			$resultE = mysql_query($queryE);
			$attachExamURL = "/attach/report/exam/";
			$attachAnswerURL = "/attach/report/answer/";

			$a = 0;
			$adminapi = array(); //DB 값이 없는 경우 배열선언 부분
			$adminapi[userID] = $userID;
			$adminapi[lectureOpenSeq] = "$lectureOpenSeq";
			$adminapi[contentsName] = $rsB[contentsName];
			$adminapi[contentsCode] = $contentsCode;
			$adminapi[totalCount] = "$reportEA";
			$adminapi[attachExamURL] = $attachExamURL;
			$adminapi[attachAnswerURL] = $attachAnswerURL;

			while($rsE = mysql_fetch_array($resultE)) {
				$adminapi[studyReport][$a][seq] = $rsE[seq];
				$adminapi[studyReport][$a][reportSeq] = $rsE[reportSeq];
				$adminapi[studyReport][$a][exam] = $rsE[exam];
				$adminapi[studyReport][$a][examAttach] = $rsE[examAttach];
				$adminapi[studyReport][$a][examAttachLink] = $rsE[examAttachLink];
				$adminapi[studyReport][$a][answerType] = $rsE[answerType];
				$adminapi[studyReport][$a][answerAttach] = $rsE[answerAttach];
				$adminapi[studyReport][$a][attachLink] = $rsE[attachLink];
				$adminapi[studyReport][$a][answerText] = $rsE[answerText];
				$adminapi[studyReport][$a][score] = $rsE[score];
				$adminapi[studyReport][$a][reserveDate] = $reserveDate;
				$a++;
			}
				
			$json_encoded = json_encode($adminapi);
			print_r($json_encoded);
		}
		
	@mysql_close();
?>