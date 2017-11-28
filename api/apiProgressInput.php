<?php
		//header('Content-Type:application/json; charset=utf-8');
		include '../lib/header.php';

		// 수강을 실수로 삭제한 경우 progress 데이터를 생성하여 복구 하는 페이지
		// nynStudyBackup 에 데이터가 있어야 복구가 가능함 (삭제한 수강만)

		$userID = $_POST['userID'];
		$lectureStart = $_POST['lectureStart'];
		$lectureEnd = $_POST['lectureEnd'];
		$contentsCode = $_POST['contentsCode'];
		$progress = $_POST['progress'];

		$query = "SELECT * FROM nynStudyBackup WHERE userID='".$userID."' AND contentsCode='".$contentsCode."' AND lectureStart='".$lectureStart."' AND lectureEnd='".$lectureEnd."' LIMIT 1";
		$result = mysql_query($query);
		$count = mysql_num_rows($result);

		if($count == 0){
			echo '{"result" : "수강 삭제된 내역이 없어 복구할 수 없습니다."}';
			exit;
		}

		$queryA = "SELECT chapter FROM nynContents WHERE contentsCode='".$contentsCode."'";
		$resultA = mysql_query($queryA);
		$rsA = mysql_fetch_assoc($resultA);
		$chapter = $rsA['chapter'];

		if($chapter == '') {
			echo '{"result" : "과정 정보가 없습니다."}';
			exit;
		}

		while($rs = mysql_fetch_array($result)) {
			$lectureOpenSeq = $rs['lectureOpenSeq'];
			$updateChapter  = ROUND($chapter*($progress/100));
			
			$queryD = "DELETE FROM nynProgress WHERE userID='".$userID."' AND lectureOpenSeq='".$lectureOpenSeq."'";
			$resultD = mysql_query($queryD);
			$rsD = mysql_fetch_assoc($resultD);

			for($m=1; $m<=$updateChapter; $m++) {
				$queryG = " INSERT INTO nynProgress 
										SET lectureOpenSeq='".$lectureOpenSeq."', 
												userID='".$userID."',
												chapter='".$m."',
												contentsCode='".$contentsCode."',
												progress='100',
												startTime='".$inputDate."',
												endTime='".$inputDate."',
												progressID=CONCAT('".$lectureOpenSeq."','_','".$contentsCode."','_','".$userID."','_','".$m."'), 
												studyIP='".$userIP."',
												totalTime='300'";
				$resultG = mysql_query($queryG);
			}
		}
		echo '{"result" : "success"}';
		exit;
		@mysql_close();
?>