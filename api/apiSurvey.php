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
			$seq = $_GET['seq'];

			if($seq != "") {
				$qSeq = "WHERE seq=".$seq;
			}

			$query = "SELECT * FROM nynSurvey ".$qSeq." ORDER BY orderBy";
			$result = mysql_query($query);
			$count = mysql_num_rows($result);
			$a = 0;
			$adminapi = array(); //DB 값이 없는 경우 배열선언 부분

			$adminapi[totalCount] = "$count"; //총 개시물 수

			while($rs = mysql_fetch_array($result)) {
				$adminapi[survey][$a][seq] = $rs[seq];
				$adminapi[survey][$a][orderBy] = $rs[orderBy];
				$adminapi[survey][$a][enabled] = $rs[enabled];
				$adminapi[survey][$a][surveyType] = $rs[surveyType];
				$adminapi[survey][$a][exam] = $rs[exam];
				
				if($rs[surveyType] == "A") {
					$queryA = "SELECT * FROM nynSurveyExample WHERE surveySeq=".$rs[seq]." ORDER BY exampleNum";
					$resultA = mysql_query($queryA);
					$n=1;

						while($rsA = mysql_fetch_array($resultA)) {
							if($rsA[exampleNum] != "") {
								$adminapi[survey][$a][example0.$n] = stripslashes($rsA[example]);
								$n++;
							}
						}
				}
				$a++;
			}

			$json_encoded = json_encode($adminapi);
			print_r($json_encoded);
		}
		
	@mysql_close();
?>