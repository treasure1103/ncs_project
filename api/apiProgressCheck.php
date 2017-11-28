<?php
  header("Content-Type: application/json; charset=UTF-8;");
?>
<? include '../lib/header.php'; ?>
<?
	if($method == "POST") {
		$lectureOpenSeq = $_POST['lectureOpenSeq'];
		$contentsCode = $_POST['contentsCode'];
		$chapter = $_POST['chapter'];
		$nowPage = $_POST['nowPage'];
		$nowPageURL = $_POST['nowPageURL'];
		$totalTime = $_POST['totalTime'];
		$mobile = $_POST['mobile'];
		$progressCheck = $_POST['progressCheck']; // pageCheck, timeCheck 구분값

		if(trim($_SESSION['loginUserID']) == "") {
			echo '{"result" : "logout"}';
			exit;
		}

		//순차 학습 확인
		if($chapter != 1) {
			$prevChapter = $chapter-1;
			$queryI = " SELECT progress
									FROM nynProgress 
									WHERE lectureOpenSeq='".$lectureOpenSeq."' 
									AND userID='".$_SESSION['loginUserID']."'
									AND chapter='".$prevChapter."'";
			$resultI = mysql_query($queryI);
			$rsI = mysql_fetch_array($resultI);
			$countI = mysql_num_rows($resultI);

			if($countI == 0) { // 이전 차시 기록이 있는지
				echo '{"result" : "순차 학습 오류"}';
				exit;
			}
			$prevProgress = $rsI[progress];

			if($prevProgress < 80) { // 이전 차시 진도율이 80% 이상인지
				echo '{"result" : "순차 학습 오류 (이전 차시 진도율 미달)"}';
				exit;
			}
		}

		$queryF = "SELECT progress, seq FROM nynProgress WHERE lectureOpenSeq='".$lectureOpenSeq."' AND userID='".$_SESSION['loginUserID']."' AND chapter='".$chapter."'";
		$resultF = mysql_query($queryF);
		$countF = mysql_num_rows($resultF);
		$rsF = mysql_fetch_array($resultF);
		$progress = $rsF[progress];
		$seq = $rsF[seq];

		if($mobile == "Y") {
			$lastPageQ = "mobileLastPage='".$nowPageURL."'";
		} else {
			$lastPageQ = "lastPage='".$nowPageURL."'";
		}

		if($countF == 0) { // 차시진도 데이터가 없는 경우 Insert

			// 일일 학습 제한(8차시) 확인
			$queryH = " SELECT COUNT(*) AS progressLimit 
									FROM nynProgress 
									WHERE lectureOpenSeq='".$lectureOpenSeq."' 
									AND userID='".$_SESSION['loginUserID']."' 
									AND EndTime BETWEEN '".substr($inputDate,0,10)." 00:00:00' AND '".substr($inputDate,0,10)." 23:59:59'";
			$resultH = mysql_query($queryH);
			$rsH = mysql_fetch_array($resultH);
			$progressLimit = $rsH[progressLimit];

			if($progressLimit >= 8) {
					echo '{"result" : "일일 학습 제한 초과"}';
					exit;
			}
			$nowProgress = 1;
			$queryG = " INSERT INTO nynProgress 
									SET lectureOpenSeq='".$lectureOpenSeq."', 
											userID='".$_SESSION['loginUserID']."',
											chapter='".$chapter."',
											contentsCode='".$contentsCode."',
											progress='".$nowProgress."',
											startTime='".$inputDate."',
											endTime='".$inputDate."',
											studyIP='".$userIP."',
											totalTime='".$totalTime."',
											progressID=CONCAT('".$lectureOpenSeq."','_','".$contentsCode."','_','".$_SESSION['loginUserID']."','_','".$chapter."'), 
											".$lastPageQ;
			$resultG = mysql_query($queryG);

			if($resultG) {
				echo '{"result" : "success"}';
			} else {
				echo '{"result" : "error"}';
			}
			exit;

		} else {

			//플레이타임 (누적시간)
			$queryE = " UPDATE nynProgress 
									SET studyIP='".$userIP."', 
											recentlyTime='".$inputDate."', 
											totalTime='".$totalTime."',
											".$lastPageQ." 
									WHERE seq='".$seq."'";
			$resultE = mysql_query($queryE);
		}

		$query = "SELECT chapter, progressCheck FROM nynContents WHERE contentsCode='".$contentsCode."'";
		$result = mysql_query($query);
		$rs = mysql_fetch_array($result);
		$contentsSize = $rs[chapter];
		$progressCheck = $rs[progressCheck];
 
		$queryA = "SELECT chapterSize, chapterMobileSize FROM nynChapter WHERE contentsCode='".$contentsCode."' AND chapter='".$chapter."'";
		$resultA = mysql_query($queryA);
		$rsA = mysql_fetch_array($resultA);

		if($mobile == "Y") {
			$chapterSize = $rsA[chapterMobileSize];
		} else {
			$chapterSize = $rsA[chapterSize];
		}

		if($progressCheck == "timeCheck") { // 시간체크과정인 경우 1분에 진도율 10%로 계산.
			if($totalTime > 600) { // 총 학습시간이 10분을 넘기는 경우는 100%
				$nowProgress = "100";
			} else { // 10분 이하일때만 진도율 계산
				$nowProgress = floor(($totalTime/60)*10);
			}

		} else { // 페이지 체크 과정인 경우 현재페이지수/전체페이지수
			$nowProgress = floor(($nowPage/$chapterSize)*100); // 차시 진도율 구함
		}

		//현재 차시 진도율이 기존 차시 진도율보다 작다면 종료
		if($nowProgress <= $progress) { 
			exit;
		}

		//최소 학습시간 5분이 지났는지 확인
		if($progressCheck != "timeCheck") {
			if($totalTime < 300) {
				echo '{"result" : "최소 학습 시간 미달"}';
				exit;
			}
		}
		if($rsF[progress] > 79) {
			$qEndTime = "";
		} else {
			$qEndTime = "endTime='".$inputDate."', ";
		}
		if($nowProgress > 100) {
			$nowProgress = 100;
		}
		//진도율 업데이트
		$queryC = " UPDATE nynProgress 
								SET progress='".$nowProgress."', 
										".$qEndTime." 
										studyIP='".$userIP."',
										recentlyTime='".$inputDate."',
										totalTime='".$totalTime."',
										".$lastPageQ." 
								WHERE seq='".$seq."'";
		$resultC = mysql_query($queryC);

		// 전체 진도율 계산
		$queryB = "SELECT SUM(progress) AS sumProgress FROM nynProgress WHERE lectureOpenSeq='".$lectureOpenSeq."' AND userID='".$_SESSION['loginUserID']."'";
		$resultB = mysql_query($queryB);
		$rsB = mysql_fetch_array($resultB);
		$sumProgress = $rsB[sumProgress];
		$totalProgress = floor($sumProgress/$contentsSize);
		if($totalProgress > 100) {
			$totalProgress = 100;
		}
		$queryD = " UPDATE nynStudy
								SET progress='".$totalProgress."',
										accessIP='".$userIP."'
								WHERE lectureOpenSeq='".$lectureOpenSeq."' 
								AND userID='".$_SESSION['loginUserID']."'";
		$resultD = mysql_query($queryD);

		if($resultD) {
			echo '{"result" : "success"}';
		} else {
			echo '{"result" : "error"}';
		}
		exit;

}
mysql_close();
?>