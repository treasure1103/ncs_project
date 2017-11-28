<?php
	header('Content-Type:application/json; charset=utf-8');
?>
<? include '../lib/header.php'; ?>
<?
	$contentsCode = $_POST['contentsCode']; // 과정코드
	$lectureStart = $_POST['lectureStart']; // 시작일
	$lectureEnd = $_POST['lectureEnd']; // 종료일
	$period = $_POST['period']; // 수강기간 (단위:월)
	$serviceType = "9";
	$loopNum = "6"; // 아이디 생성개수

	if(!$contentsCode){
		echo '{"result" : "필수값이 누락되었습니다."}';
		exit;
	}
	if(!$period){
		$period = "2";
	}
	if(!$lectureStart){
		$lectureStart = date('Y-m-d');
		$lectureEnd = date("Y-m-d", strtotime($lectureStart."+".$period." month"));
	}

	$queryC = "SELECT chapter, midRate, testRate, reportRate, sourceType FROM nynContents WHERE contentsCode='".$contentsCode."'";
	$resultC = mysql_query($queryC);
	$rsC = mysql_fetch_assoc($resultC);
	$chapter = $rsC[chapter];
	$midRate = $rsC[midRate];
	$testRate = $rsC[testRate];
	$reportRate = $rsC[reportRate];
	$sourceType = $rsC[sourceType];

	if($midRate > 0 ) {
		$midStatusQ = "midStatus='N', ";
	} else {
		$midStatusQ = "midStatus=null, ";
	}

	if($testRate > 0 ) {
		$testStatusQ = "testStatus='N', ";
	} else {
		$testStatusQ = "testStatus=null, ";
	}

	if($reportRate > 0 ) {
		$reportStatusQ = "reportStatus='N', ";
	} else {
		$reportStatusQ = "reportStatus=null, ";
	}

	$queryL = "SELECT seq 
						 FROM nynLectureOpen 
						 WHERE contentsCode='".$contentsCode."'
						 AND serviceType='".$serviceType."'
						 ORDER BY seq LIMIT 1";
	$resultL = mysql_query($queryL);
	$rsL = mysql_fetch_assoc($resultL);
	$countL = mysql_num_rows($resultL);

	if($countL == 0) { // 처음 개설한다면 추가.
		$sql="INSERT INTO nynLectureOpen 
					SET lectureStart='".$lectureStart."',
							lectureEnd='".$lectureEnd."',
							contentsCode='".$contentsCode."',
							serviceType='".$serviceType."'";
		$result = mysql_query($sql);
		$lectureOpenSeq = mysql_insert_id();

	} else { // 이미 개설되어 있으면 날짜 업데이트
		$lectureOpenSeq = $rsL[seq];
		$sqlA = " UPDATE nynLectureOpen
							SET lectureStart='".$lectureStart."',
									lectureEnd='".$lectureEnd."' 
							WHERE seq='".$lectureOpenSeq."' 
							AND contentsCode='".$contentsCode."' 
							AND serviceType='".$serviceType."'";
		$resultA = mysql_query($sqlA);
	}

	$k=1;
	for($i=0; $i<$loopNum; $i++) {

		// 아이디 등록
		$sampleID = $contentsCode.$k;
		$hash = password_hash($sampleID, PASSWORD_DEFAULT);
		$pwdQ = "pwd='".$hash."', ";
		$queryQ =		$pwdQ. " 
								userName='콘텐츠심사',
								birth='".date('ymd')."',
								sex='1',
								companyCode='0000000000',
								phone01='02',
								phone02='6494',
								phone03='2010',
								mobile01='010',
								mobile02='0000',
								mobile03='0000',
								email01='sample',
								email02='oneedu.co.kr',
								zipCode='00000',
								address01='',
								address02='',
								userLevel='12',
								agreement='Y'";

		//등록 시 아이디 중복 체크
		$queB="SELECT userID FROM nynMember WHERE userID='".$sampleID."'";
		$resultB = mysql_query($queB);
		$countB = mysql_num_rows($resultB);

		if($countB == 0) {
			$query = "INSERT INTO nynMember SET userID='".$sampleID."', inputDate='".$inputDate."', ".$queryQ;
			$result = mysql_query($query);

			if($sourceType == 'book') {
				$chapterSizeQ = "mobileLastPage='99', ";
			}

			for($m=1; $m<=$chapter; $m++) {
				//차수별 진도등록
				$queryG = " INSERT INTO nynProgress 
										SET lectureOpenSeq='".$lectureOpenSeq."', 
												userID='".$sampleID."',
												chapter='".$m."',
												contentsCode='".$contentsCode."',
												progress='100',
												startTime='".$inputDate."',
												endTime='".$inputDate."',
												studyIP='".$userIP."',
												progressID=CONCAT('".$lectureOpenSeq."','_','".$contentsCode."','_','".$_SESSION['loginUserID']."','_','".$chapter."'), 
												".$chapterSizeQ."
												totalTime='0'";
				$resultG = mysql_query($queryG);
			}

			//수강 등록
			$query = "INSERT INTO nynStudy SET
									lectureOpenSeq=".$lectureOpenSeq.", 
									contentsCode='".$contentsCode."',
									companyCode='0000000000',
									serviceType='".$serviceType."',
									tutor='tutor',
									userID='".$sampleID."',
									lectureStart='".$lectureStart."',
									lectureEnd='".$lectureEnd."',
									lectureReStudy='".$lectureEnd."', 
									accessIP='61.42.208.137',
									".$midStatusQ."
									".$testStatusQ."
									".$reportStatusQ."
									progress='100', 
									price='0',
									rPrice='0'";
			$result = mysql_query($query);

		} else {
			$queryD = "DELETE FROM nynTestAnswer WHERE userID='".$sampleID."'";
			$resultD = mysql_query($queryD);

			$queryD2 = "DELETE FROM nynReportAnswer WHERE userID='".$sampleID."'";
			$resultD2 = mysql_query($queryD2);

			$queryD3 = "DELETE FROM nynSurveyAnswer WHERE userID='".$sampleID."'";
			$resultD3 = mysql_query($queryD3);

			//수강 수정
			$query = "UPDATE nynStudy 
								SET lectureStart='".$lectureStart."',
										lectureEnd='".$lectureEnd."',
										lectureReStudy='".$lectureEnd."',
										testStartTime=null,
										testEndTime=null,
										midSaveTime=null,
										testSaveTime=null,
										testTempSaveTime=null,
										reportSaveTime=null,
										midIP=null,
										testIP=null,
										reportIP=null,
										".$midStatusQ."
										".$testStatusQ."
										".$reportStatusQ."
										survey='N'
								 WHERE lectureOpenSeq=".$lectureOpenSeq." 
								 AND userID='".$sampleID."' 
								 AND serviceType='".$serviceType."'"; 
			$result = mysql_query($query);
		}
			$k++;
	}

				if($result){
					echo '{"result" : "success"}';
				} else {
					echo '{"result" : "error"}';
				}
				exit;
?>