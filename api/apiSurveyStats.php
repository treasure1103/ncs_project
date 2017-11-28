<?php
		header('Content-Type:application/json; charset=utf-8');
?>
<? include '../lib/header.php'; ?>
<?
		if($method == "GET") { // json 출력
			$lectureOpenSeq = $_GET['lectureOpenSeq'];
			$contentsCode = $_GET['contentsCode'];

			if($lectureOpenSeq != "") {
				$qLectureOpenSeq = " AND lectureOpenSeq=".$lectureOpenSeq."";
			}
			if($contentsCode != "") {
				$qContentsCode = " AND contentsCode=".$contentsCode."";
			}

			$qSearch = $qLectureOpenSeq.$qContentsCode;

			$query = "SELECT * FROM nynSurvey WHERE surveyType='A' ORDER BY orderBy";
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
				
					$queryA = "SELECT * FROM nynSurveyExample WHERE surveySeq=".$rs[seq]." ORDER BY exampleNum";
					$resultA = mysql_query($queryA);
					$n=1;

						while($rsA = mysql_fetch_array($resultA)) {
							if($rsA[exampleNum] != "") {
								$adminapi[survey][$a][example0.$n] = stripslashes($rsA[example]);
								$n++;
							}
						}

				if($n==6) {
					$qNum05 = ", (SELECT COUNT(*) FROM nynSurveyAnswer WHERE surveySeq='".$rs[seq]."' AND userAnswer='5') AS num05Count";
				}

				$queryB = " SELECT
										(SELECT COUNT(*) FROM nynSurveyAnswer WHERE surveySeq='".$rs[seq]."'".$qSearch.") AS totalCount, 
										(SELECT COUNT(*) FROM nynSurveyAnswer WHERE surveySeq='".$rs[seq]."' AND userAnswer='1'".$qSearch.") AS num01Count,
										(SELECT COUNT(*) FROM nynSurveyAnswer WHERE surveySeq='".$rs[seq]."' AND userAnswer='2'".$qSearch.") AS num02Count,
										(SELECT COUNT(*) FROM nynSurveyAnswer WHERE surveySeq='".$rs[seq]."' AND userAnswer='3'".$qSearch.") AS num03Count,
										(SELECT COUNT(*) FROM nynSurveyAnswer WHERE surveySeq='".$rs[seq]."' AND userAnswer='4'".$qSearch.") AS num04Count".$qNum05;
				$resultB = mysql_query($queryB);
				$rsB = mysql_fetch_array($resultB);

				$num01Rate = ($rsB[num01Count]/$rsB[totalCount]) * 100;
				$num02Rate = ($rsB[num02Count]/$rsB[totalCount]) * 100;
				$num03Rate = ($rsB[num03Count]/$rsB[totalCount]) * 100;
				$num04Rate = ($rsB[num04Count]/$rsB[totalCount]) * 100;

				$adminapi[survey][$a][stats][$b][totalCount] = $rsB[totalCount];
				$adminapi[survey][$a][stats][$b][num01Count] = $rsB[num01Count];
				$adminapi[survey][$a][stats][$b][num01Rate] = "$num01Rate";
				$adminapi[survey][$a][stats][$b][num02Count] = $rsB[num02Count];
				$adminapi[survey][$a][stats][$b][num02Rate] = "$num02Rate";
				$adminapi[survey][$a][stats][$b][num03Count] = $rsB[num03Count];
				$adminapi[survey][$a][stats][$b][num03Rate] = "$num03Rate";
				$adminapi[survey][$a][stats][$b][num04Count] = $rsB[num04Count];
				$adminapi[survey][$a][stats][$b][num04Rate] = "$num04Rate";

				if($n==6) {
					$num05Rate = ($rsB[num05Count]/$rsB[totalCount]) * 100;
					$adminapi[survey][$a][stats][$b][num05Count] = $rsB[num05Count];
					$adminapi[survey][$a][stats][$b][num05Rate] = "$num05Rate";
				}

				$a++;
			}

			$json_encoded = json_encode($adminapi);
			print_r($json_encoded);
		}
?>