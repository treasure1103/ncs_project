<?php
		header('Content-Type:application/json; charset=utf-8');
?>
<? include '../lib/header.php'; ?>
<?
	if($method == "POST") {
		$seq = $_POST['seq'];
		$contentsCode = $_POST['contentsCode'];
		$examNum = $_POST['examNum'];
		$exam = addslashes(trim($_POST['exam']));
		$example01 = addslashes(trim($_POST['example01']));
		$example02 = addslashes(trim($_POST['example02']));
		$example03 = addslashes(trim($_POST['example03']));
		$example04 = addslashes(trim($_POST['example04']));
		$example05 = addslashes(trim($_POST['example05']));
		$testType = $_POST['testType'];
		$examType = $_POST['examType'];
		$answer = trim($_POST['answer']);
		$answerText = addslashes(trim($_POST['answerText']));
		$score = $_POST['score'];
		$sourceChapter = $_POST['sourceChapter'];
		$commentary = addslashes(trim($_POST['commentary']));

		if(!$answer && !$answerText) {
			echo '{"result" : "정답을 입력하세요"}';
			exit;
		} else if(!$commentary) {
			echo '{"result" : "해설을 작성해 주세요"}';
			exit;
		} else if(!$exam) {
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

		if($examType == "A") {
			if($example05 != "") {
				$loopNum = 5;
			} else {
				$loopNum = 4;
			}
		} else if($examType == "D") {
			$loopNum = 2;
		}

		$queryQ = " originCode='".$contentsCode."', 
								sourceChapter='".$sourceChapter."', 
								examNum='".$examNum."', 
								exam='".$exam."', 
								commentary='".$commentary."', 
								score='".$score."', 
								testType='".$testType."',
								examType='".$examType."'";

		if($seq == "") { // 평가시험 정보 등록
			if($examType == "A" || $examType == "D") { // 객관식, 진위형이면 보기항목도 같이 등록
				$query = "INSERT INTO nynTest SET
											answer='".$answer."', "
											.$queryQ;
				$result = mysql_query($query);
				$seq = mysql_insert_id();

					for($b=1; $b<=$loopNum; $b++) {
						$example = ${"example0".$b};
						$query02 = "INSERT INTO nynTestExample SET
													testSeq='".$seq."',
													originCode='".$contentsCode."', 
													exampleNum='".$b."', 
													example='".$example."'";
						$result02 = mysql_query($query02);
					}

			} else { // 단답형, 서술형 저장
				$query = "INSERT INTO nynTest SET
											answerText='".$answerText."', "
											.$queryQ;
				$result = mysql_query($query);
				$seq = mysql_insert_id();
			}

			$queryM = "INSERT INTO nynTestMapping SET
										contentsCode='".$contentsCode."', 
										testType='".$testType."', 
										testSeq=".$seq;
			$resultM = mysql_query($queryM);

		} else { // 평가시험 수정
			if($examType == "A" || $examType == "D") { // 객관식이면 보기항목도 같이 수정
				$query = "UPDATE nynTest SET 
											answer=".$answer.", ".$queryQ." 
										WHERE seq=".$seq;
				$result = mysql_query($query);
				
					for($b=1; $b<=$loopNum; $b++) {
						$example = ${"example0".$b};
						$query02 = "UPDATE nynTestExample SET
													example='".$example."'
												WHERE originCode='".$contentsCode."' AND testSeq=".$seq." AND exampleNum=".$b;
						$result02 = mysql_query($query02);
					}

			} else { // 단답형, 서술형 수정
				$query = "UPDATE nynTest SET 
											answerText='".$answerText."', ".$queryQ."
										WHERE seq=".$seq;
				$result = mysql_query($query);
			}

		}
	
			if($result) {
				echo '{"result" : "success"}';
			} else {
				echo '{"result" : "error"}';
			}
			exit;

	} else if($method == "DELETE") { // 평가시험 삭제
			parse_str(file_get_contents("php://input"), $_DEL);
			$allDelete = $_DEL['allDelete'];
			$seq = $_DEL['seq'];
			$contentsCode = $_DEL['contentsCode'];
			$testType = $_DEL['testType'];

			if($allDelete){ //전체 삭제
				$query = "DELETE A, B
									FROM nynTest AS A
									LEFT OUTER
									JOIN nynTestExample AS B
									ON A.seq=B.testSeq
									WHERE A.originCode='".$contentsCode."' AND A.testType='".$testType."'";
				$result = mysql_query($query);

				$queryD = "DELETE FROM nynTestMapping WHERE contentsCode='".$contentsCode."' AND testType='".$testType."'";
				$resultD = mysql_query($queryD);

			} else { // 개별 삭제
				$query = "DELETE A, B
									FROM nynTest AS A
									LEFT OUTER
									JOIN nynTestExample AS B ON A.seq=B.testSeq
									WHERE A.seq='".$seq."'";
				$result = mysql_query($query);

				$queryD = "DELETE FROM nynTestMapping WHERE testSeq=".$seq;
				$resultD = mysql_query($queryD);
			}

			if($result) {
				echo '{"result" : "success"}';
			} else {
				echo '{"result" : "error"}';
			}
			exit;

	} else if($method == "GET") { // 평가시험 json 출력
			$seq = $_GET['seq'];
			$contentsCode = $_GET['contentsCode'];
			$originCode = $_GET['originCode'];
			$testType = $_GET['testType'];
			$examNum = $_GET['examNum'];
			$examType = $_GET['examType'];
			$testType = $_GET['testType'];

			if($seq != "") {
				$qSeq = " AND T.seq='".$seq."'";
			}
			if($examNum != "") {
				$qExamNum = " AND examNum=".$examNum."";
			}
			if($examType != "") {
				$qExamType = " AND examType='".$examType."'";
			}

			$qSearch = $qSeq.$qExamNum.$qExamType;

			if($contentsCode != "" && $originCode != "") {
				$query = "SELECT T.*, IF(ISNULL(M.testSeq),'N','Y') AS checkBox
									FROM nynTest AS T
									LEFT OUTER
									JOIN nynTestMapping AS M 
									ON T.seq=M.testSeq 
									AND M.contentsCode='".$contentsCode."'
									WHERE T.testType='".$testType."' AND T.originCode='".$originCode."'".$qSearch." ORDER BY T.examNum";

			} else if($contentsCode != "" && $originCode == "") { // 수강 과정의 배정된 문제만 검색
				$query = "SELECT * 
									FROM nynTestMapping AS M 
									JOIN nynTest AS T
									ON M.testSeq=T.seq 
									WHERE M.testType='".$testType."' AND M.contentsCode='".$contentsCode."'".$qSearch." ORDER BY T.examNum";

			} else if($contentsCode == "" && $originCode != "") { // 배정과 상관없이 원출처가 된 과정코드로 검색
				$query = "SELECT * FROM nynTest WHERE testType='".$testType."' AND originCode='".$originCode."'".$qSearch." ORDER BY examNum";
				$contentsCode=$originCode;

			} else {
				echo '{"result" : "error"}';
				exit;
			}

			$result = mysql_query($query);
			$count = mysql_num_rows($result);

			$qCon = " SELECT 
								contentsName, mid01EA, mid02EA, mid03EA, mid04EA, test01EA, test02EA, test03EA, test04EA, reportEA,
								(
								SELECT COUNT(*)
								FROM nynTestMapping AS M
								LEFT OUTER
								JOIN nynTest AS T ON M.testSeq=T.seq
								WHERE M.testType='".$testType."' AND M.contentsCode='".$contentsCode."' AND T.examType='A') AS aTypeEA,
								(
								SELECT COUNT(*)
								FROM nynTestMapping AS M
								LEFT OUTER
								JOIN nynTest AS T ON M.testSeq=T.seq
								WHERE M.testType='".$testType."' AND M.contentsCode='".$contentsCode."' AND T.examType='B') AS bTypeEA,
								(
								SELECT COUNT(*)
								FROM nynTestMapping AS M
								LEFT OUTER
								JOIN nynTest AS T ON M.testSeq=T.seq
								WHERE M.testType='".$testType."' AND M.contentsCode='".$contentsCode."' AND T.examType='C') AS cTypeEA,
								(
								SELECT COUNT(*)
								FROM nynTestMapping AS M
								LEFT OUTER
								JOIN nynTest AS T ON M.testSeq=T.seq
								WHERE M.testType='".$testType."' AND M.contentsCode='".$contentsCode."' AND T.examType='D') AS dTypeEA
								FROM nynContents
								WHERE contentsCode='".$contentsCode."'";
			$rCon = mysql_query($qCon);
			$a = 0;
			$adminapi = array(); //DB 값이 없는 경우 배열선언 부분

			$adminapi[contentsCode] = $contentsCode;
			$adminapi[contentsName] = mysql_result($rCon,0,'contentsName');
			$adminapi[testType] = $testType;
			$adminapi[aTypeEA] = mysql_result($rCon,0,'aTypeEA');
			$adminapi[bTypeEA] = mysql_result($rCon,0,'bTypeEA');
			$adminapi[cTypeEA] = mysql_result($rCon,0,'cTypeEA');
			$adminapi[dTypeEA] = mysql_result($rCon,0,'dTypeEA');
			$adminapi[mid01EA] = mysql_result($rCon,0,'mid01EA');
			$adminapi[mid02EA] = mysql_result($rCon,0,'mid02EA');
			$adminapi[mid03EA] = mysql_result($rCon,0,'mid03EA');
			$adminapi[mid04EA] = mysql_result($rCon,0,'mid04EA');
			$adminapi[test01EA] = mysql_result($rCon,0,'test01EA');
			$adminapi[test02EA] = mysql_result($rCon,0,'test02EA');
			$adminapi[test03EA] = mysql_result($rCon,0,'test03EA');
			$adminapi[test04EA] = mysql_result($rCon,0,'test04EA');
			$adminapi[reportEA] = mysql_result($rCon,0,'reportEA');
			$adminapi[totalCount] = "$count"; //총 개시물 수

			while($rs = mysql_fetch_array($result)) {
				$adminapi[test][$a][seq] = $rs[seq];
				$adminapi[test][$a][examNum] = $rs[examNum];
				$adminapi[test][$a][examType] = $rs[examType];
				$adminapi[test][$a][exam] = stripslashes($rs[exam]);
	
					if($rs[examType] == "A" || $rs[examType] == "D") {
						$qExamP = "SELECT exampleNum, example FROM nynTestExample where testSeq=".$rs[seq]." order by testSeq, exampleNum";
						$rExamP = mysql_query($qExamP);

							for($e=0; $e<=4; $e++) {
								$n=$e+1;
								if(mysql_result($rExamP,$e,'exampleNum') != "") {
										$adminapi[test][$a][example0.$n] = stripslashes(mysql_result($rExamP,$e,'example'));
								}
							}

						$adminapi[test][$a][answer] = $rs[answer];
					} else {
						$adminapi[test][$a][answerText] = stripslashes($rs[answerText]);
					}

				$adminapi[test][$a][commentary] = stripslashes($rs[commentary]);
				$adminapi[test][$a][score] = $rs[score];
				$adminapi[test][$a][sourceChapter] = $rs[sourceChapter];
				$adminapi[test][$a][checkBox] = $rs[checkBox];
				$a++;
			}

			$json_encoded = json_encode($adminapi);
			print_r($json_encoded);
		}
?>