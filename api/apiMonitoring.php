<?php
	header('Content-Type:application/json; charset=utf-8');
?>
<? include '../lib/header.php'; ?>
<?
	if($method == "POST") {
		$seq = $_POST['seq'];
		$orderBy = $_POST['orderBy'];
		$exam = addslashes(trim($_POST['exam']));
		$example01 = addslashes(trim($_POST['example01']));
		$example02 = addslashes(trim($_POST['example02']));
		$example03 = addslashes(trim($_POST['example03']));
		$example04 = addslashes(trim($_POST['example04']));
		$example05 = addslashes(trim($_POST['example05']));
		$surveyType = $_POST['surveyType'];
		$enabled = $_POST['enabled'];

		if($exmaple05 != "") {
			$loopNum = 5;
		} else {
			$loopNum = 4;
		}

		$queryQ = " orderBy='".$orderBy."', 
								exam='".$exam."', 
								surveyType='".$surveyType."', 
								enabled='".$enabled."'";

		if($seq == "") { // 정보 등록
				$query = "INSERT INTO nynSurvey SET ".$queryQ;
				$result = mysql_query($query);

				$queNum="SELECT MAX(seq) AS seq FROM nynSurvey";
				$resultNum = mysql_query($queNum);
				$rsNum = mysql_fetch_assoc($resultNum);
				$seq = $rsNum[seq];

				if($surveyType == "A") { // 사지선다면 보기항목도 같이 등록
					for($b=1; $b<=$loopNum; $b++) {
						$example = ${"example0".$b};
						$query02 = "INSERT INTO nynSurveyExample SET
													surveySeq='".$seq."',
													exampleNum='".$b."', 
													example='".$example."'";
						$result02 = mysql_query($query02);
					}
				}

		} else { // 수정
				$query = "UPDATE nynSurvey SET ".$queryQ." WHERE seq=".$seq;
				$result = mysql_query($query);

				if($surveyType == "A") { // 객관식이면 보기항목도 같이 수정
					for($b=1; $b<=$loopNum; $b++) {
						$example = ${"example0".$b};
						$query02 = "UPDATE nynSurveyExample 
												SET example='".$example."'
												WHERE serveySeq='".$seq."' 
												AND exampleNum='".$b."'";
						$result02 = mysql_query($query02);
					}
				}
		}
	
			if($result){
				echo $seq;
			} else {
				echo "error";
			}
			exit;

	} else if($method == "DELETE") { // 삭제
			parse_str(file_get_contents("php://input"), $_DEL);
			$seq = $_DEL['seq'];

			$query = "DELETE FROM nynSurvey WHERE seq=".$seq;
			$result = mysql_query($query);

			$queryD = "DELETE FROM nynSurveyExample WHERE surveySeq=".$seq;
			$resultD = mysql_query($queryD);

			if($result){
				echo "success";
			} else {
				echo "error";
			}
			exit;

	} else if($method == "GET") { // json 출력
			$lectureDay = $_GET['lectureDay']; // 수강일
		  $lectureSE = EXPLODE('~',$lectureDay);

			$query = "SELECT
								Z.tutor, Z.contentsCode, Z.lectureOpenSeq,  
								(
								SELECT userName
								FROM nynMember
								WHERE userID=Z.tutor AND userLevel='7') AS tutorName,
								(
								SELECT contentsName
								FROM nynContents
								WHERE contentsCode=Z.contentsCode) AS contentsName,
								(
								SELECT COUNT(*)
								FROM nynStudy
								WHERE tutor=Z.tutor AND lectureStart='".$lectureSE[0]."' AND lectureEnd='".$lectureSE[1]."' AND contentsCode=Z.contentsCode) AS tutorCount,
								(
								SELECT COUNT(*)
								FROM nynStudy
								WHERE tutor=Z.tutor AND midStatus='Y' AND lectureStart='".$lectureSE[0]."' AND lectureEnd='".$lectureSE[1]."' AND contentsCode=Z.contentsCode) AS midStandby,
								(
								SELECT COUNT(*)
								FROM nynStudy
								WHERE tutor=Z.tutor AND midStatus='C' AND lectureStart='".$lectureSE[0]."' AND lectureEnd='".$lectureSE[1]."' AND contentsCode=Z.contentsCode) AS midComplete,
								(
								SELECT COUNT(*)
								FROM nynStudy
								WHERE tutor=Z.tutor AND (testStatus='V' OR testStatus='Y') AND lectureStart='".$lectureSE[0]."' AND lectureEnd='".$lectureSE[1]."' AND contentsCode=Z.contentsCode) AS testStandby,
								(
								SELECT COUNT(*)
								FROM nynStudy
								WHERE tutor=Z.tutor AND testStatus='C' AND lectureStart='".$lectureSE[0]."' AND lectureEnd='".$lectureSE[1]."' AND contentsCode=Z.contentsCode) AS testComplete,
								(
								SELECT COUNT(*)
								FROM nynStudy
								WHERE tutor=Z.tutor AND reportStatus='Y' AND lectureStart='".$lectureSE[0]."' AND lectureEnd='".$lectureSE[1]."' AND contentsCode=Z.contentsCode) AS reportStandby,
								(
								SELECT COUNT(*)
								FROM nynStudy
								WHERE tutor=Z.tutor AND reportStatus='C' AND lectureStart='".$lectureSE[0]."' AND lectureEnd='".$lectureSE[1]."' AND contentsCode=Z.contentsCode) AS reportComplete
								FROM
								(
								SELECT DISTINCT(tutor), contentsCode, lectureOpenSeq
								FROM nynStudy
								WHERE lectureStart='".$lectureSE[0]."' AND lectureEnd='".$lectureSE[1]."') AS Z
								ORDER BY tutorName, contentsName";
			$result = mysql_query($query);
			$count = mysql_num_rows($result);
			$a = 0;
			$adminapi = array(); //DB 값이 없는 경우 배열선언 부분

			$adminapi[totalCount] = "$count"; //총 개시물 수
			$tutorDeadline = date("Y-m-d", strtotime($lectureSE[1]."+4Day"));

			$adminapi[lectureStart] = $lectureSE[0];
			$adminapi[lectureEnd] = $lectureSE[1];
			$adminapi[tutorDeadline] = $tutorDeadline;

			while($rs = mysql_fetch_array($result)) {
				$adminapi[tutor][$a][tutorCount] = $rs[tutorCount];
				$adminapi[tutor][$a][lectureOpenSeq] = $rs[lectureOpenSeq];
				$adminapi[tutor][$a][tutorID] = $rs[tutor];
				$adminapi[tutor][$a][tutorName] = $rs[tutorName];
				$adminapi[tutor][$a][contentsName] = $rs[contentsName];
				$adminapi[tutor][$a][contentsCode] = $rs[contentsCode];
				$adminapi[tutor][$a][midStandby] = $rs[midStandby];
				$adminapi[tutor][$a][midComplete] = $rs[midComplete];
				$midSubmit = $rs[midStandby] + $rs[midComplete];
				$adminapi[tutor][$a][midSubmit] = $midSubmit;
				$adminapi[tutor][$a][testStandby] = $rs[testStandby];
				$adminapi[tutor][$a][testComplete] = $rs[testComplete];
				$testSubmit = $rs[testStandby] + $rs[testComplete];
				$adminapi[tutor][$a][testSubmit] = $testSubmit;
				$adminapi[tutor][$a][reportStandby] = $rs[reportStandby];
				$adminapi[tutor][$a][reportComplete] = $rs[reportComplete];
				$reportSubmit = $rs[reportStandby] + $rs[reportComplete];
				$adminapi[tutor][$a][reportSubmit] = $reportSubmit;
				$a++;
			}

			$json_encoded = json_encode($adminapi);
			print_r($json_encoded);
		}
		
	@mysql_close();
?>