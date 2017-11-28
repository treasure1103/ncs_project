<?php
	header('Content-Type:application/json; charset=utf-8');
?>
<? include '../lib/header.php'; ?>
<?
			$testType = $_GET['testType'];
			$lectureOpenSeq = $_GET['lectureOpenSeq'];

			$query = "SELECT A.lectureStart, A.lectureEnd, D.contentsName, D.contentsCode,
											 A.midStatus, D.mid01EA, D.mid02EA, D.mid03EA, D.mid04EA,
											 D.mid01Score, D.mid02Score, D.mid03Score, D.mid04Score, 
											 A.testStatus, D.test01EA, D.test02EA, D.test03EA, D.test04EA,
											 D.test01Score, D.test02Score, D.test03Score, D.test04Score,
											 A.reportStatus, D.reportEA
								FROM nynStudy AS A
								LEFT OUTER
								JOIN nynContents AS D ON A.contentsCode=D.contentsCode
								WHERE A.lectureOpenSeq='".$lectureOpenSeq."'
								AND A.userID='".$_SESSION['loginUserID']."'";
			$result = mysql_query($query);
			$rs = mysql_fetch_array($result);

			$queryA = " SELECT * FROM nynTestAnswer 
									WHERE lectureOpenSeq='".$lectureOpenSeq."' AND userID='".$_SESSION['loginUserID']."' AND testType='".$testType."' AND
									(((examType='A' OR examType='D') AND (userAnswer IS NULL OR userAnswer='')) OR 
									((examType='B' OR examType='C') AND (userTextAnswer IS NULL OR userTextAnswer='')))";
			$resultA = mysql_query($queryA);
			$rsA = mysql_fetch_array($resultA);
			$allPost = mysql_num_rows($resultA); // 제출하지 않은 문제 수

			$adminapi = array(); //DB 값이 없는 경우 배열선언 부분

			if($testType == "mid") {
				$status = $rs[midStatus];
				$aTypeEA = $rs[mid01EA];
				$bTypeEA = $rs[mid02EA];
				$cTypeEA = $rs[mid03EA];
				$dTypeEA = $rs[mid04EA];

			} else if($testType == 'final') {
				$status = $rs[testStatus];
				$aTypeEA = $rs[test01EA];
				$bTypeEA = $rs[test02EA];
				$cTypeEA = $rs[test03EA];
				$dTypeEA = $rs[test04EA];

			} else {
				$status = $rs[reportStatus];
				$reportEA = $rs[reportEA];
			}

			$totalCount = $aTypeEA + $bTypeEA + $cTypeEA + $dTypeEA;
			$adminapi[lectureOpenSeq] = "$lectureOpenSeq";
			$adminapi[lectureStart] = $rs[lectureStart];
			$adminapi[lectureEnd] = $rs[lectureEnd];
			$adminapi[contentsName] = $rs[contentsName];
			$adminapi[contentsCode] = $rs[contentsCode];
			$adminapi[totalCount] = "$totalCount";

			if($testType == "report") {
				$adminapi[reportEA] = "$reportEA";
			} else {
				$userCount = $totalCount - $allPost;
				$adminapi[userCount] = "$userCount"; // 수강생이 푼 문제 수
				$adminapi[aTypeEA] = "$aTypeEA";
				$adminapi[bTypeEA] = "$bTypeEA";
				$adminapi[cTypeEA] = "$cTypeEA";
				$adminapi[dTypeEA] = "$dTypeEA";
			}

			$adminapi[status] = "$status";

			$json_encoded = json_encode($adminapi);
			print_r($json_encoded);
?>