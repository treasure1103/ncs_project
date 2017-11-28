<?php
		header('Content-Type:application/json; charset=utf-8');
?>
<? include '../lib/header.php'; ?>
<?
	if($method == "POST") {
		$lectureStart = $_POST['lectureStart'];
		$certPass = $_POST['certPass'];
		$adminID = $_POST['adminID'];
		if($adminID) {
			$userID = $_POST['userID'];

			$queryC = "SELECT * FROM nynCert WHERE userID='".$userID."' AND lectureStart='".$lectureStart."'";
			$resultC = mysql_query($queryC);
			$countC = mysql_num_rows($resultC);

			if($countC == 0) {
				$query = "INSERT nynCert 
									SET userID='".$userID."', 
											lectureStart='".$lectureStart."', 
											certDate='".$inputDate."',
											adminID='".$adminID."',
											certPass='".$certPass."'";
				$result = mysql_query($query);
			}
	
		} else {

			$queryC = "SELECT * FROM nynCert WHERE userID='".$_SESSION[loginUserID]."' AND lectureStart='".$lectureStart."'";
			$resultC = mysql_query($queryC);
			$countC = mysql_num_rows($resultC);

			if($countC == 0) {
				$query = "INSERT nynCert 
									SET userID='".$_SESSION[loginUserID]."', 
											lectureStart='".$lectureStart."', 
											certDate='".$inputDate."',
											certPass='".$certPass."'";
				$result = mysql_query($query);
			}
		}

		if($result) {
			echo '{"result" : "success"}';
		} else {
			echo '{"result" : "error"}';
		}
		exit;

	} else if($method == "GET") { // 회원사 정보 불러옴
		$lectureOpenSeq = $_GET['lectureOpenSeq'];
		$contentsCode = $_GET['contentsCode'];
		$chapter = $_GET['chapter'];

		if($lectureOpenSeq == "" || $_SESSION['loginUserID'] == "") {
			echo '{"totalCount" : "0"}';
			exit;
		}
		if($chapter != "") {
			$qChapter = " AND A.chapter='".$chapter."'";
		}

		$query = "SELECT A.seq, A.lectureStart, A.lectureEnd, A.lectureReStudy, A.progress, A.userID, A.survey, A.testSaveTime, A.reportCopy, A.resultView, A.passOK,
							A.midStatus, A.midScore, A.midSaveTime, A.midIP, A.testStatus, A.testScore, A.testStartTime, A.testEndTime, A.testIP, A.midCaptchaTime, A.testCaptchaTime, A.reportCaptchaTime,
							A.reportStatus, A.reportScore, A.reportSaveTime, A.reportIP, '' AS companyName, D.previewImage, D.contentsCode, D.contentsName, D.limited, D.professor, D.sourceType, D.progressCheck,
							D.passProgress, D.passTest, D.passReport, D.totalPassMid, D.totalPassTest, D.totalPassReport, D.passScore, D.midRate, D.testRate, D.reportRate, D.testTime, D.attachFile,
							D.midTestChapter, D.midTestProgress, A.serviceType, IF(ISNULL(E.certPass),'N',E.certPass) AS certPass
							FROM nynStudy AS A 
							LEFT OUTER
							JOIN nynContents AS D ON A.contentsCode=D.contentsCode
							LEFT OUTER
							JOIN nynCert AS E ON A.userID=E.userID AND A.lectureStart=E.lectureStart
							WHERE A.lectureOpenSeq='".$lectureOpenSeq."' AND A.contentsCode='".$contentsCode."' AND A.userID='".$_SESSION['loginUserID']."'";
		$result = mysql_query($query);
		$rs = mysql_fetch_array($result);
		
		if($rs[midStatus] == "C") { // 채점완료면 중간평가 점수 환산 소수1자리까지 구함 2자리 이하 버림
			$conversionMid = floor((($rs[midScore]/$rs[totalPassMid])*$rs[midRate])*10)/10;
		} else { // 미채점이면 0으로 계산
			$conversionMid = 0;
		}

		if($rs[testStatus] == "C") { // 채점완료면 최종평가 점수 환산 소수1자리까지 구함 2자리 이하 버림
			$conversionTest = floor((($rs[testScore]/$rs[totalPassTest])*$rs[testRate])*10)/10;
		} else { // 미채점이면 0으로 계산
			$conversionTest = 0;
		}

		if($rs[reportStatus] == "C") { // 채점완료면 중간평가 점수 환산 소수1자리까지 구함 2자리 이하 버림
			$conversionReport = floor((($rs[reportScore]/$rs[totalPassReport])*$rs[reportRate])*10)/10;
		} else { // 미채점이면 0으로 계산
			$conversionReport = 0;
		}
		


		$adminapi = array(); //DB 값이 없는 경우 배열선언 부분
		$adminapi[nowTime] = $inputDate;
		$adminapi[seq] = $rs[seq];
		$adminapi[certPass] = $rs[certPass];
		$adminapi[userID] = $_SESSION[loginUserID];
		$adminapi[userName] = $_SESSION[loginUserName];
		$adminapi[companyName] = $rs[companyName];
		$adminapi[lectureOpenSeq] = "$lectureOpenSeq";
		$adminapi[lectureStart] = $rs[lectureStart];
		$adminapi[lectureEnd] = $rs[lectureEnd];
		$adminapi[lectureReStudy] = $rs[lectureReStudy];
		$adminapi[passOK] = $rs[passOK];
		
		// 17.11.24 최원오 --------------------------- 본인인증 로직 테스트 --------------------------------------------
			
		$queryC = "SELECT certPass from nynCert WHERE userID='".$_SESSION['loginUserID']."' limit 0,1";
		$resultC = mysql_query($queryC);
		$rsC = mysql_fetch_array($resultC);
		if($rsC['certPass'] == 'Y') {
			$adminapi[certPass] = 'Y';
		} else {
			$adminapi[certPass] = $rs[certPass];
		}
		
		// 17.11.24 최원오 ---------------------------------------------------------------------------------------

		$laterDate = (intval((strtotime($inputDate)-strtotime($rs[lectureStart])) / 86400))+1;
		if($laterDate >= 20){
			$suggestProgress = 100;
		} else {
			$suggestProgress = $laterDate * 5;
		}


		$queryA = "SELECT (SUM(progress)/COUNT(*)) AS aveProgress FROM nynStudy WHERE lectureOpenSeq='".$lectureOpenSeq."'";
		$resultA = mysql_query($queryA);
		$rsA = mysql_fetch_array($resultA);
		$aveProgress = floor($rsA[aveProgress]);

		$adminapi[suggestProgress] = "$suggestProgress";  // 권장 진도율
		$adminapi[totalProgress] = $rs[progress];  // 현재 진도율
		$adminapi[aveProgress] = "$aveProgress";  // 이 과정(+개설차수)의 평균 진도율

		$adminapi[previewImageURL] = "/attach/contents/";
		$adminapi[previewImage] = $rs[previewImage];
		$adminapi[attachFile] = $rs[attachFile];
		$adminapi[limited] = $rs[limited];
		$adminapi[passProgress] = $rs[passProgress];
		$adminapi[passTest] = $rs[passTest];
		$adminapi[passReport] = $rs[passReport];
		$adminapi[totalPassMid] = $rs[totalPassMid];
		$adminapi[totalPassTest] = $rs[totalPassTest];
		$adminapi[totalPassReport] = $rs[totalPassReport];
		$adminapi[passScore] = $rs[passScore];
		$adminapi[midRate] = $rs[midRate];
		$adminapi[testRate] = $rs[testRate];
		$adminapi[reportRate] = $rs[reportRate];
		$adminapi[midCaptchaTime] = $rs[midCaptchaTime];
		$adminapi[midStatus] = $rs[midStatus];
		$adminapi[midScore] = $rs[midScore];
		$adminapi[conversionMid] = "$conversionMid";
		$adminapi[midSaveTime] = $rs[midSaveTime];
		$adminapi[midIP] = $rs[midIP];
		$adminapi[testCaptchaTime] = $rs[testCaptchaTime];
		$adminapi[testStatus] = $rs[testStatus];
		$adminapi[testScore] = $rs[testScore];
		$adminapi[conversionTest] = "$conversionTest";
		$adminapi[testTime] = $rs[testTime];
		$adminapi[testStartTime] = $rs[testStartTime];
		$adminapi[testEndTime] = $rs[testEndTime];
		$adminapi[testSaveTime] = $rs[testSaveTime];
		$adminapi[testIP] = $rs[testIP];
		$adminapi[reportCaptchaTime] = $rs[reportCaptchaTime];
		$adminapi[reportStatus] = $rs[reportStatus];
		$adminapi[reportScore] = $rs[reportScore];
		$adminapi[conversionReport] = "$conversionReport";
		$adminapi[reportSaveTime] = $rs[reportSaveTime];
		$adminapi[reportIP] = $rs[reportIP];
		$adminapi[reportCopy] = $rs[reportCopy];
		$adminapi[survey] = $rs[survey];
		$adminapi[contentsName] = $rs[contentsName];
		$adminapi[contentsCode] = $rs[contentsCode];
		$adminapi[professor] = $rs[professor];
		$adminapi[sourceType] = $rs[sourceType];
		$adminapi[progressCheck] = $rs[progressCheck];
		$adminapi[resultView] = $rs[resultView];
		$adminapi[serviceType] = $rs[serviceType];
		$adminapi[midTestChapter] = $rs[midTestChapter];
		$adminapi[midTestProgress] = $rs[midTestProgress];
		
		$queryA = " SELECT A.*, B.userID, B.lectureOpenSeq, IF(ISNULL(B.progress),'0',B.progress) AS progress, 
								B.startTime, B.endTime, B.totalTime, B.studyIP, B.lastPage, B.mobileLastPage 
								FROM nynChapter AS A
								LEFT OUTER
								JOIN nynProgress AS B ON A.contentsCode=B.contentsCode 
								AND A.chapter=B.chapter 
								AND B.lectureOpenSeq='".$lectureOpenSeq."' 
								AND B.userID='".$_SESSION['loginUserID']."'
								WHERE A.contentsCode='".$contentsCode."'".$qChapter." ORDER BY chapter";
		$resultA = mysql_query($queryA);

		$queryB = "SELECT COUNT(*) AS totalCount FROM nynChapter WHERE contentsCode='".$rs[contentsCode]."' AND chapter < 100";
		$resultB = mysql_query($queryB);

		$adminapi[totalCount] = mysql_result($resultB,0,'totalCount');
		$a = 0;

		while($rsA = mysql_fetch_array($resultA)) {
			$adminapi[progress][$a][chapter] = $rsA[chapter];
			$adminapi[progress][$a][chapterName] = $rsA[chapterName];
			$adminapi[progress][$a][progress] = $rsA[progress];
			$adminapi[progress][$a][startTime] = $rsA[startTime];
			$adminapi[progress][$a][endTime] = $rsA[endTime];
			$adminapi[progress][$a][totalTime] = $rsA[totalTime];
			$adminapi[progress][$a][studyIP] = $rsA[studyIP];
			$adminapi[progress][$a][player] = $rsA[player];
			$adminapi[progress][$a][chapterPath] = $rsA[chapterPath];
			$adminapi[progress][$a][chapterMobilePath] = $rsA[chapterMobilePath];
			$adminapi[progress][$a][lastPage] = $rsA[lastPage];
			$adminapi[progress][$a][mobileLastPage] = $rsA[mobileLastPage];
			$adminapi[progress][$a][chapterMobileSize] = $rsA[chapterMobileSize];
			$adminapi[progress][$a][professor] = $rs[professor];
			$adminapi[progress][$a][goal] = $rsA[goal];
			$adminapi[progress][$a][content] = $rsA[content];
			$adminapi[progress][$a][activity] = $rsA[activity];
			$a++;
		}

		$json_encoded = json_encode($adminapi);
		print_r($json_encoded);
	}
?>