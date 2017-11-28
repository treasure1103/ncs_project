<?php
	header('Content-Type:application/json; charset=utf-8');
?>
<? include '../lib/header.php'; ?>
<?
	if($method == "POST") {
		$seq = $_POST['seq'];
		$contentsCode = $_POST['contentsCode'];
		$examNum = $_POST['examNum'];
		$sourceChapter = $_POST['sourceChapter'];
		$exam = addslashes(trim($_POST['exam']));
		$example = addslashes(trim($_POST['example']));
		$rubric = addslashes(trim($_POST['rubric']));  // 채점기준
		$score = $_POST['score'];
		$allDelete = $_POST['allDelete'];

		$attachURL = "/attach/report/exam/";
		$attachURL02 = "/attach/report/example/";
		$attachURL03 = "/attach/report/rubric/";
		$uploadDir = $_SERVER['DOCUMENT_ROOT'].$attachURL;
		$uploadDir02 = $_SERVER['DOCUMENT_ROOT'].$attachURL02;
		$uploadDir03 = $_SERVER['DOCUMENT_ROOT'].$attachURL03;
		$uploadDate = date('i');
		$attachFile01Name = $_FILES['examAttach']["name"];
		$attachFile02Name = $_FILES['exampleAttach']["name"];
		$attachFile03Name = $_FILES['rubricAttach']["name"];
		$delFile01 = $_POST['delFile01'];
		$delFile02 = $_POST['delFile02'];
		$delFile03 = $_POST['delFile03'];

		if($delFile01 == "Y" && $seq) {
			//서버에서 파일 삭제
			$query01 = "SELECT examAttachLink FROM nynReport WHERE seq=".$seq;
			$result01 = mysql_query($query01);
			$dImage01 = mysql_result($result01,0,'examAttachLink');
			$delS01 = $_SERVER['DOCUMENT_ROOT'].$dImage01;
			UNLINK($delS01);

			$queryD01 = "UPDATE nynReport SET examAttach=null, examAttachLink=null WHERE seq = ".$seq;
			$resultD01 = mysql_query($queryD01);
		}

		if($delFile02 == "Y" && $seq) {
			//서버에서 파일 삭제
			$query01 = "SELECT exampleAttachLink FROM nynReport WHERE seq=".$seq;
			$result01 = mysql_query($query01);
			$dImage01 = mysql_result($result01,0,'exampleAttachLink');
			$delS01 = $_SERVER['DOCUMENT_ROOT'].$dImage01;
			UNLINK($delS01);

			$queryD01 = "UPDATE nynReport SET exampleAttach=null, exampleAttachLink=null WHERE seq = ".$seq;
			$resultD01 = mysql_query($queryD01);
		}

		if($delFile03 == "Y" && $seq) {
			//서버에서 파일 삭제
			$query01 = "SELECT rubricAttachLink FROM nynReport WHERE seq=".$seq;
			$result01 = mysql_query($query01);
			$dImage01 = mysql_result($result01,0,'rubricAttachLink');
			$delS01 = $_SERVER['DOCUMENT_ROOT'].$dImage01;
			UNLINK($delS01);

			$queryD01 = "UPDATE nynReport SET rubricAttach=null, rubricAttachLink=null WHERE seq = ".$seq;
			$resultD01 = mysql_query($queryD01);
		}

		if(!$example && !$attachFile02Name) {
			echo '{"result" : "정답을 입력하세요"}';
			exit;
		} else if(!$rubric) {
			echo '{"result" : "채점기준을 작성해 주세요"}';
			exit;
		} else if(!$exam && !$attachFile01Name) {
			echo '{"result" : "지문을 작성해 주세요"}';
			exit;
		} else if(!$examNum) {
			echo '{"result" : "문제번호를 입력하세요"}';
			exit;
		} else if(!$score) {
			echo '{"result" : "배점을 입력하세요"}';
			exit;
		} else if(!$sourceChapter) {
			echo '{"result" : "출처차시를 입력하세요"}';
			exit;
		}

			if($attachFile01Name != "") { //첨부파일01이 있을 경우 업로드
					$attachFile01Temp = $_FILES['examAttach']['tmp_name']; // 업로드 파일 임시저장파일
					$attachFile01Path = $attachURL.$attachFile01Name;
					$attachFile01Save = $uploadDir.$attachFile01Name;

				$nameOK=1;
				$i=1;
				while($nameOK > 0){
					if(file_Exists($attachFile01Save)) { // 같은 파일명이 존재한다면
						$attachFile01Name = $uploadDate.$i."_".$_FILES['examAttach']["name"];
						$attachFile01Path = $attachURL.$attachFile01Name;
						$attachFile01Save = $uploadDir.$attachFile01Name; // 파일명 앞에 시간을 붙임.
						$i++;
					} else {
						$nameOK = 0;
					}
				}
					@move_uploaded_file($attachFile01Temp, $attachFile01Save);
					$upExamAttach = "examAttach='".$_FILES['examAttach']["name"]."', examAttachLink='".$attachFile01Path."', ";
				}

			if($attachFile02Name != "") { //첨부파일02이 있을 경우 업로드
					$attachFile02Temp = $_FILES['exampleAttach']['tmp_name']; // 업로드 파일 임시저장파일
					$attachFile02Path = $attachURL02.$attachFile02Name;
					$attachFile02Save = $uploadDir02.$attachFile02Name;

				$nameOK=1;
				$i=1;
				while($nameOK > 0){
					if(file_Exists($attachFile02Save)) { // 같은 파일명이 존재한다면
						$attachFile02Name = $uploadDate.$i."_".$_FILES['exampleAttach']["name"];
						$attachFile02Path = $attachURL02.$attachFile02Name;
						$attachFile02Save = $uploadDir02.$attachFile02Name; // 파일명 앞에 시간을 붙임.
						$i++;
					} else {
						$nameOK = 0;
					}
				}
					@move_uploaded_file($attachFile02Temp, $attachFile02Save);
					$upExampleAttach = "exampleAttach='".$_FILES['exampleAttach']["name"]."', exampleAttachLink='".$attachFile02Path."', ";
				}

			if($attachFile03Name != "") { //첨부파일03이 있을 경우 업로드
					$attachFile03Temp = $_FILES['rubricAttach']['tmp_name']; // 업로드 파일 임시저장파일
					$attachFile03Path = $attachURL03.$attachFile03Name;
					$attachFile03Save = $uploadDir03.$attachFile03Name;

				$nameOK=1;
				$i=1;
				while($nameOK > 0){
					if(file_Exists($attachFile03Save)) { // 같은 파일명이 존재한다면
						$attachFile03Name = $uploadDate.$i."_".$_FILES['rubricAttach']["name"];
						$attachFile03Path = $attachURL03.$attachFile03Name;
						$attachFile03Save = $uploadDir03.$attachFile03Name; // 파일명 앞에 시간을 붙임.
						$i++;
					} else {
						$nameOK = 0;
					}
				}
					@move_uploaded_file($attachFile03Temp, $attachFile03Save);
					$upRubricAttach = "rubricAttach='".$_FILES['rubricAttach']["name"]."', rubricAttachLink='".$attachFile03Path."', ";
				}

			$queryQ =  "examNum = '".$examNum."',
									sourceChapter = '".$sourceChapter."',
									exam = '".$exam."',
									example = '".$example."',
									rubric = '".$rubric."',
									score = '".$score."'";

		if($seq == "") { // 레포트 등록
			$query = "INSERT INTO nynReport SET originCode = '".$contentsCode."', ".$upExamAttach.$upExampleAttach.$upRubricAttach.$queryQ;
			$result = mysql_query($query);
			$seq = mysql_insert_id();

			$queryM = "INSERT INTO nynReportMapping SET
										contentsCode='".$contentsCode."', 
										reportSeq=".$seq;
			$resultM = mysql_query($queryM);

		} else { //레포트 수정
			$query = "UPDATE nynReport SET ".$upExamAttach.$upExampleAttach.$upRubricAttach.$queryQ." WHERE seq = ".$seq;
			$result = mysql_query($query);
		}

			if($result) {
				echo '{"result" : "success"}';
			} else {
				echo '{"result" : "error"}';
			}
			exit;

	} else if($method == "DELETE") { // 콘텐츠 정보 삭제
			parse_str(file_get_contents("php://input"), $_DEL);
			$allDelete = $_DEL['allDelete'];

			if(!$allDelete){
				$seq = $_DEL['seq'];
				$delQ = "seq=".$seq;
				$delQ02 = "reportSeq=".$seq;

			} else {
				$contentsCode = $_DEL['contentsCode'];
				$delQ = "originCode='".$contentsCode."'";
				$delQ02 = "contentsCode='".$contentsCode."'";
			}

				$query = "DELETE FROM nynReport WHERE ".$delQ;
				$result = mysql_query($query);

				$query2 = "DELETE FROM nynReportMapping WHERE ".$delQ02;
				$result2 = mysql_query($query2);

			if($result) {
				echo '{"result" : "success"}';
			} else {
				echo '{"result" : "error"}';
			}
			exit;

	} else if($method == "GET") { // 콘텐츠 정보 json 출력
			$seq = $_GET['seq'];
			$contentsCode = $_GET['contentsCode'];
			$originCode = $_GET['originCode'];
			$examNum = $_GET['examNum'];

			if($seq != "") {
				if($contentsCode == "" && $originCode != "") {
					$qSeq = " and seq='".$seq."'";
				} else {
					$qSeq = " and R.seq='".$seq."'";
				}
			}

			if($examNum != "") {
				$qExamNum = " and examNum=".$examNum."";
			}

			$qSearch = $qSeq.$qExamNum;

			if($contentsCode != "" && $originCode != "") {
				$query = "SELECT R.*, IF(ISNULL(M.reportSeq),'N','Y') AS checkBox
										FROM nynReport AS R
										LEFT OUTER
										JOIN nynReportMapping AS M 
										ON R.seq=M.reportSeq AND M.contentsCode='".$contentsCode."'
										WHERE R.originCode='".$originCode."'".$qSearch;

			} else if($contentsCode != "" && $originCode == "") { // 수강 과정의 배정된 문제만 검색
				$query = "SELECT * 
									FROM nynReportMapping AS M 
									JOIN nynReport AS R 
									ON M.reportSeq=R.seq 
									WHERE M.contentsCode='".$contentsCode."'".$qSearch." order by R.examNum";
									
			} else if($contentsCode == "" && $originCode != "") { // 배정과 상관없이 원출처가 된 과정코드로 검색
				$query = "SELECT * FROM nynReport WHERE originCode='".$originCode."'".$qSearch;
				$contentsCode = $originCode;

			} else {
				echo '{"result" : "error"}';
				exit;
			}
			$result = mysql_query($query);
			$count = mysql_num_rows($result);

			$qCon = "SELECT contentsName FROM nynContents WHERE contentsCode='".$contentsCode."'";
			$rCon = mysql_query($qCon);

			$a = 0;
			$adminapi = array(); //DB 값이 없는 경우 배열선언 부분

			$adminapi[contentsCode] = $contentsCode;
			$adminapi[contentsName] = mysql_result($rCon,0,'contentsName');
			$adminapi[totalCount] = "$count";

			while($rs = mysql_fetch_array($result)) {
				$adminapi[report][$a][seq] = $rs[seq];
				$adminapi[report][$a][examNum] = $rs[examNum];
				$adminapi[report][$a][sourceChapter] = $rs[sourceChapter];
				$adminapi[report][$a][exam] = stripslashes($rs[exam]);
				$adminapi[report][$a][examAttach] = $rs[examAttach];
				$adminapi[report][$a][examAttachLink] = $rs[examAttachLink];
				$adminapi[report][$a][example] = stripslashes($rs[example]);
				$adminapi[report][$a][exampleAttach] = $rs[exampleAttach];
				$adminapi[report][$a][exampleAttachLink] = $rs[exampleAttachLink];
				$adminapi[report][$a][rubric] = stripslashes($rs[rubric]);
				$adminapi[report][$a][rubricAttach] = $rs[rubricAttach];
				$adminapi[report][$a][rubricAttachLink] = $rs[rubricAttachLink];
				$adminapi[report][$a][score] = $rs[score];
				$adminapi[report][$a][checkBox] = $rs[checkBox];
				$a++;
			}

			$json_encoded = json_encode($adminapi);
			print_r($json_encoded);
		}
		
	@mysql_close();
?>